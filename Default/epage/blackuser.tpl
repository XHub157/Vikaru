<div class="group_bl_row" id="group_b{uid}">
  <a href="/{alias}" onClick="Page.Go(this.href)" class="fl_l group_bl_photo">
    <img src="{ava}" class="group_bl_photo_img">
  </a>
  <div class="fl_l">
    <a href="/{alias}" onclick="Page.Go(this.href)" class="group_bl_name">
      {name}
    </a> <span id="group_bl_rem{uid}" class="group_bl_rem"></span>
    <div class="group_bl_info" id="group_bl_info{uid}">добавил <a href="/{alias}" class="mem_link">{nadmin}</a> {date}</div>
  </div>
  <a class="fl_r group_bl_action" onClick="epage.delblacklist('{uid}','{pid}'); return false" id="group_bl_action{uid}"><span>Удалить из списка</span></a>
  <br class="clear">
</div>