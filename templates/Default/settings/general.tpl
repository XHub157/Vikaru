<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
  <div class="buttonsprofileSec2"><a href="/settings" onClick="Page.Go(this.href); return false;"><div>�����</div></a></div>
  <a href="/settings&act=privacy" onClick="Page.Go(this.href); return false;"><div>�����������</div></a>
  <a href="/settings&act=blacklist" onClick="Page.Go(this.href); return false;"><div>������ ������</div></a>
  <a href="/settings&act=mobile" onClick="Page.Go(this.href); return false;"><div>��������� �������</div></a>
  <a href="/settings&act=balance" onClick="Page.Go(this.href); return false;"><div>������</div></a>
 </div>
</div>
<div class="settings_general">
<div class="settings_section">
<div class="err_yellow name_errors {code-1}" style="font-weight:normal;margin-top:25px">��� ��������� �� ������ � �������� ��������� ����� ������. �������� ����������� ��� ��������� � ������, ������������ �� ����� �������� ����.</div>
<div class="err_yellow name_errors {code-2}" style="font-weight:normal;margin-top:25px">��� ��������� �� ������ � ������ ��������� ����� ������. �������� ����������� ��� ��������� � ������, ������������ �� ������� �������� ����.</div>
<div class="err_yellow name_errors {code-3}" style="font-weight:normal;margin-top:25px">����� ����� ����������� ����� ��� ������� ������� �� �����.</div>
<div class="margin_top_10"></div><div class="allbar_title">�������� ������</div>
<div class="err_red no_display pass_errors" id="err_pass_1" style="font-weight:normal;">������ �� ������, ��� ��� ������� ������ ����� �����������.</div>
<div class="err_red no_display pass_errors" id="err_pass_2" style="font-weight:normal;">������ �� ������, ��� ��� ����� ������ �������� �����������.</div>
<div class="err_yellow no_display pass_errors" id="ok_pass" style="font-weight:normal;">������ ������� ������.</div>
<div class="texta">������ ������:</div><input type="password" id="old_pass" class="inpst" maxlength="100" style="width:150px;" /><span id="validOldpass"></span><div class="mgclr"></div>
<div class="texta">����� ������:</div><input type="password" id="new_pass" class="inpst" maxlength="100" style="width:150px;" onMouseOver="myhtml.title('', '������ ������ ���� �� ����� 6 �������� � �����', 'new_pass')" /><span id="validNewpass"></span><div class="mgclr"></div>
<div class="texta">��������� ������:</div><input type="password" id="new_pass2" class="inpst" maxlength="100" style="width:150px;" onMouseOver="myhtml.title('', '������� ��� ��� ����� ������', 'new_pass2')" /><span id="validNewpass2"></span><div class="mgclr"></div>
<div class="texta">&nbsp;</div><div class="button_blue fl_l"><button onClick="settings.saveNewPwd(); return false" id="saveNewPwd">�������� ������</button></div><div class="mgclr"></div>
<div class="margin_top_10"></div><div class="allbar_title">����� ����� ����������� �����</div>
<div class="err_yellow name_errors no_display" id="ok_email" style="font-weight:normal;">�� <b>���</b> �������� ����� ������ ������ � ��������������.</div>
<div class="err_red no_display name_errors" id="err_email" style="font-weight:normal;">������������ email �����</div>
<div class="texta">������� �����:</div><div style="color:#555;margin-top:13px;margin-bottom:10px">{email}</div><div class="mgclr"></div>
<div class="texta">����� �����:</div><input type="text" id="email" class="inpst" maxlength="100" style="width:150px;" /><span id="validName"></span><div class="mgclr"></div>
<div class="texta">&nbsp;</div><div class="button_blue fl_l"><button onClick="settings.savenewmail(); return false" id="saveNewEmail">��������� �����</button></div><div class="mgclr"></div>
<div class="margin_top_10"></div><div class="allbar_title">����� ������������ ��������</div>
<div class="err_yellow no_display name_errors" id="ok_alias" style="font-weight:normal;">����� ������������ �������� ��� ������� ����������.</div>
<div class="err_red no_display name_errors" id="err_alias_str" style="font-weight:normal;">������������ �����.</div>
<div class="err_red no_display name_errors" id="err_alias_name" style="font-weight:normal;">����� ��� �����.</div>
<div class="texta" >������ �����: </div><span style="border: 1px solid #C6D4DC;  border-right: 0px;padding: 3px 4px;margin-right: -4px;color: #777;" onclick="settings.elfocus('alias')">http://WebMaster.pp.ua/</span><input type="text" id="alias" class="inpst" maxlength="10"  style="width:66px;border-left:0px;" value="{alias}" /><div class="mgclr"></div><div class="texta">&nbsp;</div><div class="button_blue fl_l"><button onClick="settings.savealias(); return false" id="saveAlias">���������</button></div><div class="mgclr"></div>
<div class="margin_top_10"></div><div class="allbar_title">������������ ����� ��������</div>
<div class="texta">��������� ����������:</div>
<div style="margin:4px 0" class="fl_l" id="acts" onmouseover="myhtml.title('', 'IP ���������� ��������� {ip}', 'acts')">{log-user}</div>
<div class="margin_top_10"></div>
<div class="margin_top_10"></div>
<div class="mgclr"></div>
<div class="texta">&nbsp;</div>
<a onClick="settings.logs();" style="cursor:pointer">���������� ������� ����������</a>
<div class="mgclr"></div>
<div class="allbar_title">������������ ���������</div>
<div class="texta">����:</div><select id="city" class="inpst" style="width:161px"> 
  <option value="0">�������</option> </select><div class="mgclr"></div>
</div>
</div>
<br>
<div class="settings_view_as_text clear_fix" align="center">
  �� ������ <a href="/settings&act=deactivate">������� ���� ��������</a>.
</div>