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

// Содержимое страницы

    echo '<div class="hide">
    <form method="post" action="/modules/forum/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="where" value="0" />
    <input type="submit" value="Искать" class="submit" /> </form>
    - <a href="/modules/forum/search/advanced">Расширенный поиск</a>  
    </div>
    <div class="function">			
    <a href="/modules/forum/" class="link">Разделы</a> |
    <a href="/modules/forum/new_topic">Новые темы</a> | 
    <a href="/modules/forum/new_comments">Новые комментарии</a>
    </div>
    ';
	
// Подсчёт количества разделов
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum`;");	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Премещение темы

    if (isset($_GET['add']) && $user['access'] > 0 && $user['access'] < 4) {
	
// Получаем информацию о теме

    $topic = DB :: $dbh -> queryFetch("SELECT `id`, `section`, `name`  FROM `forum_topic` WHERE `id`=? LIMIT 1;", array(abs(intval($_GET['add']))));	
	
// Выводим название директории

    $section_topic = DB :: $dbh -> queryFetch("SELECT `name` FROM `forum_section` WHERE `id`=? LIMIT 1;", array($topic['section']));

// Выводим блок

    echo '
    <div class="block">
    Перемещение темы 
    <img class="middle" src="/icons/forum_section.png">
    <a href="/modules/forum/section/'.$topic['section'].'">
    '.$section_topic['name'].'</a> /
    <a href="/modules/forum/topic/'.$topic['id'].'">
    '.$topic['name'].'</a> <br />
    <span style="font-weight: bold;">Выберите категорию:</span>
    </div>
    ';	

    }	

    $q = DB :: $dbh -> query("SELECT * FROM `forum` ORDER BY `time`;");	

// Выводим Раздел

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch" href="/modules/forum/'.$act['id'].$add.'"><img class="middle" src="/icons/forum_section.png"> 
    '.$act['name'].' 
    <span class="count">'.$act['sections'].'</span>
    <br />
    <span class="color">
    '.$act['description'].'
    </span></a>
    ';	
    }	
	
// Выводим сообщение если разделов нет	
	
    } else { $system->show("Разделов нет"); }  
	
    echo $user['access'] > 0 && $user['access'] < 4 ? '
    <div class="hide">
    <img class="middle" src="/icons/add.png"> <a href="/modules/forum/add_forum/">Добавить раздел</a>
    </div>':'';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>