<style>.speedbar{background:#fff;color:#5081b1;margin-top:-30px}</style>
[all-albums]
[admin-drag][owner]<script type="text/javascript">
$(document).ready(function(){
	Albums.Drag();
});
</script>[/owner][/admin-drag]
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <div class="buttonsprofileSec2"><a href="/albums{user-id}" onClick="Page.Go(this.href); return false;"><div>[not-owner]��� ������� {name}[/not-owner][owner]��� �������[/owner]</div></a></div>
 [owner]<a href="" onClick="Albums.CreatAlbum(); return false;">������� ������</a>[/owner]
 <a href="/albums/comments/{user-id}" onClick="Page.Go(this.href); return false;">����������� � ��������</a>
 [not-owner]<a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
 [new-photos]<a href="/albums/newphotos" onClick="Page.Go(this.href); return false;">����� ���������� �� ���� (<b>{num}</b>)</a>[/new-photos]
</div></div>
<div class="summary_wrap">
  <div class="summary">
    {num}
    </span>
  </div>
</div>
<div class="clear"></div>
<br>
[/all-albums]
[view]
<input type="hidden" id="all_p_num" value="{all_p_num}" />
<input type="hidden" id="aid" value="{aid}" />
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/albums{user-id}" onClick="Page.Go(this.href); return false;">[not-owner]��� ������� {name}[/not-owner][owner]��� �������[/owner]</a>
 <div class="buttonsprofileSec2"><a href="/albums/view/{aid}" onClick="Page.Go(this.href); return false;"><div>{album-name}</div></a></div>
 [owner]<a href="/albums/add/{aid}" onClick="Page.Go(this.href); return false;">�������� ����������</a>[/owner]
 [not-owner]<a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="summary_wrap" style="">
    <div class="summary">� ������� {photo-num} | <span><a href="/albums/view/{aid}/comments/" onClick="Page.Go(this.href); return false;">����������� � �������</a></span> [owner]| <span><a href="/albums/editphotos/{aid}" onClick="Page.Go(this.href); return false;">�������� ������� ����������</a></span>[/owner]</div>
  </div>
<div class="clear"></div><div style="margin-top:8px;"></div>
[/view]
[editphotos]
[admin-drag]<script type="text/javascript">
$(document).ready(function(){
	Photo.Drag();
});
</script>[/admin-drag]
<script type="text/javascript" src="{theme}/js/albums.view.js"></script>
<input type="hidden" id="all_p_num" value="{all_p_num}" />
<input type="hidden" id="aid" value="{aid}" />
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/albums{user-id}" onClick="Page.Go(this.href); return false;">��� �������</a>
 <a href="/album{aid}" onClick="Page.Go(this.href); return false;">{album-name}</a>
 <a href="/albums/view/{aid}/comments/" onClick="Page.Go(this.href); return false;">����������� � �������</a>
 <div class="buttonsprofileSec2"><a href="/albums/editphotos/{aid}" onClick="Page.Go(this.href); return false;"><div>�������� ������� ����������</div></a></div>
</div></div>
<br>
<div class="clear"></div><div style="margin-top:8px;"></div>
[/editphotos]
[comments]
<script type="text/javascript" src="{theme}/js/albums.view.js"></script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/albums{user-id}" onClick="Page.Go(this.href); return false;">[not-owner]��� ������� {name}[/not-owner][owner]��� �������[/owner]</a>
 [owner]<a href="" onClick="Albums.CreatAlbum(); return false;">������� ������</a>[/owner]
 <div class="buttonsprofileSec2"><a href="/albums/comments/{user-id}" onClick="Page.Go(this.href); return false;"><div>����������� � ��������</div></a></div>
 [not-owner]<a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="clear"></div>
[/comments]
[albums-comments]
<script type="text/javascript" src="{theme}/js/albums.view.js"></script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/albums{user-id}" onClick="Page.Go(this.href); return false;">[not-owner]��� ������� {name}[/not-owner][owner]��� �������[/owner]</a>
 <a href="/album/{aid}" onClick="Page.Go(this.href); return false;">{album-name}</a>
 <div class="buttonsprofileSec2"><a href="/albums/view/{aid}/comments/" onClick="Page.Go(this.href); return false;"><div>����������� � �������</div></a></div>
 [not-owner]<a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="clear"></div>
[/albums-comments]
[all-photos]
<script type="text/javascript" src="{theme}/js/albums.view.js"></script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/albums{user-id}" onClick="Page.Go(this.href); return false;">[not-owner]��� ������� {name}[/not-owner][owner]��� �������[/owner]</a>
 [owner]<a href="" onClick="Albums.CreatAlbum(); return false;">������� ������</a>[/owner]
 <a href="/albums/comments/{user-id}" onClick="Page.Go(this.href); return false;">����������� � ��������</a>
 <div class="buttonsprofileSec2"><a href="/photos{user-id}" onClick="Page.Go(this.href); return false;"><div>����� ����������</div></a></div>
 [not-owner]<a href="/u{user-id}" onClick="Page.Go(this.href); return false;">� �������� {name}</a>[/not-owner]
</div></div>
<div class="clear"></div><div style="margin-top:8px;"></div>
[/all-photos]