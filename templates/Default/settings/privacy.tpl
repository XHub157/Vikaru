<script type="text/javascript">
$(document).click(function(event){
	settings.event(event);
});
</script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
  <a href="/settings" onClick="Page.Go(this.href); return false;"><div>�����</div></a>
  <div class="buttonsprofileSec2"><a href="/settings&act=privacy" onClick="Page.Go(this.href); return false;"><div>�����������</div></a></div>
  <a href="/settings&act=blacklist" onClick="Page.Go(this.href); return false;"><div>������ ������</div></a>
  <a href="/settings&act=mobile" onClick="Page.Go(this.href); return false;"><div>��������� �������</div></a>
  <a href="/settings&act=balance" onClick="Page.Go(this.href); return false;"><div>������</div></a>
 </div>
</div>
<div class="settings_general">
<div class="err_yellow no_display" id="ok_update" style="font-weight:normal;">����� ��������� ����������� �������� � ����.</div>

<div class="margin_top_10"></div><div class="allbar_title">��� ��������</div>
<div class="texta color_000" style="width:300px">��� ����� �������� ���������� ���� <b>��������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('info')" id="privacy_lnk_info">{val_info_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_info" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_info" class="sett_selected" onClick="settings.privacyClose('info')">{val_info_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_info', '��� ������������', '1', 'privacy_lnk_info')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_info', '������ ������', '2', 'privacy_lnk_info')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_info', '������ �', '3', 'privacy_lnk_info')">������ �</div>
 </div>
 <input type="hidden" id="val_info" value="{val_info}" />
<div class="mgclr"></div>

<div class="texta color_000" style="width:300px">��� ����� ������������� ������ ���� <b>��������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('gift')" id="privacy_lnk_gift">{val_gift_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_gift" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_gift" class="sett_selected" onClick="settings.privacyClose('gift')">{val_gift_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_gift', '��� ������������', '1', 'privacy_lnk_gift')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_gift', '������ ������', '2', 'privacy_lnk_gift')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_gift', '������ �', '3', 'privacy_lnk_gift')">������ �</div>
 </div>
 <input type="hidden" id="val_gift" value="{val_gift}" />
<div class="mgclr"></div>

<div class="texta color_000" style="width:300px">��� ����� ������������� ������ ���� <b>������������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('audio')" id="privacy_lnk_audio">{val_audio_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_audio" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_audio" class="sett_selected" onClick="settings.privacyClose('audio')">{val_audio_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_audio', '��� ������������', '1', 'privacy_lnk_audio')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_audio', '������ ������', '2', 'privacy_lnk_audio')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_audio', '������ �', '3', 'privacy_lnk_audio')">������ �</div>
 </div>
 <input type="hidden" id="val_audio" value="{val_audio}" />
<div class="mgclr"></div>


<div class="texta color_000" style="width:300px">��� ����� ������������� ������ ���� <b>������������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('video')" id="privacy_lnk_video">{val_video_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_video" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_video" class="sett_selected" onClick="settings.privacyClose('video')">{val_video_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_video', '��� ������������', '1', 'privacy_lnk_video')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_video', '������ ������', '2', 'privacy_lnk_video')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_video', '������ �', '3', 'privacy_lnk_video')">������ �</div>
 </div>
 <input type="hidden" id="val_video" value="{val_video}" />
<div class="mgclr"></div>

<div class="texta color_000" style="width:300px">��� ����� ������������� ������ ���� <b>���������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('public')" id="privacy_lnk_public">{val_public_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_public" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_public" class="sett_selected" onClick="settings.privacyClose('public')">{val_public_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_public', '��� ������������', '1', 'privacy_lnk_public')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_public', '������ ������', '2', 'privacy_lnk_public')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_public', '������ �', '3', 'privacy_lnk_public')">������ �</div>
 </div>
 <input type="hidden" id="val_public" value="{val_public}" />
<div class="mgclr"></div>
<div class="margin_top_10"></div><div class="allbar_title">��� �����</div>

<div class="texta color_000" style="width:300px">��� ����� ����� ������ �� ���� <b>�����</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('wall1')" id="privacy_lnk_wall1">{val_wall1_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_wall1" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_wall1" class="sett_selected" onClick="settings.privacyClose('wall1')">{val_wall1_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall1', '��� ������������', '1', 'privacy_lnk_wall1')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall1', '������ ������', '2', 'privacy_lnk_wall1')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall1', '������ �', '3', 'privacy_lnk_wall1')">������ �</div>
 </div>
 <input type="hidden" id="val_wall1" value="{val_wall1}" />
<div class="mgclr"></div>

<div class="texta color_000" style="width:300px">��� ����� ��������� ��������� �� ���� <b>�����</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('wall2')" id="privacy_lnk_wall2">{val_wall2_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_wall2" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_wall2" class="sett_selected" onClick="settings.privacyClose('wall2')">{val_wall2_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall2', '��� ������������', '1', 'privacy_lnk_wall2')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall2', '������ ������', '2', 'privacy_lnk_wall2')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall2', '������ �', '3', 'privacy_lnk_wall2')">������ �</div>
 </div>
 <input type="hidden" id="val_wall2" value="{val_wall2}" />
<div class="mgclr"></div>

<div class="texta color_000" style="width:300px">��� ����� �������������� ��� <b>������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('wall3')" id="privacy_lnk_wall3">{val_wall3_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_wall3" style="margin-top:-1px">
  <div id="selected_p_privacy_lnk_wall3" class="sett_selected" onClick="settings.privacyClose('wall3')">{val_wall3_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall3', '��� ������������', '1', 'privacy_lnk_wall3')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall3', '������ ������', '2', 'privacy_lnk_wall3')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_wall3', '������ �', '3', 'privacy_lnk_wall3')">������ �</div>
 </div>
 <input type="hidden" id="val_wall3" value="{val_wall3}" />
<div class="mgclr"></div>
<div class="margin_top_10"></div><div class="allbar_title">����� �� ����</div>

<div class="texta color_000" style="width:300px">��� ����� ������ ��� ������ <b>���������</b>:</div>
 <div class="sett_privacy" onClick="settings.privacyOpen('msg')" id="privacy_lnk_msg">{val_msg_text}</div>
 <div class="sett_openmenu no_display" id="privacyMenu_msg">
  <div id="selected_p_privacy_lnk_msg" class="sett_selected" onClick="settings.privacyClose('msg')">{val_msg_text}</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_msg', '��� ������������', '1', 'privacy_lnk_msg')">��� ������������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_msg', '������ ������', '2', 'privacy_lnk_msg')">������ ������</div>
  <div class="sett_hover" onClick="settings.setPrivacy('val_msg', '�����', '3', 'privacy_lnk_msg')">�����</div>
 </div>
 <input type="hidden" id="val_msg" value="{val_msg}" />
<div class="mgclr"></div>

<br />
<div class="texta color_000" style="width:300px;margin-left:-90px">&nbsp;</div>
 <div class="button_blue fl_l"><button onClick="settings.savePrivacy(); return false" id="savePrivacy">���������</button></div>
<div class="mgclr"></div>

</div>