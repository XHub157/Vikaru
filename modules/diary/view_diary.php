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
	    $avatar = new avatar();

// Выводим шапку

    $title = 'Дневник';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим дневник в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `id`=? LIMIT 1;", array($id));
    $diary = $queryguest -> fetch();
	
// Только если данный дневник существует
	
    if (!empty($diary)) {	
	
// Проверяем являеться ли пользователь другом	
	    if (isset($user)) {
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($diary['user'], $user['id']));	
	}
	
// Проверяем права доступа

    if ($diary['access'] == 0 || $diary['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 || $diary['access'] == 1 && !empty($friends)) {

// Подчёт количества репостов
	
    $shareds = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `section`=? AND `share`=?;", array(0, $diary['id']));

// Подсчёт количества добавлений в закладки
	
    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(3, $diary['id']));	

// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_view` WHERE `user`=? AND `diary`=?  LIMIT 1;", array($user['id'], $diary['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `diary_view` (`user`, `time`, `diary`) VALUES (?, ?, ?);", array($user['id'], time(), $diary['id']));
    DB :: $dbh -> query("UPDATE `diary` SET `view`=`view`+1 WHERE `id`=?", array($diary['id']));
    } else {
    DB :: $dbh -> query("UPDATE `diary_view` SET `time`=? WHERE `user`=? AND `diary`=? LIMIT 1;", array(time(), $user['id'], $diary['id']));
    }}
	
// Обработка функции Share

    if ($diary['share'] > 0) {
    $section = ($diary['section'] == 0) ? 'diary' : 'forum_topic';
    $share = DB :: $dbh -> queryFetch("SELECT `id`, `user`, `name`, `description`, `time`, `edit_time` FROM `".$section."` WHERE `id`=? LIMIT 1;", array($diary['share']));
    }		

// Выводим дневник

    echo '
    <div class="block">
	'.$avatar->left_font0($act['user'], 40,40).'
    '.$profile->user($diary['user']).' :: 
    <span style="float: right;">
    '.$system->system_time($diary['time']).'
    '.($diary['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    '.($diary['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($diary['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($diary['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'      	
    </span>
    <a href="/modules/diary/'.$diary['id'].'">
    <span style="font-weight: bold;">'.$diary['name'].'</span></a>
    <br />
    '.$text->check($diary['description']).' <br />
    '.($diary['share'] > 0 ? '
    <div class="quote">
    '.$profile->user($share['user']).' :: 
    <span style="float: right;">
    '.$system->system_time($share['time']).' 
    </span> 
    <a href="/modules/'.($diary['section'] == 0 ? 'diary/' : 'forum/topic/').''.$share['id'].'">
    <span style="font-weight: bold;">'.$share['name'].'</span></a>
    <br />
    '.$text->check($share['description']).' <br />
    '.($diary['share_edit'] > 0 ? '<hr>
    Внимание! Объект был изменён после добавления.<br />
    Последний раз редактировался '.$system->system_time($share['edit_time']).'
    ' : '').'    
    </div>
    ' : '').'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$diary['ip'].' :: '.$diary['ua'].'' : '').'
    </div>
    <div class="hide">
    <img class="middle" src="/icons/share.png"> <a href="/modules/diary/share_diary/'.$diary['id'].'">Поделиться</a>: [<a href="/modules/diary/share/'.$diary['id'].'">'.$shareds.'</a>]<br />
    <img class="middle" src="/icons/likes.png"> <a href="/modules/diary/like_diary/'.$diary['id'].'">Мне нравится</a>: [<a href="/modules/diary/likes/'.$diary['id'].'">'.$diary['like'].'</a>]<br />
    <img class="middle" src="/icons/view.png"> Просмотров: [<a href="/modules/diary/views/'.$diary['id'].'">'.$diary['view'].'</a>]<br />
    <img class="middle" src="/icons/bookmarks.png"> <a href="/modules/diary/add_bookmarks/'.$diary['id'].'">Добавить в закладки </a> ['.$bookmarks.']
    </div>
    '.($diary['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3 ? '
    <div class="hide">
    [<a href="/modules/diary/edit_diary/'.$diary['id'].'">Редактировать</a>]
    [<a href="/modules/diary/delete_diary/'.$diary['id'].'">Удалить</a>]
    </div>
    ' : '').'
    ';
	
// Выводим комментарии
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_comments` WHERE `diary`=?;", array($diary['id']));	

    echo '
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

    $q = DB :: $dbh -> query("SELECT * FROM `diary_comments` WHERE `diary`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($diary['id']));	
	
// Выводим коменнатрий

    while ($act = $q -> fetch()) {	
	
// Проверяем права на сообщение	

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    
   
    } else if ($act['hide'] > 0 && $diary['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }

// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="qhide">').'
	'.$avatar->left_font0($act['user'], 40,40).'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/diary/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span>
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/diary/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/diary/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/diary/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $diary['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/diary/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/diary/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/diary/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';	

    }    	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/diary/'.$diary['id'].'/?', $config['post'], $page, $count);	 

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }   
	
// Проверяем права на форму

    if ($diary['comment'] > 0 && $user['access'] > 0 && $user['access'] < 3) {
    $form = $system->form('/modules/diary/add_comment/'.$diary['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($diary['comment'] >= 0 && $diary['user'] == $user['id']) {
    $form = $system->form('/modules/diary/add_comment/'.$diary['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($diary['comment'] == 1 && !empty($friends)) {
    $form = $system->form('/modules/diary/add_comment/'.$diary['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($diary['comment'] == 2 && $diary['user'] != $user['id'] || $diary['comment'] == 1 && empty($friends)) {
    $form = '<div class="block">Автор ограничил комментирование этой записи</div>';
    } else {
    $form = $system->form('/modules/diary/add_comment/'.$diary['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } echo $form;

// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранный вами дневник не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	