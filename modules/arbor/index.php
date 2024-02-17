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

    $title = 'Беседка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим комментарии	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor`;");
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Комментарии	
	
    $q = DB :: $dbh -> query("SELECT * FROM `arbor` ORDER BY `time` DESC LIMIT " . $page . ", " . $config['post'] . ";");	
	
// Выводим комментарий

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

// Выводим сообщение	
	
    echo ''.($act['hide'] == 0 ? '<div class="block">' : '<div class="hide">').'
    '.$profile->user($act['user']).' '.$system->system_time($act['time']).' '.($act['answer'] > 0 ? 'ответил: '.$profile->login($act['answer']).'' : '').'
    
    
    
    <span style="float: right;"><div class="wi_buttons"><a class="item_like item_sel _i" href="/modules/arbor/like_comment/'.$act['id'].'"><i class="i_like"></i><b class="v_like">'.$act['like'].'</b></a></div></span>                     
    
    <br />
    '.$comment.'<br />
    '.($act['user'] != $user['id'] ? '
    [<a href="/modules/arbor/answer_comment/'.$act['id'].'" class="link">Ответ</a>]
    [<a href="/modules/arbor/quote_comment/'.$act['id'].'" class="link">Цитировать</a>]
	' : '').'
    '.($act['user'] == $user['id'] || $user['access'] > 0 && $user['access'] < 5  ? '
    [<a href="/modules/arbor/edit_comment/'.$act['id'].'" class="link">Ред</a>]
    [<a href="/modules/arbor/delete_comment/'.$act['id'].'" class="link">Удалить</a>]
	' : '').'	
    '.($act['hide'] < 1 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/arbor/hide_comment/'.$act['id'].'" class="link">Скрыть</a>]' : '').'
    '.($act['hide'] > 0 && $user['access'] > 0 && $user['access'] < 5 ? '[<a href="/modules/arbor/hide_comment/'.$act['id'].'" class="link">Восстановить</a>]' : '').'	
    </div>';		

    }

// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/arbor/?', $config['post'], $page, $count);	 

// Выводим сообщение если сообщений нет	
	
    } else { $system->show('Комментариев нет'); }   

// Выводим форму

    echo $system->form('/modules/arbor/add_comment', '', 'Отправить', 'Комментарий', '10000', 'comment', 'Что у вас нового?', ''.$user['sid'].'', 'comment'); 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>