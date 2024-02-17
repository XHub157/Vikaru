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

    $title = 'Типаж';

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
    | <a href="/modules/administration/user/edit/main/'.$data['id'].'" class="link">Основное</a>
    | <a href="/modules/administration/user/edit/contacts/'.$data['id'].'" class="link">Контакты</a>
    | <a href="/modules/administration/user/edit/interests/'.$data['id'].'" class="link">Интересы</a>
    | <a href="/modules/administration/user/edit/type/'.$data['id'].'" >Типаж</a>
    | <a href="/modules/administration/user/edit/additionally/'.$data['id'].'" class="link">Дополнительно</a>
    </div>';

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $orientation = abs(intval($_POST['orientation']));
    $purpose = abs(intval($_POST['purpose']));
    $physique = abs(intval($_POST['physique']));
    $smoke = abs(intval($_POST['smoke']));
    $growth = abs(intval($_POST['growth']));
    $weight = abs(intval($_POST['weight']));
    $hair = $system->check($_POST['hair']);
    $eyes = $system->check($_POST['eyes']);
    $character = $system->check($_POST['character']);
	
// Обработка варианта ориентации
	
    if ($orientation >= 0 && $orientation <= 3) {

// Обработка варианта цели знакомства

    if ($purpose >= 0 && $purpose <= 8) {

// Обработка варианта телосложения
	
    if ($physique >= 0 && $physique <= 5) {

// Обработка варианта курения

    if ($smoke >= 0 && $smoke <= 4) {

// Обработка символов роста
	
    if ($system->utf_strlen($growth) <= 3) {

// Обработка символов веса
	
    if ($system->utf_strlen($weight) <= 3) {

// Обработка символов цвета глаз
	
    if ($system->utf_strlen($hair) <= 20) {	

// Обработка символов цвета волос
	
    if ($system->utf_strlen($eyes) <= 20) {		

// Обработка символов цвета волос
	
    if ($system->utf_strlen($character) <= 100) {	
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `orientation`=?, `purpose`=?,`physique`=?,`smoke`=?,`growth`=?,`weight`=?, `hair`=?, `eyes`=?, `character`=?, `edit`=?, `edit_time`=? WHERE `id`=? LIMIT 1;", array($orientation, $purpose, $physique, $smoke, $growth, $weight, $hair, $eyes, $character, $user['id'], time(), $data['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/administration/user/".$data['id']."");	
	
// Выводим ошибки	
	
    } else { $system->show("Слишком длинное или короткое описание характера"); }	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Недопустимое количество символов"); } 	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }	
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/administration/user/$data[id]");   
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Ориентация: <br />
    <input type="radio" class="middle" name="orientation" value="0" '.($data['orientation'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="orientation" value="1" '.($data['orientation'] == 1 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Гетеро' : 'Гетеро').' <br />
    <input type="radio" class="middle" name="orientation" value="2" '.($data['orientation'] == 2 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Гей' : 'Лесби').' <br />
    <input type="radio" class="middle" name="orientation" value="3" '.($data['orientation'] == 3 ? 'checked="checked"':'').'> '.($data['sex'] == 0 ? 'Би' : 'Би').' <br />	
    </div>
    <div class="block">
    Цель знакомства: <br />
    <input type="radio" class="middle" name="purpose" value="0" '.($data['purpose'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="purpose" value="1" '.($data['purpose'] == 1 ? 'checked="checked"':'').'> Дружба и общение <br />
    <input type="radio" class="middle" name="purpose" value="2" '.($data['purpose'] == 2 ? 'checked="checked"':'').'> Флирт, СМС-переписка <br />
    <input type="radio" class="middle" name="purpose" value="3" '.($data['purpose'] == 3 ? 'checked="checked"':'').'> Любовь, отношения <br />
    <input type="radio" class="middle" name="purpose" value="4" '.($data['purpose'] == 4 ? 'checked="checked"':'').'> Брак, создание семьи <br />
    <input type="radio" class="middle" name="purpose" value="5" '.($data['purpose'] == 5 ? 'checked="checked"':'').'> Виртуальный секс <br />
    <input type="radio" class="middle" name="purpose" value="6" '.($data['purpose'] == 6 ? 'checked="checked"':'').'> Секс в реале <br />
    <input type="radio" class="middle" name="purpose" value="7" '.($data['purpose'] == 7 ? 'checked="checked"':'').'> Ищу спонсора <br />
    <input type="radio" class="middle" name="purpose" value="8" '.($data['purpose'] == 8 ? 'checked="checked"':'').'> Стану спонсором <br />
    </div>
    <div class="block">
    Телосложение: <br />
    <input type="radio" class="middle" name="physique" value="0" '.($data['physique'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="physique" value="1" '.($data['physique'] == 1 ? 'checked="checked"':'').'> Обычное <br />
    <input type="radio" class="middle" name="physique" value="2" '.($data['physique'] == 2 ? 'checked="checked"':'').'> Худощавое <br />
    <input type="radio" class="middle" name="physique" value="3" '.($data['physique'] == 3 ? 'checked="checked"':'').'> Спортивное <br />
    <input type="radio" class="middle" name="physique" value="4" '.($data['physique'] == 4 ? 'checked="checked"':'').'> Мускулистое <br />
    <input type="radio" class="middle" name="physique" value="5" '.($data['physique'] == 5 ? 'checked="checked"':'').'> Плотное <br />
    </div>
    <div class="block">
    Курение: </br>
    <input type="radio" class="middle" name="smoke" value="0" '.($data['smoke'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="smoke" value="1" '.($data['smoke'] == 1 ? 'checked="checked"':'').'> Не курю <br />
    <input type="radio" class="middle" name="smoke" value="2" '.($data['smoke'] == 2 ? 'checked="checked"':'').'> Курю <br />
    <input type="radio" class="middle" name="smoke" value="3" '.($data['smoke'] == 3 ? 'checked="checked"':'').'> Иногда <br />
    <input type="radio" class="middle" name="smoke" value="4" '.($data['smoke'] == 4 ? 'checked="checked"':'').'> Бросаю <br />
    </div>
    <div class="block">
    Рост: (см) <br />
    <input type="text" name="growth" value="'.$data['growth'].'"/> <br />
    </div>
    <div class="block">
    Вес: (кг) <br />
    <input type="text" name="weight" value="'.$data['weight'].'"/> <br />
    </div>	
    <div class="block">
    Цвет волос: (20 символов) <br />
    <input type="text" name="hair" value="'.$data['hair'].'"/> <br />
    </div>	
    <div class="block">
    Цвет глаз: (20 символов) <br />
    <input type="text" name="eyes" value="'.$data['eyes'].'"/> <br />
    </div>	
    <div class="block">
    Характер: (100 символов) <br />
    <input type="text" name="character" value="'.$data['character'].'"/> <br />
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