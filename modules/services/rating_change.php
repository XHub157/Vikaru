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

    $title = 'Рейтинг';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Формула снижения цены

    $price = ($user['vip'] > time()) ? abs(intval($config['rating_change'] / 2)) : abs(intval($config['rating_change']));

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка названия
	
    $rating = abs(intval($_POST['rating']));

// Рейтинг 

    if ($rating > 0 && $rating < 100) {
	
// Формируем данные

    $ratings = $rating * 100;
    $money = $rating * $price;	
	
// Проверяем монеты	

    if ($user['money'] > $money) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `rating`=`rating`+'".$ratings."', `money`=`money`-'".$money."' WHERE `id`=? LIMIT 1;", array($user['id']));

// Запись в логи

    $message = 'Покупка рейтинга по курсу '.$ratings / 100 .'.00 за '.$money.' монет';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(0, $user['id'], $message, $money, $user['money'] - $money, $system->ip(),  $system->ua(), time()));

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/services/rating_change");	

// Выводим ошибки	
	
    } else { $system->show("У вас не хватает монет для изменения рейтинга"); }
    } else { $system->show("Недопустимое количество рейтинга"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/services/");  
    }	
	
// Выводим блок

    echo '
    <div class="hide">
    Изменение рейтинга происходит по курсу '.$price.' монет = 1.00 рейтинга <br />
    </div>
    <div class="block">
    У вас на счету: '.$user['money'].' монет <br />
    </div>
    '.($user['money'] < $price - 1 ? '
    <div class="hide">
    У вас не хватает монет для изменения рейтинга
    </div>
    ' : '').'
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Рейтинг: (max 100)<br />
    <input type="text" name="rating" value="" size="4" maxlength="4"/> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Купить" />
    <input type="submit" name="back" value="Отмена" />
    </form>	
    </div>
    ';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>