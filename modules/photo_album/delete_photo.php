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
	
// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данное фото существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Удаляем файлы из сервера

    @unlink(SERVER."/photo/64/photo$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/photo/128/photo$act[id]_$act[key].$act[type]");	
    @unlink(SERVER."/photo/256/photo$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/photo/photo$act[id]_$act[key].$act[type]");
	
// Удаляем лайки комментариев из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_comments_like` WHERE `photo`=?;", array($act['id']));	
	
// Удаляем комментарии из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_comments` WHERE `photo`=?;", array($act['id']));	

// Удаляем лайки темы

    DB :: $dbh -> query("DELETE FROM `photo_like` WHERE `photo`=?;", array($act['id']));	

// Удаляем просмотры из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_view` WHERE `photo`=?;", array($act['id']));	
	
// Удаляем закачки из базы
	
    DB :: $dbh -> query("DELETE FROM `photo_download` WHERE `photo`=?;", array($act['id']));	

// Удаляем тему из базы
	
    DB :: $dbh -> query("DELETE FROM `photo` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем данные

    if ($act['album'] != 0) { DB :: $dbh -> query("UPDATE `photo_album` SET `photos`=`photos`-1 WHERE `id`=?", array($act['album'])); }	

// Уведомляем

    $system->redirect("Фото успешно удалено", "".($act['album'] == 0 ? "/modules/photo_album/".$user['id']."" : "/modules/photo_album/album/".$act['album']."")."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/photo_album/photo/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/photo.png"> 
    <a href="/modules/photo_album/photo/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранное вами фото не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>