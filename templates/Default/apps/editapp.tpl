<div id="app_edit_cont">
<div id="apps_options_saved" class="apps_edit_success">
</div>
<table class="apps_edit_table apps_edit_info"><tbody><tr>

<td class="apps_edit_info_r" valign="top">
  <table class="apps_edit_table">
    <tbody><tr><td class="apps_edit_label ta_r">Название:</td>
    <td><input class="fave_inputsss" type="text" id="app_name" value="{title}"><span id="app_name_notice" display:="" none;=""></span></td></tr>
    <tr><td class="apps_edit_label ta_r">Описание:</td><td><textarea class="fave_inputsss" style="height: 70px;" id="app_desc">{desc}</textarea></td></tr>
	<div class="allbar_title" style="width: 460px;margin-top:-20px;">Основная информация</div>
  </tbody></table>
</td>
<td class="apps_edit_info_narrow" valign="top">
  <div class="apps_edit_img_block apps_edit_img_opts">
    <img id="app_photo" width="100" height="100" src="{ava}" align="left" style="padding-bottom:5px;">
	<center><a onclick="apps.LoadPhoto('{id}')" style="cursor:pointer">Загрузить фотографию</a></center>
  </div>
</td>
</tr></tbody></table>

<div class="margin_top_10"></div><div class="allbar_title" style="width: 460px;">Контактная информация</div>
<div class="texta">Группа приложения:</div><div id="group_sel_w"><select id="group_w" class="inpst" onChange="sp.opengroups()">
  {groups-sub}
 </select></div>

</div>