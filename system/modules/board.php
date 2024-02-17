<?php
/* 
	Appointment: Баланс
	File: balance.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){

	$act = $_GET['act'];
	$user_id = $user_info['user_id'];
	
	switch($act) {
		
		case "send":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			$id = intval($_POST['id']);
			$text = ajax_utf8(textFilter($_POST['text']));
			
			$row = $db->super_query("SELECT title FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
			
			if($row) {
			
				$db->query("INSERT INTO `".PREFIX."_clubs_topic` (boards_id,text,user_id,data) values('".$aid."','".$text."','".$user_id."','".$server_time."')");
				$db->query("UPDATE `".PREFIX."_clubs_boards` SET message_num = message_num+1, last_data = '{$server_time}', last_author = '{$user_id}'  WHERE id = '{$aid}'");
				$row_rew = $db->super_query("SELECT user_search_pref,user_photo,alias FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
				$row_sql = $db->super_query("SELECT id FROM `".PREFIX."_clubs_topic` ORDER by `id` DESC LIMIT 1");
				$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$id}'");
				
				if($row_rew['alias']) $alias = $row_rew['alias'];
				else $alias = 'id'.$user_id;
				
				$tpl->load_template('club/ltopic.tpl');
				$tpl->set('{alias}', $alias);
				$tpl->set('{ava-photo}', $row_rew['user_photo']);
				$tpl->set('{uids}', $user_id);
				$tpl->set('{pref-name}', $row_rew['user_search_pref']);
				$tpl->set('{text}', $text);
				$tpl->set('{date}', megaDateNoTpl($server_time));
				$tpl->set('{board-id}', $row_sql['id']);
				$tpl->set('{redaktirovanie}', '');
				$tpl->set('[owner-topic]', '');
				$tpl->set('[/owner-topic]', '');
				$tpl->set('[not-back]', '');
				$tpl->set('[/not-back]', '');
				$tpl->compile('content');
				AjaxTpl();
			
			}
		
			die();
		break;
		
		case "delete":
			NoAjaxQuery();
		
			$id = intval($_POST['id']);
			
			$row = $db->super_query("SELECT boards_id,user_id FROM `".PREFIX."_clubs_topic` WHERE id = '{$id}'");
			$sql_b = $db->super_query("SELECT public_id FROM `".PREFIX."_clubs_boards` WHERE id = '{$row['boards_id']}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($row) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false or $row['user_id'] == $user_id) {
					$db->query("UPDATE `".PREFIX."_clubs_topic` SET `del` = '1' WHERE id = '{$id}'");
					$sql_ = $db->super_query("SELECT user_id,data FROM `".PREFIX."_clubs_topic` WHERE boards_id = '{$row['boards_id']}' and del = '0' ORDER by `id` DESC");
					$db->query("UPDATE `".PREFIX."_clubs_boards` SET message_num = message_num-1, last_data = '{$sql_['data']}', last_author = '{$sql_['user_id']}'  WHERE id = '{$row['boards_id']}'");
				}
			}
		
			die();
		break;
		
		case "rewrite":
			NoAjaxQuery();
		
			$id = intval($_POST['id']);
			
			$row = $db->super_query("SELECT boards_id,user_id FROM `".PREFIX."_clubs_topic` WHERE id = '{$id}'");
			$sql_bd = $db->super_query("SELECT public_id FROM `".PREFIX."_clubs_boards` WHERE id = '{$row['boards_id']}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_bd['public_id']}'");
			
			if($row) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false or $row['user_id'] == $user_id) {
					$db->query("UPDATE `".PREFIX."_clubs_topic` SET `del` = '0' WHERE id = '{$id}'");
					$sql_ = $db->super_query("SELECT user_id,data FROM `".PREFIX."_clubs_topic` WHERE boards_id = '{$row['boards_id']}' and del = '0' ORDER by `id` DESC");
					$db->query("UPDATE `".PREFIX."_clubs_boards` SET message_num = message_num+1, last_data = '{$sql_['data']}', last_author = '{$sql_['user_id']}'  WHERE id = '{$row['boards_id']}'");
					$sql_a = $db->super_query("SELECT * FROM `".PREFIX."_clubs_topic` WHERE id = '{$id}' and del = '0'");
					$sql_b = $db->super_query("SELECT alias,user_photo,user_search_pref FROM `".PREFIX."_users` WHERE user_id = '{$sql_a['user_id']}'");
					
					if($sql_b['alias']) $alias = $sql_b['alias'];
					else $alias = 'id'.$sql_b['user_id'];
					
					$tpl->load_template('club/ltopic.tpl');
					$tpl->set('{alias}', $alias);
					$tpl->set('{ava-photo}', $sql_b['user_photo']);
					$tpl->set('{uids}', $user_id);
					$tpl->set('{pref-name}', $sql_b['user_search_pref']);
					$tpl->set('{text}', $sql_a['text']);
					$tpl->set('{date}', megaDateNoTpl($server_time));
					$tpl->set('{board-id}', $id);
					$tpl->set('{redaktirovanie}', '');
					$tpl->set('[owner-topic]', '');
					$tpl->set('[/owner-topic]', '');
					$tpl->set_block("'\\[not-back\\](.*?)\\[/not-back\\]'si","");
					$tpl->compile('content');
					AjaxTpl();
				
				}
			}
		
			die();
		break;
		
		case "favourite":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			
			$sql_b = $db->super_query("SELECT public_id FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($sql_b) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false) {
					$sql_ = $db->super_query("SELECT favourite FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
					if($sql_['favourite'] == 0) {
						$db->query("UPDATE `".PREFIX."_clubs_boards` SET favourite = 1 WHERE id = '{$aid}'");
						$tpl->load_template('info_f.tpl');
						$tpl->set('{error}','<b>Тема закреплена.</b><br>Теперь эта тема всегда будет выводиться над остальными в списке обсуждений.');
						$tpl->compile('content');
						AjaxTpl();
					} else {
						$db->query("UPDATE `".PREFIX."_clubs_boards` SET favourite = 0 WHERE id = '{$aid}'");
						$tpl->load_template('info_f.tpl');
						$tpl->set('{error}','<b>Тема больше не закреплена.</b><br>Эта тема будет выводиться на своем месте в списке обсуждений.');
						$tpl->compile('content');
						AjaxTpl();
					}
				}
			}
		
			die();
		break;
		
		case "changename":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			$title = ajax_utf8(textFilter($_POST['title']));
			
			$sql_b = $db->super_query("SELECT public_id,cid FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($sql_b) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false or $sql_b['cid'] == $user_id) {
					$db->query("UPDATE `".PREFIX."_clubs_boards` SET title = '{$title}' WHERE id = '{$aid}'");
				}
			}
		
			die();
		break;
		
		case "editmessage":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			$text = ajax_utf8(textFilter($_POST['text']));
			
			$sql_a = $db->super_query("SELECT boards_id,user_id FROM `".PREFIX."_clubs_topic` WHERE id = '{$aid}'");
			$sql_b = $db->super_query("SELECT public_id FROM `".PREFIX."_clubs_boards` WHERE id = '{$sql_a['boards_id']}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($sql_a) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false or $sql_a['user_id'] == $user_id) {
					$db->query("UPDATE `".PREFIX."_clubs_topic` SET text = '{$text}', edit_data = '{$server_time}' WHERE id = '{$aid}'");
					echo 'Последнее редактирование: '.megaDateNoTpl($server_time);
				}
			}
		
			die();
		break;
		
		case "deletetopic":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			
			$sql_b = $db->super_query("SELECT public_id,cid FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($sql_b) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false or $sql_b['cid'] == $user_id) {
					$db->query("DELETE FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
					$db->query("DELETE FROM `".PREFIX."_clubs_topic` WHERE boards_id = '{$aid}'");
					$db->query("UPDATE `".PREFIX."_clubs` SET boards_num = boards_num-1 WHERE id = '{$sql_b['public_id']}'");
					$tpl->load_template('info_f.tpl');
					$tpl->set('{error}','<b>Тема удалена.</b><br>Тема удалена из списка обсуждений группы.');
					$tpl->compile('content');
					AjaxTpl();
				}
			}
		
			die();
		break;
		
		case "closed":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			
			$sql_b = $db->super_query("SELECT public_id FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$sql_b['public_id']}'");
			
			if($sql_b) {
				if(stripos($sql_admin['admin'], "id{$user_id}|") !== false) {
					$sql_ = $db->super_query("SELECT close FROM `".PREFIX."_clubs_boards` WHERE id = '{$aid}'");
					if($sql_['close'] == 0) {
						$db->query("UPDATE `".PREFIX."_clubs_boards` SET close = 1 WHERE id = '{$aid}'");
						$tpl->load_template('info_f.tpl');
						$tpl->set('{error}','<b>Тема закрыта.</b><br>Участники сообщества больше не смогут оставлять сообщения в этой теме.');
						$tpl->compile('content');
						AjaxTpl();
					} else {
						$db->query("UPDATE `".PREFIX."_clubs_boards` SET close = 0 WHERE id = '{$aid}'");
						$tpl->load_template('info_f.tpl');
						$tpl->set('{error}','<b>Тема открыта.</b><br>Все участники сообщества смогут оставлять сообщения в этой теме.');
						$tpl->compile('content');
						AjaxTpl();
					}
				}
			}
		
			die();
		break;
		
		case "show":
			
			$aid = intval($_GET['aid']);
			$topicd = intval($_GET['topic']);
			
			$metatags['title'] = 'Просмотр темы';
			
			$sql_boards = $db->super_query("SELECT title,last_author,cid,close,favourite,close FROM `".PREFIX."_clubs_boards` WHERE id = '{$topicd}'");
			$sql_admin = $db->super_query("SELECT admin FROM `".PREFIX."_clubs` WHERE id = '{$aid}'");
			$row_ = $db->super_query("SELECT user_search_pref,alias,user_photo FROM `".PREFIX."_users` WHERE user_id = '{$sql_boards['cid']}'");
			$row_user = $db->super_query("SELECT user_photo FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			if($sql_boards) {
				$tpl->load_template('club/topic.tpl');
				
				$tpl->set('{group_id}', $aid);
				$tpl->set('{topic_id}', $topicd);
				$tpl->set('{title}', $sql_boards['title']);
				if($sql_boards['favourite']) $tpl->set('{favourite}', 'Не закреплять тему');
				else $tpl->set('{favourite}', 'Закрепить тему');
				if($sql_boards['close']) $tpl->set('{close}', 'Открыть тему');
				else $tpl->set('{close}', 'Закрыть тему');
				if($row_['alias']) $tpl->set('{status}', '<a href="/'.$row_['alias'].'">'.$row_['user_search_pref'].'</a>');
				else $tpl->set('{status}', '<a href="/'.$sql_boards['last_author'].'">'.$row_['user_search_pref'].'</a>');
				$row_topic = $db->super_query("SELECT * FROM `".PREFIX."_clubs_topic` WHERE boards_id = '{$topicd}' and del = '0' ORDER by `data` ASC",1);
				$tpl->compile('content');
				foreach($row_topic as $topic) {
					$row_rew = $db->super_query("SELECT user_search_pref,user_photo,alias FROM `".PREFIX."_users` WHERE user_id = '{$topic['user_id']}'");
					if($row_rew['alias']) $alias = $row_rew['alias'];
					else $alias = 'id'.$topic['user_id'];
					
					$tpl->load_template('club/ltopic.tpl');
					$tpl->set('{alias}', $alias);
					$tpl->set('{ava-photo}', $row_rew['user_photo']);
					$tpl->set('{uids}', $topic['user_id']);
					$tpl->set('{pref-name}', $row_rew['user_search_pref']);
					$tpl->set('{text}', $topic['text']);
					$tpl->set('{date}', megaDateNoTpl($topic['data']));
					$tpl->set('{board-id}',$topic['id']);
					if($topic['edit_data']) $tpl->set('{redaktirovanie}', 'Последнее редактирование: '.megaDateNoTpl($topic['edit_data']));
					else $tpl->set('{redaktirovanie}', '');
					if($topic['user_id'] == $user_id or stripos($sql_admin['admin'], "id{$user_id}|") !== false) {
						$tpl->set('[owner-topic]', '');
						$tpl->set('[/owner-topic]', '');
					} else {
						$tpl->set_block("'\\[owner-topic\\](.*?)\\[/owner-topic\\]'si","");
					}
					$tpl->set('[not-back]', '');
					$tpl->set('[/not-back]', '');
					$tpl->compile('content');
					
				}

				if(!$row_topic) {
					$tpl->load_template('info_f.tpl');
					$tpl->set('{error}', 'Сообщений не найдено!');
					$tpl->compile('content');
				}
				
				if($sql_boards['close']==0) {
					$tpl->load_template('club/bottom.tpl');
					$tpl->set('{user-id}', $user_id);
					$tpl->set('{ava}', $row_user['user_photo']);
					$tpl->set('{for_send}', $topicd);
					$tpl->set('{group_id}', $aid);
					$tpl->compile('content');
				}
			} else {$tpl->load_template('info_red.tpl');
					$tpl->set('{error}', 'Тема не найдена!');
					$tpl->compile('content');}
			
		break;
		
		case "new":
			NoAjaxQuery();
			$aid = intval($_GET['aid']);
			
			$metatags['title'] = 'Новая тема';
			
			$tpl->load_template('club/newtopic.tpl');
			$tpl->set('{group-id}', $aid);
			$tpl->compile('content');
			
		break;
		
		case "newtopic":
			NoAjaxQuery();
		
			$aid = intval($_POST['aid']);
			$title = ajax_utf8(textFilter($_POST['title']));
			$text = ajax_utf8(textFilter($_POST['text']));
			$sql_b = $db->super_query("SELECT id,admin,privacy FROM `".PREFIX."_clubs` WHERE id = '{$aid}'");
			$user_privacy_loting = xfieldsdataload($sql_b['privacy']);
			
			if($sql_b and (stripos($sql_b['admin'], "id{$user_id}|") !== false or $user_privacy_loting['val_board'] == 2) and $user_privacy_loting['val_board'] != 1) {
				$db->query("INSERT INTO `".PREFIX."_clubs_boards` (public_id,title,message_num,data_created,last_data,last_author,cid) values('".$aid."','".$title."','1','".$server_time."','".$server_time."','".$user_id."','".$user_id."')");
				$sql_q = $db->super_query("SELECT id FROM `".PREFIX."_clubs_boards` WHERE title = '{$title}' and cid = '{$user_id}' and public_id = '{$aid}'");
				$db->query("INSERT INTO `".PREFIX."_clubs_topic` (boards_id,text,user_id,data) values('".$sql_q['id']."','".$text."','".$user_id."','".$server_time."')");
				$db->query("UPDATE `".PREFIX."_clubs` SET boards_num = boards_num+1  WHERE id = '{$aid}'");
				echo $sql_q['id'];
			}
		
			die();
		break;
		
		default:
			
			$aid = intval($_GET['aid']);
			$metatags['title'] = 'Обсуждения';
			
			$row = $db->super_query("SELECT id,boards_num,adres,privacy,admin FROM `".PREFIX."_clubs` WHERE id = '{$aid}'");
			
			if($row) {
				$sql_boards = $db->super_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".PREFIX."_clubs_boards` WHERE public_id = '{$row['id']}' ORDER by `favourite` DESC", 1);
				$tpl->load_template('club/board.tpl');
				$tpl->set('{group-id}', $row['id']);
				if($row['adres']) $tpl->set('{alias}', $row['adres']);
				else $tpl->set('{alias}', 'club'.$row['id']);
				if($row['boards_num']) $tpl->set('{boards-num}', $row['boards_num'].' '.gram_record($row['boards_num'],'topics').'.');
				else $tpl->set('{boards-num}', 'ещё нет тем.');
				$user_privacy_loting = xfieldsdataload($row['privacy']);
				if($user_privacy_loting['val_board'] == 3 and stripos($row['admin'], "id{$user_id}|") !== false){
					$tpl->set('[topic_privacy]', '');
					$tpl->set('[/topic_privacy]', '');
				} else {
					$tpl->set_block("'\\[topic_privacy\\](.*?)\\[/topic_privacy\\]'si","");
				}
				
				foreach($sql_boards as $topic) {
				
					$row_ = $db->super_query("SELECT user_search_pref,user_photo FROM `".PREFIX."_users` WHERE user_id = '{$topic['last_author']}'");
					if($topic['favourite']!=0 and $topic['close']!=0) $topic_theme = 'тема закреплена и закрыта';
					elseif($topic['favourite']!=0) $topic_theme = 'тема закреплена';
					elseif($topic['close']!=0) $topic_theme = 'тема закрыта';
					else $topic_theme = '';
					$topics.= '<div class="support_questtitle"><div class="support_title_inpad fl_l"><a href="/topic-'.$row['id'].'_'.$topic['id'].'" onclick="Page.Go(this.href); return false"><b>'.$topic['title'].'</b></a><span class="color777" style="margin-left:5px;">'.$topic_theme.'</span><br><b>'.$topic['message_num'].'</b> '.gram_record($topic['message_num'],'msg').'.</div>';
					if($topic['message_num']) $topics.= '<a href="/topic-'.$row['id'].'_'.$topic['id'].'" onclick="Page.Go(this.href); return false" class="support_last_answer fl_r" style="font-size:11px"><img src="/uploads/users/'.$topic['last_author'].'/50_'.$row_['user_photo'].'" alt="" width="35">'.$row_['user_search_pref'].'<br><span class="color777">написал '.megaDateNoTpl($topic['last_data']).'</span></a>';
					else $topics.= '<div class="clear"></div> </div>';
				}
				
				$tpl->set('{topics}', $topics);
				
				$tpl->compile('content');
				
				if(!$sql_boards) {
					$tpl->load_template('info_boxd.tpl');
					$tpl->set('{error}','В сообществе ещё нет тем.');
					$tpl->compile('content');
				}
			} else {$tpl->load_template('info_red.tpl');
					$tpl->set('{error}','Группа не найдена!');
					$tpl->compile('content');}
			
		break;
		
	}
	
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>