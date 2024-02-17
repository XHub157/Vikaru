<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Справка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подсчёт сатей
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference`;");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим стати

    $q = DB :: $dbh -> query("SELECT * FROM `reference` ORDER BY `time`;");	

	echo '
	<div class="hide">
	<div class="zcoreq">
	Справка
	</div>
	</div>';
	
// Выводим статью

    while ($act = $q -> fetch()) {
    echo '
    <a class="touch" href="/modules/reference/'.$act['id'].'"> 
    <span class="color">
    '.$act['id'].'. 
    </span>
    '.$act['name'].'
    </a>
    ';
	
    } 	
	
// Выводим сообщение если информации нет	
	
    } else { $system->show("Статей нет"); }  
	
    echo $user['access'] > 0 && $user['access'] < 3 ? '
    <div class="hide">
    <img class="middle" src="/icons/add.png"> <a href="/modules/reference/add_reference/">Добавить статью</a>
    </div>':'';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>