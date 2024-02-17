<input type="hidden" id="type" value="1"/>
<div class="miniature_box">
<div class="miniature_pos" style="width:637px;padding:0px;margin-top:{top}px">
<a class="fl_r tb_close" onClick="viiBox.clos('gifts_box', 1)" style="cursor: pointer;">Закрыть</a>
<script type="text/javascript">
$(document).ready(function(){
	$('.miniature_box').scroll(function(){
		if($('.miniature_box').scrollTop()>=parseInt($('.miniature_pos').css('margin-top'))+51){
			$('#tb_tabs').addClass('tb_fixed');
			$('.tb_tabs').css('width', '607px');
		} else $('#tb_tabs').removeClass('tb_fixed');
	});
});
</script>
<div class="tb_title" id="tb_title">Выберите подарок</div>
<div class="tb_tabs_wrap" id="tb_tabs_wrap">
	<div id="tb_tabs">
		<div class="tb_tabs clear_fix">
			<a href="/balance" class="fans_search_link" id="tb_link" style="display: block;cursor: pointer;">{balance}</a>
			<div class="progress fl_r tb_prg" id="tb_prg"></div>
			<div class="fl_l summary_tab_sel" onClick="gifts.box('{from}','','1'); return false" id="selection_1">
				<a class="summary_tab2" id="tbt_members" style="cursor: pointer;"><div class="summary_tab3"><nobr>Дружба</nobr></div></a>
			</div>
			<div class="fl_l summary_tab" onClick="gifts.box('{from}','','2'); return false" id="selection_2">
				<a class="summary_tab2" id="tbt_friends" style="cursor: pointer;"><div class="summary_tab3"><nobr>День рождения</nobr></div></a>
			</div>
			<div class="fl_l summary_tab" onClick="gifts.box('{from}','','4'); return false" id="selection_4">
				<a class="summary_tab2" id="tbt_friends" style="cursor: pointer;"><div class="summary_tab3"><nobr>Романтика</nobr></div></a>
			</div>
		</div>
		<div class="tb_tabs_sh"></div>
	</div>
</div>

<div id="tb_box_1"><div class="gifts_section clear_fix" id="gifts_box">{gifts}</div></div>
<div id="tb_box_2"></div>
<div id="tb_box_3"></div>
<div id="tb_box_4"></div>

</div>
</div>