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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum` WHERE `id`=? LIMIT 1;", array($id));
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

// Подсчёт количества подфорумов
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_section` WHERE `forum`=?;", array($forum['id']));	
	
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
    <span style="font-weight: bold;">Выберите категорию:</span> '.$forum['name'].'
    </div>
    ';	

    }	

    $q = DB :: $dbh -> query("SELECT * FROM `forum_section` WHERE `forum`=? ORDER BY `time`;", array($forum['id']));	
	
// Выводим комменатрий

    while ($act = $q -> fetch()) {	    
	
    echo '
    <a class="touch" href="/modules/forum/section/'.$act['id'].$add.'"><img class="middle" src="/icons/forum_section.png"> '.$act['name'].' 
    <span class="count">'.$act['topics'].'</span>
    <span class="color"> <br />
    '.$act['description'].'
    </span> </a>
    ';

    }  	

// Выводим ошибки
	
    } else { $system->show("Разделов нет"); }   
    } else { $system->show("Выбранный вами раздел не существует"); } 	
	
    echo $user['access'] > 0 && $user['access'] < 4 ? '
    <div class="hide">
    <img class="middle" src="/icons/add.png"> <a href="/modules/forum/add_section/'.$forum['id'].'">Добавить раздел</a><br />
    <img class="middle" src="/icons/edit.png"> <a href="/modules/forum/edit_forum/'.$forum['id'].'">Редактировать раздел</a><br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/forum/delete_forum/'.$forum['id'].'">Удалить раздел</a>
    </div>':'';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>