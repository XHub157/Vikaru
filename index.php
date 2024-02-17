<?php
if(isset($_POST["PHPSESSID"])){
session_id($_POST["PHPSESSID"]);
}
@session_start();
@ob_start();
@ob_implicit_flush(0);

@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

define('MOZG', true);
define('ROOT_DIR', dirname (__FILE__));
define('ENGINE_DIR', ROOT_DIR.'/system');

header('Content-type: text/html; charset=windows-1251');
	
//AJAX
$ajax = $_POST['ajax'];

$logged = false;
$user_info = false;

include ENGINE_DIR.'/init.php';

//???? ???? ??????? ?? ??? ??????, ?? ????????? ?? ???????? ? ??????
if($_GET['reg']) $_SESSION['ref_id'] = intval($_GET['reg']);

//??????????? ????????
if(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) $xBrowser = 'ie6';
elseif(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) $xBrowser = 'ie7';
elseif(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) $xBrowser = 'ie8';
if($xBrowser == 'ie6' OR $xBrowser == 'ie7' OR $xBrowser == 'ie8')
	header("Location: /badbrowser.php");

//????????? ???-?? ????? ????????
$CacheNews = mozg_cache('user_'.$user_info['user_id'].'/new_news');
if($CacheNews){
	$new_news = "<div class=\"newNews2\" style=\"margin-left:150px;margin-top:1px\">{$CacheNews}</div>";
	$news_link = '/notifications';
}
//Новое сообщение
$user_pm_num = $user_info['user_pm_num'];
if($user_pm_num and $user_pm_num != 0 and $user_pm_num != '' and $user_pm_num != null and $user_pm_num != ' ')
	$user_pm_num = "+{$user_pm_num}";
else
	$user_pm_num = '+';
	
//Друзья
$user_friends_demands = $user_info['user_friends_demands'];
if($user_friends_demands){
	$demands = "+{$user_friends_demands}";
	$requests_link = '/requests';
	$tpl->set('[demands-log]','');
	$tpl->set('[/demands-log]','');
} else {
	$demands = '+';
	$tpl->set_block("'\\[demands-log\\](.*?)\\[/demands-log\\]'si","");
}
//Техническая поддержка
$user_support = $user_info['user_support'];
if($user_support)
	$support = "+{$user_support}";
else
	$support = '+';


$user_new_mark_photos = $user_info['user_new_mark_photos'];
if($user_new_mark_photos){
	$user_new_mark_photos = "+{$user_new_mark_photos}";
	$new_photos_link = '/newphotos';
	$tpl->set('[photo-log]','');
	$tpl->set('[/photo-log]','');
} else {
	$user_new_mark_photos = '+';
	$tpl->set_block("'\\[photo-log\\](.*?)\\[/photo-log\\]'si","");
}

//################### Вывод текущего счета ###################//
						$owner = $db->super_query("SELECT user_balance FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
						$tpl->set('{ubm}', $owner['user_balance']);	

//???? ??????? AJAX ?? ????????? ???.
if($ajax == 'yes'){

	//???? ???? POST ?????? ? ???????? AJAX, ? $ajax ?? ????????? "yes" ?? ?? ??????????
	if($_SERVER['REQUEST_METHOD'] == 'POST' AND $ajax != 'yes')
		die('??????????? ??????');

	if($spBar)
		$ajaxSpBar = "$('#speedbar').show().html('{$speedbar}')";
	else
		$ajaxSpBar = "$('#speedbar').hide()";

	$result_ajax = <<<HTML
<script type="text/javascript">
document.title = '{$metatags['title']}';
{$ajaxSpBar};
document.getElementById('new_msg').innerHTML = '{$user_pm_num}';
document.getElementById('new_news').innerHTML = '{$new_news}';
document.getElementById('new_support').innerHTML = '{$support}';
document.getElementById('news_link').setAttribute('href', '/news{$news_link}');
document.getElementById('new_requests').innerHTML = '{$demands}';
document.getElementById('new_photos').innerHTML = '{$new_photos}';
document.getElementById('requests_link_new_photos').setAttribute('href', '/albums/{$new_photos_link}');
document.getElementById('requests_link').setAttribute('href', '/friends{$requests_link}');
</script>
{$tpl->result['info']}{$tpl->result['content']}
HTML;
	echo str_replace('{theme}', '/templates/'.$config['temp'], $result_ajax);

	$tpl->global_clear();
	$db->close();

	if($config['gzip'] == 'yes')
		GzipOut();
		
	die();
} 

if($config['temp'] == 'mobile'){
	
	
		$new_actions = $demands+$new_news+$user_pm_num+$support;
		if($new_actions > 0)
			$tpl->set('{new-actions}', $new_actions);
		else
			$tpl->set('{new-actions}', '');
		
		if($user_info['user_photo'])
			$ava =$config['home_url'].'/uploads/users/'.$user_info['user_id'].'/50_'.$user_info['user_photo'];
		else 
			$ava = '/templates/Default/images/no_ava_50.gif';

		$tpl->set('{mobile-speedbar}', $speedbar);
		$tpl->set('{my-name}', $user_info['user_search_pref']);
		$tpl->set('{status-mobile}', $user_info['user_status']);
		$tpl->set('{my-ava}', $ava);
		
		
	}

//???? ????????? ? ?????? ??????????? ??? ??????? ? ???? ?? ??????????? ?? ?????????? ???????????
if($go == 'register' OR $go == 'main' AND !$logged)
	include ENGINE_DIR.'/modules/register_main.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	$tpl->load_template('main2.tpl');

}else{
	$tpl->set('{titles}', $metatag);
	$tpl->load_template('main.tpl');
}

//if($user_id != 0) {
	$tpl->set('[on_admin]','');
	$tpl->set('[/on_admin]','');
	$tpl->set_block("'\\[dont_admin\\](.*?)\\[/dont_admin\\]'si","");
/*} else {
	$tpl->set('[dont_admin]','');
	$tpl->set('[/dont_admin]','');
	$tpl->set_block("'\\[on_admin\\](.*?)\\[/on_admin\\]'si","");
}*/

//???? ???? ?????????
if($logged){
	$tpl->set_block("'\\[not-logged\\](.*?)\\[/not-logged\\]'si","");
	$tpl->set('[logged]','');
	$tpl->set('[/logged]','');
	$aliases = $db->super_query("SELECT alias FROM `".PREFIX."_users` WHERE user_id = '{$user_id}'");
	if($aliases['alias']) $tpl->set('{my-page-link}', '/u'.$user_info['user_id']);
	else $tpl->set('{my-page-link}', '/u'.$user_info['user_id']);
	$tpl->set('{my-id}', $user_info['user_id']);
	
	//Друзья
	if($user_friends_demands and $user_friends_demands!=0 and $demands!=0){
		$tpl->set('[demands-log]','');
		$tpl->set('[/demands-log]','');
		$tpl->set('{demands}', $demands);
		$tpl->set('{requests-link}', $requests_link);
	} else {
		$tpl->set_block("'\\[demands-log\\](.*?)\\[/demands-log\\]'si","");
		$tpl->set('{demands}', '');
		$tpl->set('{requests-link}', '');
	}
	
	if($user_new_mark_photos and $user_new_mark_photos!=0 and $user_new_mark_photos!=0){
		$tpl->set('[photo-log]','');
		$tpl->set('[/photo-log]','');
		$tpl->set('{new_photos}', $user_new_mark_photos);
		$tpl->set('{new_photos-link}', $new_photos_link);
	} else {
		$tpl->set_block("'\\[photo-log\\](.*?)\\[/photo-log\\]'si","");
		$tpl->set('{new_photos}', '');
		$tpl->set('{new_photos-link}', '');
	}
	
	//online friends		
	$online_cnt = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_users` WHERE user_last_visit >= '{$online_time}'");
	$tpl->set('{ofn}', $online_cnt['cnt']);	
	
	//Новости
	if($CacheNews){
		$tpl->set('{new-news}', $new_news);
		$tpl->set('{news-link}', $news_link);
	} else {
		$tpl->set('{new-news}', '');
		$tpl->set('{news-link}', '');
	}
	
	//Сообщения
	if($user_pm_num and $user_pm_num != 0 and $user_pm_num != '' and $user_pm_num != null and $user_pm_num != ' ') {
		$tpl->set('{msg}', $user_pm_num);
		$tpl->set('{msg2}', $user_pm_num);
		$tpl->set('[msg-log]','');
		$tpl->set('[/msg-log]','');
	} else {
		$tpl->set_block("'\\[msg-log\\](.*?)\\[/msg-log\\]'si","");
		$tpl->set('{msg}', '+');
		$tpl->set('{msg2}', '');
	}
	
	$tpl->set('{server_time}', $server_time);
	
	//блоки технической поддержки
	if($user_support) {
		$tpl->set('{new-support}', $support);
		$tpl->set('[sup-log]','');
		$tpl->set('[/sup-log]','');
	} else {
		$tpl->set_block("'\\[sup-log\\](.*?)\\[/sup-log\\]'si","");
		$tpl->set('{new-support}', '');
	}	

	//UBM
	if($CacheGift){
		$tpl->set('{new-ubm}', $new_ubm);
		$tpl->set('{ubm-link}', $gifts_link);
	} else {
		$tpl->set('{new-ubm}', '');
		$tpl->set('{ubm-link}', $gifts_link);
	}

} else {
	$tpl->set_block("'\\[logged\\](.*?)\\[/logged\\]'si","");
	$tpl->set('[not-logged]','');
	$tpl->set('[/not-logged]','');
	$tpl->set('{my-page-link}', '');
}

$tpl->set('{header}', $headers);
$tpl->set('{speedbar}', $speedbar);
$tpl->set('{info}', $tpl->result['info']);
$tpl->set('{content}', $tpl->result['content']);

if($spBar)
	$tpl->set_block("'\\[speedbar\\](.*?)\\[/speedbar\\]'si","");
else {
	$tpl->set('[speedbar]','');
	$tpl->set('[/speedbar]','');
}

//BUILD JS
if($config['gzip_js'] == 'yes')
	if($logged)
		$tpl->set('{js}', '<script type="text/javascript" src="/min/index.php?charset=windows-1251&amp;g=general&amp;6"></script>');
	else
		$tpl->set('{js}', '<script type="text/javascript" src="/min/index.php?charset=windows-1251&amp;g=no_general&amp;6"></script>');
else
	if($logged)
		$tpl->set('{js}', '<script type="text/javascript" src="{theme}/js/jquery.lib.js"></script><script type="text/javascript" src="{theme}/js/main.js"></script><script type="text/javascript" src="{theme}/js/profile.js"></script>');
	else
		$tpl->set('{js}', '<script type="text/javascript" src="{theme}/js/jquery.lib.js"></script><script type="text/javascript" src="{theme}/js/main.js"></script>');

$tpl->compile('main');

echo str_replace('{theme}', '/templates/'.$config['temp'], $tpl->result['main']);

$tpl->global_clear();
$db->close();

if($config['gzip'] == 'yes')
	GzipOut();
?>