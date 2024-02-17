<div class="audio_onetrack1" style="width:71%">
 <div class="audio_playic cursor_pointer fl_l" onClick="music.newStartPlay('{jid}')" id="icPlay_{jid}"></div>
  <span id="music_{jid}" data="{url}">
   <a href="/?go=search&query={artist}&type=5&n=1" onClick="Page.Go(this.href); return false"><b><span id="artis{aid}">{artist}</span></b></a> &ndash; <span id="name{aid}">{name}</span>
  </span>
 [owner]<div class="audio_deletic cursor_pointer fl_r" onClick="audio.del('{aid}')" title="Удалить" id="dtrack_{aid}"></div>
 <div class="audio_edittic cursor_pointer fl_r" onClick="audio.edit('{aid}')" title="Редактировать аудиозапись" id="etrack_{aid}"></div>[/owner][not-owner]<div class="audio_addmylistic cursor_pointer fl_r" onClick="audio.addMyList('{aid}')" onMouseOver="myhtml.title('{aid}', 'Добавить в мои аудиозаписи', 'atrack_', 3)" id="atrack_{aid}"></div><div class="audio_addmylisticOk no_display fl_r" id="atrackAddOk{aid}"></div>[/not-owner]
</div>
<div style="margin-top:37px"></div>