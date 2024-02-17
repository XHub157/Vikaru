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
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Проверяем добавлял ли пользователь в ленту

    $feed = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `profile`=? AND `user`=?;", array($act['id'], $user['id']));

// Только если пользователь ещё не добавлял в ленту
	
    if (empty($feed)) {

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `feed_user` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($user['id'], $act['id'], time()));

// Уведомляем

    $system->redirect("Пользователь успешно добавлен в ленту", "/id".$act['id']."");	
	
// Выводим ошибки

    } else { $system->show("Данный пользователь уже добавлен в вашу ленту"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /id$act[id]");  
    }
	
// Выводим форму

    echo '
    <div class="hide">
    После добавления пользователя в ленту, <br />
    вы будете получать уведомления о всех новых темах, <br />
    добавляемых файлах и других действиях. <br />
    Добавляйте в ленту только тех пользователей, которые вам действительно интересны.
    </div>
    <div class="block">
    Вы действительно хотите добавить пользователя '.$profile->user($act['id']).' в ленту?
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
    } else { $system->show("Выбранный вами пользователь не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	