<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Только для зарегистрированых

    $profile->access(true);	

// Выводим шапку

    $title = 'Удаление';
	
// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
    echo '
    <div class="hide">
    Удаление
    </div>	
	<div class="listbar" style="margin-top: -10px;">
    <a href="/modules/friends/user/'.$user['id'].'" class="">Все</a>
    <a href="/modules/friends/delete/" class="listbar-act">Удаление</a>
    </div>
    ';
	
// Подсчёт количества подписчиков

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `profile`=?;", array($user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим подписчиков

    $q = DB :: $dbh -> query("SELECT * FROM `friends` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));  	
	
// Выводим 

    while ($act = $q -> fetch()) {

    echo '
    <div class="info-block1" style="margin-bottom: 0px;">
    <span class="right"> <a href="/modules/friends/delete/'.$act['user'].'"><img class="middle" src="/icons/delete.png"> <br /> </span>
    </span>	
    '.$profile->user($act['user']).'
    </div>	
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/friends/delete/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Друзей нет"); } 
	
// Выводим меню

    echo '
    <div  class="hide">
    <img class="middle" src="/icons/feed_user.png">
    <a href="/modules/feed/user/'.$user['id'].'"> Подписчики </a>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	