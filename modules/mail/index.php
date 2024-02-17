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
	
    // Подключаем текстовое ядро
	
    $avatar = new avatar();	

// Выводим шапку

    $title = 'Почта';

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

// Подсчёт количества контактов
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=?;", array($user['id']));

// Подсчёт количества избранных сообщений
	
    $favorites = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_favorites` WHERE `user`=?;", array($user['id']));

// Подсчёт количества спам сообщений
	
    $spam = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_spam` WHERE `user`=?;", array($user['id']));

// Выводим оповещения	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим оповешения
	
    $q = DB :: $dbh -> query("SELECT * FROM `mail_contact` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));

//$q = DB :: $dbh -> queryFetch("SELECT * FROM `mail_message` WHERE `profile`=? AND `user`=? OR `profile`=? AND `user`=? ORDER BY `time` DESC LIMIT 1;", array($data['id'], $user['id'], $user['id'], $data['id']));
	  // Выводим оповещение

    while ($act = $q -> fetch()) {

    $new_mail_message = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `profile`=? AND `user`=? AND `read`=?;", array($user['id'], $act['profile'], 1));

	$mess = DB :: $dbh -> query('SELECT * FROM `mail_message` WHERE (`user` = "'.$user['id'].'" AND `profile` = "'.$act['profile'].'") or (`user` = "'.$act['profile'].'" AND `profile` = "'.$user['id'].'" ) ORDER by `time` DESC') -> fetch();
	
	


 $NavOutSend = ($mess['user'] == $user['id']) ? '<img src = "/icons/message_out.png"> ' : '<img src = "/icons/message_in.png"> ';

    echo '
    <a class="touch_mail" href="/modules/mail/contact/'.$act['profile'].'">
	    '.($new_mail_message > 0 ? '
    <span class="right_count-m">'.$new_mail_message.'</span> <span class="left_count-m">'.$act['message'].'</span>
    ' : '
    <span class="count-m">'.$act['message'].'</span>	
    ').'
	<table><tr><td>
	 '.$avatar->micro($act['profile'], 64,64).'
	 </td><td valign=top style="padding-left: 10px;">
    '.$profile->icons($act['profile']).' '.$profile->us($act['profile']).' '.$profile->birthday($act['profile']).'
		<br>
	<div class="mail__message-wrap">   <div class="mail__message">'.$NavOutSend.' '.$statusMessage.' '.$text->check($mess['message']).''.$statusMessage2.'</div></div>
	</td></tr></table>
    </a>';

    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/mail/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Контактов нет"); } 
	
// Выводим меню

    echo '
    <a class="info-block-link" href="/modules/mail/favorites" style="border-top: 1px solid #B7D2EC;margin-top: 18px;"><img class="middle" src="/icons/landing/star-24.png"> Избранное <span class="count">'.$favorites.'</span> </a>
    <a class="info-block-link" href="/modules/mail/spam"><img class="middle" src="/icons/settings/Mail.png"> Спам <span class="count">'.$spam.'</span> </a>
    <a class="info-block-link" href="/modules/settings/mail"><img class="middle" src="/icons/settings/Settings.png"> Настройки почты</a>
    ';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>