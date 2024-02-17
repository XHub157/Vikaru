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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `guestbook_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `hide_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $act['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {		
	
// Проверяем статус

    if ($act['hide'] == 0) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `guestbook_comments` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array($user['id'], time(), $act['id']));	

// Уведомляем

    $system->redirect("Комментарий успешно скрыт", "/modules/guestbook/".$act['profile']."");   	
	
    } else {
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `guestbook_comments` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array(0, time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Комментарий успешно восстановлен", "/modules/guestbook/".$act['profile']."");  	
	
    }	

// Выводим ошибки
 
    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("Выбранный вами комментарий не существует"); } 	
    } else { $system->show("Отказано в доступе"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>