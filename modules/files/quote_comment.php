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

    $title = 'Цитирование';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Получаем информацию о файле

    $file = DB :: $dbh -> queryFetch("SELECT `name`, `access`, `user` FROM `files` WHERE `id`=? LIMIT 1;", array($act['file']));	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_comments` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев за 60 секунд
	
    if ($antiflood < $config['antiflood_creation']) {	

// Проверяем является ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($file['user'], $user['id']));	
	
// Запрещаем комментировать закрытый фотоальбом

    if ($file['access'] == 0 || $file['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $file['access'] == 1 && !empty($friends)) {			

// Запрещяем отвечать на свой комментарий

    if ($act['user'] != $user['id']) {	

// Запрещаем цитировать скрытые комментарии

    if ($act['hide'] == 0) {	
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка комментария
	
    $comment = $system->check($_POST['comment']);	

// Обработка тега [quote][/quote]

    $search = array("[quote]", "[/quote]");
    $replace = array(" ", " ");
    $check = str_replace($search, $replace, $act['comment']);

// Сумируем комментарии

    $quote = '[quote]'.$check.''.chr(10).'[/quote]'.$comment.'';	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Запрос в базу	

    DB :: $dbh -> query("INSERT INTO `files_comments` (`file`, `user`, `answer`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array($act['file'], $user['id'], $act['user'], $quote, time(), $system->ip(), $system->ua()));

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `files` SET `comments`=`comments`+1 WHERE `id`=?", array($act['file']));

// Уведомление в журнал

    $system->journal("".$user['id']."", "".substr($act['comment'], 0, 50)."", "/modules/files/file/".$act['file']."", "".$act['user']."", "6");

    if ($act['user'] != $file['user'] && $file['user'] != $user['id']) {

    $system->journal("".$user['id']."", "".substr($file['name'], 0, 50)."", "/modules/files/file/".$act['file']."", "".$file['user']."", "6");	

    }	

// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/files/file/".$act['file']."");	

// Выводим ошибки

    } else { $system->show("Слишком длинный или короткий комментарий"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    }

// Выводим блок

    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).'<br />
    '.$text->check($act['comment']).'
    </div>';

// Выводим форму

    echo $system->form('/modules/files/file/quote_comment/'.$act['id'].'', '', 'Цитировать', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment'); 	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами комментарий не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>