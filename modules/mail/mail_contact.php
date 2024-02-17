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

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Запрещаем писать самому себе

    if ($data['id'] != $user['id']) {	
	
// Проверяем есть ли данный пользователь в контактах

    $contact = DB :: $dbh -> queryFetch("SELECT `id` FROM `mail_contact` WHERE `user`=? AND `profile`=? LIMIT 1;", array($user['id'], $data['id']));		

// Выводим меню

    echo '
    <div class="show" style="background: #4C73A2;margin-bottom: 0px;">
    Автоответчик: '.($data['hello_mail'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$data['hello_mail'].'').'
    </div>
	<div class="listbar">
    <a href="/modules/mail" class="listbar-act">Диалоги</a>
    <a href="/modules/mail/received" class="">Полученные</a>
    <a href="/modules/mail/posted" class="">Отправленные</a>
    </div>


    ';

// Выводим форму

    echo $system->form('/modules/mail/send/'.$data['id'].'', '', 'Отправить', 'Сообщение', '10000', 'comment', '', ''.$user['sid'].'', 'message');

// Подсчёт количества сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `profile`=? AND `user`=? OR `profile`=? AND `user`=?;", array($data['id'], $user['id'], $user['id'], $data['id']));
   
// Выводим блок с количеством сообщений
   
    echo '
     <div class="show" style="background: #4C73A2;margin-top: -10px;">
    Сообщений: ['.$count.']
    </div>
    ';

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения

    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `profile`=? AND `user`=? OR `profile`=? AND `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], $user['id'], $user['id'], $data['id']));

// Выводим сообщение

    while ($act = $q -> fetch()) {
	
// Проверяем добавлено ли в избранное сообщение	
	
    $favorites = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_favorites` WHERE `last_id`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));
	
// Проверяем добавлено ли в спам сообщение	
	
    $spam = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_spam` WHERE `last_id`=? AND `user`=? LIMIT 1;", array($act['id'], $user['id']));	

// Обновляем статус сообщения

    if ($act['read'] == 1) 	DB :: $dbh -> query("UPDATE `mail_message` SET `read`=? WHERE `user`=? AND `profile`=? AND `id`=?; LIMIT 1", array(0, $data['id'], $user['id'], $act['id']));

    echo '
    '.(empty($spam) ? '<div class="block" style="border: 1px solid #D3DAE0;margin: 1px 0;">' : '<div class="hide" style="border: 1px solid #D3DAE0;margin: 1px 0;background: #F1DEE8;">').'
	
	    '.($act['read'] == 1 ? '<span style="margin-left: 5px;margin-bottom: 2px;display: inline-block;background: #A24C4C;padding: 8px 16px 8px 16px;border-radius: 20px;color: white;float: right;">Не прочитано</span>' : '').'
    <span style="margin-bottom: 2px;display: inline-block;background: #4C73A2;padding: 8px 16px 8px 16px;border-radius: 20px;color: white;float: right;"> 
    '.$system->system_time($act['time']).'
    </span>
    				<table><tr><td>
	    '.$avatar->micro($act['user'], 64,64).'
	    	</td><td valign=top style="padding-left: 10px;">
    '.($act['user'] == $user['id'] ? '<span style="font-weight: bold;color: #009933;"> Я </span>>' : ''.$profile->icons($act['user']).'').'
    '.$profile->login($data['id']).'
<br />
    <div class="mail__message-wrap">   <div class="mail__message">'.$text->check($act['message']).' </div></div>
    		</td></tr></table>
    </div>
	
	<div class="listbar" style="margin-top: -1px;">
	'.($act['user'] == $user['id'] ? '
    <a href="/modules/mail/edit_message/'.$act['id'].'" class="" style="float: left;">Редактировать</a>
	' : '').'
    <a href="/modules/mail/delete_message/'.$act['id'].'" class="" style="float: left;">Удалить</a>
	
	'.(empty($favorites) ? '
    <a href="/modules/mail/favorites/'.$act['id'].'" class="" style="float: left;">В избранное</a>
	' : '
	<a href="/modules/mail/favorites/'.$act['id'].'" class="listbar-act" style="float: left;">Удалить с избранного</a>
	').'
	<a href="/modules/mail/spam/'.$act['id'].'" class="listbar-act" style="float: right;">Спам</a>
    </div>
	
	'; 	
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/mail/contact/'.$data['id'].'/?', $config['post'], $page, $count);	
	
// Выводим меню

    echo '
    '.(empty($contact) ? '
    ' : '
    <a class="info-block-link" href="/modules/mail/delete_contact/'.$contact['id'].'"><img class="middle" src="/icons/delete.png"> Удалить контакт '.$profile->us($data['id']).'</a>').'
    ';	
	
// Выводим ошибки	
	
    } else { $system->show("Сообщений нет"); }
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранный вами пользователь не существует"); } 	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	