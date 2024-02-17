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

// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Поиск';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Только если отправлен POST запрос

    if (isset($_POST['search'])) {

    $_SESSION['search'] = $_POST['search'];

    }	

    if (isset($_SESSION['search'])) {  
	
// Обработка поискового запроса

    $search = $system->check($_SESSION['search']);	

// Обработка количества символов поиска
	
    if ($system->utf_strlen($search) >= 3 && $system->utf_strlen($search) < 100) {
	
// Подсчёт количества сообщений

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `message` like '%".$search."%' AND `user`=? OR `profile`=? AND `message` like '%".$search."%';", array($user['id'], $user['id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '<div class="hide">Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' сообщений</div>';

// Выводим сообщения

    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `message` like '%".$search."%' AND `user`=? OR `profile`=? AND `message` like '%".$search."%' ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id'], $user['id']));

// Выводим сообщение

    while ($act = $q -> fetch()) {

    echo '<div class="block">
    '.$profile->user($act['user']).' ::
    <a href="/modules/mail/contact/'.($act['user'] == $user['id'] ? "".$act['profile']."" : "".$act['user']."").'">
    '.$profile->us($act['profile']).'
    </a>
    <span style="float: right;">
    '.$system->system_time($act['time']).'
    </span> <br />
    '.$text->number($act['message'], 250).'
    </div>';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/mail/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/mail/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>