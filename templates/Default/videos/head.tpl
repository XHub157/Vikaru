<script type="text/javascript">
$(document).ready(function(){
	videos.scroll();
});
</script>
<div class="sft" style="margin-top:-6px">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <div class="buttonsprofileSec2"><a href="/videos/{user-id}" onClick="Page.Go(this.href); return false;"><div>[owner]Все видеозаписи[/owner][not-owner]Видеозаписи {name}[/not-owner]</div></a></div>
</div>
</div>
<div class="search_form_tab" style="margin-top:12px;border-top: 1px solid #E4E7EB;">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
<input type="text" class="fave_input fl_l" onblur="if(this.value=='') this.value='Поиск по видеозаписям';this.style.color = '#c1cad0';" onfocus="if(this.value=='Начните вводить имя или название') this.value='';this.style.color = '#000'" id="filter" style="margin-left: 0px; width: 300px !important;margin-top: 0px;">
 <div class="button_blue fl_r">[admin-video-add][owner]<button onClick="videos.add(); return false;">Добавить видеоролик</button>[/owner][/admin-video-add]</div>
 [not-owner]<div class="fl_r"><a href="/u{user-id}" onClick="Page.Go(this.href); return false;">К странице {name}</a></div>[/not-owner]
</div>
</div>
<div class="clear"></div><div style="margin-top:10px;"></div>
<input type="hidden" value="{user-id}" id="user_id" />
<input type="hidden" id="set_last_id" />
<input type="hidden" id="videos_num" value="{videos_num}" />