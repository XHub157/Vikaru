<?php

/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */
        ob_start();
    
// Подключаем текстовое ядро
	
    $avatar = new avatar();
	
// Подключаем статистическое ядро
	
    $count = new count();
    $count_dating = new count_dating();

// Генерация
 
    $start_time = microtime(true); 

// Выключаем показ ошибок

    error_reporting(E_ALL); 
    ini_set("display_errors", 0); 

    echo '
    <?xml version="1.0" encoding="utf-8"?>
    <!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
    <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="Generator" content="'.($config['generator'] == NULL ? ''.$domain.'' : ''.$config['generator'].'').'" />
    <meta name="keywords" content="'.($config['keywords'] == NULL ? ''.$domain.'' : ''.$config['keywords'].'').'" />
    <meta name="author" content="'.($config['author'] == NULL ? ''.$domain.'' : ''.$config['author'].'').'" />
    <meta name="reply-to" content="'.($config['reply-to'] == NULL ? ''.$domain.'' : ''.$config['reply-to'].'').'" />
    <meta name="copyright" content="'.($config['copyright'] == NULL ? ''.$domain.'' : ''.$config['copyright'].'').'" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <link rel="stylesheet" type="text/css" href="/template/paceworld.css">
		<link rel="stylesheet" type="text/css" href="/template/info.css">
	<link rel="stylesheet" type="text/css" href="/template/web.css">';
                        
   echo '
    <title>'.(!empty($title) == NULL ? ''.DOMAIN.'' : ''.$title.' | '.DOMAIN.'').'</title>
    </head><body>
    ';
	
// Выводим друзей онлайн

    include_once ($_SERVER['DOCUMENT_ROOT']."/js/online_frend.php"); 

	
// Только для зарегестрированых

    if (isset($user)) {
	
// Подсчёт количества оповещений в ленте

    $count_feed = DB :: $dbh -> querySingle("select count(*) from `feed_user`, `feed` where `feed_user`.`user`='".$user['id']."' and `feed`.`user`=`feed_user`.`profile` and `feed`.`time`>'".$user['feed']."';");
    $feed_i = ($count_feed > 100) ? '+100>' : ''.$count_feed.'';	
	$feed = ($count_feed > 0) ? '<span class="horizMenuLinkEvent horizMenuLinkEventShow">'.$feed_i.'</span>' : '';
	
// Подсчёт количества оповещений в почте

    $count_mail = DB :: $dbh -> querySingle("SELECT count(*) FROM `mail_message` WHERE `profile`=? AND `read`=?;", array($user['id'], 1));
    $mail_int = ($count_mail > 100) ? '+100>' : ''.$count_mail.'';
    $mail = ($count_mail > 0) ? '<span class="horizMenuLinkEvent horizMenuLinkEventShow">'.$mail_int.'</span>' : '';	

// Подсчёт количества оповещений в журнале

    $count_journal = DB :: $dbh -> querySingle("SELECT count(*) FROM `journal` WHERE `profile`=? AND `read`=?;", array($user['id'], 1));
    $journal_int = ($count_journal > 100) ? '100>' : ''.$count_journal.'';
    $journal = ($count_journal > 0) ? '<span class="horizMenuLinkEvent horizMenuLinkEventShow">'.$journal_int.'</span>' : '';	

	
// Выводим меню пользователя	
	
	echo '

	<div id="toolbar">	
        <div class="toolbar-right">
        <a href="/modules/dating/online" class="toolbar-link">Онлайн<span class="toolbar-count horizMenuLinkEventShow" style="display: inline;opacity: 1;">'.$count->online().'</span></a>
		
		<div class="toolbar-search">
      <form method="post" action="/modules/search/">
  <input type="text" class="font_medium m r_field" placeholder="Поиск..."  name="search"  value=""></input></form>


</div>

        <a href="/id'.$user['id'].'" class="toolbar-ava">'.$avatar->mini_left($user['id'], 50,50).'</a>
    </div>

    <div class="toolbar-left">
        <a href="/" class="toolbar-logo"><img src="/template/logo_r.png"></a>
        <a href="/modules/dating/" class="toolbar-link "><img src="/icons/love-10.png" class="m"> Знакомства</a>
		<a href="/modules/shared_zone/" class="toolbar-link "><img src="/icons/newspaper-10-16.png" class="m"> Зона обмена</a>
        <a href="/modules/diary/" class="toolbar-link"><img src="/icons/conference-10-16.png" class="m"> Блоги</a>
        <a href="/modules/forum/" class="toolbar-link"><img src="/icons/photo-10-16.png" class="m"> Форум</a>
        <a href="/modules/arbor/" class="toolbar-link"><img src="/icons/services-10-16.png" class="m"> Беседка</a>
        <a href="/modules/services/" class="toolbar-link"><img src="/icons/banknotes-10-16.png" class="m"> Услуги</a>

    </div></div>
	
<div id="eventbar">
    <div class="eventbar-right">
        <a href="/modules/mail" id="">Сообщения '.$mail.'</a>
        <a href="/modules/journal" id="">Журнал '.$journal.'</a>
		<a href="/modules/feed" id="">Мои Новости '.$feed.'</a>
    </div>
    </div>
	   
	   
<div class="sidebar-back"></div>
<div id="sidebar">

    <div class="sidebar-links">
	       <a href="/modules/feed/"><img src="/icons/newspaper-10.png" class="mq">  Мои Новости </a>
		  <a href="/modules/friends/user/'.$user[id].'/"><img src="/icons/frends.png" class="mq">  Мои Друзья</a>
		   <a href="/modules/photo_album/'.$user[id].'"><img src="/icons/photo.png" class="mq">  Мои Фотографии</a>
		  <a href="/modules/files/'.$user[id].'/"><img src="/icons/folder.png" class="mq"> Мои Файлы</a>
		   <a href="/modules/audio/"><img src="/icons/play.png" class="mq">   Топ-Музыка </a>
		  <a href="/modules/profile/blog/'.$data['id'].'"><img src="/icons/diary.png" class="mq">   Мои Блоги </a>
		    
		   
		   	'.($user['access'] > 0 && $user['access'] < 3 && $qusesrq['id'] != $user['id'] ? '
			<br>
	<a href="/modules/administration/"><img src="/icons/password.png" class="mq">  Админка </a>
	<a href="#"><img src="/icons/pin.png" class="mq"> Написать новость</a>
	<br>
	' : '').'	
		   ';

			echo'	 
        <div class="sidebar-bottom">
		<a href="#"><img src="/icons/str.png" class="m"> Помощь</a>
            <a href="/modules/landing/rules.php"><img src="/icons/str.png" class="m"> Правила</a>
            <a href="/modules/settings"><img src="/icons/str.png" class="m">  Настройки</a>
            <a href="/modules/authorization/output"><img src="/icons/str.png" class="m">  Выход</a>
        </div>
    </div>
</div>
   ';
   
echo '
<div id="wrap_all"><div id="main_shadow">
   <div id="left_nav_bg"></div>   
<div id="scroll_page" style="width: 255px; cursor: default;"> <div id="scroll_page_place_wrapper"> <div id="scroll_page_place" style="opacity: 0;"></div> </div> <div id="scroll_page_toTop" style="opacity: 0;">▲</div> <div id="scroll_page_toBottom" style="opacity: 0;">▼</div> </div>
<script type="text/javascript" src="/js/scroll_page.js"></script><div id="main_content_block">
<div class="content_wrap" id="content_wrap_move"><script src="/js/jquery-2.1.4.min.js"></script>
<script src="/js/ion.sound.min.js"></script>
<script>
	var NOTIFS_PLAY_SOUND = true;
</script>
<script src="/js/events.js"></script>
<div id="wrapper_for_header">
<div id="wrapper_for_header_fix"><div id="body"><style>
.__locationBar {
background: #FFF;
overflow: hidden;
border-bottom: 1px solid #D5DBE4;
    position: relative;
        font-size: 87.5%;
}
.__locationBar a {
padding: 10px;
display: inline-block;
color: #069;
text-decoration: none;
border-right: 1px solid #D5DBE4;
}
.__locationBar a:hover {
background: #eceff1;
}
.__locationBar span {
padding: 10px;
color: #434958;
}
</style>

';

//echo '<a class="clock" href="/modules/settings/clock">'.$system->system_time(time()).'</a>';

    }	else  {
	
   echo '
<div id="toolbar">
    <div class="toolbar-left">
        <a href="/" class="toolbar-logo"><img src="/template/logo_r.png"></a>
        <a href="/modules/authorization" class="toolbar-link">Вход</a>
        <a href="/modules/registration" class="toolbar-link">Регистрация</a>
        <a href="/" class="toolbar-link">О нас</a>
        <a href="/" class="toolbar-link">Наши контакты</a>
    </div>
    </div>
	<div id="wrap_all"><div id="main_shadow">
	<div id="left_nav_bg"></div>
	'; 
    
    }
	
// Выводим блок уведомлений

    echo (isset($_SESSION['show']) ? '
    <div class="show">
    ' . $_SESSION['show'] . '
    </div>' : '');

?>