<style type="text/css" media="all">
.texta_profileedit {width: 195px;padding-top: 5px;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	myhtml.checked(['{settings-audio}','{settings-contact}','{settings-comments}','{settings-videos}']);
	$('#descr').autoResize({extraSpace:0,limit:608});
	if($('#public_category').val() == 0) $('#pcategory').hide();
});
</script>
<div class="sft" style="margin-top:-6px">
 <div class="buttonsprofile albumsbuttonsprofile buttonsprofileSecond2" style="height:15px">
 <div class="buttonsprofileSec2"><a href="/epage?act=edit&pid={pid}" onClick="Page.Go(this.href); return false;"><div>����������</div></a></div>
 <a href="/epage?act=users&pid={pid}" onClick="Page.Go(this.href); return false;"><div>���������</div></a>
 <a href="/epage?act=blacklist&pid={pid}" onClick="Page.Go(this.href); return false;"><div>������ ������</div></a>
 <a href="/epage?act=link&pid={pid}" onClick="Page.Go(this.href); return false;"><div>������</div></a>
</div>
</div>
<div style="margin-top:-120px"><a href="/{adres}" onClick="Page.Go(this.href); return false;" style="float:right;">�������� � ����������</a></div>
<div style="margin-top:120px"></div>
<div class="settings_general">
<div class="err_yellow" id="info_save" style="display:none;font-weight:normal;margin: 20px 0px -20px 0px;"></div><br><br>
<div class="clear" style="margin-top:10px;"></div>
  <div class="texta_profileedit">��������:</div>
   <input type="text" id="title" class="inpst" maxlength="100"  style="width:256px;" value="{title}" />
  <div class="mgclr"></div>
    <div class="texta_profileedit">����� ��������:</div>
   <span style="border: 1px solid #C6D4DC;  border-right: 0px;padding: 3px 4px;margin-right: -6px;color: #777;" onclick="settings.elfocus('adres_page')">
   http://webmaster.pp.ua/</span><input type="text" id="adres_page" class="inpst" maxlength="10"  style="width:172px;border-left:0px;outline:none;background: transparent;" value="{adres}" />
  <div class="mgclr"></div>  
  <div class="texta_profileedit">��������:</div>
  <textarea class="inpst" style="height: 60px; width: 256px; margin-bottom: 10px; resize: none; overflow-y: hidden; position: absolute; top: 0px; left: -9999px; line-height: normal; text-decoration: none; letter-spacing: normal; " tabindex="-1"></textarea>
  <textarea id="descr" class="inpst" style="width:256px;height:60px;resize:none;overflow-y: hidden;">{edit-descr}</textarea>
  <div class="mgclr"></div>
  <div class="texta_profileedit">���-����:</div>
   <input type="text" id="website" class="inpst" maxlength="100"  style="width:256px;" value="{website}" />
  <div class="mgclr"></div>
  <div class="mgclr" style="padding-top:7px;"></div>
  <div class="texta_profileedit" style="padding-top: 0px;">�������� �����:</div>
   <div class="html_checkbox" id="comments" onClick="myhtml.checkbox(this.id)" style="margin-bottom:8px">����������� ��������</div>
  <div class="mgclr clear" style="padding-top:7px;"></div>
  <div class="texta_profileedit" style="padding-top: 0px;">�������������� �������:</div>
   <div class="html_checkbox" id="audio" onClick="myhtml.checkbox(this.id)" style="margin-bottom:8px">�����������</div>
  <div class="mgclr clear" style="padding-top:3px;"></div>
  <div class="texta_profileedit" style="padding-top: 0px;">&nbsp;</div>
  <div class="html_checkbox" id="contact" onClick="myhtml.checkbox(this.id)" style="margin-bottom:8px">��������</div>
  <div class="mgclr clear" style="padding-top:3px;"></div>
  <div class="texta_profileedit" style="padding-top: 0px;">&nbsp;</div>
  <div class="html_checkbox" id="videos" onClick="myhtml.checkbox(this.id)" style="margin-bottom:8px">�����������</div>
  <div class="mgclr clear"></div>
<div style="margin: 15px 0px;"></div>
<div class="texta_profileedit">&nbsp;</div><div class="button_blue fl_l"><button name="save" onClick="epage.saveInfo('{pid}'); return false" id="saveform_interests">���������</button></div><div class="mgclr_edit"></div>
<br>
<br>
</div>