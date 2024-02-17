<div class="group_l_row" id="group_{ids}">
  <a class="fl_l group_l_photo" href="{lnk}">
    <img src="{ava}" class="group_l_photo_img" id="group_l_photo{ids}">
  </a>
  <div class="fl_l group_l_info">
    <a href="/away.php?url={lnk}" id="group_l_title{ids}">{name}</a>
    <div class="group_l_position" id="group_l_position{ids}">{descr}</div>
  </div>
  <div class="fl_r" id="group_l_actions{ids}">
    <a class="group_l_action" onClick="epage.editLink('{ids}','{types}'); return false" id="group_l_action_edt{ids}">Редактировать</a>
    <a class="group_l_action" onClick="epage.delLink('{ids}','{ids}'); return false" id="group_l_action_del{ids}">Удалить</a>
  </div>
  <a id="group_l_restore{ids}" class="group_l_action fl_r" onClick="epage.rebornLink('{screen}','{types}','{ids}'); return false" style="display:none">Восстановить</a>
  <br class="clear">
</div>