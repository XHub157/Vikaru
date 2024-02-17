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

    $title = 'Заявки';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
    echo '
    <div class="hide">
    Заявки в друзья
    </div>	
		<div class="listbar" style="margin-top: -10px;">
    <a href="/modules/friends/user/'.$user['id'].'" class="">Все</a>
    <a href="/modules/friends/new/" class="listbar-act">Заявки</a>
    </div>
    ';

// Подсчёт количества предложений
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends_new` WHERE `profile`=?;", array($user['id']));

// Подсчёт предложений
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим предложения
	
    $q = DB :: $dbh -> query("SELECT * FROM `friends_new` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));

// Выводим Предложение

    while ($act = $q -> fetch()) {
	
    echo '
    <div class="block">
    '.$profile->user($act['user']).' <br />
    <a class="link" href="/modules/friends/yes/'.$act['id'].'">Принять</a> ::
    <a class="link" href="/modules/friends/no/'.$act['id'].'">Отклонить</a>
    </div> 
    ';

    }	
    
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/friends/new/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Заявок нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>