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

    $title = 'Редактирование';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {	
	
// Ищим новость в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `news` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная новость существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `news` WHERE `closed_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $act['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {			
	
// Проверяем статус

    if ($act['closed'] == 0) {		
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `news` SET `closed`=?, `closed_time`=? WHERE `id`=? LIMIT 1;", array($user['id'], time(), $act['id']));	
	
// Уведомляем
	
    $system->redirect("Новость успешно зыкрыта", "/modules/news/".$act['id']."");	

    } else {

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `news` SET `closed`=?, `closed_time`=? WHERE `id`=? LIMIT 1;", array(0, 0, $act['id']));	
	
// Уведомляем
	
    $system->redirect("Новость успешно открыта", "/modules/news/".$act['id']."");	

    }

// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранная вами новость не существует"); } 
    } else { $system->show("Отказано в доступе"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>