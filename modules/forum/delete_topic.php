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

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная тема существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 4) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Удаляем репосты тем

    DB :: $dbh -> query("UPDATE `diary` SET `share`=?, `section`=? WHERE `share`=? AND `section`=?;", array(0, 0, $act['id'], 1));	
	
// Удаляем лайки комментариев из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_comments_like` WHERE `topic`=?;", array($act['id']));	
	
// Удаляем комментарии из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_comments` WHERE `topic`=?;", array($act['id']));	

// Удаляем лайки темы

    DB :: $dbh -> query("DELETE FROM `forum_topic_like` WHERE `topic`=?;", array($act['id']));	

// Удаляем просмотры из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_topic_view` WHERE `topic`=?;", array($act['id']));	

// Удаляем тему из базы
	
    DB :: $dbh -> query("DELETE FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем данные

    DB :: $dbh -> query("UPDATE `forum_section` SET `topics`=`topics`-1 WHERE `id`=?", array($act['section']));	

// Уведомляем

    $system->redirect("Тема успешно удалёна", "/modules/forum/section/".$act['section']."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/forum/topic/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/topic.png"> 
    <a href="/modules/forum/topic/'.$act['id'].'">'.$act['name'].'</a>
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
    } else { $system->show("Выбраная вами тема не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>