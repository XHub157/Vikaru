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

    $title = 'Очистка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подсчёт

    $journal = DB :: $dbh -> querySingle("SELECT count(*) FROM `journal` WHERE `profile`=?;", array($user['id']));

// Только если оповещений больше 0

    if ($journal > 0) {
	
// Только если отправлен POST запрос
	
    if (isset($_POST['clean'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `journal` WHERE `profile`=?;", array($user['id']));	

// Уведомляем

    $system->redirect("Журнал успешно очищен", "/modules/journal/");
	
// Выводим ошибки	
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/settings/");
    }		
	
// Выводим блок
	
    echo '<div class="block">
    Вы действительно хотите удалить '.$journal.' уведомлений?
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="clean" value="Очистить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';

    } else { $system->show("Уведомлений нет"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>