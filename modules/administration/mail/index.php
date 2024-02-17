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
	
// Подсчёт сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message`;");
	
// Выводим меню

    echo '
    <div class="hide">
    Поиск по сообщениям: <span class="count">'.$count.'</span>
    <form method="post" action="/modules/administration/mail/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'" style="width: 50%;" />
    <input type="submit" value="Искать" class="submit" /></form>
    </div>';	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим сообщения	
	
    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` ORDER BY `time` DESC LIMIT " . $page . ", " . $config['post'] . ";");
	
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
    $navigation->pages('/modules/administration/mail/?', $config['post'], $page, $count);	 	

// Выводим ошибки
	
    } else { $system->show("Сообщений нет"); }  
    } else { $system->redirect("Отказано в доступе", "/"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>