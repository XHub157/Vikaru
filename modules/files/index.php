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

// Выводим поисковый блок

    echo '
    <div class="hide">
    <form method="post" action="/modules/files/search/">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>  
    </div>';

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Делаем подсчёт папок только на главной страничке

    if ($page == 0) {	
	
// Подсчёт количества фотоальбомов

    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `user`=? AND `dir`=?;", array($data['id'], 0));		
	
    if ($dir > 0) {

// Выводим папки
	
    $q = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `user`=? AND `dir`=? ORDER BY `time` DESC  LIMIT 20;", array($data['id'], 0));	
	
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

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `user`=? AND `dir`=?;", array($data['id'], 0));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим файлы
	
    $q = DB :: $dbh -> query("SELECT * FROM `files` WHERE `user`=? AND `dir`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], 0));	
	
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
    $navigation->pages('/modules/files/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	 
    } else { $system->show("Файлов нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	
	
// Выводим меню

    echo ''.($data['id'] == $user['id'] ? '
    <div class="hide">
    <img class="middle" src="/icons/files.png"> <a href="/modules/files/add_dir/">Добавить папку</a> <br />
    <img class="middle" src="/icons/upload.png"> <a href="/modules/files/add_file/">Добавить файл</a> <br />
    </div>' : '').'';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	