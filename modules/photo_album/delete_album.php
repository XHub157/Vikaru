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

// Выводим шапку

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим альбом в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_album` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный фотоальбом существует
	
    if (!empty($act)) {	
	
// Проверяем доступ

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {	
	
// Проверяем существуют ли фотоальбомы в фотоальбоме
	
    $album = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `album`=?;", array($act['id']));   

// Только если фотоальбомов нет

    if (empty($album)) { 

// Проверяем фото
	
    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `album`=?;", array($act['id'])); 

// Только если фото нет

    if (empty($photo)) { 	
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_album` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем данные

    if ($act['album'] != 0) { DB :: $dbh -> query("UPDATE `photo_album` SET `albums`=`albums`-1 WHERE `id`=?", array($act['album'])); }	

// Уведомляем

    $system->redirect("Фотоальбом успешно удалён", "".($act['album'] == 0 ? "/modules/photo_album/".$user['id']."" : "/modules/photo_album/album/".$act['album']."")."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/photo_album/album/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/files.png"> 
    <a href="/modules/photo_album/album/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки
 
    } else { $system->show("Данный фотоальбом имеет вложенные фотографии"); }
    } else { $system->show("Данный фотоальбом имеет вложенные фотоальбомы"); }	
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранный вами фотоальбом не существует"); } 	 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>