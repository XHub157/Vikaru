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

// Выводим шапку

    $title = 'Форум';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим тему в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `forum_topic` WHERE `id`=? LIMIT 1;", array($id));
    $topic = $queryguest -> fetch();
	
// Только если данная тема существует
	
    if (!empty($topic)) {	

// Подчёт количества репостов
	
    $share = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `section`=? AND `share`=?;", array(1, $topic['id']));	

// Подсчёт количества добавлений в закладки
	
    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(4, $topic['id']));

// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic_view` WHERE `user`=? AND `topic`=?  LIMIT 1;", array($user['id'], $topic['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `forum_topic_view` (`user`, `time`, `topic`) VALUES (?, ?, ?);", array($user['id'], time(), $topic['id']));
    DB :: $dbh -> query("UPDATE `forum_topic` SET `view`=`view`+1 WHERE `id`=?", array($topic['id']));
    } else {
    DB :: $dbh -> query("UPDATE `forum_topic_view` SET `time`=? WHERE `user`=? AND `topic`=? LIMIT 1;", array(time(), $user['id'], $topic['id']));
    }}

// Выводим тему

    echo '
    <div class="block">
    '.$profile->user($topic['user']).' :: 
    <span style="float: right;">
    '.$system->system_time($topic['time']).'
    '.($topic['closed'] > 0 ? '<img class="middle" src="/icons/access_me.png">' : '').'
    '.($topic['locked'] > 0 ? '<img class="middle" src="/icons/locked.png">' : '').'  
    '.($topic['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'     	
    </span>	
    <a href="/modules/forum/topic/'.$topic['id'].'">
    <span style="font-weight: bold;">'.$topic['name'].'</span></a>
    <br />
    '.$text->check($topic['description']).' 
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$topic['ip'].' :: '.$topic['ua'].'' : '').'   
    </div>
    <div class="hide">
    <img class="middle" src="/icons/view.png"> Просмотров: [<a href="/modules/forum/views/'.$topic['id'].'">'.$topic['view'].'</a>] <br />
    <img class="middle" src="/icons/share.png"> <a href="/modules/forum/share_topic/'.$topic['id'].'">Поделиться</a>: [<a href="/modules/forum/share/'.$topic['id'].'">'.$share.'</a>] <br />
    <img class="middle" src="/icons/likes.png"> <a href="/modules/forum/like_topic/'.$topic['id'].'">Мне нравится</a>: [<a href="/modules/forum/likes/'.$topic['id'].'">'.$topic['like'].'</a>] <br />
    <img class="middle" src="/icons/bookmarks.png"> <a href="/modules/forum/add_bookmarks/'.$topic['id'].'">Добавить в закладки </a> ['.$bookmarks.']
    '.($topic['locked'] > 0 ? '<br /><img class="middle" src="/icons/locked.png"> Закрепил: '.$profile->login($topic['locked']).'' : '').'	
    </div>
    '.($topic['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 4 ? '<div class="hide">' : '').'
    '.($user['access'] > 0 && $user['access'] < 4 && $topic['locked'] < 1 ? '[<a href="/modules/forum/locked_topic/'.$topic['id'].'" class="link">Закрепить</a>]' : '').'
    '.($user['access'] > 0 && $user['access'] < 4 && $topic['locked'] > 0 ? '[<a href="/modules/forum/locked_topic/'.$topic['id'].'" class="link">Открепить</a>]' : '').'	
    '.($user['access'] > 0 && $user['access'] < 4 && $topic['closed'] < 1 ? '[<a href="/modules/forum/closed_topic/'.$topic['id'].'" class="link">Закрыть</a>]' : '').'
    '.($user['access'] > 0 && $user['access'] < 4 && $topic['closed'] > 0 ? '[<a href="/modules/forum/closed_topic/'.$topic['id'].'" class="link">Открыть</a>]' : '').'
    '.($user['access'] > 0 && $user['access'] < 4 ? '[<a href="/modules/forum/?add='.$topic['id'].'" class="link">Переместить</a>]' : '').'
    '.($topic['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 4 ? '
    [<a href="/modules/forum/edit_topic/'.$topic['id'].'" class="link">Редактировать</a>]
    [<a href="/modules/forum/delete_topic/'.$topic['id'].'" class="link">Удалить</a>]
    </div>
    ' : '').'
    ';
	
// Выводим комментарии
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `topic`=?;", array($topic['id']));	

// Выводим количество комментариев данной темы	
	
    echo '
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';

    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим комментарии	
	
    $q = DB :: $dbh -> query("SELECT * FROM `forum_comments` WHERE `topic`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($topic['id']));	
	
// Выводим коменнатрий

    while ($act = $q -> fetch()) {	
	
// Проверяем права на комментарий

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    
   
    } else if ($act['hide'] > 0 && $topic['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }

// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/forum/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span>
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/forum/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/forum/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/forum/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $topic['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/forum/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/forum/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/forum/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';
		

    }    	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/forum/topic/'.$topic['id'].'/?', $config['post'], $page, $count);	 

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }   
	
// Выводим форму

    echo ''.($topic['closed'] == 0 ? '
    '.$system->form('/modules/forum/add_comment/'.$topic['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment').'
    ' : '
    <div class="block">
    Тема закрыта '.$profile->login($topic['closed']).'
    </div>
    ').'';

// Выводим ошибки

    } else { $system->show("Выбранная вами тема не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	