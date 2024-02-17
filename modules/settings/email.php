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

    $title = 'Настройка E-mail';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Показуем только когда пользователь ещё не активирован

    if ($user['activation'] == 0) {	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $mail = $system->check($_POST['email']);	
	
// Обработка E-mail	

    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
	
// Проверяем E-mail	
	
    $activation = DB :: $dbh -> querySingle("SELECT `id` FROM `user` WHERE lower(`email`)=? AND `activation`=? LIMIT 1;", array(strtolower($mail), 2));	
	
// Только если email свободен

    if (empty($activation)) {
	
// Генерируем код

    $key = rand(10000000, 99999999);	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `email`=?, `activation`=?, `key`=?  WHERE `id`=? LIMIT 1;", array($mail, 1, $key, $user['id']));	
	
// Отправляем письмо на почту

    $email->send($mail, 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    Для активации аккаунта вы должны ввести код. <br />
    Ваш код <span style="font-weight: bold;">'.$key.'</span> <br />
    Если Вы не подавали заявки на активацию, проигнорируйте это письмо - возможно, кто-то совершил ошибку.');

// Уведомляем

    $system->redirect("Код активации отправлен на ".$mail."", "/modules/settings/email");	
	
// Выводим ошибки	
	
    } else { $system->show("Данный E-mail уже активирован"); }	
    } else { $system->show("Не верный формат E-mail (email@mail.ru)"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму	

    echo '

    <div class="title">
    Активация страницы на E-mail
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Зачем нужна активация E-mail? <br />
    <span style="color:#00aa00">
    - Активация введена для того, чтобы избежать указания чужих адресов. <br />
    - Восстановить утерянный пароль. <br />
    - Уведомления о происходящем на сайте <br />
    </span>
    E-mail: (50 символов) <br />
    <input type="text" name="email" value=""/> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
    } else if ($user['activation'] == 1) {
	
// Только если отправлен POST запрос

    if (isset($_POST['delete'])) {

// Проверяем sid

    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Запись в базу

    DB :: $dbh -> query("UPDATE `user` SET `email`=?, `activation`=?, `key`=?  WHERE `id`=? LIMIT 1;", array(NULL, 0, 0, $user['id']));	

// Уведомляем

    $system->redirect("Вы успешно деактивировали аккаунт", "/modules/settings/email");

// Выводим ошибки

    } else { $system->show("Замечена подозрительная активность, повторите действие"); }

    }	
	
// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Проверяем E-mail	
	
    $activation = DB :: $dbh -> querySingle("SELECT `id` FROM `user` WHERE lower(`email`)=? AND `activation`=? LIMIT 1;", array(strtolower($user['email']), 2));	
	
// Только если email свободен

    if (empty($activation)) {	
	
// Обработка переменных	
	
    $key = $system->check($_POST['key']);	
	
// Проверяем код

    if ($key == $user['key']) {

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `activation`=? WHERE `id`=? LIMIT 1;", array(2, $user['id'])); 

// Отправляем письмо на почту

    $email->send($user['email'], 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    Вы успешно активировали аккаунт на '.DOMAIN.' <br />
    Теперь вам доступны новые возможности <br />
    Для начала прочитайте <a href="http://'.DOMAIN.'/modules/reference/">Справку</a>. <br />
    Если у вас возникнут вопросы по сайту обращайтесь в техническую поддержку, с уважением Администрация. <br />'); 	
	
// Уведомляем

    $system->redirect("Вы успешно активировали аккаунт", "/modules/settings/email");	
	
// Выводим ошибки	
	
    } else { $system->show("Код активации не верный"); }	
    } else { $system->show("Данный E-mail уже активирован"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }

// Выводим форму	

    echo '
    <div class="act">
    <a class="act_active" href="/modules/settings/email">Подтверждение</a>
    <a class="act_noactive" href="/id'.$user['id'].'">'.$user['login'].'</a>
    </div>
    <div class="hide">
    Подтверждение активации E-mail
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <span style="color:#00aa00">
    Код активации успешно отправлен на '.$user['email'].' <br />
    </span>
    Код активации: <br />
    <input type="text" name="key" value=""/> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    <input type="submit" name="delete" value="Изменить" />
    </form>
    </div>';    	

    } else if ($user['activation'] == 2) {
	
// Только если отправлен POST запрос

    if (isset($_POST['delete'])) {

// Проверяем sid

    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Генерируем код

    $key = rand(10000000, 99999999);	

// Запись в базу

    DB :: $dbh -> query("UPDATE `user` SET `activation`=?, `key`=?  WHERE `id`=? LIMIT 1;", array(3, $key, $user['id']));	
	
// Отправляем письмо на почту

    $email->send($user['email'], 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    Для изменения E-mail вы должны ввести код. <br />
    Ваш код <span style="font-weight: bold;">'.$key.'</span> <br />
    Если Вы не подавали заявки на изменение E-mail, проигнорируйте это письмо - возможно, кто-то совершил ошибку.');	

// Уведомляем

    $system->redirect("Код активации отправлен на ".strstr($user['email'], "@", true)."@***", "/modules/settings/email");

// Выводим ошибки

    } else { $system->show("Замечена подозрительная активность, повторите действие"); }
    }	
	
// Содержимое страницы

    if (isset($_POST['save'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка переменных	
	
    $notice = abs(intval($_POST['notice']));	
	
// Проверка уведомлений
	
    if ($notice == 0 || $notice == 1) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `notice`=? WHERE `id`=? LIMIT 1;", array($notice, $user['id']));

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/email");

// Выводим ошибки
	
    } else { $system->show("Пожалуйста, выберите один из вариантов уведомления"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }	
	
// Выводим форму	

    echo '
    <div class="act">
    <a class="act_active" href="/modules/settings/email">Настройка E-mail</a>
    <a class="act_noactive" href="/id'.$user['id'].'">'.$user['login'].'</a>
    </div>
    <div class="hide">
    Настройка E-mail
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Ваш E-mail: <span style="color:#00aa00; font-weight: bold;">
    '.strstr($user['email'], "@", true).'@***
    </span> <br />
    Посылать периодические оповещения: <br />
    <input type="radio" class="middle" name="notice" value="1" '.($user['notice'] == 1 ? 'checked="checked"':'').'/> Да <br />	
    <input type="radio" class="middle" name="notice" value="0" '.($user['notice'] == 0 ? 'checked="checked"':'').' /> Нет <br /> 
    <hr>
    Внимание! данная функция будет оповещать вас о происходящем на сайте
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    <input type="submit" name="delete" value="Изменить" />
    </form>
    </div>';	
	
    } else if ($user['activation'] == 3) {
	
// Содержимое страницы

    if (isset($_POST['save'])) {

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Обработка email
	
    $mail = $system->check($_POST['email']);	

// Обработка кода
	
    $key = intval($_POST['key']);	

// Проверяем код

    if ($key == $user['key']) {
	
// Обработка E-mail	

    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {	
	
// Проверяем E-mail	
	
    $activation = DB :: $dbh -> querySingle("SELECT `id` FROM `user` WHERE lower(`email`)=? AND `activation`=? LIMIT 1;", array(strtolower($mail), 2));	
	
// Только если email свободен

    if (empty($activation)) {	

// Генерируем код

    $key = rand(10000000, 99999999);	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `activation`=?, `email`=?, `key`=? WHERE `id`=? LIMIT 1;", array(1, $mail, $key, $user['id'])); 
	
// Отправляем письмо на почту

    $email->send($mail, 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$user['login'].'</span> <br />
    Для изменения активации нового E-mail вы должны ввести код. <br />
    Ваш код <span style="font-weight: bold;">'.$key.'</span> <br />
    Если Вы не подавали заявки на активацию E-mail, проигнорируйте это письмо - возможно, кто-то совершил ошибку.');
	
// Уведомляем

    $system->redirect("Код активации отправлен на ".$mail."", "/modules/settings/email");	

// Выводим ошибки	
	
    } else { $system->show("Данный E-mail уже активирован"); }
    } else { $system->show("Не верный формат E-mail (email@mail.ru)"); }	
    } else { $system->show("Код активации не верный"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }	
	  	
// Выводим форму	

    echo '
    <div class="act">
    <a class="act_active" href="/modules/settings/email">Изменение E-mail</a>
    <a class="act_noactive" href="/id'.$user['id'].'">'.$user['login'].'</a>
    </div>
    <div class="hide">
    Изменение E-mail
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Старый E-mail: <br />
    <input type="text" name="name" value="'.strstr($user['email'], "@", true).'@***" disabled="disabled"/> <br />
    Новый E-mail: (50 символов) <br />
    <input type="text" name="email" value=""/> <br />
    Код активации: <br />
    <input type="text" name="key" value=""/> <br />    	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
    }
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		