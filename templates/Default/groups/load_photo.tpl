<div class="miniature_box">
 <div class="miniature_pos" style="width:644px;">
  <div class="miniature_title fl_l apps_box_text" style="font-size:14px">Изменение главной фотографии</div><a class="cursor_pointer fl_r" onClick="Box.Close()">Закрыть</a>
<script type="text/javascript">
$(document).ready(function(){
	Xajax = new AjaxUpload('upload', {
		action: '/index.php?go=groups&act=loadphoto&id={id}',
		name: 'uploadfile',
		onSubmit: function (file, ext){
			if (!(ext && /^(jpg|png|jpeg|gif|jpe)$/.test(ext))) {
				Box.Info('load_photo_er', lang_dd2f_no, lang_bad_format, 400);
				return false;
			}
			butloading('upload', '113', 'disabled', '');
		},
		onComplete: function (file, data){
			butloading('upload', '113', 'enabled', 'Выбрать фотографию');
			if(data == 'big_size'){
				Box.Info('load_photo_er2', lang_dd2f_no, lang_max_size, 250);
				return false
			} else {
				viiBox.clos('loadphoto', 1);
				$('#ava').attr('src', '/uploads/groups/{id}/'+data);
				$('#del_pho_but').show();
			}
		}
	});
});
</script>
<div class="load_photo_pad" style="padding:0px">
<div class="err_red" style="display:none;font-weight:normal;"></div><div class="mgclr"></div>
Хорошая фотография сделает Ваше сообщество более узнаваемым.<br>
Вы можете загрузить изображение в формате JPG, GIF или PNG
<div class="load_photo_but" style="margin: 10px 35% 20px 36%;"><div class="button_blue fl_l"><button id="upload" style="font-size:13px;">Выбрать фотографию</button></div></div>
Если у Вас возникают проблемы с загрузкой, попробуйте выбрать фотографию меньшего размера.</div></div></div>