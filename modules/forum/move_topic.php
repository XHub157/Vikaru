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

    $title = 'Переместить';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 4) {
	
// Ищим раздел в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_section` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный раздел существует
	
    if (!empty($act)) {		

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array(abs(intval($_GET['add']))));
    $topic = $queryguest -> fetch();	

// Только если данная тема существует
	
    if (!empty($topic)) {	

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_system'], $topic['id']));

// Только если не было изменений в течении $config['antiflood_system'] секунд
	
    if (empty($antiflood)) {	

// Запрещаем перемещать в один и тот же раздел

    if ($topic['section'] != $act['id']) {	
	
// Получаем информацию о разделе	
	
    $section = DB :: $dbh -> queryFetch("SELECT `name` FROM `forum_section` WHERE `id`=? LIMIT 1;", array($topic['section']));		
	
// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum_topic` SET `section`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($act['id'], $user['id'], time(), $topic['id']));

// Отправляем предупреждение от системы

    $comment = 'Тема [b]"'.$topic['name'].'"[/b] перенесена '.($user['access'] > 0 && $user['access'] < 3 ? 'администратором' : 'модератором').' '.$profile->login($user['id']).', из [b]"'.$section['name'].'"[/b] в [b]"'.$act['name'].'"[/b]';	
	
// Добавляем сообщение в базу

    DB :: $dbh -> query("INSERT INTO `forum_comments` (`topic`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($topic['id'], 0, $comment, time(), $system->ip(), $system->ua()));		

// Обновляем информацию в теме

    DB :: $dbh -> query("UPDATE `forum_topic` SET `last_user`=? WHERE `id`=? LIMIT 1;", array(0, $topic['id']));

// Уведомляем

    $system->redirect("Тема успешно перенесена", "/modules/forum/topic/".$topic['id']."");
	
// Выводим ошибки

    } else { $system->redirect("Выберите другой раздел", "/modules/forum/topic/".$topic['id'].""); }	
    } else { $system->redirect("Не так быстро, подождите немного", "/modules/forum/topic/".$topic['id'].""); }	
    } else { $system->redirect("Выбранная вами тема не существует", "/modules/forum/"); }
    } else { $system->redirect("Выбранный вами раздел не существует", "/modules/forum/"); }	
    } else { $system->redirect("Отказано в доступе", "/modules/forum/"); }	

?>