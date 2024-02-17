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

    $title = 'Почтовый шпион';

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
    Почтовый шпион '.$profile->login($data['id']).'
    </div>';
	
// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `user`=? OR `profile`=?;", array($data['id'], $data['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `user`=? OR `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], $data['id']));
	
// Выводим сообщение

    while ($act = $q -> fetch()) {	 	
	
    echo '
    <div class="block">
    <a href="/modules/administration/mail/user/'.$act['user'].'">'.$profile->us($act['user']).'</a> -> 
    <a href="/modules/administration/mail/user/'.$act['profile'].'">'.$profile->us($act['profile']).'</a>
    '.($act['read'] == 1 ? '<span style="color: #FF0000;">(не прочитано)</span>' : '').'
    <span style="float: right;"> 
    '.$system->system_time($act['time']).'
    </span><br />
    '.$text->check($act['message']).' 
    <hr>
    '.$act['ip'].' :: '.$act['ua'].'
    </div>'; 

    }  

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/administration/mail/user/'.$data['id'].'/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Сообщений нет"); }  	 
    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>