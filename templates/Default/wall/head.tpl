<script type="text/javascript">
$(document).ready(function(){
	music.jPlayerInc();
	$('.fast_form_width').autoResize();
});
$(document).click(function(event){
	wall.event(event);
});
</script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <div class="{activetab-own}"><a href="/wall{uid}_sec=own" onClick="Page.Go(this.href); return false;"><div>Записи {name}</div></a></div>
 <div class="{activetab-}"><a href="/wall{uid}" onClick="Page.Go(this.href); return false;"><div>Все записи</div></a></div>
 [record-tab]<div class="{activetab-record}"><a href="/wall{uid}_{rec-id}" onClick="Page.Go(this.href); return false;"><div>Запись на стене</div></a></div>[/record-tab]
</div></div>
<div style="margin-top:-20px"><div style="float:right;"><a href="/u{uid}" onClick="Page.Go(this.href); return false;">Перейти к странице</div></div></a>

<div class="clear"></div><div style="margin-top:24px;"></div>
<div id="jquery_jplayer"></div>
<input type="hidden" id="teck_id" value="" />
<input type="hidden" id="teck_prefix" value="" />
<input type="hidden" id="typePlay" value="standart" />
<div class="newmes" id="wall_tab" style="border-bottom:0px;margin-bottom:-5px;margin:-12px;margin-top:-23px">
  <div style="margin:8px">

 <input type="hidden" value="Что у Вас нового?" id="wall_input_text" />
  <input type="text" class="wall_inpst" value="Что у Вас нового?" onMouseDown="wall.form_open(); return false" id="wall_input" style="width:584px;padding-left:5px;padding-bottom:41px;" />
  <div class="no_display" id="wall_textarea">

   <textarea id="wall_text" class="wall_inpst wall_fast_opened_texta" style="width:585px;height:50px;
	onKeyUp="wall.CheckLinkText(this.value)"
	onBlur="wall.CheckLinkText(this.value, 1)"
   >
   </textarea>
</div>
<div id="attach_files" class="margin_top_10 no_display"></div>
   <div id="attach_block_lnk" class="no_display clear">
   <div class="attach_link_bg">
    <div align="center" id="loading_att_lnk"><img src="{theme}/images/loading_mini.gif" style="margin-bottom:-2px" /></div>
    <img src="" align="left" id="attatch_link_img" class="no_display cursor_pointer" onClick="wall.UrlNextImg()" />
	<div id="attatch_link_title"></div>
	<div id="attatch_link_descr"></div>
	<div class="clear"></div>
   </div>
   <div class="attach_toolip_but"></div>
   <div class="attach_link_block_ic fl_l"></div><div class="attach_link_block_te"><div class="fl_l">Ссылка: <a href="/" id="attatch_link_url" target="_blank"></a></div><img class="fl_l cursor_pointer" style="margin-top:2px;margin-left:5px" src="{theme}/images/close_a.png" onMouseOver="myhtml.title('1', 'Не прикреплять', 'attach_lnk_')" id="attach_lnk_1" onClick="wall.RemoveAttachLnk()" /></div>
   <input type="hidden" id="attach_lnk_stared" />
   <input type="hidden" id="teck_link_attach" />
   <span id="urlParseImgs" class="no_display"></span>
   </div>
   <div class="clear"></div>
   <div id="attach_block_vote" class="no_display">
   <div class="attach_link_bg">
	<div class="texta">Тема опроса:</div><input type="text" id="vote_title" class="inpst" maxlength="80" value="" style="width:330px;margin-left:5px" 
		onKeyUp="$('#attatch_vote_title').text(this.value)"
	/><div class="mgclr"></div>
	<div class="texta">Варианты ответа:<br /><small><span id="addNewAnswer"><a class="cursor_pointer" onClick="Votes.AddInp()">добавить</a></span> | <span id="addDelAnswer">удалить</span></small></div><input type="text" id="vote_answer_1" class="inpst" maxlength="80" value="" style="width:330px;margin-left:5px" /><div class="mgclr"></div>
	<div class="texta">&nbsp;</div><input type="text" id="vote_answer_2" class="inpst" maxlength="80" value="" style="width:330px;margin-left:5px" /><div class="mgclr"></div>
	<div id="addAnswerInp"></div>
	<div class="clear"></div>
   </div>
   <div class="attach_toolip_but"></div>
   <div class="attach_link_block_ic fl_l"></div><div class="attach_link_block_te"><div class="fl_l">Опрос: <a id="attatch_vote_title" style="text-decoration:none;cursor:default"></a></div><img class="fl_l cursor_pointer" style="margin-top:2px;margin-left:5px" src="{theme}/images/close_a.png" onMouseOver="myhtml.title('1', 'Не прикреплять', 'attach_vote_')" id="attach_vote_1" onClick="Votes.RemoveForAttach()" /></div>
   <input type="hidden" id="answerNum" value="2" />
   </div>
   <div class="clear"></div>
   <input id="vaLattach_files" type="hidden" />
   <div class="clear"></div>
   <div class="button_blue fl_l margin_top_10"><button onClick="wall.send_w(); return false" id="wall_send">Отправить</button></div>
   <div class="wall_attach fl_r" onClick="wall.attach_menu('open', this.id, 'wall_attach_menu')" onMouseOut="wall.attach_menu('close', this.id, 'wall_attach_menu')" id="wall_attach">Прикрепить</div>
   <div class="wall_attach_menu no_display" onMouseOver="wall.attach_menu('open', 'wall_attach', 'wall_attach_menu')" onMouseOut="wall.attach_menu('close', 'wall_attach', 'wall_attach_menu')" id="wall_attach_menu" style="margin-left:508px">
    <div class="wall_attach_icon_smile" id="wall_attach_link" onClick="wall.attach_addphoto()">Фотографию</div>
    <div class="wall_attach_icon_video" id="wall_attach_link" onClick="wall.attach_addvideo()">Видеозапись</div>
    <div class="wall_attach_icon_photo" id="wall_attach_link" onClick="wall.attach_addaudio()">Аудиозапись</div>
    <div class="wall_attach_icon_audio" id="wall_attach_link" onClick="wall.attach_addDoc()">Документ</div>
    <div class="wall_attach_icon_doc" id="wall_attach_link" onClick="$('#attach_block_vote').slideDown('fast');wall.attach_menu('close', 'wall_attach', 'wall_attach_menu');$('#vote_title').focus();$('#vaLattach_files').val($('#vaLattach_files').val()+'vote|start||')">Опрос</div>
   </div>
  </div>
  <div class="clear"></div>
 </div>
<div class="clear"></div><div style="margin-top:10px;"></div>
<div id="jquery_jplayer"></div>
<input type="hidden" id="teck_id" value="" />
<input type="hidden" id="teck_prefix" value="" />
<input type="hidden" id="typePlay" value="standart" />