<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Подарки';

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
    Подарки '.$profile->login($data['id']).'
    </div>';

// Подсчёт количества подарков
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `gifts_user` WHERE `profile`=?;", array($data['id']));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим подарки	

    $q = DB :: $dbh -> query("SELECT * FROM `gifts_user` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));	
	
// Выводим подарок

    while ($act = $q -> fetch()) {	
	
    if ($act['access'] == 2) {
    $login = 'Неизвестный '.($act['user'] == $user['id'] ? '(Вы)' : '').'';
    } else if ($act['access'] == 1) {
    $login = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$profile->us($act['user']).'' : 'Неизвестный').'';
    } else {
    $login = ''.$profile->us($act['user']).'';
    }

    echo '
    <a class="touch_white" href="/modules/gifts/user/gift/'.$act['id'].'">
    <img class="middle" width="50px;" src="http://'.SERVER_DOMAIN.'/gifts/32/'.$act['gift'].'.png">
    <span style="vertical-align: 8px;">
    '.$login.'
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    </span></span></a>
    ';

    }  	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/gifts/user/'.$data['id'].'/?', $config['post'], $page, $count);	

// Выводим ошибки
	
    } else { $system->show("Подарков нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); }

// Выводим меню

    echo ''.(!empty($data['id']) && $data['id'] != $user['id'] ? '
    <div class="hide">
    <img class="middle" src="/icons/gifts.png">
    <a href="/modules/gifts/?add='.$data['id'].'">Подарить подарок</a>
    </div>' : '').'';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>