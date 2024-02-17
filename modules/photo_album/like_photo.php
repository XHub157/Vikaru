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
	
// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данное фото существует
	
    if (!empty($act)) {	

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['user'], $user['id']));	

// Запрещаем лайкать закрытое фото

    if ($act['access'] == 0 || $act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $act['access'] == 1 && !empty($friends)) {		
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_like` WHERE `user`=? AND `photo`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `photo_like` (`user`, `time`, `photo`) VALUES (?, ?, ?);", array($user['id'], time(), $act['id']));
    DB :: $dbh -> query("UPDATE `photo` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));		
	
// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[id]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_like` WHERE `user`=? AND `photo`=? LIMIT 1;", array($user['id'], $act['id']));
    DB :: $dbh -> query("UPDATE `photo` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[id]");	

    } else {
	
// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[id]");	
	
    }
	 
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/photo_album/photo/".$act['id'].""); }
    } else { $system->redirect("Выбранное вами фото не существует", "/modules/photo_album/".$user['id'].""); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>