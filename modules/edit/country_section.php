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

    $title = 'Регион';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим страну в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `geo_countries` WHERE `country_id`=? LIMIT 1;", array($id));
    $country = $queryguest -> fetch();

// Только если данная страна существует
	
    if (!empty($country)) {

// Выводим форму поиска

    echo '<div class="hide"><form method="post" action="/modules/edit/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$_SESSION['search'].'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="country" value="'.$country['country_id'].'" />
    <input type="submit" value="Искать" class="submit" /></form></div>';

// Подсчёт количества регионов страны
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `geo_regions` WHERE `cid`=?;", array($country['country_id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим регионы
	
    $q = DB :: $dbh -> query("SELECT * FROM `geo_regions` WHERE `cid`=? ORDER BY `region_name` ASC  LIMIT " . $page . ", " . $config['post'] . ";", array($country['country_id']));		
	
// Выводим город

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch" href="/modules/edit/region_section/'.$act['region_id'].'">'.$act['region_name'].'</a>
    ';
	
    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/edit/country_section/'.$country['country_id'].'/?', $config['post'], $page, $count);	 

// Выводим ошибки
	
    } else { $system->show("Регионов нет"); }   
    } else { $system->show("Выбранная вами страна не существует"); } 		
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>