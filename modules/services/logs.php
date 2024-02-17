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

// Выводим меню

    echo '
    <div class="hide">
    Здесь вы сможете посмотреть историю баланса на '.DOMAIN.'.
    </div>';
	
// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_logs` WHERE `user`=?;", array($user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `user_logs` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));
	
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
    $navigation->pages('/modules/services/logs/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Уведомлений нет"); }   	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>