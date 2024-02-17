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

// Проверяем количество подальбомов

    $count_album = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `album`=? AND `user`=?;", array(0, $user['id']));

// Только если альбомов мение 20
	
    if ($count_album < 20) {		

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `time`>? AND `user`=?;", array(time()-$config['antiflood_creation'], $user['id']));

// Только если был создан фотоальбом в течении $config['antiflood_creation'] секунд
	
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

// Проверка доступа
	
    if ($access == 0 || $access == 1 || $access == 2) {	

// Проверяем имя папки

    $album = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `name`=? AND `user`=?;", array($name, $user['id']));

// Только если имя свободно	

    if (empty($album)) {	
	
// Добавляем фотоальбом в базу
	
    DB :: $dbh -> query("INSERT INTO `photo_album` (`name`, `description`, `access`, `user`, `time`) VALUES (?, ?, ?, ?, ?);", array($name, $description, $access, $user['id'], time()));	

// Уведомляем

    $system->redirect("Фотоальбом успешно добавлен", "/modules/photo_album/".$user['id']."");	
	
// Выводим ошибки
    
    } else { $system->show("У вас уже есть альбом под именем ".$name.""); }		
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); } 	
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/photo_album/$user[id]");  
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
    Доступен: <br />
    <input type="radio" class="middle" name="access" value="0" checked="checked" /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1"/> Моим друзьям <br />	
    <input type="radio" class="middle" name="access" value="2"/> Только мне <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("В этом фотоальбоме нельзя создать больше фотоальбомов"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	