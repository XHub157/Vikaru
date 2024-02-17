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

    $title = 'Журнал операций';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {
	
// Ищим пользователя в базе	

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {

// Выводим меню

    echo '
    <div class="hide">
    <span style="font-weight: bold;">Журнал операций</span> '.$profile->login($data['id']).'
    </div>';
	
// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_logs` WHERE `user`=?;", array($data['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `user_logs` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));
	
// Выводим сообщение

    while ($act = $q -> fetch()) {	 	
	
    echo '
    <div class="block">
    <span style="float: right;">
    '.$system->system_time($act['time']).'
    </span> 
    '.($act['section'] == 0 ? '<span style="color: #FF0000;">-'.$act['price'].' монет</span>
    ' : '<span style="color: #009933;">+'.$act['price'].' монет</span>').' 
    (<span style="color: #0000FF;">'.$act['money'].' монет</span>) <br />	
    '.$act['message'].' 
    </div>';

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/administration/user/'.$data['id'].'/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Уведомлений нет"); }  
    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>