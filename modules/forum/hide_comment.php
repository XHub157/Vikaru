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

    if ($user['access'] > 0 && $user['access'] < 5) {

// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `hide_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $act['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {		
	
// Запрещяем скритие комментария от системы

    if ($act['user'] != 0) {	
	
// Проверяем статус

    if ($act['hide'] == 0) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum_comments` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array($user['id'], time(), $act['id']));	

// Уведомляем

    $system->redirect("Комментарий успешно скрыт", "/modules/forum/topic/".$act['topic']."");   	
	
    } else {
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum_comments` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array(0, 0, $act['id']));	
	
// Уведомляем

    $system->redirect("Комментарий успешно восстановлен", "/modules/forum/topic/".$act['topic']."");  	
	
    }	

// Выводим ошибки
 
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("Выбранный вами комментарий не существует"); } 	
    } else { $system->show("Отказано в доступе"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>