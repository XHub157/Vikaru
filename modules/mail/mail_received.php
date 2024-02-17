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

// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Сообщения';

// Инклудим шапку

include_once (ROOT.'template/head.php');

    echo '
	
	 <a class="info-block" style="font-weight: bold;margin-bottom: -11px;display: block;background: #4C73A2;text-align: center;color: white;" href="/modules/mail/send">Написать сообщение</a>

	<div class="info-block bottom_link_block tools_block show-all r_field-list " style="margin-bottom: 0px;">
<form method="post" action="/modules/mail/search">
<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 100%;" class="m">
<div style="padding:0 20px 0 10px;">
<input type="text" class="font_medium m" placeholder="Поиск.." style="width: 100%; margin: 0 0 0 -10px;" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'" size="15" value="">
</td>

<td class="m">
<input type="submit" style="line-height: 19px;  margin-top: 0;" class="main_submit" value="Найти">
</td>
</tr>  
</table>
</form>
</div>

	<div class="listbar">
    <a href="/modules/mail" class="">Диалоги</a>
    <a href="/modules/mail/received" class="listbar-act">Полученные</a>
    <a href="/modules/mail/posted" class="">Отправленные</a>
    </div>
';


// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `profile`=?;", array($user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим список полученых сообщений

    echo '
    <div class="hide">
    Вы получили '.$count.' сообщений
    </div>';	
	
// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));
	
// Выводим сообщение

    while ($act = $q -> fetch()) {	 	
	
    echo '<div class="block">
    '.$profile->user($act['user']).' :: 
    <a href="/modules/mail/contact/'.$act['user'].'">
    '.$profile->us($act['profile']).'
    </a>
    <span style="margin-bottom: 2px;display: inline-block;background: #4C73A2;padding: 8px 16px 8px 16px;border-radius: 20px;color: white;float: right;"> 
    '.$system->system_time($act['time']).'
    </span> <br />
    <div class="mail__message-wrap">   <div class="mail__message">'.$text->check($act['message']).'</div></div>
    </div>';

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/mail/received/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Сообщений нет"); }   	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>