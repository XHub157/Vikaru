<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Закладки';

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
    <a class="act_active" href="/modules/bookmarks/'.$data['id'].'">Закладки</a>
    <a class="act_noactive" href="/id'.$data['id'].'">'.$data['first_name'].' '.$data['last_name'].'</a>
    </div>
    <div class="hide">
    Закладки '.$profile->login($data['id']).'
    </div>';
	
// Подсчёт количества закладок

    $count = ($data['id'] == $user['id']) ? DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `user`=?;", array($data['id']))
    : DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `user`=? AND `access`=?;", array($data['id'], 0));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим дневники

    $q = ($data['id'] == $user['id']) ? DB :: $dbh -> query("SELECT * FROM `bookmarks` WHERE `user`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']))
    : DB :: $dbh -> query("SELECT * FROM `bookmarks` WHERE `user`=? AND `access`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], 0));	
	
// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '<div class="block">
    <img src="/icons/bookmarks_access_'.($act['access'] == 0 ? 'true' : 'false').'.png">  
    '.$profile->login($act['user']).' :: <a href="'.$act['url'].'">'.$act['name'].'</a>
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 ? '<br />
    [<a href="/modules/bookmarks/edit_bookmarks/'.$act['id'].'" class="link">Редактировать</a>]
    [<a href="/modules/bookmarks/delete_bookmarks/'.$act['id'].'" class="link">Удалить</a>]
    ' : '').'
    </div>
    ';
	
    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/bookmarks/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("Закладок нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	