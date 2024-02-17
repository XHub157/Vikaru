<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Выводим шапку

    $title = 'Знакомства';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	

// Выводим блок	
	
    echo '
    <div class="hide">
    Знакомства на '.DOMAIN.'
    </div>
    ';	

// Подсчёт администрации
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `access`>?;", array(0));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;	
	
// Выводим администрацию
	
    $q = DB :: $dbh -> query("SELECT * FROM `user` WHERE `access`>? ORDER BY `access` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array(0));	
	
// Выводим Администратора

    while ($act = $q -> fetch()) {
	
// Выводим данные пользователя

    $data = $profile->data($act['id']);	
	
// Выводим статус

    $status = array("", "<span style='color: #209143;'>Создатель ".DOMAIN."</span>", "<span style='color: #209143;'>Администратор</span>", "<span style='color: #209143;'>Модератор</span>");	
	
    echo '
    <div class="block">
			'.($act['id'] == $user['id'] ? '
	' : '
    <span class="right"><a href="/modules/mail/contact/'.$act['id'].'">Написать сообщение</a> <br /> </span>
     ').'
    <table><tr><td>
    '.$avatar->micro($act['id'], 64,64).'
    </td><td valign=top style="padding-left: 10px;">
    '.$profile->user($act['id']).'
    '.($data['city'] != NULL && $data['region'] != NULL && $data['country'] != NULL ? '
    <br /><br /><span class="count_zxc">'.$data['city'].'</span> <span class="count_zxc">'.$data['region'].'</span> <span class="count_zxc">'.$data['country'].'</span>' : '').'
    <br />
    '.$status[$data['access']].'
	<div class="user__status-wrap">   <div class="user__status">'.($data['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$data['hello'].'').'</div></div>
    </td></tr></table>
    </div>
    ';	

    } 
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/administration/?', $config['post'], $page, $count);	
	 
// Выводим ошибки	
	
    } else { $system->show("Администрации нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>