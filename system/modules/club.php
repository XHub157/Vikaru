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
	$gid = intval($_GET['gid']);
	
	if(preg_match("/^[a-zA-Z0-9_-]+$/", $_GET['get_adres'])) $get_adres = $db->safesql($_GET['get_adres']);
	
	$sql_where = "id = '".$gid."'";
	
	if($gid){
		$get_adres = '';
		$sql_where = "id = '".$gid."'";
	}
	if($get_adres){
		$gid = '';
		$sql_where = "adres = '".$get_adres."'";
	} else
	
	echo $get_adres;

	//Если Группа вывзана через "к предыдущим записям"
	$limit_select = 10;
	if($_POST['page_cnt'] > 0)
		$page_cnt = intval($_POST['page_cnt'])*$limit_select;
	else
		$page_cnt = 0;

	if($page_cnt){
		$row = $db->super_query("SELECT admin,real_admin,data_del FROM `".PREFIX."_clubs` WHERE id = '{$gid}'");
		$row['id'] = $gid;
	} else
		$row = $db->super_query("SELECT id, title, descr, ban_reason, traf, flist, ulist, privacy, website, photo, date, data_del, admin, feedback, comments, real_admin, rec_num, del, ban, adres, audio_num, boards_num FROM `".PREFIX."_clubs` WHERE ".$sql_where."");
	
	if($row['del'] == 1){
		$user_speedbar = 'Группа удалена';
		msgbox('', '<br /><br />Сообщество удалено администрацией.<br /><br /><br />', 'info_2');
		if(stripos($row['admin'], "id{$user_id}|") !== false and ($server_time-$row['data_del'])<86400) msgbox('', '<br /><div class="texta">&nbsp;</div><div class="texta"></div><div class="button_div fl_l"><button onClick="clubs.overspublic('.$gid.'); return false" id="pubOvers">Восстановить группу</button></div>', 'info_2');
	} elseif($row){
		$user_privacy_loting = xfieldsdataload($row['privacy']);
		$metatags['title'] = stripslashes($row['title']);
		if($user_privacy_loting['val_intog'] == 1) $user_speedbar = "Открытая группа";
		else $user_speedbar = "Закрытая группа";
		
		if(stripos($row['admin'], "id{$user_id}|") !== false)
			$public_admin = true;
		else
			$public_admin = false;

		//Стена
		//Если Группа вывзана через "к предыдущим записям"
		if($page_cnt)
			NoAjaxQuery();
		
		include ENGINE_DIR.'/classes/wall.club.php';
		$wall = new wall();
		$wall->query("SELECT SQL_CALC_FOUND_ROWS tb1.id, tb1.uid, tb1.ofmessgroup, tb1.view_author, text, public_id, add_date, fasts_num, attach, likes_num, likes_users, tell_uid, public, tell_date, tell_comm, tb2.title, photo, comments, adres FROM `".PREFIX."_clubs_wall` tb1, `".PREFIX."_clubs` tb2 WHERE tb1.public_id = '{$row['id']}' AND tb1.public_id = tb2.id AND fast_comm_id = 0 ORDER by `add_date` DESC LIMIT {$page_cnt}, {$limit_select}");
		$wall->template('groups/recordclub.tpl');
		//Если Группа вывзана через "к предыдущим записям"
		if($page_cnt)
			$wall->compile('content');
		else
			$wall->compile('wall');
		$wall->select($public_admin, $server_time);
		
		//Если Группа вывзана через "к предыдущим записям"
		if($page_cnt){
			AjaxTpl();
			exit;
		}
		
		$tpl->load_template('club/main.tpl');
		
		$rowd = xfieldsdataload($row['privacy']);
		$tpl->set('{val_wall1_wall}', $rowd['val_wall1']);
		$tpl->set('{val_wall1_text_wall}', strtr($rowd['val_wall1'], array('1' => 'Выключена', '2' => 'Открытая', '3' => 'Закрытая')));
		$tpl->set('{val_intog}', $rowd['val_intog']);
		$tpl->set('{val_intog_text}', strtr($rowd['val_intog'], array('1' => 'Открытая', '2' => 'Закрытая')));
		$tpl->set('{val_board}', $rowd['val_board']);
		$tpl->set('{val_boards_text}', strtr($rowd['val_board'], array('1' => 'Выключены', '2' => 'Открытые', '3' => 'Ограниченные')));
		
		$tpl->set('{title}', stripslashes($row['title']));
		
		if($row['photo']){
			$tpl->set('{photo}', "/uploads/clubs/{$row['id']}/{$row['photo']}");
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
			
		if($row['website'])
			$tpl->set('{website-css}', '');
		else 
			$tpl->set('{website-css}', 'no_display');
		
		//КНопка Показать полностью..
		$expBR = explode('<br />', $row['descr']);
		$textLength = count($expBR);
		if($textLength > 9)
			$row['descr'] = '<div class="wall_strlen" id="hide_wall_rec'.$row['id'].'">'.$row['descr'].'</div><div class="wall_strlen_full" onMouseDown="wall.FullText('.$row['id'].', this.id)" id="hide_wall_rec_lnk'.$row['id'].'">Показать полностью..</div>';
				
		$tpl->set('{descr}', stripslashes($row['descr']));
		$tpl->set('{website}', stripslashes($row['website']));
		$tpl->set('{edit-descr}', myBrRn(stripslashes($row['descr'])));
		$tpl->set('{edit-webiste}', myBrRn(stripslashes($row['website'])));
		
		$tpl->set('{num}', '<span id="traf">'.$row['traf'].'</span> '.gram_record($row['traf'], 'groups_users'));
		if($row['traf']){
			$tpl->set('{num-2}', '<a href="/club'.$row['id'].'" onClick="clubs.all_people(\''.$row['id'].'\'); return false">'.gram_record($row['traf'], 'groups_act').'</a>');
			$tpl->set('{no-users}', '');
		} else {
			$tpl->set('{num-2}', '<span class="color777">Вы будете первым.</span>');
			$tpl->set('{no-users}', 'no_display');
		}
		
		//Права админа
		if($public_admin == true){
			$tpl->set('[admin]', '');
			$tpl->set('[/admin]', '');
		} else
			$tpl->set_block("'\\[admin\\](.*?)\\[/admin\\]'si","");

		//Права не админа
		if($public_admin == false){
			$tpl->set('[not-admin]', '');
			$tpl->set('[/not-admin]', '');
		} else
			$tpl->set_block("'\\[not-admin\\](.*?)\\[/not-admin\\]'si","");
			
		if($row['real_admin'] == $user_id){
			$tpl->set('[admin-del]', '');
			$tpl->set('[/admin-del]', '');
		} else
			$tpl->set_block("'\\[admin-del\\](.*?)\\[/admin-del\\]'si","");
		
		//Проверка подписан юзер или нет
		if(stripos($row['ulist'], "|{$user_id}|") !== false or stripos($row['flist'], "|{$user_id}|") !== false)
			$tpl->set('{yes}', 'no_display');
		else {
			$tpl->set('{no}', 'no_display');
			$tpl->set('{no-fev}', 'no_display');
		}
		
		if(stripos($row['ulist'], "|{$user_id}|") !== false) $tpl->set('{no-fev}', 'no_display');
		if(stripos($row['flist'], "|{$user_id}|") !== false) $tpl->set('{no}', 'no_display');
		
		//Контакты
		if($row['feedback']){
			$tpl->set('[yes]', '');
			$tpl->set('[/yes]', '');
			$tpl->set_block("'\\[no\\](.*?)\\[/no\\]'si","");
			$tpl->set('{num-feedback}', '<span id="fnumu">'.$row['feedback'].'</span> '.gram_record($row['feedback'], 'feedback'));
			$sql_feedbackusers = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.fuser_id, office, tb2.user_search_pref, user_photo FROM `".PREFIX."_clubs_feedback` tb1, `".PREFIX."_users` tb2 WHERE tb1.cid = '{$row['id']}' AND tb1.fuser_id = tb2.user_id ORDER by `fdate` ASC LIMIT 0, 5", 1);
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
		$sql_users = $db->super_query("SELECT tb1.user_id, tb2.user_name, user_lastname, user_photo FROM `".PREFIX."_friends` tb1, `".PREFIX."_users` tb2 WHERE tb1.friend_id = '{$row['id']}' AND tb1.user_id = tb2.user_id AND tb1.subscriptions = 3 ORDER by rand() LIMIT 0, 6", 1);
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
			$admins_arr = str_replace('|', '', explode('id', $row['admin']));
			foreach($admins_arr as $admin_id){
				if($admin_id){
					$row_admin = $db->super_query("SELECT user_search_pref, user_photo FROM `".PREFIX."_users` WHERE user_id = '{$admin_id}'");
					if($row_admin['user_photo']) $ava_admin = "/uploads/users/{$admin_id}/50_{$row_admin['user_photo']}";
					else $ava_admin = "{theme}/images/no_ava_50.png";
					if($admin_id != $row['real_admin']) $admin_del_href = "<a href=\"/\" onClick=\"clubs.deladmin('{$row['id']}', '{$admin_id}'); return false\"><small>Удалить</small></a>";
					$adminO .= "<div class=\"public_oneadmin\" id=\"admin{$admin_id}\"><a href=\"/u{$admin_id}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava_admin}\" align=\"left\" width=\"32\" /></a><a href=\"/u{$admin_id}\" onClick=\"Page.Go(this.href); return false\">{$row_admin['user_search_pref']}</a><br />{$admin_del_href}</div>";		
				}
			}
			
			$tpl->set('{admins}', $adminO);
		}
		
		if($row['flist']){
			$flist_arr = str_replace('||', '|', explode('|', $row['flist']));
			foreach($flist_arr as $flist_id){
				if($flist_id){
					$row_admin = $db->super_query("SELECT user_search_pref, user_photo FROM `".PREFIX."_users` WHERE user_id = '{$flist_id}'");
					if($row_admin['user_photo']) $ava_admin = "/uploads/users/{$flist_id}/50_{$row_admin['user_photo']}";
					else $ava_admin = "{theme}/images/no_ava_50.png";
					$admin_del_href = "<a href=\"/\" onClick=\"clubs.okfevs('{$row['id']}', '{$flist_id}'); return false\"><small>Добавить</small></a>  <a href=\"/\" onClick=\"clubs.nofevs('{$row['id']}', '{$flist_id}'); return false\"><small>Отклонить</small></a>";
					$fev_clubs .= "<div class=\"public_oneadmin\" id=\"admin{$flist_id}\"><a href=\"/u{$flist_id}\" onClick=\"Page.Go(this.href); return false\"><img src=\"{$ava_admin}\" align=\"left\" width=\"32\" /></a><a href=\"/u{$flist_id}\" onClick=\"Page.Go(this.href); return false\">{$row_admin['user_search_pref']}</a><br />{$admin_del_href}</div>";		
				}
			}
			
			$tpl->set('{fev-clubs}', $fev_clubs);
		} else $tpl->set('{fev-clubs}', '<div class="info_center">Заявки отсутствуют!</div>');

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
				$tpl->set('{records}', '<div class="wall_none" style="border-top:0px">Записей пока нет.</div>');
			else
				$tpl->set('{records}', '<div class="wall_none">Записей пока нет.</div>');
		}
		
		
		
		if($user_privacy_loting['val_wall1'] == 1){
			$tpl->set_block("'\\[wall_privacy\\](.*?)\\[/wall_privacy\\]'si","");
		} else {
			$tpl->set('[wall_privacy]', '');
			$tpl->set('[/wall_privacy]', '');
		}
		
		if($user_privacy_loting['val_board'] == 1){
			$tpl->set_block("'\\[board_privacy\\](.*?)\\[/board_privacy\\]'si","");
		} else {
			$tpl->set('[board_privacy]', '');
			$tpl->set('[/board_privacy]', '');
		}
		
		if($user_privacy_loting['val_board'] == 3 and $public_admin == false){
			$tpl->set_block("'\\[topic_privacy\\](.*?)\\[/topic_privacy\\]'si","");
		} else {
			$tpl->set('[topic_privacy]', '');
			$tpl->set('[/topic_privacy]', '');
		}
		
		if($user_privacy_loting['val_wall1'] == 3 and $public_admin == false) {
			$tpl->set_block("'\\[wall_privacy_admin\\](.*?)\\[/wall_privacy_admin\\]'si","");
		} else {
			$tpl->set('[wall_privacy_admin]', '');
			$tpl->set('[/wall_privacy_admin]', '');
		}
		
		//Выводим информцию о том кто смотрит страницу для себя
		$tpl->set('{viewer-id}', $user_id);
			
		if(!$row['adres']) $row['adres'] = 'club'.$row['id'];
		$tpl->set('{adres}', $row['adres']);
		
		//Обложки групп
			$rrw = $db->super_query("SELECT cover, cover_pos FROM `".PREFIX."_clubs` WHERE id = '{$row['id']}'");
			$cpos = str_replace(" ", "", $rrw['cover_pos']);
			if($rrw['cover']){
				$tpl->set('{cov}', 'style="top:-'.$cpos.'px;position:relative"');
				$tpl->set('{cover}', 'http://'.$_SERVER['HTTP_HOST'].'/uploads/clubs/'.$rrw['cover']);
				$tpl->set('{cover_pos}', $cpos);
				$tpl->set('{covs}', 'no_display');
				$tpl->set('{cove}', 'style="cursor:default"');
				$tpl->set('{covss}', '');
				$tpl->set('{covses}', 'style="position:absolute;z-index:2;display:block;margin-left:397px"');
				$tpl->set('{ocov}', '<div class="cover_all_user"><img src="http://'.$_SERVER['HTTP_HOST'].'/uploads/clubs/'.$rrw['cover'].'" width="794" id="cover_img" style="top:-'.$cpos.'px;position:relative" /></div>');
				$tpl->set('{ocovss}', '<div class="cover_newava" >');
				$tpl->set('{ocovs}', '</div>');
			} else {
				$tpl->set('{ocovss}', '');
				$tpl->set('{ocov}', '');
				$tpl->set('{ocovs}', '');
				$tpl->set('{covses}', '');
				$tpl->set('{cove}', '');
				$tpl->set('{covss}', 'no_display');
				$tpl->set('{covs}', '');
				$tpl->set('{cov}', '');
				$tpl->set('{cover}', '');
				$tpl->set('{cover_pos}', '');
			}

		//Аудиозаписи
		if($row['audio_num']){
			$sql_audios = $db->super_query("SELECT SQL_CALC_FOUND_ROWS url, artist, name FROM `".PREFIX."_clubs_audio` WHERE public_id = '{$row['id']}' ORDER by `adate` DESC LIMIT 0, 3", 1, "clubs/audio{$row['id']}");
			$jid = 0;
			foreach($sql_audios as $row_audios){
				$jid++;
				
				$row_audios['artist'] = stripslashes($row_audios['artist']);
				$row_audios['name'] = stripslashes($row_audios['name']);
				
				$audios .= "<div class=\"audio_onetrack\"><div class=\"audio_playic cursor_pointer fl_l\" onClick=\"music.newStartPlay('{$jid}')\" id=\"icPlay_{$jid}\"></div><span id=\"music_{$jid}\" data=\"{$row_audios['url']}\"><a href=\"/?go=search&query={$row_audios['artist']}&type=5\" onClick=\"Page.Go(this.href); return false\"><b><span id=\"artis{aid}\">{$row_audios['artist']}</span></b></a> &ndash; <span id=\"name{aid}\">{$row_audios['name']}</span></span><div id=\"play_time{$jid}\" class=\"color777 fl_r no_display\" style=\"margin-top:2px;margin-right:5px\"></div> <div class=\"clear\"></div><div class=\"player_mini_mbar fl_l no_display\" id=\"ppbarPro{$jid}\" style=\"width:178px\"></div> </div>";
				
			}
			
			$tpl->set('{audios}', $audios);
			$tpl->set('{audio-num}', $row['audio_num']);
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
		
		//Обсуждения
		if($row['boards_num']){
			$sql_boards = $db->super_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".PREFIX."_clubs_boards` WHERE public_id = '{$row['id']}' ORDER by `favourite` DESC LIMIT 0, 5", 1);
			foreach($sql_boards as $row_topic){
				$sql_name = $db->super_query("SELECT user_name, user_lastname, alias FROM `".PREFIX."_users` WHERE user_id = '{$row_topic['last_author']}'");
				if($sql_name['alias']) $goto = $sql_name['alias'];
				else $goto = $row_topic['last_author'];
				$topics .= '<div style="padding: 10px;line-height: 17px;border-bottom: 1px solid #E0EAEF;"><div class="fl_l"><a href="/topic-'.$row['id'].'_'.$row_topic['id'].'" onclick="Page.Go(this.href); return false"><b>'.$row_topic['title'].'</b></a><br>'.$row_topic['message_num'].' '.gram_record($row_topic['message_num'],'msg').'.';
				if($row_topic['message_num']) $topics .= 'Последнее от <a href="/'.$goto.'">'.gramatikName($sql_name['user_name']).' '.gramatikName($sql_name['user_lastname']).'</a>, '.megaDateNoTpl($row_topic['last_data']).'</div><div class="clear"></div> </div>';
				else $topics .= '</div><div class="clear"></div> </div>';
			}
			
			$tpl->set('{boards}', $topics);
			$tpl->set('{boards-num}', $row['boards_num']);
			$tpl->set('[boards]', '');
			$tpl->set('[/boards]', '');
			$tpl->set('[yestopic]', '');
			$tpl->set('[/yestopic]', '');
			$tpl->set_block("'\\[notopic\\](.*?)\\[/notopic\\]'si","");
			
		} else {
		
			$tpl->set('{boards}', '');
			$tpl->set('[notopic]', '');
			$tpl->set('[/notopic]', '');
			$tpl->set_block("'\\[yestopic\\](.*?)\\[/yestopic\\]'si","");
			$tpl->set('[boards]', '');
			$tpl->set('[/boards]', '');
		}
		
				$check_fave = $db->super_query("SELECT club_id FROM `".PREFIX."_fave` WHERE user_id = '{$user_info['user_id']}' AND club_id = '{$row['id']}'");
				if($check_fave){
					$tpl->set('[yes-fave]', '');
					$tpl->set('[/yes-fave]', '');
					$tpl->set_block("'\\[no-fave\\](.*?)\\[/no-fave\\]'si","");
				} else {
					$tpl->set('[no-fave]', '');
					$tpl->set('[/no-fave]', '');
					$tpl->set_block("'\\[yes-fave\\](.*?)\\[/yes-fave\\]'si","");
				}
		
		$tpl->compile('content');
	} else {
		$user_speedbar = $lang['no_infooo'];
		msgbox('', $lang['no_upage'], 'info');
	}
	
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>