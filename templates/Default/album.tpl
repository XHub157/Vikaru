<div class="photo_row" id="album_{aid}" [owner]style="cursor:pointer"[/owner]><div class="cont">
    <a class="img_link [yes-photos]no_photo[/yes-photos] [yesdescr]photo_album_title_show[/yesdescr] [owner]edit_owner[/owner]">
		<img src="{cover}" onClick="Page.Go('/album{aid}'); return false" id="cover_{aid}">
		[owner]<div class="photo_album_info" onClick="Albums.EditBox({aid}); return false" title="Редактировать альбом" style="cursor:pointer">
			<div class="photo_album_info_back"></div>
			<div class="photo_album_info_cont"></div>
		</div>
		<div class="photo_album_info" onClick="Albums.EditCover({aid}); return false" title="Изменить обложку" style="right:31px;cursor:pointer">
			<div class="photo_album_info_back"></div>
			<div class="photo_album_info_cont" style="background:url(templates/Default/images/photo_icons.png) 2px -23px no-repeat;height:12px;cursor:pointer"></div>
		</div>
		<div class="photo_album_info" onClick="Albums.Delete({aid}, '{hash}'); return false" title="Удалить альбом" style="right:56px;cursor:pointer">
			<div class="photo_album_info_back"></div>
			<div class="photo_album_info_cont" style="background:url(templates/Default/images/photo_icons.png) 2px -77px no-repeat;height:12px;"></div>
		</div>[/owner]
		<div class="photo_album_title" onClick="Page.Go('/album{aid}'); return false">
			<div class="clear_fix" style="margin: 0px">
				<div class="ge_photos_album fl_l" title="{name}" id="albums_name_{aid}">{name}</div>
				<div class="camera fl_r">{photo-num}</div>
			</div>
			<div class="description" id="descr_{aid}">{descr}</div>
		</div>
	</a>
	</div><a class="bg"></a>
</div>