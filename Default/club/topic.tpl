<div class="search_form_tab" style="margin-top:-9px;background:white">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
  <a href="/board{group_id}"><div><b>Обсуждения</b></div></a>
  <div class="buttonsprofileSec"><a href="/topic-{group_id}_{topic_id}"><div><b>Просмотр темы</b></div></a></div>
  <a href="/board{group_id}" onClick="boards.openmenu(); return false"><div style="fl_r"><b>Редактировать</b></div></a>
  <div class="wall_attach_menu" onmouseover="boards.openmenu(); return false" onmouseout="boards.closemenu(); return false" id="red_menu" style="display:none;margin-left: 215px;margin-top: 24px;">
    <div class="wall_attach_icon_doc" id="wall_attach_link" onclick="boards.changename('{topic_id}')">Изменить название</div>
    <div class="wall_attach_icon_doc" id="wall_attach_link" onclick="boards.favourite('{topic_id}')">{favourite}</div>
    <div class="wall_attach_icon_doc" id="wall_attach_link" onclick="boards.closed('{topic_id}')">{close}</div>
    <div class="wall_attach_icon_doc" id="wall_attach_link" onclick="boards.del_topic('{topic_id}')">Удалить тему</div>
   </div>
 </div>
</div>
<div class="note_full_title">
 <span><a href="/topic-{group_id}_{topic_id}" onClick="Page.Go(this.href); return false"><span id="title_name_topics">{title}</span></a></span><br />
 <div id="status">{status}</div>
</div>
<div id="ok_message"></div>
