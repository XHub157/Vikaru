<script type="text/javascript">
$(document).ready(function(){
	music.jPlayerInc();
	[search-tab]$('#page').css('min-height', '444px');
	$(window).scroll(function(){
		if($(window).scrollTop() > 103)
			$('.search_sotrt_tab').css('position', 'fixed').css('margin-top', '-80px');
		else
			$('.search_sotrt_tab').css('position', 'absolute').css('margin-top', '39px');
	});[/search-tab]
	myhtml.checked(['{checked-online}', '{checked-user-photo}']);	
	var query = $('#query_full').val();
	if(query == '������� ������� ����� ����� ��� ���')
		$('#query_full').css('color', '#c1cad0');
});
</script>
<div class="search_form_tab">
<input type="text" value="{query}" class="fave_input" id="query_full" 
	onBlur="if(this.value==''){this.value='������� ������� ����� ����� ��� ���';this.style.color = '#c1cad0';}" 
	onFocus="if(this.value=='������� ������� ����� ����� ��� ���'){this.value='';this.style.color = '#000'}" 
	onKeyPress="if(event.keyCode == 13)gSearch.go();" 
	style="width:500px;margin:0px;color:#000" 
maxlength="65" />
<div class="button_blue fl_r"><button onClick="gSearch.go(); return false">�����</button></div>
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="margin-top:10px;height:22px">
 <div class="{activetab-1}"><a href="/?{query-people}" onClick="Page.Go(this.href); return false;"><div><b>����</b></div></a></div>
 [search-tab]<div class="{activetab-4}"><a href="/?go=search{query-groups}" onClick="Page.Go(this.href); return false;"><div><b>����������</b></div></a></div>[/search-tab]
 [search-tabg]<div class="{activetab-4}"><a href="/?go=search{query-groups}" onClick="Page.Go(this.href); return false;"><div><b>����������</b></div></a></div>[/search-tabg]
 [search-tabv]<div class="{activetab-4}"><a href="/?go=search{query-groups}" onClick="Page.Go(this.href); return false;"><div><b>����������</b></div></a></div>[/search-tabv]
 [search-taba]<div class="{activetab-4}"><a href="/?go=search{query-groups}" onClick="Page.Go(this.href); return false;"><div><b>����������</b></div></a></div>[/search-taba]

 [search-tabc]<div class="{activetab-6}"><a href="/?go=search{query-clubs}" onClick="Page.Go(this.href); return false;"><div><b>����������</b></div></a></div>[/search-tabc]
 <div class="{activetab-5}"><a href="/?go=search{query-audios}" onClick="Page.Go(this.href); return false;"><div><b>�����������</b></div></a></div>
 <div class="{activetab-2}"><a href="/?go=search{query-videos}" onClick="Page.Go(this.href); return false;"><div><b>�����������</b></div></a></div>
</div>
<input type="hidden" value="{type}" id="se_type_full" />
</div>

[search-tab]<div class="search_sotrt_tab">
   
 <b>��������</b>
 <div class="search_clear"></div>
   
 <div class="padstylej"><select name="country" id="country" class="inpst search_sel" onChange="Profile.LoadCity(this.value); gSearch.go();"><option value="0">����� ������</option>{country}</select><img src="{theme}/images/loading_mini.gif" alt="" class="load_mini" id="load_mini" /></div>
 <div class="search_clear"></div>

 <div class="padstylej"><select name="city" id="select_city" class="inpst search_sel" onChange="gSearch.go();"><option value="0">����� �����</option>{city}</select></div>
 <div class="search_clear"></div>

 <div class="html_checkbox" id="online" onClick="myhtml.checkbox(this.id); gSearch.go();" style="color: black">������ �� �����</div>
 <div class="html_checkbox" id="user_photo" onClick="myhtml.checkbox(this.id); gSearch.go();" style="margin-top:9px;color: black">� �����������</div>
 <div class="search_clear" style="margin-top:60px"></div>
  <div class="search_clear"></div>
 <b>���</b>
 <div class="" style="margin-top:10px"></div>
  <div class="padstylej"><select name="sex" id="sex" class="inpst search_sel" onChange="gSearch.go();"><option value="0">���</option>{sex}</select></div>

 
<div style="margin-top:10px"></div>
 <b>���� ��������</b>
 <div class="" style="margin-top:10px"></div>
 
 <div class="padstylej"><select name="day" class="inpst search_sel" id="day" onChange="gSearch.go();"><option value="0">����� ����</option>{day}</select>
 <div class="search_clear"></div>
  
 <select name="month" class="inpst search_sel" id="month" onChange="gSearch.go();"><option value="0">����� �����</option>{month}</select>
 <div class="search_clear"></div>
  
 <select name="year" class="inpst search_sel" id="year" onChange="gSearch.go();"><option value="0">����� ���</option>{year}</select></div>
 <div class="search_clear"></div>
  
</div>[/search-tab]
[search-tabc]
<div class="search_sotrt_tab">
 <b>��� ����������</b>
 <div class="search_clear"></div>
 <div class="padstylej"><input type="radio" checked="" id="club" name="type" onclick="Page.Go('/?go=search&type=6')"><label for="club" style="cursor:pointer">������</label></div>
<div class="mgclr"></div>
 <div class="padstylej"><input type="radio" id="public" name="type" onclick="Page.Go('/?go=search&type=4')"><label for="public" style="cursor:pointer">��������</label></div>
</div>
[/search-tabc]
[search-tabg]
<div class="search_sotrt_tab">
 <b>��� ����������</b>
 <div class="search_clear"></div>
 <div class="padstylej"><input type="radio" id="club" name="type" onclick="Page.Go('/?go=search&type=6')"><label for="club" style="cursor:pointer">������</label></div>
<div class="mgclr"></div>
 <div class="padstylej"><input type="radio" checked="" id="public" name="type" onclick="Page.Go('/?go=search&type=4')"><label for="public" style="cursor:pointer">��������</label></div>
</div>
[/search-tabg]

<div class="clear"></div>
[yes]<div class="margin_top_10"></div><div class="search_result_title">������� {count}</div>[/yes]
<div id="jquery_jplayer"></div>
<input type="hidden" id="teck_id" value="0" />
<input type="hidden" id="typePlay" value="standart" />
<input type="hidden" id="teck_prefix" value="" />