<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем ядро аватаров
	
    $avatar = new avatar();
	
// Подключаем статистическое ядро
	
    $count_dating = new count_dating();	

// Выводим шапку

    $title = 'Знакомства';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
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
    <a href="/modules/dating/" class="listbar-act">Все обитатели</a>
    <a href="/modules/dating/online" class="">Сейчас в сети</a>
    <a href="/modules/dating/women" class="">Женщины</a>
	<a href="/modules/dating/male" class="">Мужчины</a>
	<a href="/modules/dating/search" class="" style="color: darkred;">Поиск</a>
    </div>
    ';		

// Подсчёт пользователей
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `dating`=?;", array(0));
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;	
	
// Выводим пользователей
	
    $q = DB :: $dbh -> query("SELECT * FROM `user` WHERE `dating`=? ORDER BY `rating` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array(0));	
	
// Выводим пользователя

    while ($act = $q -> fetch()) {
	
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
    </span>'.($act['city'] != NULL && $act['region'] != NULL && $act['country'] != NULL ? '
    <br /><br /><span class="count_zxc">'.$act['city'].'</span> <span class="count_zxc">'.$act['region'].'</span> <span class="count_zxc">'.$act['country'].'</span>' : '').'
	<div class="user__status-wrap">   <div class="user__status">'.($act['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$act['hello'].'').'</div></div>
     </td></tr></table>
    </div>
    ';	

    } 
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/dating/?', $config['post'], $page, $count);	
	 
// Выводим ошибки	
	
    } else { $system->show("Пользователей нет"); } 

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>