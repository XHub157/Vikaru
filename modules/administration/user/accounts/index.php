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

    $title = 'Возможные аккаунты';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 5) {	

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {

// Выводим меню

    echo '
    <div class="hide">
    <span style="font-weight: bold;">Возможные аккаунты</span> '.$profile->login($data['id']).' <br />
    IP: '.$data['ip'].' <br />
    Браузер: '.$data['ua'].' <br />	
    </div>';

// Подсчёт количества аккаунтов
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `ip`=? AND `id`!=?;", array($data['ip'], $data['id']));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим аккаунты

    $q = DB :: $dbh -> query("SELECT * FROM `user` WHERE `ip`=? AND `id`!=? ORDER BY `date_aut` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['ip'], $data['id']));	
	
// Выводим пользователя

    while ($act = $q -> fetch()) {	

    echo '
    <div class="block">
    '.$profile->user($act['id']).' 
    <span style="float: right;">
    '.$system->system_time($act['date_reg']).'
    </span> <br />
    IP: '.$act['ip'].' <br />
    Браузер: '.($act['ua'] == $data['ua'] ? '<span style="color: #FF0000;">'.$act['ua'].'</span>
    ' : ''.$act['ua'].'').' <br />
    </div>
    ';

    }  	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/administration/user/accounts/'.$data['id'].'/?', $config['post'], $page, $count);	

// Выводим ошибки
	
    } else { $system->show("Аккаунтов нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>