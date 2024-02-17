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

// Ищим комментарий в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary_comments` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();

// Только если данный комментарий существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5) {
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Удаляем лайки

    DB :: $dbh -> query("DELETE FROM `diary_comments_like` WHERE `comments`=? LIMIT 1;", array($act['id']));		

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `diary_comments` WHERE `id`=? LIMIT 1;", array($act['id']));

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `diary` SET `comments`=`comments`-1 WHERE `id`=?", array($act['diary']));	

// Уведомляем

    $system->redirect("Комментарий успешно удалён", "/modules/diary/".$act['diary']."");
	
// Выводим ошибки	
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/diary/$act[diary]");
    }	
	
// Выводим блок
	
    echo '<div class="block">
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' <br />
    '.$text->check($act['comment']).'
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
    } else { $system->show("Выбранный вами комментарий не существует"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>