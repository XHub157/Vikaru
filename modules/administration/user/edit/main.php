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

    $title = 'Основное';

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
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $data['id']));

// Только если не было изменений в течении 5 секунд
	
    if (empty($antiflood)) {

// Меню

    echo '
    <div class="hide">
    <a href="/modules/administration/user/edit_data/'.$data['id'].'" class="link">Данные</a>
    | <a href="/modules/administration/user/edit/main/'.$data['id'].'">Основное</a>
    | <a href="/modules/administration/user/edit/contacts/'.$data['id'].'" class="link">Контакты</a>
    | <a href="/modules/administration/user/edit/interests/'.$data['id'].'" class="link">Интересы</a>
    | <a href="/modules/administration/user/edit/type/'.$data['id'].'" class="link">Типаж</a>
    | <a href="/modules/administration/user/edit/additionally/'.$data['id'].'" class="link">Дополнительно</a>
    </div>';

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $hello = $system->check($_POST['hello']);
    $first_name = $system->check($_POST['first_name']);
    $last_name = $system->check($_POST['last_name']); 
    $sex = abs(intval($_POST['sex']));
    $day = $system->check($_POST['day']);
    $month = $system->check($_POST['month']);
    $year = $system->check($_POST['year']);
    $marital_status = abs(intval($_POST['marital_status']));	
	
// Обработка количества символов привецтвия
	
    if ($system->utf_strlen($hello) <= 200) {

// Обработка количества символов имени
	
    if ($system->utf_strlen($first_name) <= 20) {		

// Обработка количества символов фамилии
	
    if ($system->utf_strlen($last_name) <= 20) {

// Обработка пола
	
    if ($sex == 0 || $sex == 1) {	

// Обработка символов даты рождения

    if ($system->utf_strlen($day) > 1 && $system->utf_strlen($month) > 1 && $day > 0 && $day < 32 && $month > 1 && $month < 13 && $year > 1949 && $year < 2011 || $day.$month.$year == NULL) {

// Обработка пола
	
    if ($marital_status == 0 || $marital_status == 1 || $marital_status == 2 || $marital_status == 3 || $marital_status == 4 || $marital_status == 5 || $marital_status == 6 || $marital_status == 7) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `hello`=?, `first_name`=?, `last_name`=?, `sex`=?, `day`=?, `month`=?, `year`=?, `marital_status`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($hello, $first_name, $last_name, $sex, $day, $month, $year, $marital_status, $user['id'], time(), $data['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/administration/user/".$data['id']."");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }	
    } else { $system->show("Не верно заполнена дата рождения, формат (дд.мм.гггг)"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); } 	
    } else { $system->show("Слишком длинное или короткое имя"); }	
    } else { $system->show("Слишком длинная или короткая фамилия"); }	
    } else { $system->show("Слишком длинное или короткое приветствие"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/administration/user/$data[id]");  
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Приветствие: (200 символов) <br />
    <input type="text" name="hello" style="width: 70%;" value="'.$data['hello'].'"/> <br />
    </div>
    <div class="block">
    Имя: (20 символов) <br />
    <input type="text" name="first_name" value="'.$data['first_name'].'"/> <br />
    </div>
    <div class="block">
    Фамилия: (20 символов) <br />
    <input type="text" name="last_name" value="'.$data['last_name'].'"/> <br />
    </div>
    <div class="block">
    Пол: </br>
    <input type="radio" class="middle" name="sex" value="0" '.($data['sex'] == 0 ? 'checked="checked"':'').'> Мужской <br />
    <input type="radio" class="middle" name="sex" value="1" '.($data['sex'] == 1 ? 'checked="checked"':'').'> Женский <br />
    </div>
    <div class="block">
    Дата рождения: (дд.мм.гггг) <br />
    <input type="text" name="day" value="'.$data['day'].'" size="2" maxlength="2"/> .
    <input type="text" name="month" value="'.$data['month'].'" size="2" maxlength="2"/> .
    <input type="text" name="year" value="'.$data['year'].'" size="4" maxlength="4"/>
    <br />
    </div>
    <div class="block">
    Семейное положение: <br />
    <input type="radio" class="middle" name="marital_status" value="0" '.($data['marital_status'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="marital_status" value="1" '.($data['marital_status'] == 1 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'В активном поиске' : 'В активном поиске').' <br />
    <input type="radio" class="middle" name="marital_status" value="2" '.($data['marital_status'] == 2 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Влюблён' : 'Влюблёна').' <br />
    <input type="radio" class="middle" name="marital_status" value="3" '.($data['marital_status'] == 3 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Встречаюсь' : 'Встречаюсь').' <br />	
    <input type="radio" class="middle" name="marital_status" value="4" '.($data['marital_status'] == 4 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Помовлен' : 'Помовлена').' <br />
    <input type="radio" class="middle" name="marital_status" value="5" '.($data['marital_status'] == 5 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Женат' : 'Замужем').' <br />	
    <input type="radio" class="middle" name="marital_status" value="6" '.($data['marital_status'] == 6 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Всё сложно' : 'Всё сложно').' <br />
    <input type="radio" class="middle" name="marital_status" value="7" '.($data['marital_status'] == 7 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Не женат' : 'Не замужем').' <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';

// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами пользователь не существует"); } 
    } else { $system->redirect("Отказано в доступе", "/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		