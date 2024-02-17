[all]<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/wall{user-id}_sec=own" onClick="Page.Go(this.href); return false;"><div>Записи {name}</div></a>
 <a href="/wall{user-id}" onClick="Page.Go(this.href); return false;"><div>Все записи</div></a>
 <div class="buttonsprofileSec2"><a href="/notes/{user-id}" onClick="Page.Go(this.href); return false;"><div>Заметки {name}</div></a></div>
</div></div>
<div style="margin-top:-20px"><div style="float:right;"><a href="/notes/add" onClick="Page.Go(this.href); return false;">Добавить заметку</div></div></a>
<div class="clear"></div><div style="margin-top:10px;"></div>
<br>
[/all]
[add]<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/notes" onClick="Page.Go(this.href); return false;">Мои заметки</a>
 <div class="buttonsprofileSec2"><a href="/notes/add" onClick="Page.Go(this.href); return false;"><div>Добавить запись</div></a></div>
</div></div>
<div class="clear"></div><div class="hralbum" style="margin-top:10px;"></div>
[/add]
[edit]<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <a href="/notes" onClick="Page.Go(this.href); return false;">Мои заметки</a>
 <div class="buttonsprofileSec2"><a href="/notes/edit/{note-id}" onClick="Page.Go(this.href); return false;"><div>Редактирование записи</div></a></div>
 <a href="/notes/add" onClick="Page.Go(this.href); return false;">Добавить запись</a>
</div></div>
<div class="clear"></div><div class="hralbum" style="margin-top:10px;"></div>
[/edit]
[view]<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:16px">
 <a href="/wall{user-id}_sec=own" onClick="Page.Go(this.href); return false;"><div>Записи {name}</div></a>
 <a href="/wall{user-id}" onClick="Page.Go(this.href); return false;"><div>Все записи</div></a>
 <a href="/notes/{user-id}" onClick="Page.Go(this.href); return false;">Заметки {name}</a>
 <div class="buttonsprofileSec2"><a href="/notes/view/{note-id}" onClick="Page.Go(this.href); return false;"><div>Просмотр заметки</div></a></div>
</div></div>
<div class="clear"></div>
[/view]