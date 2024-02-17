<div class="albums_cover_photo" [owner]onMouseOver="PhotoGroups.Panel({id}, 'show')" onMouseOut="PhotoGroups.Panel({id}, 'hide')"[/owner] id="a_photo_{id}">
	<a href="/photo-{uid}_{id}{aid}{section}" onClick="PhotoGroups.Show(this.href); return false">
		<div class="albums_new_cover" id="albums_new_cover_{id}"></div>
	</a>
	[owner]<div class="albums_photo_panel" id="albums_photo_panel_{id}">
		<a href="/" class="albums_ic ic_posi" title="Переместить фотографию" onClick="Albums.MovePhoto('{id}'); return false"></a>
		<a href="/" class="albums_ic ic_del" title="Удалить" onClick="PhotoGroups.MsgDelete({id}, null); return false"></a>
		<a href="/" class="albums_ic ic_edit" title="Редактировать" onClick="PhotoGroups.EditBox({id}, 1); return false"></a>
		<a href="/" class="albums_ic ic_cover" title="Сделать обложкой альбома" onClick="PhotoGroups.SetCover({id}, {id}); return false"></a>
	</div>[/owner]
	<a href="/photo-{uid}_{id}{aid}{section}" onClick="PhotoGroups.Show(this.href); return false"><img src="{photo}" alt="" /></a>
</div>