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

    $title = 'Подарок';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим подарок в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `gifts_user` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();
	
// Только если данный подарок существует
	
    if (!empty($act)) {

    echo '
    <div class="act">
    <a class="act_active" href="/modules/gifts/user/'.$act['profile'].'">Подарки</a>
    <a class="act_noactive" href="/id'.$act['profile'].'">'.$profile->login($act['profile']).'</a>
    </div>
    <div class="hide">
    Подарки '.$profile->login($act['profile']).'
    </div>';
	
    if ($act['access'] == 2) {
    $login = 'Неизвестный '.($act['user'] == $user['id'] ? '(Вы)' : '').'';
    $section = 'Анонимный';
    $message = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$text->check($act['message']).'' : 'Скрыто').'';
    } else if ($act['access'] == 1) {
    $login = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$profile->user($act['user']).'' : 'Неизвестный').'';
    $section = 'Личный';
    $message = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$text->check($act['message']).'' : 'Скрыто').'';
    } else {
    $login = ''.$profile->user($act['user']).'';
    $section = 'Публичный';
    $message = ''.$text->check($act['message']).'';
    }	
	
// Выводим форму

    echo '
    <div class="block">
    <img src="http://'.SERVER_DOMAIN.'/gifts/128/'.$act['gift'].'.png"/>	<br />
    Подарил: '.$login.' <br />
    Дата: '.$system->system_time($act['time']).' <br />
    Тип подарка: '.$section.' <br />
    Сообщение: '.$message.'
    '.($user['access'] > 0 && $user['access'] < 5 ? '<hr>
    '.$act['ip'].' :: '.$act['ua'].'' : '').'
    </div>
    '.($act['profile'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4 ? '
    <div class="hide">
    '.($act['profile'] == $user['id'] && $act['access'] == 0 || $act['access'] == 1 ? '
    <img class="middle" src="/icons/gifts.png"> <a href="/modules/gifts/?add='.$act['user'].'"> Подарить подарок </a> <br />
    ' : '').'
    <img class="middle" src="/icons/delete.png"> <a href="/modules/gifts/user/delete_gift/'.$act['id'].'"> Удалить подарок </a>	 
    </div>
    ' : '').'
    ';		
	
// Выводим ошибки

    } else { $system->show("Выбранный вами подарок не существует"); } 		

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>