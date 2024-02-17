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

    $title = 'Vip статус';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Запрещяем покупать vip статус 2 раза

    if ($user['vip'] < time()) {	
	
// Проверяем монеты	

    if ($user['money'] > $config['login_change'] - 1) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`-'".$config['login_change']."', `vip`=? WHERE `id`=? LIMIT 1;", array(time()+86400 * 1, $user['id']));

// Запись в логи

    $message = 'Покупка vip статуса';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(0, $user['id'], $message, $config['login_change'], $user['money'] - $config['login_change'], $system->ip(),  $system->ua(), time()));

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/services/vip_change");	

// Выводим ошибки	
	
    } else { $system->show("У вас не хватает монет для покупки vip статуса"); }
    } else { $system->show("Вы уже купили vip статус"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/services/");  
    }	
	
// Выводим блок

    echo '
    <div class="hide">
    Здесь Вы можете купить vip статус невидимки и получить бонусы.
    </div>
    <div class="block">
    Покупка vip статуса стоит '.$config['login_change'].' монет сутки <br />
    У вас на счету: '.$user['money'].' монет <br />
    </div>
    '.($user['money'] < $config['login_change'] - 1 ? '
    <div class="hide">
    У вас не хватает монет для покупки vip статуса
    </div>
    ' : '').'
    <div class="block">
    Стоимость этой услуги установлена достаточно высокой <br />
    Что даст Вам VIP-статус: <br />
    - Особый знак отличия <br />
    - Особая формула начисления рейтинга <br />
    - Снижение цены на другие услуги <br />
    - Дополнительные бонусы <br />
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
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
	