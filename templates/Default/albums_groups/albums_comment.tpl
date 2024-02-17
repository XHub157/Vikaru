<div id="comment_all_{id}" style="margin:0 10px;">
<div class="wallrecord comm_wr_all fl_l">
<div class="ava_mini" style="float:width:60px"><a href="/id{uid}" onClick="Page.Go(this.href); return false"><img src="{ava}" alt="" title="" /></a></div>
<div class="photo_comment_info">
<div class="wallauthor"><a href="/id{uid}" onClick="Page.Go(this.href); return false">{author}</a></div>
<div class="walltext" style="margin-top:2px">{comment}</div>
<div class="infowalltext">
	{date} [owner]&nbsp;|&nbsp;<a href="/" onClick="commentsGroups.delet_page_comm({id}, '{hash}'); return false" id="full_del_but_{id}">Удалить</a>[/owner]&nbsp;|&nbsp;
	<input type="hidden" id="update_like{id}" value="0" />
	<div class="public_likes_user_block no_display" style="margin-left: 161px; margin-top: -85px;" id="public_likes_user_block{id}" onMouseOver="PhotoGroups.wall_like_users_five('{id}')" onMouseOut="PhotoGroups.wall_like_users_five_hide('{id}')">
		<div onClick="PhotoGroups.wall_all_liked_users('{id}', '', '{likes}')">Понравилось {likes-text}</div>
		<div class="public_wall_likes_hidden">
		<div class="public_wall_likes_hidden2">
		<a href="/id{uid}" id="like_user{uid}_{id}" class="no_display" onClick="Page.Go(this.href); return false"><img src="{viewer-ava}" width="32" /></a>
		<div id="likes_users{id}"></div>
	</div>
	</div>
	<div class="public_like_strelka" style="position: static;"></div>
	</div>
	<div class="public_comment_like cursor_pointer" style="float:none" onClick="{like-js-function}" onMouseOver="PhotoGroups.wall_like_users_five('{id}')" onMouseOut="PhotoGroups.wall_like_users_five_hide('{id}')" id="wall_like_link{id}">
		<div class="fl_l" id="wall_like_active">Мне нравится</div>
		<div class="public_wall_like_no {yes-like}" id="wall_active_ic{id}"></div>
		<b id="wall_like_cnt{id}" class="{yes-like-color}">{likes}</b>
	</div>
</div>
</div>
</div>
<div class="comment_photo"><a href="/photo-{pids}_{pid}{aid}_sec={section}" onClick="PhotoGroups.Show(this.href); return false"><img src="{photo}" alt="" /></a></div>
<div class="clear"></div>
</div>