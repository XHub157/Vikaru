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

    $title = 'Лента';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {

// Выводим блок	
	
    echo '
    <div class="hide">
    Здесь показаны новости, которые читает '.$profile->login($data['id']).'
    </div>	
    ';	

// Подсчёт количества ленты	

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user`, `feed` WHERE `feed_user`.`user`='".$data['id']."' AND `feed`.`user`=`feed_user`.`profile`;");

// Выводим ленту
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим ленту
	
    $q = DB :: $dbh -> query("SELECT `feed_user`.*, `feed`.* FROM `feed_user`, `feed` WHERE `feed_user`.`user`='".$data['id']."' AND `feed`.`user`=`feed_user`.`profile` ORDER BY `feed`.`time` DESC LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим ленту

    while ($act = $q -> fetch()) {
	
    if ($act['section'] == 1) {
    $section = 'Новый комментарий';
    } else if ($act['section'] == 2) {
    $section = 'Новая новость';
    } else if ($act['section'] == 3) {
    $section = 'Новая тема';
    } else if ($act['section'] == 4) {
    $section = 'Новй дневник';
    } else if ($act['section'] == 5) {
    $section = 'Новое фото';
    } else if ($act['section'] == 6) {
    $section = 'Новый файл';
    }
	
    echo '
    <a class="touch" href="'.$act['url'].'">
    '.$act['message'].'
    <br />
    <span class="color">
    '.$section.' / '.$profile->us($act['user']).' /
    '.$system->system_time($act['time']).'
    </span>
    </a>';

    }	
    
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/feed/feed_user/'.$data['id'].'/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Новостей нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); }
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>