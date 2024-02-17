<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Только для зарегистрированых

    $profile->access(true);	

// Выводим шапку

    $title = 'Журнал';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим блок	
	
    echo '
    <div class="hide">
    В журнале будут отображаться все ответы на ваши комментарии или записи.
    </div>
		<div class="listbar" style="margin-top: -10px;">

    <a href="/modules/journal/0/?page='.$page.'"'.(empty($id) || $id == 0 || $id > 6 ? 'class="listbar-act"' : '').'>Все</a> 
    <a href="/modules/journal/1/?page='.$page.'"'.($id == 1 ? 'class="listbar-act"' : '').'>Комментарии</a> 
    <a href="/modules/journal/2/?page='.$page.'"'.($id == 2 ? 'class="listbar-act"' : '').'>Новости</a> 
    <a href="/modules/journal/3/?page='.$page.'"'.($id == 3 ? 'class="listbar-act"' : '').'>Форум</a> 
    <a href="/modules/journal/4/?page='.$page.'"'.($id == 4 ? 'class="listbar-act"' : '').'>Дневники</a> 
    <a href="/modules/journal/5/?page='.$page.'"'.($id == 5 ? 'class="listbar-act"' : '').'>Фото</a> 
    <a href="/modules/journal/6/?page='.$page.'"'.($id == 6 ? 'class="listbar-act"' : '').'>Файлы</a>	
    </div>	
    ';
	
// Обработка сортировки
    
    $sorting = ($id >= 0 && $id < 7) ? "AND `section`".($id == 0 ? '>=0' : '='.$id.'')."" : "AND `section`>'0'";	

// Подсчёт количества оповещений	
	
    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `journal` WHERE `profile`=? ".$sorting.";", array($user['id']));

// Выводим оповещения	
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим оповещения
	
    $q = DB :: $dbh -> query("SELECT * FROM `journal` WHERE `profile`=? ".$sorting." ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($user['id']));

// Выводим оповещение

    while ($act = $q -> fetch()) {

// Обновляем статус

    if ($act['read'] == 1) DB :: $dbh -> query("UPDATE `journal` SET `read`=? WHERE `id`=?; LIMIT 1", array(0, $act['id']));
	
    echo '
    <a class="touch" href="'.$act['url'].'">
    '.($act['read'] == 1 ? '<span style="color: #FF0000;">'.$act['message'].'...</span>' : ''.$act['message'].'...').'
    <br />
    <span class="color">
    '.$profile->us($act['profile']).' / '.$profile->us($act['user']).' /
    '.$system->system_time($act['time']).'
    </span>
    </a>';

    }	
    
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/journal/'.$id.'/?', $config['post'], $page, $count);	    

// Выводим сообщение если оповещений нет	
	
    } else { $system->show("Уведомлений нет"); } 
	
// Выводим меню

    echo '
	<a href="/modules/journal/clean" class="info-block-link">
			       <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Очистить</div>
			       
			    </a><a href="/modules/settings/journal" class="info-block-link">
			       <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Отключить уведомления </div> </a></div></div></div></div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>