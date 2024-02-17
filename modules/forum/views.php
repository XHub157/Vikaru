<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Просмотры';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $topic = $queryguest -> fetch();

// Только если данная тема существует
	
    if (!empty($topic)) {

    echo '
    <div class="hide">
    Список посмотревших тему <a href="/modules/forum/topic/'.$topic['id'].'">'.$topic['name'].'</a>
    </div>
    ';

// Подчёт количеста просмотревших	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic_view` WHERE `topic`=?;", array($topic['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим просмотры	
	
    $q = DB :: $dbh -> query("SELECT * FROM `forum_topic_view` WHERE `topic`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($topic['id']));   

// Выводим блок

    while ($act = $q -> fetch()) {     

    echo '
    <div class="block">
    '.$profile->user($act['user']).' 
    <span style="float: right;">
    '.$system->system_time($act['time']).'
    </span>
    </div>
    ';

    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/views/'.$topic['id'].'/?', $config['post'], $page, $count);	
	
// Выводим ошибки
	
    } else { $system->show("Нет просмотров"); }      
    } else { $system->show("Выбранная вами тема не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>