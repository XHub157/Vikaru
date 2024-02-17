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

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим сообщение в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данное сообщение существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $act['profile'] == $user['id']) {
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `mail_message` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем счётчики

    DB :: $dbh -> query("UPDATE `mail_contact` SET `message`=`message`-1 WHERE `user`=? AND `profile`=?", array($act['user'], $act['profile']));
    DB :: $dbh -> query("UPDATE `mail_contact` SET `message`=`message`-1 WHERE `user`=? AND `profile`=?", array($act['profile'], $act['user']));

// Уведомляем

    $system->redirect("Сообщение успешно удалено", "".($act['user'] == $user['id'] ? "/modules/mail/contact/".$act['profile']."" : "/modules/mail/contact/".$act['user']."")."");
	
// Выводим ошибки	
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: ".($act['user'] == $user['id'] ? "/modules/mail/contact/".$act['profile']."" : "/modules/mail/contact/".$act['user']."")."");
    }	
	
// Выводим блок
	
    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$text->check($act['message']).'
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Выбранное вами сообщение не существует"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>