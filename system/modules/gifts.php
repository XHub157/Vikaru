<?php
/* 
	Appointment: Подарки
	File: gifts.php 
	Данный код защищен авторскими правами
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$act = $_GET['act'];
	$user_id = $user_info['user_id'];

	switch($act){
		
		//################### Страница всех подарков ###################//
		case "view":
			NoAjaxQuery();
			$for_user_id = intval($_POST['user_id']);
			
			$section = intval($_POST['section']);
			if(!$section) $section = 1;
			
				$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS gid, img, price FROM `".PREFIX."_gifts_list` WHERE category = '{$section}' ORDER by `gid` DESC", 1);
		
				if(!$_POST['section']) $tpl->load_template('gifts/gifts_box.tpl');
			
				foreach($sql_ as $gift){
					$gift_list .= "<a class=\"gift_cell fl_l\" onmouseover=\"gifts.showGiftPrice('{$gift['gid']}','{$gift['price']} голос')\" onmouseout=\"gifts.hideGiftPrice()\" onClick=\"gifts.select('{$gift['img']}', '{$for_user_id}'); return false\" id=\"gift_{$gift['gid']}\"><img class=\"gift_img\" src=\"/uploads/gifts/{$gift['img']}.png\" width=\"96\" height=\"96\"></a>";
				}
				
				if(!$_POST['section']) {
					$row = $db->super_query("SELECT user_balance FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
					$tpl->set('{balance}', 'У вас <b>'.$row['user_balance'].' '.gram_record($row['user_balance'], 'votes').'</b>');
					$tpl->set('{top}', '50');
					$tpl->set('{gifts}', $gift_list);
					$tpl->set('{from}', $for_user_id);
					$tpl->compile('content');
				} else echo $gift_list;

			AjaxTpl();
			die();
		break;
		
		//################### Отправка подарка в БД ###################//
		case "send":
			NoAjaxQuery();
			$for_user_id = intval($_POST['for_user_id']);
			$gift = intval($_POST['gift']);
			$privacy = intval($_POST['privacy']);
			if($privacy == 0) $privacy = 1;
			else if($privacy == 1) $privacy = 2;
			else $privacy = 1;
			$msg = ajax_utf8(textFilter($_POST['msg']));
			$gifts = $db->super_query("SELECT price FROM `".PREFIX."_gifts_list` WHERE img = '".$gift."'");
			$str_date = time();
			
			//Выводим текущий баланс свой
			$row = $db->super_query("SELECT user_balance FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			if($gifts['price'] AND $user_id != $for_user_id){
				if($row['user_balance'] >= $gifts['price']){
					//Отправляем сообщение получателю
					$db->query("INSERT INTO `".PREFIX."_messages` SET theme = 'Подарок', text = '<img src=\"/uploads/gifts/{$gift}.png\">', for_user_id = '{$for_user_id}', from_user_id = '{$user_id}', date = '{$server_time}', pm_read = 'no', folder = 'inbox', history_user_id = '{$user_id}'");
					$dbid = $db->insert_id();

					//Сохраняем сообщение в папку отправленные
					$db->query("INSERT INTO `".PREFIX."_messages` SET theme = 'Подарок', text = '<img src=\"/uploads/gifts/{$gift}.png\">', for_user_id = '{$user_id}', from_user_id = '{$for_user_id}', date = '{$server_time}', pm_read = 'no', folder = 'outbox', history_user_id = '{$user_id}'");

					//Обновляем кол-во новых сообщения у получателя
					$db->query("UPDATE `".PREFIX."_users` SET user_pm_num = user_pm_num+1 WHERE user_id = '{$for_user_id}'");
					
					//Проверка на наличии созданого диалога у себя
					$check_im = $db->super_query("SELECT iuser_id FROM `".PREFIX."_im` WHERE iuser_id = '".$user_id."' AND im_user_id = '".$for_user_id."'");
					if(!$check_im)
						$db->query("INSERT INTO ".PREFIX."_im SET iuser_id = '".$user_id."', im_user_id = '".$for_user_id."', idate = '".$server_time."', all_msg_num = 1");
					else
						$db->query("UPDATE ".PREFIX."_im  SET idate = '".$server_time."', all_msg_num = all_msg_num+1 WHERE iuser_id = '".$user_id."' AND im_user_id = '".$for_user_id."'");
							
					//Проверка на наличии созданого диалога у получателя, а если есть то просто обновляем кол-во новых сообщений в диалоге
					$check_im_2 = $db->super_query("SELECT iuser_id FROM ".PREFIX."_im WHERE iuser_id = '".$for_user_id."' AND im_user_id = '".$user_id."'");
					if(!$check_im_2)
						$db->query("INSERT INTO ".PREFIX."_im SET iuser_id = '".$for_user_id."', im_user_id = '".$user_id."', msg_num = 1, idate = '".$server_time."', all_msg_num = 1");
					else
						$db->query("UPDATE ".PREFIX."_im  SET idate = '".$server_time."', msg_num = msg_num+1, all_msg_num = all_msg_num+1 WHERE iuser_id = '".$for_user_id."' AND im_user_id = '".$user_id."'");
							
					$db->query("INSERT INTO `".PREFIX."_gifts` SET uid = '{$for_user_id}', gift = '{$gift}', msg = '{$msg}', privacy = '{$privacy}', gdate = '{$str_date}', from_uid = '{$user_id}', status = 1");
					$db->query("UPDATE `".PREFIX."_users` SET user_balance = user_balance-{$gifts['price']} WHERE user_id = '{$user_id}'");
					$db->query("UPDATE `".PREFIX."_users` SET user_gifts = user_gifts+1 WHERE user_id = '{$for_user_id}'");
					$db->query("INSERT INTO `".PREFIX."_history` SET user_id = '{$user_id}', for_user_id = '{$for_user_id}', type = '1', price='{$gifts['price']}', date = '{$server_time}'");

					
						//Вставляем событие в моментальные оповещания
						$row_owner = $db->super_query("SELECT user_last_visit FROM `".PREFIX."_users` WHERE user_id = '{$for_user_id}'");
						$update_time = $server_time - 70;

						if($row_owner['user_last_visit'] >= $update_time){

							$action_update_text = "<img src=\"/uploads/gifts/{$gift}.png\" align=\"right\" width=\"50\">";
	
							$db->query("INSERT INTO `".PREFIX."_updates` SET for_user_id = '{$for_user_id}', from_user_id = '{$user_info['user_id']}', type = '7', date = '{$str_date}', text = '{$action_update_text}', user_photo = '{$user_info['user_photo']}', user_search_pref = '{$user_info['user_search_pref']}', lnk = '/gifts{$user_info['user_id']}'");

							mozg_create_cache("user_{$for_user_id}/updates", 1);

							}
					
					//Добавляем +1 юзеру для оповещания
					$cntCacheNews = mozg_cache("user_{$for_user_id}/new_gift");
					mozg_create_cache("user_{$for_user_id}/new_gift", ($cntCacheNews+1));
					
					mozg_mass_clear_cache_file("user_{$for_user_id}/profile_{$for_user_id}|user_{$for_user_id}/gifts");
					
					//Отправка уведомления на E-mail
					if($config['news_mail_6'] == 'yes'){
						$rowUserEmail = $db->super_query("SELECT user_name, user_email FROM `".PREFIX."_users` WHERE user_id = '".$for_user_id."'");
						if($rowUserEmail['user_email']){
							include_once ENGINE_DIR.'/classes/mail.php';
							$mail = new dle_mail($config);
							$rowMyInfo = $db->super_query("SELECT user_search_pref FROM `".PREFIX."_users` WHERE user_id = '".$user_id."'");
							$rowEmailTpl = $db->super_query("SELECT text FROM `".PREFIX."_mail_tpl` WHERE id = '6'");
							$rowEmailTpl['text'] = str_replace('{%user%}', $rowUserEmail['user_name'], $rowEmailTpl['text']);
							$rowEmailTpl['text'] = str_replace('{%user-friend%}', $rowMyInfo['user_search_pref'], $rowEmailTpl['text']);
							$rowEmailTpl['text'] = str_replace('{%rec-link%}', $config['home_url'].'gifts'.$for_user_id, $rowEmailTpl['text']);
							$mail->send($rowUserEmail['user_email'], 'Вам отправили новый подарок', $rowEmailTpl['text']);
						}
					}		
				} else
					echo '1';
			}
			die();
		break;
		
		//################### Удаление подарка ###################//
		case "delet":
				NoAjaxQuery();
			
			$gid = intval($_POST['gid']);
			$folders = $db->safesql($_POST['folders']);
			
			if($folder == 'inbox')
				$folder = 'inbox';
			else
				$folder = 'outbox';
			
			$row = $db->super_query("SELECT uid FROM `".PREFIX."_gifts` WHERE gid = '{$gid}'");
			die();
		break;
		
		//################### Удаление подарка ###################//
		case "del":
			NoAjaxQuery();
			$gid = intval($_POST['gid']);
			$row = $db->super_query("SELECT uid FROM `".PREFIX."_gifts` WHERE gid = '{$gid}'");
			if($user_id == $row['uid']){
				$db->query("DELETE FROM `".PREFIX."_gifts` WHERE gid = '{$gid}'");
				$db->query("UPDATE `".PREFIX."_users` SET user_gifts = user_gifts-1 WHERE user_id = '{$user_id}'");
				mozg_mass_clear_cache_file("user_{$user_id}/profile_{$user_id}|user_{$user_id}/gifts");
			}
			die();
		break;
		
		default:
		
			//################### Всех подарков пользователя ###################//
			$metatags['title'] = $lang['gifts'];
			$uid = intval($_GET['uid']);
			
			$row = $db->super_query("SELECT user_id, user_privacy FROM `".PREFIX."_users` WHERE user_id = '{$uid}'");
			
			$user_privacy = xfieldsdataload($row['user_privacy']);
			
			$check_friend = CheckFriends($row['user_id']);
			
			if($user_privacy['val_gift'] == 1 OR $user_privacy['val_gift'] == 2 AND $check_friend OR $user_id == $uid){
			
			if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
			$gcount = 15;
			$limit_page = ($page-1)*$gcount;
			
			$owner = $db->super_query("SELECT user_name, user_gifts FROM `".PREFIX."_users` WHERE user_id = '{$uid}'");
			
			$tpl->load_template('gifts/head.tpl');
			$tpl->set('{uid}', $uid);
			if($user_id == $uid){
				$tpl->set('[owner]', '');
				$tpl->set('[/owner]', '');
				$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
			} else {
				$tpl->set('[not-owner]', '');
				$tpl->set('[/not-owner]', '');
				$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
			}
			$tpl->set('{name}', gramatikName($owner['user_name']));
			$tpl->set('{gifts-num}', '<span id="num">'.$owner['user_gifts'].'</span> '.gram_record($owner['user_gifts'], 'gifts'));
			if($owner['user_gifts']){
				$tpl->set('[yes]', '');
				$tpl->set('[/yes]', '');
				$tpl->set_block("'\\[no\\](.*?)\\[/no\\]'si","");
			} else {
				$tpl->set('[no]', '');
				$tpl->set('[/no]', '');
				$tpl->set_block("'\\[yes\\](.*?)\\[/yes\\]'si","");
			}

			if($_GET['new'] AND $user_id == $uid){
				$tpl->set('[new]', '');
				$tpl->set('[/new]', '');
				$tpl->set_block("'\\[no-new\\](.*?)\\[/no-new\\]'si","");
				$sql_where = "AND status = 1";
				$gcount = 50;
				mozg_create_cache("user_{$user_id}/new_gift", '');
			} else {
				$tpl->set('[no-new]', '');
				$tpl->set('[/no-new]', '');
				$tpl->set_block("'\\[new\\](.*?)\\[/new\\]'si","");
			}
			
			$tpl->compile('info');
			if($owner['user_gifts']){
				$sql_ = $db->super_query("SELECT tb1.gid, gift, from_uid, msg, gdate, privacy, tb2.user_search_pref, user_photo, user_last_visit FROM `".PREFIX."_gifts` tb1, `".PREFIX."_users` tb2 WHERE tb1.uid = '{$uid}' AND tb1.from_uid = tb2.user_id {$sql_where} ORDER by `gdate` DESC LIMIT {$limit_page}, {$gcount}", 1);
				$tpl->load_template('gifts/gift.tpl');
				foreach($sql_ as $row){
					$tpl->set('{id}', $row['gid']);
					$tpl->set('{uid}', $row['from_uid']);
					if($row['privacy'] == 1 OR $user_id == $row['from_uid'] OR $user_id == $uid AND $row['privacy'] != 3){
						$tpl->set('{author}', $row['user_search_pref']);
						$tpl->set('{msg}', stripslashes($row['msg']));
						$tpl->set('[link]', '<a href="/u'.$row['from_uid'].'" onClick="Page.Go(this.href); return false">');
						$tpl->set('[/link]', '</a>');
						OnlineTpl($row['user_last_visit']);
					} else {
						$tpl->set('{author}', 'Неизвестный отправитель');
						$tpl->set('{msg}', '');
						$tpl->set('{online}', '');
						$tpl->set('[link]', '');
						$tpl->set('[/link]', '');
					}
					$tpl->set('{gift}', $row['gift']);
					megaDate($row['gdate'], 1, 1);
					$tpl->set('[privacy]', '');
					$tpl->set('[/privacy]', '');
					if($row['privacy'] == 3 AND $user_id == $uid){
						$tpl->set('{msg}', stripslashes($row['msg']));
						$tpl->set_block("'\\[privacy\\](.*?)\\[/privacy\\]'si","");
					}
					if($row['privacy'] == 1 OR $user_id == $row['from_uid'] OR $user_id == $uid AND $row['privacy'] != 3)
						if($row['user_photo'])
							$tpl->set('{ava}', '/uploads/users/'.$row['from_uid'].'/50_'.$row['user_photo']);
						else
							$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
					else
						$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
						
					if($user_id == $uid){
						$tpl->set('[owner]', '');
						$tpl->set('[/owner]', '');
					} else
						$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
						
					if($sql_where)
						$db->query("UPDATE `".PREFIX."_gifts` SET status = 0 WHERE gid = '{$row['gid']}'");
						
					$tpl->compile('content');
				}
				navigation($gcount, $owner['user_gifts'], "/gifts{$uid}?page=");
				
				if($sql_where AND !$sql_)
					msgbox('', '<br /><br />Новых подарков еще нет.<br /><br /><br />', 'info_2');
			}
			
			} else {
			
				$tpl->load_template('info_red.tpl');
				$tpl->set('{error}','Страница защищена настройками приватности!');
				$tpl->compile('content');
			
			}
	}
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>