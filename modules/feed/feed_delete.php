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
	
// Ищим подписку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `feed_user` WHERE `profile`=? AND `user`=? LIMIT 1;", array($id, $user['id']));
    $act = $queryguest -> fetch();	
	
// Только если данная подписка существует
	
    if (!empty($act)) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `feed_user` WHERE `id`=? LIMIT 1;", array($act['id']));		

// Уведомляем

    $system->redirect("Пользователь успешно удалён", "/modules/feed/my");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/feed/my");
    }	
	
// Выводим блок

    echo '<div class="block">
    Удалить из подписок 
    '.$profile->user($act['profile']).'?
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки

    } else { $system->show("Выбранная вами подписка не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>