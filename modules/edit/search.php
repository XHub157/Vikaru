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
    $_SESSION['country'] = $_POST['country'];	

    }

    if (isset($_SESSION['search'])) {  	
	
// Обработка поискового запроса

    $search = $system->check($_SESSION['search']);	
	
// Обработка номера страны

    $country = intval($_SESSION['country']);	

// Обработка количества символов поиска
	
    if ($system->utf_strlen($search) >= 3 && $system->utf_strlen($search) < 100) {
	
// Подчёт количества городов

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `geo_cities` WHERE `cid`=? AND `city_name` like '%".$search."%';", array($country));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '
    <div class="hide">
    Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' городов
    </div>';

// Выводим города

    $q = DB :: $dbh -> query("SELECT * FROM `geo_cities` WHERE `cid`=? AND `city_name` like '%".$search."%' ORDER BY `city_name` ASC  LIMIT " . $page . ", " . $config['post'] . ";", array($country));

// Выводим город

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch" href="/modules/edit/city/'.$act['city_id'].'">'.$act['city_name'].'</a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/edit/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/edit/country"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>