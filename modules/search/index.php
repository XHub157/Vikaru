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

    }
	
    if (isset($_SESSION['search'])) {  

// Обработка поискового запроса

    $search = $system->check($_SESSION['search']);	
	
// Обработка количества символов поиска
	
    if ($system->utf_strlen($search) >= 3 && $system->utf_strlen($search) < 100) {	

// Подсчёт количества пользователей

    $cac = explode(" ", $search);

$people = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE (`first_name` like '%".@$cac[0]."%' AND `last_name` like '%".@$cac[1]."%') OR (`first_name` like '%".@$cac[1]."%' AND `last_name` like '%".@$cac[0]."%');"); 

	
// Подсчёт количества новостей

    $news = DB :: $dbh -> querySingle("SELECT count(*) FROM `news` WHERE `description` like '%".$search."%';");	

// Подсчёт количества дневников

    $diary = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `description` like '%".$search."%';");
	
// Подсчёт количества топиков в форуме

    $topic = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `description` like '%".$search."%';");	
	
// Подсчёт количества фотографий

    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `description` like '%".$search."%';");

// Подсчёт количества файлов

    $files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `description` like '%".$search."%';");	
	
// Подсчёт количества аудио файлов

    $audio = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `section`='audio' AND `description` like '%".$search."%';");	
	
// Сумарный подсчёт	

    $count = $people + $news + $diary + $topic + $photo + $files + $audio;
	
// Вывод случайной фразы

    $search_rand_1 = array("Armin van Buuren", "Алексей Шпилька", "Гонки", "Dota 2", "Иван Дорн", "Павел Дуров", "Trance", "Рок", "CS", "Admin", "PSY", "Deelfy"); 	
    $search_rand_2 = array("Android", "Вконтакте", "Админ", "Google", "Дима Билан", "Секс", "Игры", "Стив Джобс", "Apple", "Angry Birds", "NFS", "+100500");	
	
// Выводим блок

    echo '
    <div class="hide">
    <span style=" font-weight: bold; ">Поиск:</span>
    '.$search.' <br />
    <form method="post" action="/modules/search/">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>  
    <span style=" font-size: small; ">- '.$search_rand_1[rand(0,11)].', '.$search_rand_2[rand(0,11)].'</span>
    </div>
    ';
	
// Выводим данные поиска

    if ($count > 0) {	
	
    echo '
    '.($people > 0 ? '
    <a class="touch" href="/modules/dating/people/search">
    <img class="middle" src="/icons/guests.png">
    Пользователи <span class="count">'.$people.'</span>
    </a>
    ' : '
    ').' 
    '.($news > 0 ? '
    <a class="touch" href="/modules/news/search">
    <img class="middle" src="/icons/news.png">
    Новости <span class="count">'.$news.'</span>
    </a>
    ' : '
    ').'
    '.($diary > 0 ? '
    <a class="touch" href="/modules/diary/search">
    <img class="middle" src="/icons/diary.png">
    Дневники <span class="count">'.$diary.'</span>
    </a>
    ' : '
    ').'
    '.($topic > 0 ? '
    <a class="touch" href="/modules/forum/search">
    <img class="middle" src="/icons/forum.png">
    Форум <span class="count">'.$topic.'</span>
    </a>
    ' : '
    ').'
    '.($photo > 0 ? '
    <a class="touch" href="/modules/photo_album/search">
    <img class="middle" src="/icons/photo.png">
    Фото <span class="count">'.$photo.'</span>
    </a>
    ' : '
    ').'
    '.($files > 0 ? '
    <a class="touch" href="/modules/files/search">
    <img class="middle" src="/icons/files.png">
    Файлы <span class="count">'.$files.'</span>
    </a>
    ' : '
    ').'
    '.($audio > 0 ? '
    <a class="touch" href="/modules/audio/search">
    <img class="middle" src="/icons/audio.png">
    Музыка <span class="count">'.$audio.'</span>
    </a>
    ' : '
    ').'
    ';
	
// Выводим статистику

    echo '
    <div class="hide">
    По запросу <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' объектов
    </div>';

// Выводим ошибки	
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); } 
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); }	
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>