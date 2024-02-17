<script type="text/javascript">
$(document).ready(function(){
	vii_interval = setInterval('im.updateDialogs()', 2000);
	var query = $('#msg_query').val();
	if(query == 'Поиск по сообщений по имени')
		$('#msg_query').css('color', '#c1cad0');
});
</script>
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:10px"><div class="buttonsprofileSec2"><a href="/im" onClick="Page.Go(this.href); return false;"><div>Диалоги</div></a></div>[dialog_owner]<a href="/messages" onClick="Page.Go(this.href); return false;">Просмотр диалогов</a>[/dialog_owner]</div><br><div class="clear"></div>
<div class="clear"></div><div style="margin-top:-1px"></div>
<div class="msg_se_bg"><input type="text" value="Поиск по сообщений по имени" class="fave_input msg_se_inp fl_l" onblur="if(this.value==''){this.value='Поиск по сообщений по имени';this.style.color = '#c1cad0';}" onfocus="if(this.value=='Поиск по сообщений по имени'){this.value='';this.style.color = '#000'}" id="msg_query" maxlength="130" onKeyPress="if(event.keyCode == 13)messages.search(1);" style="width:435px!important" />
<div class="button_blue fl_l msg_pad_top"><button onClick="messages.search(1); return false">Найти отправленные</button></div>
<div class="clear"></div>
</div>
<div class="msg_speedbar" style="margin-top:10px">{msg-cnt} &nbsp;|&nbsp; <a href="/" style="font-weight:normal" onClick="im.settTypeMsg(); return false" id="settTypeMsg">Показать в виде сообщений</a></div>
<div id="body_messages">