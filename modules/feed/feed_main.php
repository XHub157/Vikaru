<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Лента';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Выводим блок	
	
    echo '<div class="hide">
    С помощью ленты вы можете отслеживать всю активность ваших друзей на '.DOMAIN.'
    </div>
    <div class="function">
    <a href="/modules/feed/feed_main/0/?page='.$page.'"'.(empty($id) || $id == 0 || $id > 6 ? '' : 'class="link"').'>Все</a> |
    <a href="/modules/feed/feed_main/1/?page='.$page.'"'.($id == 1 ? '' : 'class="link"').'>Комментарии</a> |
    <a href="/modules/feed/feed_main/2/?page='.$page.'"'.($id == 2 ? '' : 'class="link"').'>Новости</a> |
    <a href="/modules/feed/feed_main/3/?page='.$page.'"'.($id == 3 ? '' : 'class="link"').'>Форум</a> |
    <a href="/modules/feed/feed_main/4/?page='.$page.'"'.($id == 4 ? '' : 'class="link"').'>Дневники</a> |
    <a href="/modules/feed/feed_main/5/?page='.$page.'"'.($id == 5 ? '' : 'class="link"').'>Фото</a> |
    <a href="/modules/feed/feed_main/6/?page='.$page.'"'.($id == 6 ? '' : 'class="link"').'>Файлы</a>	
    </div>	
    ';
	
// Обработка сортировки
    
    $sorting = ($id >= 0 && $id < 7) ? "`section`".($id == 0 ? '>=0' : '='.$id.'')."" : "`section`>'0'";	

// Подсчёт количества ленты	

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed` WHERE ".$sorting.";");

// Выводим ленту
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим ленту
	
    $q = DB :: $dbh -> query("SELECT * FROM `feed` WHERE ".$sorting." ORDER BY `time` DESC LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим ленту

    while ($act = $q -> fetch()) {
	
    if ($act['section'] == 1) {
    $section = 'Новый комментарий';
    } else if ($act['section'] == 2) {
    $section = 'Новая новость';
    } else if ($act['section'] == 3) {
    $section = 'Новая тема';
    } else if ($act['section'] == 4) {
    $section = 'Новй дневник';
    } else if ($act['section'] == 5) {
    $section = 'Новое фото';
    } else if ($act['section'] == 6) {
    $section = 'Новый файл';
    }
	
    echo '
	<div class="info-block mg-b">
    <a  href="'.$act['url'].'">
	'.$avatar->left_font0($act['user'], 40,40).'
    '.$act['message'].'
    <br />
    <span class="color">
    '.$section.' / '.$profile->us($act['user']).' /
    '.$system->system_time($act['time']).'
    </span>
    </a></div>
	';
    }	
    
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/feed/feed_main/'.$id.'/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Уведомлений нет"); } 

// Выводим меню

    echo '
    <div class="hide">
    <a href="/modules/feed/">
    <img class="middle" src="/icons/home.png">
    Моя лента </a>    
    </div>';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>