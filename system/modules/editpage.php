<?php
/* 
	Appointment: Редактирование страницы
	File: editprofile.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$act = $_GET['act'];
	$pid = intval($_GET['pid']);
	$user_id = $user_info['user_id'];

	$row = $db->super_query("SELECT id, admin, title, descr, ban_reason, traf, ulist, photo, date, data_del, feedback, comments, discussion, links_num, videos_num, real_admin, rec_num, del, ban, adres, audio_num, type_public, web, date_created, privacy FROM `".PREFIX."_communities` WHERE id = '{$pid}'");

		switch($act){
		
			case "edit":
			
				if(stripos($row['admin'], "u{$user_id}|") !== false){
				
					$metatags['title'] = 'Редактирование информации';
					
					$tpl->load_template('epage/edit.tpl');
					
					$explode_type = explode('-',$row['type_public']);
					$explode_created = explode('-',$row['date_created']);
					
					$tpl->set('{pid}', $pid);
					$tpl->set('{title}', stripslashes($row['title']));
					$tpl->set('{descr}', stripslashes($row['descr']));
					$tpl->set('{website}', stripslashes($row['web']));
					$tpl->set('{edit-descr}', myBrRn(stripslashes($row['descr'])));
					if(!$row['adres']) $row['adres'] = 'public'.$row['id'];
					$tpl->set('{adres}', $row['adres']);
					
					$privaces = xfieldsdataload($row['privacy']);
					
					if($row['comments']) $tpl->set('{settings-comments}', 'comments');
					else $tpl->set('{settings-comments}', 'none');
					if($privaces['p_audio']) $tpl->set('{settings-audio}', 'audio');
					else $tpl->set('{settings-audio}', 'none');
					if($privaces['p_videos']) $tpl->set('{settings-videos}', 'videos');
					else $tpl->set('{settings-videos}', 'none');
					if($privaces['p_contact']) $tpl->set('{settings-contact}', 'contact');
					else $tpl->set('{settings-contact}', 'none');
					
					if($row['real_admin'] == $user_id){
						$tpl->set('[admin-del]', '');
						$tpl->set('[/admin-del]', '');
					} else $tpl->set_block("'\\[admin-del\\](.*?)\\[/admin-del\\]'si","");					
					
					$tpl->compile('content');
				
				} else {
					$user_speedbar = 'Информация';
					msgbox('', '<div style="margin:0 auto; width:370px;text-align:center;height:65px;font-weight:bold">Вы не имеете прав для редактирования данного сообщества.<br><br><div class="button_blue fl_l" style="margin-left:115px;"><a href="/public'.$pid.'" onClick="Page.Go(this.href); return false"><button>На страницу сообщества</button></a></div></div>', 'info_red');
				}
				
			break;
			
			case "saveGeneralInfo":
				NoAjaxQuery();
				$id = intval($_POST['id']);
				$title = ajax_utf8(textFilter($_POST['title'], false, true));
				$adres_page = ajax_utf8(strtolower(textFilter($_POST['adres'], false, true)));
				$descr = ajax_utf8(textFilter($_POST['descr'], 5000));
				$website = ajax_utf8(textFilter($_POST['website'], false, true));
				$comments = intval($_POST['comments']);
				$audio = intval($_POST['audio']);
				$contact = intval($_POST['contact']);
				$videos = intval($_POST['videos']);
								
				if($comments<0 or $comments>1) $comments = 0;
				if($audio<0 or $audio>1) $audio = 0;
				if($contact<0 or $contact>1) $contact = 0;
				if($videos<0 or $videos>1) $videos = 0;
				
				$privacy = "p_audio|{$audio}||p_contact|{$contact}||p_videos|{$videos}||";
				
				if(!preg_match("/^[a-zA-Z0-9_-]+$/", $adres_page)) $adress_ok = false;
				else $adress_ok = true;

				//Проверка на то, что действиие делает админ
				$checkAdmin = $db->super_query("SELECT admin FROM `".PREFIX."_communities` WHERE id = '".$id."'");

				if(stripos($checkAdmin['admin'], "u{$user_id}|") !== false AND isset($title) AND !empty($title) AND $adress_ok){
					if(preg_match('/public[0-9]/i', $adres_page))
						$adres_page = '';

					//Проверка на то, что адрес страницы свободен
					if($adres_page)
						$checkAdres = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_communities` WHERE adres = '".$adres_page."' AND id != '".$id."'");
						$checkAdresClubs = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_clubs` WHERE adres = '".$adres_page."'");
						$chek_user = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users` WHERE alias = '".$adres_page."' "); // Проверяем адреса у пользователей
					if(!$checkAdres['cnt'] AND !$chek_user['cnt'] AND !$checkAdresClubs['cnt'] OR $adres_page == ''){
						$db->query("UPDATE `".PREFIX."_communities` SET title = '".$title."', descr = '".$descr."', comments = '".$comments."', adres = '".$adres_page."', web = '".$website."', date_created = '".$date_created."', type_public = '".$type_public."', privacy = '".$privacy."' WHERE id = '".$id."'");
						if(!$adres_page)
							echo 'no_new';
					} else
						echo 'err_adres';

					mozg_clear_cache_folder('groups');
				}
				
				die();
			break;
			
			case "blacklist":

				if(stripos($row['admin'], "u{$user_id}|") !== false) {
				
					$cnt_pages = intval($_POST['page_cnt']);
					if(!$_POST['page_cnt']) $page_cnt = 25;
					else $page_cnt = $cnt_pages*25;
					
					$metatags['title'] = 'Участники';
					$tpl->load_template('epage/blacklist.tpl');
					$tpl->set('{pid}', $pid);
					if(!$row['adres']) $tpl->set('{adres}', 'public'.$row['id']);
					else $tpl->set('{adres}', $row['adres']);
					
					$blacklist = $db->super_query("SELECT COUNT(*) as cnt FROM `".PREFIX."_communities_blacklist` WHERE pid = '{$pid}'");
					
					if(!$blacklist['cnt']) {
						$tpl->set('{title}', 'Нет ни одного заблокированного пользователя');
						$tpl->set('[no_banned]','');
						$tpl->set('[/no_banned]','');
					} else {
						$tpl->set('{title}', 'В сообществе '.$blacklist['cnt'].' '.gram_record($blacklist['cnt'], 'blocked').' '.gram_record($blacklist['cnt'], 'blocked_users').'');
						$tpl->set_block("'\\[no_banned\\](.*?)\\[/no_banned\\]'si","");
					}
					$tpl->compile('content');
					
					$blacklistSQL = $db->super_query("SELECT tb1.user_id, admin, date, tb2.user_search_pref, alias, user_photo FROM `".PREFIX."_communities_blacklist` tb1, `".PREFIX."_users` tb2 WHERE tb2.user_id = tb1.user_id and pid = '{$pid}'",1);
					
					$tpl->result['content'] .= '<div id="group_bl_rows">';
					foreach($blacklistSQL as $row_) {
						$aSQL = $db->super_query("SELECT user_search_pref FROM `".PREFIX."_users` WHERE user_id = '{$row_['admin']}'");
						$tpl->load_template('epage/blackuser.tpl');
						if($row_['alias']) $alias = $row_['alias'];
						else $alias = 'id'.$row_['user_id'];
						$tpl->set('{alias}', $alias);
						if($row_['user_photo']) $avatar = '/uploads/users/'.$row_['user_id'].'/50_'.$row_['user_photo'];
						else $avatar = '/templates/Default/images/no_ava_50.png';
						$tpl->set('{ava}', $avatar);
						$tpl->set('{name}', $row_['user_search_pref']);
						$tpl->set('{nadmin}', $aSQL['user_search_pref']);
						$tpl->set('{date}', megaDateNoTpl($row_['date']));
						$tpl->set('{uid}', $row_['user_id']);
						$tpl->set('{pid}', $pid);
						$tpl->compile('content');
					}
					$tpl->result['content'] .= '</div>';
				
				} else {
					$user_speedbar = 'Информация';
					msgbox('', '<div style="margin:0 auto; width:370px;text-align:center;height:65px;font-weight:bold">Вы не имеете прав для редактирования данного сообщества.<br><br><div class="button_blue fl_l" style="margin-left:115px;"><a href="/public'.$pid.'" onClick="Page.Go(this.href); return false"><button>На страницу сообщества</button></a></div></div>', 'info_red');
				}
			
			break;
			
			case "link":

				if(stripos($row['admin'], "u{$user_id}|") !== false) {
				
					$cnt_pages = intval($_POST['page_cnt']);
					if(!$_POST['page_cnt']) $page_cnt = 25;
					else $page_cnt = $cnt_pages*25;
					
					$metatags['title'] = 'Ссылки';
					$tpl->load_template('epage/link.tpl');
					$tpl->set('{pid}', $pid);
					if(!$row['adres']) $tpl->set('{adres}', 'public'.$row['id']);
					else $tpl->set('{adres}', $row['adres']);
					
					$links = $db->super_query("SELECT COUNT(*) as cnt FROM `".PREFIX."_communities_links` WHERE pid = '{$pid}'");
					
					if(!$links['cnt']) {
						$tpl->set('{title}', 'На странице еще нет ссылок');
						$tpl->set('[no_links]','');
						$tpl->set('[/no_links]','');
					} else {
						$tpl->set('{title}', 'В сообществе '.$links['cnt'].' '.gram_record($links['cnt'], 'links'));
						$tpl->set_block("'\\[no_links\\](.*?)\\[/no_links\\]'si","");
					}
					$tpl->compile('content');
					
					$linkSQL = $db->super_query("SELECT link, photo, descr, name, id, outweb, screen FROM `".PREFIX."_communities_links` WHERE pid = '{$pid}' ORDER BY position ASC",1);
					
					$tpl->result['content'] .= '<div id="group_bl_rows"><div id="dragndrop" style="cursor:move"><ul>';
					foreach($linkSQL as $row_) {
						$tpl->load_template('epage/linktpl.tpl');
						$tpl->set('{lnk}', $row_['link']);
						$tpl->set('{ava}', $row_['photo']);
						$tpl->set('{name}', $row_['name']);
						$tpl->set('{descr}', $row_['descr']);
						$tpl->set('{ids}', $row_['id']);
						$tpl->set('{screen}', $row_['screen']);
						if($row_['outweb']) $tp = 2;
						else $tp = 1;
						$tpl->set('{types}', $tp);
						$tpl->compile('content');
					}
					$tpl->result['content'] .= '</ul></div></div>';
			
				} else {
					$user_speedbar = 'Информация';
					msgbox('', '<div style="margin:0 auto; width:370px;text-align:center;height:65px;font-weight:bold">Вы не имеете прав для редактирования данного сообщества.<br><br><div class="button_blue fl_l" style="margin-left:115px;"><a href="/public'.$pid.'" onClick="Page.Go(this.href); return false"><button>На страницу сообщества</button></a></div></div>', 'info_red');
				}
			
			break;
			
			case "delblacklist":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
			
				$check = $db->super_query("SELECT id FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$id}' and pid = '{$pid}'");
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and $check and $row['real_admin'] != $id) {
				
					$db->query("DELETE FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$id}' and pid = '{$pid}'");
				
				} else echo "no_in_black_list";
				
				AjaxTpl();
				die();
			break;
			
			case "newblacklist":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
			
				$check = $db->super_query("SELECT id FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$id}' and pid = '{$pid}'");
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and !$check and $row['real_admin'] != $id) {
				
					$db->query("INSERT INTO `".PREFIX."_communities_blacklist` SET user_id = '{$id}', pid = '{$pid}', date = '{$server_time}', admin = '{$user_id}'");
				
				} else echo "in_black_list";
				
				AjaxTpl();
				die();
			break;
			
			case "users":
			
				$tab = $_GET['tab'];

				if(stripos($row['admin'], "u{$user_id}|") !== false) {
				
					if(!$_POST['page_cnt']) $page_cnt = 10;
					else $page_cnt = $_POST['page_cnt']*10;
					$explode_admins = array_slice(str_replace('|', '', explode('u', $row['admin'])), 0, $page_cnt);
					unset($explode_admins[0]);
					$explode_users = array_slice(str_replace('|','',explode('||', $row['ulist'])), 0, $page_cnt);
					
					$metatags['title'] = 'Участники';
					if(!$_POST['page_cnt']) {
						$tpl->load_template('epage/users.tpl');
						$tpl->set('{pid}', $pid);
						if($tab == 'admin') {
							$tpl->set('{button_tab_b}', 'buttonsprofileSec');
							$tpl->set('{button_tab_a}', '');
							$tpl->set('{type}', 'admin');
							$tpl->set('[admin_page]','');
							$tpl->set('[/admin_page]','');
							$tpl->set_block("'\\[noadmin_page\\](.*?)\\[/noadmin_page\\]'si","");
						}
						else {
							$tpl->set('{button_tab_b}', '');
							$tpl->set('{button_tab_a}', 'buttonsprofileSec');
							$tpl->set('{type}', 'all');
							$tpl->set('[noadmin_page]','');
							$tpl->set('[/noadmin_page]','');
							$tpl->set_block("'\\[admin_page\\](.*?)\\[/admin_page\\]'si","");
						}
						if(!$row['adres']) $tpl->set('{adres}', 'public'.$row['id']);
						else $tpl->set('{adres}', $row['adres']);
						if($tab == 'admin') $tpl->set('{title}', 'В сообществе '.count($explode_admins).' '.gram_record($row['traf'], 'admins').'');
						else $tpl->set('{title}', 'В сообществе '.$row['traf'].' '.gram_record($row['traf'], 'apps').'');
						$tpl->set('{count}', $row['traf']);
					}
					
					if($_POST['page_cnt'])
						NoAjaxQuery();
					
					$tpl->compile('content');
					if(!$_POST['page_cnt']) $tpl->result['content'] .= '<table><tbody><tr><td id="all_users">';
					if(!$tab == 'admin') $foreach = $explode_users;
					else $foreach = $explode_admins;
					foreach($foreach as $user) {
						$p_user = $db->super_query("SELECT user_search_pref, user_photo, alias, user_last_visit FROM `".PREFIX."_users` WHERE user_id = '{$user}'");
						$a_user = $db->super_query("SELECT level FROM `".PREFIX."_communities_admins` WHERE user_id = '{$user}'");
						$tpl->load_template('epage/user.tpl');
						$tpl->set('{uid}', $user);
						$tpl->set('{name}', $p_user['user_search_pref']);
						$tpl->set('{ava}', $p_user['user_photo']);
						if($a_user) {
							if($row['real_admin'] == $user) $tpl->set('{tags}', '<b>Создатель</b>');
							else $tpl->set('{tags}');
							$tpl->set('{view_tags}','');
						} else {$tpl->set('{view_tags}','no_display');$tpl->set('{tags}', '');}
						if($p_user['user_last_visit'] >= $online_time) {
							$tpl->set('[online]','');
							$tpl->set('[/online]','');
						} else $tpl->set_block("'\\[online\\](.*?)\\[/online\\]'si","");
						if($p_user['alias']) $alias = $p_user['alias'];
						else $alias = 'u'.$user;
						$tpl->set('{adres}', $alias);
						if($p_user['user_photo']) $avatar = '/uploads/users/'.$user.'/100_'.$p_user['user_photo'].'';
						else $avatar = '/templates/Default/images/100_no_ava.png';
						$tpl->set('{ava_photo}', $avatar);
						if(in_array($user, $explode_admins)) {
							if($user != $row['real_admin']) {
								$tpl->set('[yes_admin]','');
								$tpl->set('[/yes_admin]','');
							} else $tpl->set_block("'\\[yes_admin\\](.*?)\\[/yes_admin\\]'si","");
							$tpl->set_block("'\\[no_admin\\](.*?)\\[/no_admin\\]'si","");
						} else {
							if($user != $row['real_admin']) {
								$tpl->set('[no_admin]','');
								$tpl->set('[/no_admin]','');
							} else $tpl->set_block("'\\[no_admin\\](.*?)\\[/no_admin\\]'si","");
							$tpl->set_block("'\\[yes_admin\\](.*?)\\[/yes_admin\\]'si","");
						}
						$tpl->compile('content');
					}
					if(!$_POST['page_cnt']) $tpl->result['content'] .= '</td></tr></tbody></table>';
				
					if($_POST['page_cnt']){
						AjaxTpl();
						exit;
					}
				
				} else {
					$user_speedbar = 'Информация';
					msgbox('', '<div style="margin:0 auto; width:370px;text-align:center;height:65px;font-weight:bold">Вы не имеете прав для редактирования данного сообщества.<br><br><div class="button_blue fl_l" style="margin-left:115px;"><a href="/public'.$pid.'" onClick="Page.Go(this.href); return false"><button>На страницу сообщества</button></a></div></div>', 'info_red');
				}
			
			break;
			
			case "editadmin":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
				$type = ajax_utf8(textFilter($_POST['type'], false, true));
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and stripos($row['admin'], "u{$id}|") !== false or $type == 'newadmin' and $row['real_admin'] != $id) {
				
					$rows = $db->super_query("SELECT user_search_pref, alias, user_photo FROM `".PREFIX."_users` WHERE user_id = '{$id}'");
					$a_row = $db->super_query("SELECT level FROM `".PREFIX."_communities_admins` WHERE user_id = '{$id}' and pid = '{$pid}'");
					$feed_row = $db->super_query("SELECT office, fphone, femail, visible FROM `".PREFIX."_communities_feedback` WHERE fuser_id = '{$id}' and cid = '{$pid}'");
					if($rows['alias']) $alias = $rows['alias'];
					else $alias = $id;
					if($feed_row['femail'] == '') {$dh = 'no_display'; $dc = '';}
					else {$dh = ''; $dc = 'no_display';}
					if($feed_row['fphone'] == '') {$dhp = 'no_display'; $dcp = '';}
					else {$dhp = ''; $dcp = 'no_display';}
					if($a_row['level'] == 1) $ok1 = 'on';
					else if($a_row['level'] == 2) $ok2 = 'on';
					else if($a_row['level'] == 3 or $type == 'newadmin') $ok3 = 'on';
					if($feed_row['visible']) {$contblock = 'blockcontact';}
					else {$contblock = '';}
					if($type == 'newadmin') $rowlevel = 3;
					else $rowlevel = $a_row['level'];
					if($rows['user_photo']) $avatars = '/uploads/users/'.$id.'/50_'.$rows['user_photo'].'';
					else $avatars = '/templates/Default/images/no_ava_50.png';
					$f = "'";
					if($type == 'editadmin' or $type == 'newadmin') {$blocklevel = '
	<div class="videos_text" style="margin-bottom: 5px;">Уровень полномочий</div><input id="value_type" type="hidden" value="'.$rowlevel.'"/><div>
	<div class="radiobtn settings_reason '.$ok3.'" onclick="radiobtn.select('.$f.'value_type'.$f.',3);" style="padding-top:10px;"><div></div>Модератор</div><div class="groups_create_about">Может удалять добавленные пользователями материалы, управлять черным списком сообщества</div></div><br>
	<div><div class="radiobtn settings_reason '.$ok2.'" onclick="radiobtn.select('.$f.'value_type'.$f.',2);"><div></div>Редактор</div><div class="groups_create_about">Может писать от имени сообщества, добавлять, удалять и редактировать контент, обновлять главную фотографию</div>
	</div><br><div><div class="radiobtn settings_reason '.$ok1.'" onclick="radiobtn.select('.$f.'value_type'.$f.',1);"><div></div>Администратор</div><div class="groups_create_about">Может назначать и снимать администраторов, изменять название и адрес сообщества</div></div>';$type1 = 'назначить';$type2 = 'руководителем сообщества';}
					else {$blocklevel = '<div style="margin-top:-10px;"></div>';$type1 = 'убрать';$type2 = 'из руководителей сообщества';}
					$temp = '
	<div style="padding:15px"><script type="text/javascript">$(document).ready(function(){myhtml.checked(["'.$contblock.'"]);});</script><div class="gedit_admbox_top clear_fix"><a href="/'.$id.'" class="gedit_admbox_thumb fl_l" target="_blank"><img class="gedit_admbox_img" src="'.$avatars.'"></a>
	<div class="gedit_admbox_info fl_l">Вы собираетесь '.$type1.' <a href="/'.$id.'" target="_blank">'.$rows['user_search_pref'].'</a> '.$type2.'.</div>
	</div>'.$blocklevel.'<div class="html_checkbox" id="blockcontact" onclick="myhtml.checkbox(this.id);" style="margin-top: 15px;">Отображать в блоке контактов</div><div class="clear"></div><div id="gedit_admbox_contact_info" class="no_display">
	<div class="gedit_admbox_contact_row clear_fix"><input type="text" id="gedit_admbox_position" class="inpst fl_l" maxlength="100"  style="width:170px;" value="'.$feed_row['office'].'" /><div class="gedit_admbox_contact_desc fl_l">укажите должность</div>
	</div><div class="gedit_admbox_contact_row clear_fix '.$dh.'" id="gedit_admbox_email_row"><input type="text" id="gedit_admbox_email" class="inpst fl_l" maxlength="100"  style="width:170px;" value="'.$feed_row['femail'].'" />
	<div class="gedit_admbox_contact_desc fl_l">укажите e-mail</div></div><div class="gedit_admbox_contact_fill '.$dc.'" id="gedit_admbox_email_fill"><a id="gedit_admbox_email" onclick="myhtml.toggleBlocks(this.id);" style="cursor:pointer">указать контактный e-mail</a></div>
	<div class="gedit_admbox_contact_row clear_fix '.$dhp.'" id="gedit_admbox_phone_row">
	<input type="text" id="gedit_admbox_phone" class="inpst fl_l" maxlength="100"  style="width:170px;" value="'.$feed_row['fphone'].'" />
	<div class="gedit_admbox_contact_desc fl_l">укажите телефон</div></div><div class="gedit_admbox_contact_fill '.$dcp.'" id="gedit_admbox_phone_fill"><a id="gedit_admbox_phone" onclick="myhtml.toggleBlocks(this.id);" style="cursor:pointer">указать контактный телефон</a></div></div></div>';
				
					echo $temp;
				
				} else echo "no_admin_lc";
				
				AjaxTpl();
				die();
			break;
			
			case "saveadmin":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
				$level = intval($_POST['level']);
				$blockf = intval($_POST['blockf']);
				$position = ajax_utf8(textFilter($_POST['position'], false, true));
				$email = ajax_utf8(textFilter($_POST['email'], false, true));
				$phone = ajax_utf8(textFilter($_POST['phone'], false, true));
				$type = ajax_utf8(textFilter($_POST['type'], false, true));
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and stripos($row['admin'], "u{$id}|") !== false or $type == 'newadmin' and $row['real_admin'] != $id) {
				
					if($level<0 or $level>3) $level = 3;
					if($blockf<0 or $blockf>1) $blockf = 0;
					
					if($type == 'newadmin' and stripos($row['admin'], "u{$id}|") === false) {
						$admins = $row['admin'].'u'.$id.'|';
						$db->query("UPDATE `".PREFIX."_communities` SET admin = '{$admins}' WHERE id = '{$pid}'");
					}
				
					$select = $db->super_query("SELECT level FROM `".PREFIX."_communities_admins` WHERE user_id = '{$id}' and pid = '{$pid}'");
					
					if(!$select) $db->query("INSERT INTO `".PREFIX."_communities_admins` SET user_id = '{$id}', pid = '{$pid}', level = '{$level}'");
					else $db->query("UPDATE `".PREFIX."_communities_admins` SET level = '{$level}' WHERE user_id = '{$id}' and pid = '{$pid}'");
					
					$feedback = $db->super_query("SELECT fuser_id, visible FROM `".PREFIX."_communities_feedback` WHERE fuser_id = '{$id}' and cid = '{$pid}'");
					
					if($blockf) {
						if(!$feedback) {
							$db->query("INSERT INTO `".PREFIX."_communities_feedback` SET fuser_id = '{$id}', cid = '{$pid}', visible = '1', fdate = '{$server_time}', office = '{$position}', fphone = '{$phone}', femail = '{$email}'");
							$db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback+1 WHERE id = '{$pid}'");
						} else {
							if($feedback['visible'] == 0) $db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback+1 WHERE id = '{$pid}'");
							$db->query("UPDATE `".PREFIX."_communities_feedback` SET visible = '1', fdate = '{$server_time}', office = '{$position}', fphone = '{$phone}', femail = '{$email}' WHERE fuser_id = '{$id}' and cid = '{$pid}'");
						}
					} else {
						if($feedback) {
							$db->query("UPDATE `".PREFIX."_communities_feedback` SET visible = '0' WHERE fuser_id = '{$id}' and cid = '{$pid}'");
							if($feedback['visible'] == 1) $db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback-1 WHERE id = '{$pid}'");
						}
					}
				
				} else echo "no_admin_lc";
				
				AjaxTpl();
				die();
			break;
			
			case "deleteadmin":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
				$blockf = intval($_POST['blockf']);
				$position = ajax_utf8(textFilter($_POST['position'], false, true));
				$email = ajax_utf8(textFilter($_POST['email'], false, true));
				$phone = ajax_utf8(textFilter($_POST['phone'], false, true));
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and stripos($row['admin'], "u{$id}|") !== false and $row['real_admin'] != $id) {
				
					if($blockf<0 or $blockf>1) $blockf = 0;
					
					$db->query("UPDATE `".PREFIX."_communities` SET admin = REPLACE(admin, 'u{$id}|', '') WHERE id = '{$pid}'");
					$db->query("DELETE FROM `".PREFIX."_communities_admins` WHERE user_id = '{$id}' and pid = '{$pid}'");
					
					$feedback = $db->super_query("SELECT fuser_id, visible FROM `".PREFIX."_communities_feedback` WHERE fuser_id = '{$id}' and cid = '{$pid}'");
					
					if($blockf) {
						if(!$feedback) {
							$db->query("INSERT INTO `".PREFIX."_communities_feedback` SET fuser_id = '{$id}', cid = '{$pid}', visible = '1', fdate = '{$server_time}', office = '{$position}', fphone = '{$phone}', femail = '{$email}'");
							$db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback+1 WHERE id = '{$pid}'");
						} else {
							if($feedback['visible'] == 0) $db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback+1 WHERE id = '{$pid}'");
							$db->query("UPDATE `".PREFIX."_communities_feedback` SET visible = '1', fdate = '{$server_time}', office = '{$position}', fphone = '{$phone}', femail = '{$email}' WHERE fuser_id = '{$id}' and cid = '{$pid}'");
						}
					} else {
						if($feedback) {
							$db->query("DELETE FROM `".PREFIX."_communities_feedback` WHERE fuser_id = '{$id}' and cid = '{$pid}'");
							$db->query("UPDATE `".PREFIX."_communities` SET feedback = feedback-1 WHERE id = '{$pid}'");
						}
					}
				
				} else echo "no_admin_lc";
				
				AjaxTpl();
				die();
			break;
			
			case "deleteusers":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and stripos($row['admin'], "u{$id}|") === false and stripos($row['ulist'], "|{$id}|") !== false and $row['real_admin'] != $id) {
					
					$db->query("UPDATE `".PREFIX."_communities` SET ulist = REPLACE(ulist, '|{$id}|', ''), traf = traf-1 WHERE id = '{$pid}'");
					$db->query("DELETE FROM `".PREFIX."_friends` WHERE user_id = '{$id}' and friend_id = '{$pid}' and subscriptions = '2'");
					$db->query("INSERT INTO `".PREFIX."_friends` SET user_id = '{$id}', friend_id = '{$pid}', subscriptions = '4', friends_date = '{$server_time}'");
					$db->query("UPDATE `".PREFIX."_users` SET user_public_num = user_public_num-1 WHERE user_id = '{$id}'");
				
				} else echo "no_users";
				
				AjaxTpl();
				die();
			break;
			
			case "rebornusers":
				NoAjaxQuery();
			
				$id = intval($_POST['id']);
			
				$check_users_reborn = $db->super_query("SELECT user_id FROM `".PREFIX."_friends` WHERE user_id = '{$id}' and friend_id = '{$pid}' and subscriptions = '4'");
			
				if(stripos($row['admin'], "u{$user_id}|") !== false and $check_users_reborn and $row['real_admin'] != $id) {
					
					$ulistnew = $row['ulist'].'|'.$id.'|';
					$db->query("UPDATE `".PREFIX."_communities` SET ulist = '{$ulistnew}', traf = traf+1 WHERE id = '{$pid}'");
					$db->query("DELETE FROM `".PREFIX."_friends` WHERE user_id = '{$id}' and friend_id = '{$pid}' and subscriptions = '4'");
					$db->query("INSERT INTO `".PREFIX."_friends` SET user_id = '{$id}', friend_id = '{$pid}', subscriptions = '2', friends_date = '{$server_time}'");
					$db->query("UPDATE `".PREFIX."_users` SET user_public_num = user_public_num+1 WHERE user_id = '{$id}'");
				
				} else echo "no_reborn_users";
				
				AjaxTpl();
				die();
			break;
			
			case "miniature":
				if(stripos($row['admin'], "u{$user_id}|") !== false) {
				$row = $db->super_query("SELECT photo FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
				
				if($row['photo']){
					$tpl->load_template('miniature/mainpage.tpl');
					$tpl->set('{pid}', $pid);
					$tpl->set('{ava}', $row['photo']);
					$tpl->compile('content');
					AjaxTpl();
				} else echo '1';
				} else echo "no_admins";
				exit();
			break;
			
			case "miniature_save":
				if(stripos($row['admin'], "u{$user_id}|") !== false) {
				$row = $db->super_query("SELECT photo,adres FROM `".PREFIX."_communities` WHERE id = '{$pid}'");

				$i_left = intval($_POST['i_left']);
				$i_top = intval($_POST['i_top']);
				$i_width = intval($_POST['i_width']);
				$i_height = intval($_POST['i_height']);

				if($row['photo'] AND $i_width >= 100 AND $i_height >= 100 AND $i_left >= 0 AND $i_height >= 0){
					include_once ENGINE_DIR.'/classes/images.php';

					$tmb = new thumbnail(ROOT_DIR."/uploads/groups/{$pid}/{$row['photo']}");
					$tmb->size_auto($i_width."x".$i_height, 0, "{$i_left}|{$i_top}");
					$tmb->jpeg_quality(100);
					$tmb->save(ROOT_DIR."/uploads/groups/{$pid}/100_{$row['photo']}");
					
					$tmb = new thumbnail(ROOT_DIR."/uploads/groups/{$pid}/100_{$row['photo']}");
					$tmb->size_auto("100x100", 1);
					$tmb->jpeg_quality(100);
					$tmb->save(ROOT_DIR."/uploads/groups/{$pid}/100_{$row['photo']}");
					
					$tmb = new thumbnail(ROOT_DIR."/uploads/groups/{$pid}/100_{$row['photo']}");
					$tmb->size_auto("50x50");
					$tmb->jpeg_quality(100);
					$tmb->save(ROOT_DIR."/uploads/groups/{$pid}/50_{$row['photo']}");
					
					if($row['adres']) echo $row['adres'];
					else echo 'public'.$pid;
					
				} else echo 'err';
				} else echo "no_admins";
				
				exit();
			break;
			
			case "vphoto":
				$uid = intval($_POST['uid']);
				
				if($_POST['type'])
					$photo = ROOT_DIR."/uploads/attach/{$uid}/{$_POST['photo']}";
				else
					$photo = ROOT_DIR."/uploads/groups/{$uid}/{$_POST['photo']}";
				
				if(file_exists($photo)){
					$tpl->load_template('photos/photo_profile.tpl');
					$tpl->set('{uid}', $uid);
					if($_POST['type'])
						$tpl->set('{photo}', "/uploads/attach/{$uid}/{$_POST['photo']}");
					else
						$tpl->set('{photo}', "/uploads/groups/{$uid}/{$_POST['photo']}");
					$tpl->set('{close-link}', $_POST['close_link']);
					$tpl->compile('content');
					AjaxTpl();
				} else
					echo 'no_photo';
			break;
			
		}
	
	$db->free();
	$tpl->clear();
	
} else {
	$user_speedbar = 'Информация';
	msgbox('', $lang['not_logged'], 'info');
}
?>