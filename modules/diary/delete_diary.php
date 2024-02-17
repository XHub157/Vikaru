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
	
// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный дневник существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Удаляем репосты дневника

    DB :: $dbh -> query("UPDATE `diary` SET `share`=?, `section`=? WHERE `share`=? AND `section`=?;", array(0, 0, $act['id'], 0));	
	
// Удаляем лайки комментариев из базы
	
    DB :: $dbh -> query("DELETE FROM `diary_comments_like` WHERE `diary`=?;", array($act['id']));	
	
// Удаляем комментарии из базы
	
    DB :: $dbh -> query("DELETE FROM `diary_comments` WHERE `diary`=?;", array($act['id']));	

// Удаляем просмотры из базы
	
    DB :: $dbh -> query("DELETE FROM `diary_view` WHERE `diary`=?;", array($act['id']));	
	
// Удаляем лайки дневника из базы	

    DB :: $dbh -> query("DELETE FROM `diary_like` WHERE `diary`=?;", array($act['id']));

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `diary` WHERE `id`=? LIMIT 1;", array($act['id']));		

// Уведомляем

    $system->redirect("Дневник успешно удалён", "/modules/diary/user/".$act['user']."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/diary/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/diary.png"> <a href="/modules/diary/'.$act['id'].'">'.$act['name'].'</a>
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
    } else { $system->show("Выбранный вами дневник не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>