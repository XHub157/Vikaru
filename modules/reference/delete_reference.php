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

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {
	
// Ищим статью в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `reference` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная статья существует
	
    if (!empty($act)) {			
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем рейтинг из базы
	
    DB :: $dbh -> query("DELETE FROM `reference_rating` WHERE `reference`=?;", array($act['id']));

// Удаляем просмотры из базы
	
    DB :: $dbh -> query("DELETE FROM `reference_view` WHERE `reference`=?;", array($act['id']));	

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `reference` WHERE `id`=? LIMIT 1;", array($act['id']));		

// Уведомляем

    $system->redirect("Статья успешно удалёна", "/modules/reference/");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/reference/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/reference.png"> 
    <a href="/modules/reference/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки


    } else { $system->show("Выбранная вами статья не существует"); } 	
    } else { $system->show("Отказано в доступе"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>