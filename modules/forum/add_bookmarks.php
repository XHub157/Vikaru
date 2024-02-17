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

    $title = 'Закладки';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данная тема существует
	
    if (!empty($act)) {
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Обработка названия
	
    $name = $system->check($_POST['name']);		
	
// Обработка приватности	
	
    $access = abs(intval($_POST['access']));	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Проверка доступа
	
    if ($access == 0 || $access == 1) {			
	
// Проверяем закладки пользователя

    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=? AND `user`=?;", array(4, $act['id'], $user['id']));	

// Только если такой закладки нет	
	
    if (empty($bookmarks)) {
	
// Формируем ссылку

    $url = '/modules/forum/topic/'.$act['id'].'';

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `bookmarks` (`section`, `element`, `name`, `url`, `access`, `user`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(4, $act['id'], $name, $url, $access, $user['id'], time()));

// Уведомляем

    $system->redirect("Тема успешно добавлена в закладки", "/modules/forum/topic/".$act['id']."");	
	
// Выводим ошибки

    } else { $system->show("Данная тема уже добавлена в закладки"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); }	
    } else { $system->show("Слишком длинное или короткое название"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/forum/topic/$act[id]");  
    }
	
// Выводим форму

    echo '
    <div class="hide">
    Добавить в закладки: <img class="middle" src="/icons/topic.png"> 
    <a href="/modules/forum/topic/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    </div>
    <div class="block">
    Закладка видна: <br />
    <input type="radio" class="middle" name="access" value="0" checked="checked" /> Всем <br />   
    <input type="radio" class="middle" name="access" value="1"/> Только мне <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Выбранная вами тема не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	