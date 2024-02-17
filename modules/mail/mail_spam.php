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

    $title = 'Спам';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим меню

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
    <a href="/modules/mail" class="listbar-act">Диалоги</a>
    <a href="/modules/mail/received" class="">Полученные</a>
    <a href="/modules/mail/posted" class="">Отправленные</a>
    </div>
';
// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_spam` WHERE `user`=?;", array($user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `mail_spam` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));
	
// Выводим сообщение

    while ($act = $q -> fetch()) {	 	
	
    echo '<div class="block">
    '.$profile->user($act['last_user']).' ::
    <a href="/modules/mail/contact/'.($act['last_user'] == $user['id'] ? "".$act['last_profile']."" : "".$act['last_user']."").'">
    '.$profile->us($act['last_profile']).'
    </a>
    <span style="float: right;">
    '.$system->system_time($act['time']).'
    </span> <br />
    '.$text->number($act['message'], 250).'
    </div>';

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/mail/spam/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Сообщений нет"); }   	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>