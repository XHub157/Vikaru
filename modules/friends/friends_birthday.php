<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Друзья';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Выводим меню

    echo '
    <div class="act">
    <a class="act_active" href="/modules/friends/birthday/'.$data['id'].'">Именинники</a>
    <a class="act_noactive" href="/id'.$data['id'].'">'.$data['first_name'].' '.$data['last_name'].'</a>
    </div>
    <div class="hide">
    Друзья '.$profile->login($data['id']).'
    </div>
    <div class="function">
    <a href="/modules/friends/user/'.$data['id'].'" class="link">Все</a> |
    <a href="/modules/friends/online/'.$data['id'].'" class="link">Онлайн</a> | 
    '.($data['id'] == $user['id'] ? '
    <a href="/modules/friends/new" class="link">Заявки</a> |
    <a href="/modules/friends/delete" class="link">Удаление</a> |' : '').'
    <a href="/modules/friends/birthday">Именинники</a>
    </div>
    ';	
	
// Подсчёт количества друзей онлайн

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`day`=? AND `user`.`month`=?;", array($data['id'], date("d"), date('m')));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим друзей онлайн

    $q = DB :: $dbh -> query("SELECT `friends`.*, `user`.* FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`day`=? AND `user`.`month`=? ORDER BY `user`.`aut` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], date("d"), date('m'))); 	
	
// Выводим пользователя

    while ($act = $q -> fetch()) {

    echo '
    <div class="block">
    '.$profile->user($act['user']).'
    <span style="float: right;"> 
    '.$system->system_time($act['date_aut']).'
    </span>
    </div>	
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/friends/birthday/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Именинников нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	