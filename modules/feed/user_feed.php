<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Подписчики';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Выводим меню

    echo '
    <div class="hide">
    Подписчики '.$profile->login($data['id']).'
    </div>
    ';	
	
// Подсчёт количества подписчиков

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `profile`=?;", array($data['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим подписчиков

    $q = DB :: $dbh -> query("SELECT * FROM `feed_user` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));  	
	
// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '
    <div class="block">
    '.$profile->user($act['user']).'
    <span style="float: right;margin-bottom: 2px;display: inline-block;background: #916D97;padding: 3px 8px 3px 8px;border-radius: 6px;color: white;">
    '.$system->system_time($act['time']).'
    </span>
    </div>	
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/feed/user/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Подписчиков нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	