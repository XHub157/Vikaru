<script type="text/javascript">
[new]var msg_num = parseInt($('#new_msg').text().replace(')', '').replace('(', ''))-1;
if(msg_num > 0)
	$('#new_msg').html("+"+msg_num);
else
	$('#new_msg').html('');[/new]

$(document).ready(function(){
	music.jPlayerInc();
	$('#msg_value').autoResize({extraSpace:0});
	$('#msg_value').focus();
});
</script>
<div id="jquery_jplayer"></div>
<input type="hidden" id="teck_id" value="" />
<input type="hidden" id="teck_prefix" value="" />
<input type="hidden" id="typePlay" value="standart" />

<div class="mail_message">
  
  <div class="mail_envelope_wrap">
    <div class="mail_envelope">
      <table cellpadding="0" cellspacing="0">
	   <tr><td class="mail_envelope_photo_cell">
<div class="mail_envelope_photo">
 <a href="/u{user-id}" onClick="Page.Go(this.href); return false"><img width="100" src="{ava}" alt="" /></a>
 <div>{online}</div>
</div>
</td><td>
<h4>
<div id="mail_envelope_actions">
 <div class="mem_link"">[outbox]Сообщение для [/outbox]<a href="/u{user-id}" onClick="Page.Go(this.href); return false">{name}</a></div>
 </h4>
 <div class="mail_envelope_time">{date}</div>
 <div class="mail_envelope_body wall_module wrapped" style="width: 285px;">{text}</div>
 </td></tr>
 </table>
<div class="mail_envelope_form">
<textarea class="inpst" style="height:105px;width:410px;margin-bottom:10px" id="msg_value"></textarea>
<div id="attach_files" class="no_display"></div>
<input id="vaLattach_files" type="hidden" />
<div class="clear"></div>
<div class="button_blue fl_l"><button onClick="messages.reply({user-id}, '[inbox]reply[/inbox][outbox]new[/outbox]'); return false" id="msg_sending">[inbox]Ответить[/inbox][outbox]Отправить[/outbox]</button></div>
<div class="wall_attach fl_r" onClick="wall.attach_menu('open', this.id, 'wall_attach_menu')" onMouseOut="wall.attach_menu('close', this.id, 'wall_attach_menu')" id="wall_attach" style="margin-top:0px">Прикрепить</div>
 <div class="wall_attach_menu no_display" onMouseOver="wall.attach_menu('open', 'wall_attach', 'wall_attach_menu')" onMouseOut="wall.attach_menu('close', 'wall_attach', 'wall_attach_menu')" id="wall_attach_menu" style="margin-left:282px;margin-top:20px">
 <div class="wall_attach_icon_smile" id="wall_attach_link" onClick="wall.attach_addsmile()">Смайлик</div>
 <div class="wall_attach_icon_photo" id="wall_attach_link" onClick="wall.attach_addphoto()">Фотографию</div>
 <div class="wall_attach_icon_video" id="wall_attach_link" onClick="wall.attach_addvideo()">Видеозапись</div>
 <div class="wall_attach_icon_audio" id="wall_attach_link" onClick="wall.attach_addaudio()">Аудиозапись</div>
 <div class="wall_attach_icon_doc" id="wall_attach_link" onClick="wall.attach_addDoc()">Документ</div>
</div>
<div class="clear" style="margin-top:10px"></div>
</div>
</div>
</div>
</div>
<div class="mail_envelope_shadow"></div>
<div class="msg_view_histroy" id="history_lnk" onClick="messages.history({user-id}); return false">Показать историю сообщений c {name}</div>
<span class="no_display"><input type="hidden" id="theme_value" value="{subj}" /></span>
<div class="msg_view_history_title no_display">История сообщений</div>
<div id="msg_historyies"></div>