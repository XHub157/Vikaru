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

    $title = 'Поиск по сообщениях';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Выводим меню

    echo '
    <div class="hide">
    Поиск по сообщениям:
    <form method="post" action="/modules/administration/mail/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'" style="width: 50%;" />
    <input type="submit" value="Искать" class="submit" /></form>
    </div>';

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

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `message` like '%".$search."%';");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '<div class="hide">Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' сообщений</div>';

// Выводим сообщения

    $q = DB :: $dbh -> query("SELECT * FROM `mail_message` WHERE `message` like '%".$search."%' ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим сообщение

    while ($act = $q -> fetch()) {

    echo '
    <div class="block">
    '.$profile->login($act['user']).' -> '.$profile->login($act['profile']).'
    '.($act['read'] == 1 ? '<span style="color: #FF0000;">(не прочитано)</span>' : '').'
    <span style="float: right;"> 
    '.$system->system_time($act['time']).'
    </span><br />
    '.$text->check($act['message']).' 
    <hr>
    '.$act['ip'].' :: '.$act['ua'].'
    </div>'; 

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/administration/search/?', $config['post'], $page, $count);		 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/administration/mail"); }
    } else { $system->redirect("Отказано в доступе", "/"); }	
    
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>