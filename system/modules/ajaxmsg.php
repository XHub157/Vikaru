<?php
/* 
	Appointment: Сообщения в аяксе
	File: messages.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$act = $_GET['act'];
	$user_id = $user_info['user_id'];

	if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
	$gcount = 7;
	$limit_page = ($page-1)*$gcount;
	

		
			//################### Вывод всех полученных сообщений ###################//

				$metatags['title'] = $lang['msg_inbox'];
				$user_speedbar = $lang['msg_inbox'];
				
				//Вывод информации после отправки сообщения
				if($_GET['info'] == 1)
					msgbox('', '<script type="text/javascript">setTimeout(\'$(".err_yellow").fadeOut()\', 1500);</script>Ваше сообщение успешно отправлено.', 'info');
				
				//Для поиска
				$se_query = $db->safesql(ajax_utf8(strip_data(urldecode($_GET['se_query']))));
				if(isset($se_query) AND !empty($se_query)){
					$search_sql = "AND tb2.user_search_pref LIKE '%{$se_query}%'";
					$query_string = '&se_query='.strip_data($_GET['se_query']);
				} else {
					$se_query = 'Поиск по полученным сообщениям';
					$search_sql = '';
				}
				
				//Запрос в БД на вывод сообщений
				$query = "SELECT SQL_CALC_FOUND_ROWS tb1.id, theme, text, for_user_id, from_user_id, date, pm_read, attach, tb2.user_search_pref, user_photo, user_last_visit FROM `".PREFIX."_messages` tb1, `".PREFIX."_users` tb2 WHERE tb1.for_user_id = '{$user_id}' AND tb1.folder = 'inbox' AND tb1.from_user_id = tb2.user_id {$search_sql} ORDER by `date` DESC LIMIT {$limit_page}, {$gcount}";

				$sql_ = $db->super_query($query, 1);
				
				//Если есть ответ из БД, то считаем кол-вот ответа
				if($sql_)
					$msg_count = $db->super_query("SELECT COUNT(id) AS cnt FROM `".PREFIX."_messages` tb1, `".PREFIX."_users` tb2 WHERE tb1.for_user_id = '{$user_id}' AND tb1.folder = 'inbox' AND tb1.from_user_id = tb2.user_id {$search_sql}");
				
				//header сообщений

			$owner = $db->super_query("SELECT user_banpass FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			if($owner['user_banpass'] == 1){
				header('Location: /settings');
			}
				
				//Если есть сообщения то продолжаем, если нет, то выводи информацию
				if($sql_){
					$tpl->load_template('messages/ajax.tpl');
					foreach($sql_ as $row){
					
						if($row['user_photo']){
							$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row['from_user_id'].'/50_'.$row['user_photo']);
						} else
							$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
							
						$tpl->set('{subj}', stripslashes($row['theme']));
						
						$tpl->set('{text}', substr(stripslashes(strip_tags($row['text'])), 0, 50));
						
						$attach_filesPhoto = explode('photo_u|', $row['attach']);
						if($attach_filesPhoto[1]) $attach_filesP = '<div class="msg_new_mes_ic_photo">Фотография</div>';
						else $attach_filesP = '';
						
						$attach_filesVideo = explode('video|', $row['attach']);
						if($attach_filesVideo[1]) $attach_filesV = '<div class="msg_new_mes_ic_video">Видеозапись</div>';
						else $attach_filesV = '';
						
						$attach_filesSmile = explode('smile|', $row['attach']);
						if($attach_filesSmile[1]) $attach_filesS = '<div class="msg_new_mes_ic_smile">Смайлик</div>';
						else $attach_filesS = '';
						
						$attach_filesAudio = explode('audio|', $row['attach']);
						if($attach_filesAudio[1]) $attach_filesA = '<div class="msg_new_mes_ic_audio">Аудиозапись</div>';
						else $attach_filesA = '';
						
						$attach_filesVote = explode('vote|', $row['attach']);
						if($attach_filesVote[1]) $attach_filesVX = 'Опрос';
						else $attach_filesVX = '';
						
						$attach_filesDoc = explode('doc|', $row['attach']);
						if($attach_filesDoc[1]) $attach_filesD = 'Файл';
						else $attach_filesD = '';
						
						$tpl->set('{attach}', $attach_filesP.$attach_filesV.$attach_filesS.$attach_filesA.$attach_filesVX.$attach_filesD);

						$tpl->set('{user-id}', $row['from_user_id']);
						$tpl->set('{name}', $row['user_search_pref']);
						$tpl->set('{mid}', $row['id']);
						
						OnlineTpl($row['user_last_visit']);
						megaDate($row['date'], 1, 1);
						
						if($row['pm_read'] == 'no'){
							$tpl->set('[new]', '');
							$tpl->set('[/new]', '');
						} else
							$tpl->set_block("'\\[new\\](.*?)\\[/new\\]'si","");
							
						$tpl->set('{folder}', 'inbox');
						$tpl->compile('content');
					}

					
				} else
					msgbox('', $lang['no_msg'], 'info_2');
			
	AjaxTpl();
	die();
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>