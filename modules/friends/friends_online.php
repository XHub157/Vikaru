<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Онлайн';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подключаем текстовое ядро
	
    $text = new text();

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Выводим меню

    echo '
    <div class="hide">
    Друзья '.$profile->login($data['id']).'
    </div>
	<div class="listbar" style="margin-top: -10px;">
    <a href="/modules/friends/user/'.$data['id'].'" class="">Все</a>
    <a href="/modules/friends/online/'.$data['id'].'" class="listbar-act">Онлайн</a>
    </div>';
	
// Подсчёт количества друзей онлайн

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`aut`>?;", array($data['id'], time()-60));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим друзей онлайн

    $q = DB :: $dbh -> query("SELECT `friends`.*, `user`.* FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`aut`>? ORDER BY `user`.`aut` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], time()-60)); 	
	
// Выводим пользователя

    while ($act = $q -> fetch()) {

    echo '
    <div class="info-block1" style="margin-bottom: 0px;">
					'.($act['id'] == $user['id'] ? '
	' : '
    <span class="right"><a href="/modules/mail/contact/'.$act['id'].'">Написать сообщение</a> <br /> </span>
     ').'
    </span>
	'.$avatar->left_font0($act['user'], 40,40).'		
    '.$profile->user($act['user']).'
	'.($act['city'] != NULL && $act['region'] != NULL && $act['country'] != NULL ? '
    <br /><br /><span class="count_zxc">'.$act['city'].'</span> <span class="count_zxc">'.$act['region'].'</span> <span class="count_zxc">'.$act['country'].'</span>' : '').'
	<div class="user__status-wrap">   <div class="user__status">'.($act['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$act['hello'].'').'</div></div>
    </div>	
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/friends/online/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Друзей нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	