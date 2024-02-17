<link rel="stylesheet" href="{theme}/apps/css/apps.css?51" type="text/css" /></link>
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
		<a href="/editapp/payments_{id}">
			<div><b>Платежи</b>

			</div>
		</a>
		<a href="/editapp/admins_{id}">
			<div class="buttonsprofileSec">
				<div><b>Администраторы</b>

				</div>
		</a>
		</div>
		<div class="fl_r">
			<a href="/app{id}" onclick="return nav.go(this, event, {noback: true})">К приложению</a>
		</div>
	</div>
</div>
<div id="content" style="margin-top: 15px;">
	<div id="app_edit">
		<input type="hidden" id="app_id" value="{id}">
		<input type="hidden" id="app_hash" value="{hash}">
		<div class="button_div fl_r" style="margin-top: 22px;">
			<button id="apps_edit_search_btn" onclick="AppsEdit.uSearch()">Добавить администратора</button>
		</div>
		<div class="app_edit_main">
			<div id="app_edit_error_wrap">
				<div id="app_edit_error"></div>
			</div>
			<div id="app_edit_wrap">
				<div id="apps_edit_admins">
					<div id="apps_edit_summary_wrap" class="summary_wrap" style="display: block;">
						<div id="apps_edit_summary" class="summary">У приложения 1 администратор</div>
					</div>
					<div id="apps_edit_users_rows" class="clear_fix">
						<div id="apps_edit_admin{uid}" class="apps_edit_user clear_fix">
	<div class="apps_edit_user_thumb_wrap fl_l">
		<a class="apps_edit_user_thumb" href="/id{uid}">
			<img class="apps_edit_user_img" src="{img}" width="32">
		</a>
	</div>
	<div class="apps_edit_user_info fl_l">
		<a class="apps_edit_user_name" href="/id{uid}">{name} </a>  <b> главный администратор</b>
	</div>
	<div class="apps_edit_user_actions fl_r"><a class="apps_edit_user_action" onclick="AppsEdit.uRemoveAdmin({uid})">Разжаловать</a></div>
</div>
						{all}
						
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>