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

    $title = 'Рейтинг';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим статью в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `reference` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная статья существует
	
    if (!empty($act)) {	
	
// Проверяем голосовал ли пользователь

    $queryguest = DB :: $dbh -> query("SELECT * FROM `reference_rating` WHERE `user`=? AND `reference`=? LIMIT 1;", array($user['id'], $act['id']));
    $rating = $queryguest -> fetch();

    if (!empty($rating)) {	

// Проверяем показатель голосования

    if ($rating['section'] == 1) {
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `reference_rating` SET `section`=?, `time`=? WHERE `user`=? AND `reference`=? LIMIT 1;", array(0, time(), $user['id'], $act['id']));	
	
// Уведомляем

    $system->redirect("Вы успешно проголосовали", "/modules/reference/".$act['id']."");	
	
    } else {
	
// Уведомляем

    $system->redirect("Вы уже голосовали", "/modules/reference/".$act['id']."");	

    }	
    } else {
	
// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `reference_rating` (`user`, `time`, `reference`, `section`) VALUES (?, ?, ?, ?);", array($user['id'], time(), $act['id'], 0));

// Уведомляем

    $system->redirect("Вы успешно проголосовали", "/modules/reference/".$act['id']."");	

    } 
	
// Выводим ошибки

    } else { $system->show("Выбранная вами статья не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>