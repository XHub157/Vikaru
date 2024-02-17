<link rel="stylesheet" href="{theme}/apps/css/apps.css?51" type="text/css" />
<link type="text/css" rel="stylesheet" href="{theme}/style/apps_edit.css"></link>
<link rel="stylesheet" href="{theme}/css/wk.css" type="text/css" /></link>
<script type="text/javascript" src="{theme}/js/apps_edit.js?1"></script>
<div class="search_form_tab" style="margin-top:-9px">
	<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
		<a href="/editapp/info_{id}">
			<div><b>Информация</b>
			</div>
		</a>
		<a href="/editapp/options_{id}">
			<div><b>Настройки</b>
			</div>
		</a>
		<div class="buttonsprofileSec">
			<a href="/editapp/payments_{id}">
				<div><b>Платежи</b>
				</div>
			</a>
		</div>
		<a href="/editapp/admins_{id}">
			<div><b>Администраторы</b>
			</div>
		</a>
		<div class="fl_r">
			<a href="/app{id}" onclick="return nav.go(this, event, {noback: true})">К приложению</a>
		</div>
	</div>
</div>
<div id="content">
	<div id="app_edit">
		<div id="app_edit_error_wrap">
			<div id="app_edit_error"></div>
		</div>
		<div id="app_edit_wrap">
			<div class="white">
				<div id="app_edit_cont">
					<div class="app_stat_head no_padd no_lpadd">История платежей - На балансе: {balance} голосов</div>
					<div id="app_payments_settings_err" class="error" style="display: none; margin: 0 0 15px 0;"></div>
					<div id="app_user_cont" style="margin-bottom: 20px;">
						<table id="app_payments_table" cellspacing="0" cellpadding="0" class="wk_table">
							<tbody>
								<tr>
									<th style="width:300px">Пользователь</th>
									<th>голосов</th>
									<th style="width:155px; padding: 1px 5px 2px;">
										<div class="app_time_label">Время</div>
									</th>
								</tr>{payments}</tbody>
						</table>
					</div>
					<br class="clear">
				</div>
			</div>
		</div>
	</div>
</div>