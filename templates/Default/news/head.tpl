[news]<script type="text/javascript">
var page_cnt = 1;
$(document).ready(function(){
	music.jPlayerInc();
	$('#wall_text, .fast_form_width').autoResize();
	$(window).scroll(function(){
		if($(document).height() - $(window).height() <= $(window).scrollTop()+($(document).height()/2-250)){
			news.page();
		}
	});
});
$(document).click(function(event){
	wall.event(event);
});
</script>
<div id="jquery_jplayer"></div>
<input type="hidden" id="teck_id" value="" />
<input type="hidden" id="teck_prefix" value="" />
<input type="hidden" id="typePlay" value="standart" />
<input type="hidden" id="type" value="{type}" />
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <div class="{buttonsprofileSec2-}"><a href="/feed" onClick="Page.Go(this.href); return false;"><div>Новости</div></a></div>
 <div class="{buttonsprofileSec2-notifications}"><a href="/feed&section=notifications" onClick="Page.Go(this.href); return false;"><div>Ответы</div></a></div>
 <div class="{buttonsprofileSec2-updates}"><a href="/feed&section=updates" onClick="Page.Go(this.href); return false;"><div>Обновления</div></a></div>
</div></div>
<div class="search_form_tab" style="margin-top:12px;margin-bottom:10px;border-top: 1px solid #E4E7EB;width: 621px; height: 25px; padding:5px 10px">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:15px">
<div class="{activetab-}"><a href="/feed" onClick="Page.Go(this.href); return false;"><div><b>Лента</b></div></a></div>
<div class="{activetab-photos}"><a href="/feed&section=photos" onClick="Page.Go(this.href); return false;"><div><b>Фотографии</b></div></a></div>
<div class="{activetab-videos}"><a href="/feed&section=videos" onClick="Page.Go(this.href); return false;"><div><b>Видеозаписи</b></div></a></div>
</div></div>
<div class="clear"></div><div style="margin-top:10px;"></div>[/news]
[bottom]<span id="news"></span>
[bottom]<span id="news"></span>
<div onClick="news.page()" id="wall_l_href_news" class="cursor_pointer"><div class="photo_all_comm_bg wall_upgwi" id="loading_news" style="width:750px">Показать предыдущие новости</div></div>[/bottom]