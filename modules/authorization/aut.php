<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Только для гостей

    $profile->access(false);	

/* Только если отправлен $_POST запрос */

    if (isset($_POST['aut_password']) && isset($_POST['aut_email'])) { 

/* Обработка полученных данных */
	
		$password = $system->check($_POST['aut_password']);
		$email = $system->check($_POST['aut_email']);


/* Проверяем есть ли такой пользователь */
	
		$aut = DB :: $dbh -> queryFetch("SELECT * FROM `user` WHERE `email`=? LIMIT 1;", array($email));	

/* Только если данный пользователь существует */
	
		if (!empty($aut)) {

/* Проверяем данные */

			if ($email == $aut['email'] && $password == $aut['password']) {
			
			// Проверяем частоту авторизаций
	
    $authorization = DB :: $dbh -> querySingle("SELECT count(*) FROM `authorization` WHERE `user`=? AND `time`>?;", array($aut['id'], time()-300));	

// Только если было меньше 3 входов за 300 секунд
	
    if ($authorization < 3) {

// Проверяем наличие блокировки

    $ban = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_ban` WHERE `user`=? AND `time`>?;", array($aut['id'], time()));

// Только если пользователь не забанен

    if (empty($ban)) {	
    

/* Генерируем sid */
                 
				$sid = md5(md5($aut['email'].time()));
				
// Обновляем информацию

    DB :: $dbh -> query("UPDATE `user` SET `ip`=?, `ua`=?, `sid`=? WHERE `id`=? LIMIT 1;", array($system->ip(), $system->ua(), $sid, $aut['id']));
	
// Запись в историю входов	
	
    DB :: $dbh -> query("INSERT INTO `authorization` (`user`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?);", array($aut['id'], $system->ip(), $system->ua(), time()));	

// Запись данных в COOKIE

    setcookie("sid", $sid, time() + 3600 * 24 * 1, "/", "".DOMAIN."");

// Если всё отлично перенаправляем на стартовую

    $system->redirect("Добро пожаловать", "/modules/startpage/");

// Выводим ошибки	
	
    } else { $system->redirect("Вы были заблокированы на ".DOMAIN."", "/modules/profile/ban/".$aut['id'].""); }
        } else { $system->redirect("Вы очень часто входите на ".DOMAIN.". Подождите 5 минут и попробуйте снова", "/modules/authorization/"); }
    } else { $system->redirect("Неправильный пароль", "/modules/authorization/"); }
    } else { $system->redirect("Данный пользователь не существует", "/modules/authorization/"); }
    } else { $system->redirect("Заполните все поля", "/modules/authorization/"); }

?>