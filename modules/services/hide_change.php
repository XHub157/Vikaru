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

    $title = 'Невидимка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Обработка стоимости

    $price = ($user['vip'] > time()) ? abs(intval($config['hide_change'] / 2)) : abs(intval($config['hide_change']));	

// Только если отправлен POST запрос	
	
    if (isset($_POST['delete'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Проверяем наличие статуса невидимки

    if ($user['hide'] > time()) {		

// Продаём статус невидимки	
	
    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`+'".abs(intval( $price / 2 ))."', `hide`=? WHERE `id`=? LIMIT 1;", array(0, $user['id']));

// Запись в логи

    $message = 'Продажа статуса невидимки';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(1, $user['id'], $message, abs(intval( $price / 2 )), $user['money'] + abs(intval( $price / 2 )), $system->ip(),  $system->ua(), time()));

// Уведомляем

    $system->redirect("Статус невидимки успешно продан за ".abs(intval( $price / 2 ))." монет", "/modules/services/");	
	
// Выводим ошибки	
	
    } else { $system->show("Вы не покупали статус невидимки"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    }	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Запрещяем покупать невидимку 2 раза

    if ($user['hide'] < time()) {	
	
// Проверяем монеты	

    if ($user['money'] > $price - 1) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`-'".$price."', `hide`=? WHERE `id`=? LIMIT 1;", array(time()+86400 * 1, $user['id']));

// Запись в логи

    $message = 'Покупка статуса невидимки';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(0, $user['id'], $message, $price, $user['money'] - $price, $system->ip(),  $system->ua(), time()));

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/services/");	

// Выводим ошибки	
	
    } else { $system->show("У вас не хватает монет для покупки статуса невидимки"); }
    } else { $system->show("Вы уже купили статус невидимки"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/services/");  
    }	
	
// Выводим блок

    echo '

    <div class="hide">
    Здесь Вы можете купить статус невидимки и стать невидимым на сайте.
    </div>
    <div class="block">
    Покупка статуса невидимки стоит '.$price.' монет сутки <br />
    У вас на счету: '.$user['money'].' монет <br />
    </div>
    '.($user['money'] < $price - 1 ? '
    <div class="hide">
    У вас не хватает монет для покупки статуса невидимки
    </div>
    ' : '').'
    <div class="block">
    Что даст Вам статус невидимки: <br />
    - Невидимость на сайте <br />
    - Невидимость на страницах пользователей <br />
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Купить" />
    '.($user['hide'] > time() ? '
    <input type="submit" name="delete" value="Продать" />
    ' : '').'	
    <input type="submit" name="back" value="Отмена" />
    </form>	
    </div>
    ';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>