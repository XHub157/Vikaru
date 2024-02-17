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

// Меню

    echo '
    <div class="hide">
    <a href="/modules/edit/main" class="link">Основное</a>
    | <a href="/modules/edit/contacts" class="link">Контакты</a>
    | <a href="/modules/edit/interests" class="link">Интересы</a>
    | <a href="/modules/edit/type">Типаж</a>
    | <a href="/modules/edit/additionally" class="link">Дополнительно</a>
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
	
    DB :: $dbh -> query("UPDATE `user` SET `orientation`=?, `purpose`=?,`physique`=?,`smoke`=?,`growth`=?,`weight`=?, `hair`=?, `eyes`=?, `character`=? WHERE `id`=? LIMIT 1;", array($orientation, $purpose, $physique, $smoke, $growth, $weight, $hair, $eyes, $character, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/type");	
	
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
    header ("Location: /modules/profile/$user[id]");   
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Ориентация: <br />
    <input type="radio" class="middle" name="orientation" value="0" '.($user['orientation'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="orientation" value="1" '.($user['orientation'] == 1 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Гетеро' : 'Гетеро').' <br />
    <input type="radio" class="middle" name="orientation" value="2" '.($user['orientation'] == 2 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Гей' : 'Лесби').' <br />
    <input type="radio" class="middle" name="orientation" value="3" '.($user['orientation'] == 3 ? 'checked="checked"':'').'> '.($user['sex'] == 0 ? 'Би' : 'Би').' <br />	
    </div>
    <div class="block">
    Цель знакомства: <br />
    <input type="radio" class="middle" name="purpose" value="0" '.($user['purpose'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="purpose" value="1" '.($user['purpose'] == 1 ? 'checked="checked"':'').'> Дружба и общение <br />
    <input type="radio" class="middle" name="purpose" value="2" '.($user['purpose'] == 2 ? 'checked="checked"':'').'> Флирт, СМС-переписка <br />
    <input type="radio" class="middle" name="purpose" value="3" '.($user['purpose'] == 3 ? 'checked="checked"':'').'> Любовь, отношения <br />
    <input type="radio" class="middle" name="purpose" value="4" '.($user['purpose'] == 4 ? 'checked="checked"':'').'> Брак, создание семьи <br />
    <input type="radio" class="middle" name="purpose" value="5" '.($user['purpose'] == 5 ? 'checked="checked"':'').'> Виртуальный секс <br />
    <input type="radio" class="middle" name="purpose" value="6" '.($user['purpose'] == 6 ? 'checked="checked"':'').'> Секс в реале <br />
    <input type="radio" class="middle" name="purpose" value="7" '.($user['purpose'] == 7 ? 'checked="checked"':'').'> Ищу спонсора <br />
    <input type="radio" class="middle" name="purpose" value="8" '.($user['purpose'] == 8 ? 'checked="checked"':'').'> Стану спонсором <br />
    </div>
    <div class="block">
    Телосложение: <br />
    <input type="radio" class="middle" name="physique" value="0" '.($user['physique'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="physique" value="1" '.($user['physique'] == 1 ? 'checked="checked"':'').'> Обычное <br />
    <input type="radio" class="middle" name="physique" value="2" '.($user['physique'] == 2 ? 'checked="checked"':'').'> Худощавое <br />
    <input type="radio" class="middle" name="physique" value="3" '.($user['physique'] == 3 ? 'checked="checked"':'').'> Спортивное <br />
    <input type="radio" class="middle" name="physique" value="4" '.($user['physique'] == 4 ? 'checked="checked"':'').'> Мускулистое <br />
    <input type="radio" class="middle" name="physique" value="5" '.($user['physique'] == 5 ? 'checked="checked"':'').'> Плотное <br />
    </div>
    <div class="block">
    Курение: </br>
    <input type="radio" class="middle" name="smoke" value="0" '.($user['smoke'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="smoke" value="1" '.($user['smoke'] == 1 ? 'checked="checked"':'').'> Не курю <br />
    <input type="radio" class="middle" name="smoke" value="2" '.($user['smoke'] == 2 ? 'checked="checked"':'').'> Курю <br />
    <input type="radio" class="middle" name="smoke" value="3" '.($user['smoke'] == 3 ? 'checked="checked"':'').'> Иногда <br />
    <input type="radio" class="middle" name="smoke" value="4" '.($user['smoke'] == 4 ? 'checked="checked"':'').'> Бросаю <br />
    </div>
    <div class="block">
    Рост: (см) <br />
    <input type="text" name="growth" value="'.$user['growth'].'" size="3" maxlength="3"/> <br />
    </div>
    <div class="block">
    Вес: (кг) <br />
    <input type="text" name="weight" value="'.$user['weight'].'" size="3" maxlength="3"/> <br />
    </div>	
    <div class="block">
    Цвет волос: (20 символов) <br />
    <input type="text" name="hair" value="'.$user['hair'].'"/> <br />
    </div>	
    <div class="block">
    Цвет глаз: (20 символов) <br />
    <input type="text" name="eyes" value="'.$user['eyes'].'"/> <br />
    </div>	
    <div class="block">
    Характер: (100 символов) <br />
    <input type="text" name="character" value="'.$user['character'].'"/> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>