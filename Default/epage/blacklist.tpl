<script type="text/javascript">
$(document).ready(function(){
	$(window).scroll(function(){
		if($(document).height() - $(window).height() <= $(window).scrollTop()+($(document).height()/2-250) || $(document).height()/2 <= $(window).scrollTop()){
			epage-.-users_page('{pid}');
		}
	});
});
</script>
<input id="page_cnt" value="1" type="hidden"/>
<input id="count" value="{count}" type="hidden"/>
<input id="pid" value="{pid}" type="hidden"/>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/epage?act=edit&pid={pid}" onClick="Page.Go(this.href); return false;"><div>Информация</div></a>
 <a href="/epage?act=users&pid={pid}" onClick="Page.Go(this.href); return false;"><div>Участники</div></a>
 <div class="buttonsprofileSec2"><a href="/epage?act=blacklist&pid={pid}" onClick="Page.Go(this.href); return false;"><div>Черный список</div></a></div>
 <a href="/epage?act=link&pid={pid}" onClick="Page.Go(this.href); return false;"><div>Ссылки</div></a>
</div>
</div>
<div style="margin-top:-120px"><a href="/{adres}" onClick="Page.Go(this.href); return false;" style="float:right;">Вернутся к сообществу</a></div>
<div class="clear"></div>
<div class="search_form_tab" style="margin-top:1px">
<input type="text" value="Введите имя и фамилию пользователя или ссылку на страницу" class="fave_input" onblur="if(this.value=='') this.value='Введите имя и фамилию пользователя или ссылку на страницу';this.style.color = '#777';" onfocus="if(this.value=='Введите имя и фамилию пользователя или ссылку на страницу') this.value='';this.style.color = '#000'" id="filter" style="margin: -1px 0px 0px 0px;width: 408px !important;">
<div class="button_blue fl_r"><button style="width:170px">Добавить в черный список</button></div></div>
<div id="gedit_users_summaryw_members" class="summary_wrap" style="margin-bottom:0px;"><div id="gedit_users_summary_members" class="summary">{title}</div></div>
[no_banned]<div class="msg_none" style="background:#fff;margin-bottom:-15px;">Нет ни одного заблокированного пользователя</div>[/no_banned]