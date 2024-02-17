<link rel="stylesheet" href="{theme}/apps/css/apps.css?51" type="text/css" /></link>
<link type="text/css" rel="stylesheet" href="{theme}/style/apps_edit.css"></link>
<script type="text/javascript" src="{theme}/js/apps_edit.js"></script>
<div class="search_form_tab" style="margin-top:-9px">
  <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond" style="height:22px">
    <div class="buttonsprofileSec">
      <a href="/editapp/info_{id}">
        <div><b>Информация</b>
        </div>
      </a>
    </div>
    <a href="/editapp/options_{id}">
      <div><b>Настройки</b>
      </div>
    </a>
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
    <div id="app_edit_wrap">
      <div id="app_edit_cont">
        <div id="apps_options_saved" class="apps_edit_success"></div>
        <table class="apps_edit_table apps_edit_info">
          <tbody>
            <tr>
              <td class="apps_edit_info_r" valign="top">
                <table width="100%">
                  <tbody>
                    <tr>
                      <td class="apps_edit_label ta_r">Название:</td>
                      <td>
                        <input class="text" type="text" id="app_name" value="{title}">
                      </td>
                    </tr>
                    <tr>
                      <td class="apps_edit_label ta_r">Описание:</td>
                      <td>
                        <textarea class="text" id="app_desc">{desk}</textarea>
                      </td>
                    </tr>
                    <!--tr>
  <td class="label ta_r">Тип:</td>
  <td class="clear_fix" id="app_category">
    <div id="app_category_0" class="apps_edit_cat radiobtn fl_l on" onclick="cur.checkAppCat(0);"><div></div>Игра</div>
    <div id="app_category_1" class="apps_edit_cat radiobtn fl_l" onclick="cur.checkAppCat(1);"><div></div>Приложение</div>
  </td>
</tr>

    <tr style="">
      <td class="label ta_r">Категория:</td>
      <td><div id="container10" class="selector_container dropdown_container" style="width: 258px;"><table cellspacing="0" cellpadding="0" class="selector_table">    <tbody><tr>      <td class="selector">        <span class="selected_items"></span>        <input type="text" class="selector_input selected" readonly="true" style="color: rgb(0, 0, 0); width: 231px;">        <input type="hidden" name="selectedItems" id="selectedItems" value="16" class="resultField"><input type="hidden" name="selectedItems_custom" id="selectedItems_custom" value="" class="customField">      </td><td id="dropdown10" class="selector_dropdown" style="width: 16px;">&nbsp;</td>    </tr>  </tbody></table>  <div class="results_container">    <div class="result_list dividing_line" style="display: none; opacity: 1; width: 258px;"><ul></ul></div>    <div class="result_list_shadow" style="width: 258px;">      <div class="shadow1"></div>      <div class="shadow2"></div>    </div>  </div></div></td>
    </tr>
    <tr><td class="label ta_r">Иконка 16x16:</td><td id="app_icon_cont" class="apps_edit_i" style="background-image: url('/images/icons/app_icon.gif');"><a onclick="AppsEdit.uploadIcon();">Выбрать файл</a></td></tr-->
                    <!-- <tr style=""><td class="label ta_r">Страница помощи:</td><td class="apps_edit_t"><a id="privacy_edit_help" onclick="Privacy.show(this, event, 'help')">Выключена</a></td></tr>

    <tr><td class="label ta_r"></td><td class="apps_edit_header">Контактная информация</td></tr>

    <tr style="display: none;"><td class="label apps_edit_wide ta_r">Пользовательское соглашение:</td><td class="apps_edit">
      <input type="text" class="text" placeholder="http://" onfocus="AppsEdit.urlFocus(this, 'terms_hint');" onblur="AppsEdit.urlBlur(this);" value="" id="app_agreement">
    </td></tr>

    <tr style="display: none;"><td class="label apps_edit_wide ta_r">Политика конфиденциальности:</td><td class="apps_edit">
      <input type="text" class="text" placeholder="http://" onfocus="AppsEdit.urlFocus(this, 'policy_hint');" onblur="AppsEdit.urlBlur(this);" value="" id="app_policy">
    </td></tr>

    <tr><td class="label ta_r">Группа приложения:</td><td><div id="container11" class="selector_container dropdown_container" style="width: 258px;"><table cellspacing="0" cellpadding="0" class="selector_table">    <tbody><tr>      <td class="selector">        <span class="selected_items"></span>        <input type="text" class="selector_input selected" readonly="true" style="color: rgb(0, 0, 0); width: 231px;">        <input type="hidden" name="selectedItems" id="selectedItems" value="0" class="resultField"><input type="hidden" name="selectedItems_custom" id="selectedItems_custom" value="" class="customField">      </td><td id="dropdown11" class="selector_dropdown" style="width: 16px;">&nbsp;</td>    </tr>  </tbody></table>  <div class="results_container">    <div class="result_list dividing_line" style="display: none; opacity: 1; width: 258px;"><ul></ul></div>    <div class="result_list_shadow" style="width: 258px;">      <div class="shadow1"></div>      <div class="shadow2"></div>    </div>  </div></div></td></tr>-->
                    <tr>
                      <td class="label ta_r"></td>
                      <td class="apps_edit_info_save">
                        <input type="hidden" id="app_id" value="{id}">
                        <input type="hidden" id="app_hash" value="{hash}">
                        <div class="button_div fl_l">
                          <button id="app_save_btn" onclick="AppsEdit.SaveOptions('save_info',{id});">Сохранить изменения</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td class="apps_edit_info_narrow" valign="top">
                <div class="apps_edit_img_block">
                  <img src="{img}" align="left" id="apps_edit_img_small" class="apps_edit_img_small">
                </div>
                <div id="apps_edit_upload_small">
                  <div class="button_div fl_l">
                    <button onclick="AppsEdit.LoadPhoto({id}); $('.profileMenu').hide(); return false;">Выбрать файл</button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
