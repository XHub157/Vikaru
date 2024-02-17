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

// Выводим шапку

    $title = 'Дневник';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Содержимое страници

    echo '<div class="hide"><img class="middle" src="/icons/add.png"> <a href="/modules/diary/add_diary/">Добавить дневник</a> <br />
    <form method="post" action="/modules/diary/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="where" value="0" />
    <input type="submit" value="Искать" class="submit" /></form>
    - <a href="/modules/diary/search/advanced">Расширенный поиск</a>    
    </div>';

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Подсчёт количества дневников

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `user`=?;", array($data['id']));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Сортировка

    if (isset($_GET['sorting']) && $_GET['sorting'] == 1) {
    echo '
    <div class="function">
    <a href="/modules/diary/user/'.$data['id'].'/?sorting=1&amp;page='.$page.'" class="link">Популярные</a> |
    <a href="/modules/diary/user/'.$data['id'].'/?page='.$page.'">Новые</a>
    </div>';	
    $sorting = '`view`';
    $page_sorting = '1';	
    } else {
    echo '
    <div class="function">
    <a href="/modules/diary/user/'.$data['id'].'/?page='.$page.'" class="link">Новые</a> |
    <a href="/modules/diary/user/'.$data['id'].'/?sorting=1&amp;page='.$page.'">Популярные</a>
    </div>';	
    $sorting = '`time`';
    $page_sorting = '0';
    }

// Выводим дневники
	
    $q = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `user`=? ORDER BY " . $sorting. " DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));	
	
// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch_white" href="/modules/diary/'.$act['id'].'">
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
    <span class="color">
    '.($act['censored'] == 1 || $act['access'] > 0 ? 'Доступ закрыт' : ''.$text->number($act['description'], 250).'').' <br />
    </span>
    Комментариев 
    <span class="count">'.$act['comments'].'</span>
    </a>
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/diary/user/'.$data['id'].'/?sorting='.$page_sorting.'&amp;', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Дневников нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	