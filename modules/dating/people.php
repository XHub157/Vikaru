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
    <form method="post" action="/modules/dating/people/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>
    - <a href="/modules/dating/search">Расширенный поиск</a> <br />
    </div>
    ';	

// Подсчёт пользователей
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user`;");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;	
	
// Выводим пользователей
	
    $q = DB :: $dbh -> query("SELECT * FROM `user` ORDER BY `rating` DESC LIMIT " . $page . ", " . $config['post'] . ";");	
	
// Выводим пользователя

    while ($act = $q -> fetch()) {
	
// Выводим данные пользователя

    $data = $profile->data($act['id']);	
	
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
    </span>'.($data['city'] != NULL && $data['region'] != NULL && $data['country'] != NULL ? '
    <br /><br /><span class="count_zxc">'.$data['city'].'</span> <span class="count_zxc">'.$data['region'].'</span> <span class="count_zxc">'.$data['country'].'</span>' : '').'
	<div class="user__status-wrap">   <div class="user__status">'.($data['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$data['hello'].'').'</div></div>
    </td></tr></table>
    </div>
    ';

    } 
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/people/?', $config['post'], $page, $count);	
	 
// Выводим ошибки	
	
    } else { $system->show("Пользователей нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>