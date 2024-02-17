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

// Ищим закладку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `bookmarks` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная закладка существует
	
    if (!empty($act)) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

// Только если не было изменений в течении $config['antiflood_edit'] секунд
	
    if (empty($antiflood)) {		
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3) {	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $name = $system->check($_POST['name']);		

// Обработка прав доступа

    $access = abs(intval($_POST['access']));	  

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Проверка доступа
	
    if ($access == 0 || $access == 1) {	

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `bookmarks` SET `name`=?, `access`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $access, $user['id'], time(), $act['id']));	
	
// Уведомляем

    $system->redirect("Закладка успешно отредактирована", "/modules/bookmarks/".$act['user']."");	
	
// Выводим ошибки
  
    } else { $system->show("Пожалуйста, выберите один из вариантов"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/bookmarks/$act[user]");  
    }	

// Выводим форму

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value="'.$act['name'].'"/> <br />
    </div>	
    <div class="block">
    Закладка видна: <br />
    <input type="radio" class="middle" name="access" value="0" '.($act['access'] == 0 ? 'checked="checked"':'').' /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1" '.($act['access'] == 1 ? 'checked="checked"':'').' /> Только мне <br />	
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
    } else { $system->show("Выбранная вами закладка не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	