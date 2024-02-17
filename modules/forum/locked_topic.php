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

    if ($user['access'] > 0 && $user['access'] < 4) {	
	
// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная тема существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `locked_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $act['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {			
	
// Проверяем статус

    if ($act['locked'] == 0) {		
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum_topic` SET `locked`=?, `locked_time`=? WHERE `id`=? LIMIT 1;", array($user['id'], time(), $act['id']));	
	
// Уведомляем
	
    $system->redirect("Тема успешно закреплена", "/modules/forum/topic/".$act['id']."");	

    } else {

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum_topic` SET `locked`=?, `locked_time`=? WHERE `id`=? LIMIT 1;", array(0, time(), $act['id']));	
	
// Уведомляем
	
    $system->redirect("Тема успешно откреплена", "/modules/forum/topic/".$act['id']."");	

    }

// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранная вами тема не существует"); } 	
    } else { $system->show("Отказано в доступе"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>