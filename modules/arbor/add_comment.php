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
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев зв 60 секунд	
	
    if ($antiflood < $config['antiflood_creation']) {		
	
// Только если данные отправлены POST запросом	
	
    if (isset($_POST['comment'])) {

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка текста	
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	
	
// Делаем запрос в базу	
	
    DB :: $dbh -> query("INSERT INTO `arbor` (`user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?);", array($user['id'], $comment, time(), $system->ip(), $system->ua()));

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($comment, 0, 50)."", "/modules/arbor/", "1");

// Уведомляем	
	
    $system->redirect("Комментарий успешно добавлен", "/modules/arbor/");	
	
// В случаи ошибки перенаправляем
	
    } else { $system->redirect("Слишком длинный или короткий комментарий", "/modules/arbor/"); }
    } else { $system->redirect("Замечена подозрительная активность, повторите действие", "/modules/arbor/"); }
    } else { $system->redirect("Отказано в доступе", "/modules/arbor/"); }
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/arbor/"); }	

?>