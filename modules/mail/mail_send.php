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
	
// Подключаем ядро отправки email

    $email = new email();	
	
// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный пользователь существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `user`=? AND `time`>?;", array($user['id'], time()-30));

// Только если было меньше $config['antiflood_creation'] комментариев зв 30 секунд	
	
    if ($antiflood < $config['antiflood_creation']) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	
	
// Проверяем является есть ли пользователь в контактах
	
    $contact = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	

// Запрещаем комментировать закрытый дневник

    if ($act['access_mail'] == 0 || $user['access'] > 0 && $user['access'] < 3 || $act['access_mail'] == 1 && !empty($friends) || $act['access_mail'] == 2 && !empty($contact)) {	

// Запрещяем писать самому себе

    if ($act['id'] != $user['id']) {

// Проверяем добавление в контакты

    $contact_user = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	
    if (empty($contact_user)) {
    DB :: $dbh -> query("INSERT INTO `mail_contact` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['id'], $user['id'], time()));	
    }	
	
    $contact_profile = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($user['id'], $act['id']));	
    if (empty($contact_profile)) {
    DB :: $dbh -> query("INSERT INTO `mail_contact` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($user['id'], $act['id'], time()));	
    }	
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['message'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $message = $system->check($_POST['message']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($message) >= 3 && $system->utf_strlen($message) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `mail_message` (`user`, `message`, `profile`, `read`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array($user['id'], $message, $act['id'], 1, time(), $system->ip(), $system->ua()));

// Обновляем счётчики

    $count_message = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `profile`=? AND `user`=? OR `profile`=? AND `user`=?;", array($act['id'], $user['id'], $user['id'], $act['id']));
    DB :: $dbh -> query("UPDATE `mail_contact` SET `time`=?, `message`=? WHERE `user`=? AND `profile`=?", array(time(), $count_message, $act['id'], $user['id']));
    DB :: $dbh -> query("UPDATE `mail_contact` SET `time`=?, `message`=? WHERE `user`=? AND `profile`=?", array(time(), $count_message, $user['id'], $act['id'])); 
	
// Уведомление на email

    if ($act['activation'] == 2 && $act['notice'] == 1 && $act['date_aut'] < time()-300) {	
	
    $email->send($act['email'], 'Новое уведомление на '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$act['login'].'</span>, у Вас новое сообщение от пользователя <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    <a href="http://'.DOMAIN.'/modules/mail/contact/'.$user['id'].'">'.substr($message, 0, 100).'</a> <br />');

    }	

// Уведомляем

    $system->redirect("Сообщение успешно отправлено", "/modules/mail/contact/".$act['id']."");	

// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинное или короткое сообщение", "/modules/mail/contact/".$act['id'].""); } 
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/mail/contact/".$act['id'].""); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/mail/contact/".$act['id'].""); }	
    } else { $system->redirect("Отказано в доступе", "/modules/mail/"); } 
    } else { $system->redirect("Пользователь закрыл свою почту", "/modules/mail/"); } 
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/mail/contact/".$act['id'].""); }	
    } else { $system->redirect("Выбранный вами пользователь не существует", "/modules/mail/"); }	

?>