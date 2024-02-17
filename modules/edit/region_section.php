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

    $title = 'Город';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим регион в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `geo_regions` WHERE `region_id`=? LIMIT 1;", array($id));
    $region = $queryguest -> fetch();

// Только если данный регион существует
	
    if (!empty($region)) {

// Выводим форму поиска

    echo '<div class="hide"><form method="post" action="/modules/edit/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$_SESSION['search'].'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="hidden" name="country" value="'.$region['cid'].'" />
    <input type="submit" value="Искать" class="submit" /></form></div>';

// Подсчёт количества городов региона
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `geo_cities` WHERE `rid`=?;", array($region['region_id']));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим города
	
    $q = DB :: $dbh -> query("SELECT * FROM `geo_cities` WHERE `rid`=? ORDER BY `city_name` ASC  LIMIT " . $page . ", " . $config['post'] . ";", array($region['region_id']));		
	
// Выводим город

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch" href="/modules/edit/city/'.$act['city_id'].'">'.$act['city_name'].'</a>
    ';
	
    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/edit/region_section/'.$region['region_id'].'/?', $config['post'], $page, $count);	 

// Выводим ошибки
	
    } else { $system->show("Городов нет"); }   
    } else { $system->show("Выбранный вами регион не существует"); } 		
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>