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

    $title = 'Новости';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим новость в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `news` WHERE `id`=? LIMIT 1;", array($id));
    $news = $queryguest -> fetch();

// Только если данная новость существует
	
    if (!empty($news)) {

// Подчёт рейтинга

    $rating_plus = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_rating` WHERE `news`=? AND `section`=?;", array($news['id'], 1));
    $rating_minus = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_rating` WHERE `news`=? AND `section`=?;", array($news['id'], 0));

// Подчёт количества добавлений в закладки
	
    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(2, $news['id']));	

// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_view` WHERE `user`=? AND `news`=?  LIMIT 1;", array($user['id'], $news['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `news_view` (`user`, `time`, `news`) VALUES (?, ?, ?);", array($user['id'], time(), $news['id']));
    DB :: $dbh -> query("UPDATE `news` SET `view`=`view`+1 WHERE `id`=?", array($news['id']));
    } else {
    DB :: $dbh -> query("UPDATE `news_view` SET `time`=? WHERE `user`=? AND `news`=? LIMIT 1;", array(time(), $user['id'], $news['id']));
    }}		

// Выводим блок

    echo '
    <div class="block">
    <img src="/icons/news.png"> 
    <span style="float: right;">
    '.$system->system_time($news['time']).'
    '.($news['closed'] > 0 ? '<img class="middle" src="/icons/access_me.png">' : '').'	
    </span>	
    <span style="font-weight: bold;">'.$news['name'].'</span> 
    <br />
    '.$text->check($news['description']).'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$news['ip'].' :: '.$news['ua'].'' : '').'	
    </div>
    <div class="hide">
    <img class="middle" src="/icons/creator.png"> Автор: '.$profile->login($news['user']).'<br />
    <img class="middle" src="/icons/view.png"> Просмотров: [<a href="/modules/news/views/'.$news['id'].'">'.$news['view'].'</a>]<br />
    <img class="middle" src="/icons/rating.png"> Рейтинг: [<span style="color:#209143">+</span><a href="/modules/news/rating/'.$news['id'].'">'.$rating_plus.'</a>/<span style="color:#f30000">-</span><a href="/modules/news/rating/'.$news['id'].'">'.$rating_minus.'</a>]<br />
    <img class="middle" src="/icons/likes.png"> [<a href="/modules/news/rating/plus/'.$news['id'].'"><span style="color:#209143">За</span></a>/<a href="/modules/news/rating/minus/'.$news['id'].'"><span style="color:#f30000">Против</span></a>]<br />
    <img class="middle" src="/icons/bookmarks.png"> <a href="/modules/news/add_bookmarks/'.$news['id'].'">Добавить в закладки </a> ['.$bookmarks.']
    </div>
    '.($user['access'] > 0 && $user['access'] < 3 ? '<div class="hide">' : '').'	
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    [<a href="/modules/news/edit_news/'.$news['id'].'" class="link">Редактировать</a>]
    [<a href="/modules/news/delete_news/'.$news['id'].'" class="link">Удалить</a>]	
    ' : '').'		
    '.($news['closed'] < 1 && $user['access'] > 0 && $user['access'] < 3 ? '[<a href="/modules/news/closed_news/'.$news['id'].'" class="link">Закрыть</a>]' : '').'
    '.($news['closed'] > 0 && $user['access'] > 0 && $user['access'] < 3 ? '[<a href="/modules/news/closed_news/'.$news['id'].'" class="link">Открыть</a>]' : '').'	
    '.($user['access'] > 0 && $user['access'] < 3 ? '</div>' : '').'	
    ';
	
// Выводим комментарии
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_comments` WHERE `news`=?;", array($news['id']));	

    echo '
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим комментарии	
	
    $q = DB :: $dbh -> query("SELECT * FROM `news_comments` WHERE `news`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($news['id']));	
	
// Выводим коменнатрий

    while ($act = $q -> fetch()) {			
	
// Проверяем права на сообщение	

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }

// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' ('.$system->system_time($act['time']).') '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/news/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span> 
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/news/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/news/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/news/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5 || $user['access'] == 3 || $user['access'] == 4  ? '[<a href="/modules/news/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/news/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/news/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';
		

    }    	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/news/'.$news['id'].'/?', $config['post'], $page, $count);	 

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }   
	
// Выводим форму

    echo $news['closed'] == 0 ? '
    '.$system->form('/modules/news/add_comment/'.$news['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment').'
    ' : '
    <div class="block">
    Новость закрыта '.$profile->login($news['closed']).'
    </div>'; 

// Выводим ошибки

    } else { $system->show("Выбранная вами новость не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>