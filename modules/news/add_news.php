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

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `news` WHERE `time`>?;", array(time()-$config['antiflood_creation']));

// Только если не было изменений в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {	
	
// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $name = $system->check($_POST['name']);		
	
// Обработка описания
	
    $description = $system->check($_POST['description']);	

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {		
	
// Обработка количества символов описания
	
    if ($system->utf_strlen($description) >= 3 && $system->utf_strlen($description) < 10000) {	
	
// Добавляем новость в базу
	
    DB :: $dbh -> query("INSERT INTO `news` (`name`, `description`, `user`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($name, $description, $user['id'], time(), $system->ip(), $system->ua()));
	
// Получаем id новости
 
    $id = DB :: $dbh -> lastInsertId();	

// Формируем заголовок пользователям

    $show = "<a href='/modules/news/".$id."'>".$name."</a>";
    file_put_contents(SERVER."/system/show.dat", $show, LOCK_EX);

// Добавляем новость в информационное окно	
	
    DB :: $dbh -> query("UPDATE `user` SET `show`=?;", array($show));	
	
// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($name, 0, 50)."", "/modules/news/".$id."", "2");	
	
// Уведомляем

    $system->redirect("Новость успешно добавлена", "/modules/news/".$id."");

// Выводим ошибки

    } else { $system->show("Слишком длинное или короткое описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/news/");  
    }	
	
// Выводим блок

    echo '
    <div class="block">
    <form method="post">		
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Отказано в доступе"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>