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

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($act)) {
	
// Запрещяем добавлять самого себя

    if ($act['id'] != $user['id']) {	
	
// Проверяем являеться ли пользователь другом

    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `profile`=? AND `user`=?;", array($act['id'], $user['id']));

// Только если пользователь нет в друзьсях
	
    if (empty($friends)) {	
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Проверяем добавление заявки

    $friends_new = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends_new` WHERE `profile`=? AND `user`=?;", array($act['id'], $user['id']));

// Только если пользователь не отправлял заявку
	
    if (empty($friends_new)) {	

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `friends_new` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($user['id'], $act['id'], time()));
	
// Уведомление в журнал

    $system->journal("0", "Пользователь ".$user['first_name']." ".$user['last_name']." предлагает вам дружбу.", "/modules/friends/new", "".$act['id']."", "0");		

// Уведомляем

    $system->redirect("Заявка успешно отправлена", "/id".$act['id']."");	
	
// Выводим ошибки

    } else { $system->show("Вы уже отправили заявку данному пользователю"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /id$act[id]");  
    }
	
// Выводим форму

    echo '
    <div class="block">
    Вы действительно хотите добавить пользователя '.$profile->user($act['id']).' в друзья?
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="save" value="Да" />
    <input type="submit" name="back" value="Нет" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Данный пользователь уже является вашим другом"); }
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	