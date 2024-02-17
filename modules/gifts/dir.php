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

    $title = 'Подарки';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Ищим категорию

    $queryguest = DB :: $dbh -> query("SELECT * FROM `gifts_dir` WHERE `id`=? LIMIT 1;", array($id));
    $dir = $queryguest -> fetch();

// Только если данная категория существует
	
    if (!empty($dir)) {
	
// Подсчёт категорий
  
    $dirs = DB :: $dbh -> querySingle("SELECT count(*) FROM `gifts_dir`;");
	
// Только если категоии существуют
   
    if ($dirs > 0) {   
	
// Выводим сортировку категорий

    $q = DB :: $dbh -> query("SELECT * FROM `gifts_dir` ORDER BY `time`;"); 

// Выводим категорию	

    echo '
    <div class="hide">
    <a href="/modules/gifts/'.$add.'">Все</a>';
    while ($act = $q -> fetch()) {
    echo '
    | <a '.($act['id'] == $dir['id'] ? 'class="link"' : '').' href="/modules/gifts/dir/'.$act['id'].$add.'">'.$act['name'].'</a>';
    }
    echo '
    </div>
    '; 
	
    }	
	
// Только если выбран пользователь

    if (isset($_GET['add'])) {	
	
// Получаем информацию о пользователе

    $us = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `id`=?;", array(abs(intval($_GET['add']))));
	
// Выводим меню если выбран пользователь	

    echo (!empty($us) && abs(intval($_GET['add'])) != $user['id']) ? '
    <div class="block">
    Выберите подарок для '.$profile->login(abs(intval($_GET['add']))).'
    </div>':'';	

    } 

// Подсчёт количества подарков
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `gifts` WHERE `dir`=?;", array($dir['id']));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим подарки	

    $q = DB :: $dbh -> query("SELECT * FROM `gifts` WHERE `dir`=? ORDER BY `time;", array($dir['id']));	
	
// Выводим подарок

    while ($act = $q -> fetch()) {	

    echo '
    <a class="touch" href="/modules/gifts/gift/'.$act['id'].'/'.$add.'">
    <img class="middle" src="http://'.SERVER_DOMAIN.'/gifts/32/'.$act['id'].'.png">
    <span class="color" style="vertical-align: 8px;">('.$act['money'].' монет)</span>
    </a>
    ';

    }  	

// Выводим ошибки
	
    } else { $system->show("Подарков нет"); }   
    } else { $system->show("Выбранная вами категория не существует"); } 	
	
    echo $user['access'] > 0 && $user['access'] < 3 ? '
    <div class="hide">
    <img class="middle" src="/icons/add.png"> <a href="/modules/gifts/add_gift/'.$dir['id'].'">Добавить подарок</a><br />
    <img class="middle" src="/icons/edit.png"> <a href="/modules/gifts/edit_dir/'.$dir['id'].'">Редактировать категорию</a><br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/gifts/delete_dir/'.$dir['id'].'">Удалить категорию</a>
    </div>
    ':'';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>