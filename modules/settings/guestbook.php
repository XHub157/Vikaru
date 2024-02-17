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

    $title = 'Гостевая';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $access_guestbook = abs(intval($_POST['access_guestbook']));

// Обработка приватности
	
    if ($access_guestbook == 0 || $access_guestbook == 1 || $access_guestbook == 2) {	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `access_guestbook`=?  WHERE `id`=? LIMIT 1;", array($access_guestbook, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/guestbook");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '
    <div class="title">
    Настройки гостевой
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Комментировать имеют право: <br />
    <input type="radio" class="middle" name="access_guestbook" value="0" '.($user['access_guestbook'] == 0 ? 'checked="checked"':'').'> Все <br />
    <input type="radio" class="middle" name="access_guestbook" value="1" '.($user['access_guestbook'] == 1 ? 'checked="checked"':'').'> Моим друзьям <br />
    <input type="radio" class="middle" name="access_guestbook" value="2" '.($user['access_guestbook'] == 2 ? 'checked="checked"':'').'> Только я <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		