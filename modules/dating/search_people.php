<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Выводим шапку

    $title = 'Поиск';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
    echo '
    <div class="hide">
    Знакомства на '.DOMAIN.'
    </div>
    ';	

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

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE (`first_name` like '%".@$cac[0]."%' AND `last_name` like '%".@$cac[1]."%') OR (`first_name` like '%".@$cac[1]."%' AND `last_name` like '%".@$cac[0]."%');"); 

// Выводим результаты в блок

    echo '<div class="hide">Результатов поиска <span style="font-weight: bold;">'.$search.'</span> найдено '.$count.' пользователей</div>';	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим пользователей

$q = DB :: $dbh -> query("SELECT * FROM `user` WHERE (`first_name` like '%".@$cac[0]."%' AND `last_name` like '%".@$cac[1]."%') OR (`first_name` like '%".@$cac[1]."%' AND `last_name` like '%".@$cac[0]."%') ORDER BY `aut` DESC LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим пользователя

    while ($act = $q -> fetch()) {

// Выводим данные пользователя

    $data = $profile->data($act['id']);	
	
    echo '
    <div class="block">
			'.($act['id'] == $user['id'] ? '
	' : '
    <span class="right"><a href="/modules/mail/contact/'.$act['id'].'">Написать сообщение</a> <br /> </span>
     ').'
    <table><tr><td>
    '.$avatar->micro($act['id'], 64,64).'
    </td><td valign=top style="padding-left: 10px;">
    '.$profile->user($act['id']).'
    </span>'.($data['city'] != NULL && $data['region'] != NULL && $data['country'] != NULL ? '
    <br />'.$data['city'].'. '.$data['region'].' '.$data['country'].'' : '').'
    </td></tr></table>
    </div>
    ';

    }
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/people/search/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	
    } else { $system->show("По запросу <span style='font-weight: bold;'>".$search."</span> ничего не найдено"); }  
    } else { $system->show("Слишком длинный или короткий поисковый запрос"); } 	
    } else { $system->redirect("Отказано в доступе", "/modules/dating/people"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>