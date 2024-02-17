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

    $title = 'Поделились';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $topic = $queryguest -> fetch();

// Только если данная тема существует
	
    if (!empty($topic)) {	

    echo '
    <div class="hide">
    Список поделившихся темой <a href="/modules/forum/topic/'.$topic['id'].'">'.$topic['name'].'</a>
    </div>
    ';

// Подчёт количеста поделившихся	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `share`=? AND `section`=?;", array($topic['id'], 1));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `share`=? AND `section`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($topic['id'], 1));   

// Выводим блок

    while ($act = $q -> fetch()) {  

    echo '
    <a class="touch_white" href="/modules/diary/'.$act['id'].'">
    '.$profile->icons($act['user']).' '.$profile->us($act['user']).' :: 
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '<img class="middle" src="/icons/access_all.png" title="Доступен всем">').'    	
    </span>
    <span style="font-weight: bold;">
    '.$act['name'].'
    </span>
    <br />
    <span class="color">
    '.($act['censored'] == 1 || $act['access'] == 1 ? 'Доступ закрыт' : ''.$text->number($act['description'], 250).'').' <br />
    </span>
    Комментариев 
    <span class="count">'.$act['comments'].'</span>
    </a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/topic/share/'.$topic['id'].'/?', $config['post'], $page, $count);		

// Выводим ошибки
	
    } else { $system->show("Нет поделившихся"); }     
    } else { $system->show("Выбранная вами тема не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>