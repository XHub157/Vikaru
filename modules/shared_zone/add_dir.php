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

// Проверяем права доступа

    if ($user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {
	
// Проверяем количество папок

    $count_dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `dir`=?;", array(0));

// Только если папок меньше 20
	
    if ($count_dir < 20) {		

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $name = $system->check($_POST['name']);	
	
// Обработка содержимого на загрузку файлов

    $upload = (empty($_POST['upload'])) ? 0 : 1;	
	
// Обработка содержимого на метку 18+

    $censored = (empty($_POST['censored'])) ? 0 : 1;		

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 100) {	

// Проверяем имя папки

    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `name`=? AND `dir`=?;", array($name, 0));

// Только если имя свободно	

    if (empty($dir)) {	
	
// Добавляем папку в базу
	
    DB :: $dbh -> query("INSERT INTO `shared_zone` (`name`, `upload`, `censored`, `user`, `time`) VALUES (?, ?, ?, ?, ?);", array($name, $upload, $censored, $user['id'], time()));	

// Уведомляем

    $system->redirect("Папка успешно добавлена", "/modules/shared_zone/");	
	
// Выводим ошибки
    
    } else { $system->show("Уже есть папка под именем ".$name.""); }	
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/shared_zone/");  
    }

// Выводим блок

    echo '
    <div class="block">
    <form method="post">	
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (100 символов) <br />
    <input type="text" name="name" value=""/> <br />
    </div>
    <div class="block">
    <input type="checkbox" class="middle" name="upload" value="1"/> Разрешить загрузку файлов <br />
    <input type="checkbox" class="middle" name="censored" value="1"/> Только для взрослых <br />
    </div>	
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("В этом папке нельзя создать больше папок"); }	
    } else { $system->show("Отказано в доступе"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	