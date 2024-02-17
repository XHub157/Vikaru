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

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 5) {

// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `arbor` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `hide_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $act['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {			
	
// Проверяем статус

    if ($act['hide'] == 0) {	
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `arbor` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array($user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Комментарий успешно скрыт", "/modules/arbor/");
	
// Если комментарий скрыт

    } else {

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `arbor` SET `hide`=?, `hide_time`=? WHERE `id`=? LIMIT 1;", array(0, 0, $act['id']));	
	
// Уведомляем

    $system->redirect("Комментарий успешно восстановлен", "/modules/arbor/");

    }	

// Выводим ошибки

    } else { $system->redirect("Не так быстро, подождите немного", "/modules/arbor/"); } 	
    } else { $system->redirect("Выбранный вами комментарий не существует", "/modules/arbor/"); } 
    } else { $system->redirect("Отказано в доступе", "/modules/arbor/"); } 

?>