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

    $title = 'Мои подписки';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Выводим меню

    echo '
    <div class="hide">
    Мои подписки
    </div>';	
	
// Подсчёт количества подписчиков

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `user`=?;", array($user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим подписчиков

    $q = DB :: $dbh -> query("SELECT * FROM `feed_user` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));  	
	
// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '
    <div class="block">
    '.$profile->user($act['profile']).'
    <span style="float: right;"> 
    <a href="/modules/feed/delete/'.$act['profile'].'">
    <img class="middle" src="/icons/delete.png">
    </a></span>
    </div>';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/feed/my/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Подписчиков нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	