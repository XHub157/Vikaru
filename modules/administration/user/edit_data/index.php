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

    $title = 'Редактирование';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `edit_time`>? AND `id`=?;", array(time()-5, $data['id']));

// Только если не было изменений в течении 5 секунд
	
    if (empty($antiflood)) {			

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Обработка логина
	
    $login = $system->check($_POST['login']);	
	
// Обработка пароля

    $password = ($user['access'] == 1) ? $system->check($_POST['password']) : $data['password'];	

// Обработка статуса

    $access = ($user['access'] == 1) ? abs(intval($_POST['access'])) : $data['access'];		
	
// Обработка монет
	
    $money = abs(intval($_POST['money']));


// Проверка $password

    if ($system->utf_strlen($password) > 5 && $system->utf_strlen($password) < 33 && preg_match('|^[a-z0-9\-]+$|i', $password)) {	

// Проверка доступа
	
    if ($access == 0 || $access == 1 || $access == 2 || $access == 3 || $access == 4) {	

// Проверка монет
	
    if ($money >= 0 && $money < 10000) {		

// Выполняем запрос в базу
	
    DB :: $dbh -> query("UPDATE `user` SET `password`=?, `access`=?, `money`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($password, $access, $money, $user['id'], time(), $data['id']));	
	
// Уведомляем

    $system->redirect("Пользователь успешно отредактирован", "/modules/administration/user/".$data['id']."");	
	
// Выводим ошибки
  
    } else { $system->show("Недопустимое количество монет"); } 
    } else { $system->show("Пожалуйста, выберите один из вариантов статуса пользователя"); }
    } else { $system->show("Недопустимые символы в пароле"); } 		
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/administration/user/$data[id]");  
    }

// Выводим форму

    echo '
    <div class="hide">
    <a href="/modules/administration/user/edit_data/'.$data['id'].'" >Данные</a>
    | <a href="/modules/administration/user/edit/main/'.$data['id'].'" class="link">Основное</a>
    | <a href="/modules/administration/user/edit/contacts/'.$data['id'].'" class="link">Контакты</a>
    | <a href="/modules/administration/user/edit/interests/'.$data['id'].'" class="link">Интересы</a>
    | <a href="/modules/administration/user/edit/type/'.$data['id'].'" class="link">Типаж</a>
    | <a href="/modules/administration/user/edit/additionally/'.$data['id'].'" class="link">Дополнительно</a>
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    '.($user['access'] == 1 ? '
    Пароль: (От 6 до 32 символов, русские буквы нельзя) <br />
    <input type="text" name="password" value="'.$data['password'].'"/> <br />
    ' : '').'	
    </div>	
    '.($user['access'] == 1 ? '
    <div class="block">
    Статуст: <br />
    <input type="radio" class="middle" name="access" value="0" '.($data['access'] == 0 ? 'checked="checked"':'').' /> Пользователь <br />   
    <input type="radio" class="middle" name="access" value="1" '.($data['access'] == 1 ? 'checked="checked"':'').' /> Создатель <br />	
    <input type="radio" class="middle" name="access" value="2" '.($data['access'] == 2 ? 'checked="checked"':'').' /> Администратор <br />
    <input type="radio" class="middle" name="access" value="3" '.($data['access'] == 3 ? 'checked="checked"':'').' /> Модератор форума <br />
    <input type="radio" class="middle" name="access" value="4" '.($data['access'] == 4 ? 'checked="checked"':'').' /> Модератор Файлов <br />
    </div>
    ' : '').'
    <div class="block">
    Монеты: (max 10000) <br />
    <input type="text" name="money" value="'.$data['money'].'" size="4" maxlength="4"/> <br />
    </div>
    '.($data['edit'] > 0 ? '
    <div class="hide">
    <img class="middle" src="/icons/edit.png">
    Последний раз редактировалось: '.$system->system_time($data['edit_time']).'
    '.$profile->login($data['edit']).'
    </div>
    ' : '').'
    <div class="block">	
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами пользователь не существует"); } 
    } else { $system->redirect("Отказано в доступе", "/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	