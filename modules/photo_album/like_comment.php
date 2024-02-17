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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Получаем информацию о фото

    $photo = DB :: $dbh -> queryFetch("SELECT `access`, `user` FROM `photo` WHERE `id`=? LIMIT 1;", array($act['photo']));	
	
// Запрещаем лайкать комментарии в закрытом фотоальбоме

    if ($photo['access'] == 0 || $photo['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {		
	
// Проверяем лайкнул ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_comments_like` WHERE `user`=? AND `comments`=? LIMIT 1;", array($user['id'], $act['id']));
    $like = $queryguest -> fetch();

    if (empty($like)) {	
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `photo_comments_like` (`user`, `time`, `photo`, `comments`) VALUES (?, ?, ?, ?);", array($user['id'], time(), $act['photo'], $act['id']));
    DB :: $dbh -> query("UPDATE `photo_comments` SET `like`=`like`+1 WHERE `id`=?", array($act['id']));		
	
// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[photo]");	
	
    } else if (!empty($like)) {	
	
// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_comments_like` WHERE `comments`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
    DB :: $dbh -> query("UPDATE `photo_comments` SET `like`=`like`-1 WHERE `id`=?", array($act['id']));

// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[photo]");

    } else {
	
// Перенаправляем

    header("Location: /modules/photo_album/photo/$act[photo]");
	
    }
	 
	
// Выводим ошибки

    } else { $system->redirect("Отказано в доступе", "/modules/photo_album/photo/".$act['photo'].""); }
    } else { $system->redirect("Выбранный вами комментарий не существует", "/modules/photo_album/".$user['id'].""); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>