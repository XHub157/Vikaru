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

// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный дневник существует
	
    if (!empty($act)) {
	
// Обработка функции Share

    if ($act['share'] > 0) {
    $section = ($act['section'] == 0) ? 'diary' : 'forum_topic';
    $share = DB :: $dbh -> queryFetch("SELECT `id`, `user`, `name`, `description`, `time`, `edit_time` FROM `".$section."` WHERE `id`=? LIMIT 1;", array($act['share']));
    }

// Функция удаления репоста

    if (isset($_POST['delete'])) {
	
// Удаляем только существующие репосты

    if ($act['share'] > 0) {	

// Проверяем sid

    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Запрос в базу

    DB :: $dbh -> query("UPDATE `diary` SET `share`=?, `section`=? WHERE `id`=? LIMIT 1;", array(0, 0, $act['id']));

// Уведомляем 

    $system->redirect("Репост успешно удалён", "/modules/diary/edit_diary/".$act['id']."");		

// Выводим ошибки

    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else { $system->show("Выбранный вами репост не существует"); }	
    }
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $act['id']));

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
	
// Обработка описания
	
    $description = $system->check($_POST['description']);	

// Обработка прав доступа

    $access = abs(intval($_POST['access']));	  

// Обработка прав комментирования

    $comment = abs(intval($_POST['comment']));	

// Обработка содержимого на метку 18+

    $censored = (empty($_POST['censored'])) ? 0 : 1;	

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) >= 3 && $system->utf_strlen($description) < 10000) {	

// Проверка доступа
	
    if ($access == 0 || $access == 1 || $access == 2) {	

// Проверка комментирования
	
    if ($comment == 0 || $comment == 1 || $comment == 2) {		

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `diary` SET `name`=?, `description`=?, `access`=?, `comment`=?, `censored`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($name, $description, $access, $comment, $censored,  $user['id'], time(), $act['id']));

// Обновляем время редактирования репостов

    DB :: $dbh -> query("UPDATE `diary` SET `share_edit`=? WHERE `share`=? AND `section`=?;", array(time(), $act['id'], 0));	
	
// Уведомляем

    $system->redirect("Дневник успешно отредактирован", "/modules/diary/".$act['id']."");	
	
// Выводим ошибки
  
    } else { $system->show("Пожалуйста, выберите один из вариантов прав комментирования"); } 
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); } 	
    } else { $system->show("Слишком длинное или короткое описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/diary/$act[id]");  
    }

// Выводим форму

    echo '
    '.($act['share'] > 0 ? '
    <div class="block">
    <div class="quote">
    '.$profile->user($share['user']).' :: 
    <a href="/modules/'.($act['section'] == 0 ? 'diary/' : 'forum/topic/').''.$share['id'].'">
    '.$share['name'].' </a>	<br />
    '.($share['edit_time'] > 0 ? '
    Внимание! Объект был изменён после добавления.<br />
    Последний раз редактировался '.$system->system_time($share['edit_time']).'
    ' : '').'
    </div>
    </div>
    ' : '').'
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value="'.$act['name'].'"/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" />'.$act['description'].'</textarea> <br />
    </div>	
    <div class="block">
    Доступен: <br />
    <input type="radio" class="middle" name="access" value="0" '.($act['access'] == 0 ? 'checked="checked"':'').' /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1" '.($act['access'] == 1 ? 'checked="checked"':'').' /> Моим друзьям <br />	
    <input type="radio" class="middle" name="access" value="2" '.($act['access'] == 2 ? 'checked="checked"':'').' /> Только мне <br />
    </div>
    <div class="block">
    Комментирование доступно: <br />
    <input type="radio" class="middle" name="comment" value="0" '.($act['comment'] == 0 ? 'checked="checked"':'').' /> Всем <br />   
    <input type="radio" class="middle" name="comment" value="1" '.($act['comment'] == 1 ? 'checked="checked"':'').'/> Моим друзьям <br />
    <input type="radio" class="middle" name="comment" value="2" '.($act['comment'] == 2 ? 'checked="checked"':'').'/> Только мне <br />	
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
    ' : '
    ').'
    <div class="block">	
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    '.($act['share'] > 0 ? '
    <input type="submit" name="delete" value="Удалить Репост" />
    ':'').'
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами дневник не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	