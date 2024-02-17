<div id="gedit_user_members" class="gedit_user">
	<div class="gedit_user_bigph_wrap fl_l">
		<a class="gedit_bigph" onClick="Photo.Profile('{uid}', '{ava}'); return false"><span class="gedit_bigph_label" style="cursor:pointer">Увеличить</span></a>
		<a class="gedit_user_thumb" href="/{adres}"><img class="gedit_user_img" src="{ava_photo}"></a>
	</div>
	<div class="gedit_user_info fl_l">
		<div class="gedit_user_name"><a class="gedit_user_lnk" href="/{adres}" id="gedit_user_name{uid}">{name}</a></div>
		<div class="gedit_user_level {view_tags}" id="gedit_user_level{uid}">{tags}</div>
		[online]<div class="gedit_user_online">Online</div>[/online]
		<div class="gedit_user_btns"></div>
	</div>
	[yes_admin]
	<div class="gedit_user_actions fl_r">
		<span id="gedit_users{uid}">
		<a class="gedit_user_action" onClick="epage.editadmin('{uid}','editadmin'); return false" style="cursor:pointer">Редактировать</a>
		<a class="gedit_user_action" onClick="epage.editadmin('{uid}','deleteadmin'); return false" style="cursor:pointer">Разжаловать руководителя</a>
		<a class="gedit_user_action" onClick="epage.deleteusers('{uid}'); return false" id="gedit_user_action{uid}" style="cursor:pointer">Удалить из сообщества</a>
		</span>
		<a class="gedit_user_action" onClick="epage.rebornusers('{uid}'); return false" id="gedit_user_actions{uid}" style="display:none" style="cursor:pointer">Восстановить</a>
	</div>
	[/yes_admin]
	[no_admin]
	<div class="gedit_user_actions fl_r">
		<span id="gedit_users{uid}">
		<a class="gedit_user_action" onClick="epage.editadmin('{uid}','newadmin'); return false" style="cursor:pointer">Назначить руководителем</a>
		<a class="gedit_user_action" onClick="epage.deleteusers('{uid}'); return false" id="gedit_user_action{uid}" style="cursor:pointer">Удалить из сообщества</a>
		</span>
		<a class="gedit_user_action" onClick="epage.rebornusers('{uid}'); return false" id="gedit_user_actions{uid}" style="display:none" style="cursor:pointer">Восстановить</a>
	</div>
	[/no_admin]
</div>