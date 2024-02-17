<div class="msg_one [new]msg_new[/new]" id="bmsg_{mid}" style="width:450px;margin-left:0px;">
 <div class="msg_pad">
  <div class="msg_ava"><a href="/id{user-id}" onClick="Page.Go(this.href); return false"><img src="{ava}" alt="" /></a></div>
  <div class="msg_left_col">
   <a href="/id{user-id}" onClick="Page.Go(this.href); return false"><span>{name}</span></a>
   <div style="color:black;">{text}</div>
   <div><small>{date}</small> | <a href="/messages/show/{mid}" onClick="Profile.miniatureClose();Page.Go(this.href); return false">К диалогу</a></div>
  </div>
  <a href="/messages/show/{mid}" onClick="Page.Go(this.href); return false"><div class="msg_right_col" style="margin-left:15px;"><div>{attach}</div></div></a>

  <div class="clear"></div>
 </div>
</div>