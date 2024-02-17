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

    $title = 'Мои гости';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим меню	

    echo '
    <div class="act">
    <a class="act_active" href="/modules/guests">Мои гости</a>
    <a class="act_noactive" href="/id'.$user['id'].'">'.$user['first_name'].' '.$user['last_name'].'</a>
    </div>
    <div class="hide">
    Здесь показаны пользователи, которые посещали вашу страницу.
    </div>';

// Подсчёт количества гостей
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_view` WHERE `profile`=?;", array($user['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `user_view` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));   

// Выводим блок

    while ($act = $q -> fetch()) {   

// Обновляем статус

    if ($act['read'] == 1) DB :: $dbh -> query("UPDATE `user_view` SET `read`=? WHERE `id`=?; LIMIT 1", array(0, $act['id']));	

    echo '
    <div class="block">
    '.$profile->user($act['user']).' 
    '.($act['read'] == 1 ? '<span style="float: right; color: #FF0000;">' : '<span style="float: right;">').' 
    '.$system->system_time($act['time']).'
    </span>
    </div>';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/guests/?', $config['post'], $page, $count);		

// Выводим ошибки
	
    } else { $system->show("Нет гостей"); }  

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>