<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Рейтинг';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим статью в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `reference` WHERE `id`=? LIMIT 1;", array($id));
    $reference = $queryguest -> fetch();

// Только если данная статья существует
	
    if (!empty($reference)) {

    echo '
    <div class="hide">
    Список проголосовавших за статью <a href="/modules/reference/'.$reference['id'].'">'.$reference['name'].'</a>
    </div>
    ';

// Подчёт количеста проголосовавших
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference_rating` WHERE `reference`=?;", array($reference['id']));

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим проголосовавших	
	
    $q = DB :: $dbh -> query("SELECT * FROM `reference_rating` WHERE `reference`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($reference['id']));   

// Выводим блок

    while ($act = $q -> fetch()) {     

    echo '
    <div class="block">
    '.$profile->user($act['user']).'
    '.($act['section'] == 1 ? '<span style="color:#209143">+</span>' : '<span style="color:#f30000">-</span>').' 
    <span style="float: right;"> 
    '.$system->system_time($act['time']).'
    </span>
    </div>
    ';

    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/reference/rating/'.$reference['id'].'/?', $config['post'], $page, $count);		
	
// Выводим ошибки
	
    } else { $system->show("Нет голосов"); }      
    } else { $system->show("Выбранная вами статья не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>