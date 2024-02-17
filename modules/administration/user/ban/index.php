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

    $title = 'Блокировка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 5) {
	
// Ищим пользователя в базе	

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {
	
// Только если отправлен POST запрос

    if (isset($_POST['save'])) {		
	
// Обработка причины	
	
    $cause = abs(intval($_POST['cause']));	
	
// Обработка причины(Подозрение на взлом(блок со сменой пароля))

    $change_cause = ($cause == 9) ? ($user['access'] > 0 && $user['access'] < 3 && $data['activation'] == 2) ? $cause : 0 : $cause;	

// Обработка сообщения
	
    $message = $system->check($_POST['message']);

// Обработка причины	
	
    $last_time = abs(intval($_POST['last_time']));	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Проверка варианта блока
	
    if ($change_cause >= 0 && $change_cause < 11) {	

// Обработка количества символов сообщения
	
    if ($system->utf_strlen($message) < 1000) {

// Проверка времени блока

    if ($last_time == 0 || $last_time == 1 || $last_time == 3 || $last_time == 12 || $last_time == 24 || $last_time == 48 || $last_time == 120 || $last_time == 240 || $last_time == 480) {

// Делаем запрос в базу

    DB :: $dbh -> query("INSERT INTO `user_ban` (`user`, `cause`, `message`, `time`, `last_user`, `last_time`) VALUES (?, ?, ?, ?, ?, ?);", array($data['id'], $change_cause, $message, time() + ($last_time * 3600), $user['id'], time()));
	
// Генерируем sid

    $sid = md5(md5($data['login'].time()));	
	
// Генерируем пароль

    $password = ($change_cause == 9) ? substr(md5(time()), 0, 10) : $data['password'];
	
// Обновляем информацию

    DB :: $dbh -> query("UPDATE `user` SET `sid`=?, `password`=? WHERE `id`=? LIMIT 1;", array($sid, $password, $data['id']));		

// Уведомляем

    $system->redirect("Пользователь успешно заблокирован", "/modules/profile/ban/".$data['id']."");	
	
// Выводим ошибки

    } else { $system->show("Пожалуйста, выберите один из вариантов времени блока"); }
    } else { $system->show("Слишком длинное или короткое сообщение"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов блока"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /mudules/administration/user/$data[id]");  
    }
	
// Выводим форму

    echo '
    <div class="hide">
    <span style="font-weight: bold;">Блокировка пользователя</span> '.$profile->login($data['id']).'
    </div>
    <div class="block">	
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Причина: <br />
    <input type="radio" class="middle" name="cause" value="0" checked="checked" /> Грубость и оскорбления <br />
    <input type="radio" class="middle" name="cause" value="1"/> Нецензурная лексика <br />
    <input type="radio" class="middle" name="cause" value="2"/> Спам, реклама <br />
    <input type="radio" class="middle" name="cause" value="3"/> Разжигание ненависти <br />
    <input type="radio" class="middle" name="cause" value="4"/> Флуд, Оффтопик <br />
    <input type="radio" class="middle" name="cause" value="5"/> Намеки на детскую порнографию <br />
    <input type="radio" class="middle" name="cause" value="6"/> Детская порнография <br />
    <input type="radio" class="middle" name="cause" value="7"/> Педофилия <br />
    <input type="radio" class="middle" name="cause" value="8"/> Попытки входа на сайт в обход блокировки <br />
    '.($user['access'] > 0 && $user['access'] < 3 && $data['activation'] == 2 ? '
    <input type="radio" class="middle" name="cause" value="9"/> Подозрение на взлом (блок со сменой пароля) <br />
    ' : '').'	
    <input type="radio" class="middle" name="cause" value="10"/> Иное <br />
    </div>
    <div class="block">
    Время блокировки: 
    <select name="last_time">
    <option value="0" selected="selected"> 0 часов (предупреждение) </option>
    <option value="1"> 1 час </option>
    <option value="3"> 3 часа </option>
    <option value="12"> 12 часов </option>
    <option value="24"> 24 часа </option>
    <option value="48"> 48 часов </option>
    <option value="120"> 120 часов </option>
    <option value="240"> 240 часов </option>
    <option value="480"> 480 часов </option>
    </option></select>
    </div>
    <div class="block">
    Сообщение: (1000 символов)
    <textarea cols="25" rows="3" name="message" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Заблокировать" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	
	
// Выводим ошибки

    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>