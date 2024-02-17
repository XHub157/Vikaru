<?php
/* 
	Appointment: Настройки
	File: settings.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$user_id = $user_info['user_id'];
	$act = $_GET['act'];
	$metatags['title'] = $lang['settings'];

	switch($act){
	
		//################### Личный адрес профиля ###################//
		case "alias":
			NoAjaxQuery();		
            $alias = ajax_utf8(strtolower(textFilter($_POST['alias'], false, true)));
           
 		    if(!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) $alias_ok = false;
			else $alias_ok = true;
			
			

		  if(preg_match("/^id/", $alias)) $alias_s_ok = false;
			else $alias_s_ok = true;
		  	
		
	
            if($alias_ok AND $alias_s_ok AND strlen($alias) > 4 or strlen($alias) == 0){
			
			$check_public = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_communities` WHERE adres = '".$alias."' "); //Проверяем адреса у публичных страниц
			$chek_user = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users` WHERE alias = '".$alias."' AND  user_id != '".$user_id."'"); // Проверяем адреса у пользователей
			
			if(!$check_public['cnt'] AND !$chek_user['cnt'] OR $alias == ''){
		    $db->query("UPDATE `".PREFIX."_users` SET alias = '".$alias."'  WHERE user_id = '".$user_id."'");
            echo 'ok_alias';
			}else {echo 'err_alias_name';}
			
			}else echo 'err_alias_str';

	
   		    mozg_clear_cache_file('user_'.$user_id.'/profile_'.$user_id);
			mozg_clear_cache();
			die();
		break;
		
		//################### Изменение пароля ###################//
		case "newpass":
			NoAjaxQuery();
			
			$_POST['old_pass'] = ajax_utf8($_POST['old_pass']);
			$_POST['new_pass'] = ajax_utf8($_POST['new_pass']);
			$_POST['new_pass2'] = ajax_utf8($_POST['new_pass2']);
			
			$old_pass = md5(md5(GetVar($_POST['old_pass'])));
			$new_pass = md5(md5(GetVar($_POST['new_pass'])));
			$new_pass2 = md5(md5(GetVar($_POST['new_pass2'])));
			
			//Выводим текущий пароль
			$row = $db->super_query("SELECT user_password FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			if($row['user_password'] == $old_pass){
				if($new_pass == $new_pass2)
					$db->query("UPDATE `".PREFIX."_users` SET user_password = '{$new_pass2}' WHERE user_id = '{$user_id}'");
				else
					echo '2';
			} else
				echo '1';
			
			die();
		break;
		
		//################### Изменение имени ###################//
		case "newname":
			NoAjaxQuery();
			$user_name = ajax_utf8(textFilter($_POST['name']));
			$user_lastname = ajax_utf8(textFilter(ucfirst($_POST['lastname'])));

			//Проверка имени
			if(isset($user_name)){
				if(strlen($user_name) >= 2){
					if(!preg_match("/^[a-zA-Zа-яА-Я]+$/", $user_name))
						$errors = 3;
				} else
					$errors = 2;
			} else
				$errors = 1;
				
			//Проверка фамилии
			if(isset($user_lastname)){
				if(strlen($user_lastname) >= 2){
					if(!preg_match("/^[a-zA-Zа-яА-Я]+$/", $user_lastname))
						$errors_lastname = 3;
				} else
					$errors_lastname = 2;
			} else
				$errors_lastname = 1;
			
			if(!$errors){
				if(!$errors_lastname){
					$user_name = ucfirst($user_name);
					$user_lastname = ucfirst($user_lastname);
					
					$db->query("UPDATE `".PREFIX."_users` SET user_name = '{$user_name}', user_lastname = '{$user_lastname}', user_search_pref = '{$user_name} {$user_lastname}' WHERE user_id = '{$user_id}'");
					
					mozg_clear_cache_file('user_'.$user_id.'/profile_'.$user_id);
					mozg_clear_cache();
				} else
					echo $errors;
			} else
				echo $errors;
			
			die();
		break;
		
		//################### Сохранение настроек приватности ###################//
		case "saveprivacy":
			NoAjaxQuery();
			
			$val_msg = intval($_POST['val_msg']);
			$val_wall1 = intval($_POST['val_wall1']);
			$val_wall2 = intval($_POST['val_wall2']);
			$val_wall3 = intval($_POST['val_wall3']);
			$val_info = intval($_POST['val_info']);
			$val_public = intval($_POST['val_public']);
			$val_audio = intval($_POST['val_audio']);
			$val_video = intval($_POST['val_video']);
			$val_gift = intval($_POST['val_gift']);
			
			if($val_msg <= 0 OR $val_msg > 3) $val_msg = 1;
			if($val_wall1 <= 0 OR $val_wall1 > 3) $val_wall1 = 1;
			if($val_wall2 <= 0 OR $val_wall2 > 3) $val_wall2 = 1;
			if($val_wall3 <= 0 OR $val_wall3 > 3) $val_wall3 = 1;
			if($val_info <= 0 OR $val_info > 3) $val_info = 1;
			if($val_public <= 0 OR $val_public > 3) $val_public = 1;
			if($val_audio <= 0 OR $val_audio > 3) $val_audio = 1;
			if($val_video <= 0 OR $val_video > 3) $val_video = 1;
			if($val_gift <= 0 OR $val_gift > 3) $val_gift = 1;
			
			$user_privacy = "val_msg|{$val_msg}||val_wall1|{$val_wall1}||val_wall2|{$val_wall2}||val_wall3|{$val_wall3}||val_info|{$val_info}||val_gift|{$val_gift}||val_audio|{$val_audio}||val_video|{$val_video}||val_public|{$val_public}||";
			
			$db->query("UPDATE `".PREFIX."_users` SET user_privacy = '{$user_privacy}' WHERE user_id = '{$user_id}'");
			
			mozg_clear_cache_file('user_'.$user_id.'/profile_'.$user_id);
			
			die();
		break;
		
		//################### Мобильные сервисы ###################//
		case "mobile":
			$tpl->load_template('settings/mobile.tpl');
			$tpl->compile('info');
		break;
		
		//################### Приватность настройки ###################//
		case "privacy":
			$sql_ = $db->super_query("SELECT user_privacy FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			$row = xfieldsdataload($sql_['user_privacy']);
			$tpl->load_template('settings/privacy.tpl');
			$tpl->set('{val_msg}', $row['val_msg']);
			$tpl->set('{val_msg_text}', strtr($row['val_msg'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Никто')));
			$tpl->set('{val_wall1}', $row['val_wall1']);
			$tpl->set('{val_wall1_text}', strtr($row['val_wall1'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_wall2}', $row['val_wall2']);
			$tpl->set('{val_wall2_text}', strtr($row['val_wall2'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_wall3}', $row['val_wall3']);
			$tpl->set('{val_wall3_text}', strtr($row['val_wall3'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_info}', $row['val_info']);
			$tpl->set('{val_info_text}', strtr($row['val_info'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_public}', $row['val_public']);
			$tpl->set('{val_public_text}', strtr($row['val_public'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_audio}', $row['val_audio']);
			$tpl->set('{val_audio_text}', strtr($row['val_audio'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_video}', $row['val_video']);
			$tpl->set('{val_video_text}', strtr($row['val_video'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->set('{val_gift}', $row['val_gift']);
			$tpl->set('{val_gift_text}', strtr($row['val_gift'], array('1' => 'Все пользователи', '2' => 'Только друзья', '3' => 'Только я')));
			$tpl->compile('info');
		break;
		
		//################### Добавление в черный список ###################//
		case "addblacklist":
			NoAjaxQuery();
			$bad_user_id = intval($_POST['bad_user_id']);
			
			//Проверяем на существование юзера
			$row = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users` WHERE user_id = '{$bad_user_id}'");

			//Выводим свой блеклист для проверка
			$myRow = $db->super_query("SELECT user_blacklist FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			$array_blacklist = explode('|', $myRow['user_blacklist']);

			if($row['cnt'] AND !in_array($bad_user_id, $array_blacklist) AND $user_id != $bad_user_id){
				$db->query("UPDATE `".PREFIX."_users` SET user_blacklist_num = user_blacklist_num+1, user_blacklist = '{$myRow['user_blacklist']}|{$bad_user_id}|' WHERE user_id = '{$user_id}'");
				
				//Если юзер есть в др.
				if(CheckFriends($bad_user_id)){
					//Удаляем друга из таблицы друзей
					$db->query("DELETE FROM `".PREFIX."_friends` WHERE user_id = '{$user_id}' AND friend_id = '{$bad_user_id}' AND subscriptions = 0");
					
					//Удаляем у друга из таблицы
					$db->query("DELETE FROM `".PREFIX."_friends` WHERE user_id = '{$bad_user_id}' AND friend_id = '{$user_id}' AND subscriptions = 0");
					
					//Обновляем кол-друзей у юзера
					$db->query("UPDATE `".PREFIX."_users` SET user_friends_num = user_friends_num-1 WHERE user_id = '{$user_id}'");
					
					//Обновляем у друга которого удаляем кол-во друзей
					$db->query("UPDATE `".PREFIX."_users` SET user_friends_num = user_friends_num-1 WHERE user_id = '{$bad_user_id}'");
					
					//Чистим кеш владельцу стр и тому кого удаляем из др.
					mozg_clear_cache_file('user_'.$user_id.'/profile_'.$user_id);
					mozg_clear_cache_file('user_'.$bad_user_id.'/profile_'.$bad_user_id);
					
					//Удаляем пользователя из кеш файл друзей
					$openMyList = mozg_cache("user_{$user_id}/friends");
					mozg_create_cache("user_{$user_id}/friends", str_replace("u{$bad_user_id}|", "", $openMyList));
					
					$openTakeList = mozg_cache("user_{$bad_user_id}/friends");
					mozg_create_cache("user_{$bad_user_id}/friends", str_replace("u{$user_id}|", "", $openTakeList));
				}
				
				$openMyList = mozg_cache("user_{$user_id}/blacklist");
				mozg_create_cache("user_{$user_id}/blacklist", $openMyList."|{$bad_user_id}|");
			}
			
			die();
		break;
		
		//################### Удаление из черного списка ###################//
		case "delblacklist":
			NoAjaxQuery();
			$bad_user_id = intval($_POST['bad_user_id']);
			
			//Проверяем на существование юзера
			$row = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users` WHERE user_id = '{$bad_user_id}'");

			//Выводим свой блеклист для проверка
			$myRow = $db->super_query("SELECT user_blacklist FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			$array_blacklist = explode('|', $myRow['user_blacklist']);

			if($row['cnt'] AND in_array($bad_user_id, $array_blacklist) AND $user_id != $bad_user_id){
				$myRow['user_blacklist'] = str_replace("|{$bad_user_id}|", "", $myRow['user_blacklist']);
				$db->query("UPDATE `".PREFIX."_users` SET user_blacklist_num = user_blacklist_num-1, user_blacklist = '{$myRow['user_blacklist']}' WHERE user_id = '{$user_id}'");
				
				$openMyList = mozg_cache("user_{$user_id}/blacklist");
				mozg_create_cache("user_{$user_id}/blacklist", str_replace("|{$bad_user_id}|", "", $openMyList));
			}
			
			die();
		break;
		
		//################### Черный список ###################//
		case "blacklist":
			$row = $db->super_query("SELECT user_blacklist, user_blacklist_num FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			
			$tpl->load_template('settings/blacklist.tpl');
			$tpl->set('{cnt}', '<span id="badlistnum">'.$row['user_blacklist_num'].'</span> '.gram_record($row['user_blacklist_num'], 'fave'));
			if($row['user_blacklist_num']){
				$tpl->set('[yes-users]', '');
				$tpl->set('[/yes-users]', '');
			} else
				$tpl->set_block("'\\[yes-users\\](.*?)\\[/yes-users\\]'si","");
			$tpl->compile('info');
			
			if($row['user_blacklist_num'] AND $row['user_blacklist_num'] <= 100){
				$tpl->load_template('settings/baduser.tpl');
				$array_blacklist = explode('|', $row['user_blacklist']);
				foreach($array_blacklist as $user){
					if($user){
						$infoUser = $db->super_query("SELECT user_photo, user_search_pref FROM `".PREFIX."_users` WHERE user_id = '{$user}'");
						
						if($infoUser['user_photo'])
							$tpl->set('{ava}', '/uploads/users/'.$user.'/50_'.$infoUser['user_photo']);
						else
							$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
						
						$tpl->set('{name}', $infoUser['user_search_pref']);
						$tpl->set('{user-id}', $user);
						
						$tpl->compile('content');
					}
				}
			} else
				msgbox('', $lang['settings_nobaduser'], 'info_2');
		break;
		
		//################### Удаление страницы ###################//
		case "deactive":
			
			$spBar = true;
			$user_speedbar = 'Удаление страницы';
			
			$tpl->load_template('settings/deactive.tpl');
			
			$tpl->compile('info');
			
		break;
		
		//################### Удаление страницы ###################//
		case "deactivego":
			NoAjaxQuery();
			
			$reason = ajax_utf8(textFilter($_POST['reason']));
			$type = intval($_POST['type']);
			$nfriends = intval($_POST['nfriends']);
			
			$row = $db->super_query("SELECT user_delete_type,user_ban,user_delet FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			
			if($row and $row['user_delete_type'] == 0) {
				if($row['user_ban'] == 0 and $row['user_delet'] == 0) {
					$db->query("UPDATE `".PREFIX."_users` SET user_delete_type = '{$type}', user_delete_reason = '{$reason}', user_delete_date = '{$server_time}' WHERE user_id = '{$user_id}'");
					if($nfriends != 0) {
						$db->query("INSERT INTO `".PREFIX."_wall` SET author_user_id = '{$user_id}', for_user_id = '{$user_id}', text = 'Я удалил свою страницу, поздравьте меня!<br>{$reason}', add_date = '{$server_time}', fast_comm_id = '0'");
						$dbid = $db->insert_id();
						$db->query("INSERT INTO `".PREFIX."_news` SET ac_user_id = '{$user_id}', action_type = 1, action_text = 'Я удалил свою страницу, поздравьте меня!<br>{$reason}', obj_id = '{$dbid}', action_time = '{$server_time}'");
					}
					echo "ok";
				} else echo "already_delete";
			} else echo "already_delete";
			
			die();
		break;
		
		//################### Смена e-mail ###################//
		case "change_mail":
		
			//Отправляем письмо на обе почты
			include_once ENGINE_DIR.'/classes/mail.php';
			$mail = new dle_mail($config);
			
			$email = textFilter($_POST['email'], false, true);
			
			//Проверка E-mail
			if(preg_match('/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i', $email)) $ok_email = true;
			else $ok_email = false;
				
			$row = $db->super_query("SELECT user_email FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			
			$check_email = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users`  WHERE user_email = '{$email}'");
			
			if($row['user_email'] AND $ok_email AND !$check_email['cnt']){
				
				//Удаляем все пред. заявки
				$db->query("DELETE FROM `".PREFIX."_restore` WHERE email = '{$email}'");
				
				$salt = "abchefghjkmnpqrstuvwxyz0123456789";
				for($i = 0; $i < 15; $i++){
					$rand_lost .= $salt{rand(0, 33)};
				}
				$hash = md5($server_time.$row['user_email'].rand(0, 100000).$rand_lost);
						
				$message = <<<HTML
Вы получили это письмо, так как зарегистрированы на сайте
{$config['home_url']} и хотите изменить основной почтовый адрес.
Вы желаете изменить почтовый адрес с текущего ({$row['user_email']}) на {$email}
Для того чтобы Ваш основной e-mail на сайте {$config['home_url']} был
изменен, Вам необходимо пройти по ссылке:
{$config['home_url']}index.php?go=settings&code1={$hash}

Внимание: не забудьте, что после изменения почтового адреса при входе
на сайт Вам нужно будет указывать новый адрес электронной почты.

Если Вы не посылали запрос на изменение почтового адреса,
проигнорируйте это письмо.С уважением,
Администрация {$config['home_url']}
HTML;
				$mail->send($row['user_email'], 'Изменение почтового адреса', $message);
				
				//Вставляем в БД код 1
				$db->query("INSERT INTO `".PREFIX."_restore` SET email = '{$email}', hash = '{$hash}', ip = '{$_IP}'");
				
				$salt = "abchefghjkmnpqrstuvwxyz0123456789";
				for($i = 0; $i < 15; $i++){
					$rand_lost .= $salt{rand(0, 33)};
				}
				$hash = md5($server_time.$row['user_email'].rand(0, 300000).$rand_lost);
						
				$message = <<<HTML
Вы получили это письмо, так как зарегистрированы на сайте
{$config['home_url']} и хотите изменить основной почтовый адрес.
Вы желаете изменить почтовый адрес с текущего ({$row['user_email']}) на {$email}
Для того чтобы Ваш основной e-mail на сайте {$config['home_url']} был
изменен, Вам необходимо пройти по ссылке:
{$config['home_url']}index.php?go=settings&code2={$hash}

Внимание: не забудьте, что после изменения почтового адреса при входе
на сайт Вам нужно будет указывать новый адрес электронной почты.

Если Вы не посылали запрос на изменение почтового адреса,
проигнорируйте это письмо.С уважением,
Администрация {$config['home_url']}
HTML;
				$mail->send($email, 'Изменение почтового адреса', $message);
				
				//Вставляем в БД код 2
				$db->query("INSERT INTO `".PREFIX."_restore` SET email = '{$email}', hash = '{$hash}', ip = '{$_IP}'");
			
			} else
				echo '1';
			
			exit;
			
		break;	
		
		//################### Окошко с историей заходов ###################//		
		case"userlogs":
		
			$sql_ = $db->super_query("SELECT user_id, browser, ip, date FROM `".PREFIX."_user_log` WHERE user_id='{$user_id}' ORDER BY date DESC LIMIT 10",1);		
			foreach($sql_ as $sqls){
				if(date('Y-m-d', $sqls['date']) == date('Y-m-d', $server_time))
					$dateTell = langdate('сегодня в H:i', $sqls['date']);
				elseif(date('Y-m-d', $sqls['date']) == date('Y-m-d', ($server_time-84600)))
					$dateTell = langdate('вчера в H:i',$sqls['date']);
				else
	$dateTell = langdate('j F Y в H:i', $sqls['date']);
if(stripos($sqls['browser'], 'Chrome') !== false){
	$browser = explode('Chrom', $sqls['browser']);
	$browser2 = explode(' ', 'Chrom'.str_replace('/', ' ', $browser[1]));
	$browser[0] = $browser2[0].' '.$browser2[1];
} elseif(stripos($sqls['browser'], 'Opera') !== false){
	$browser2 = explode('/', $sqls['browser']);
	$browser3 = end(explode('/', $sqls['browser']));
	$browser[0] = $browser2[0].' '.$browser3;
} elseif(stripos($sqls['browser'], 'Firefox') !== false){
	$browser3 = end(explode('/', $sqls['browser']));
	$browser[0] = 'Firefox '.$browser3;
} elseif(stripos($sqls['browser'], 'Safari') !== false){
	$browser3 = end(explode('Version/', $sqls['browser']));
	$browser4 = explode(' ', $browser3);
	$browser[0] = 'Safari '.$browser4[0];
	}

	$ip =  $sqls['ip'];
	$pageip=file_get_contents('http://ip-whois.net/ip_geo.php?ip='.$ip);
	preg_match_all("/Страна: (.*)<br>/i", $pageip, $matches);
	unset($pageip);
					
		
	$logs .=  '<tr class=""><td><span class="browser_info">Браузер '.$browser[0].'</span></td><td>'.$dateTell.'</td><td>'.$matches[1][1].'('.$sqls['ip'].')</td></tr>';

			}
			
			echo '
			<div class="err_yellow  pass_errors" style="font-size:11px;padding: 8px;">
			<b>История активности</b>
показывает информацию о том, в какое время
и с каких устройств производилась авторизация на Ваш профиль. 
			</div>
			<table id="activityHistory" class="history" width="100%" cellspacing="0" cellpadding="0">
			<tr>
			<th>Тип доступа</th>
			<th>Время</th>
			<th>IP-адрес</th>
			</tr>
			'.$logs.'
			</table>
			';
		exit;
		break;
		
		//################### Общие настройки ###################//
		default:
			$row = $db->super_query("SELECT user_name, alias, user_lastname FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");

			$sqls = $db->super_query("SELECT user_id, browser, ip, date FROM `".PREFIX."_user_log` WHERE user_id='{$user_id}' ORDER BY date DESC LIMIT 1");
if(date('Y-m-d', $sqls['date']) == date('Y-m-d', $server_time))
$dateTell = langdate('сегодня в H:i', $sqls['date']);
elseif(date('Y-m-d', $sqls['date']) == date('Y-m-d', ($server_time-84600)))
$dateTell = langdate('вчера в H:i',$sqls['date']);
else
$dateTell = langdate('j F Y в H:i', $sqls['date']);
if(stripos($sqls['browser'], 'Chrome') !== false){
$browser = explode('Chrom', $sqls['browser']);
$browser2 = explode(' ', 'Chrom'.str_replace('/', ' ', $browser[1]));
$browser[0] = $browser2[0].' '.$browser2[1];
} elseif(stripos($sqls['browser'], 'Opera') !== false){
$browser2 = explode('/', $sqls['browser']);
$browser3 = end(explode('/', $sqls['browser']));
$browser[0] = $browser2[0].' '.$browser3;
} elseif(stripos($sqls['browser'], 'Firefox') !== false){
$browser3 = end(explode('/', $sqls['browser']));
$browser[0] = 'Firefox '.$browser3;
} elseif(stripos($sqls['browser'], 'Safari') !== false){
$browser3 = end(explode('Version/', $sqls['browser']));
$browser4 = explode(' ', $browser3);
$browser[0] = 'Safari '.$browser4[0];
}	
$tpl->set('{ip}', $sqls['ip']);
$tpl->set('{log-user}', $dateTell.' Браузер ('.$browser[0].')');
			
			//Загружаем вверх
			$tpl->load_template('settings/general.tpl');
			$tpl->set('{name}', $row['user_name']);
			$tpl->set('{lastname}', $row['user_lastname']);
			$tpl->set('{id}', $user_id);
			if($row['alias'])$tpl->set('{alias}',$row['alias']); else $tpl->set('{alias}','u'.$user_id);
			//Завершении смены E-mail
			$tpl->set('{code-1}', 'no_display');
			$tpl->set('{code-2}', 'no_display');
			$tpl->set('{code-3}', 'no_display');
			$code1 = strip_data($_GET['code1']);
			$code2 = strip_data($_GET['code2']);
			
			if(strlen($code1) == 32){
				
				$code2 = '';
				
				$check_code1 = $db->super_query("SELECT email FROM `".PREFIX."_restore` WHERE hash = '{$code1}' AND ip = '{$_IP}'");

				if($check_code1['email']){
					
					$check_code2 = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_restore` WHERE hash != '{$code1}' AND email = '{$check_code1['email']}' AND ip = '{$_IP}'");
					
					if($check_code2['cnt'])
						$tpl->set('{code-1}', '');
					else {
						$tpl->set('{code-1}', 'no_display');
						$tpl->set('{code-3}', '');
						
						//Меняем
						$db->query("UPDATE `".PREFIX."_users` SET user_email = '{$check_code1['email']}' WHERE user_id = '{$user_id}'");							
						$row['user_email'] = $check_code1['email'];
							
					}
					
					$db->query("DELETE FROM `".PREFIX."_restore` WHERE hash = '{$code1}' AND ip = '{$_IP}'");
					
				}
			
			}
			
			if(strlen($code2) == 32){
			
				$check_code2 = $db->super_query("SELECT email FROM `".PREFIX."_restore` WHERE hash = '{$code2}' AND ip = '{$_IP}'");

				if($check_code2['email']){
				
					$check_code1 = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_restore` WHERE hash != '{$code2}' AND email = '{$check_code2['email']}' AND ip = '{$_IP}'");
					
					if($check_code1['cnt'])
						$tpl->set('{code-2}', '');
					else {
						$tpl->set('{code-2}', 'no_display');
						$tpl->set('{code-3}', '');
						
						//Меняем
						$db->query("UPDATE `".PREFIX."_users` SET user_email = '{$check_code2['email']}'  WHERE user_id = '{$user_id}'");						
						$row['user_email'] = $check_code2['email'];
						
					}
					
					$db->query("DELETE FROM `".PREFIX."_restore` WHERE hash = '{$code2}' AND ip = '{$_IP}'");
					
				}
			
			}
			
			//Email
			$substre = substr($row['user_email'], 0, 1);
			$epx1 = explode('@', $row['user_email']);
			$tpl->set('{email}', $substre.'*******@'.$epx1[1]);
			$tpl->compile('info');
	}
	
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>