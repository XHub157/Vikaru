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

    $title = 'Добавить';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим заявку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `friends_new` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данная заявка существует
	
    if (!empty($act)) {
	
// Проверяем доступ

    if ($act['profile'] == $user['id']) {		
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `friends` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['profile'], $act['user'], time()));
    DB :: $dbh -> query("INSERT INTO `friends` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['user'], $act['profile'], time()));
    DB :: $dbh -> query("DELETE FROM `friends_new` WHERE `user`=? AND `profile`=? LIMIT 1;", array($act['profile'], $act['user']));	
    DB :: $dbh -> query("DELETE FROM `friends_new` WHERE `user`=? AND `profile`=? LIMIT 1;", array($act['user'], $act['profile']));	
	
// Проверяем подписку

    $feed_user = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `user`=? AND `profile`=?;", array($act['user'], $act['profile']));	
    if (empty($feed_user)) {
    DB :: $dbh -> query("INSERT INTO `feed_user` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['user'], $act['profile'], time()));	
    } $feed_profile = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `user`=? AND `profile`=?;", array($act['profile'], $act['user']));
    if (empty($feed_profile)) {
    DB :: $dbh -> query("INSERT INTO `feed_user` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($act['profile'], $act['user'], time()));
    }
	
// Уведомление в журнал

    $system->journal("0", "Пользователь ".$user['first_name']." ".$user['last_name']." принял предложение вашей дружбы.", "/modules/friends/user/".$act['user']."", "".$act['user']."", "0");		

// Уведомляем

    $system->redirect("Пользователь успешно добавлен в друзья", "/modules/friends/user/".$user['id']."");	
	
// Выводим ошибки

    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/friends/new");  
    }
	
// Выводим форму

    echo '
    <div class="block">
    Вы действительно хотите добавить пользователя '.$profile->user($act['user']).' в друзья?
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="save" value="Да" />
    <input type="submit" name="back" value="Нет" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранная вами заявка не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	