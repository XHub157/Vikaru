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

    $title = 'Знакомства';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Выводим блок	

echo "

<div class='info-block bottom_link_block tools_block show-all r_field-list ' style='margin-bottom: 0px;'>
<form method='post' action='/modules/search/'>
<table style='width: 100%;' cellspacing='0' cellpadding='0'>
<tr>
<td style='width: 100%;' class='m'>
<div style='padding:0 20px 0 10px;'>
<input type='text' class='font_medium m' placeholder='Поиск..' style='width: 100%; margin: 0 0 0 -10px;' id='search_text' name='search' size='15' value=''>
</td>

<td class='m'>
<input type='submit' style='line-height: 19px;  margin-top: 0;' class='main_submit' value='Найти'>
</td>
</tr>  
</table>
</form>
</div>
";
	
    echo '
	<div class="listbar">
    <a href="/modules/dating/" class="">Все обитатели</a>
    <a href="/modules/dating/online" class="listbar-act">Сейчас в сети</a>
    <a href="/modules/dating/women" class="">Женщины</a>
	<a href="/modules/dating/male" class="">Мужчины</a>
	<a href="/modules/dating/search" class="" style="color: darkred;">Поиск</a>
    </div>
    ';	


// Подсчёт пользователей онлайн
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `date_aut`>? AND `hide`<?;", array(time()-60, time()));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;	
	
// Выводим пользователей онлайн
	
    $q = DB :: $dbh -> query("SELECT * FROM `user` WHERE `date_aut`>? AND `hide`<? ORDER BY `date_aut` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array(time()-60, time()));	
	
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
    <br /><br /><span class="count_zxc">'.$data['city'].'</span> <span class="count_zxc">'.$data['region'].'</span> <span class="count_zxc">'.$data['country'].'</span>' : '').'
	<div class="user__status-wrap">   <div class="user__status">'.($data['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$data['hello'].'').'</div></div>
    </td></tr></table>
    </div>
    ';

    } 
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/online/?', $config['post'], $page, $count);	
	 
// Выводим ошибки	
	
    } else { $system->show("На сайте никого нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>