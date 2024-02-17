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

    $title = 'Настройки системы';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Только если отправлен POST запрос

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $registration = abs(intval($_POST['registration']));
    $money = abs(intval($_POST['money']));
    $post = abs(intval($_POST['post']));
    $copyright = ($user['access'] == 1) ? $system->check($_POST['copyright']) : $config['copyright'];
    $author = ($user['access'] == 1) ? $system->check($_POST['author']) : $config['author'];
    $generator = ($user['access'] == 1) ? $system->check($_POST['generator']) : $config['generator'];
    $keywords = ($user['access'] == 1) ? $system->check($_POST['keywords']) : $config['keywords'];
    $reply_to = ($user['access'] == 1) ? $system->check($_POST['reply_to']) : $config['reply_to'];
    $login_change = abs(intval($_POST['login_change']));
    $vip_change = abs(intval($_POST['vip_change']));
    $rating_change = abs(intval($_POST['rating_change']));
    $hide_change = abs(intval($_POST['hide_change']));
    $antiflood_creation = abs(intval($_POST['antiflood_creation']));
    $antiflood_edit = abs(intval($_POST['antiflood_edit']));
    $antiflood_system = abs(intval($_POST['antiflood_system']));	
	
// Обработка регистрации

    if ($registration == 0 || $registration == 1) {

// Обработка количества бонусных монет при регистрации

    if ($money >= 0 && $money < 101) {	
	
// Обработка количества контента на страницу

    if ($post > 2 && $post < 11) {	
	
// Обработка количества символов копирайта
	
    if ($system->utf_strlen($copyright) <= 500) {

// Обработка количества символов автора
	
    if ($system->utf_strlen($author) <= 100) {		

// Обработка количества символов generator
	
    if ($system->utf_strlen($generator) <= 500) {
	
// Обработка количества символов keywords
	
    if ($system->utf_strlen($keywords) <= 100) {

// Обработка количества символов reply_to
	
    if ($system->utf_strlen($reply_to) <= 100) {

// Обработка стоимости смены логина

    if ($login_change > 0 || $login_change < 101) {

// Обработка стоимости покупки вип статуса

    if ($vip_change > 0 || $vip_change < 101) {	
	
// Обработка стоимости покупки рейтинга

    if ($rating_change > 0 || $rating_change < 101) {	
	
// Обработка стоимости покупки статуса невидимки

    if ($hide_change > 0 || $hide_change < 101) {	
	
// Обработка антифлуда на создание

    if ($antiflood_creation > 0 || $antiflood_creation < 101) {

// Обработка антифлуда на редактирование

    if ($antiflood_edit > 0 || $antiflood_edit < 101) {	
	
// Обработка антифлуда на системные задачи

    if ($antiflood_system > 0 || $antiflood_system < 101) {		
	
// Запись в базу	
	
    $dbr = DB :: $dbh -> prepare("UPDATE `config` SET `value`=? WHERE `name`=?;");
    $dbr -> execute($registration, 'registration');
    $dbr -> execute($money, 'money');	
    $dbr -> execute($post, 'post');	
    $dbr -> execute($copyright, 'copyright');
    $dbr -> execute($author, 'author');
    $dbr -> execute($generator, 'generator');
    $dbr -> execute($keywords, 'keywords');
    $dbr -> execute($reply_to, 'reply-to');
    $dbr -> execute($login_change, 'login_change');
    $dbr -> execute($vip_change, 'vip_change');
    $dbr -> execute($rating_change, 'rating_change');
    $dbr -> execute($hide_change, 'hide_change');
    $dbr -> execute($antiflood_creation, 'antiflood_creation');
    $dbr -> execute($antiflood_edit, 'antiflood_edit');
    $dbr -> execute($antiflood_system, 'antiflood_system');

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/administration/system");	
	
// Выводим ошибки	
	
    } else { $system->show("Антифлуд на системные задачи должен иметь значение от 1 до 60 секунд"); }
    } else { $system->show("Антифлуд на редактирование должен иметь значение от 1 до 60 секунд"); }
    } else { $system->show("Антифлуд на создание должен иметь значение от 1 до 60 секунд"); }
    } else { $system->show("Цена покупки статуса невидимки должна быть от 1 до 100 монет"); }	
    } else { $system->show("Цена покупки рейтинга должна быть от 1 до 100 монет"); }
    } else { $system->show("Цена покупки статуса vip должна быть от 1 до 100 монет"); }
    } else { $system->show("Цена изменения логина должна быть от 1 до 100 монет"); }
    } else { $system->show("Слишком длинное или короткое значение META[reply_to]"); }	
    } else { $system->show("Слишком длинное или короткое значение META[keywords]"); }	
    } else { $system->show("Слишком длинное или короткое значение META[generator]"); } 	
    } else { $system->show("Слишком длинное или короткое значение META[author]"); }	
    } else { $system->show("Слишком длинное или короткое значение META[copyright]"); }
    } else { $system->show("Значение количества контента на странице должно быть от 3 до 10"); }	
    } else { $system->show("Количество монет при регистрации должно быть от 0 до 100"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов регистрации"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/profile/$user[id]");  
    }
	
// Выводим форму	

    echo '
    <div class="hide">
    Настройки системы сайта
    </div>	
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Регистрация: <br />
    <input type="radio" class="middle" name="registration" value="0" '.($config['registration'] == 0 ? 'checked="checked"':'').'> Открыта <br />
    <input type="radio" class="middle" name="registration" value="1" '.($config['registration'] == 1 ? 'checked="checked"':'').'> Закрыта <br />	
    Колово монет при регистрации: (от 0 до 100) <br />
    <input type="text" name="money" value="'.$config['money'].'" size="2" maxlength="2"/> <br />
    Колово контента на странице: (от 3 до 10) <br />
    <input type="text" name="post" value="'.$config['post'].'" size="2" maxlength="2"/> <br />
    </div>
    '.($user['access'] == 1 ? '
    <div class="block">
    META [copyright]: (500 символов) <br />
    <textarea name="copyright" class="comment" />'.$config['copyright'].'</textarea> <br />	
    META [author]: (100 символов) <br />
    <input type="text" name="author" value="'.$config['author'].'"/> <br />	
    META [generator]: (500 символов) <br />
    <textarea name="generator" class="comment" />'.$config['generator'].'</textarea> <br />	
    META [keywords]: (100 символов) <br />
    <input type="text" name="keywords" value="'.$config['keywords'].'"/> <br />	
    META [reply_to]: (100 символов) <br />
    <input type="text" name="reply_to" value="'.$config['reply-to'].'"/> <br />		
    </div>
    ' : '').'
    <div class="block">
    Стоимость смены логина: (от 1 до 100) <br />
    <input type="text" name="login_change" value="'.$config['login_change'].'" size="2" maxlength="2"/> <br />
    Стоимость покупки статуса vip: (от 1 до 100) <br />
    <input type="text" name="vip_change" value="'.$config['vip_change'].'" size="2" maxlength="2"/> <br />
    Стоимость покупки рейтинга: (от 1 до 100) <br />
    <input type="text" name="rating_change" value="'.$config['rating_change'].'" size="2" maxlength="2"/> <br />
    Стоимость покупки статуса невидимки: (от 1 до 100) <br />
    <input type="text" name="hide_change" value="'.$config['hide_change'].'" size="2" maxlength="2"/> <br />	
    </div>
    <div class="block">
    Антифлуд на создание: (в секундах от 1 до 60) <br />
    <input type="text" name="antiflood_creation" value="'.$config['antiflood_creation'].'" size="2" maxlength="2"/> <br />
    Антифлуд на редактирование: (в секундах от 1 до 60) <br />
    <input type="text" name="antiflood_edit" value="'.$config['antiflood_edit'].'" size="2" maxlength="2"/> <br />
    Антифлуд на системные задачи: (в секундах от 1 до 60) <br />
    <input type="text" name="antiflood_system" value="'.$config['antiflood_system'].'" size="2" maxlength="2"/> <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';

// Выводим ошибки
	 
    } else { $system->redirect("Отказано в доступе", "/"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>