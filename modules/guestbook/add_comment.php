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
	
// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($act)) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев зв 60 секунд
	
    if ($antiflood < $config['antiflood_creation']) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	

// Запрещаем комментировать закрытую гостевую

    if ($act['access_guestbook'] == 0 || $act['id'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $act['access_guestbook'] == 1 && !empty($friends)) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `guestbook_comments` (`profile`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $user['id'], $comment, time(), $system->ip(), $system->ua()));

// Уведомление в журнал

    if ($act['id'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($comment, 0, 50)."", "/modules/guestbook/".$act['id']."", "".$act['id']."", "1");	

    }

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($comment, 0, 50)."", "/modules/guestbook/".$act['id']."", "1");	

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/guestbook/".$act['id']."");	

// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинный или короткий комментарий", "/modules/guestbook/".$act['id'].""); } 
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/guestbook/".$act['id'].""); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/guestbook/".$act['id'].""); }	
    } else { $system->redirect("Отказано в доступе", "/modules/guestbook/".$act['id'].""); } 
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/guestbook/".$act['id'].""); }	
    } else { $system->redirect("Выбраная вами гостевая не существует", "/modules/guestbook/".$user['id'].""); }	

?>