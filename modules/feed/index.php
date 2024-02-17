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

    $title = 'Лента';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
    echo '
    <div class="hide">
    С помощью ленты вы можете отслеживать всю активность ваших друзей на '.DOMAIN.'
    </div>
    <div class="listbar" style="margin-top: -10px;">
    <a href="/modules/feed/0/?page='.$page.'"'.(empty($id) || $id == 0 || $id > 6 ? 'class="listbar-act"' : '').'>Все</a> 
    <a href="/modules/feed/1/?page='.$page.'"'.($id == 1 ? 'class="listbar-act"' : '').'>Комментарии</a> 
    <a href="/modules/feed/2/?page='.$page.'"'.($id == 2 ? 'class="listbar-act"' : '').'>Новости</a> 
    <a href="/modules/feed/3/?page='.$page.'"'.($id == 3 ? 'class="listbar-act"' : '').'>Форум</a> 
    <a href="/modules/feed/4/?page='.$page.'"'.($id == 4 ? 'class="listbar-act"' : '').'>Дневники</a> 
    <a href="/modules/feed/5/?page='.$page.'"'.($id == 5 ? 'class="listbar-act"' : '').'>Фото</a> 
    <a href="/modules/feed/6/?page='.$page.'"'.($id == 6 ? 'class="listbar-act"' : '').'>Файлы</a>	
    </div>	
    ';
	
// Обработка сортировки
    
    $sorting = ($id >= 0 && $id < 7) ? "AND `section`".($id == 0 ? '>=0' : '='.$id.'')."" : "AND `section`>'0'";
	
// Обновляем время входа

    DB :: $dbh -> query("UPDATE `user` SET `feed`=? WHERE `id`=?;", array(time(), $user['id']));	

// Подсчёт количества ленты	

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user`, `feed` WHERE `feed_user`.`user`='".$user['id']."' AND `feed`.`user`=`feed_user`.`profile` ".$sorting.";");

// Выводим ленту
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим ленту
	
    $q = DB :: $dbh -> query("SELECT `feed_user`.*, `feed`.* FROM `feed_user`, `feed` WHERE `feed_user`.`user`='".$user['id']."' AND `feed`.`user`=`feed_user`.`profile` ".$sorting." ORDER BY `feed`.`time` DESC LIMIT " . $page . ", " . $config['post'] . ";");

// Выводим ленту

    while ($act = $q -> fetch()) {
	
    if ($act['section'] == 1) {
    $section = 'Новый комментарий';
    } else if ($act['section'] == 2) {
    $section = 'Новая новость';
    } else if ($act['section'] == 3) {
    $section = 'Новая тема';
    } else if ($act['section'] == 4) {
    $section = 'Новй дневник';
    } else if ($act['section'] == 5) {
    $section = 'Новое фото';
    } else if ($act['section'] == 6) {
    $section = 'Новый файл';
    }
	
    echo '
    <a class="touch" href="'.$act['url'].'">
    '.($act['time'] > $user['feed'] ? '<span style="color: #FF0000;">'.$act['message'].'</span>' : ''.$act['message'].'').'
    <br />
    <span class="color">
    '.$section.' / '.$profile->us($act['user']).' /
    '.$system->system_time($act['time']).'
    </span>
    </a>';

    }	
    
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/feed/'.$id.'/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Уведомлений нет"); } 

// Выводим меню

    echo '
    <div class="hide">
    <a href="/modules/feed/my">
    <img class="middle" src="/icons/feed_user.png">
    Мои подписки
    </a>    
    </div>
    ';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>