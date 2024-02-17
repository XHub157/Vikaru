<input type="hidden" id="pid" value="{user-id}"/>
[all-albums]
[admin-drag][owner]<script type="text/javascript">
$(document).ready(function(){
	AlbumsGroups.Drag();
	var page_cnt = $('#page_cnt_photos').val();
	var count_photos = parseInt($('#num_photos').val());
	$(document).scroll(function(){
		if($(document).height() - $(window).height() <= $(window).scrollTop()+($(document).height()/2-250) && (page_cnt*30)<count_photos){
			PhotoGroups.loadingPhotos();
		}
		var page_cnt_albums = $('#page_cnt_albums').val();
		var count_albums = parseInt($('#num_albums').text());
		if(page_cnt_albums != 1) {
			if($('#dragndrop').height() - $(window).height() <= $(window).scrollTop()+($('#dragndrop').height()/8-250) && (page_cnt_albums*6)<count_albums){
				PhotoGroups.loadingAlbums();
			}
		}
	});
});
</script>[/owner][/admin-drag]
<input type="hidden" id="page_cnt_albums" value="1"/>
<input type="hidden" id="page_cnt_photos" value="1"/>
<input type="hidden" id="loading_albums" value="1"/>
<input type="hidden" id="loading_photos" value="1"/>
<input type="hidden" id="num_photos" value="{count_photos}"/>
<div class="buttonsprofile albumsbuttonsprofile" style="height:10px;">
 <div class="activetab"><a href="/albums-{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;"><div>Альбомы сообщества</div></a></div>
 <a href="/{alias}" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Вернутся к сообществу</a>
 [new-photos]<a href="/albums-{user-id}/newphotos" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Новые фотографии со мной (<b>{num}</b>)</a>[/new-photos]
</div>
<div class="clear" style="border-bottom:1px solid #58a358;margin: 20px -12px 0px -12px;"></div>
<div class="summary_wrap" style="margin:0 20px;padding: 13px 0px 5px;"><b id="num_albums">{albums_num}</b> <b>{palbums_num}</b>[owner]<a href="" onClick="AlbumsGroups.CreatAlbum('{user-id}'); return false;" style="margin: 2px;float:right">Создать альбом</a>[/owner] <span class="fl_r" style="padding-top:2px;">&nbsp;|&nbsp;</span> <a href="/albums-{user-id}/comments" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Комментарии к альбомам</a></div>
[/all-albums]
[view]
<script type="text/javascript">
$(document).ready(function(){
	PhotoGroups.Drag();
});
</script>
<input type="hidden" id="all_p_num" value="{all_p_num}" />
<input type="hidden" id="aid" value="{aid}" />
<div class="buttonsprofile albumsbuttonsprofile" style="height:10px;">
 <a href="/albums-{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Альбомы сообщества</a>
 <div class="activetab"><a href="/albums-{user-id}_{aid}" onClick="Page.Go(this.href); return false;" style="margin: 2px;max-width: 320px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;overflow: hidden;"><div>{album-name}</div></a></div>
 <a href="/{alias}" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Вернутся к сообществу</a>
</div>
<div class="clear" style="border-bottom:1px solid #58a358;margin: 20px -12px 0px -12px;"></div>
[owner]<div id="photos_upload_area" onClick="Page.Go('/albums-{user-id}_{aid}/add'); return false;" class="cursor_pointer" style="margin: 0 -12px;"><div class="photos_upload_area"><span id="photo_upload_area_label" class="photos_upload_area_img">Добавить фотографии в альбом</span></div></div>[/owner]
[photos_yes]<div class="summary_wrap" style="margin:0 10px;padding: 13px 0px 5px;"><b>В альбоме {photos_num}</b><a href="/albums-{user-id}_{aid}/comments/" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Комментарии к альбому</a></div>[/photos_yes]
[/view]
[comments]
<script type="text/javascript" src="{theme}/js/AlbumsGroups.view.js"></script>
<div class="buttonsprofile albumsbuttonsprofile">
 <a href="/albums-{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Альбомы сообщества</a>
 [owner]<a href="" onClick="AlbumsGroups.CreatAlbum('{user-id}'); return false;" style="margin: 2px;">Создать альбом</a>[/owner]
 <div class="activetab"><a href="/albums-{user-id}/comments/{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;"><div>Комментарии к альбомам</div></a></div>
 <a href="/{alias}" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Вернутся к сообществу</a>
</div>
<div class="clear" style="border-bottom:1px solid #58a358;margin: 0px -12px 0px -12px;"></div>
<div class="summary_wrap" style="margin:0 10px;padding: 13px 0px 5px;margin-bottom: -1px;"><b>{comments_num}</b></div>
<div class="clear"></div>
[/comments]
[albums-comments]
<script type="text/javascript" src="{theme}/js/AlbumsGroups.view.js"></script> 
<div class="buttonsprofile albumsbuttonsprofile">
 <a href="/albums-{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Альбомы сообщества</a>
 <a href="/albums-{user-id}_{aid}" onClick="Page.Go(this.href); return false;" style="margin: 2px;max-width: 150px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;overflow: hidden;">{album-name}</a>
 <div class="activetab"><a href="/albums-{user-id}_{aid}/comments/" onClick="Page.Go(this.href); return false;" style="margin: 2px;"><div>Комментарии к альбому</div></a></div>
 <a href="/{alias}" onClick="Page.Go(this.href); return false;" style="margin: 2px;float:right">Вернутся к сообществу</a>
</div>
<div class="clear" style="border-bottom:1px solid #58a358;margin: 0px -12px 0px -12px;"></div>
<div class="summary_wrap" style="margin:0 10px;padding: 13px 0px 5px;margin-bottom: -1px;"><b>{comments_num}</b></div>
<div class="clear"></div>
[/albums-comments]
[all-photos]
<script type="text/javascript" src="{theme}/js/AlbumsGroups.view.js"></script>
<div class="buttonsprofile albumsbuttonsprofile" style="height:10px;">
 <a href="/albums-{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Альбомы сообщества</a>
 [owner]<a href="" onClick="AlbumsGroups.CreatAlbum('{user-id}'); return false;" style="margin: 2px;">Создать альбом</a>[/owner]
 <a href="albums-{user-id}/comments/" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Комментарии к альбомам</a>
 <div class="activetab"><a href="/photos{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;"><div>Обзор фотографий</div></a></div>
 [not-owner]<a href="/id{user-id}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">К странице {name}</a>[/not-owner]
</div>
<div class="clear"></div><div style="margin-top:8px;"></div>
[/all-photos]