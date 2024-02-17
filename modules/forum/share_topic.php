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

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `time`>? AND `user`=?;", array(time()-$config['antiflood_creation'], $user['id']));

// Только если был создан дневник в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная тема существует
	
    if (!empty($act)) {	

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
	
    if ($access == 0 || $access == 1) {		
	
// Проверка комментирования
	
    if ($comment == 0 || $comment == 1) {	
	
// Добавляем дневник в базу
	
    DB :: $dbh -> query("INSERT INTO `diary` (`name`, `description`, `share`, `section`, `access`, `comment`, `censored`, `user`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", array($name, $description, $act['id'], 1, $access, $comment, $censored,  $user['id'], time(), $system->ip(), $system->ua()));

// Получаем id дневника
 
    $id = DB :: $dbh -> lastInsertId();	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($name, 0, 50)."", "/modules/diary/".$id."", "4");	

// Уведомляем

    $system->redirect("Дневник успешно добавлен", "/modules/diary/".$id."");	
	
// Выводим ошибки
    
    } else { $system->show("Пожалуйста, выберите один из вариантов прав комментирования"); } 
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); } 	
    } else { $system->show("Слишком длинное или короткое описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/forum/topic/$act[id]");  
    }

// Выводим блок

    echo '
    <div class="block">
    <div class="quote">
    '.$profile->user($act['user']).' :: 
    <a href="/modules/forum/topic/'.$act['id'].'"> 
    '.$act['name'].' </a>
    </div>
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (10000 символов) <br />
    <textarea cols="25" rows="3" name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    Доступен: <br />
    <input type="radio" class="middle" name="access" value="0" checked="checked" /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1"/> Только мне <br />	
    </div>
    <div class="block">
    Комментирование доступно: <br />
    <input type="radio" class="middle" name="comment" value="0" checked="checked" /> Всем <br />   
    <input type="radio" class="middle" name="comment" value="1"/> Только мне <br />	
    </div>	
    <div class="block">
    <input type="checkbox" class="middle" name="censored" value="1"/> Только для взрослых <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки
 
    } else { $system->show("Выбранная вами тема не существует"); } 		
    } else { $system->show("Не так быстро, подождите немного"); }
	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	