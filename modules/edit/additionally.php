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

    $title = 'Дополнительно';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Выводим меню

    echo '
    <div class="hide">
    <a href="/modules/edit/main" class="link">Основное</a>
    | <a href="/modules/edit/contacts" class="link">Контакты</a>
    | <a href="/modules/edit/interests" class="link">Интересы</a>
    | <a href="/modules/edit/type" class="link">Типаж</a>
    | <a href="/modules/edit/additionally">Дополнительно</a>
    </div>';

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Обработка переменных	

    $business = abs(intval($_POST['business']));
    $profession = $system->check($_POST['profession']);	
    $policy = abs(intval($_POST['policy']));
    $world_view = abs(intval($_POST['world_view']));	
    $life = abs(intval($_POST['life']));	
    $people = abs(intval($_POST['people']));
    $inspire = $system->check($_POST['inspire']);	
    $about_me = $system->check($_POST['about_me']);	

// Обработка варианта чем я занимаюсь

    if ($business >= 0 && $business <= 7) {
	
// Обработка символов професии
	
    if ($system->utf_strlen($profession) <= 100) {
	
// Обработка вариантов полит. предпочтения
	
    if ($policy >= 0 && $policy <= 7) {

// Обработка вариантов мировозрения
	
    if ($world_view >= 0 && $world_view <= 8) {

// Обработка вариантов главное в жизни
	
    if ($life >= 0 && $life <= 8) {	
	
// Обработка вариантов главное в людях
	
    if ($people >= 0 && $people <= 6) {	

// Обработка символов источника вдохновления	
	
    if ($system->utf_strlen($inspire) <= 1000) {	

// Обработка символов о себе
	
    if ($system->utf_strlen($about_me) <= 10000) {

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `business`=?,`profession`=?,`policy`=?, `world_view`=?, `life`=?, `people`=?, `inspire`=?, `about_me`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($business, $profession, $policy, $world_view, $life, $people, $inspire, $about_me, $user['id'], time(), $user['id']));	
	
// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/additionally");	

// Выводим ошибки	
		
    } else { $system->show("Недопустимое количество символов"); }
    } else { $system->show("Недопустимое количество символов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Недопустимое количество символов"); }
    } else { $system->show("Пожалуйста, выберите один из вариантов"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/profile/$user[id]");   
    }	
	
// Выводим форму	

    echo '<div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Чем я занимаюсь: <br />
    <input type="radio" class="middle" name="business" value="0" '.($user['business'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="business" value="1" '.($user['business'] == 1 ? 'checked="checked"':'').'> Учусь в школе <br />
    <input type="radio" class="middle" name="business" value="2" '.($user['business'] == 2 ? 'checked="checked"':'').'> Учусь в колледже/лицее <br />
    <input type="radio" class="middle" name="business" value="3" '.($user['business'] == 3 ? 'checked="checked"':'').'> Учусь в ВУЗе <br />
    <input type="radio" class="middle" name="business" value="4" '.($user['business'] == 4 ? 'checked="checked"':'').'> Учусь в военном училище <br />
    <input type="radio" class="middle" name="business" value="5" '.($user['business'] == 5 ? 'checked="checked"':'').'> Служу в армии <br />
    <input type="radio" class="middle" name="business" value="6" '.($user['business'] == 6 ? 'checked="checked"':'').'> Работаю <br />
    <input type="radio" class="middle" name="business" value="7" '.($user['business'] == 7 ? 'checked="checked"':'').'> Не работаю <br />
    </div>
    <div class="block">
    Профессия: (100 символов) <br />
    <input type="text" name="profession" value="'.$user['profession'].'"/> <br />
    </div>
    <div class="block">
    Полит. предпочтения: <br />
    <input type="radio" class="middle" name="policy" value="0" '.($user['policy'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="policy" value="1" '.($user['policy'] == 1 ? 'checked="checked"':'').'> Индифферентные <br />
    <input type="radio" class="middle" name="policy" value="2" '.($user['policy'] == 2 ? 'checked="checked"':'').'> Коммунистические <br />
    <input type="radio" class="middle" name="policy" value="3" '.($user['policy'] == 3 ? 'checked="checked"':'').'> Социалистические <br />
    <input type="radio" class="middle" name="policy" value="4" '.($user['policy'] == 4 ? 'checked="checked"':'').'> Умеренные <br />
    <input type="radio" class="middle" name="policy" value="5" '.($user['policy'] == 5 ? 'checked="checked"':'').'> Либеральные <br />
    <input type="radio" class="middle" name="policy" value="6" '.($user['policy'] == 6 ? 'checked="checked"':'').'> Консервативные <br />
    <input type="radio" class="middle" name="policy" value="7" '.($user['policy'] == 7 ? 'checked="checked"':'').'> Монархические <br />
    </div>
    <div class="block">
    Мировоззрение: <br />
    <input type="radio" class="middle" name="world_view" value="0" '.($user['world_view'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="world_view" value="1" '.($user['world_view'] == 1 ? 'checked="checked"':'').'> Иудаизм <br />
    <input type="radio" class="middle" name="world_view" value="2" '.($user['world_view'] == 2 ? 'checked="checked"':'').'> Православные <br />
    <input type="radio" class="middle" name="world_view" value="3" '.($user['world_view'] == 3 ? 'checked="checked"':'').'> Католицизм <br />
    <input type="radio" class="middle" name="world_view" value="4" '.($user['world_view'] == 4 ? 'checked="checked"':'').'> Протестантизм <br />
    <input type="radio" class="middle" name="world_view" value="5" '.($user['world_view'] == 5 ? 'checked="checked"':'').'> Ислам <br />
    <input type="radio" class="middle" name="world_view" value="6" '.($user['world_view'] == 6 ? 'checked="checked"':'').'> Буддизм <br />
    <input type="radio" class="middle" name="world_view" value="7" '.($user['world_view'] == 7 ? 'checked="checked"':'').'> Конфуцианство <br />
    <input type="radio" class="middle" name="world_view" value="8" '.($user['world_view'] == 8 ? 'checked="checked"':'').'> Светский гуманизм <br />
    </div>
    <div class="block">
    Главное в жизни: <br />
    <input type="radio" class="middle" name="life" value="0" '.($user['life'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="life" value="1" '.($user['life'] == 1 ? 'checked="checked"':'').'> Семья и дети <br />
    <input type="radio" class="middle" name="life" value="2" '.($user['life'] == 2 ? 'checked="checked"':'').'> Карьера и деньги <br />
    <input type="radio" class="middle" name="life" value="3" '.($user['life'] == 3 ? 'checked="checked"':'').'> Развлечения и отдых <br />
    <input type="radio" class="middle" name="life" value="4" '.($user['life'] == 4 ? 'checked="checked"':'').'> Наука и исследования <br />
    <input type="radio" class="middle" name="life" value="5" '.($user['life'] == 5 ? 'checked="checked"':'').'> Совершенствования мира <br />
    <input type="radio" class="middle" name="life" value="6" '.($user['life'] == 6 ? 'checked="checked"':'').'> Саморазвитие <br />
    <input type="radio" class="middle" name="life" value="7" '.($user['life'] == 7 ? 'checked="checked"':'').'> Красота и искусство <br />
    <input type="radio" class="middle" name="life" value="8" '.($user['life'] == 8 ? 'checked="checked"':'').'> Слава и влияние <br />
    </div>
    <div class="block">
    Главное в людях: <br />
    <input type="radio" class="middle" name="people" value="0" '.($user['people'] == 0 ? 'checked="checked"':'').'> Не выбрано <br />
    <input type="radio" class="middle" name="people" value="1" '.($user['people'] == 1 ? 'checked="checked"':'').'> Ум и креативность <br />
    <input type="radio" class="middle" name="people" value="2" '.($user['people'] == 2 ? 'checked="checked"':'').'> Доброта и честность <br />
    <input type="radio" class="middle" name="people" value="3" '.($user['people'] == 3 ? 'checked="checked"':'').'> Красота и здоровье <br />
    <input type="radio" class="middle" name="people" value="4" '.($user['people'] == 4 ? 'checked="checked"':'').'> Власть и богатство <br />
    <input type="radio" class="middle" name="people" value="5" '.($user['people'] == 5 ? 'checked="checked"':'').'> Смелость и упорство <br />
    <input type="radio" class="middle" name="people" value="6" '.($user['people'] == 6 ? 'checked="checked"':'').'> Юмор и жизнелюбие <br />
    </div>
    <div class="block">
    Источники вдохновения: (1000 символов) <br />
    <textarea name="inspire" class="comment" />'.$user['inspire'].'</textarea> <br />	
    </div>
    <div class="block">
    О себе: (10000 символов) <br />
    <textarea name="about_me" class="comment" />'.$user['about_me'].'</textarea> <br />
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	