<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $text = new text();

// Подключаем текстовое ядро
	
    $avatar = new avatar();
	
// Выводим шапку

    $title = 'Дневники';

// Инклудим шапку

include_once (ROOT.'template/head.php');

    echo '<div class="hide"><img class="middle" src="/icons/add.png"> <a href="/modules/diary/add_diary/">Добавить дневник</a><br />
    <form method="post" action="/modules/diary/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="where" value="0" />
    <input type="submit" value="Искать" class="submit" /></form>
    - <a href="/modules/diary/search/advanced">Расширенный поиск</a> <br />   
    '.(isset($user) ? '- <a href="/modules/diary/user/'.$user['id'].'">Мой дневник</a>' : '').' 
    </div>';
	
// Сортировка

    if (isset($_GET['sorting']) && $_GET['sorting'] == 1) {
    echo '
<div class="listbar" style="margin-top: -10px;">
    <a href="/modules/diary/?sorting=1&amp;page='.$page.'" class="listbar-act">Популярные</a>
    <a href="/modules/diary/?page='.$page.'" class="">Новые</a>
    </div>';	
    $sorting = '`view`';
    $page_sorting = '1';	
    } else {
    echo '
    <div class="listbar" style="margin-top: -10px;">
    <a href="/modules/diary/?sorting=1&amp;page='.$page.'" class="">Популярные</a>
    <a href="/modules/diary/?page='.$page.'" class="listbar-act">Новые</a>
    </div>';	
    $sorting = '`time`';
    $page_sorting = '0';
    }

// Выводим Дневники
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary`;");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `diary` ORDER BY " . $sorting. " DESC LIMIT " . $page . ", " . $config['post'] . ";");	

// Выводим дневник

    while ($act = $q -> fetch()) {
	
    echo '
	
	<div class="info-block mg-b">
	<a href="/modules/diary/'.$act['id'].'">
    '.$avatar->left_font0($act['user'], 40,40).'
	'.$profile->icons($act['user']).' '.$profile->us($act['user']).' '.$profile->birthday($act['user']).' ::
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'    	
    </span>
    <span style="font-weight: bold;">
    '.$act['name'].'
    </span>
    <br />
	</a>
	<br>
    <span class="color">
    '.($act['censored'] == 1 || $act['access'] > 0 ? 'Доступ закрыт' : ''.$text->number($act['description'], 250).'').' <br />
    </span>
	</div>
    <a href="/modules/diary/'.$act['id'].'" class="info-block-link">Подробнее</a>
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/diary/?sorting='.$page_sorting.'&amp;', $config['post'], $page, $count);	 	
	
// Выводим сообщение если дневников нет	
	
    } else { $system->show("Дневников нет"); }  

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>