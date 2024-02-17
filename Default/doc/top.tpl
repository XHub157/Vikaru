<script type="text/javascript">
$('.bg_show_bottom').hide();
$('.box_footer').hide();
$(document).ready(function(){
	Xajax = new AjaxUpload('upload_2', {
		action: '/index.php?go=doc&act=upload',
		name: 'uploadfile',
		onSubmit: function (file, ext) {
			if(!(ext && /^(doc|docx|xls|xlsx|ppt|pptx|rtf|pdf|png|jpg|gif|psd|mp3|djvu|fb2|ps|jpeg|txt|rar|zip|7zip)$/.test(ext))) {
				addAllErr('Неверный формат файла', 3300);
				return false;
			}
			Page.Loading('start');
		},
		onComplete: function (file, row){
			if(row == 1)
				addAllErr('Превышен максимальный размер файла 10 МБ', 3300);
			else {
				row = row.split('"');
				Doc.AddAttach(row[0], row[1]);
			}
			Page.Loading('stop');
		}
	});
});

langNumric('langNumric', '{doc-num}', 'документ', 'документа', 'документов', 'документ', 'документов');

var page_cnt = 1;
function docAddedLoadAjax(){
	$('#wall_l_href_doc').attr('onClick', '');
	textLoad('wall_l_href_doc_load');
	$.post('/index.php?go=doc', {page_cnt: page_cnt}, function(d){
		$('#docAddedLoadAjax').append(d);
		$('#wall_l_href_doc').attr('onClick', 'docAddedLoadAjax()');
		$('#wall_l_href_doc_load').html('Показать еще документы');
		if(!d) $('#wall_l_href_doc').hide();
		page_cnt++;
	});
}
</script>
<div class="cover_edit_title fixed" style="width:605.5px">
<div class="upload_doc"><button id="upload_2">Загрузить новый файл</button></div>
<div class="clear"></div>
</div>
<div style="height:50px"></div>
<div class="clear"></div>
<div class="mgclr"></div>