<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем ядро аватаров
	
    $avatar = new avatar();

// Выводим шапку

    $title = 'Поиск';

// Инклудим шапку

include_once (ROOT.'template/head.php');

 // Выводим блок	

echo "

<div class='info-block bottom_link_block tools_block show-all r_field-list ' style='margin-bottom: 0px;'>
<form method='post' action='/modules/search/'>
<table style='width: 100%;' cellspacing='0' cellpadding='0'>
<tr>
<td style='width: 100%;' class='m'>
<div style='padding:0 20px 0 10px;'>
<input type='text' class='font_medium m' placeholder='Поиск..' style='width: 100%; margin: 0 0 0 -10px;' id='search_text' name='search' size='15' value=''>
</td>

<td class='m'>
<input type='submit' style='line-height: 19px;  margin-top: 0;' class='main_submit' value='Найти'>
</td>
</tr>  
</table>
</form>
</div>
";
	
    echo '
	<div class="listbar">
    <a href="/modules/dating/" class="">Все обитатели</a>
    <a href="/modules/dating/online" class="">Сейчас в сети</a>
    <a href="/modules/dating/women" class="">Женщины</a>
	<a href="/modules/dating/male" class="">Мужчины</a>
	<a href="/modules/dating/search" class="listbar-act" style="color: darkred;">Поиск</a>
    </div>
    ';
// Только если отправлен POST запрос	
	
    if (isset($_POST['search'])) {
	
// Обработка переменных		

    $first_name = NULL;
    if (isset($_SESSION['first_name'])) $first_name = $system->check($_SESSION['first_name']);
    if (isset($_POST['first_name'])) $first_name = $system->check($_POST['first_name']);
    $_SESSION['first_name'] = $first_name;

    $last_name = NULL;
    if (isset($_SESSION['last_name'])) $last_name = $system->check($_SESSION['last_name']);
    if (isset($_POST['last_name'])) $last_name = $system->check($_POST['last_name']);
    $_SESSION['last_name'] = $last_name;
	
    $sex = NULL;
    if (isset($_SESSION['sex'])) $sex = abs(intval($_SESSION['sex']));
    if (isset($_POST['sex'])) $sex = abs(intval($_POST['sex']));
    $_SESSION['sex'] = $sex;	

    $age_from = NULL;
    if (isset($_SESSION['age_from'])) $age_from = abs(intval($_SESSION['age_from']));
    if (isset($_POST['age_from'])) $age_from = abs(intval($_POST['age_from']));
    $_SESSION['age_from'] = $age_from;	
	
    $age_to = NULL;
    if (isset($_SESSION['age_to'])) $age_to = abs(intval($_SESSION['age_to']));
    if (isset($_POST['age_to'])) $age_to = abs(intval($_POST['age_to']));
    $_SESSION['age_to'] = $age_to;		

    $online = NULL;
    $online = (empty($_SESSION['online'])) ? 0 : 1;
    $online = (empty($_POST['online'])) ? 0 : 1;
    $_SESSION['online'] = $online;
	
    $user_photo = NULL;
    $user_photo = (empty($_SESSION['user_photo'])) ? 0 : 1;
    $user_photo = (empty($_POST['user_photo'])) ? 0 : 1;
    $_SESSION['user_photo'] = $user_photo;	
	
    header ("Location: /modules/dating/search");

    } else if (isset($_POST['back'])) {
    header ("Location: /modules/dating/");  
    }	

// Выводим форму

    echo '
    <div class="block">
    <form method="post">
    Имя:  <br />
    <input type="text" name="first_name" value="'.(empty($_SESSION['first_name']) ? '' : ''.$_SESSION['first_name'].'').'"/> <br />
    Фамилия:  <br />
    <input type="text" name="last_name" value="'.(empty($_SESSION['last_name']) ? '' : ''.$_SESSION['last_name'].'').'"/> <br />
    Пол: </br>
    <input type="radio" class="middle" name="sex" value="0" '.(!empty($_SESSION['sex']) == 0 ? 'checked="checked"':'').'/> Мужской <br />
    <input type="radio" class="middle" name="sex" value="1" '.(!empty($_SESSION['sex']) == 1 ? 'checked="checked"':'').'/> Женский <br />	
    Возраст: <br />
    от <input type="text" name="age_from" value="'.(empty($_SESSION['age_from']) ? '' : ''.$_SESSION['age_from'].'').'" size="2" maxlength="2"/>
    до <input type="text" name="age_to" value="'.(empty($_SESSION['age_to']) ? '' : ''.$_SESSION['age_to'].'').'" size="2" maxlength="2"/> <br />
    <input type="checkbox" class="middle" name="online" value="1" '.(!empty($_SESSION['online']) == 1 ? 'checked="checked"':'').'/> На сайте <br />
    <input type="checkbox" class="middle" name="user_photo" value="1" '.(!empty($_SESSION['user_photo']) == 1 ? 'checked="checked"':'').'/> С фотографией <br />	
    </div>
    <div class="block">
    <input type="submit" name="search" value="Искать" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>'; 

// Только если переменные не пусты

    if (isset($_SESSION['first_name']) || isset($_SESSION['sex']) || isset($_SESSION['last_name']) || isset($_SESSION['age_from']) || isset($_SESSION['age_to']) || isset($_SESSION['online']) || isset($_SESSION['user_photo'])) {

// Формируем переменные

    $first_name = ($_SESSION['first_name'] == NULL) ? " WHERE " : " WHERE `first_name`='".$_SESSION['first_name']."' AND ";
    $sex = ($_SESSION['sex'] == 1) ? "`sex`='1' " : "`sex` = '0' ";
    $last_name = ($_SESSION['last_name'] == NULL) ? " " : " AND `last_name`='".$_SESSION['last_name']."' ";
    $age_from = ($_SESSION['age_from'] == NULL) ? " " : "".date("Y") - $_SESSION['age_from']."";
    $age_to = ($_SESSION['age_to'] == NULL) ? " " : "".date("Y") - $_SESSION['age_to']."";
    $birthday = ($_SESSION['age_from'] == NULL && $_SESSION['age_to'] == NULL) ? " " : " AND `year`>'".$age_to."' AND `year`<'".$age_from."' ";
    $online = ($_SESSION['online'] == 0) ? " " : " AND `aut` > '".(time()-60)."' ";
    $user_photo = ($_SESSION['user_photo'] == 0) ? " " : " AND `avatar` = '1' ";
	
// Подсчёт количества пользователей

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` ".$first_name." ".$sex."  ".$last_name." ".$birthday." ".$online." ".$user_photo.";");	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим блок

    echo '
    <div class="hide">
    Результаты поиска
    <span class="count">'.$count.'</span>
    </div>';

// Выводим пользователей

    $q = DB :: $dbh -> query("SELECT * FROM `user` ".$first_name." ".$sex." ".$last_name." ".$birthday." ".$online." ".$user_photo." ORDER BY `rating` DESC  LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим пользователя

    while ($act = $q -> fetch()) {

// Выводим данные пользователя

    $data = $profile->data($act['id']);	
	
    echo '
    <div class="block">
			'.($act['id'] == $user['id'] ? '
	' : '
    <span class="right"><a href="/modules/mail/contact/'.$act['id'].'">Написать сообщение</a> <br /> </span>
     ').'
    <table><tr><td>
    '.$avatar->micro($act['id'], 64,64).'
    </td><td valign=top style="padding-left: 10px;">
    '.$profile->user($act['id']).'
    </span>'.($data['city'] != NULL && $data['region'] != NULL && $data['country'] != NULL ? '
    <br />'.$data['city'].'. '.$data['region'].' '.$data['country'].'' : '').'
    </td></tr></table>
    </div>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/search/?', $config['post'], $page, $count);	 		
    }} 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>