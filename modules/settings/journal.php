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

    $title = 'Журнал';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $notice_journal = abs(intval($_POST['notice_journal']));

// Обработка приватности
	
    if ($notice_journal == 0 || $notice_journal == 1) {	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `notice_journal`=?  WHERE `id`=? LIMIT 1;", array($notice_journal, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/journal");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов уведомления"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '

    <div class="title">
    Настройки журнала
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Посылать оповещения в журнал:  <br />
    <input type="radio" class="middle" name="notice_journal" value="0" '.($user['notice_journal'] == 0 ? 'checked="checked"':'').'> Да <br />
    <input type="radio" class="middle" name="notice_journal" value="1" '.($user['notice_journal'] == 1 ? 'checked="checked"':'').'> Нет <br />
    <hr>
    Внимание! данная функция будет оповещать вас о новых ответах на ваши комментарии или записи.
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		