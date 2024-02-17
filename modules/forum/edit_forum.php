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

    $title = 'Редактирование';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 4) {	
	
// Ищим раздел в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный раздел существует
	
    if (!empty($act)) {	
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

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

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {		
	
// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 200) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `forum` SET `name`=?, `description`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $description, $user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Раздел успешно отредактирован", "/modules/forum/".$act['id']."");

// Выводим ошибки

    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/forum/$act[id]");  
    }	
	
// Выводим форму

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value="'.$act['name'].'"/> <br />
    Описание: (200 символов) <br />
    <textarea name="description" class="textarea" />'.$act['description'].'</textarea> <br />
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

    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("Выбранный вами раздел не существует"); } 	
    } else { $system->show("Отказано в доступе"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>