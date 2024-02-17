<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Музыка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подключаем текстовое ядро
	
    $avatar = new avatar();
	
// Подключаем текстовое ядро
	
    $text = new text();
	
// Выводим блок	
	
    echo '
    <div class="hide">
    <form method="post" action="/modules/audio/search/">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>  
    </div>
	
    <div class="listbar" style="margin-top: -10px;">
    <a href="/modules/audio/0/?page='.$page.'"'.(empty($id) || $id == 0 || $id > 4 ? 'class="listbar-act"' : '').'>Топ рейтинга</a> 
    <a href="/modules/audio/1/?page='.$page.'"'.($id == 1 ? 'class="listbar-act"' : '').'>Популярное</a> 
    <a href="/modules/audio/2/?page='.$page.'"'.($id == 2 ? 'class="listbar-act"' : '').'>Новое</a> 
    <a href="/modules/audio/3/?page='.$page.'"'.($id == 3 ? 'class="listbar-act"' : '').'>Обсуждаемое</a> 
    <a href="/modules/audio/4/?page='.$page.'"'.($id == 4 ? 'class="listbar-act"' : '').'>Скачанное</a> 
    </div>	
    ';	
	
// Сортировка

    if ($id == 1) {
    $sorting = '`like`';
    } if ($id == 2) {
    $sorting = '`time`';
    } else if ($id == 3) {
    $sorting = '`comments`';
    } else if ($id == 4) {
    $sorting = '`download`';	
    } else if (empty($id) || $id == 0) {
    $sorting = '`view`';
    }	

// Подсчёт музыкальных файлов
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `section`=?;", array('audio'));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;	
	
// Выводим музыкальные файлы
	
    $q = DB :: $dbh -> query("SELECT * FROM `files` WHERE `section`=? ORDER BY ".$sorting." DESC  LIMIT " . $page . ", " . $config['post'] . ";", array('audio'));	
	
// Выводим музыкальный файл

    while ($act = $q -> fetch()) {
	
    ++$i;
    $number = ($page + $i);
	
    $today = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_view` WHERE `file`=? AND `time`>?;", array($act['id'], time()-86400 * 1));
    $yesterday = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_view` WHERE `file`=? AND `time`>? AND `time`<?;", array($act['id'], time()-86400 * 3, time()-86400 * 2));
    $rating = ($today > $yesterday) ? '
    &uarr; <span style="color: #009933;">'.$today.'</span>
    ' : '
    &darr; <span style="color: #FF0000;">'.$today.'</span>
    ';
	
    echo '
	<div class="info-block mg-b">
	<b>'.$number.'.</b>
    <a href="/modules/files/file/'.$act['id'].'">
	    '.$avatar->left_font0($act['user'], 40,40).'
     <img class="middle" src="/icons/type/audio.png">
    <span class="color" style="float: right;">
    '.$rating.'
    </span>	
    '.$act['name'].'.'.$act['type'].'
	</a>
	<br><br>
	'.($act['description'] == NULL ? '<span class="count_zxc">Описание: Отсутствует</span>' : '<span class="count_zxc">Описание: '.$text->check($act['description']).'</span> ').'
	</div>
		
    ';

    } 
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/audio/'.$id.'/?', $config['post'], $page, $count);	
	 
// Выводим ошибки	
	
    } else { $system->show("Композиций нет"); } 
	
// Выводим меню

    echo '
    <div class="show">
    Все файлы в этом разделе являются собственностью <br />
    пользователей '.DOMAIN.'. Администрация сайта не несет <br />
    ответственности за содержимое файлов.
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>