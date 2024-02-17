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

    $title = 'Зона Обмена';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим поисковый блок

    echo '
    <div class="hide">
    <form method="post" action="/modules/files/search/">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>  
    </div>';

// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `shared_zone` WHERE `id`=? LIMIT 1;", array($id));
    $dir = $queryguest -> fetch();
	
// Только если данная папка существует
	
    if (!empty($dir)) {
	
// Загрузка файлов

    if (isset($_GET['add'])) {
	
// Получаем информацию о файле

    $file = DB :: $dbh -> queryFetch("SELECT `id`, `dir`, `user`, `access`, `name`, `type`  FROM `files` WHERE `id`=? LIMIT 1;", array(abs(intval($_GET['add']))));	

// Проверяем доступ

    if ($file['user'] == $user['id'] && $file['access'] == 0) {
	
// Выводим название директории

    $dir_file = DB :: $dbh -> queryFetch("SELECT `name` FROM `files_dir` WHERE `id`=? LIMIT 1;", array($file['dir']));	

// Выводим блок

    echo '
    <div class="advertising">
    Внимание! Перед добавлением файла в Зону обмена внимательно <br />
    прочитайте <a href="/modules/reference/">Правила Зоны обмена</a>. <br />
    За нарушение этих правил, ваша страница может быть заблокирована <br />
    временно или навсегда.
    </div>
    <div class="block">
    Перемещение файла <img class="middle" src="/icons/files.png">
    '.($file['dir'] == 0 ? '<a href="/modules/files/'.$file['user'].'">Файлы</a>
    ' : '<a href="/modules/files/dir/'.$file['dir'].'">'.$dir_file['name'].'</a>').' / 
    '.$files->type($file['type']).' <a href="/modules/files/file/'.$file['id'].'">'.$file['name'].'</a>.'.$file['type'].' <br />
    <span style="font-weight: bold;">Выберите категорию:</span> '.$dir['name'].' <br />
    </div>
    '.($dir['upload'] == 1 ? '
    <a class="touch" href="/modules/shared_zone/upload/'.$dir['id'].'/?add='.$file['id'].'">
    <img class="middle" src="/icons/upload.png">
    Добавить файл
    </a>
    ' : '').'
    ';
	
    }}	
	
// Делаем подсчёт папок только на главной страничке

    if ($page == 0) {	
	
// Подсчёт количества папок

    $dirs = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `dir`=?;", array($dir['id']));		
	
    if ($dirs > 0) {

// Выводим папки
	
    $q = DB :: $dbh -> query("SELECT * FROM `shared_zone` WHERE `dir`=? ORDER BY `time` ASC  LIMIT 30;", array($dir['id']));	
	
// Выводим папку

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch_white" href="/modules/shared_zone/dir/'.$act['id'].$add.'">
    <img class="middle" src="/icons/files.png"> '.$act['name'].'
    <span class="left_count">'.$act['dirs'].'</span><span class="right_count">'.$act['files'].'</span>
    <span style="float: right;">
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    </a>';
	
    }}}
	
// Подсчёт количества файлов

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `shared_zone`=?;", array($dir['id']));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим файлы
	
    $q = DB :: $dbh -> query("SELECT * FROM `files` WHERE `shared_zone`=? ORDER BY `shared_time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($dir['id']));	
	
// Выводим файл

    while ($act = $q -> fetch()) {	
	
    echo '
    <a class="touch_white" href="/modules/files/file/'.$act['id'].'">
    '.$files->type($act['type']).'
    '.$act['name'].'.'.$act['type'].'
    <span class="color" style="float: right;">
    '.$system->system_time($act['shared_time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    </a>
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/shared_zone/dir/'.$dir['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	 
    } else { $system->show("Файлов нет"); } 
    } else { $system->show("Выбранная вами папка не существует"); } 	
	
// Выводим меню

    if (isset($user)) {
    echo '
    '.($user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    <img class="middle" src="/icons/files.png"> <a href="/modules/shared_zone/add_dir/'.$dir['id'].'">Добавить папку</a> <br />
    <img class="middle" src="/icons/edit.png"> <a href="/modules/shared_zone/edit_dir/'.$dir['id'].'">Редактировать папку</a> <br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/shared_zone/delete_dir/'.$dir['id'].'">Удалить папку</a> <br />	
    </div>
    ' : '').'
    ';	
}
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	