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

// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $diary = $queryguest -> fetch();

// Только если данный дневник существует
	
    if (!empty($diary)) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($diary['user'], $user['id']));
	
// Проверяем права доступа

    if ($diary['access'] == 0 || $diary['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $diary['access'] == 1 && !empty($friends)) {	

    echo '
    <div class="hide">
    Список пользователей которым понравилася дневник <a href="/modules/diary/'.$diary['id'].'">'.$diary['name'].'</a>
    </div>
    ';

// Подчёт количеста лайкнувших	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_like` WHERE `diary`=?;", array($diary['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим лайки	
	
    $q = DB :: $dbh -> query("SELECT * FROM `diary_like` WHERE `diary`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($diary['id']));   

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
    $navigation->pages('/modules/diary/likes/'.$diary['id'].'/?', $config['post'], $page, $count);		
	
// Выводим ошибки
	
    } else { $system->show("Нет оценок"); }  
    } else { $system->show("Отказано в доступе"); } 	    
    } else { $system->show("Выбранный вами дневник не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>