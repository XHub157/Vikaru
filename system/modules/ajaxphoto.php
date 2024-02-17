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
	$gcount = 10;
	$limit_page = ($page-1)*$gcount;
	

	//################### Страница с новыми фотографиями ###################//
			$rowMy = $db->super_query("SELECT user_new_mark_photos FROM `".PREFIX."_users` WHERE user_id = '".$user_info['user_id']."'");

			//Загрузка верхушки
			$tpl->load_template('albums_top_newphotos.tpl');
			$tpl->set('{user-id}', $user_info['user_id']);
			$tpl->set('{num}', $rowMy['user_new_mark_photos']);
			$tpl->compile('info');

			//Выводим сами фотографии
			if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
			$gcount = 25;
			$limit_page = ($page-1) * $gcount;
			$sql_ = $db->super_query("SELECT tb1.mphoto_id, tb2.photo_name, album_id, user_id FROM `".PREFIX."_photos_mark` tb1, `".PREFIX."_photos` tb2 WHERE tb1.mphoto_id = tb2.id AND tb1.mapprove = 0 AND tb1.muser_id = '".$user_info['user_id']."' ORDER by `mdate` DESC LIMIT ".$limit_page.", ".$gcount, 1);
			$tpl->load_template('albums_top_newphoto.tpl');
			if($sql_){
				foreach($sql_ as $row){
					$tpl->set('{uid}', $row['user_id']);
					$tpl->set('{id}', $row['mphoto_id']);
					$tpl->set('{aid}', '_'.$row['album_id']);
					$tpl->set('{photo}', '/uploads/users/'.$row['user_id'].'/albums/'.$row['album_id'].'/c_'.$row['photo_name']);
					$tpl->compile('content');
				}
				$rowCount = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_photos_mark` WHERE mapprove = 0 AND muser_id = '".$user_info['user_id']."'");
				navigation($gcount, $rowCount['cnt'], $config['home_url'].'albums/newphotos/');
			} else
				msgbox('', '<br /><br /><br />Отметок не найдено.<br /><br /><br />', 'info_2');
				
				
			
	AjaxTpl();
	die();
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>