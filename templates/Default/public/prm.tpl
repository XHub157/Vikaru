<script type="text/javascript" src="{theme}/js/login.js"></script>
<div class="ava fl_r" style="margin-right:0px" onMouseOver="groups.wall_like_users_five_hide()">
<div class="ava"> 
<img src="{ava}" alt="" title="" id="ava_{user-id}"/>
</div>
<div class="publick_subscblock">
   <div id="yes" class="{yes}">
    <div class="button_blue fl_l" style="margin-bottom:15px;line-height:15px"><button onClick="login.login(); return false" style="width:174px">Войти и подписаться</button></div>
   </div>
   <div style="margin-top:7px"></div>
   </div>
   <div class="clear"></div>
<div class="clear"></div>
<div style="margin-top:7px">
  <div class="{no-users}" id="users_block">
   <div class="albtitle cursor_pointer" onClick="groups.subscribes_groups('{id}')">Подписчики</div>
   <div class="public_bg">
    <div class="color777 public_margbut">{num}</div>
	<div class="public_usersblockhidden">{users}</div>
    <div class="clear"></div>
   </div>
  </div>
 </div>
</div>
<div class="public_title" id="e_public_title">{title}</div>
<div class="status">
{status-text}
</div>
 <div class="{descr-css}" id="descr_display"><div class="flpodtext">Описание:</div> <div class="flpodinfo" id="e_descr">{descr}</div></div>
  [web]<div class="flpodtext">Веб-сайт:</div> <div class="flpodinfo"><a href="{web}" target="_blank">{web}</a></div>[/web]
  <div class="flpodtext">Дата создания:</div> <div class="flpodinfo">{date}</div>
  [gtype]<div class="flpodtext">Тематика:</div> <div class="flpodinfo">{gtype}</div>[/gtype]
 <div class="albtitle" style="border-bottom:0px">{rec-num}</div>
  <div class="info_center" style="margin-top:60px">
Чтобы просмотреть записи Вам нужно <a href="/" onClick="login.login(); return false">авторизоваться</a> или <a href="/" onClick="login.reg(); return false">зарегистрироваться</a>.
<br /><br />
</div>
<div class="clear"></div>