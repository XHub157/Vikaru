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
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic_like` WHERE `user`=? AND `topic`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `forum_topic_like` (`user`, `time`, `topic`) VALUES (?, ?, ?);", array($user['id'], time(), $act['id']));	
    DB :: $dbh -> query("UPDATE `forum_topic` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));
	
// Перенаправляем

    header("Location: /modules/forum/topic/$act[id]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_topic_like` WHERE `user`=? AND `topic`=? LIMIT 1;", array($user['id'], $act['id']));
    DB :: $dbh -> query("UPDATE `forum_topic` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/forum/topic/$act[id]");	

    } else {
	
// Перенаправляем

    header("Location: /modules/forum/topic/$act[id]");		
	
    }
	 
	
// Выводим ошибки

    } else { $system->redirect("Выбранная вами тема не существует", "/modules/forum/"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>