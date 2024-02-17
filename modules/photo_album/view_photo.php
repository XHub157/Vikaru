<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $text = new text();	
	
// Подключаем графическое ядро
	
    $class_photo = new photo();	

// Выводим шапку

    $title = 'Фото';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $photo = $queryguest -> fetch();
	
// Только если данное фото существует
	
    if (!empty($photo)) {
	
// Выводим информацию о альбоме

    $album = DB :: $dbh -> queryFetch("SELECT `name` FROM `photo_album` WHERE `id`=? LIMIT 1;", array($photo['album']));	

// Проверяем являеться ли пользователь другом	
if (isset($user)) {	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($photo['user'], $user['id']));
}

// Проверяем права доступа

    if ($photo['access'] == 0 || $photo['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $photo['access'] == 1 && !empty($friends)) {	
	
// Функция скачивания файла	
	
    if (isset($_GET['download'])) {
    if (isset($user)) {
    $download_photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_download` WHERE `user`=? AND `photo`=?  LIMIT 1;", array($user['id'], $photo['id']));
    if (empty($download_photo)) {
    DB :: $dbh -> query("INSERT INTO `photo_download` (`user`, `time`, `photo`) VALUES (?, ?, ?);", array($user['id'], time(), $photo['id']));
    DB :: $dbh -> query("UPDATE `photo` SET `download`=`download`+1 WHERE `id`=?", array($photo['id']));
    }}
    header ("Location: http://".SERVER_DOMAIN."/photo/photo".$photo['id']."_".$photo['key'].".".$photo['type']."");  
    exit();
    }	

// Подсчёт количества добавлений в закладки
	
    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(5, $photo['id']));	
	
// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_view` WHERE `user`=? AND `photo`=?  LIMIT 1;", array($user['id'], $photo['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `photo_view` (`user`, `time`, `photo`) VALUES (?, ?, ?);", array($user['id'], time(), $photo['id']));
    DB :: $dbh -> query("UPDATE `photo` SET `view`=`view`+1 WHERE `id`=?", array($photo['id']));
    } else {
    DB :: $dbh -> query("UPDATE `photo_view` SET `time`=? WHERE `user`=? AND `photo`=? LIMIT 1;", array(time(), $user['id'], $photo['id']));
    }}

// Выводим навигацию по фото

    $next = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `album`=? AND `id`<? AND `user`=? ORDER BY `id` DESC LIMIT 1;", array($photo['album'], $photo['id'], $photo['user']))-> fetch();
    $down = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `album`=? AND `id`>? AND `user`=? ORDER BY `id` ASC LIMIT 1;", array($photo['album'], $photo['id'], $photo['user']))-> fetch();
    $all = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `album`=? AND `user`=?;", array($photo['album'], $photo['user']));
    $number = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `album`=? AND `id`<? AND `user`=?;", array($photo['album'], $photo['id'], $photo['user']));
    $count_photo = $all - $number;
    $down_photo = ($down['id']) ? '&larr; <a href="/modules/photo_album/photo/'.$down['id'].'/">Предыдущая</a>' : '';
    $next_photo = ($next['id']) ? '<a href="/modules/photo_album/photo/'.$next['id'].'/">Следующая</a> &rarr;' : '';
    $info_photo = ($down['id'] && $next['id']) ? '( '.$count_photo.' из  '.$all.' )' : '';		
	
// Выводим фото

    echo '	
    <div class="block">
    <img class="middle" src="/icons/photo.png"> 
    <span style="float: right;">
    '.$system->system_time($photo['time']).'
    '.($photo['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    '.($photo['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($photo['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($photo['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'   
    </span> 	
    <span style="font-weight: bold;">'.$photo['name'].'</span> 
    </div>
    <div class="block" style="text-align: center;">
    '.$class_photo->small($photo['id'], 0, 0, $photo['key'], $photo['type']).' 
    </div>
    '.($photo['description'] == NULL ? '' : '<div class="block">'.$text->check($photo['description']).'</div>').'	
    '.($down['id'] || $next['id'] ? '
    <div class="function" style="text-align: center;">
    '.$down_photo.' '.$info_photo.' '.$next_photo.'
    </div>
    ' : '').'
    <div class="block">
    Фото добавлено: '.$profile->user($photo['user']).' '.$system->system_time($photo['time']).' <br />
    В фотоальбом <img class="middle" src="/icons/files.png"> '.($photo['album'] == 0 ? '<a href="/modules/photo_album/'.$photo['user'].'">Фото</a>
    ' : '<a href="/modules/photo_album/album/'.$photo['album'].'">'.$album['name'].'</a>').' 
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$photo['ip'].' :: '.$photo['ua'].'' : '').'		
    </div>

	<div class="hide">
    <img class="middle" src="/icons/download.png"> <a href="/modules/photo_album/photo/'.$photo['id'].'/?download">Скачать</a>: ['.$photo['download'].'] <br />
    '.($photo['user'] == $user['id'] ? '<img class="middle" src="/icons/photo.png"> <a href="/modules/photo_album/photo/avatar/'.$photo['id'].'">Установить на страницу</a><br />' : '').'
    <img class="middle" src="/icons/likes.png"> <a href="/modules/photo_album/photo/like_photo/'.$photo['id'].'">Мне нравится</a>: 
    [<a href="/modules/photo_album/photo/likes/'.$photo['id'].'">'.$photo['like'].'</a>] <br />
    <img class="middle" src="/icons/view.png"> Просмотров: [<a href="/modules/photo_album/photo/views/'.$photo['id'].'">'.$photo['view'].'</a>]<br />
    <img class="middle" src="/icons/bookmarks.png"> <a href="/modules/photo_album/photo/add_bookmarks/'.$photo['id'].'">Добавить в закладки </a> ['.$bookmarks.']
    </div>
    '.($photo['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    [<a href="/modules/photo_album/photo/edit_photo/'.$photo['id'].'" class="link">Редактировать</a>]
    [<a href="/modules/photo_album/photo/delete_photo/'.$photo['id'].'" class="link">Удалить</a>]
    </div>
    ' : '').'
    ';
	
// Выводим комментарии
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_comments` WHERE `photo`=?;", array($photo['id']));	

    echo '
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `photo_comments` WHERE `photo`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($photo['id']));

// Выводим коменнатрий

    while ($act = $q -> fetch()) {	

// Проверяем права на сообщение	

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    
   
    } else if ($act['hide'] > 0 && $photo['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }
	
// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/photo_album/photo/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span> 
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/photo_album/photo/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/photo_album/photo/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/photo_album/photo/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $photo['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/photo_album/photo/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/photo_album/photo/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/photo_album/photo/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';
		
    }   	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/photo_album/photo/'.$photo['id'].'/?', $config['post'], $page, $count);

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }   

// Проверяем права на форму


    echo $system->form('/modules/photo_album/photo/add_comment/'.$photo['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');

// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранное вами фото не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		