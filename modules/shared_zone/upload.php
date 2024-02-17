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
	
// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `shared_zone` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная папка существует
	
    if (!empty($act)) {	

// Только если разрешено загружать файлы в данную папку

    if ($act['upload'] == 1) {

// Выводим информацию о файле

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array(abs(intval($_GET['add']))));
    $file = $queryguest -> fetch();

// Проверяем доступ

    if ($file['user'] == $user['id'] && $file['access'] == 0) {
	
// Запрещаем перемещать в одну и ту же самую папку

    if ($file['shared_zone'] != $act['id']) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `shared_time`>? AND `user`=?;", array(time()-10, $user['id']));

// Только если не было изменений в течении 10 секунд
	
    if (empty($antiflood)) {

// Выполняем запрос в базу

    DB :: $dbh -> query("UPDATE `files` SET `shared_zone`=?, `shared_time`=? WHERE `id`=? LIMIT 1;", array($act['id'], time(), $file['id'])); 

// Обновляем данные

    DB :: $dbh -> query("UPDATE `shared_zone` SET `files`=`files`+1 WHERE `id`=?", array($act['id'])); 

// Выводим уведомление

    $system->redirect("Файл успешно добавлен в Зону Обмена", "/modules/files/file/".$file['id']."");	

// Выводим ошибки

    } else { $system->redirect("Не так быстро, подождите немного", "/modules/shared_zone/dir/".$act['id'].""); }		
    } else { $system->redirect("Файл уже добавлен в данную папку", "/modules/shared_zone/dir/".$act['id'].""); }
    } else { $system->redirect("Отказано в доступе", "/modules/files/".$user['id'].""); }
    } else { $system->redirect("В данную папку загружать запрещено", "/modules/shared_zone/dir/".$act['id'].""); }
    } else { $system->redirect("Выбранная вами папка не существует", "/modules/shared_zone/"); }	
	
?>	