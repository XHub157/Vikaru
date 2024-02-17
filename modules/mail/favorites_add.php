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
	
// Ищим сообщение в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данное сообщение существует
	
    if (!empty($act)) {	
	
// Проверяем доступ

    if ($act['user'] == $user['id'] || $act['profile'] == $user['id']) {		
	
// Ищим сообщение в избранном

    $queryguest = DB :: $dbh -> query("SELECT * FROM `mail_favorites` WHERE `last_id`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
    $favorites = $queryguest -> fetch();	

    if (empty($favorites)) {	
	
// Добавляем сообщение в избранное

    DB :: $dbh -> query("INSERT INTO `mail_favorites` (`last_id`, `user`, `message`, `last_user`, `last_profile`, `time`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $user['id'], $act['message'], $act['user'], $act['profile'], $act['time']));
	
// Уведомляем

    $system->redirect("Сообщение успешно добавлено в избранное", "".($act['user'] == $user['id'] ? "/modules/mail/contact/".$act['profile']."" : "/modules/mail/contact/".$act['user']."")."");	
	
    } else {
	
    DB :: $dbh -> query("DELETE FROM `mail_favorites` WHERE `last_id`=? AND `user`=?;", array($act['id'], $user['id']));
	
// Уведомляем

    $system->redirect("Сообщение успешно удалено из избранного", "".($act['user'] == $user['id'] ? "/modules/mail/contact/".$act['profile']."" : "/modules/mail/contact/".$act['user']."")."");	
	
    }
	 	
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/mail/"); }
    } else { $system->redirect("Выбранное вами сообщение не существует", "/modules/mail/"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>