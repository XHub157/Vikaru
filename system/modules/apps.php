<?php
/*
	Appointment: Игры
	File: apps.php

*/
if(!defined('MOZG'))
	die('И че ты тут забыл??');

if($ajax == 'yes')
	NoAjaxQuery();

$user_id = $user_info['user_id'];


$act = $_GET['act'];
if($logged){

	switch($act){

		case "view":
		
			$id = intval($_POST['id']);
			$row = $db->super_query("SELECT id,app,cols,title,img,desk,settings,new FROM `".PREFIX."_apps` WHERE id='{$id}'");
			$num = $row['cols'];

			//Склонение поля человека смотрящего обьявление
			if($user_info['user_sex'] == '1'){

				$user_sex = 'первым';

			}else{

				$user_sex = 'первой';

			}

			//Проверка устанавливал ли кто нибудь игру
			if($row['cols'] == 0){
				$games_f = 'Игру еще ни кто не установил будь '.$user_sex;
			}else{
				$games_f = 'Игру установили '.$num.' '.gram_record($num, 'apps');
			}

			echo '<div class="apps_view_pos">
				<img width="75" height="75" src="/uploads/apps/'.$row['id'].'/'.$row['img'].'">
				<a>'.$row['title'].'</a>
				<div>'.$row['desk'].'</div>
				</div>
				<div class="clear"></div>
				<div class="apps_view_block">
				<div class="apps_view_block_txt fl_l">'.$games_f.'</div>
				<div class="button_blue fl_r">
				<button onclick="Page.Go(\'/app'.$row['id'].'\');apps.c();">Запустить игру</button>
				</div>
				<div class="clear"></div>
				</div>
				<div class="appsfh" onclick="Page.Go(\'/app'.$row['id'].'\');apps.c();">
				<div class="apps_i_run_box">Запустить игру</div>
				<div class="apps_main_poster cursor_pointer">
				<div class="apps_inimgs">
				<img width="607" height="376" src="/uploads/apps/'.$row['id'].'/'.$row['img'].'">
				</div>
				</div>
				</div>				
				<h1>Права доступа</h1>
				
				<div class="clear"></div>';

		break;

		//############### Вывод игры ###############

		case "app":
			$id = intval($_GET['id']);
			$user_info['apperant'] = 1;
			

			//Проверка добавлял ли игру пользователь
			$rows = $db->super_query("SELECT user_id,game_id FROM `".PREFIX."_apps_users` WHERE user_id='{$user_id}' AND  game_id='{$id}'");

			if($rows['user_id'] != $user_id && $rows['game_id'] != $id){
				$db->query("INSERT INTO `".PREFIX."_apps_users` (user_id,game_id,date) VALUES ('".$user_id."','".$id."','".$server_time."')");
				$db->query("UPDATE `".PREFIX."_apps` SET cols=cols+1 WHERE id='{$id}'");
			}

			//Вывод игры из базы
			$row = $db->super_query("SELECT id,app,cols,title,admin,img,secrets,page,real_admin,api_settings,settings,new FROM `".PREFIX."_apps` WHERE id='{$id}'");
			$metatags['title'] = 'Игра | '.$row['title'].'';
			$admins_arr = str_replace('|', '', explode('id', $row['admin']));

			//$game_status = mysql_real_escape_string('<a href="?i='.$row['id'].'" onClick="apps.view(\''.$row['id'].'\',this.href,\'/u'.$user_id.'\'); return false" ><img width="15" height="15" src="/uploads/apps/'.$row['id'].'/'.$row['img'].'"> '.$row['title'].'</a>');
			//$db->query("UPDATE  `".PREFIX."_users` SET user_status='<img width=\'15\' height=\'15\' src=\'/uploads/apps/".$row['id']."/".$row['img']."\'> ".$row['title']."' WHERE user_id='{$user_id}'");
			
			if(stripos($row['admin'], "id{$user_id}|") !== false) $tpl->set('{editapps}', ' | <a href="/editapp/'.$id.'">редактировать</a>');
			else $tpl->set('{editapps}', '');
			
			$rgLetters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','1','2','3','4','5','6','7','8','9','0');
			shuffle($rgLetters);
			$password = join('',array_slice($rgLetters, 0, mt_rand(10, 10)));
			
			$auth_key = md5($id."_".$user_id."_".$row['secrets']);
			
			$db->query("DELETE FROM `".PREFIX."_apps_auth` WHERE user_id='".$user_id."' and app_id='".$id."'");
			$db->query("INSERT INTO `".PREFIX."_apps_auth` (user_id,app_id,secret,api_settings,auth_key_api) VALUES ('".$user_id."','".$id."','".$password."','".$row['api_settings']."','".$auth_key."') ON DUPLICATE KEY UPDATE secret = '".$password."', api_settings = '".$row['api_settings']."', auth_key_api = '".$auth_key."'");
			
			if($row['page']==0) {
				$row_a = $db->super_query("SELECT user_id,user_name,user_lastname FROM `".PREFIX."_users` WHERE user_id='{$row['real_admin']}'");
				$user_info['name-groups'] = $row_a['user_name']." ".$row_a['user_lastname'];
				$user_info['group-href'] = "id".$row_a['user_id'];
				$user_info['authororgroup'] = "Автор";
			} else {
				$row_g = $db->super_query("SELECT id,title,adres FROM `".PREFIX."_communities` WHERE id='{$row['page']}'");
				$user_info['name-groups'] = $row_g['title'];
				if($row_g['adres']) $user_info['group-href'] = $row_g['adres'];
				else $user_info['group-href'] = "public".$row_g['id'];
				$user_info['authororgroup'] = "Группа";
			}
			
			$num = $row['cols'];
            $tpl->set('{nums}', $num.' '.gram_record($num, 'apps'));
			$tpl->set('{title}', $row['title']);
			$tpl->set('{id}', $row['id']);
			$tpl->set('{secret}', $password);
			$tpl->set('{auth_key}', $auth_key);
			$tpl->set('{api_id}', $id);
			$tpl->set('{viewer_id}', $user_id);
			$tpl->set('{api_settings}', $row['api_settings']);
			$tpl->set('{ava}', '/uploads/apps/'.$row['id'].'/'.$row['img']);
			$tpl->set('{games}', $row['id'].'/'.$row['app']);
			$tpl->load_template('apps/game.tpl');
			$tpl->compile('content');

		break;

		//##################### Удаление игр у пользователя #####################

		case "mydel":
			$id = intval($_POST['id']);
			$row = $db->super_query("SELECT id FROM `".PREFIX."_apps_users` WHERE user_id='{$user_id}' AND game_id='{$id}'");
			if($row) {
				$db->query("DELETE FROM `".PREFIX."_apps_users` WHERE user_id='{$user_id}' AND game_id='{$id}'");
				$db->query("UPDATE `".PREFIX."_apps` SET cols=cols-1 WHERE id='{$id}'");
			}

		break;

		//########## Отправка рассказать друзьям об игре ################

		case"mywall":

			$id = intval($_POST['id']);
			$sql = $db->super_query("SELECT id,app,cols,title,img FROM `".PREFIX."_apps` WHERE id='{$id}'");
			if($user_info['user_sex'] == 1){
				$sex = 'Я начал';
			}else{
				$sex = 'Я начала';
			}

			$text = $sex.' играть в приложение <a href="/apps?i='.$sql['id'].'" onclick="apps.view(\''.$attach_type[1].'\', this.href, \' \'); return false;">'.$sql['title'].'</a>.<br> Присоединяйся!';

			$attach = 'apps|'.$sql['id'].'|'.$sql['img'].'||';

			$db->query("INSERT INTO `".PREFIX."_wall` (author_user_id,add_date,text,attach,for_user_id) VALUES ('".$user_id."','".$server_time."','".$text."','".$attach."','".$user_id."')");
			$db->query("UPDATE `".PREFIX."_users` SET user_wall_num = user_wall_num+1 WHERE user_id = '{$id}'");


		break;

		//############### Поиск по приложениям ##################

		case"search":
			$games = $db->safesql(ajax_utf8(strip_data(urldecode($_POST['query_games']))));
			$games = strtr($games, array(' ' => '%'));


			$sql = $db->super_query("SELECT * FROM `".PREFIX."_apps` WHERE title LIKE '%{$games}%'",1);

			foreach($sql as $ro){
				$num = $ro['cols'];
				$search_g .='
				 <div class="apps_game apps_game2 apps_last_new" id="{id}">
 <a href="/apps?i='.$ro['id'].'" onClick="apps.view(\''.$ro['id'].'\', this.href, \'/apps\'); return false">
 <img src="/uploads/apps/'.$ro['id'].'/'.$ro['img'].'" class="fl_l" width="75" height="75" /></a>
 <a href="/apps?i='.$ro['id'].'" onClick="apps.view(\''.$ro['id'].'\', this.href, \'/apps\'); return false">'.$ro['title'].'</a>
 <div class="apps_num">'.$num.' '.gram_record($num, 'apps').'</div>
</div>
<div class="clear"></div>

				';
			}

			echo $search_g;
			AjaxTpl();
            die();
		break;

		//################# Подгружаем игры ######################
		case"doload":

			$start = intval($_POST['num']);

			$sqll_ = $db->super_query("SELECT tb1.user_id,tb1.game_id,tb2.title,tb2.img,tb2.cols FROM `".PREFIX."_apps_users` tb1,`".PREFIX."_apps` tb2 WHERE tb1.user_id='{$user_id}' AND tb2.id=tb1.game_id ORDER BY tb1.date DESC LIMIT {$start}, 5",1);
			$tpl->load_template('apps/mygame.tpl');
			foreach($sqll_ as $rows){
				$num = $rows['cols'];
				$mygame .='<div id="app'.$rows['game_id'].'" class="apps_game">
				<a onclick="Page.Go(this.href); return false" href="/app'.$rows['game_id'].'">
				<img class="fl_l" width="50" height="50" src="/uploads/apps/'.$rows['game_id'].'/'.$rows['img'].'">
				</a>
				<a onclick="Page.Go(this.href); return false" href="/app'.$rows['game_id'].'">'.$rows['title'].'</a>
				<div id="appsgan'.$rows['game_id'].'" class="apps_fast_del fl_r cursor_pointer" onmouseover="myhtml.title(\''.$rows['game_id'].'\', \'Удалить игру\', \'appsgan\')" onclick="apps.mydel(\''.$rows['game_id'].'\', true)">
				<img src="/templates/Default/images/close_a.png">
				</div>

				</div>
				<div class="clear"></div>';
			}
			$sqlls_ = $db->super_query("
			SELECT tb1.*,tb2.*,tb3.*,tb4.user_id,tb4.user_search_pref,tb4.user_sex,tb4.user_photo
			FROM `".PREFIX."_apps_users` tb1,`".PREFIX."_friends` tb2,`".PREFIX."_apps` tb3,`".PREFIX."_users` tb4
			WHERE tb2.friend_id=tb1.user_id AND tb2.user_id='{$user_id}' AND tb2.subscriptions='0' AND tb3.id=tb1.game_id AND tb4.user_id=tb2.friend_id
			ORDER BY tb1.date DESC LIMIT {$start}, 5",1);

			foreach($sqlls_ as $rowsa){
				if($rowsa['user_sex'] == 1){
				$m = 'запустил игру';
				}else{
				$m = 'запустила игру';
				}
				if(date('Y-m-d', $rowsa['date']) == date('Y-m-d', $server_time))
						$dateTell = langdate('сегодня в H:i', $rowsa['date']);
					elseif(date('Y-m-d', $rowsa['date']) == date('Y-m-d', ($server_time-84600)))
						$dateTell = langdate('вчера в H:i',$rowsa['date']);
					else
						$dateTell = langdate('j F Y в H:i', $rowsa['date']);

                if($rowsa['user_photo'])
			    $ava =$config['home_url'].'/uploads/users/'.$rowsa['user_id'].'/50_'.$rowsa['user_photo'];
		        else
			    $ava = '/templates/Default/images/no_ava_50.png';

				$frgame .= ' <div class="apps_game">
				 <a href="/u'.$rowsa['user_id'].'" onClick="Page.Go(this.href); return false">
				 <img src="'.$ava.'" class="fl_l" width="50" style="max-height:50px;" onMouseOver="myhtml.title(\''.$rowsa['id'].$rowsa['user_id'].'\', \''.$rowsa['user_search_pref'].'\', \'apps_user\')" id="apps_user'.$rowsa['id'].$rowsa['user_id'].'" /></a>
				 <a href="/apps?i='.$rowsa['id'].$rowsa['user_id'].'" onClick="apps.view(\''.$rowsa['id'].$rowsa['user_id'].'\', this.href, \'/apps\'); return false">
				 <img src="/uploads/apps/'.$rowsa['id'].'/'.$rowsa['img'].'" class="fl_r" width="50" height="50" onMouseOver="myhtml.title(\''.$rowsa['id'].$rowsa['user_id'].'\', \''.$rowsa['title'].'\', \'apps_gane\')" id="apps_gane'.$rowsa['id'].$rowsa['user_id'].'" /></a>
				 <div class="apps_gr">
				  <div class="apps_grtext">
				  '.$m.'<br /><small>'.$dateTell.'</small>
				  </div>
				 </div>
				</div>
				<div class="clear"></div>';

			}
			echo $mygame.'||'.$frgame;
						AjaxTpl();
            die();
		break;

		case"loads":

		$start = intval($_POST['num']);
		$sql_ = $db->super_query("SELECT id,app,cols,title,img FROM `".PREFIX."_apps` WHERE `goods`='1' ORDER BY id DESC LIMIT {$start},20",1);
		$tpl->load_template('apps/newgame.tpl');

		//#################### Вывод популярных игр ######################
		foreach($sql_ as $rowsd){
			if($rowsd['cols'] >= 2){
				$num = $rowsd['cols'];
			$le .=' <div class="apps_game apps_game2 apps_last_new" id="{id}">
			 <a href="/apps?i='. $rowsd['id'].'" onClick="apps.view(\''. $rowsd['id'].'\', this.href,\'/apps\'); return false">
			 <img src="/uploads/apps/'.$rowsd['id'].'/'.$rowsd['img'].'" class="fl_l" width="75" height="75" /></a>
			 <a href="/apps?i='. $rowsd['id'].'" onClick="apps.view(\''. $rowsd['id'].'\', this.href, \'/apps\'); return false">'.$rowsd['title'].'</a>
             <div class="apps_num"></div>
			</div>
			<div class="clear"></div>';
			}
		}

		//#################### Вывод новых игр ######################
		foreach($sql_ as $row){
			$num = $row['cols'];
			$new .=' <div class="apps_game apps_game2 apps_last_new" id="{id}">
			 <a href="/apps?i='. $row['id'].'" onClick="apps.view(\''. $row['id'].'\', this.href,\'/apps\'); return false">
			 <img src="/uploads/apps/'.$row['id'].'/'.$row['img'].'" class="fl_l" width="75" height="75" /></a>
			 <a href="/apps?i='. $row['id'].'" onClick="apps.view(\''. $row['id'].'\', this.href, \'/apps\'); return false">'.$row['title'].'</a>
             <div class="apps_num">'.$num.' '.gram_record($num, 'apps').'</div>
			</div>
			<div class="clear"></div>';
		}
		echo $le.'||'.$new;
			AjaxTpl();
            die();
		break;

		default:

		$sqls_ = $db->super_query("SELECT id,app,cols,title,img FROM `".PREFIX."_apps` WHERE `goods`='1' ORDER BY id DESC LIMIT 3",1);
		$tpl->load_template('apps/slider.tpl');

		//#################### Вывод популярных игр ######################
		foreach($sqls_ as $rowsds){

				$tpl->set('{title}', $rowsds['title']);
				$tpl->set('{id}', $rowsds['id']);
				$tpl->set('{ava}', '/uploads/apps/'.$rowsds['id'].'/'.$rowsds['img']);
				$tpl->compile('slider');

		}

		//############# Вывод моих игр #####################

		$sqll_ = $db->super_query("SELECT tb1.user_id,tb1.game_id,tb2.title,tb2.img,tb2.cols FROM `".PREFIX."_apps_users` tb1,`".PREFIX."_apps` tb2 WHERE tb1.user_id='{$user_id}' AND tb2.id=tb1.game_id ORDER BY tb1.date DESC LIMIT 5",1);
		$tpl->load_template('apps/mygame.tpl');
		foreach($sqll_ as $rows){
			$num = $rows['cols'];
            $tpl->set('{nums}', $num.' '.gram_record($num, 'apps'));
			$tpl->set('{title}', $rows['title']);
			$tpl->set('{id}', $rows['game_id']);
			$tpl->set('{ava}', '/uploads/apps/'.$rows['game_id'].'/'.$rows['img']);
			$tpl->compile('mygame');
		}


		//################ Игры друзей ###################
		$sqlls_ = $db->super_query("
		SELECT tb1.*,tb2.*,tb3.*,tb4.user_id,tb4.user_search_pref,tb4.user_sex,tb4.user_photo
		FROM `".PREFIX."_apps_users` tb1,`".PREFIX."_friends` tb2,`".PREFIX."_apps` tb3,`".PREFIX."_users` tb4
		WHERE

		tb2.friend_id=tb1.user_id
		AND tb2.user_id='{$user_id}'
		AND tb2.subscriptions='0'
		AND tb3.id=tb1.game_id
		AND tb4.user_id=tb2.friend_id
		ORDER BY tb1.date DESC LIMIT 5",1);

		$tpl->load_template('apps/frgame.tpl');
		foreach($sqlls_ as $rowsa){
			if($rowsa['user_sex'] == 1){
			$tpl->set('{zapust}', 'запустил игру');
			}else{
			$tpl->set('{zapust}', 'запустила игру');
			}
			if(date('Y-m-d', $rowsa['date']) == date('Y-m-d', $server_time))
					$dateTell = langdate('сегодня в H:i', $rowsa['date']);
				elseif(date('Y-m-d', $rowsa['date']) == date('Y-m-d', ($server_time-84600)))
					$dateTell = langdate('вчера в H:i',$rowsa['date']);
				else
					$dateTell = langdate('j F Y в H:i', $rowsa['date']);
	        if($rowsa['user_photo'])
			$ava =$config['home_url'].'/uploads/users/'.$rowsa['user_id'].'/50_'.$rowsa['user_photo'];
		    else
			$ava = '/templates/Default/images/no_ava_50.png';

			$tpl->set('{title}', $rowsa['title']);
			$tpl->set('{date}', $dateTell);
			$tpl->set('{name}', $rowsa['user_search_pref']);
			$tpl->set('{user-id}', $rowsa['user_id']);
			$tpl->set('{id}', $rowsa['id'].$rowsa['user_id']);
			$tpl->set('{ava}', $ava);
			$tpl->set('{img}', '/uploads/apps/'.$rowsa['id'].'/'.$rowsa['img']);
			$tpl->compile('frgame');

		}


		//################### Вывод новых и популярных игр #####################
		$metatags['title'] = 'Приложения';
		$sql_ = $db->super_query("SELECT id,app,cols,title,img FROM `".PREFIX."_apps` WHERE `goods`='1' ORDER BY id DESC LIMIT 5",1);
		$tpl->load_template('apps/newgame.tpl');

		//#################### Вывод популярных игр ######################
		foreach($sql_ as $rowsd){
			if($rowsd['cols'] >= 2){
				$num = $rowsd['cols'];
                $tpl->set('{nums}');
				$tpl->set('{title}', $rowsd['title']);
				$tpl->set('{id}', $rowsd['id']);
				$tpl->set('{ava}', '/uploads/apps/'.$rowsd['id'].'/'.$rowsd['img']);
				$tpl->compile('popgame');
			}
		}

		//#################### Вывод новых игр ######################
		foreach($sql_ as $row){
			$num = $row['cols'];
            $tpl->set('{nums}', $num.' '.gram_record($num, 'apps'));
			$tpl->set('{title}', $row['title']);
			$tpl->set('{id}', $row['id']);
			$tpl->set('{ava}', '/uploads/apps/'.$row['id'].'/'.$row['img']);
			$tpl->compile('newgame');
		}

		$tpl->load_template('apps/content.tpl');
		$tpl->set('{slider}', $tpl->result['slider']);
		$tpl->set('{mygame}', $tpl->result['mygame']);
		$tpl->set('{frgame}', $tpl->result['frgame']);
		$tpl->set('{popgame}', $tpl->result['popgame']);
		$tpl->set('{newgame}', $tpl->result['newgame']);
		$tpl->compile('content');
	}
	$db->free();
	$tpl->clear();
} else {
	$user_speedbar = 'Информация';
	msgbox('', $lang['not_logged'], 'info');
}
?>