[all-albums]
<div class="tmenuf">
 <div><a href="/albums/{user-id}">���</a></div>
 <a href="/albums/comments/{user-id}">�����������</a>
 [not-owner]<a href="/u{user-id}">� �������� {name}</a>[/not-owner]
 [new-photos]<a href="/albums/newphotos" >����� ���������� �� ���� (<b>{num}</b>)</a>[/new-photos]
</div>
<div class="clr"></div>
[/all-albums]
[view]
<input type="hidden" id="all_p_num" value="{all_p_num}" />
<input type="hidden" id="aid" value="{aid}" />
<div class="tmenuf">
 <a href="/albums/{user-id}">���</a>
 <div><a href="/albums/view/{aid}">{album-name}</a></div>
 <a href="/albums/view/{aid}/comments/">�����������</a>
 [not-owner]<a href="/u{user-id}">� �������� {name}</a>[/not-owner]
</div>
<div class="clr"></div>
[/view]
[comments]
<div class="tmenuf">
 <a href="/albums/{user-id}">���</a>
 <div><a href="/albums/comments/{user-id}">�����������</a></div>
 [not-owner]<a href="/u{user-id}">� �������� {name}</a>[/not-owner]
</div>
<div class="clr"></div>
[/comments]
[albums-comments]
<div class="tmenuf">
 <a href="/albums/{user-id}">���</a>
 <a href="/albums/view/{aid}">{album-name}</a>
 <div><a href="/albums/view/{aid}/comments/">�����������</a></div>
 [not-owner]<a href="/u{user-id}">� �������� {name}</a>[/not-owner]
</div>
<div class="clr"></div>
[/albums-comments]