<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Форум';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим меню

    echo '<div class="hide">
    <form method="post" action="/modules/forum/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="where" value="0" />
    <input type="submit" value="Искать" class="submit" /> </form>
    - <a href="/modules/forum/search/advanced">Расширенный поиск</a>
    </div>
    <div class="function">			
    <a href="/modules/forum/"> Разделы </a> |
    <a href="/modules/forum/new_topic" class="link">Новые темы</a> | 
    <a href="/modules/forum/new_comments">Новые комментарии</a>
    </div>
    ';
	
// Подсчёт тем
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `time`>?;", array(time()-86400 * 1));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим темы	
	
    $q = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `time`>? ORDER BY `id` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array(time()-86400 * 1));
	
// Выводим тему

    while ($act = $q -> fetch()) {	

    echo '
    <a class="touch_white" href="/modules/forum/topic/'.$act['id'].'">
    '.($act['locked'] > 0 ? '
    <img class="middle" src="/icons/locked.png">
    ' : '<img class="middle" src="/icons/topic.png">').'
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).' 
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    '.$act['name'].' 
    '.($act['closed'] > 0 ? '<img class="middle" src="/icons/access_me.png">' : '').'
    <br />
    '.$profile->us($act['user']).' / '.$profile->us($act['last_user']).' <br />
    Комментариев <span class="count">'.$act['comments'].'</span>
    </a>
    ';

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/new_topic/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Новых тем нет"); }   	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>