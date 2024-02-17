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

    $title = 'Гостевая';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {	

// Проверяем являеться ли пользователь другом	
	
    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `user`=? AND `profile`=?;", array($data['id'], $user['id']));
	
// Подсчёт количества комментариев

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `profile`=?;", array($data['id']));	

// Выводим количество комментариев данного пользователя
	
    echo '
    <div class="act">
    <a class="act_active" href="/modules/guestbook/'.$data['id'].'">Гостевая</a>
    <a class="act_noactive" href="/id'.$data['id'].'">'.$data['first_name'].' '.$data['last_name'].'</a>
    </div>
    <div class="hide">
    Гостевая '.$profile->login($data['id']).'
    </div>
    <div class="advertising">
    Комментариев: ['.$count.']
    </div>
    ';	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим комментарии	
	
    $q = DB :: $dbh -> query("SELECT * FROM `guestbook_comments` WHERE `profile`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id']));

// Выводим коменнатрий

    while ($act = $q -> fetch()) {		
	
// Проверяем права на комментарий

    if ($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5) {

    $comment = ''.$text->check($act['comment']).'';    

    } else if ($act['hide'] > 0 && $act['answer'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).''; 

    } else if ($act['hide'] > 0 && $act['user'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';    
   
    } else if ($act['hide'] > 0 && $data['id'] == $user['id']) {

    $comment = ''.$text->check($act['comment']).'';       

    } else if ($act['hide'] > 0 && $act['user'] != $user['id']) {

    $comment = 'Сообщение скрыто '.$profile->login($act['hide']).'';  

    } else {

    $comment = ''.$text->check($act['comment']).'';   

    }

// Выводим комментарий

    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/guestbook/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span>
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '[<a href="/modules/guestbook/answer_comment/'.$act['id'].'" class="link">Ответ</a>]' : '').'
    '.($act['user'] != $user['id'] ? '[<a href="/modules/guestbook/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/guestbook/edit_comment/'.$act['id'].'" class="link">Ред</a>]' : '').'
    '.($act['user'] == $user['id'] || $data['id'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '[<a href="/modules/guestbook/delete_comment/'.$act['id'].'" class="link">Удалить</a>]' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/guestbook/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/guestbook/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';
		

    }    	
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/guestbook/'.$data['id'].'/?', $config['post'], $page, $count);	 

// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Комментариев нет"); }  

// Проверяем права на форму

    if ($data['access_guestbook'] > 0 && $user['access'] > 0 && $user['access'] < 3) {
    $form = $system->form('/modules/guestbook/add_comment/'.$data['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($data['access_guestbook'] >= 0 && $data['id'] == $user['id']) {
    $form = $system->form('/modules/guestbook/add_comment/'.$data['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($data['access_guestbook'] == 1 && !empty($friends)) {
    $form = $system->form('/modules/guestbook/add_comment/'.$data['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } else if ($data['access_guestbook'] == 2 && $data['id'] != $user['id'] || $data['access_guestbook'] == 1 && empty($friends)) {
    $form = '<div class="block">'.$profile->login($data['id']).' ограничил комментирование</div>';
    } else {
    $form = $system->form('/modules/guestbook/add_comment/'.$data['id'].'', '', 'Отправить', 'Комментарий', '10000', 'comment', '', ''.$user['sid'].'', 'comment');
    } echo $form;

    echo '
    '.($data['id'] == $user['id']  ? '
    <div class="hide">
    <img class="middle" src="/icons/settings.png"> <a href="/modules/settings/guestbook">Настройки гостевой</a> <br />
    <img class="middle" src="/icons/delete.png"> <a href="/modules/guestbook/clean">Очистить гостевую</a>
    </div>' : '').'
    ';
	
// Выводим сообщение если комментариев нет	
	
    } else { $system->show("Выбранный вами пользователь не существует"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		