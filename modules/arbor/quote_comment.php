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

    $queryguest = DB :: $dbh -> query("SELECT * FROM `arbor` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `user`=? AND `time`>?;", array($user['id'], time()-60));

// Только если было меньше $config['antiflood_creation'] комментариев зв 60 секунд	
	
    if ($antiflood < $config['antiflood_creation']) {		

// Запрещяем отвечать на свой комментарий

    if ($act['user'] != $user['id']) {	

// Запрещаем цитировать скрытые комментарии

    if ($act['hide'] == 0) {	
	
// Только если отправлен POST запрос	
	
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

// Делаем запрос в базу	
	
    DB :: $dbh -> query("INSERT INTO `arbor` (`user`, `answer`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($user['id'], $act['user'], $quote, time(), $system->ip(), $system->ua()));	

// Уведомление в журнал

    $system->journal("".$user['id']."", "".substr($act['comment'], 0, 50)."", "/modules/arbor/", "".$act['user']."", "1");
	
// Уведомляем

    $system->redirect("Комментарий успешно добавлен", "/modules/arbor/");

// Выводим ошибки

    } else { $system->show("Слишком длинный или короткий комментарий"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    }	
	
// Выводим блок

    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$text->check($act['comment']).'
    </div>';
    		
// Выводим форму

    echo $system->form('/modules/arbor/quote_comment/'.$act['id'].'', '', 'Цитировать', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment'); 

// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами комментарий не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>