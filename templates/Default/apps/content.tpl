<script type="text/javascript">
var page_cnt_app = 1;
var page_cnt_app_old = 1;
var apphre = req_href.split('apps?i=');
if(apphre[1]) apps.view(apphre[1], req_href, '/apps');
$(window).scroll(function(){
	if($(document).height() - $(window).height() <= $(window).scrollTop()+($(document).height()/2-250)){
		apps.showMore();
	}
});
</script><style type="text/css">
<!--
/** 
 * Slideshow style rules.
 */
#slideshow {
    height: 106px;
    margin: 10px auto 25px;
    position: relative;
    width: 770px;
}
#slideshow #slidesContainer {
    height: 120px;
    margin: 0 auto;
    overflow: auto;
    position: relative;
    width: 720px;
}
#slideshow #slidesContainer .slide {
   float: left;
    height: 100px;
    width: 190px;
}


.control {
	cursor: pointer;
    display: block;
    height: 147px;
    position: absolute;
    text-indent: -10000px;
    width: 30px;
}

.slide img {
  float:right;
  margin:0 15px;
  border-radius: 10px;
}
.appsslider{
margin-top:10px;
}
-->
</style>
<script type="text/javascript">

$(document).ready(function(){
	var currentPosition = 0;
	var slideWidth = 720;
	var slides = $('.slide');
	var numberOfSlides = slides.length;

	$('#slidesContainer').css('overflow', 'hidden');
	slides
    .wrapAll('<div id="slideInner"></div>');
	$('#slideInner').css('width', slideWidth * numberOfSlides);
	$('#slideshow')
    .prepend('<span class="control" id="leftControl"></span>')
    .append('<span class="control" id="rightControl"></span>');
	manageControls(currentPosition);
	$('.control')
	.bind('click', function(){
	if(document.getElementById('slideInner').style.marginLeft=='-1440px'){currentPosition=0;}else{
		currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
		}
		manageControls(currentPosition);
		
		$('#slideInner').animate({
			'marginLeft' : slideWidth*(-currentPosition)
		});
		
	});
	function manageControls(position){
		if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
		if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show(); }
	}	
});
</script>
<div class="search_form_tab">
<img src="/templates/Default/images/loading_mini.gif" class="fl_r no_display" id="apps_se_load" style="margin-left:580px;margin-top:8px;position:absolute" />
<input type="text" value="����� �� �����" class="fave_input" id="query_games" 
	onBlur="if(this.value==''){this.value='����� �� �����';this.style.color = '#c1cad0';}" 
	onFocus="if(this.value=='����� �� �����'){this.value='';this.style.color = '#000'}" 
	onKeyPress="if(event.keyCode == 13)gSearch.go();"
	onKeyUp="apps.gSearch()"
	style="width:500px;margin:0px;color:#c1cad0" 
	maxlength="65" />
</div>


<div class="clear" style="height:20px"></div>
<div id="apps_all">
	<div class="apps_block" style="margin-right:20px">
		<div class="apps_title">��� ����������</div>
		<div id="apps_my_games">
			{mygame}
		</div>
	</div>
	<div class="apps_block">
		<div class="apps_title">���������� ������</div>
		<div id="apps_activity">
			{frgame}
		</div>
	</div>
	<div class="clear" style="height:20px"></div>
	<div class="public_wall_all_comm apps_but cursor_pointer" onClick="apps.showMoreOld()">
		<span id="apps_text_load_old">�������� ������ ����������</span>
	</div>
	<div class="apps_block" style="margin-right:20px">
		<div class="apps_top">����������</div>
		<div id="apps_pop">
			{popgame}
		</div>
	</div>
	<div class="apps_block"> 
		<div class="apps_top">�����</div>
		<div id="apps_new">
			{newgame}
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear" style="height:20px"></div>
	<div class="public_wall_all_comm apps_but2 cursor_pointer" onClick="apps.showMore()">
		<span id="apps_text_load">�������� ������ ����������</span>
	</div>
</div>
<div id="apps_search" class="no_display">
	<div class="apps_title">�������� ����</div>
	<div id="apps_search_res"></div>
</div>
<div class="clear"></div>