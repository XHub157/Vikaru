<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
  <a href="/board{group-id}" onClick="Page.Go(this.href); return false;"><div>Обсуждения</div></a>
  <div class="buttonsprofileSec2"><a href="/board{group-id}/new" onClick="Page.Go(this.href); return false;"><div>Новая тема</div></a></div>
 </div>
</div>
<div style="margin-top:12px"></div>
<div class="note_full_title" style="padding-left: 40px;margin-bottom: -16px;height: 375px;">
 <div class="videos_text">Заголовок:</div>
 <input type="text" class="videos_input" id="title_new_topic" maxlength="255" style="width: 545px;" />
 <div class="videos_text">Текст:</div>
 <textarea type="text" class="videos_input" id="text_new_topic" maxlength="255" style="width: 545px;height:220px"/></textarea>
<div class="button_div fl_l"><button onClick="boards.newtopic('{group-id}'); return false" id="send">Создать тему</button></div>
</div>