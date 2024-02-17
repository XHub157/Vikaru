<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Только для зарегистрированых

    $profile->access(true);	
	
// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный файл существует
	
    if (!empty($act)) {	   
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев зв 1 минуту	
	
    if ($antiflood < $config['antiflood_creation']) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['user'], $user['id']));	

// Запрещаем комментировать закрытый дневник

    if ($act['access'] == 0 || $act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $act['access'] == 1 && !empty($friends)) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `files_comments` (`file`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $user['id'], $comment, time(), $system->ip(), $system->ua()));	

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `files` SET `comments`=`comments`+1 WHERE `id`=?", array($act['id']));
	
// Уведомление в журнал

    if ($act['user'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/files/file/".$act['id']."", "".$act['user']."", "6");	

    }	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/files/file/".$act['id']."", "1");	

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/files/file/".$act['id']."");	

// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинный или короткий комментарий", "/modules/files/file/".$act['id'].""); } 
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/files/file/".$act['id'].""); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/files/file/".$act['id'].""); }	
    } else { $system->redirect("Отказано в доступе", "/modules/files/file/".$act['id'].""); } 
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/files/file/".$act['id'].""); }	
    } else { $system->redirect("Выбранный вами файл не существует", "/modules/files/".$user['id'].""); }	

?>