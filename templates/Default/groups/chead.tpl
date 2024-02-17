<script type="text/javascript" src="{theme}/js/groups.filter.js"></script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
  <div class="buttonsprofileSec2"><a href="/clubs" onClick="Page.Go(this.href); return false;"><div>Сообщества</div></a></div>
  <a href="/clubs?act=admin" onClick="Page.Go(this.href); return false;"><div>Управление</div></a>
 </div>
</div>
<div style="margin-top:-35px"><div style="float:right;"><a href="/clubs" onClick="groups.created(); return false">Создать сообщество</div></div></a>
<div class="search_form_tab" style="margin-top:35px;border-top: 1px solid #E4E7EB;">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
<input type="text" value="Начните вводить имя или название" class="fave_input fl_l" onblur="if(this.value=='') this.value='Начните вводить имя или название';this.style.color = '#c1cad0';" onfocus="if(this.value=='Начните вводить имя или название') this.value='';this.style.color = '#000'" id="filter" style="margin-left: 0px; width: 300px !important;margin-top: 0px;">
 <div class="fl_r"><a href="/groups" onclick="Page.Go(this.href); return false;"><div><b>Публичные страницы</b></div></a></div>
 <div class="buttonsprofileSec fl_r"><a href="/clubs" onclick="Page.Go(this.href); return false;"><div><b>Группы</b></div></a></div>
</div>
</div>
<div class="margin_top_10"></div><div class="allbar_title" [yes]style="margin-bottom:0px;border-bottom:0px"[/yes]>[yes]Вы состоите в {num}[/yes][no]Вы не состоите ни в одной группе.[/no]</div>
[no]<div class="info_center"><br /><br />
Вы пока не состоите ни в одной группе. 
<br /><br />
Вы можете <a href="/groups" onClick="groups.created(); return false">создать группу</a> или воспользоваться <a href="/" onClick="gSearch.open_tab(); gSearch.select_type('6', 'по группам'); return false" id="se_link">поиском по группам</a>.<br /><br /><br />
</div>[/no]
<div id="no_search" class="no_display">
<div class="info_center"><br /><br />Группы с данным названием не найдено!
<br /><br />
Вы можете <a href="/groups" onClick="groups.created(); return false">создать группу</a> или воспользоваться <a href="/" onClick="gSearch.open_tab(); gSearch.select_type('6', 'по группам'); return false" id="se_link">поиском по группам</a>.<br /><br /><br />
</div></div>