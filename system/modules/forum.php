<?

	/* 
		@params[appointment]: Форум
		@params[file]: forum.php
		@params[author]: Мантикор
	 
	*/

if(!defined('MOZG')) die('Hacking attempt!');
if($ajax == 'yes') NoAjaxQuery();

if($logged){

	$user_id = $user_info['user_id'];
	$act = $_GET['act'];
	
	$metatags['title'] = base64_decode('1O7w8+wgLSDx4u7h7uTt7uUg7uH55e3o5Q==');
	
	switch($act) {
	
		default:
		
			$tpl->load_template('forums/main.tpl');
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