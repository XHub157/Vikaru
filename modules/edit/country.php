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

    $title = 'Страна';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Подсчёт количества стран
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `geo_countries`;");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим популярные страны

    echo '
    <div class="block">
    <span style=" font-weight: bold; ">
    Выберите вашу страну
    </span> <br />
    Популярные страны: 
    </div>
	
    <a class="touch_black" href="/modules/edit/country_section/9908">
    <img class="middle" src="/icons/ua.png"> Украина
    </a>
	
    <a class="touch_black" href="/modules/edit/country_section/3159">
    <img class="middle" src="/icons/ru.png"> Россия
    </a>
	
    <a class="touch_black" href="/modules/edit/country_section/248">
    <img class="middle" src="/icons/by.png"> Беларусь
    </a>
	
    <a class="touch_black" href="/modules/edit/country_section/1894">
    <img class="middle" src="/icons/kz.png"> Казахстан
    </a>
	
    <a class="touch_black" href="/modules/edit/country_section/9787">
    <img class="middle" src="/icons/uz.png"> Узбекистан
    </a>
	
    ';
	
// Выводим страны
	
    $q = DB :: $dbh -> query("SELECT * FROM `geo_countries` ORDER BY `country_name` ASC LIMIT " . $page . ", " . $config['post'] . ";");	
	
// Выводим страну

    while ($act = $q -> fetch()) {
	
    echo '
    <a class="touch" href="/modules/edit/country_section/'.$act['country_id'].'">'.$act['country_name'].'</a>
    ';
	
    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/edit/country/?', $config['post'], $page, $count);	 

// Выводим сообщение если сообщений нет	
	
    } else { $system->show("Стран нет"); }   

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>