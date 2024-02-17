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

    if ($user['access'] > 0 && $user['access'] < 4) {	
	
// Ищим раздел в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный раздел существует
	
    if (!empty($act)) {	
	
// Проверяем существуют ли подразделы
	
    $section = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_section` WHERE `forum`=?;", array($act['id']));   

// Только если подразделов нет

    if (empty($section)) { 		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `forum` WHERE `id`=? LIMIT 1;", array($act['id']));		

// Уведомляем

    $system->redirect("Раздел успешно удалён", "/modules/forum/");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/forum/");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/forum_section.png"> 
    <a href="/modules/forum/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки
 
    } else { $system->show("Данный раздел имеет подразделы"); }	
    } else { $system->show("Выбранный вами раздел не существует"); } 	
    } else { $system->show("Отказано в доступе"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>