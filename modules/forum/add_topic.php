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
	
// Ищим раздел в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_section` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный раздел существует
	
    if (!empty($act)) {		

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `time`>? AND `user`=?;", array(time()-$config['antiflood_creation'], $user['id']));

// Только если была создана тема в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {		
	
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
	
    if ($system->utf_strlen($description) >= 3 && $system->utf_strlen($description) < 10000) {	

// Добавляем тему в базу
	
    DB :: $dbh -> query("INSERT INTO `forum_topic` (`section`, `name`, `description`, `user`, `time`, `ip`, `ua`, `censored`, `last_user`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);", array($act['id'], $name, $description, $user['id'], time(), $system->ip(), $system->ua(), $censored, 0));	
	
// Получаем id темы
 
    $id = DB :: $dbh -> lastInsertId();	

// Обновляем данные

    DB :: $dbh -> query("UPDATE `forum_section` SET `topics`=`topics`+1 WHERE `id`=?", array($act['id']));

// Отправляем предупреждение от системы

    $comment = 'Тема [b]"'.$name.'"[/b] успешно создана, соблюдаем правила форума';	
	
// Добавляем сообщение в базу

    DB :: $dbh -> query("INSERT INTO `forum_comments` (`topic`, `user`, `comment`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?);", array($id, 0, $comment, time(), $system->ip(), $system->ua()));

// Обновляем комментарии

    DB :: $dbh -> query("UPDATE `forum_topic` SET `comments`=`comments`+1 WHERE `id`=?", array($id));	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($name, 0, 50)."", "/modules/forum/topic/".$id."", "3");	

// Уведомляем

    $system->redirect("Тема успешно создана", "/modules/forum/topic/".$id."");

// Выводим ошибки

    } else { $system->show("Слишком длинное или короткое описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/forum/section/$act[id]");  
    } 	
	
// Выводим форму

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
    <input type="checkbox" class="middle" name="censored" value="1"/> Только для взрослых <br />
    </div>	
    <div class="block">	
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 
    } else { $system->show("Выбранный вами раздел не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>