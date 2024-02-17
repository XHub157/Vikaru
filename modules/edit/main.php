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

// Меню

    echo '
    <div class="hide">
    <a href="/modules/edit/main">Основное</a>
    | <a href="/modules/edit/contacts" class="link">Контакты</a>
    | <a href="/modules/edit/interests" class="link">Интересы</a>
    | <a href="/modules/edit/type" class="link">Типаж</a>
    | <a href="/modules/edit/additionally" class="link">Дополнительно</a>
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
	
    if ($system->utf_strlen($first_name) >= 3 && $system->utf_strlen($first_name) <= 20) {
 
// Символы в имени
    		
    if(preg_match('#^([A-zА-я \-]*)$#ui', $first_name)) {

// Обработка количества символов фамилии
	
       if ($system->utf_strlen($last_name) >= 3 && $system->utf_strlen($last_name) <= 20) {

// Символы в фамилии
    		
    if(preg_match('#^([A-zА-я \-]*)$#ui', $last_name)) {
    
// Обработка пола
	
    if ($sex == 0 || $sex == 1) {	

// Обработка символов даты рождения

    if ($system->utf_strlen($day) > 1 && $system->utf_strlen($month) > 1 && $day > 0 && $day < 32 && $month > 1 && $month < 13 && $year > 1949 && $year < 2011 || $day.$month.$year == NULL) {

// Обработка пола
	
    if ($marital_status == 0 || $marital_status == 1 || $marital_status == 2 || $marital_status == 3 || $marital_status == 4 || $marital_status == 5 || $marital_status == 6 || $marital_status == 7) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `hello`=?, `first_name`=?, `last_name`=?, `sex`=?, `day`=?, `month`=?, `year`=?, `marital_status`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($hello, $first_name, $last_name, $sex, $day, $month, $year, $marital_status, $user['id'], time(), $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/main");	
	
// Выводим ошибки	
	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }	
    } else { $system->show("Не верно заполнена дата рождения, формат (дд.мм.гггг)"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); } 	
    } else { $system->show("Фамилия содержит не допустимые символы"); }
    } else { $system->show("Слишком длинная или короткая фамилия"); }
        } else { $system->show("Имя содержит не допустимые символы"); }	
        } else { $system->show("Слишком длинное или короткое имя"); }
    } else { $system->show("Слишком длинное или короткое приветствие"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/profile/$user[id]");  
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Приветствие: (200 символов) <br />
    <input type="text" name="hello" style="width: 70%;" value="'.$user['hello'].'"/> <br />
    </div>
    <div class="block">
    Имя: (20 символов) <br />
    <input type="text" name="first_name" value="'.$user['first_name'].'"/> <br />
    </div>
    <div class="block">
    Фамилия: (20 символов) <br />
    <input type="text" name="last_name" value="'.$user['last_name'].'"/> <br />
    </div>
    <div class="block">
    Пол: </br>
    <input type="radio" class="middle" name="sex" value="0" '.($user['sex'] == 0 ? 'checked="checked"':'').'> Мужской <br />
    <input type="radio" class="middle" name="sex" value="1" '.($user['sex'] == 1 ? 'checked="checked"':'').'> Женский <br />
    </div>
    <div class="block">
    Дата рождения: (дд.мм.гггг) <br />
    <input type="text" name="day" value="'.$user['day'].'" size="2" maxlength="2"/> .
    <input type="text" name="month" value="'.$user['month'].'" size="2" maxlength="2"/> .
    <input type="text" name="year" value="'.$user['year'].'" size="4" maxlength="4"/>
    <br />
    </div>
    <div class="block">
    Семейное положение: <br />
    <input type="radio" class="middle" name="marital_status" value="0" '.($user['marital_status'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="marital_status" value="1" '.($user['marital_status'] == 1 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'В активном поиске' : 'В активном поиске').' <br />
    <input type="radio" class="middle" name="marital_status" value="2" '.($user['marital_status'] == 2 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Влюблён' : 'Влюблёна').' <br />
    <input type="radio" class="middle" name="marital_status" value="3" '.($user['marital_status'] == 3 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Встречаюсь' : 'Встречаюсь').' <br />	
    <input type="radio" class="middle" name="marital_status" value="4" '.($user['marital_status'] == 4 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Помовлен' : 'Помовлена').' <br />
    <input type="radio" class="middle" name="marital_status" value="5" '.($user['marital_status'] == 5 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Женат' : 'Замужем').' <br />	
    <input type="radio" class="middle" name="marital_status" value="6" '.($user['marital_status'] == 6 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Всё сложно' : 'Всё сложно').' <br />
    <input type="radio" class="middle" name="marital_status" value="7" '.($user['marital_status'] == 7 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Не женат' : 'Не замужем').' <br />	
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		