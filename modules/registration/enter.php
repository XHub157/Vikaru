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

/* Получаем данные из формы */

    if (isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['password'])) { 
	
/* Обработка полученных данных */

		$email = $system->check($_POST['email']);
		$first_name = $system->check($_POST['first_name']);
        $last_name = $system->check($_POST['last_name']);		
		$password = $system->check($_POST['password']);
		$sex = (empty($_POST['sex'])) ? 0 : 1;	 

/* Проверка формы */
	
				//$check_ip = DB :: $dbh -> querySingle("SELECT `id` FROM `user` WHERE lower(`ip`)=? LIMIT 1;", array(strtolower($ip)));	
				//if (empty($check_ip)) {
				//if ($config['registration'] == 0 && empty($_SESSION['user'])) {	
				if ($config['registration'] == 0) {	
				
				    $check_email = DB :: $dbh -> querySingle("SELECT `id` FROM `user` WHERE lower(`email`)=? LIMIT 1;", array(strtolower($email)));		
					if (empty($check_email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $system->utf_strlen($email) <= 50) {
				
						if ($system->utf_strlen($password) >= 3 && $system->utf_strlen($password) <= 16) {
								
						    if ($system->utf_strlen($first_name) >= 3 && $system->utf_strlen($first_name) <= 32) {									
	
						        if ($system->utf_strlen($last_name) >= 3 && $system->utf_strlen($last_name) <= 32) {
						        
    		                                         if(preg_match('#^([A-zА-я \-]*)$#ui', $first_name)) {
    		                                         
    		                                             if(preg_match('#^([A-zА-я \-]*)$#ui', $last_name)) {
    		                                             
    		                                               if (!empty($_POST['kod'])) {
	
	                                                              if ($_SESSION['code'] == $_POST['kod']) {
	
/* Генерируем SID */

    $sid = md5(md5($login.time()));
	
// Выводим блок новостей

    $show = file_get_contents(SERVER."/system/news.dat");

// Запись в базу	
	
    DB :: $dbh -> query("INSERT INTO `user` (`first_name`, `email`, `last_name`, `password`, `sex`, `sid`, `date_reg`, `date_aut`, `aut`, `money`, `ip`, `ua`, `show`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", array($first_name, $email, $last_name, $password, $sex, $sid, time(), time(), time(), $config['money'], $system->ip(), $system->ua(), $show));
	
// Выводим id пользователя
 
    $user_id = DB :: $dbh -> lastInsertId();	
	
// Запись в историю входов	
	
    DB :: $dbh -> query("INSERT INTO `authorization` (`user`, `ip`, `ua`, `time`, `one`) VALUES (?, ?, ?, ?, ?);", array($user_id, $system->ip(), $system->ua(), time(), 1));
	
// Отправляем подарок
 
    DB :: $dbh -> query("INSERT INTO `gifts_user` (`gift`, `message`, `access`, `user`, `profile`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(1, 'Добро пожаловать '.DOMAIN.'', 0, 1, $user_id, time(), $system->ip(), $system->ua()));
    	
	
// Запись в логи

    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(1, $user_id, 'Подарок от администрации сайта', $config['money'], $config['money'], $system->ip(),  $system->ua(), time()));  

// Запись в журнал

    DB :: $dbh -> query("INSERT INTO `journal` (`user`, `message`, `url`, `profile`, `read`, `time`, `section`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(0, "У Вас новый подарок", "/modules/gifts/user/".$user_id."", $user_id, 1, time(), 0)); 

// Отправляем сообщения от системы

    DB :: $dbh -> query("INSERT INTO `mail_contact` (`user`, `profile`, `time`) VALUES (?, ?, ?);", array($user_id, 1, time()));		
    $mail_message_1 = 'Добро пожаловать в мир яркого общения. Здесь ты сможешь найти новых друзей и с интересом провести время. Мы рады приветствовать тебя в нашем добром клубе
    [b]'.DOMAIN.'[/b] - это мир, населенный обитателями и новичкам тут всегда рады!
    Если у тебя есть желание создать свой дневник или фото альбом, завести новых друзей или пригласить к себе старых, обмениваться с ними файлами в [url=http://'.DOMAIN.'/modules/shared_zone/]Зоне обмена[/url].
    Поделиться со всем миром своими мыслями, фотографиями, музыкой, видео - то всё это здесь!';
    $mail_message_2 = 'Если хочешь найти новых друзей не жди, что тебе сразу начнут писать, нужно самому проявить активность. Зайди в [url=http://'.DOMAIN.'/modules/dating/]Знакомства[/url] и заполни анкету.
    Пройдись по дневникам, фотоальбомам оставь свои комментарии, тебя заметят и наверняка захотят познакомиться
    В Зону обмена обитатели выкладывают свои файлы. Тут всегда можно загрузить свой телефон под завязку.
    Остальную информации по сайту можно прочитать в разделе [url=http://'.DOMAIN.'/modules/reference/]Справка[/url]';
    $mail_message_3 = 'В ленте сайта - можно узнать что происходило пока ты отсутствовал.
    В [b]беседке[/b] можно к примеру пожелать всем хорошего дня или пригласить посмотреть свою страничку.
    В разделе форум легко найти новых собеседников.
    В разделе анкета на моей странице можно заполнить свои данные для лучшего поиска вас в знакомствах.
    Удачного общения на '.DOMAIN.', с уважением Артем Соколовский.';	
    DB :: $dbh -> query("INSERT INTO `mail_message` (`user`, `message`, `profile`, `read`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(1, $mail_message_1, $user_id, 1, time(), $system->ip(), $system->ua())); 
    DB :: $dbh -> query("INSERT INTO `mail_message` (`user`, `message`, `profile`, `read`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(1, $mail_message_2, $user_id, 1, time(), $system->ip(), $system->ua()));
    DB :: $dbh -> query("INSERT INTO `mail_message` (`user`, `message`, `profile`, `read`, `time`, `ip`, `ua`) VALUES (?, ?, ?, ?, ?, ?, ?);", array(1, $mail_message_3, $user_id, 1, time(), $system->ip(), $system->ua()));	
    DB :: $dbh -> query("UPDATE `mail_contact` SET `time`=?, `message`=`message`+3 WHERE `user`=? AND `profile`=?", array(time(), $user_id, 1));

// Запись данных в COOKIE

    setcookie("sid", $sid, time() + 3600 * 24 * 1, "/", "".DOMAIN."");

// Запись данных в сессии

    $_SESSION['user'] = $user_id;  	
	
// Если всё отлично перенаправляем на стартовую

    $system->redirect("Добро пожаловать", "/modules/startpage/");
	
// Выводим ошибки	
                      } else { $system->redirect("Проверочный код неправильный!", "/modules/registration/"); } 
                        } else { $system->redirect("Не введён проверочный код!", "/modules/registration/"); }    
                      } else { $system->redirect("Ошибка при вводе фамилии", "/modules/registration/"); }
                                } else { $system->redirect("Ошибка при вводе имени", "/modules/registration/"); }
               } else { $system->redirect("Ошибка при вводе фамилии", "/modules/registration/"); }
				            } else { $system->redirect("Ошибка при вводе имени", "/modules/registration/"); }
				        } else { $system->redirect("Ошибка при вводе пароля", "/modules/registration/"); }
                    } else { $system->redirect("Ошибка при вводе email", "/modules/registration/"); }					
				} else { $system->redirect("Регистрация временно приостановлена или данный ip уже есть в базе", "/modules/registration/"); }
			} else { $system->redirect("Заполните все поля", "/modules/registration/"); }	

?>