<script type="text/javascript">
$(document).ready(function(){
	music.jPlayerInc();
	$(window).scroll(function(){
		if($(window).scrollTop() > 45){
			$('.player_container').css('position', 'fixed').css('margin-top', '-38px').css('width', '610px').css('z-index', '1');
			$('.player_shadow').show();
		} else {
			$('.player_container').css('position', 'static').css('margin-top', '-14px').css('width', '610px');
			$('.player_shadow').hide();
		}
		if($(document).height() - $(window).height() <= $(window).scrollTop()+($(document).height()/2)){
			audio.page();
		}
	});
});
</script>

<br>
<br>
<br>
<div class="leftinfo">
У пользователя еще нет аудиозаписей.
</div>
<br>
<br>
<br>
<br>
<div class="search_sotrt_tab_a" style="width:150px;margin-top:-120px;margin-left:448px;z-index:1;min-height:104px">

    [admin-add][owner]<a href="/audio{uid}" onClick="audio.addBoxComp(); return false;"><div class="addaudio"></div></a><br>[/owner]  [/admin-add]
<div style="margin-left:10px">
<a href="/?go=search&type=5&query=">Поиск</a><br><br>
<a href="/u{uid}" onClick="Page.Go(this.href); return false;"><div>
[not-owner]К странице {name}[/not-owner]</a></div>
<a href="/u{uid}" onClick="Page.Go(this.href); return false;"><div>[owner]К моей странице[/owner]</a></div>

 </div></div>