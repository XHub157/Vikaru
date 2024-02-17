<input type="hidden" id="offset" value="1"/>
<input type="hidden" id="loading" value="0"/>
<input type="hidden" id="type" value="1"/>
<div class="miniature_box">
<div class="miniature_pos" style="width:620px;padding:0px;margin-top:{top}px">
<a class="fl_r tb_close" onClick="viiBox.clos('subscribes', 1)" style="cursor: pointer;">Закрыть</a>
<script type="text/javascript">
$(document).ready(function(){
	$('.miniature_box').scroll(function(){
		if($('.miniature_box').scrollTop()>=parseInt($('.miniature_pos').css('margin-top'))+51){
			$('#tb_tabs').addClass('tb_fixed');
			$('.tb_tabs').css('width', '590px');
		} else $('#tb_tabs').removeClass('tb_fixed');
		if($('.miniature_pos').height() - $('.miniature_box').height() <= $('.miniature_box').scrollTop()+($('.miniature_pos').height()/2-250)) {
			groups.page_subscribes_groups('{pid}');
		}
	});
});
</script>
<div class="tb_title" id="tb_title">Подписчики сообщества</div>
<div class="tb_tabs_wrap" id="tb_tabs_wrap"><div id="tb_tabs"><div class="tb_tabs clear_fix"><a class="fans_search_link" href="/?go=search" id="tb_link" style="display: block; ">Общий поиск</a><div class="progress fl_r tb_prg" id="tb_prg"></div><div class="fl_l summary_tab_sel" onClick="groups.friends_groups('1','{pid}'); return false" id="all_button"><a class="summary_tab2" id="tbt_members" style="cursor: pointer;"><div class="summary_tab3"><nobr>Подписчики<span class="fans_count" id="subs_count">{count_subscribe}</span></nobr></div></a></div>[friends]<div class="fl_l summary_tab" onClick="groups.friends_groups('2','{pid}'); return false" id="friends_button"><a class="summary_tab2" id="tbt_friends" style="cursor: pointer;"><div class="summary_tab3"><nobr>Друзья в сообществе<span class="fans_count" id="fr_count">{count_friends}</span></nobr></div></a></div></div>[/friends]<div class="tb_tabs_sh"></div></div></div>

<div id="tb_members">
	<div class="fans_rows" id="fans_rowsmembers">
		{users}
	</div>
</div>

</div>
</div>