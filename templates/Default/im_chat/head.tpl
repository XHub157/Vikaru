<link href="/templates/Default/im_chat/im_chat.css" type="text/css" rel="stylesheet">

<script type="text/javascript" src="/templates/Default/js/icons.js"></script>
<script type="text/javascript">
$( init );
function init() {
  $('#im_chat_block').draggable();
}
</script>
<div class="im_chat_block" id="im_chat_block">
<script type="text/javascript">
$(document).ready(function(){
	vii_interval = setInterval('im_chat.updateDialogs()', 2000);
});
</script>
<div class="im_chathead fl_l">{count-fr}</div>
<div class="im_chathead2 fl_l"></div>
<div class="im_chatclose fl_r" onclick="im_chat.close();"><div></div></div>
<div class="clear"></div>
{mail}
<div class="im_chatSearch">
<input type="text" id="im_chatSearch" placeholder="Начните вводить имя..." onkeydown='im_chat.search();' class="im_chatsearchtext" value="">
</div>
<span id="updateDialogs"></span>
<div class="im_chatserch"></div>
<div class="im_chatbody">
{dialogs}
</div>
<div class="im_chatbody2" style="display:none;">
</div>
<div class="clear"></div>
</div>
