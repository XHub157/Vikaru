<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 
	
// Подключаем mail ядро

    $email = new email();	
	
// Выводим шапку

    $title = 'Восстановление пароля';

// Инклудим шапку

include_once (ROOT.'template/head.php');	 	

// Только если отправлен POST запрос	
	
    if (isset($_POST['save'])) {		

// Обработка email
	
    $user_email = $system->check($_POST['email']);	

// Проверяем и выводим информацию

    $act = DB :: $dbh -> queryFetch("SELECT `id`, `login`, `activation`, `email`, `recovery` FROM `user` WHERE `email`=? LIMIT 1;", array(strtolower($user_email)));	
	
// Восстановление только раз в 30 секунд
	
    if ($act['recovery'] < time()-30) {
	
// Только если пользователь активирован

    if ($act['activation'] == 2) {	

// Генерируем sid

    $sid = md5(md5($login.time()));
	
// Генерируем пароль

    $password = substr(md5(time()), 0, 10);	
	
// Обновляем информацию

    DB :: $dbh -> query("UPDATE `user` SET `sid`=?, `password`=?, `recovery`=? WHERE `id`=? LIMIT 1;", array($sid, $password, time(), $act['id']));

// Уведомление на email

    $email->send($act['email'], 'Система безопасности '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$act['login'].'</span>. <br />
    Вы подали запрос на восстановление пароля. <br />
    Ваш пароль: <span style="font-weight: bold;">'.$password.'</span>.
    ');   	

// Уведомляем

    $system->redirect("Пароль успешно отправлен на ".$user_email."", "/modules/authorization/");	
	
// Выводим ошибки
    
    } else { $system->show("Выбранный вами E-mail не существует"); } 
    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/authorization/");  
    }

// Выводим блок

    echo '
    <div class="block">
    <form method="post">
    E-mail на который активирован аккаунт: <br />
    <input type="text" name="email" value="" style="width: 70%;" />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Восстановить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';		
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	