<?php
/* 
	Appointment: ������
	File: balance.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$act = $_GET['act'];
	$user_id = $user_info['user_id'];
	$metatags['title'] = $lang['balance'];
	
	switch($act){
		
		//################### �������� ����������� ���� ###################//
		case "invite":
			$tpl->load_template('balance/invite.tpl');
			$tpl->set('{uid}', $user_id);
			$tpl->compile('content');
		break;
		
		//################### �������� ������������ ������ ###################//
		case "invited":
			$tpl->load_template('balance/invited.tpl');
			$tpl->compile('info');
			$sql_ = $db->super_query("SELECT tb1.ruid, tb2.user_name, user_search_pref, user_birthday, user_last_visit, user_photo FROM `".PREFIX."_invites` tb1, `".PREFIX."_users` tb2 WHERE tb1.uid = '{$user_id}' AND tb1.ruid = tb2.user_id", 1);
			if($sql_){
				$tpl->load_template('balance/invitedUser.tpl');
				foreach($sql_ as $row){
					$user_country_city_name = explode('|', $row['user_country_city_name']);
					$tpl->set('{country}', $user_country_city_name[0]);

					if($user_country_city_name[1])
						$tpl->set('{city}', ', '.$user_country_city_name[1]);
					else
						$tpl->set('{city}', '');

					$tpl->set('{user-id}', $row['ruid']);
					$tpl->set('{name}', $row['user_search_pref']);
					
					if($row['user_photo'])
						$tpl->set('{ava}', '/uploads/users/'.$row['ruid'].'/100_'.$row['user_photo']);
					else
						$tpl->set('{ava}', '{theme}/images/100_no_ava.png');
					
					//������� �����
					$user_birthday = explode('-', $row['user_birthday']);
					$tpl->set('{age}', user_age($user_birthday[0], $user_birthday[1], $user_birthday[2]));
					
					OnlineTpl($row['user_last_visit']);
					$tpl->compile('content');
				}
			} else
				msgbox('', '<br /><br />�� ��� ������ �� ����������.<br /><br /><br />', 'info_2');
		break;
		
		default:
		
			//################### ����� �������� ����� ###################//
			$owner = $db->super_query("SELECT user_balance FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			$tpl->load_template('balance/main.tpl');
			$row = $db->super_query("SELECT user_balance FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
			$tpl->set('{ubm}', '<b>'.$row['user_balance'].' '.gram_record($row['user_balance'], 'votes').'</b>');
			$tpl->compile('content');
	}
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>