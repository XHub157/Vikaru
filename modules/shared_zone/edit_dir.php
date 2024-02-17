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

// Проверяем права доступа

    if ($user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {

// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `shared_zone` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная папка существует
	
    if (!empty($act)) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
    if (empty($antiflood)) {		

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

    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `shared_zone` WHERE `name`=? AND `dir`=? AND `id`!=?;", array($name, $act['dir'], $act['id']));

// Только если имя свободно	

    if (empty($dir)) {		

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `shared_zone` SET `name`=?, `upload`=?, `censored`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $upload, $censored,  $user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Папка успешно отредактирована", "/modules/shared_zone/dir/".$act['id']."");	
	
// Выводим ошибки
  
    } else { $system->show("Уже есть папка под именем ".$name.""); }	
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/shared_zone/dir/$act[id]");  
    }	

// Выводим форму

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (100 символов) <br />
    <input type="text" name="name" value="'.$act['name'].'"/> <br />
    </div>
    <div class="block">
    <input type="checkbox" class="middle" name="upload" value="1" '.($act['upload'] == 1 ? 'checked="checked"':'').' /> Разрешить загрузку файлов <br />
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
 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранная вами папка не существует"); } 
    } else { $system->show("Отказано в доступе"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	