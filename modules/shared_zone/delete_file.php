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
	
// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный файл существует
	
    if (!empty($act)) {	
	
// Проверяем доступ

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {	

// Только если разрешено загружать файлы в данную папку

    if ($act['shared_zone'] > 0) {

// Выполняем запрос в базу

    DB :: $dbh -> query("UPDATE `files` SET `shared_zone`=?, `shared_time`=? WHERE `id`=? LIMIT 1;", array(0, 0, $act['id'])); 

// Обновляем данные

    DB :: $dbh -> query("UPDATE `shared_zone` SET `files`=`files`-1 WHERE `id`=?", array($act['shared_zone']));  

// Выводим уведомление

    $system->redirect("Файл успешно удалён из Зоны Обмена", "/modules/files/file/".$act['id']."");	

// Выводим ошибки

    } else { $system->redirect("Данный файл не загружен в Зону Обмена", "/modules/files/file/".$act['id'].""); }		
    } else { $system->redirect("Отказано в доступе", "/modules/shared_zone/dir/".$act['id'].""); }
    } else { $system->redirect("Выбранный вами файл не существует", "/modules/files/".$user['id'].""); }	
	
?>	