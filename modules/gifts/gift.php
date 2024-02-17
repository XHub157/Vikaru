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

// Подключаем ядро отправки email

    $email = new email();

// Выводим шапку

    $title = 'Подарок';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим подарок в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `gifts` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данный подарок существует
	
    if (!empty($act)) {
	
// Обработка полученого id получателя

    $id_user = (empty($_GET['add'])) ? 0 : abs(intval($_GET['add']));
	
// Ищем пользователя в базе

    $data = DB :: $dbh -> queryFetch("SELECT `id`, `login`, `activation`, `email`, `date_aut`, `notice` FROM `user` WHERE `id`=? LIMIT 1;", array($id_user)); 

// Только если данный пользователь существует
	
    if (!empty($data)) {  

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `gifts_user` WHERE `time`>? AND `user`=?;", array(time()-$config['antiflood_creation'], $user['id']));

// Только если был отправлен подарок в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) { 	
	
// Запрещаем добавлять самого себя

    if ($data['id'] != $user['id']) {	
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Проверяем монеты пользователя

    if ($user['money'] >= $act['money']) {	

// Обработка сообщения
	
    $message = $system->check($_POST['message']);

// Обработка прав доступа

    $access = abs(intval($_POST['access']));	 

// Обработка количества символов сообщения
	
    if ($system->utf_strlen($message) >= 3 && $system->utf_strlen($message) < 10000) {	
	
// Проверка доступа
	
    if ($access == 0 || $access == 1 || $access == 2) {		

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `gifts_user` (`gift`, `message`, `access`, `user`, `profile`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array($act['id'], $message, $access, $user['id'], $data['id'], time(), $system->ip(), $system->ua()));

// Выводим id подарка

    $id_gift = DB :: $dbh -> lastInsertId();  

// Обновляем данные

    DB :: $dbh -> query("UPDATE `gifts` SET `send`=`send`+1 WHERE `id`=?", array($act['id']));  

// Генерируем случайный подарок отправителю

    $rand = rand(1,10);
    $rand_money = rand(1,5);

    if ($rand == 5) {

// Добавляем бонус

    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`+'".$rand_money."' WHERE `id`=? LIMIT 1;", array($user['id']));

// Запись в логи

    $message_logs = 'Подарок от администрации сайта';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(1, $user['id'], $message_logs, $rand_money, $user['money'] + $rand_money - $act['money'], $system->ip(),  $system->ua(), time()));    

// Уведомляем 

    $system->journal("0", "Подарок от администрации сайта ".$rand_money." монет", "/modules/services/logs", "".$user['id']."", "0");

    }	
	
// Снимаем монеты

    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`-'".$act['money']."' WHERE `id`=? LIMIT 1;", array($user['id']));	

// Запись в логи

    $message_logs_user = 'Подарок для <span style="font-weight: bold;">'.$data['login'].'</span>';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(0, $user['id'], $message_logs_user, $act['money'], $user['money'] - $act['money'], $system->ip(),  $system->ua(), time()));
	
// Уведомление на email

    if ($data['activation'] == 2 && $data['notice'] == 1 && $data['date_aut'] < time()-300) {	
	
    $email->send($data['email'], 'Новое уведомление на '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$data['login'].'</span>, у Вас новый подарок <br />
    <a href="http://'.DOMAIN.'/modules/gifts/user/'.$data['id'].'">
    <img src="http://'.SERVER_DOMAIN.'/gifts/128/'.$act['id'].'.png"/>
    <br />
    '.substr($message, 0, 100).'</a> <br />');

    }		
	
// Уведомление в журнал

    DB :: $dbh -> query("INSERT INTO `journal` (`user`, `message`, `url`, `profile`, `read`, `time`, `section`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(0, "У Вас новый подарок", "/modules/gifts/user/gift/".$id_gift."", $data['id'], 1, time(), 0));
	
// Уведомляем

    $system->redirect("Подарок успешно отправлен", "/modules/gifts/user/".$data['id']."");	
	
// Выводим ошибки

    } else { $system->show("Пожалуйста, выберите один из вариантов типа подарка"); }
    } else { $system->show("Слишком длинное или короткое сообщение"); }
    } else { $system->show("У вас не хватает монет"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/gifts/dir/$act[dir]/$add");  
    }
	
// Выводим форму

    echo '
    <div class="block">
    Подарок для '.$profile->user($data['id']).'
    </div>
    <div class="block">
    <img src="http://'.SERVER_DOMAIN.'/gifts/128/'.$act['id'].'.png"/>	<br />
    Стоимость: '.$act['money'].' монет <br />
    </div>
    '.($user['money'] < $act['money'] ? '
    <div class="hide">
    К сожалению, у вас не хватает монет.
    </div>
    ' : '').'	
    <div class="block">
    <form method="post">
    Тип подарка: <br />
    <input type="radio" name="access" value="0" checked/> Публичный <br />
    <span style="font-size: 11px;"> Все будут видеть Ваш подарок, сообщение и Логин. </span> <br />
    <input type="radio" name="access" value="1" /> Личный <br />
    <span style="font-size: 11px;"> Все будут видеть Ваш подарок, но только получатель сможет видеть ваш Логин и сообщение. </span> <br />
    <input type="radio" name="access" value="2" /> Анонимный <br />
    <span style="font-size: 11px;"> Все будут видеть Ваш подарок. Только получатель увидит ваше сообщение. Никто не увидит ваш Логин. </span> <br />
    </div>
    <div class="block">
    Сообщение: (10000 символов) <br />
    <textarea cols="25" rows="3" name="message" class="textarea" /></textarea> <br />
    </div>
    <div class="block">	
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="save" value="Отправить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); }
    } else { $system->show("Не так быстро, подождите немного"); }	
    } else { $system->show("Выбранный вами пользователь не существует"); } 	
    } else { $system->show("Выбранный вами подарок не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	