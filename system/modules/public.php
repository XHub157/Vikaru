<?php
/* 
	Appointment: Сообщества -> Публичные страницы
	File: public.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$user_id = $user_info['user_id'];
	$pid = intval($_GET['pid']);
	
	if(preg_match("/^[a-zA-Z0-9_-]+$/", $_GET['get_adres'])) $get_adres = $db->safesql($_GET['get_adres']);
	
	$sql_where = "id = '".$pid."'";
	
	if($pid){
		$get_adres = '';
		$sql_where = "id = '".$pid."'";
	}
	if($get_adres){
		$pid = '';
		$sql_where = "adres = '".$get_adres."'";
	} else
	
	echo $get_adres;

	//Если страница вывзана через "к предыдущим записям"
	$limit_select = 10;
	if($_POST['page_cnt'] > 0)
		$page_cnt = intval($_POST['page_cnt'])*$limit_select;
	else
		$page_cnt = 0;

	if($page_cnt){
		$row = $db->super_query("SELECT admin FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
		$row['id'] = $pid;
	} else
		$row = $db->super_query("SELECT id, com_real, ptype, title, descr, traf, ulist, photo, date, admin, feedback, comments, status_text, real_admin, rec_num, del, ban, adres, audio_num, gtype, videos_num, web, privacy FROM `".PREFIX."_communities` WHERE ".$sql_where."");
	
	if($row['del'] == 1){
		$user_speedbar = 'Страница удалена';
		msgbox('', '<br /><br />Сообщество удалено администрацией.<br /><br /><br />', 'info_2');
	} elseif($row['ban'] == 1){
		$user_speedbar = 'Страница заблокирована';
		msgbox('', '<br /><br />Сообщество заблокировано администрацией.<br /><br /><br />', 'info_2');
	} elseif($row){
		$metatags['title'] = stripslashes($row['title']);
		if($row['com_real']==1){
		$ver = '<a href="/verify"><div class="page_verified" onMouseOver="news.showWallText(250)" onMouseOut="news.hideWallText(250)" id="2href_text_250" style="cursor:pointer"></div></a>';
		}else{
		$ver ='';
		}
		$user_speedbar = $row['ptype'].$ver;
		
		if(stripos($row['admin'], "u{$user_id}|") !== false)
			$public_admin = true;
		else
			$public_admin = false;

		//Стена
		//Если страница вывзана через "к предыдущим записям"
		if($page_cnt)
			NoAjaxQuery();
		
		include ENGINE_DIR.'/classes/wall.public.php';
		$wall = new wall();
		$wall->query("SELECT SQL_CALC_FOUND_ROWS tb1.id, text, public_id, add_date, fasts_num, attach, likes_num, likes_users, tell_uid, public, tell_date, tell_comm, tb2.title, photo, comments, adres FROM `".PREFIX."_communities_wall` tb1, `".PREFIX."_communities` tb2 WHERE tb1.public_id = '{$row['id']}' AND tb1.public_id = tb2.id AND fast_comm_id = 0 ORDER by `add_date` DESC LIMIT {$page_cnt}, {$limit_select}");
		$wall->template('groups/record.tpl');
		//Если страница вывзана через "к предыдущим записям"
		if($page_cnt)
			$wall->compile('content');
		else
			$wall->compile('wall');
		$wall->select($public_admin, $server_time);
		
		//Если страница вывзана через "к предыдущим записям"
		if($page_cnt){
			AjaxTpl();
			exit;
		}
		
		$tpl->load_template('public/main.tpl');
		
		$tpl->set('{title}', stripslashes($row['title']));
		if($row['photo']){
			$tpl->set('{photo}', "/uploads/groups/{$row['id']}/{$row['photo']}");
			$tpl->set('{ava}', $row['photo']);
			$tpl->set('{display-ava}', '');
		} else {
			$tpl->set('{ava}', '');
			$tpl->set('{photo}', "{theme}/images/no_ava.gif");
			$tpl->set('{display-ava}', 'no_display');
		}
		
		if($row['descr'])
			$tpl->set('{descr-css}', '');
		else 
			$tpl->set('{descr-css}', 'no_display');
		
		$privacy = xfieldsdataload($row['privacy']);
		
		if($privacy['p_contact']) {
			$tpl->set('[pr_contact]', '');
			$tpl->set('[/pr_contact]', '');
		} else $tpl->set_block("'\\[pr_contact\\](.*?)\\[/pr_contact\\]'si","");
		
		if($privacy['p_audio']) {
			$tpl->set('[pr_audio]', '');
			$tpl->set('[/pr_audio]', '');
		} else $tpl->set_block("'\\[pr_audio\\](.*?)\\[/pr_audio\\]'si","");
		
		if($privacy['p_videos']) {
			$tpl->set('[pr_videos]', '');
			$tpl->set('[/pr_videos]', '');
		} else $tpl->set_block("'\\[pr_videos\\](.*?)\\[/pr_videos\\]'si","");
		
		//КНопка Показать полностью..
		$expBR = explode('<br />', $row['descr']);
		$textLength = count($expBR);
		if($textLength > 9)
			$row['descr'] = '<div class="wall_strlen" id="hide_wall_rec'.$row['id'].'">'.$row['descr'].'</div><div class="wall_strlen_full" onMouseDown="wall.FullText('.$row['id'].', this.id)" id="hide_wall_rec_lnk'.$row['id'].'">Показать полностью..</div>';
				
		$tpl->set('{descr}', stripslashes($row['descr']));
		$tpl->set('{edit-descr}', myBrRn(stripslashes($row['descr'])));
		
		$tpl->set('{num}', '<span id="traf">'.$row['traf'].'</span> '.gram_record($row['traf'], 'subscribers'));
		if($row['traf']){
			$tpl->set('{num-2}', '<a href="/public'.$row['id'].'" onClick="groups.subscribes_groups(\''.$row['id'].'\'); return false">'.gram_record($row['traf'], 'subscribers2').'</a>');
			$tpl->set('{no-users}', '');
		} else {
			$tpl->set('{num-2}', '<span class="color777">Вы будете первым.</span>');
			$tpl->set('{no-users}', 'no_display');
		}
		
		//Права админа
		if($public_admin){
			$tpl->set('[admin]', '');
			$tpl->set('[/admin]', '');
		} else
			$tpl->set_block("'\\[admin\\](.*?)\\[/admin\\]'si","");
		
		//Проверка подписан юзер или нет
		if(stripos($row['ulist'], "|{$user_id}|") !== false)
			$tpl->set('{yes}', 'no_display');
		else
			$tpl->set('{no}', 'no_display');
			
		//Тематика by sloopy 
		$tpl->set('{gtype2}', installationSelected($row['gtype'], '<option value="СМИ">СМИ</option><option value="Электроника и техника">Электроника и техника</option><option value="Фото, оптика">Фото, оптика</option><option value="Услуги и деятельность">Услуги и деятельность</option><option value="Телефоны и связь">Телефоны и связь</option><option value="Строительство и ремонт">Строительство и ремонт</option><option value="Публичная страница">Публичная страница</option><option value="Отказаться от рекламы">Отказаться от рекламы</option><option value="Одежда, обувь, аксессуары">Одежда, обувь, аксессуары</option><option value="Недвижимость">Недвижимость</option><option value="Музыка, искусство">Музыка, искусство</option><option value="Мебель, интерьер">Мебель, интерьер</option><option value="Компьютерная техника">Компьютерная техника</option><option value="Книги, учебники, журналы">Книги, учебники, журналы</option><option value="Игры">Игры</option><option value="Видео">Видео</option><option value="Авто и мото">Авто и мото</option> '));  
			
		//Контакты
		if($row['feedback']){
			$tpl->set('[yes]', '');
			$tpl->set('[/yes]', '');
			$tpl->set_block("'\\[no\\](.*?)\\[/no\\]'si","");
			$tpl->set('{num-feedback}', '<span id="fnumu">'.$row['feedback'].'</span> '.gram_record($row['feedback'], 'feedback'));
			$sql_feedbackusers = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.fuser_id, office, tb2.user_search_pref, user_photo FROM `".PREFIX."_communities_feedback` tb1, `".PREFIX."_users` tb2 WHERE tb1.cid = '{$row['id']}' AND tb1.fuser_id = tb2.user_id ORDER by `fdate` ASC LIMIT 0, 5", 1);
			foreach($sql_feedbackusers as $row_feedbackusers){
				if($row_feedbackusers['user_photo']) $ava = "/uploads/users/{$row_feedbackusers['fuser_id']}/50_{$row_feedbackusers['user_photo']}";
				else $ava = "{theme}/images/no_ava_50.png";
				$row_feedbackusers['office'] = stripslashes($row_feedbackusers['office']);
				$feedback_users .= "<div class=\"onesubscription onesubscriptio2n\" style=\"margin-left:8px;\" id=\"fb{$row_feedbackusers['fuser_id']}\"><a href=\"/u{$row_feedbackusers['fuser_id']}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava}\" alt=\"\" /><div class=\"onesubscriptiontitle\">{$row_feedbackusers['user_search_pref']}</div></a><div class=\"nesubscriptstatus\">{$row_feedbackusers['office']}</div></div>";
			}
			$tpl->set('{feedback-users}', $feedback_users);
			$tpl->set('[feedback]', '');
			$tpl->set('[/feedback]', '');
		} else {
			$tpl->set('[no]', '');
			$tpl->set('[/no]', '');
			$tpl->set_block("'\\[yes\\](.*?)\\[/yes\\]'si","");
			$tpl->set('{feedback-users}', '');
			if($public_admin){
				$tpl->set('[feedback]', '');
				$tpl->set('[/feedback]', '');
			} else
				$tpl->set_block("'\\[feedback\\](.*?)\\[/feedback\\]'si","");
		}
		
		//Выводим подписчиков
		$sql_users = $db->super_query("SELECT tb1.user_id, tb2.user_name, user_lastname, user_photo FROM `".PREFIX."_friends` tb1, `".PREFIX."_users` tb2 WHERE tb1.friend_id = '{$row['id']}' AND tb1.user_id = tb2.user_id AND tb1.subscriptions = 2 ORDER by rand() LIMIT 0, 6", 1);
		foreach($sql_users as $row_users){
			if($row_users['user_photo']) $ava = "/uploads/users/{$row_users['user_id']}/50_{$row_users['user_photo']}";
			else $ava = "{theme}/images/no_ava_50.png";
			$users .= "<div class=\"onefriend oneusers\" id=\"subUser{$row_users['user_id']}\"><a href=\"/u{$row_users['user_id']}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava}\"  style=\"margin-bottom:3px\" /></a><a href=\"/u{$row_users['user_id']}\" onClick=\"Page.Go(this.href); return false\">{$row_users['user_name']}<br /><span>{$row_users['user_lastname']}</span></a></div>";
		}
		$tpl->set('{users}', $users); 
		
		$tpl->set('{id}', $row['id']);
		megaDate(strtotime($row['date']), 1, 1);
		
		//Комментарии включены
		if($row['comments'])
			$tpl->set('{settings-comments}', 'comments');
		else
			$tpl->set('{settings-comments}', 'none');
			
		//Выводим админов при ред. страницы
		if($public_admin){
			$admins_arr = str_replace('|', '', explode('u', $row['admin']));
			foreach($admins_arr as $admin_id){
				if($admin_id){
					$row_admin = $db->super_query("SELECT user_search_pref, user_photo FROM `".PREFIX."_users` WHERE user_id = '{$admin_id}'");
					if($row_admin['user_photo']) $ava_admin = "/uploads/users/{$admin_id}/50_{$row_admin['user_photo']}";
					else $ava_admin = "{theme}/images/no_ava_50.png";
					if($admin_id != $row['real_admin']) $admin_del_href = "<a href=\"/\" onClick=\"groups.deladmin('{$row['id']}', '{$admin_id}'); return false\"><small>Удалить</small></a>";
					$adminO .= "<div class=\"public_oneadmin\" id=\"admin{$admin_id}\"><a href=\"/u{$admin_id}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava_admin}\" align=\"left\" width=\"32\" /></a><a href=\"/u{$admin_id}\" onClick=\"Page.Go(this.href); return false\">{$row_admin['user_search_pref']}</a><br />{$admin_del_href}</div>";		
				}
			}
			
			$tpl->set('{admins}', $adminO);
		}

		$tpl->set('{records}', $tpl->result['wall']);
		
		//Стена
		if($row['rec_num'] > 10)
			$tpl->set('{wall-page-display}', '');
		else
			$tpl->set('{wall-page-display}', 'no_display');
			
		if($row['rec_num'])
			$tpl->set('{rec-num}', '<b id="rec_num">'.$row['rec_num'].'</b> '.gram_record($row['rec_num'], 'rec'));
		else {
			$tpl->set('{rec-num}', '<b id="rec_num">Нет записей</b>');
			if($public_admin)
				$tpl->set('{records}', '<div class="wall_none" style="border-top:0px">Новостей пока нет.</div>');
			else
				$tpl->set('{records}', '<div class="wall_none">Новостей пока нет.</div>');
		}
		
		//Выводим информцию о том кто смотрит страницу для себя
		$tpl->set('{viewer-id}', $user_id);
			
		if(!$row['adres']) $row['adres'] = 'public'.$row['id'];
		$tpl->set('{adres}', $row['adres']);

		//Аудиозаписи
		if($row['audio_num']){
			$sql_audios = $db->super_query("SELECT SQL_CALC_FOUND_ROWS url, artist, name FROM `".PREFIX."_communities_audio` WHERE public_id = '{$row['id']}' ORDER by `adate` DESC LIMIT 0, 3", 1, "groups/audio{$row['id']}");
			$jid = 0;
			foreach($sql_audios as $row_audios){
				$jid++;
				
				$row_audios['artist'] = stripslashes($row_audios['artist']);
				$row_audios['name'] = stripslashes($row_audios['name']);
				
				$audios .= "<div class=\"audio_onetrack\"><div class=\"audio_playic cursor_pointer fl_l\" onClick=\"music.newStartPlay('{$jid}')\" id=\"icPlay_{$jid}\"></div><span id=\"music_{$jid}\" data=\"{$row_audios['url']}\"><a href=\"/?go=search&query={$row_audios['artist']}&type=5\" onClick=\"Page.Go(this.href); return false\"><b><span id=\"artis{aid}\">{$row_audios['artist']}</span></b></a> &ndash; <span id=\"name{aid}\">{$row_audios['name']}</span></span><div id=\"play_time{$jid}\" class=\"color777 fl_r no_display\" style=\"margin-top:2px;margin-right:5px\"></div> <div class=\"clear\"></div><div class=\"player_mini_mbar fl_l no_display\" id=\"ppbarPro{$jid}\" style=\"width:178px\"></div> </div>";
				
			}
			
			$tpl->set('{audios}', $audios);
			$tpl->set('{audio-num}', $row['audio_num'].' '.gram_record($row['audio_num'], 'audio'));
			$tpl->set('[audios]', '');
			$tpl->set('[/audios]', '');
			$tpl->set('[yesaudio]', '');
			$tpl->set('[/yesaudio]', '');
			$tpl->set_block("'\\[noaudio\\](.*?)\\[/noaudio\\]'si","");
			
		} else {
		
			$tpl->set('{audios}', '');
			$tpl->set('[noaudio]', '');
			$tpl->set('[/noaudio]', '');
			$tpl->set_block("'\\[yesaudio\\](.*?)\\[/yesaudio\\]'si","");
			
			if($public_admin){
				$tpl->set('[audios]', '');
				$tpl->set('[/audios]', '');
			} else
				$tpl->set_block("'\\[audios\\](.*?)\\[/audios\\]'si","");
			
		}
		
	//Статус
		$tpl->set('{status-text}', stripslashes($row['status_text']));
			
		if($row['status_text']){
		
			$tpl->set('[status]', '');
			$tpl->set('[/status]', '');
			$tpl->set_block("'\\[no-status\\](.*?)\\[/no-status\\]'si","");
			
		} else {
		
			$tpl->set_block("'\\[status\\](.*?)\\[/status\\]'si","");
			$tpl->set('[no-status]', '');
			$tpl->set('[/no-status]', '');
			
		}
		
		$tpl->set('{gtype}', $row['gtype']);
		if($row['gtype']){
		$tpl->set('[gtype]', '');
		$tpl->set('[/gtype]', '');
		} else
		$tpl->set_block("'\\[gtype\\](.*?)\\[/gtype\\]'si","");
		
		//Видеозаписи
		if($row['videos_num']){
			
			$sql_videos = $db->super_query("SELECT id, title, photo, add_date, comm_num, owner_user_id FROM `".PREFIX."_videos` WHERE public_id = '{$row['id']}' ORDER by `add_date` DESC LIMIT 0, 2", 1, "groups/video{$row['id']}");
			
			foreach($sql_videos as $row_video){
				
				$row_video['title'] = stripslashes($row_video['title']);
				$date_video = megaDateNoTpl(strtotime($row_video['add_date']));
				$comm_num = $row_video['comm_num'].' '.gram_record($row_video['comm_num'], 'comments');
				
				$videos .= "
<div class=\"public_one_video\"><a href=\"/video{$row_video['owner_user_id']}_{$row_video['id']}\" onClick=\"videos.show({$row_video['id']}, this.href, '/{$row['adres']}'); return false\"><img src=\"{$row_video['photo']}\" alt=\"\" width=\"185\" /></a><div class=\"video_profile_title\"><a href=\"/video{$row_video['owner_user_id']}_{$row_video['id']}\" onClick=\"videos.show({$row_video['id']}, this.href, '/{$row['adres']}'); return false\">{$row_video['title']}</a></div><div class=\"nesubscriptstatus\">{$date_video} - <a href=\"/video{$row_video['owner_user_id']}_{$row_video['id']}\" onClick=\"videos.show({$row_video['id']}, this.href, '/{$row['adres']}'); return false\">{$comm_num}</a></div></div>
				";
				
			}
			
			$tpl->set('{videos}', $videos);
			$tpl->set('{videos-num}', $row['videos_num'].' '.gram_record($row['videos_num'], 'videos'));
			$tpl->set('[videos]', '');
			$tpl->set('[/videos]', '');
			$tpl->set('[yesvideo]', '');
			$tpl->set('[/yesvideo]', '');
			$tpl->set_block("'\\[novideo\\](.*?)\\[/novideo\\]'si","");
			
		} else {
		
			$tpl->set('{videos}', '');
			$tpl->set('[novideo]', '');
			$tpl->set('[/novideo]', '');
			$tpl->set_block("'\\[yesvideo\\](.*?)\\[/yesvideo\\]'si","");
			
			if($public_admin){
			
				$tpl->set('[videos]', '');
				$tpl->set('[/videos]', '');
				
			} else
				$tpl->set_block("'\\[videos\\](.*?)\\[/videos\\]'si","");
			
		}

		$tpl->set('{web}', $row['web']);
		if($row['web']){
		$tpl->set('[web]', '');
		$tpl->set('[/web]', '');
		} else
		$tpl->set_block("'\\[web\\](.*?)\\[/web\\]'si","");
		
		//
		$privaces = xfieldsdataload($row['privacy']);	
		if($privaces['p_audio']) $tpl->set('{settings-audio}', 'audio');
		else $tpl->set('{settings-audio}', 'none');
		if($privaces['p_videos']) $tpl->set('{settings-videos}', 'videos');
		else $tpl->set('{settings-videos}', 'none');
		if($privaces['p_contact']) $tpl->set('{settings-contact}', 'contact');
		else $tpl->set('{settings-contact}', 'none');
		
		//Записываем в статистику "Уникальные посетители"
		$stat_date = date('Y-m-d', $server_time);
		$stat_x_date = date('Y-m', $server_time);
		$stat_date = strtotime($stat_date);
		$stat_x_date = strtotime($stat_x_date);
		
		$check_stat = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_communities_stats` WHERE gid = '{$row['id']}' AND date = '{$stat_date}'");
		$check_user_stat = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_communities_stats_log` WHERE gid = '{$row['id']}' AND user_id = '{$user_info['user_id']}' AND date = '{$stat_date}' AND act = '1'");
		
		if(!$check_user_stat['cnt']){
		
			if($check_stat['cnt']){
			
				$db->query("UPDATE `".PREFIX."_communities_stats` SET cnt = cnt + 1 WHERE gid = '{$row['id']}' AND date = '{$stat_date}'");

			} else {
			
				$db->query("INSERT INTO `".PREFIX."_communities_stats` SET gid = '{$row['id']}', date = '{$stat_date}', cnt = '1', date_x = '{$stat_x_date}'");
			
			}
			
			$db->query("INSERT INTO `".PREFIX."_communities_stats_log` SET user_id = '{$user_info['user_id']}', date = '{$stat_date}', gid = '{$row['id']}', act = '1'");
		
		}
		
		//Записываем в статистику "Просмотры"
		$db->query("UPDATE `".PREFIX."_communities_stats` SET hits = hits + 1 WHERE gid = '{$row['id']}' AND date = '{$stat_date}'");
		
		$tpl->compile('content');
	} else {
		$user_speedbar = $lang['no_infooo'];
		msgbox('', $lang['no_upage'], 'info');
	}
	
	$tpl->clear();
	$db->free();
} else {
	$pid = intval($_GET['pid']);

	if(!$row){
		$row = $db->super_query("SELECT id, com_real, ptype, title, descr, traf, ulist, photo, date, admin, feedback, comments, status_text, real_admin, rec_num, del, ban, adres, audio_num, gtype, videos_num, web, privacy FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
	} 

	//Если есть такой,  юзер то продолжаем выполнение скрипта
	if($row){			
			$metatags['title'] = stripslashes($row['title']);
			if($row['com_real']==1){
			$ver = '<a href="/verify"><div class="page_verified" onMouseOver="news.showWallText(250)" onMouseOut="news.hideWallText(250)" id="2href_text_250" style="cursor:pointer"></div></a>';
			}else{
			$ver ='';
			}
			$user_speedbar = $row['ptype'].$ver;
         $tpl->load_template('public/prm.tpl');
		 //Статус
			$tpl->set('{status-text}', stripslashes($row['status_text']));
			
			if($row['status_text']){
				$tpl->set('[status]', '');
				$tpl->set('[/status]', '');
				$tpl->set_block("'\\[no-status\\](.*?)\\[/no-status\\]'si","");
			} else {
				$tpl->set_block("'\\[status\\](.*?)\\[/status\\]'si","");
				$tpl->set('[no-status]', '');
				$tpl->set('[/no-status]', '');
			}
			//Аватарка
			$tpl->set('{title}', stripslashes($row['title']));
			if($row['photo']){
			if($config['temp'] == 'mobile'){$tpl->set('{ava}', $config['home_url'].'uploads/groups/'.$row['id'].'/50_'.$row['photo']);}else{$tpl->set('{ava}', $config['home_url'].'uploads/groups/'.$row['id'].'/'.$row['photo']);}
				$tpl->set('{display-ava}', 'style="display:block;"');
			} else {
				$tpl->set('{ava}', '{theme}/images/no_ava.gif');
				$tpl->set('{display-ava}', 'style="display:none;"');
			}
			$tpl->set('{num}', '<span id="traf">'.$row['traf'].'</span> '.gram_record($row['traf'], 'subscribers'));
		if($row['traf']){
			$tpl->set('{num-2}', '<a href="/public'.$row['id'].'" onClick="groups.subscribes_groups(\''.$row['id'].'\'); return false">'.gram_record($row['traf'], 'subscribers2').'</a>');
			$tpl->set('{no-users}', '');
		} else {
			$tpl->set('{num-2}', '<span class="color777">Вы будете первым.</span>');
			$tpl->set('{no-users}', 'no_display');
		}
		$tpl->set('{descr}', stripslashes($row['descr']));
		$tpl->set('{web}', $row['web']);
		if($row['web']){
		$tpl->set('[web]', '');
		$tpl->set('[/web]', '');
		} else
		$tpl->set_block("'\\[web\\](.*?)\\[/web\\]'si","");
		$tpl->set('{gtype}', $row['gtype']);
		if($row['gtype']){
		$tpl->set('[gtype]', '');
		$tpl->set('[/gtype]', '');
		} else
		$tpl->set_block("'\\[gtype\\](.*?)\\[/gtype\\]'si","");
		if($row['rec_num'])
			$tpl->set('{rec-num}', '<b id="rec_num">'.$row['rec_num'].'</b> '.gram_record($row['rec_num'], 'rec'));
		else {
			$tpl->set('{rec-num}', '<b id="rec_num">Нет записей</b>');
			if($public_admin)
				$tpl->set('{records}', '<div class="wall_none" style="border-top:0px">Новостей пока нет.</div>');
			else
				$tpl->set('{records}', '<div class="wall_none">Новостей пока нет.</div>');
		}
		//Выводим подписчиков
		$sql_users = $db->super_query("SELECT tb1.user_id, tb2.user_name, user_lastname, user_photo FROM `".PREFIX."_friends` tb1, `".PREFIX."_users` tb2 WHERE tb1.friend_id = '{$row['id']}' AND tb1.user_id = tb2.user_id AND tb1.subscriptions = 2 ORDER by rand() LIMIT 0, 6", 1);
		foreach($sql_users as $row_users){
			if($row_users['user_photo']) $ava = "/uploads/users/{$row_users['user_id']}/50_{$row_users['user_photo']}";
			else $ava = "{theme}/images/no_ava_50.png";
			$users .= "<div class=\"onefriend oneusers\" id=\"subUser{$row_users['user_id']}\"><a href=\"/u{$row_users['user_id']}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava}\"  style=\"margin-bottom:3px\" /></a><a href=\"/u{$row_users['user_id']}\" onClick=\"Page.Go(this.href); return false\">{$row_users['user_name']}<br /><span>{$row_users['user_lastname']}</span></a></div>";
		}
		$tpl->set('{users}', $users); 
		
		$tpl->set('{id}', $row['id']);
		megaDate(strtotime($row['date']), 1, 1);
			$tpl->compile('content');
		
		}
		else {
		$user_speedbar = $lang['no_infooo'];
		msgbox('', $lang['no_upage'], 'infou');
	}
}
?>