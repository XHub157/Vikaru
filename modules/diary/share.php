<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Поделились';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $diary = $queryguest -> fetch();

// Только если данный дневник существует
	
    if (!empty($diary)) {
	
// Проверяем права доступа

    if ($diary['access'] == 0 || $diary['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3) {	

    echo '
    <div class="hide">
    Список поделившихся дневником <a href="/modules/diary/'.$diary['id'].'">'.$diary['name'].'</a>
    </div>
    ';

// Подчёт количеста просмотревших	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `share`=? AND `section`=?;", array($diary['id'], 0));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `share`=? AND `section`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($diary['id'], 0));   

// Выводим блок

    while ($act = $q -> fetch()) {  

    echo '
    <a class="touch_white" href="/modules/diary/'.$act['id'].'">
    '.$profile->icons($act['user']).' '.$profile->us($act['user']).' '.$profile->birthday($act['user']).' :: 
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'    	
    </span>
    <span style="font-weight: bold;">
    '.$act['name'].'
    </span>
    <br />
    <span class="color">
    '.($act['censored'] == 1 || $act['access'] > 0 ? 'Доступ закрыт' : ''.$text->number($act['description'], 250).'').' <br />
    </span>
    Комментариев 
    <span class="count">'.$act['comments'].'</span>
    </a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/diary/share/'.$diary['id'].'/?', $config['post'], $page, $count);		

// Выводим ошибки
	
    } else { $system->show("Нет поделившихся"); }  
    } else { $system->show("Отказано в доступе"); }     
    } else { $system->show("Выбранный вами дневник не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>