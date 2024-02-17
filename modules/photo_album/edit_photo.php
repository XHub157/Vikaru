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

// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данное фото существует
	
    if (!empty($act)) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
    if (empty($antiflood)) {	

// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $name = $system->check($_POST['name']);		
	
// Обработка описания
	
    $description = $system->check($_POST['description']);	

// Обработка содержимого на метку 18+

    $censored = (empty($_POST['censored'])) ? 0 : 1;	  

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 10000) {

// Проверяем имя фото

    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `name`=? AND `user`=? AND `id`!=?;", array($name, $user['id'], $act['id']));

// Только если имя свободно	

    if (empty($photo)) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `photo` SET `name`=?, `description`=?, `censored`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $description, $censored, $user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Фото успешно отредактирован", "/modules/photo_album/photo/".$act['id']."");

// Выводим ошибки
     
    } else { $system->show("У вас уже есть фото под именем ".$name.""); }		
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/photo_album/photo/$act[id]");  
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
    <input type="checkbox" class="middle" name="censored" value="1" '.($act['censored'] == 1 ? 'checked="checked"':'').' /> Только для взрослых <br />
    </div>
    '.($act['edit'] > 0 ? '
    <div class="hide">
    <img class="middle" src="/icons/edit.png">
    Последний раз редактировалось: '.$system->system_time($act['edit_time']).'
    '.$profile->login($act['edit']).'
    </div>
    ' : '').'	
    <div class="block">	
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранное вами фото не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		