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
	
// Выводим шапку

    $title = 'Сообщение';

// Инклудим шапку

include_once (ROOT.'template/head.php');	 

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `user`=? AND `time`>?;", array($user['id'], time()-30));

// Только если было меньше $config['antiflood_creation'] комментариев за 30 секунд	
	
    if ($antiflood < $config['antiflood_creation']) {

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {		
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $login = $system->check($_POST['login']);	

// Обработка описания
	
    $message = $system->check($_POST['message']);	
	
// Проверяем и выводим информацию

    $act = DB :: $dbh -> queryFetch("SELECT `id`, `access_mail`, `email`, `notice`, `date_aut` FROM `user` WHERE `login`=? LIMIT 1;", array(strtolower($login)));

// Только если данный пользователь существует
	
    if ($system->utf_strlen($login) >= 3 && $system->utf_strlen($login) <= 32 && !empty($act) && preg_match('|^[a-z0-9\-]+$|i', $login)) {	
	
// Запрещяем писать самому себе

    if ($act['id'] != $user['id']) {	
	
// Проверяем является ли пользователь другом
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	
	
// Проверяем является есть ли пользователь в контактах
	
    $contact = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	

// Проверяем права доступа

    if ($act['access_mail'] == 0 || $user['access'] > 0 && $user['access'] < 3 || $act['access_mail'] == 1 && !empty($friends) || $act['access_mail'] == 2 && !empty($contact)) {  	

// Обработка количества символов сообщения
	
    if ($system->utf_strlen($message) >= 3 && $system->utf_strlen($message) < 10000) {	

// Проверяем добавление в контакты

    $contact_user = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($act['id'], $user['id']));	
    if (empty($contact_user)) {
    DB :: $dbh -> query("INSERT INTO `mail_contact` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['id'], $user['id'], time()));	
    }	
	
    $contact_profile = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_contact` WHERE `user`=? AND `profile`=?;", array($user['id'], $act['id']));	
    if (empty($contact_profile)) {
    DB :: $dbh -> query("INSERT INTO `mail_contact` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($user['id'], $act['id'], time()));	
    }		
	
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
	
// Выводим ошибки
    
    } else { $system->show("Слишком длинное или короткое сообщение"); }
    } else { $system->show("Пользователь закрыл свою почту"); } 
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/mail/");  
    }

// Выводим блок

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Кому: (Логин) <br />
    <input type="text" name="login" value=""/> <br />
    Сообщение: (10000 символов) <br />
    <textarea cols="25" rows="3" name="message" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Отправить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	