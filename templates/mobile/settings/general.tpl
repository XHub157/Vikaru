<div class="clr"></div>
<div class="margin_top_10"></div><div class="allbar_title">�������� ������</div>
<div class="infobl err_red no_display pass_errors" id="err_pass_1" style="font-weight:normal;">������ �� ������, ��� ��� ������� ������ ����� �����������.</div>
<div class="infobl err_red no_display pass_errors" id="err_pass_2" style="font-weight:normal;">������ �� ������, ��� ��� ����� ������ �������� �����������.</div>
<div class="infobl err_yellow no_display pass_errors" id="ok_pass" style="font-weight:normal;">������ ������� ������.</div>
<div class="texta">������ ������:</div><input type="password" id="old_pass" class="inp" maxlength="100" /><span id="validOldpass"></span>
<div class="texta">����� ������:</div><input type="password" id="new_pass" class="inp" maxlength="100" /><span id="validNewpass"></span>
<div class="texta">��������� ������:</div><input type="password" id="new_pass2" class="inp" maxlength="100" /><span id="validNewpass2"></span>
<button onClick="settings.saveNewPwd(); return false" id="saveNewPwd" class="button" style="margin-top:10px">�������� ������</button>
<div class="allbar_title" style="margin-top:10px">�������� ���</div>
<div class="infobl err_red no_display name_errors" id="err_name_1" style="font-weight:normal;">����������� ������� � ������� ���������.</div>
<div class="infobl err_yellow no_display name_errors" id="ok_name" style="font-weight:normal;">��������� ������� ���������.</div>
<div class="texta">���� ���:</div><input type="text" id="name" class="inp" maxlength="100"  value="{name}" /><span id="validName"></span>
<div class="texta">���� �������:</div><input type="text" id="lastname" class="inp" maxlength="100"  value="{lastname}" /><span id="validLastname"></span>
<button onClick="settings.saveNewName(); return false" id="saveNewName" class="button" style="margin-top:10px">�������� ���</button>