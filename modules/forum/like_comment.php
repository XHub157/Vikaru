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
	
// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	

// Запрещаем лайкать комментарии системы

    if ($act['user'] != 0) {
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_comments_like` WHERE `user`=? AND `comments`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `forum_comments_like` (`user`, `time`, `topic`, `comments`) VALUES (?, ?, ?, ?);", array($user['id'], time(), $act['topic'], $act['id']));
    DB :: $dbh -> query("UPDATE `forum_comments` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));		
	
// Перенаправляем

    header("Location: /modules/forum/topic/$act[topic]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_comments_like` WHERE `comments`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
    DB :: $dbh -> query("UPDATE `forum_comments` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/forum/topic/$act[topic]");	

    } else {
	
// Перенаправляем

    header("Location: /modules/forum/topic/$act[topic]");		
	
    }
	 	
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/forum/topic/".$act['topic'].""); }
    } else { $system->redirect("Выбранный вами комментарий не существует", "/modules/forum/"); } 

?>