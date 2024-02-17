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

    $title = 'Почта';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка автоотвечика

    $hello_mail = $system->check($_POST['hello_mail']);	

// Обработка прав доступа
	
    $access_mail = abs(intval($_POST['access_mail']));
	
// Обработка количества символов автоотвечика
	
    if ($system->utf_strlen($hello_mail) <= 200) {	

// Обработка приватности
	
    if ($access_mail == 0 || $access_mail == 1 || $access_mail == 2 || $access_mail == 3) {	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `access_mail`=?, `hello_mail`=?  WHERE `id`=? LIMIT 1;", array($access_mail, $hello_mail, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/mail");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов прав доступа"); }
    } else { $system->show("Слишком длинное или короткое сообщение автоотвечика"); }		
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '
    <div class="title">
    Настройки почты
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Автоответчик: (200 символов) <br />
    <input type="text" name="hello_mail" style="width: 70%;" value="'.$user['hello_mail'].'"/> <br />
    </div>
    <div class="block">
    Принимать почту: <br />
    <input type="radio" class="middle" name="access_mail" value="0" '.($user['access_mail'] == 0 ? 'checked="checked"':'').'> От всех <br />
    <input type="radio" class="middle" name="access_mail" value="1" '.($user['access_mail'] == 1 ? 'checked="checked"':'').'> От друзей <br />
    <input type="radio" class="middle" name="access_mail" value="2" '.($user['access_mail'] == 2 ? 'checked="checked"':'').'> От известных контактов <br />	
    <input type="radio" class="middle" name="access_mail" value="3" '.($user['access_mail'] == 3 ? 'checked="checked"':'').'> Не принимать почту <br />
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