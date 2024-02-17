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
    <a href="/modules/forum/new_topic">Новые темы</a> | 
    <a href="/modules/forum/new_comments" class="link">Новые комментарии</a>
    </div>
    ';
	
// Подсчёт комментариев

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `time`>?;", array(time()-86400 * 1));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим комментарии	
	
    $q = DB :: $dbh -> query("SELECT * FROM `forum_comments` WHERE `time`>? ORDER BY `id` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array(time()-86400 * 1));
	
// Выводим комментарий

    while ($act = $q -> fetch()) {	 
	
// Получаем информацию о теме

    $topic = DB :: $dbh -> queryFetch("SELECT `name` FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($act['topic']));	
	
    echo '<div class="block">
    <img class="middle" src="/icons/topic.png">
    <a href="/modules/forum/topic/'.$act['topic'].'">'.$topic['name'].'</a> :: 
    '.$profile->login($act['user']).'
    <span style="float: right;">
    '.$system->system_time($act['time']).'
    </span> <br />
    '.$text->check($act['comment']).'
    </div>';

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/new_comments/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Новых комментариев нет"); }   	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>