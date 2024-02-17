<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем файловое ядро

    $files = new files();

// Выводим шапку

    $title = 'Файлы';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `id`=? LIMIT 1;", array($id));
    $dir = $queryguest -> fetch();
	
// Только если данная папка существует
	
    if (!empty($dir)) {

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($dir['user'], $user['id']));

// Проверяем права доступа

    if ($dir['access'] == 0 || $dir['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $dir['access'] == 1 && !empty($friends)) {

// Выводим описание папки

    echo '
    '.($dir['description'] == NULL ? '
    ' : '
    <div class="block">
    <div class="quote">
    '.$dir['description'].'
    </div>
    </div>	
    ').'	
    ';
	
// Делаем подсчёт папок только на главной страничке

    if ($page == 0) {	
	
// Подсчёт количества папок

    $dirs = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `dir`=?;", array($dir['id']));		
	
    if ($dirs > 0) {

// Выводим папки
	
    $q = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `dir`=? ORDER BY `time` DESC  LIMIT 20;", array($dir['id']));	
	
// Выводим папку

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch_white" href="/modules/files/dir/'.$act['id'].'">
    <img class="middle" src="/icons/files.png">
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'      	
    </span>	
    '.$act['name'].' 
    <span class="left_count">'.$act['dirs'].'</span><span class="right_count">'.$act['files'].'</span>
    </a>
    ';
	
    }}}
	
// Подсчёт количества файлов

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `dir`=?;", array($dir['id']));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим файлы
	
    $q = DB :: $dbh -> query("SELECT * FROM `files` WHERE `dir`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($dir['id']));	
	
// Выводим файл

    while ($act = $q -> fetch()) {	
	
    echo '
    <a class="touch_white" href="/modules/files/file/'.$act['id'].'">
    '.$files->type($act['type']).'
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>	
    '.$act['name'].'.'.$act['type'].'
    </a>
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/files/dir/'.$dir['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	 
    } else { $system->show("Файлов нет"); } 
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранная вами папка не существует"); } 	
	
// Выводим меню

    echo '
    '.($dir['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    '.($dir['user'] == $user['id'] ? '
    <img class="middle" src="/icons/files.png"> <a href="/modules/files/add_dir/'.$dir['id'].'">Добавить папку</a> <br />
    <img class="middle" src="/icons/upload.png"> <a href="/modules/files/add_file/'.$dir['id'].'">Добавить файл</a> <br />
    ' : '').'
    <img class="middle" src="/icons/edit.png"> <a href="/modules/files/edit_dir/'.$dir['id'].'">Редактировать папку</a> <br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/files/delete_dir/'.$dir['id'].'">Удалить папку</a> <br />	 
    </div>
    ' : '').'
    ';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	