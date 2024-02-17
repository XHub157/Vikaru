<div class="wallrecord comm_wr" id="comment_{id}">
<div class="ava_mini" style="float:width:60px"><img src="{ava}" alt="" title="" /></div>
[owner]<div class="fl_r reply_actions_wrap">
<div class="reply_actions">
<div onClick="commentsGroups.delet({id}, '{hash}'); return false" id="del_but_{id}"
onmouseover="myhtml.title('{id}', 'Удалить', 'del_but_')" class="reply_delete_button fl_r"></div>
</div></div>[/owner]
<div style="float:left;width:329px">
<div class="wallauthor"><a href="/id{uid}" onClick="Page.Go(this.href); return false">{author}</a></div>
<div class="walltext" style="margin-top:2px">{comment}</div>
<div class="infowalltext">{date}
<input type="hidden" id="update_like{id}" value="0" />
<div class="public_likes_user_block no_display" style="margin-left: 243px;margin-top: -85px;z-index: 10; " id="public_likes_user_block{id}" onMouseOver="PhotoGroups.wall_like_users_five('{id}')" onMouseOut="PhotoGroups.wall_like_users_five_hide('{id}')">
<div onClick="PhotoGroups.wall_all_liked_users('{id}', '', '{likes}')">Понравилось {likes-text}</div>
<div class="public_wall_likes_hidden">
<div class="public_wall_likes_hidden2">
<a href="/id{uid}" id="like_user{uid}_{id}" class="no_display" onClick="Page.Go(this.href); return false"><img src="{viewer-ava}" width="32" /></a>
<div id="likes_users{id}"></div>
</div>
</div>
<div class="public_like_strelka" style="position: static;"></div>
</div>
<div class="public_comment_like cursor_pointer" style="float:right" onClick="{like-js-function}" onMouseOver="PhotoGroups.wall_like_users_five('{id}')" onMouseOut="PhotoGroups.wall_like_users_five_hide('{id}')" id="wall_like_link{id}">
	<div class="fl_l" id="wall_like_active">Мне нравится</div>
	<div class="public_wall_like_no {yes-like}" id="wall_active_ic{id}"></div>
	<b id="wall_like_cnt{id}" class="{yes-like-color}">{likes}</b>
</div>
</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>