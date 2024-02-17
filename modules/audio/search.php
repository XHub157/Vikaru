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

// Выводим блок

    echo '
    <div class="hide">
    Все файлы в этом разделе являются собственностью <br />
    пользователей '.DOMAIN.'. Администрация сайта не несет <br />
    ответственности за содержимое файлов.
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
	
// Подсчёт количества музыкальных файлов

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `name` like '%".$search."%' AND `section`=?;", array('audio'));	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим результаты в блок

    echo '<div class="hide">Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' композиций</div>';

// Выводим музыкальные файлы

    $q = DB :: $dbh -> query("SELECT * FROM `files` WHERE `name` like '%".$search."%' AND `section`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array('audio'));

// Выводим музыкальный файл

    while ($act = $q -> fetch()) {

    echo '
    <a class="touch_white" href="/modules/files/file/'.$act['id'].'">
    <img class="middle" src="/icons/type/audio.png">
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </span>	
    '.$act['name'].'.'.$act['type'].'
    </a>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/audio/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/audio/"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>