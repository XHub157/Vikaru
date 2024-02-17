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
	
// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный дневник существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев за 60 секунд	
	
    if ($antiflood < $config['antiflood_creation']) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['user'], $user['id']));	

// Запрещаем комментировать закрытый дневник

    if ($act['comment'] == 0 && $act['access'] == 0 || $act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $act['access'] == 1 ||  $act['comment'] == 1 && !empty($friends)) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `diary_comments` (`diary`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $user['id'], $comment, time(), $system->ip(), $system->ua()));

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `diary` SET `comments`=`comments`+1 WHERE `id`=?", array($act['id']));	
	
// Уведомление в журнал

    if ($act['user'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/diary/".$act['id']."", "".$act['user']."", "4");	

    }	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/diary/".$act['id']."", "1");

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/diary/".$act['id']."");	

// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинный или короткий комментарий", "/modules/diary/".$act['id'].""); } 
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/diary/".$act['id'].""); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/diary/".$act['id'].""); }	
    } else { $system->redirect("Отказано в доступе", "/modules/diary/".$act['id'].""); } 
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/diary/".$act['id'].""); }	
    } else { $system->redirect("Выбранный вами дневник не существует", "/modules/diary/"); }	

?>