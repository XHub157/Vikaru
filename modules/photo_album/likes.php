<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Понравелось';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $photo = $queryguest -> fetch();

// Только если данное фото существует
	
    if (!empty($photo)) {

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($photo['user'], $user['id']));
	
// Проверяем права доступа

    if ($photo['access'] == 0 || $photo['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $photo['access'] == 1 && !empty($friends)) {
	
// Выводим статистику	

    echo '
    <div class="hide">
    Список пользователей которым понравилось фото <a href="/modules/photo_album/photo/'.$photo['id'].'">'.$photo['name'].'</a>
    </div>
    ';

// Подчёт количеста лайкнувших	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_like` WHERE `photo`=?;", array($photo['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим лайки	
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo_like` WHERE `photo`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($photo['id']));   

// Выводим лайк

    while ($act = $q -> fetch()) {     

    echo '
    <div class="block">
    '.$profile->user($act['user']).' 
    <span style="float: right;"> 
    '.$system->system_time($act['time']).'
    </span>	
    </div>
    ';

    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/photo_albums/photo/likes/'.$photo['id'].'/?', $config['post'], $page, $count);		
	
// Выводим ошибки
	
    } else { $system->show("Нет оценок"); } 
    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Выбранное вами фото не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>