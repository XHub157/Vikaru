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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Получаем информацию о файле

    $file = DB :: $dbh -> queryFetch("SELECT `access`, `user` FROM `files` WHERE `id`=? LIMIT 1;", array($act['file']));	
	
// Запрещаем лайкать комментарии в закрытой папке

    if ($file['access'] == 0 || $file['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {		
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_comments_like` WHERE `user`=? AND `comments`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `files_comments_like` (`user`, `time`, `file`, `comments`) VALUES (?, ?, ?, ?);", array($user['id'], time(), $act['file'], $act['id']));
    DB :: $dbh -> query("UPDATE `files_comments` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));		
	
// Перенаправляем

    header("Location: /modules/files/file/$act[file]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `files_comments_like` WHERE `comments`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
    DB :: $dbh -> query("UPDATE `files_comments` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/files/file/$act[file]");

    } else {
	
// Перенаправляем

    header("Location: /modules/files/file/$act[file]");
	
    }
	 
	
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/files/file/".$act['file'].""); }
    } else { $system->redirect("Выбранный вами комментарий не существует", "/modules/files/".$user['id'].""); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>