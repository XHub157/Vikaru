<link type="text/css" rel="stylesheet" href="{theme}/style/apps_edit.css"></link>
<script type="text/javascript" src="{theme}/js/apps_edit.js"></script>
<div id="page_body" style="width: 641px;margin-top:-5px;margin-left:-12px;height:216px">
      <div id="header_wrap2">
        <div id="header_wrap1">
          <div id="header" style="display: none">
            <h1 id="title"></h1>
          </div>
        </div>
      </div>
      <div id="wrap_between"></div>
      <div id="wrap3"><div id="wrap2">
  <div id="wrap1">
    <div id="content"><div class="app_edit_summary">�������� ����������
	<span class="divider">|</span>
	<a href="/dev">������� � ������������</a>
</div>
<div id="apps_edit_create_cont" class="app_edit_main">

<div id="app_edit_error_wrap"><div id="app_edit_error"></div></div>
<div class="app_edit_cont apps_edit_create">

<div class="apps_edit_header">�������� ����������</div>

<table class="apps_edit_table">
<tr><td class="label ta_r">��������:</td><td><input class="text" type="text" id="app_title"/></td></tr>
</table>

<table id="app_type" class="apps_edit_table apps_create_type_select">
  <tr><td class="label ta_r">���:</td><td class="apps_edit_rs">
  <div id="app_type_0" class="radiobtn" onclick="cur.checkAppType(0);"><div></div>Standalone-����������</div>
  </td></tr>
  <tr><td class="label ta_r"></td><td class="apps_edit_rs">
  <div id="app_type_1" class="radiobtn" onclick="cur.checkAppType(1);"><div></div>���-����</div>
  </td></tr>
  <tr><td class="label ta_r"></td><td class="apps_edit_rs">
  <div id="app_type_2" class="radiobtn" onclick="cur.checkAppType(2);"><div></div>IFrame/Flash ����������</div>
  </td></tr>
</table>

<div id="apps_edit_connect_options" style="display: none;">
  <table class="apps_edit_table">
  <tr><td class="label ta_r">����� �����:</td><td class="apps_edit_desc">
  <input id="app_site_url" type="text" class="text" value="" placeholder="http://yoursite.com" />
  </td></tr>
  <tr><td class="label ta_r">������� �����:</td><td class="apps_edit_desc">
  <input id="app_base_domain" type="text" class="text" value="" placeholder="yoursite.com" />
  </td></tr>
  </table>
</div>

<div id="apps_edit_iframe_options" style="display: none;">
  <table id="app_type" class="apps_edit_table">
  <tr><td class="label ta_r">��������:</td><td><textarea class="text" id="app_desc"></textarea></td></tr>

  <tr>
  <td class="label ta_r">���:</td>
  <td class="clear_fix" id="app_category">
    <div id="app_category_0" class="apps_edit_cat radiobtn fl_l" onclick="cur.checkAppCat(0);"><div></div>����</div>
    <div id="app_category_1" class="apps_edit_cat radiobtn fl_l" onclick="cur.checkAppCat(1);"><div></div>����������</div>
  </td>
</tr>

  <tr><td class="label ta_r">���������:</td><td><div class="box_dark apps_edit_dd"><input class="text" type="hidden" id="app_subcategory"/></div></td></tr>

  <tr><td class="label ta_r"></td><td class="apps_edit_agree">�������� ����������, �� ������������ � <a onclick="AppsEdit.showRulesBox();">��������� ���������� ����������</a></td></tr>

  </table>
</div>

<table class="apps_edit_table apps_edit_create_btn">
<tr><td class="label ta_r"></td><td>
  <div class="button_blue"><button id="apps_create_app_btn" onclick="AppsEdit.CreateApp(this);">���������� ����������</button></div>
</td></tr>
</table>

</div>
</div></div>
  </div>
</div></div>
    </div>