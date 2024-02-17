<script type="text/javascript">
$(document).ready(function(){
	myhtml.checked(['{checked-privated}']);
});
</script>
<div style="padding:1px 14px">
 <div class="videos_text" style="margin: 12px 0px 5px;">Название</div>
 <input type="text" class="videos_input" id="name_{id}" maxlength="100" style="width:370px;padding:3px;" value="{name}" />
 <div class="videos_text" style="margin: 0px 0px 5px;">Описание</div>
 <textarea class="videos_input" id="descr_t{id}" style="width:370px;height:70px;padding:3px;">{descr}</textarea>
<div class="html_checkbox" id="privated" onclick="myhtml.checkbox(this.id)" style="margin-bottom:8px">Фотографии могут добавлять только редакторы и администраторы</div>
</div>
<div class="clear"></div>
<br />
<div class="clear"></div>