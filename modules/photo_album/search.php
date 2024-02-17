<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Подключаем графическое ядро
	
    $photo = new photo();

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
	
// Подчёт количества фотографий

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `description` like '%".$search."%';");	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '<div class="hide">Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' фото</div>';

// Выводим фотографии

    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `description` like '%".$search."%' ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим файл

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch_white" href="/modules/photo_album/photo/'.$act['id'].'">
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>
    '.($act['access'] == 0 ? '
    <table><tr><td>
    '.$photo->micro($act['id'], 64, 64, $act['key'], $act['type']).'
    </td><td valign=top><span style="padding-left: 10px;">
    '.$act['name'].'
    </span></td></tr></table>	
    ' : '
    <img class="middle" src="/icons/photo.png">
    '.$act['name'].'
    ').'
    </a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/photo_album/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "".(empty($user) ? '/' : '/modules/photo_album/'.$user['id'].'').""); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>