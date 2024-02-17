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

// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Редактирование';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим сообщение в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данное сообщение существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
    if (empty($antiflood)) {		
	
// Проверяем права

    if ($act['user'] == $user['id']) {
	
// Только если отправлен POST запрос	
	
    if (isset($_POST['message'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Обработка комментария
	
    $message = $system->check($_POST['message']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($message) >= 3 && $system->utf_strlen($message) < 10000) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `mail_message` SET `message`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($message, time(), $act['id']));	

// Обновляем сообщение в избранном

    DB :: $dbh -> query("UPDATE `mail_favorites` SET `message`=? WHERE `last_id`=? LIMIT 1;", array($message, $act['id']));
	
// Обновляем сообщение в спаме

    DB :: $dbh -> query("UPDATE `mail_spam` SET `message`=? WHERE `last_id`=? LIMIT 1;", array($message, $act['id']));	
	
// Уведомляем

    $system->redirect("Сообщение успешно отредактировано", "/modules/mail/contact/".$act['profile']."");
	
// Выводим ошибки

    } else { $system->show("Слишком длинное или короткое сообщение"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    }

// Выводим форму	
	
    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$text->check($act['message']).'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$act['ip'].' :: '.$act['ua'].'' : '').'
    </div>
    ';

// Выводим форму

    echo $system->form('/modules/mail/edit_message/'.$act['id'].'', ''.$act['message'].'', 'Редактировать', 'Сообщение', '10000', 'comment', '', ''.$user['sid'].'', 'message'); 
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранное вами сообщение не существует"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>