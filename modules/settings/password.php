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

// Подключаем mail ядро

    $email = new email();
	
// Выводим шапку

    $title = 'Пароль';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка нового первого пароля
	
    $password_one = $system->check($_POST['password_one']);
	
// Обработка нового второго пароля 	
	
    $password_two = $system->check($_POST['password_two']);
	
// Обработка текущего пароля
	
    $password = $system->check($_POST['password']);
	
// Проверка нового пароля

    if ($system->utf_strlen($password_one) >= 6 && $system->utf_strlen($password_one) <= 32 && preg_match('|^[a-z0-9\-]+$|i', $password_one)) {		
	
// Проверка старого пароля
	
    if ($password == $user['password']) {	
	
// Только если пароли совпадают

    if ($password_one == $password_two) {

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `password`=?  WHERE `id`=? LIMIT 1;", array($password_one, $user['id']));	
	
// Только если аккаунт подтверждён

    if ($user['activation'] == 2) {	
	
// Отправляем письмо на почту

    $email->send($user['email'], 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    Пароль на '.DOMAIN.' был изменён '.date("d.m.Y").' в '.$system->system_time(time()).' (UTC '.$user['timezone'].') <br />
    <span style="font-weight: bold;">'.$password.'</span> на <span style="font-weight: bold;">'.$password_one.'</span> <br />
    ');

    }	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/password");	
	
// Выводим ошибки	
	
    } else { $system->show("Новый пароль не совпадает с повтором нового пороля"); }	
    } else { $system->show("Не верно введён текущий пароль"); }
    } else { $system->show("Недопустимые символы в пароле"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '
    <div class="title">
    Изменение пароля
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Какие пароли легко подобрать: <br />
    - Любые наборы цифр (26739, 2478904, ...). <br />
    - Даты рождения (010680, 120479...). <br />
    - Номера телефонов. <br />
    - Имена людей и клички домашних животных. <br />
    <hr>
    Если вы делаете себе такой пароль, считайте, что ваш профиль уже не ваш. <br />
    Вот примеры хороших паролей: sUti42Dd, 7u8YY59, L0161Aoo <br />
    </div>
    <div class="block">
    Введите ваш текущий пароль: <br />
    <input type="password" name="password" value=""/> <br />
    Введите новый пароль: <br />
    <input type="password" name="password_one" value=""/> <br />	
    Повторите новый пароль: <br />
    <input type="password" name="password_two" value=""/> <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		