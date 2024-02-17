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

// Выводим сообщения 	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `authorization` WHERE `user`=? AND `one`=?;", array($user['id'], 0));

// Выводим блок	
	
    echo '<div class="hide">
    <span style="font-weight: bold;">История входов </span><br />
    <span style="font-size: 11px;">Здесь вы сможете проверить, не заходил ли кто-то чужой под вашим логином <br />
    Если Вы подозреваете, что кто-то получил доступ к Вашему профилю, <br /> Вы можете в любой момент прекратить эту активность.
    </span>
    </div>';

// Подсчёт входов	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим входы	
	
    $q = DB :: $dbh -> query("SELECT * FROM `authorization` WHERE `user`=? AND `one`=? ORDER BY `time` DESC LIMIT 6;", array($user['id'], 0));

// Выводим вход

    while ($act = $q -> fetch()) {
	
    if ($count > 6) {
    DB :: $dbh -> query("DELETE FROM `authorization` WHERE `id`<? AND `user`=? AND `one`=?;",array($act['id']-4, $user['id'], 0));	
    }

    echo '
    <div class="block">
    Дата: '.$system->system_time($act['time']).' <br />
    IP-адрес: '.$act['ip'].' <br />
    Браузер:  '.$act['ua'].'
    </div>';

    }

// Выводим первый вход	
	
    $authorization = DB :: $dbh -> queryFetch("SELECT `time`, `ip`, `ua` FROM `authorization` WHERE `user`=? AND `one`=? LIMIT 1;", array($user['id'], 1));

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

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>