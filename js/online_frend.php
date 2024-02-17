<?
if (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match('#linux|bsd|x11|unix|macos|macintosh|#i', $_SERVER["HTTP_USER_AGENT"]) && isset($user))
{
	
	$ONLINE_FRENDS_COUNT = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`aut`>?;", array($user['id'], time()-60));
	
	if ($ONLINE_FRENDS_COUNT > 0)
	{
		?>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script>
		function bottom_FR_open(type)
		{
			if (type == 'open')
			{
				$('#bottom_frends').css({'display':'block'});
				$('#bottom_close').css({'display':'block'});
			}
			
			if (type == 'close')
			{
				$('#bottom_frends').css({'display':'none'});
				$('#bottom_close').css({'display':'none'});	
			}
		}
		</script>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<style>
		.ok_online_frend
		{
			display: none;
		}
		
		@media screen and (min-width: 980px)
		{
			.ok_online_frend
			{
				display: block;
			}
		}
		
		.ok_mail_link
		{
			background: #d7d7d7;
			border-radius: 3px;
			-moz-border-radius: 3px;
			padding: 4px;
			margin: 2px;
			display: inline-block;
		}
		.ok_mail_link:hover
		{
			background: #e7e7e7;
		}
		.ok_hover
		{
			background: #fff;
		}
		.ok_hover:hover
		{
			background: #ececec;
		}
		</style>		
		<div style="position: fixed; bottom: 0; right: 43px; height: 70%; width: 300px; display: none; z-index: 10;" onmouseover="bottom_FR_open('close')" id="bottom_close">
		</div>
		
		<div style="position: fixed; bottom: 0; right: 60px;  width: 260px; z-index: 10;" class="ok_online_frend">
		<div style="display: inline-block; width: 240px; padding: 10px; background-color: #4C73A2; color:#ffffff; font-weight: bold;" onmouseover="bottom_FR_open('open')">
		Друзья на сайте <span style="position:absolute; right: 10px;"><?=$ONLINE_FRENDS_COUNT?></span>
		</div>
		
		<div style="display: none; border: 1px solid #7f7f7f;" id="bottom_frends">
		<table cellspacing="0" cellpadding="0">
		<?
		
		$qas = DB :: $dbh -> query("SELECT `friends`.*, `user`.* FROM `friends`, `user` WHERE `friends`.`profile`=? AND `user`.`id`=`friends`.`user` AND `user`.`aut`>? ORDER BY `user`.`aut` DESC  LIMIT 6;", array($user['id'], time()-60)); 
		
		while ($online_post = $qas -> fetch())
		{
			echo '
			<div class="user-online-widget">
			<span class="right"><a href="/modules/mail/contact/'.$online_post['id'].'"><img src="/icons/index/chat.png"></a> <br /> </span>
				'.$avatar->left_font0($online_post['id'], 40,40).'		
        '.$profile->user($online_post['id']).'
		<br>
		<span class="count_zxc">Сейчас на сайте</span>
			';
		}
		
		?>
		</table>
		<?
		
		if ($ONLINE_FRENDS_COUNT >6)
		{
			?>
			<div style="padding: 5px; background-color: #eaeaea;">
			<a href="/modules/friends/user/'.$user[id].'/">Все друзья на сайте</a>
			</div>	
			<?		
		}
		?>
		</div>
		</div>
		<?			
	}
}

?>