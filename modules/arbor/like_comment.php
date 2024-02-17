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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `arbor` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `arbor_comments_like` WHERE `user`=? AND `comments`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `arbor_comments_like` (`user`, `time`, `comments`) VALUES (?, ?, ?);", array($user['id'], time(), $act['id']));
    DB :: $dbh -> query("UPDATE `arbor` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));	
	
// Перенаправляем

    header("Location: /modules/arbor/");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `arbor_comments_like` WHERE `comments`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
    DB :: $dbh -> query("UPDATE `arbor` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/arbor/");	

    } else {
	
// Перенаправляем

    header("Location: /modules/arbor/");		
	
    }
	 
	
// Выводим ошибки

    } else { $system->redirect("Выбранный вами комментарий не существует", "/modules/arbor/"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>