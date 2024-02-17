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

    $title = 'История входов';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {
	
// Ищим пользователя в базе	

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {

// Выводим сообщения 	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `authorization` WHERE `user`=?;", array($data['id']));

// Выводим блок	
	
    echo '
    <div class="hide">
    <span style="font-weight: bold;">История входов</span> '.$profile->login($data['id']).'
    </div>';

// Подсчёт входов	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим входы	
	
    $q = DB :: $dbh -> query("SELECT * FROM `authorization` WHERE `user`=? ORDER BY `time` DESC  LIMIT 6;", array($data['id']));

// Выводим вход

    while ($act = $q -> fetch()) {

    echo '
    <div class="block">
    Дата: '.$system->system_time($act['time']).' <br />
    IP-адрес: '.$act['ip'].' <br />
    Браузер:  '.$act['ua'].'
    </div>';

    }

// Выводим первый вход	
	
    $authorization = DB :: $dbh -> queryFetch("SELECT `time`, `ip`, `ua` FROM `authorization` WHERE `user`=? AND `one`=? LIMIT 1;", array($data['id'], 1));

// Выводим блок	
	
    echo '
    <div class="hide">
    <span style="font-weight: bold;">Самый первый вход</span> <br />
    Дата: '.$system->system_time($authorization['time']).' <br />
    IP-адрес: '.$authorization['ip'].' <br />
    Браузер: '.$authorization['ua'].'
    </div>';    

// Выводим сообщение если входов нет	
	
    } else { $system->show("История входов пуста"); }
    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>