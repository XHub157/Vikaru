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

    $title = 'Редактировать';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная папка существует
	
    if (!empty($act)) {
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
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

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 10000) {

// Проверяем имя папки

    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `name`=? AND `user`=? AND `id`!=?;", array($name, $user['id'], $act['id']));

// Только если имя свободно	

    if (empty($dir)) {	
	
// Проверка доступа
	
    if ($access == 0 || $access == 1 || $access == 2) {		

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `files_dir` SET `name`=?, `description`=?, `access`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $description, $access, $user['id'], time(), $act['id']));	
	
// Обновляем статус файлов в папке	
	
    DB :: $dbh -> query("UPDATE `files` SET `access`=?  WHERE `dir`=?", array($access, $act['id']));	
	
// Уведомляем

    $system->redirect("Папка успешно отредактирована", "/modules/files/dir/".$act['id']."");

// Выводим ошибки
    
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); } 
    } else { $system->show("У вас уже есть папка под именем ".$name.""); }		
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/files/dir/$act[id]");  
    }	
	
// Выводим форму

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value="'.$act['name'].'"/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" />'.$act['description'].'</textarea> <br />
    </div>	
    <div class="block">
    Доступна: <br />
    <input type="radio" class="middle" name="access" value="0" '.($act['access'] == 0 ? 'checked="checked"':'').' /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1" '.($act['access'] == 1 ? 'checked="checked"':'').' /> Моим друзьям <br />
    <input type="radio" class="middle" name="access" value="2" '.($act['access'] == 2 ? 'checked="checked"':'').' /> Только мне <br />	
    </div>
    '.($act['edit'] > 0 ? '
    <div class="hide">
    <img class="middle" src="/icons/edit.png">
    Последний раз редактировалось: '.$system->system_time($act['edit_time']).'
    '.$profile->login($act['edit']).'
    </div>
    ' : '
    ').'
    <div class="block">	
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 
    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Выбранная вами папка не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		