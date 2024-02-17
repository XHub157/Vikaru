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

    if ($user['access'] > 0 && $user['access'] < 4) {	
	
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
	
    if ($system->utf_strlen($description) < 200) {	
	
// Добавляем форум в базу
	
    DB :: $dbh -> query("INSERT INTO `forum` (`name`, `description`, `user`, `time`) VALUES (?, ?, ?, ?);", array($name, $description, $user['id'], time()));
	
// Уведомляем

    $system->redirect("Форум успешно добавлен", "/modules/forum/");

// Выводим ошибки

    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/forum/");  
    }	
	
// Выводим блок

    echo '
    <div class="block">
    <form method="post">	
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (200 символов) <br />
    <textarea name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>