[all-friends]
<div class="sft2" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:16px">
 <div class="buttonsprofileSec2"><a href="/friends/{user-id}" onClick="Page.Go(this.href); return false;"><div>��� ������</div></a></div>
 <a href="/friends/online/{user-id}" onClick="Page.Go(this.href); return false;">������ �� �����</a>
 [owner]<a href="/friends/requests" onClick="Page.Go(this.href); return false;">������ � ������ {demands}</a>[/owner]
 [not-owner][common-friends]<a href="/friends/common/{user-id}" onClick="Page.Go(this.href); return false;"><div>����� ������</div></a>[/common-friends]
 <a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="clear"></div>
<div class="search_form_tab" style="margin-top:10px;margin-bottom:10px;border-top: 1px solid #E4E7EB;width: 621px;padding:5px 10px">
<div style="padding:5px 0px;"><input type="text" style="width:450px;" id="friendsearch" placeholder="������� ������� ��� �����" onkeydown="friends.search(1,1);" class="friends_se_search friends_s_search" value="">
<div class="button_blue fl_r" style="margin-top:10px;"><button onclick="Page.Go('/balance?act=invite'); return false">���������� ������</button></div></div>
<div class="clear"></div>
</div>
<div style="margin-top:-5px;"></div>
<div class="summary_wrap" style="">
<div class="summary">� [owner]���[/owner][not-owner]{name}[/not-owner] {friends-num}</div>
</div>
<div style="margin-top:10px;"></div>
<div id="searchbody" style="display:none;"></div>
[/all-friends]

[request-friends]
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/friends/{user-id}" onClick="Page.Go(this.href); return false;">��� ������</a>
 <a href="/friends/online/{user-id}" onClick="Page.Go(this.href); return false;">������ �� �����</a>
 <div class="buttonsprofileSec2"><a href="/friends/requests" onClick="Page.Go(this.href); return false;"><div>������ � ������ {demands}</div></a></div>
</div></div>
<div class="search_form_tab" style="margin-top:12px;border-top: 1px solid #E4E7EB;">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
 <div class="buttonsprofileSec fl_l"><a href="/friends/requests" onclick="Page.Go(this.href); return false;"><div><b>��� ����������</b></div></a></div>
 <div class="fl_l"><a href="/friends/myrequests" onclick="Page.Go(this.href); return false;"><div><b>��������� ������</b></div></a></div>
</div>
</div>
<div class="clear"></div><div style="margin-top:10px;"></div>
<br>
[/request-friends]

[myrequest-friends]
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/friends/{user-id}" onClick="Page.Go(this.href); return false;">��� ������</a>
 <a href="/friends/online/{user-id}" onClick="Page.Go(this.href); return false;">������ �� �����</a>
 <div class="buttonsprofileSec2"><a href="/friends/requests" onClick="Page.Go(this.href); return false;"><div>������ � ������</div></a></div>
</div></div>
<div class="search_form_tab" style="margin-top:12px;border-top: 1px solid #E4E7EB;">
<div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
 <div class="fl_l"><a href="/friends/requests" onclick="Page.Go(this.href); return false;"><div><b>��� ����������</b></div></a></div>
 <div class="buttonsprofileSec fl_l"><a href="/friends/myrequests" onclick="Page.Go(this.href); return false;"><div><b>��������� ������</b></div></a></div>
</div>
</div>
<div class="clear"></div><div style="margin-top:10px;"></div>
<br>
[/myrequest-friends]

[online-friends]
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/friends/{user-id}" onClick="Page.Go(this.href); return false;">��� ������</a>
 <div class="buttonsprofileSec2"><a href="/friends/online/{user-id}" onClick="Page.Go(this.href); return false;"><div>������ �� �����</div></a></div>
 [owner]<a href="/friends/requests" onClick="Page.Go(this.href); return false;">������ � ������ {demands}</a>[/owner]
 [not-owner][common-friends]<a href="/friends/common/{user-id}" onClick="Page.Go(this.href); return false;"><div>����� ������</div></a>[/common-friends]
 <a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="clear"></div>
<div class="search_form_tab" style="margin-top:10px;margin-bottom:10px;border-top: 1px solid #E4E7EB;width: 621px;padding:5px 10px">
<div style="padding:5px 0px;"><input type="text" style="width:450px;" id="friendsearch" placeholder="������� ������� ��� �����" onkeydown="friends.search(1,1);" class="friends_se_search friends_s_search" value="">
<div class="button_blue fl_r" style="margin-top:10px;"><button onclick="Page.Go('/balance?act=invite'); return false">���������� ������</button></div></div>
<div class="clear"></div>
</div>
<div style="margin-top:-5px;"></div>
<div class="summary_wrap" style="">
<div class="summary">� [owner]���[/owner][not-owner]{name}[/not-owner] {friends-num}</div>
</div>
<div class="clear"></div><div style="margin-top:10px;"></div>
<br>
[/online-friends]