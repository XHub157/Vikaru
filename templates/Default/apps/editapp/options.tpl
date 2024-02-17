<link type="text/css" rel="stylesheet" href="{theme}/style/apps_edit.css"></link>
<script type="text/javascript" src="{theme}/js/apps_edit.js"></script>
<div class="search_form_tab" style="margin-top:-9px">
  <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
    <a href="/editapp/info_{id}">
      <div><b>Информация</b>
      </div>
    </a>
    <div class="buttonsprofileSec">
      <a href="/editapp/options_{id}">
        <div><b>Настройки</b>
        </div>
      </a>
    </div>
    <a href="/editapp/payments_{id}">
      <div><b>Платежи</b>
      </div>
    </a>
    <a href="/editapp/admins_{id}">
      <div><b>Администраторы</b>
      </div>
    </a>
    <div class="fl_r">
      <a href="/app{id}" onclick="return nav.go(this, event, {noback: true})">К приложению</a>
    </div>
  </div>
</div>
<div id="content">
  <div id="app_edit">
    <div id="app_edit_error_wrap">
      <div id="app_edit_error"></div>
    </div>
    <div id="app_edit_wrap">
      <div id="app_edit_cont">
        <div id="apps_options_saved" class="apps_edit_success"></div>
        <div class="apps_edit_top"></div>
        <table class="apps_edit_table">
          <tbody>
            <tr>
              <td class="label ta_r">ID приложения:</td>
              <td class="apps_edit_t"> <b>{id}</b>

                <input type="hidden" id="app_id" value="{id}">
                <input type="hidden" id="app_hash" value="{hash}">
              </td>
            </tr>
            <tr>
              <td class="label ta_r">Защищенный ключ:</td>
              <td class="apps_edit">
                <input type="text" class="text" id="app_secret2" value="{secrets}">
              </td>
            </tr>
          </tbody>
        </table>
        <div style="">
          <table class="apps_edit_table">
            <tbody>
              <tr>
                <td class="label ta_r">Состояние:</td>
                <td>
                  <select name="status" id="status"  class="inpst">{option}</select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="apps_edit_container_opts" style="">
          <div class="apps_edit_header">Настройки контейнера</div>
          <table class="apps_edit_table">
            <tbody>
              <tr>
                <td class="label ta_r">Тип приложения:</td>
                <td>
                  <select id="type" name="type" class="inpst" onchange="AppsEdit.type()">{type} </select>
                </td>
              </tr>
            </tbody>
          </table>
          <table id="apps_edit_iframe_options" class="apps_edit_table"  style="display: {siframe};">
            <tbody>
              <tr>
                <td class="label ta_r">Адрес IFrame:</td>
                <td>
                  <input class="text" type="text" id="app_iframe_url" value="{url}">
                </td>
              </tr>
              <tr>
                <td class="label ta_r">Размер IFrame:</td>
                <td>
                  <input class="text text_small" type="text" id="app_iframe_width" value="{width}"> <span class="apps_edit_iframe_res">x</span>

                  <input class="text text_small" type="text" id="app_iframe_height" value="{height}">
                </td>
              </tr>
            </tbody>
          </table>
           <div id="apps_edit_flash_options" style="display: {sflash};">
    <div class="apps_edit_header">Загрузка SWF-приложения</div>
    <table class="apps_edit_table">
    <tbody><tr><td class="label ta_r"></td><td><a onclick="AppsEdit.updateSWF({id});" class="cursor_pointer">Загрузить приложение</a></td></tr>
    </tbody></table>
    </div>
        </div>
        <table id="apps_edit_save" class="apps_edit_table">
          <tbody>
            <tr>
              <td class="label ta_r"></td>
              <td>
                <div class="button_div fl_l">
                  <button id="app_save_btn" onclick="AppsEdit.SaveOptions('save_options',{id});">Сохранить изменения</button>
                </div>
                <div id="app_save_info" style="padding: 5px; margin-left: 150px;"></div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="apps_edit_other">

        <div class="apps_edit_header">Удаление приложения</div>
        <table class="apps_edit_table">
          <tbody>
            <tr>
              <td class="label ta_r"></td>
              <td>Если Вы удалите это приложение, Вы уже не сможете его восстановить.</td>
            </tr>
            <tr>
              <td class="label ta_r"></td>
              <td>
                <div class="button_div fl_l">
                  <button id="app_save_btn" onclick="AppsEdit.DeleteApp({id});">Удалить приложение</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>