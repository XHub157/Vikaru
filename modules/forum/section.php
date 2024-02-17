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

// Ищим раздел в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_section` WHERE `id`=? LIMIT 1;", array($id));
    $forum = $queryguest -> fetch();

// Только если данный раздел существует
	
    if (!empty($forum)) {

// Выводим меню

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
    '.$forum['name'].' <br />
    <img class="middle" src="/icons/add.png"> <a href="/modules/forum/move_topic/'.$forum['id'].'/?add='.$topic['id'].'">Вставить тему</a>
    </div>
    ';	

    }	
	
// Подсчёт количества тем	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `section`=?;", array($forum['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим темы	
	
    $q = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `section`=? ORDER BY `locked` AND `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($forum['id']));
	
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
    $navigation->pages('/modules/forum/section/'.$forum['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Тем еще нет, будь первым"); }   
    } else { $system->show("Выбранный вами раздел не существует"); } 	
	
// Выводим меню	
	
    echo '<div class="hide">
    <img class="middle" src="/icons/add.png"> <a href="/modules/forum/add_topic/'.$forum['id'].'">Добавить тему</a><br />
    '.($user['access'] > 0 && $user['access'] < 4 ? '
    <img class="middle" src="/icons/edit.png"> <a href="/modules/forum/edit_section/'.$forum['id'].'">Редактировать раздел</a><br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/forum/delete_section/'.$forum['id'].'">Удалить раздел</a>	
    ' : '').'
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>