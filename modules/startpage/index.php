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

// Подключаем стартовое ядро
	
    $startpage = new startpage();

// Подключаем графическое ядро
	
    $photo = new photo();	

// Подключаем стартовое ядро
	
    $rating = new rating();

// Выводим шапку

    $title = 'Старт';

// Инклудим шапку

include_once (ROOT.'template/head.php');

    echo '
    <div class="block">
    Привет, <span style="font-weight: bold;">'.$user['first_name'].' '.$user['last_name'].'</span><br/>
    <a href="/modules/startpage/history">История входов</a>: 
    последний раз вы заходили в <span style="color: #009933;">'.$system->system_time($user['date_aut']).'</span></div>
    <div class="block"><span style="font-weight: bold;">Ваш рейтинг:</span> '.($user['rating'] == 0 ? '0.00' : ''.$user['rating'] / 100 .'').'
    <span style="color: #D3D3D3;">('.$rating->user($user['id']).')</span>
    </div>';
   
	
// Выводим последние 3 фото

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `access`=? LIMIT 3;", array(0));

// Только если фотографий больше 0	
	
    if ($count > 0) {

// Выводим блок

    echo '
    <div class="hide">
    Новые фото: 
    </div><div class="block">';

// Выводим фотографии

    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `access`=? ORDER BY `time` DESC  LIMIT 4;", array(0));

// Выводим фото

    while ($act = $q -> fetch()) {

    echo '
    <a href="/modules/photo_album/photo/'.$act['id'].'">
    '.$photo->micro($act['id'], 64, 64, $act['key'], $act['type']).'
    </a>
    ';

    }
	
    echo '</div>';

    }
	
    echo '
    '.$startpage->diary().'
    '.$startpage->forum().'
    ';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>