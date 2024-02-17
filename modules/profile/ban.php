<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Нарушения';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data)) {

// Выводим меню

    echo '
    <div class="hide">
    Нарушения '.$profile->login($data['id']).'
    </div>';

// Подсчёт количества нарушений
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_ban` WHERE `user`=?;", array($data['id']));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Формируем заголовки

    $cause = array("Грубость и оскорбления", "Нецензурная лексика", "Спам, реклама", "Разжигание ненависти", "Флуд, Оффтопик", "Намеки на детскую порнографию", "Детская порнография", "Педофилия", "Попытки входа на сайт в обход блокировки", "Подозрение на взлом (блок со сменой пароля)", "Иное"); 	

// Выводим нарушений	

    $q = DB :: $dbh -> query("SELECT * FROM `user_ban` WHERE `user`=? ORDER BY `last_time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));	
	
// Выводим подарок

    while ($act = $q -> fetch()) {	
	
    echo '
    <div class="block">
    Причина: '.$cause[$act['cause']].' <br />
    '.(!empty($act['message']) ? 'Сообщение: '.$text->check($act['message']).' <br />' : '').'
    Дата: '.$system->system_time($act['last_time']).' <br />
    Время: '.($act['time'] - $act['last_time']) / 3600 .' ' . $system->ending(($act['time'] - $act['last_time']) / 3600, 'час', 'часа', 'часов') . ' <br />
    '.($act['time'] > time() ? 'Время окончания блока: '.$system->system_time($act['time']).' <br />' : '').'
    Пользователь: '.$profile->login($act['user']).' <br />
    Администратор: '.$profile->login($act['last_user']).' <br />
    </div>';

    }  	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/profile/ban/'.$data['id'].'/?', $config['post'], $page, $count);	

// Выводим ошибки
	
    } else { $system->show("Нарушений нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>