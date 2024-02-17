<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Подключаем графическое ядро
	
    $photo = new photo();

// Выводим шапку

    $title = 'Фотоальбом';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим фотоальбом в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_album` WHERE `id`=? LIMIT 1;", array($id));
    $album = $queryguest -> fetch();
	
// Только если данный фотоальбом существует
	
    if (!empty($album)) {

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($album['user'], $user['id']));

// Проверяем права доступа

    if ($album['access'] == 0 || $album['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $album['access'] == 1 && !empty($friends)) {

// Выводим описание альбома

    echo '
    '.($album['description'] == NULL ? '
    ' : '
    <div class="block">
    <div class="quote">
    '.$album['description'].'
    </div>
    </div>	
    ').'	
    ';
	
// Делаем подсчёт альбомов только на главной страничке

    if ($page == 0) {	
	
// Подсчёт количества фотоальбомов

    $albums = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `album`=?;", array($album['id']));		
	
    if ($albums > 0) {

// Выводим фотоальбомы
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo_album` WHERE `album`=? ORDER BY `time` DESC  LIMIT 20;", array($album['id']));	
	
// Выводим фотоальбом

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch_white" href="/modules/photo_album/album/'.$act['id'].'">
    <img class="middle" src="/icons/files.png"> 
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'      	
    </span>	
    '.$act['name'].' 
    <span class="left_count">'.$act['albums'].'</span><span class="right_count">'.$act['photos'].'</span>
    </a>
    ';
	
    }}}
	
// Подсчёт количества фото

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `album`=?;", array($album['id']));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим фотографии
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `album`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($album['id']));	
	
// Выводим фото

    while ($act = $q -> fetch()) {	
	
    echo '
    <a class="touch_white" href="/modules/photo_album/photo/'.$act['id'].'">
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    <table><tr><td>
    '.$photo->micro($act['id'], 64, 64, $act['key'], $act['type']).'
    </td><td valign=top><span style="padding-left: 10px;">
    '.$act['name'].'
    </span></td></tr></table>
    </a>';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/photo_album/album/'.$album['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	 
    } else { $system->show("Фотографий нет"); } 
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранный вами фотоальбом не существует"); } 	
	
// Выводим меню

    echo '
    '.($album['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    '.($album['user'] == $user['id'] ? '
    <img class="middle" src="/icons/files.png"> <a href="/modules/photo_album/add_album/'.$album['id'].'">Добавить фотоальбом</a> <br />
    <img class="middle" src="/icons/photo.png"> <a href="/modules/photo_album/add_photo/'.$album['id'].'">Добавить фото</a> <br />
    ' : '').'
    <img class="middle" src="/icons/edit.png"> <a href="/modules/photo_album/edit_album/'.$album['id'].'">Редактировать фотоальбом</a> <br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/photo_album/delete_album/'.$album['id'].'">Удалить фотоальбом</a> <br />	
    </div>	
    ' : '').'
    ';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	