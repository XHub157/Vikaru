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
	
// Подключаем файловое ядро

    $files = new files();

// Выводим шапку

    $title = 'Файл';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $file = $queryguest -> fetch();
	
// Только если данный файл существует
	
    if (!empty($file)) {

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($file['user'], $user['id']));
	
// Выводим информацию о папке

    $dir = DB :: $dbh -> queryFetch("SELECT `name` FROM `files_dir` WHERE `id`=? LIMIT 1;", array($file['dir']));	

// Выводим папку Зоны обмена

    $shared_zone = DB :: $dbh -> queryFetch("SELECT `name` FROM `shared_zone` WHERE `id`=? LIMIT 1;", array($file['shared_zone']));	

// Проверяем права доступа

    if ($file['access'] == 0 || $file['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 || $file['access'] == 1 && !empty($friends)) {

// Функция скачивания файла	
	
    if (isset($_GET['download'])) {
    if (isset($user)) {
    $download_files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_download` WHERE `user`=? AND `file`=?  LIMIT 1;", array($user['id'], $file['id']));
    if (empty($download_files)) {
    DB :: $dbh -> query("INSERT INTO `files_download` (`user`, `time`, `file`) VALUES (?, ?, ?);", array($user['id'], time(), $file['id']));
    DB :: $dbh -> query("UPDATE `files` SET `download`=`download`+1 WHERE `id`=?", array($file['id']));
    }}
    $files->download(SERVER.'/'.$file['section'].'/file'.$file['id'].'_'.$file['key'].'.'.$file['type'].'', $file['id'], $file['key'], $file['type']);
    exit();
    }

// Подсчёт количества добавлений в закладки
	
    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(6, $file['id']));	

// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_view` WHERE `user`=? AND `file`=?  LIMIT 1;", array($user['id'], $file['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `files_view` (`user`, `time`, `file`) VALUES (?, ?, ?);", array($user['id'], time(), $file['id']));
    DB :: $dbh -> query("UPDATE `files` SET `view`=`view`+1 WHERE `id`=?", array($file['id']));
    } else {
    DB :: $dbh -> query("UPDATE `files_view` SET `time`=? WHERE `user`=? AND `file`=? LIMIT 1;", array(time(), $user['id'], $file['id']));
    }}	
	
// Выводим навигацию по файлам

    $next = DB :: $dbh -> query("SELECT * FROM `files` WHERE `dir`=? AND `id`<? AND `user`=? ORDER BY `id` DESC LIMIT 1;", array($file['dir'], $file['id'], $file['user']))-> fetch();
    $down = DB :: $dbh -> query("SELECT * FROM `files` WHERE `dir`=? AND `id`>? AND `user`=? ORDER BY `id` ASC LIMIT 1;", array($file['dir'], $file['id'], $file['user']))-> fetch();
    $all = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `dir`=? AND `user`=?;", array($file['dir'], $file['user']));
    $number = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `dir`=? AND `id`<? AND `user`=?;", array($file['dir'], $file['id'], $file['user']));
    $count_files = $all - $number;
    $down_files = ($down['id']) ? '&larr; <a href="/modules/files/file/'.$down['id'].'/">Предыдущий</a>' : '';
    $next_files = ($next['id']) ? '<a href="/modules/files/file/'.$next['id'].'/">Следующий</a> &rarr;' : '';
    $info_files = ($down['id'] && $next['id']) ? '( '.$count_files.' из  '.$all.' )' : '';	

// Выводим Файл	

    echo '	
    <div class="block">
    '.$files->type($file['type']).' 
    <span style="float: right;">
    '.$system->system_time($file['time']).'
    '.($file['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    '.($file['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($file['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($file['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'   
    </span> 	
    <span style="font-weight: bold;">'.$file['name'].'</span>.'.$file['type'].' 
    </div>
    '.$files->view($file['id'], $file['name'], $file['key'], $file['type']).'
    '.($file['description'] == NULL ? '' : '<div class="block">'.$text->check($file['description']).'</div>').'	
    '.($file['compatibility'] == NULL ? '' : '<div class="block"><span style="font-weight: bold;">Совместимость</span>: '.$text->check($file['compatibility']).'</div>').'	
    '.($down['id'] || $next['id'] ? '
    <div class="function" style="text-align: center;">
    '.$down_files.' '.$info_files.' '.$next_files.'
    </div>
    ' : '').'
    <div class="block">
    Файл добавлен: '.$profile->user($file['user']).' ('.$system->system_time($file['time']).') <br />
    В папку <img class="middle" src="/icons/files.png"> '.($file['dir'] == 0 ? '<a href="/modules/files/'.$file['user'].'">Файлы </a>' : '<a href="/modules/files/dir/'.$file['dir'].'">'.$dir['name'].'</a>').' <br />
    '.($file['shared_zone'] > 0 ? '
    Зона Обмена <img class="middle" src="/icons/files.png"> 
    <a href="/modules/shared_zone/dir/'.$file['shared_zone'].'">'.$shared_zone['name'].'</a>
    '.$system->system_time($file['shared_time']).'
    ' : '').'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$file['ip'].' :: '.$file['ua'].'' : '').'	
    </div>
    <div class="hide">
    <img class="middle" src="/icons/download.png"> <a href="/modules/files/file/'.$file['id'].'/?download">Скачать</a>: ['.$file['download'].'] <br />
    <img class="middle" src="/icons/likes.png"> <a href="/modules/files/file/like_file/'.$file['id'].'">Мне нравится</a>: [<a href="/modules/files/file/likes/'.$file['id'].'">'.$file['like'].'</a>] <br />
    <img class="middle" src="/icons/view.png"> Просмотров: [<a href="/modules/files/file/views/'.$file['id'].'">'.$file['view'].'</a>]<br />
    <img class="middle" src="/icons/bookmarks.png"> <a href="/modules/files/file/add_bookmarks/'.$file['id'].'">Добавить в закладки </a> ['.$bookmarks.']
    </div>
    '.($file['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    '.($file['user'] == $user['id'] && $file['shared_zone'] == 0 ? '
    [<a href="/modules/shared_zone/?add='.$file['id'].'" class="link">Добавить в ЗО</a>]
    ' : '
    '.($file['shared_zone'] != 0 ? '
    [<a href="/modules/shared_zone/delete_file/'.$file['id'].'" class="link">Удалить из ЗО</a>]
    ' : '').'').'	
    '.($file['section'] == 'audio' || $file['section'] == 'other' && $file['user'] == $user['id'] ? '
    [<a href="/modules/files/file/screen/'.$file['id'].'" class="link">Скриншот</a>]
    ' : '').'
    [<a href="/modules/files/file/edit_file/'.$file['id'].'" class="link">Редактировать</a>]
    [<a href="/modules/files/file/delete_file/'.$file['id'].'" class="link">Удалить</a>]
    </div>
    ' : '').'';
	
// Выводим комментарии
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_comments` WHERE `file`=?;", array($file['id']));

    echo '
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `files_comments` WHERE `file`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($file['id']));

// Выводим коменнатрий

    while ($act = $q -> fetch()) {	

// Проверяем права на сообщение	

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    
   
    } else if ($act['hide'] > 0 && $file['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }
	
// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/files/file/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span>
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/files/file/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/files/file/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/files/file/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $file['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/files/file/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/files/file/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/files/file/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';
		

    }   	

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/files/file/'.$file['id'].'/?', $config['post'], $page, $count);

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }   

// Проверяем права на форму


    echo $system->form('/modules/files/file/add_comment/'.$file['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранный вами файл не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	
	