<?php
/* 
	Appointment: Подключение модулей
	File: mod.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if(isset($_GET['go']))
	$go = htmlspecialchars(strip_tags(stripslashes(trim(urldecode(mysql_escape_string($_GET['go']))))));
else
	$go = "main";

$mozg_module = $go;

check_xss();

switch($go){
	
	//Регистрация
	case "register":
		include ENGINE_DIR.'/modules/register.php';
	break;
	
	//Login
	case "llogin":
		include ENGINE_DIR.'/modules/llogin.php';
	break;
	
	//Reg
	case "rreg":
		include ENGINE_DIR.'/modules/rreg.php';
	break;
	
	//Разработчикам
	case "dev":
		include ENGINE_DIR.'/modules/dev.php';
	break;
	
	//Работа
	case "jobs":
		$spBar = true;
		include ENGINE_DIR.'/modules/jobs.php';
	break;
	
	//Чат с диалогами
	case "im_chat":
		include ENGINE_DIR.'/modules/im_chat.php';
	break;
	
	//ADS
	case "ads":
		include ENGINE_DIR.'/modules/ads.php';
	break;
	
	//Граффити
	case "graffiti":
		include ENGINE_DIR.'/modules/graffiti.php';
	break;
	
	//Профиль пользователя
	case "profile":
		$spBar = true;
		include ENGINE_DIR.'/modules/profile.php';
	break;
	
	//Статистика сообществ
	case "stats_groups":
		include ENGINE_DIR.'/modules/stats_groups.php';
	break;
	
	//Редактирование моей страницы
	case "editprofile":
		include ENGINE_DIR.'/modules/editprofile.php';
	break;
	
	//Загрузка городов
	case "loadcity":
		include ENGINE_DIR.'/modules/loadcity.php';
	break;
	
	//Альбомы
	case "albums":
		if($config['album_mod'] == 'yes')
			include ENGINE_DIR.'/modules/albums.php';
		else {
			$user_speedbar = 'Информация';
			msgbox('', 'Сервис отключен.', 'info');
		}
	break;
	
	//Альбомы
	case "groups_albums":
		include ENGINE_DIR.'/modules/albums_groups.php';
	break;
	
	//Просмотр фотографии
	case "photo":
		include ENGINE_DIR.'/modules/photo.php';
	break;
	
	case "photo_groups":
		include ENGINE_DIR.'/modules/photo_groups.php';
	break;
	
	//Друзья
	case "friends":
		include ENGINE_DIR.'/modules/friends.php';
	break;
	
	//Закладки
	case "fave":
		include ENGINE_DIR.'/modules/fave.php';
	break;
	
	//Сообщения
	case "messages":
		include ENGINE_DIR.'/modules/messages.php';
	break;
	
	//Диалоги
	case "im":
		include ENGINE_DIR.'/modules/im.php';
	break;

	//Заметки
	case "notes":
		include ENGINE_DIR.'/modules/notes.php';
	break;
	
	//Заявки в AJAX
    case "ajaxfriends":
		include ENGINE_DIR.'/modules/ajaxfriends.php';
    break;
	
	//Сообщения в AJAX
    case "ajaxmsg":
		include ENGINE_DIR.'/modules/ajaxmsg.php';
    break;
	
	//Фото в AJAX
    case "ajaxphoto":
		include ENGINE_DIR.'/modules/ajaxphoto.php';
    break;
	
	//Подписки
	case "subscriptions":
		include ENGINE_DIR.'/modules/subscriptions.php';
	break;
	
	//Видео
	case "videos":
		if($config['video_mod'] == 'yes')
			include ENGINE_DIR.'/modules/videos.php';
		else {
			$user_speedbar = 'Информация';
			msgbox('', 'Сервис отключен.', 'info');
		}
	break;
	
	//Поиск
	case "search":
		include ENGINE_DIR.'/modules/search.php';
	break;
	
	//Стена
	case "wall":
		include ENGINE_DIR.'/modules/wall.php';
	break;
	
	//Статус
	case "status":
		include ENGINE_DIR.'/modules/status.php';
	break;
	
	//Новости
	case "news":
		include ENGINE_DIR.'/modules/news.php';
	break;
	
	//Настройки
	case "settings":
		include ENGINE_DIR.'/modules/settings.php';
	break;
	
	//Помощь
	case "support":
		include ENGINE_DIR.'/modules/support.php';
	break;
	
	//Игры
	case "apps":
		include ENGINE_DIR.'/modules/apps.php';
	break;
	
	case "editapp":
		include ENGINE_DIR.'/modules/editapp.php';
    break;
	
	//Воостановление доступа
	case "restore":
		include ENGINE_DIR.'/modules/restore.php';
	break;
	
	//Загрузка картинок при прикриплении файлов со стены, заметок, или сообщений
	case "attach":
		include ENGINE_DIR.'/modules/attach.php';
	break;
	
	//Блог сайта
	case "blog":
		include ENGINE_DIR.'/modules/blog.php';
	break;

	//Баланс
	case "balance":
		include ENGINE_DIR.'/modules/balance.php';
	break;
	
	//Подарки
	case "gifts":
		include ENGINE_DIR.'/modules/gifts.php';
	break;

	//Сообщества
	case "groups":
		include ENGINE_DIR.'/modules/groups.php';
	break;
	
	//Сообщества
	case "clubs":
		include ENGINE_DIR.'/modules/clubs.php';
	break;
	
	//Сообщества -> Публичные страницы
	case "public":
		$spBar = true;
		include ENGINE_DIR.'/modules/public.php';
	break;
	
	//Сообщества -> Группы
	case "club":
		$spBar = true;
		include ENGINE_DIR.'/modules/club.php';
	break;
	
	//Сообщества -> Загрузка фото
	case "attach_groups":
		include ENGINE_DIR.'/modules/attach_groups.php';
	break;

	//Музыка
	case "audio":
		if($config['audio_mod'] == 'yes')
			include ENGINE_DIR.'/modules/audio.php';
		else {
			$user_speedbar = 'Информация';
			msgbox('', 'Сервис отключен.', 'info');
		}
	break;
	
	//Плеер в окне
	case "audio_player":
		if($config['audio_mod'] == 'yes')
			include ENGINE_DIR.'/modules/audio_player.php';
		else {
			$spBar = true;
			$user_speedbar = 'Информация';
			msgbox('', 'Сервис отключен.', 'info');
		}
	break;

	//Статические страницы
	case "static":
		include ENGINE_DIR.'/modules/static.php';
	break;

	//Выделить человека на фото
	case "distinguish":
		include ENGINE_DIR.'/modules/distinguish.php';
	break;

	//Скрываем блок Дни рожденья друзей
	case "happy_friends_block_hide":
		$_SESSION['happy_friends_block_hide'] = 1;
		die();
	break;

	//Скрываем блок Дни рожденья друзей
	case "fast_search":
		include ENGINE_DIR.'/modules/fast_search.php';
	break;

	//Жалобы
	case "report":
		include ENGINE_DIR.'/modules/report.php';
	break;
	
	// Алиасы
	case "alias":
	$spBar = true;
    $alias = $db->safesql($_GET['url']);
	if($alias){
	$alias_club = $db->super_query("SELECT id,title FROM `".PREFIX."_clubs` WHERE adres = '".$alias."' "); //Проверяем адреса у групп
 	$alias_public = $db->super_query("SELECT id,title FROM `".PREFIX."_communities` WHERE adres = '".$alias."' "); //Проверяем адреса у публичных страниц
	$alias_user = $db->super_query("SELECT user_id, user_search_pref FROM `".PREFIX."_users` WHERE alias = '".$alias."'"); // Проверяем адреса у пользователей
    if($alias_user){   			
	    $_GET['id']= $alias_user['user_id'];
	    include ENGINE_DIR.'/modules/profile.php';
		}elseif($alias_public){   		
		$_GET['pid']= $alias_public['id'];
		include ENGINE_DIR.'/modules/public.php';
		}elseif($alias_club){   		
		$_GET['gid']= $alias_club['id'];
		include ENGINE_DIR.'/modules/club.php';
	}else{
	$spBar = true;
			$user_speedbar = 'Информация';
			msgbox('', 'Доменное имя <b>'.$alias.'</b> свободно для регистрации.', 'info');
	}
	}
    break;

	//Отправка записи в сообщество или другу
	case "repost":
		include ENGINE_DIR.'/modules/repost.php';
	break;

	//Моментальные оповещания
	case "updates":
		include ENGINE_DIR.'/modules/updates.php';
	break;
	
	//Обсужднеия
	case "board":
		include ENGINE_DIR.'/modules/board.php';
	break;

	//Документы
	case "doc":
		include ENGINE_DIR.'/modules/doc.php';
	break;

	//Опросы
	case "votes":
		include ENGINE_DIR.'/modules/votes.php';
	break;
	
	case "editpage":
		include ENGINE_DIR.'/modules/editpage.php';
	break;
	
	//Сообщества -> Публичные страницы -> Аудиозаписи
	case "public_audio":
		include ENGINE_DIR.'/modules/public_audio.php';
	break;
	
	//Статистика страницы пользователя
	case "my_stats":
		include ENGINE_DIR.'/modules/my_stats.php';
	break;	
	
	//Сообщества -> Публичные страницы -> Видеозаписи
	case "public_videos":
		include ENGINE_DIR.'/modules/public_videos.php';
	break;
	
	//Сообщества => обсуждения
	case "groups_forum":
		include ENGINE_DIR.'/modules/groups_forum.php';
    break;
	
	//Сообщества -> Группы -> Аудиозаписи
	case "club_audio":
		include ENGINE_DIR.'/modules/club_audio.php';
	break;
	
	case "forum":
		include ENGINE_DIR.'/modules/forum.php';
	break;



		default:
			$spBar = true;
			
			/*if($logged)
				header("Location: /u{$user_info['user_id']}");
			else*/
				if($go != 'main')
					msgbox('', $lang['no_str_bar'], 'info');
}

if(!$metatags['title'])
	$metatags['title'] = $config['home'];
	
if($user_speedbar) 
	$speedbar = $user_speedbar;
else 
	$speedbar = $lang['welcome'];

$headers = '<title>'.$metatags['title'].'</title>
<meta name="generator" content="TOEngine" />
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />';
?>