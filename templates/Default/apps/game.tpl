<script type="text/javascript">
$(document).ready(function(){
	if($('#app_container').width() > 647) {
		var wd = $('#app_container').width() - 647;
		$('.autowr').css('padding-left', wd + 'px').css('padding-right', wd + 'px').css('width', 814 + wd + 'px');
		$('.content').css('width', 654 + wd + 'px');
		$('#page_header .content').css('width', 799 + wd + 'px');
		$('.back').css('width', 799 + wd + 'px');
		$('.right').css('margin-left', 799 + wd + 'px');
		$('.cover_edit_title').css('width', 631 + wd + 'px');
	}
});
</script>
<div class="cover_edit_title doc_full_pg_top">
	<div class="fl_l margin_top_5 fl_l">
		<div><b>{title}</b>{editapps}</div>
	</div>
	
	<div class="clear"></div>
</div> <!-- // 1000 // -->
<div class="clear"></div>
<div class="apps_faslh_pos" style="padding-top:3px;"><center>
	<object width="795" height="495" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
	<param value="sameDomain" name="allowScriptAccess">
	<param value="/uploads/apps/{games}" name="movie">
	<param value="high" name="quality"> 
	<embed id="app_container" width="807" height="629" flashvars="secret={secret}&auth_key={auth_key}&api_id={api_id}&viewer_id={viewer_id}&api_settings={api_settings}" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" quality="high" src="/uploads/apps/{games}">
	</object></center>
</div>
<div class="clear"></div>