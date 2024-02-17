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

    $title = 'Цвет темы';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $site_themes = abs(intval($_POST['site_themes']));

// Обработка приватности
	
    if ($site_themes == 0 || $site_themes == 1 || $site_themes == 2) {	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `site_themes`=?  WHERE `id`=? LIMIT 1;", array($site_themes, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/site_themes");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов уведомления"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '
    <div class="title">
    Настройки темы
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Вариант цвета:  <br />
    <input type="radio" class="middle" name="site_themes" value="0" '.($user['site_themes'] == 0 ? 'checked="checked"':'').'> Черный (Стандартный) <br />
    <input type="radio" class="middle" name="site_themes" value="1" '.($user['site_themes'] == 1 ? 'checked="checked"':'').'> Розовый <br />
    <input type="radio" class="middle" name="site_themes" value="2" '.($user['site_themes'] == 2 ? 'checked="checked"':'').'> Зеленый <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>