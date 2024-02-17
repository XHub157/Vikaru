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

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['user'], $user['id']));
	
// Запрещаем лайкать закрытый дневник

    if ($act['access'] == 0 || $act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $act['access'] == 1 && !empty($friends)) {		
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary_like` WHERE `user`=? AND `diary`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `diary_like` (`user`, `time`, `diary`) VALUES (?, ?, ?);", array($user['id'], time(), $act['id']));
    DB :: $dbh -> query("UPDATE `diary` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));	
	
// Перенаправляем

    header("Location: /modules/diary/$act[id]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `diary_like` WHERE `user`=? AND `diary`=? LIMIT 1;", array($user['id'], $act['id']));
    DB :: $dbh -> query("UPDATE `diary` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/diary/$act[id]");	

    } else {
	
// Перенаправляем

    header("Location: /modules/diary/$act[id]");		
	
    }
	 
	
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/diary/user/".$user['id'].""); }
    } else { $system->redirect("Выбранный вами дневник не существует", "/modules/diary/"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>