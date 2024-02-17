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

    $title = 'Время';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных
	
    $timezone = $system->check($_POST['timezone']);
    $clock = abs(intval($_POST['clock']));
	
// Обработка временной зоны	
	
    if (preg_match('|^[\-\+]{0,1}[0-9]{1,2}$|', $timezone)) {	

// Обработка показа времени
	
    if ($clock == 0 || $clock == 1) {	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `timezone`=?, `clock`=?  WHERE `id`=? LIMIT 1;", array($timezone, $clock, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/clock");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов показа времени"); }
    } else { $system->show("Пожалуйста, укажите правильно ваш часовой пояс"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим часовые пояса

    $arrtimezone = array('-12', '-11', '-10', '-9', '-8', '-7', '-6', '-5', '-4', '-3', '-2', '-1', '0', '+1', '+2', '+3', '+5', '+6', '+7', '+8', '+9', '+10', '+11', '+12');	
	
// Выводим форму	

    echo '
    <div class="title">
    Настройки часового пояса сайта
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Отображать время: <br />
    <input type="radio" class="middle" name="clock" value="0" '.($user['clock'] == 0 ? 'checked="checked"':'').'> Да <br />
    <input type="radio" class="middle" name="clock" value="1" '.($user['clock'] == 1 ? 'checked="checked"':'').'> Нет <br />
    </div><div class="block">
    Часовой пояс:
    <select name="timezone">';
    foreach($arrtimezone as $zone) {
    echo '
    <option value="' . $zone . '" '.($user['timezone'] == $zone ? 'selected="selected"':'').'>
    ' . $zone . '
    </option>';
    }	
    echo '
    </select><br />
    <span style="color:#ff0000">
    * Временной сдвиг <span style="font-weight: bold;">0</span> по умолчанию является Киев
    </span></div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		