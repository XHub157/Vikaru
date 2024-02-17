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
    <span style="font-weight: bold;">Выберите категорию:</span>
    </div>';
	
    }}	
	
// Подсчёт количества разделов

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `dir`=?;", array(0));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `shared_zone` WHERE `dir`=? ORDER BY `time` ASC  LIMIT 20;", array(0));	

// Выводим раздел

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch_white" href="/modules/shared_zone/dir/'.$act['id'].$add.'">
    <img class="middle" src="/icons/files.png"> 
    '.$act['name'].'
    <span class="left_count">'.$act['dirs'].'</span><span class="right_count">'.$act['files'].'</span>
    <span class="color" style="float: right;">
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    </a>';
	
    }	
	
// Выводим сообщение если разделов нет	
	
    } else { $system->show("Разделов нет"); }  
	
    echo '<div class="hide">
    Все файлы в этом разделе являются собственностью <br />
    пользователей '.DOMAIN.'. Администрация сайта не несет <br />
    ответственности за содержимое файлов.
    </div>
    <div class="hide">
    <img class="middle" src="/icons/reference.png"> <a href="/modules/reference/">Как добавить файл?</a> <br />';
    
    if (isset($user)) {
    echo '
    '.($user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <img class="middle" src="/icons/add.png"> <a href="/modules/shared_zone/add_dir/">Добавить раздел</a>
    ' : '').'
    ';
}

echo '</div>';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>