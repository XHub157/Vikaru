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

// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $file = $queryguest -> fetch();

// Только если данный файл существует
	
    if (!empty($file)) {

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($file['user'], $user['id']));
	
// Проверяем права доступа

    if ($file['access'] == 0 || $file['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $file['access'] == 1 && !empty($friends)) {
	
// Выводим статистику	

    echo '
    <div class="hide">
    Список пользователей которым понравился файл <a href="/modules/files/file/'.$file['id'].'">'.$file['name'].'</a>
    </div>
    ';

// Подчёт количеста лайкнувших	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_like` WHERE `file`=?;", array($file['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим лайки	
	
    $q = DB :: $dbh -> query("SELECT * FROM `files_like` WHERE `file`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($file['id']));   

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
    $navigation->pages('/modules/files/file/likes/'.$file['id'].'/?', $config['post'], $page, $count);		
	
// Выводим ошибки
	
    } else { $system->show("Нет оценок"); } 
    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Выбранный вами файл не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>