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
	
// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная тема существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев за 60 секунд
	
    if ($antiflood < $config['antiflood_creation']) {	

// Запрещаем комментировать закрытую тему

    if ($act['closed'] == 0) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `forum_comments` (`topic`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $user['id'], $comment, time(), $system->ip(), $system->ua()));	
	
// Обновляем информацию в теме

    DB :: $dbh -> query("UPDATE `forum_topic` SET `last_user`=? WHERE `id`=? LIMIT 1;", array($user['id'], $act['id']));

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `forum_topic` SET `comments`=`comments`+1 WHERE `id`=?", array($act['id']));

// Уведомление в журнал

    if ($act['user'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/forum/topic/".$act['id']."", "".$act['user']."", "3");	

    }	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($act['name'], 0, 50)."", "/modules/forum/topic/".$act['id']."", "1");	

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/forum/topic/".$act['id']."");	

// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинный или короткий комментарий", "/modules/forum/topic/".$act['id'].""); } 
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/forum/topic/".$act['id'].""); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/forum/topic/".$act['id'].""); }	
    } else { $system->redirect("Отказано в доступе", "/modules/forum/topic/".$act['id'].""); } 
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/forum/topic/".$act['id'].""); }	
    } else { $system->redirect("Выбраная вами тема не существует", "/modules/forum/"); }	

?>