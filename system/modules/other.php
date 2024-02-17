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
	$id = intval($_GET['id']);
	$alias = $db->safesql($_GET['adres']);
	 $user_id = $user_info['user_id'];
	switch($act){	
//################### Сообщения ###################//
  case "newmsg":   		
			$mid = intval($_GET['mid']);
			if($mid){
				//SQL Запрос за вывод сообщения
				$row = $db->super_query("SELECT tb1.id, theme, text, from_user_id, history_user_id, date, pm_read, folder, attach, tell_uid, tell_date, public, tell_comm, tb2.user_search_pref, user_photo, user_last_visit FROM `".PREFIX."_messages` tb1, `".PREFIX."_users` tb2 WHERE tb1.id = '{$mid}' AND tb1.from_user_id = tb2.user_id AND tb1.for_user_id = '{$user_id}'");
				
				$folder = $row['folder'];

				if($row){
					$tpl->load_template('other/message_new.tpl');

					if($row['user_photo'])
						$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row['from_user_id'].'/100_'.$row['user_photo']);
					else
						$tpl->set('{ava}', '{theme}/images/100_no_ava.png');

					if($folder == 'inbox')
						$tpl->set('{name}', $row['user_search_pref']);
					else {
						$name_exp = explode(' ', $row['user_search_pref']);
						$tpl->set('{name}', gramatikName($name_exp[0]).' '.gramatikName($name_exp[1]));
					}
					
					//Прикрипленные файлы
					if($row['attach']){
						$attach_arr = explode('||', $row['attach']);
						$cnt_attach = 1;
						$cnt_attach_link = 1;
						$jid = 0;
						$attach_result = '';
						foreach($attach_arr as $attach_file){
							$attach_type = explode('|', $attach_file);
							
							//Фото со стены сообщества
							if($attach_type[0] == 'photo' AND file_exists(ROOT_DIR."/uploads/groups/{$row['tell_uid']}/photos/c_{$attach_type[1]}")){
								$attach_result .= "<img id=\"photo_wall_{$row['id']}_{$cnt_attach}\" src=\"/uploads/groups/{$row['tell_uid']}/photos/{$attach_type[1]}\" style=\"margin-top:3px;margin-right:3px\" align=\"left\" onClick=\"groups.wall_photo_view('{$row['id']}', '{$row['tell_uid']}', '{$attach_type[1]}', '{$cnt_attach}')\" class=\"cursor_pointer page_num{$row['id']}\" />";
								
								$cnt_attach++;
								
								$resLinkTitle = '';
								
							//Фото со стены юзера
							} elseif($attach_type[0] == 'photo_u'){
								if($row['history_user_id'] == $user_id) $attauthor_user_id = $user_id;
								elseif($row['tell_uid']) $attauthor_user_id = $row['tell_uid'];
								else $attauthor_user_id = $row['from_user_id'];

								if($attach_type[1] == 'attach' AND file_exists(ROOT_DIR."/uploads/attach/{$attauthor_user_id}/c_{$attach_type[2]}")){
									if($cnt_attach < 2)
										$attach_result .= "<div class=\"profile_wall_attach_photo cursor_pointer page_num{$row['id']}\" onClick=\"groups.wall_photo_view('{$row['id']}', '{$attauthor_user_id}', '{$attach_type[1]}', '{$cnt_attach}', 'photo_u')\"><img id=\"photo_wall_{$row['id']}_{$cnt_attach}\" src=\"/uploads/attach/{$attauthor_user_id}/{$attach_type[2]}\" align=\"left\" /></div>";
									else
										$attach_result .= "<img id=\"photo_wall_{$row['id']}_{$cnt_attach}\" src=\"/uploads/attach/{$attauthor_user_id}/c_{$attach_type[2]}\" style=\"margin-top:3px;margin-right:3px\" align=\"left\" onClick=\"groups.wall_photo_view('{$row['id']}', '', '{$attach_type[1]}', '{$cnt_attach}')\" class=\"cursor_pointer page_num{$row['id']}\" />";
										
									$cnt_attach++;
								} elseif(file_exists(ROOT_DIR."/uploads/users/{$attauthor_user_id}/albums/{$attach_type[2]}/c_{$attach_type[1]}")){
									if($cnt_attach < 2)
										$attach_result .= "<div class=\"profile_wall_attach_photo cursor_pointer page_num{$row['id']}\" onClick=\"groups.wall_photo_view('{$row['id']}', '{$attauthor_user_id}', '{$attach_type[1]}', '{$cnt_attach}', 'photo_u')\"><img id=\"photo_wall_{$row['id']}_{$cnt_attach}\" src=\"/uploads/users/{$attauthor_user_id}/albums/{$attach_type[2]}/{$attach_type[1]}\" align=\"left\" /></div>";
									else
										$attach_result .= "<img id=\"photo_wall_{$row['id']}_{$cnt_attach}\" src=\"/uploads/users/{$attauthor_user_id}/albums/{$attach_type[2]}/c_{$attach_type[1]}\" style=\"margin-top:3px;margin-right:3px\" align=\"left\" onClick=\"groups.wall_photo_view('{$row['id']}', '', '{$attach_type[1]}', '{$cnt_attach}')\" class=\"cursor_pointer page_num{$row['id']}\" />";
										
									$cnt_attach++;
								}
								
								$resLinkTitle = '';

							//Видео
							} elseif($attach_type[0] == 'video' AND file_exists(ROOT_DIR."/uploads/videos/{$attach_type[3]}/{$attach_type[1]}")){
								$attach_result .= "<div><a href=\"/video{$attach_type[3]}_{$attach_type[2]}\" onClick=\"videos.show({$attach_type[2]}, this.href, location.href); return false\"><img src=\"/uploads/videos/{$attach_type[3]}/{$attach_type[1]}\" style=\"margin-top:3px;margin-right:3px\" align=\"left\" /></a></div>";
								
								$resLinkTitle = '';
								
							//Музыка
							} elseif($attach_type[0] == 'audio'){
								$audioId = intval($attach_type[1]);
								$audioInfo = $db->super_query("SELECT artist, name, url FROM `".PREFIX."_audio` WHERE aid = '".$audioId."'");
								if($audioInfo){
									$jid++;
									$attach_result .= '<div class="audioForSize'.$row['id'].' player_mini_mbar_wall_all2" id="audioForSize"><div class="audio_onetrack audio_wall_onemus"><div class="audio_playic cursor_pointer fl_l" onClick="music.newStartPlay(\''.$jid.'\', '.$row['id'].')" id="icPlay_'.$row['id'].$jid.'"></div><div id="music_'.$row['id'].$jid.'" data="'.$audioInfo['url'].'" class="fl_l" style="margin-top:-1px"><a href="/?go=search&type=5&query='.$audioInfo['artist'].'" onClick="Page.Go(this.href); return false"><b>'.stripslashes($audioInfo['artist']).'</b></a> &ndash; '.stripslashes($audioInfo['name']).'</div><div id="play_time'.$row['id'].$jid.'" class="color777 fl_r no_display" style="margin-top:2px;margin-right:5px">00:00</div><div class="player_mini_mbar fl_l no_display player_mini_mbar_wall player_mini_mbar_wall_all2" id="ppbarPro'.$row['id'].$jid.'"></div></div></div>';
								}
								
								$resLinkTitle = '';

							//Смайлик
							} elseif($attach_type[0] == 'smile' AND file_exists(ROOT_DIR."/uploads/smiles/{$attach_type[1]}")){
								$attach_result .= '<img src=\"/uploads/smiles/'.$attach_type[1].'\" style="margin-right:5px" />';
								
								$resLinkTitle = '';
								
							//Если ссылка
							} elseif($attach_type[0] == 'link' AND preg_match('/http:\/\/(.*?)+$/i', $attach_type[1]) AND $cnt_attach_link == 1){
								$count_num = count($attach_type);
								$domain_url_name = explode('/', $attach_type[1]);
								$rdomain_url_name = str_replace('http://', '', $domain_url_name[2]);
								
								$attach_type[3] = stripslashes($attach_type[3]);
								$attach_type[3] = substr($attach_type[3], 0, 200);
									
								$attach_type[2] = stripslashes($attach_type[2]);
								$str_title = substr($attach_type[2], 0, 55);
								
								if(stripos($attach_type[4], '/uploads/attach/') === false){
									$attach_type[4] = '{theme}/images/no_ava_groups_100.gif';
									$no_img = false;
								} else
									$no_img = true;
								
								if(!$attach_type[3]) $attach_type[3] = '';
									
								if($no_img AND $attach_type[2]){
								
									$attach_result .= '<div style="margin-top:2px" class="clear"><div class="attach_link_block_ic fl_l" style="margin-top:4px;margin-left:0px"></div><div class="attach_link_block_te"><div class="fl_l">Ссылка: <a href="/away.php?url='.$attach_type[1].'" target="_blank">'.$rdomain_url_name.'</a></div></div><div class="clear"></div><div class="wall_show_block_link" style="border:0px"><a href="/away.php?url='.$attach_type[1].'" target="_blank"><div style="width:108px;height:80px;float:left;text-align:center"><img src="'.$attach_type[4].'" /></div></a><div class="attatch_link_title"><a href="/away.php?url='.$attach_type[1].'" target="_blank">'.$str_title.'</a></div><div style="max-height:50px;overflow:hidden">'.$attach_type[3].'</div></div></div>';

									$resLinkTitle = $attach_type[2];
									$resLinkUrl = $attach_type[1];
								} else if($attach_type[1] AND $attach_type[2]){
									$attach_result .= '<div style="margin-top:2px" class="clear"><div class="attach_link_block_ic fl_l" style="margin-top:4px;margin-left:0px"></div><div class="attach_link_block_te"><div class="fl_l">Ссылка: <a href="/away.php?url='.$attach_type[1].'" target="_blank">'.$rdomain_url_name.'</a></div></div></div><div class="clear"></div>';
									
									$resLinkTitle = $attach_type[2];
									$resLinkUrl = $attach_type[1];
								}
								
								$cnt_attach_link++;
								
							//Если документ
							} elseif($attach_type[0] == 'doc'){
							
								$doc_id = intval($attach_type[1]);
								
								$row_doc = $db->super_query("SELECT dname, dsize FROM `".PREFIX."_doc` WHERE did = '{$doc_id}'");
								
								if($row_doc){
									
									$attach_result .= '<div style="margin-top:5px;margin-bottom:5px" class="clear"><div class="doc_attach_ic fl_l" style="margin-top:4px;margin-left:0px"></div><div class="attach_link_block_te"><div class="fl_l">Файл <a href="/index.php?go=doc&act=download&did='.$doc_id.'" target="_blank" onMouseOver="myhtml.title(\''.$doc_id.$cnt_attach.$row['id'].'\', \'<b>Размер файла: '.$row_doc['dsize'].'</b>\', \'doc_\')" id="doc_'.$doc_id.$cnt_attach.$row['id'].'">'.$row_doc['dname'].'</a></div></div></div><div class="clear"></div>';
										
									$cnt_attach++;
								}
								
							//Если опрос
							} elseif($attach_type[0] == 'vote'){
							
								$vote_id = intval($attach_type[1]);
								
								$row_vote = $db->super_query("SELECT title, answers, answer_num FROM `".PREFIX."_votes` WHERE id = '{$vote_id}'", false, "votes/vote_{$vote_id}");
								
								if($vote_id){

									$checkMyVote = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_votes_result` WHERE user_id = '{$user_id}' AND vote_id = '{$vote_id}'", false, "votes/check{$user_id}_{$vote_id}");
									
									$row_vote['title'] = stripslashes($row_vote['title']);
									
									if(!$row['text'])
										$row['text'] = $row_vote['title'];

									$arr_answe_list = explode('|', stripslashes($row_vote['answers']));
									$max = $row_vote['answer_num'];
									
									$sql_answer = $db->super_query("SELECT answer, COUNT(*) AS cnt FROM `".PREFIX."_votes_result` WHERE vote_id = '{$vote_id}' GROUP BY answer", 1, "votes/vote_answer_cnt_{$vote_id}");
									$answer = array();
									foreach($sql_answer as $row_answer){
									
										$answer[$row_answer['answer']]['cnt'] = $row_answer['cnt'];
										
									}
									
									$attach_result .= "<div class=\"clear\" style=\"height:10px\"></div><div id=\"result_vote_block{$vote_id}\"><div class=\"wall_vote_title\">{$row_vote['title']}</div>";
									
									for($ai = 0; $ai < sizeof($arr_answe_list); $ai++){

										if(!$checkMyVote['cnt']){
										
											$attach_result .= "<div class=\"wall_vote_oneanswe\" onClick=\"Votes.Send({$ai}, {$vote_id})\" id=\"wall_vote_oneanswe{$ai}\"><input type=\"radio\" name=\"answer\" /><span id=\"answer_load{$ai}\">{$arr_answe_list[$ai]}</span></div>";
										
										} else {

											$num = $answer[$ai]['cnt'];

											if(!$num ) $num = 0;
											if($max != 0) $proc = (100 * $num) / $max;
											else $proc = 0;
											$proc = round($proc, 2);
											
											$attach_result .= "<div class=\"wall_vote_oneanswe cursor_default\">
											{$arr_answe_list[$ai]}<br />
											<div class=\"wall_vote_proc fl_l\"><div class=\"wall_vote_proc_bg\" style=\"width:".intval($proc)."%\"></div><div style=\"margin-top:-16px\">{$num}</div></div>
											<div class=\"fl_l\" style=\"margin-top:-1px\"><b>{$proc}%</b></div>
											</div><div class=\"clear\"></div>";
					
										}
									
									}
									
									if($row_vote['answer_num']) $answer_num_text = gram_record($row_vote['answer_num'], 'fave');
									else $answer_num_text = 'человек';
									
									if($row_vote['answer_num'] <= 1) $answer_text2 = 'Проголосовал';
									else $answer_text2 = 'Проголосовало';
										
									$attach_result .= "{$answer_text2} <b>{$row_vote['answer_num']}</b> {$answer_num_text}.<div class=\"clear\" style=\"margin-top:10px\"></div></div>";
									
								}
								
							} else
							
								$attach_result .= '';
						
						}
						
						if($resLinkTitle AND $row['text'] == $resLinkUrl OR !$row['text'])
							$row['text'] = $resLinkTitle.$attach_result;
						else if($attach_result)
							$row['text'] = preg_replace('`(http(?:s)?://\w+[^\s\[\]\<]+)`i', '<a href="/away.php?url=$1" target="_blank">$1</a>', $row['text']).$attach_result;
						else
							$row['text'] = preg_replace('`(http(?:s)?://\w+[^\s\[\]\<]+)`i', '<a href="/away.php?url=$1" target="_blank">$1</a>', $row['text']);
					
					} else
						$row['text'] = preg_replace('`(http(?:s)?://\w+[^\s\[\]\<]+)`i', '<a href="/away.php?url=$1" target="_blank">$1</a>', $row['text']);
					
					$resLinkTitle = '';
					
					//Если это запись с "рассказать друзьям"
					if($row['tell_uid']){
						if($row['public'])
							$rowUserTell = $db->super_query("SELECT title, photo FROM `".PREFIX."_communities` WHERE id = '{$row['tell_uid']}'");
						else
							$rowUserTell = $db->super_query("SELECT user_search_pref, user_photo FROM `".PREFIX."_users` WHERE user_id = '{$row['tell_uid']}'");

						if(date('Y-m-d', $row['tell_date']) == date('Y-m-d', $server_time))
							$dateTell = langdate('сегодня в H:i', $row['tell_date']);
						elseif(date('Y-m-d', $row['tell_date']) == date('Y-m-d', ($server_time-84600)))
							$dateTell = langdate('вчера в H:i', $row['tell_date']);
						else
							$dateTell = langdate('j F Y в H:i', $row['tell_date']);
						
						if($row['public']){
							$rowUserTell['user_search_pref'] = stripslashes($rowUserTell['title']);
							$tell_link = 'public';
							if($rowUserTell['photo'])
								$avaTell = '/uploads/groups/'.$row['tell_uid'].'/50_'.$rowUserTell['photo'];
							else
								$avaTell = '{theme}/images/no_ava_50.png';
						} else {
							$tell_link = 'u';
							if($rowUserTell['user_photo'])
								$avaTell = '/uploads/users/'.$row['tell_uid'].'/50_'.$rowUserTell['user_photo'];
							else
								$avaTell = '{theme}/images/no_ava_50.png';
						}

						$row['text'] = <<<HTML
{$row['tell_comm']}
<div class="wall_repost_border">
<div class="wall_tell_info"><div class="wall_tell_ava"><a href="/{$tell_link}{$row['tell_uid']}" onClick="Page.Go(this.href); return false"><img src="{$avaTell}" width="30" /></a></div><div class="wall_tell_name"><a href="/{$tell_link}{$row['tell_uid']}" onClick="Page.Go(this.href); return false"><b>{$rowUserTell['user_search_pref']}</b></a></div><div class="wall_tell_date">{$dateTell}</div></div>{$row['text']}
<div class="clear"></div>
</div>
HTML;
					}
			
					$tpl->set('{text}', stripslashes($row['text']));
					
					$tpl->set('{subj}', stripslashes($row['theme']));
					$tpl->set('{user-id}', $row['from_user_id']);
	
					OnlineTpl($row['user_last_visit']);
					megaDate($row['date'], 1, 1);
					
					$tpl->set('{msg-id}', $mid);

					if($folder == 'inbox'){
						$tpl->set('[inbox]', '');
						$tpl->set('[/inbox]', '');
						$tpl->set_block("'\\[outbox\\](.*?)\\[/outbox\\]'si","");
					} else {
						$tpl->set('[outbox]', '');
						$tpl->set('[/outbox]', '');
						$tpl->set_block("'\\[inbox\\](.*?)\\[/inbox\\]'si","");
					}
					
					if($row['pm_read'] == 'no'){
						$tpl->set('[new]', '');
						$tpl->set('[/new]', '');
					} else
						$tpl->set_block("'\\[new\\](.*?)\\[/new\\]'si","");
					
					$tpl->compile('content');
					
					//Если статус сообщения не прочитано, то обновляем его
					if($row['pm_read'] == 'no' AND $folder == 'inbox'){
						$db->query("UPDATE `".PREFIX."_messages` SET pm_read = 'yes' WHERE id = '{$mid}'");
						$db->query("UPDATE `".PREFIX."_messages` SET pm_read = 'yes' WHERE id = '".($mid+1)."'");
						$db->query("UPDATE `".PREFIX."_users` SET user_pm_num = user_pm_num-1 WHERE user_id = '{$user_id}'");
						$db->query("UPDATE `".PREFIX."_im` SET msg_num = msg_num-1 WHERE iuser_id = '".$user_id."' AND im_user_id = '".$row['from_user_id']."'");
						
						//Читисм кеш обновлений
						mozg_clear_cache_file('user_'.$row['from_user_id'].'/im');
					}
				} else
					msgbox('', $lang['none_msg'], 'info_box');
			} else
				msgbox('', $lang['none_msg'], 'info_box');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;
//################### Информация о мобильной версии ###################//
  case "mobile_info":  
   $tpl->load_template('other/mobile.tpl');
   $tpl->compile('content');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;
//################### Новое окно загрузки фото ###################//
  case "newphoto":  
   $tpl->load_template('other/newphoto.tpl');
   $tpl->compile('content');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;  
  //################### Последние фотографии ###################//
  case "awayphoto":  
  
   
### запрос 30-ти последних фоток
$vaphoto = $db->super_query("SELECT * FROM `".PREFIX."_photos` ORDER BY id DESC LIMIT 0, 30",1);
$tpl->load_template('other/awayphoto.tpl');
	
### Вывод фотографий
if($vaphoto){
foreach($vaphoto as $row_view_photos)
                {
			  $rowPhoto[] .= "src=\"/uploads/users/{$row_view_photos['user_id']}/albums/{$row_view_photos['album_id']}/{$row_view_photos['photo_name']}\" id=\"/photo{$row_view_photos['user_id']}_{$row_view_photos['id']}_{$row_view_photos['album_id']}\"   onclick='location.href=\"/u{$row_view_photos['user_id']}\"'";    
			 
			}
			$i = 0;
			while($i < 30){
			$tpl->set('{vaphoto'.$i.'}', $rowPhoto[$i]);
			$i++;
			}
			


               } 
   $tpl->compile('content');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;  
  
  case "ourphoto":  
 $vaphoto2 = $db->super_query("SELECT * FROM `".PREFIX."_photos` where user_id = '{$user_id}' ORDER BY id DESC LIMIT 0, 16",1);
$tpl->load_template('other/ourphoto.tpl');
### Вывод фотографий
if($vaphoto2){
foreach($vaphoto2 as $row_view_photos)
                {
			  $rowPhoto[] .= "src=\"/uploads/users/{$row_view_photos['user_id']}/albums/{$row_view_photos['album_id']}/{$row_view_photos['photo_name']}\" id=\"/photo{$row_view_photos['user_id']}_{$row_view_photos['id']}_{$row_view_photos['album_id']}\"   onclick='location.href=\"/u{$row_view_photos['user_id']}\"'";    
			 
			}
			$i = 0;
			while($i < 16){
			$tpl->set('{vaphoto'.$i.'}', $rowPhoto[$i]);
			$i++;
			}
			


               } 
   $tpl->compile('content');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;  

  
 //################### Информация о мобильной версии ###################//
  case "userinfo": 

	$cache_folder = 'user_'.$id;

	//Читаем кеш
	$row = unserialize(mozg_cache($cache_folder.'/profile_'.$id));

	//Проверяем на наличие кеша, если нету то выводи из БД и создаём его 
	if(!$row){
		$row = $db->super_query("SELECT user_id, user_mobile, user_active, user_search_pref, user_country_city_name, user_birthday, user_xfields, user_xfields_all, user_city, user_country, user_photo, user_friends_num, user_notes_num, user_subscriptions_num, user_wall_num, user_albums_num, user_last_visit, user_videos_num, user_status, user_privacy, user_sp, user_sex, user_gifts, user_public_num, user_audio, user_delet, user_ban_date, xfields, user_doc_num, user_real, user_design,clr_l,clr_f,clr_h1,clr_h2,tonirovka FROM `".PREFIX."_users` WHERE user_id = '{$id}'");
		if($row){
			mozg_create_folder_cache($cache_folder);
			mozg_create_cache($cache_folder.'/profile_'.$id, serialize($row));
		}
		$row_online['user_last_visit'] = $row['user_last_visit'];
	} else 
		$row_online = $db->super_query("SELECT user_last_visit FROM `".PREFIX."_users` WHERE user_id = '{$id}'");




  
   $tpl->load_template('other/info_user.tpl');
    if($row){
   if($row['user_photo']){
			if($config['temp'] == 'mobile'){$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row['user_id'].'/50_'.$row['user_photo']);}else{$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row['user_id'].'/'.$row['user_photo']);}
				$tpl->set('{display-ava}', 'style="display:block;"');
			} else {
				$tpl->set('{ava}', '/templates/Default/images/no_ava.gif');
				$tpl->set('{display-ava}', 'style="display:none;"');
			}
   $tpl->set('{user_name}', $row['user_search_pref']);
   		//Конакты
			$xfields = xfieldsdataload($row['user_xfields']);
			$preg_safq_name_exp = explode(', ', 'phone, vk, od, skype, fb, icq, site');
			foreach($preg_safq_name_exp as $preg_safq_name){
				if($xfields[$preg_safq_name]){
					$tpl->set("[not-contact-{$preg_safq_name}]", '');
					$tpl->set("[/not-contact-{$preg_safq_name}]", '');
				} else
					$tpl->set_block("'\\[not-contact-{$preg_safq_name}\\](.*?)\\[/not-contact-{$preg_safq_name}\\]'si","");
			}
			$tpl->set('{vk}', '<a href="'.stripslashes($xfields['vk']).'" target="_blank">'.stripslashes($xfields['vk']).'</a>');
			$tpl->set('{od}', '<a href="'.stripslashes($xfields['od']).'" target="_blank">'.stripslashes($xfields['od']).'</a>');
			$tpl->set('{fb}', '<a href="'.stripslashes($xfields['fb']).'" target="_blank">'.stripslashes($xfields['fb']).'</a>');
			$tpl->set('{skype}', stripslashes($xfields['skype']));
			$tpl->set('{icq}', stripslashes($xfields['icq']));
			$tpl->set('{oblo}', stripslashes($xfields['phone']));
			
			if(preg_match('/http:\/\//i', $xfields['site']))
				if(preg_match('/\.ru|\.com|\.net|\.su|\.in\.ua|\.ua/i', $xfields['site']))
					$tpl->set('{site}', '<a href="'.stripslashes($xfields['site']).'" target="_blank">'.stripslashes($xfields['site']).'</a>');
				else
					$tpl->set('{site}', stripslashes($xfields['site']));
			else
				$tpl->set('{site}', 'http://'.stripslashes($xfields['site']));
			
			if(!$xfields['vk'] && !$xfields['od'] && !$xfields['fb'] && !$xfields['skype'] && !$xfields['icq'] && !$xfields['phone'] && !$xfields['site'])
				$tpl->set_block("'\\[not-block-contact\\](.*?)\\[/not-block-contact\\]'si","");
			else {
				$tpl->set('[not-block-contact]', '');
				$tpl->set('[/not-block-contact]', '');
			}
				
			//Интересы
			$xfields_all = xfieldsdataload($row['user_xfields_all']);
			$preg_safq_name_exp = explode(', ', 'activity, interests, myinfo, music, kino, books, games, quote');
			
			if(!$xfields_all['activity'] AND !$xfields_all['interests'] AND !$xfields_all['myinfo'] AND !$xfields_all['music'] AND !$xfields_all['kino'] AND !$xfields_all['books'] AND !$xfields_all['games'] AND !$xfields_all['quote'])
				$tpl->set('{not-block-info}', '<div align="center" style="color:#999;">Информация отсутствует.</div>');
			else
				$tpl->set('{not-block-info}', '');
			
			foreach($preg_safq_name_exp as $preg_safq_name){
				if($xfields_all[$preg_safq_name]){
					$tpl->set("[not-info-{$preg_safq_name}]", '');
					$tpl->set("[/not-info-{$preg_safq_name}]", '');
				} else
					$tpl->set_block("'\\[not-info-{$preg_safq_name}\\](.*?)\\[/not-info-{$preg_safq_name}\\]'si","");
			}
			
			$tpl->set('{activity}', nl2br(stripslashes($xfields_all['activity'])));
			$tpl->set('{interests}', nl2br(stripslashes($xfields_all['interests'])));
			$tpl->set('{myinfo}', nl2br(stripslashes($xfields_all['myinfo'])));
			$tpl->set('{music}', nl2br(stripslashes($xfields_all['music'])));
			$tpl->set('{kino}', nl2br(stripslashes($xfields_all['kino'])));
			$tpl->set('{books}', nl2br(stripslashes($xfields_all['books'])));
			$tpl->set('{games}', nl2br(stripslashes($xfields_all['games'])));
			$tpl->set('{quote}', nl2br(stripslashes($xfields_all['quote'])));
			$tpl->set('{name}', $user_name_lastname_exp[0]);
			$tpl->set('{lastname}', $user_name_lastname_exp[1]);
			$tpl->set('{age}', nl2br(stripslashes($xfields_all['age'])));
   }else{
    echo 'Что-то пошло не так. Сообщите администрации об этом:Р Ну или вы хацкер?';
    }  	
   $tpl->compile('content');
  AjaxTpl();
  die();
  $tpl->clear();
  $db->free();
  break;
  
		//################### О сайте ###################//
		case "about":
			$metatags['title'] = 'О сайте';
			$user_speedbar = 'О сайте';
		
			
			//Загружаем сам шаблон добавления
			$tpl->load_template('other/about.tpl');
			

						
						
			$tpl->compile('content');
		break;
  
  		//################### FaceCam mainpage ###################//
		case "facetagram":
			$metatags['title'] = 'Facetagram';
			$user_speedbar = 'Facetagramm';
		
			$tpl->load_template('other/facetagram.tpl');	
											
			$tpl->compile('content');
		break;  
  
	}
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>