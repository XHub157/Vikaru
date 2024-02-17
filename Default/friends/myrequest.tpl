<div class="friends_onefriend" id="friend_{user-id}" style="width:100%">
 <a href="/u{user-id}" onClick="Page.Go(this.href); return false"><div class="friends_ava"><img src="{ava}" alt="" /></div>
 </a>
 <a href="/u{user-id}" onClick="Page.Go(this.href); return false"><b>{name}</b></a> {age}
 <div class="friends_clr"></div>
 {country}{city}<div class="friends_clr"></div><div class="friends_clr"></div>
 <div class="friends_clr" style="margin-top:10px"></div>
 <div id="action_{user-id}">
 <div class="button_blue fl_l"><button onMouseDown="friends.myreject({user-id}); return false">Отменить заявку</button></div>
 </div>
</div>