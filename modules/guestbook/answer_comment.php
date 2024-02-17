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

    $title = 'Ответ';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `guestbook_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	

// Проверяем доступ

    $guestbook = DB :: $dbh -> queryFetch("SELECT `access_guestbook` FROM `user` WHERE `id`=? LIMIT 1;", array($act['profile']));
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев зв 60 секунд
	
    if ($antiflood < $config['antiflood_creation']) {

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($act['profile'], $user['id']));	
	
// Запрещаем комментировать закрытую гостевую

    if ($guestbook['access_guestbook'] == 0 || $act['profile'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $guestbook['access_guestbook'] == 1 && !empty($friends)) {		

// Запрещяем отвечать на свой комментарий

    if ($act['user'] != $user['id']) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `guestbook_comments` (`profile`, `user`, `answer`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array($act['profile'], $user['id'], $act['user'], $comment, time(), $system->ip(), $system->ua()));

// Уведомление в журнал

    $system->journal("".$user['id']."", "".substr($act['comment'], 0, 50)."", "/modules/guestbook/".$act['profile']."", "".$act['user']."", "1");

    if ($act['user'] != $act['profile'] && $act['profile'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($act['comment'], 0, 50)."", "/modules/guestbook/".$act['profile']."", "".$act['profile']."", "1");	

    }		

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/guestbook/".$act['profile']."");	

// Выводим ошибки

    } else { $system->show("Слишком длинный или короткий комментарий"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    }
	
// Проверяем права на сообщение	

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 
   
    } else if ($act['hide'] > 0 && $act['profile'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }

// Выводим блок

    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$comment.'
    </div>';

// Выводим форму

    echo $system->form('/modules/guestbook/answer_comment/'.$act['id'].'', '', 'Ответить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment'); 	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами комментарий не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>