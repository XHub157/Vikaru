<script type="text/javascript" src="{theme}/js/swfupload.js"></script>
<script type="text/javascript">
var cnt = 0;
var UploadedFiles = 0;

function AlbumDeletePhoto(i){
	Page.Loading('start');
	$.get('/index.php?go=groups_albums&act=del_photo', {id: i}, function(){
		$('#a_photo_'+i).remove();
		var count_img = $('#count_img img').size();
		if(count_img < 1)
			$('#l_text').hide();

		Page.Loading('stop');
	});
}
function SetNewCover(i){
	Page.Loading('start');
	$.get('/index.php?go=groups_albums&act=set_cover', {id: i}, function(){
		$('.covers').css('background', '#fff');
		$('.cover_links').show();
		$('#cover_link_'+i).hide();
		Page.Loading('stop');
	});
}
function PhotoSaveDescr(i){
	var descr = $('#descr_'+i).val();
	Page.Loading('start');
	$.post('/index.php?go=groups_albums&act=save_descr', {id: i, descr: descr}, function(d){
		Page.Loading('stop');
	});
}

$(document).ready(function() {
    function uploadSuccess(file, serverData) {
		response = serverData;
		if(response == 'max_img'){
			Box.Info('load_photo_er2', lang_dd2f_no, lang_max_imgs, 340);
			return false
		}
		if(response == 'big_size'){
			Box.Info('load_photo_er2', lang_dd2f_no, lang_max_size, 250);
			return false
		}
				
		if(response == 'hacking'){
			return false
		} else {
			response = response.split('|||');
			$('<span id="a_photo_'+response[0]+'"></span>').appendTo('#dragndrop > ul').html('<div class="hralbum" style="margin:0px;background:#efefef;"></div><div id="cover_'+response[0]+'" class="covers" style="padding-bottom:10px;padding-top:10px;padding-left:10px;"><a href="/photo'+response[2]+'_'+response[0]+'_sec=loaded" onClick="Photo.Show(this.href); return false"><div class="albums_cover"><span id="count_img"><img src="'+response[1]+'" alt="" /></span></div></a><div style="float:left;"><div class="albums_name" style="color:#368e36;padding-bottom:5px;"><b>'+lang_albums_add_photo+'</b></div><textarea class="inpst" id="descr_'+response[0]+'" style="width:438px;height:42px;"></textarea><div class="clear"></div></div><div style="margin-top: 5px;margin-left:0px;float:left;width: 447px;"><a href="/" onClick="SetNewCover(\''+response[0]+'\'); return false;" id="cover_link_'+response[0]+'" class="cover_links fl_l" style="margin-right:10px"><div>'+lang_albums_set_cover+'</div></a><a href="/" onClick="AlbumDeletePhoto(\''+response[0]+'\'); return false;" class="fl_r"><div>Удалить</div></a><a href="/" onClick="PhotoSaveDescr(\''+response[0]+'\'); return false;" class="fl_l"><div>'+lang_albums_save_descr+'</div></a></div><div class="clear"></div></div>');
			count_img = $('#count_img img').size();
			if(count_img == 1)
				$('#l_text').show();
				
				$('body, html').animate({scrollTop: 99999}, 250);
		}
    }
    function uploadComplete(file) {
		UploadedFiles++;
		if(UploadedFiles == cnt){
			$('#status').html($('<p>Загружено ' + cnt + ' из ' + cnt + '</p>'));
			$('.uploadButton').css('width', '145px').css('height', '11px').css('overflow', 'inherit');
			$('#upBar, .uploadbuttbg').hide();
			$('#uploadproc').css('width', '0px');
		} else
			$('#status').html($('<p>Загружено ' + UploadedFiles + ' из ' + cnt + '</p>'));
    }
    function uploadStart(file) {
		$('.uploadButton').css('width', '0px').css('height', '0px').css('overflow', 'hidden');
		$('#upBar, .uploadbuttbg').show();
		if(cnt > 1)
			$('#status').html($('<p>Загружено ' + UploadedFiles + ' из ' + cnt + '</p>'));
		else
			$('#status').html('Фотография загружается..');
	
        return true;
    }
    function uploadProgress(file, bytesLoaded, bytesTotal) {
		pw = 270;
		var w = Math.ceil(pw * (UploadedFiles / cnt + (bytesLoaded / (file.size * cnt))));
        $('#uploadproc').css('width', w+'px');
    }
    function fileDialogComplete(numFilesSelected, numFilesQueued) {
		cnt = numFilesSelected;
		UploadedFiles = 0;
        this.startUpload(); 
    }
	function photos_fileQueueError(file, errorCode, message){
		try{
			switch(errorCode){
				case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
					$('.uploadButton').css('width', '0px').css('height', '0px').css('overflow', 'hidden');
					$('.uploadbuttbg').show();
					Box.Info('load_photo_er2', lang_dd2f_no, 'Максимально можно загрузить 20 фотографий за один раз.', 350, 3000);
					setTimeout(function(){
						$('.uploadButton').css('width', '145px').css('height', '11px').css('overflow', 'inherit');
						$('.uploadbuttbg').hide();
					}, 3000);
				break;
				case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
				break;
			}
		} catch(ex){
			//false 
		}
	}
    var swfu = new SWFUpload({
		upload_url: "/index.php?go=groups_albums&act=upload&aid={aid}&pid={pid}",
		flash_url: "{theme}/js/swfupload.swf",
		file_post_name: "uploadfile",
		post_params: {"PHPSESSID" : "{PHPSESSID}"},
		file_size_limit: "5 MB",
		file_types: "*.jpg; *.png; *.jpeg; *.gif",
		file_types_description: "Images",
		file_upload_limit: "20",
		debug: false,
		button_placeholder_id: "uploadButton",
		button_image_url: "{theme}/images/uploadbuttona.png",
		button_width: 145,
		button_height: 24,
		button_cursor: SWFUpload.CURSOR.HAND,
		file_dialog_complete_handler: fileDialogComplete,
		upload_success_handler: uploadSuccess,
		upload_complete_handler: uploadComplete,
		upload_start_handler: uploadStart,
		upload_progress_handler: uploadProgress,
		file_queue_error_handler: photos_fileQueueError,
	}); 
	
	$(document).ready(function(){
		PhotoGroups.Drag();
	});

});
</script>
<div class="buttonsprofile albumsbuttonsprofile">
 <a href="/albums-{pid}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">Альбомы сообщества</a>
 <a href="/albums-{pid}_{aid}" onClick="Page.Go(this.href); return false;" style="margin: 2px;">{album-name}</a>
 <div class="activetab"><a href="/albums-{pid}_{aid}/add" onClick="Page.Go(this.href); return false;" style="margin: 2px;"><div>Добавление фотографий</div></a></div>
</div>
<div class="clear" style="border-bottom:1px solid #58a358;margin: 0px -12px 12px -12px;"></div>
<div class="clear"></div>
<span id="photos"><div id="dragndrop"><ul></ul></div></span>
<div class="clear"></div>
<div class="load_photo_but" style="margin-left:155px;">
<div class="fl_l">
 <div class="uploadButton"><div id="uploadButton"></div></div>
 <div class="uploadbuttbg no_display"></div>
</div>
<div class=" button_gray fl_l" style="margin-left:10px;"><button onClick="Page.Go('/albums-{pid}_{aid}'); return false;">Просмотр альбома</button></div>
</div>
<div class="swf_loaded" id="upBar">
 <div class="video_show_bg swf_uploaded">
  <div class="upProcLotitle" id="status"></div>
  <div style="background:url('{theme}/images/photos_add_progress2.gif?1');border:1px solid #45688e;height:18px;position:absolute" id="uploadproc"></div>
  <div style="background:#fff;border:1px solid #cccccc;width:270px;height:18px;margin-bottom:10px"></div>
  Не закрывайте эту вкладку, пока не завершится загрузка..
 </div>
</div>
<input type="hidden" value="{aid}" id="aid" />