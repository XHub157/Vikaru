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

    $title = 'Удалить';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `friends` WHERE `profile`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данная заявка существует
	
    if (!empty($act)) {
	
// Проверяем доступ

    if ($act['user'] == $user['id']) {		
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Делаем запрос в базу

    DB :: $dbh -> query("DELETE FROM `friends` WHERE `user`=? AND `profile`=? LIMIT 1;", array($act['profile'], $act['user']));	
    DB :: $dbh -> query("DELETE FROM `friends` WHERE `user`=? AND `profile`=? LIMIT 1;", array($act['user'], $act['profile']));	
	
// Уведомление в журнал

    $system->journal("0", "Сожелем ".$user['first_name']." ".$user['last_name']." больше не является вашим другом.", "/modules/friends/user/".$act['profile']."", "".$act['profile']."", "0");		

// Уведомляем

    $system->redirect("Пользователь успешно удалён из списка друзей", "/modules/friends/delete");	
	
// Выводим ошибки

    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/friends/delete");  
    }
	
// Выводим форму

    echo '
    <div class="block">
    Вы действительно хотите удалить пользователя '.$profile->user($act['profile']).' из друзей?
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