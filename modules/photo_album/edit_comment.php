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

// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_comments` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
    if (empty($antiflood)) {		
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5) {
	
// Только если отправлен POST запрос	
	
    if (isset($_POST['comment'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Обработка комментария
	
    $comment = $system->check($_POST['comment']);	
	
// Обработка количества символов
	
    if ($system->utf_strlen($comment) >= 3 && $system->utf_strlen($comment) < 10000) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `photo_comments` SET `comment`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($comment, $user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Комментарий успешно отредактирован", "/modules/photo_album/photo/".$act['photo']."");
	
// Выводим ошибки

    } else { $system->show("Слишком длинный или короткий комментарий"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    }

// Выводим форму	
	
    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$text->check($act['comment']).'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$act['ip'].' :: '.$act['ua'].'' : '').'
    </div>
    '.($act['edit'] > 0 ? '
    <div class="hide">
    <img class="middle" src="/icons/edit.png">
    Последний раз редактировалось: '.$system->system_time($act['edit_time']).'
    '.$profile->login($act['edit']).'
    </div>
    ' : '').'
    ';

// Выводим форму

    echo $system->form('/modules/photo_album/photo/edit_comment/'.$act['id'].'', ''.$act['comment'].'', 'Редактировать', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment'); 
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами комментарий не существует"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>