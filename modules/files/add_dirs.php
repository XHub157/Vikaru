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

// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная папка существует
	
    if (!empty($act)) {	

// Проверяем права

    if ($act['user'] == $user['id']) {

// Проверяем количество папок в папке

    $count_dirs = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `dir`=? AND `user`=?;", array($act['id'], $user['id']));

// Только если папок менее 20
	
    if ($count_dirs < 20) {

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `time`>? AND `user`=?;", array(time()-$config['antiflood_creation'], $user['id']));

// Только если был создана папка в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {		
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $name = $system->check($_POST['name']);	

// Обработка описания
	
    $description = $system->check($_POST['description']);	

// Обработка прав доступа

    $access = abs(intval($_POST['access']));

// Проверяем имя папки

    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `name`=? AND `user`=?;", array($name, $user['id']));

// Только если имя свободно	

    if (empty($dir)) {

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 10000) {	

// Проверка доступа
	
    if ($access == 0 || $access == 1) {		
	
// Добавляем папку в базу
	
    DB :: $dbh -> query("INSERT INTO `files_dir` (`dir`, `name`, `description`, `access`, `user`, `time`) VALUES (?, ?, ?, ?, ?, ?);", array($act['id'], $name, $description, $access, $user['id'], time()));

// Обновляем данные

    DB :: $dbh -> query("UPDATE `files_dir` SET `dirs`=`dirs`+1 WHERE `id`=?", array($act['id']));	

// Уведомляем

    $system->redirect("Папка успешно добавлена", "/modules/files/dir/".$act['id']."");	
	
// Выводим ошибки
    
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); } 	
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("У вас уже есть папка под именем ".$name.""); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/files/dir/$act[id]");  
    }

// Выводим блок

    echo '
    <div class="block">
    <img class="middle" src="/icons/files.png"> Папка <a href="/modules/files/dir/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <div class="block">
    <form method="post">	
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    Доступна: <br />
    <input type="radio" class="middle" name="access" value="0" '.($act['access'] == 0 ? 'checked="checked"':'').' /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1" '.($act['access'] == 1 ? 'checked="checked"':'').' /> Моим друзьям <br />	
    <input type="radio" class="middle" name="access" value="2" '.($act['access'] == 2 ? 'checked="checked"':'').' /> Только мне <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("В этой папке нельзя создать больше папок"); }	
    } else { $system->show("Отказано в доступе"); }
    } else { $system->show("Выбранная вами папка не существует"); }  	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		