<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Поиск';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Только если отправлен POST запрос

    if (isset($_POST['search'])) {

    $_SESSION['search'] = $_POST['search'];
    $_SESSION['where'] = $_POST['where'];	

    }	

// Только если поисковый запрос задан	

    if (isset($_SESSION['search'])) { 
	
// Обработка поискового запроса

    $search = $system->check($_SESSION['search']);	
	
// Обработка варианта запроса

    $where = (empty($_SESSION['where'])) ? 0 : 1;

// Обработка количества символов поиска
	
    if ($system->utf_strlen($search) >= 3 && $system->utf_strlen($search) < 100) {
	
// Подчёт количества тем

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `".($where == 0 ? 'name' : 'description')."` like '%".$search."%';");	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '
    <div class="hide">
    Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' тем
    </div>
    ';	

// Выводим темы

    $q = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `".($where == 0 ? 'name' : 'description')."` like '%".$search."%' ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch_white" href="/modules/forum/topic/'.$act['id'].'">
    '.($act['locked'] > 0 ? '
    <img class="middle" src="/icons/locked.png">
    ' : '<img class="middle" src="/icons/topic.png">').'
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).' 
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    '.$act['name'].' 
    '.($act['closed'] > 0 ? '<img class="middle" src="/icons/access_me.png">' : '').'
    <br />
    '.$profile->us($act['user']).' / '.$profile->us($act['last_user']).' <br />
    Комментариев <span class="count">'.$act['comments'].'</span>
    </a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/forum/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>