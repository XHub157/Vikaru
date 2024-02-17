var uagent = navigator.userAgent.toLowerCase();
var is_safari = ((uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc."));
var is_ie = ((uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv));
var is_ie4 = ((is_ie) && (uagent.indexOf("msie 4.") != -1));
var is_moz = (navigator.product == 'Gecko');
var is_ns = ((uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari));
var is_ns4 = ((is_ns) && (parseInt(navigator.appVersion) == 4));
var is_opera = (uagent.indexOf('opera') != -1);
var is_kon = (uagent.indexOf('konqueror') != -1);
var is_webtv = (uagent.indexOf('webtv') != -1);
var is_win = ((uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") != -1));
var is_mac = ((uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc."));
var is_chrome = (uagent.match(/Chrome\/\w+\.\w+/i)); if(is_chrome == 'null' || !is_chrome || is_chrome == 0) is_chrome = '';
var ua_vers = parseInt(navigator.appVersion);
var req_href = location.href;
var vii_interval = false;
var vii_interval_im = false;
var scrollTopForFirefox = 0;
var url_next_id = 1;

$(document).ready(function(){
	var mw = ($('html, body').width()-800)/2;
	if($('.autowr').css('padding-left', mw+'px').css('padding-right', mw+'px')){
		$('body').show();
		history.pushState({link:location.href}, '', location.href);
	}
	$('.update_code').click(function(){
		var rndval = new Date().getTime(); 
		$('#sec_code').html('<img src="/antibot/antibot.php?rndval=' + rndval + '" alt="" title="Показать другой код" width="120" height="50" />');
		return false;
	});
	$(window).scroll(function(){
		if($(document).scrollTop() > ($(window).height()/2))
			$('.scroll_fix_bg').fadeIn(200); 
		else 
			$('.scroll_fix_bg').fadeOut(200); 
	});
	$(window).scroll(function(){
		if($(document).scrollTop() > ($(window).height()/1))
			$('.fmenu').fadeIn(0); 
		else 
			$('.fmenu').fadeOut(0); 
	});
});

$(document).click(function(event){
	oi = (event.target) ? event.target.id: ((event.srcElement) ? event.srcElement.id : null);
	if(oi != 'fast_name' && oi != 'fast_img' && oi != 'search_selected_text' && oi != 'query' && oi != '1' && oi != '2' && oi != '3' && oi != '4' && oi != '5')
		$('.fast_search_bg').hide();
});

if(CheckRequestPhoto(req_href)){
	$(document).ready(function(){
		Photo.Show(req_href);
	});
}

if(CheckRequestVideo(req_href)){
	$(document).ready(function(){
		var video_id = req_href.split('_');
		var section = req_href.split('sec=');
		var fuser = req_href.split('wall/fuser=');

		if(fuser[1])
			var close_link = '/u'+fuser[1];
		else
			var close_link = '';
		
		if(section[1]){
			var xSection = section[1].split('/');

			if(xSection[0] == 'news')
				var close_link = 'news';

			if(xSection[0] == 'msg'){
				var msg_id = xSection[1].split('id=');
				var close_link = '/messages/show/'+msg_id[1];
			}
		}
		
		videos.show(video_id[1], req_href, close_link);
	});
}

//AJAX PAGES
window.onload = function(){ 
	window.setTimeout(
		function(){ 
			window.addEventListener(
				"popstate",  
				function(e){
					e.preventDefault(); 

					if(CheckRequestPhoto(e.state.link))
						Photo.Prev(e.state.link);
					else if(CheckRequestVideo(e.state.link))
						videos.prev(e.state.link);
					else
						Page.Prev(e.state.link);
				},  
			false); 
		}, 
	1); 
}
function CheckRequestPhoto(request){
	var pattern = new RegExp(/photo[0-9]/i);
 	return pattern.test(request);
}
function CheckRequestVideo(request){
	var pattern = new RegExp(/video[0-9]/i);
 	return pattern.test(request);
}
function onBodyResize(){
	var mw = ($('html, body').width()-800)/2;
	$('.autowr').css('padding-left', mw+'px').css('padding-right', mw+'px');
}
var Page = {
	Loading: function(f){
		var top_pad = $(window).height()/2-50;
		if(f == 'start'){
			$('#loading').remove();
			$('html, body').append('<div id="loading" style="margin-top:'+top_pad+'px"><div class="loadstyle"></div></div>');
			$('#loading').show();
		}
		if(f == 'stop'){
			$('#loading').remove();
		}
	},
	Go: function(h){	
		history.pushState({link:h}, null, h);
		$('.js_titleRemove').remove();
		
		clearInterval(vii_interval);
		clearInterval(vii_interval_im);
		
		$('#sel_types, .fast_search_bg').hide();
		$('#query').val('Поиск').css('color', '#c1cad0');

		Page.Loading('start');
		$('#page').load(h, {ajax: 'yes'}, function(data){
			Page.Loading('stop');
			$('html, body').scrollTop(0);
			
			$('.ladybug_ant').imgAreaSelect({remove: true});
			
			//Удаляем кеш фоток, видео, модальных окон
			$('.photo_view, .box_pos, .box_info, .video_view').remove();
			
			//Возвращаем scroll
			$('html, body').css('overflow-y', 'auto');

		}).css('min-height', '0px');
	},
	Prev: function(h){
		clearInterval(vii_interval);
		clearInterval(vii_interval_im);
		
		$('#sel_types, .fast_search_bg').hide();
		$('#query').val('Поиск').css('color', '#c1cad0');
		
		Page.Loading('start');
		$('#page').load(h, {ajax: 'yes'}, function(data){
			Page.Loading('stop');

			$('html, body').scrollTop(0);
			
			$('.ladybug_ant').imgAreaSelect({remove: true});
			
			//Удаляем кеш фоток, видео, модальных окон
			$('.photo_view, .box_pos, .box_info, .video_view').remove();
			
			//Возвращаем scroll
			$('html, body').css('overflow-y', 'auto');

		}).css('min-height', '0px');		
	}
}
//PROFILE FUNC
var Profile = {
	LoadCity: function(id){
		$('#load_mini').show();
		if(id > 0){
			$('#city').slideDown();
			$('#select_city').load('/index.php?go=loadcity', {country: id});
		} else {
			$('#city').slideUp();
			$('#load_mini').hide();
		}
	},
	webcam:function(){
		$.post('/index.php?go=editprofile&act=webcam',function(d){
			$('html, body').css({'overflow-y':'hidden','margin':'0 17px 0 0'});
			$('body').append('<div id="newbox_miniature">'+d+'</div>');
		});
	},
	miniature: function(){
		Page.Loading('start');
		$.post('/index.php?go=editprofile&act=miniature', function(d){
			Page.Loading('stop');
			if(d == 1) 
				addAllErr('Вы пока что не загрузили фотографию.');
			else {
				if(is_moz && !is_chrome) scrollTopForFirefox = $(window).scrollTop();
				$('html, body').css('overflow-y', 'hidden');
				if(is_moz && !is_chrome) $(window).scrollTop(scrollTopForFirefox);
				$('body').append('<div id="newbox_miniature">'+d+'</div>');
			}
			$(window).keydown(function(event){
				if(event.keyCode == 27) Profile.miniatureClose();
			});
		});
	},
	preview: function(img, selection){
		if(!selection.width || !selection.height) return;
		var scaleX = 100 / selection.width;
		var scaleY = 100 / selection.height;
		var scaleX50 = 50 / selection.width;
		var scaleY50 = 50 / selection.height;
		$('#miniature_crop_100 img').css({
			width: Math.round(scaleX * $('#miniature_crop').width()),
			height: Math.round(scaleY * $('#miniature_crop').height()),
			marginLeft: -Math.round(scaleX * selection.x1),
			marginTop: -Math.round(scaleY * selection.y1)
		});
		$('#miniature_crop_50 img').css({
			width: Math.round(scaleX50 * $('#miniature_crop').width()),
			height: Math.round(scaleY50 * $('#miniature_crop').height()),
			marginLeft: -Math.round(scaleX50 * selection.x1),
			marginTop: -Math.round(scaleY50 * selection.y1)
		});
	},
	miniatureSave: function(){
		var i_left = $('#mi_left').val();
		var i_top = $('#mi_top').val();
		var i_width = $('#mi_width').val();
		var i_height = $('#mi_height').val();
		butloading('miniatureSave', '111', 'disabled', '');
		$.post('/index.php?go=editprofile&act=miniature_save', {i_left: i_left, i_top: i_top, i_width: i_width, i_height: i_height}, function(d){
			if(d == 'err') addAllErr('Ошибка');
			else window.location.href = '/u'+d;
			butloading('miniatureSave', '111', 'enabled', 'Сохранить изменения');
		});
	},
	miniatureClose: function(){
		$('#miniature_crop').imgAreaSelect({remove: true});
		$('#newbox_miniature').remove();
		$('html, body').css('overflow-y', 'auto');
	},
	LoadCity: function(id){
		$('#load_mini').show();
		if(id > 0){
			$('#city').slideDown();
			$('#select_city').load('/index.php?go=loadcity', {country: id});
		} else {
			$('#city').slideUp();
			$('#load_mini').hide();
		}
	},
	//MAIN PHOTOS
	LoadPhoto: function(){
		Page.Loading('start');
		$.get('/index.php?go=editprofile&act=load_photo', function(data){
			Box.Show('photo', 400, lang_title_load_photo, data, lang_box_canсel);
			Page.Loading('stop');
		});
	},
	DelPhoto: function(){
		Box.Show('del_photo', 400, lang_title_del_photo, '<div style="padding:15px;">'+lang_del_photo+'</div>', lang_box_canсel, lang_box_yes, 'Profile.StartDelPhoto(); return false;');
	},
	StartDelPhoto: function(){
		$('#box_loading').show();
		$.get('/index.php?go=editprofile&act=del_photo', function(){
			$('#ava').html('<img src="/templates/Default/images/no_ava.gif" alt="" />');
			$('#del_pho_but').hide();
			Box.Close('del_photo');
			Page.Loading('stop');
		});
	},
	MoreInfo: function(){
		$('#moreInfo').show();
		$('#moreInfoText').text('Скрыть подробную информацию');
		$('#moreInfoLnk').attr('onClick', 'Profile.HideInfo()');
	},
	HideInfo: function(){
		$('#moreInfo').hide();
		$('#moreInfoText').text('Показать подробную информацию');
		$('#moreInfoLnk').attr('onClick', 'Profile.MoreInfo()');
	}
}

// RADIO BUTTON
var radiobtn = {
	select: function(j,i){
		$('#'+j).val(i);
		$('.settings_reason').removeClass('on');
		$(event.target).addClass('on');
	}
}

//MODAL BOX
var Box = {
	Page: function(url, data, name, width, title, cancel_text, func_text, func, height, overflow, bg_show, bg_show_bottom, input_focus, cache){
	
		//url - ссылка которую будем загружать
		//data - POST данные
		//name - id окна
		//width - ширина окна
		//title - заголовк окна
		//content - контент окна
		//close_text - текст закрытия
		//func_text - текст который будет выполнять функцию
		//func - функция текста "func_text"
		//height - высота окна
		//overflow - постоянный скролл
		//bg_show - тень внтури окна сверху
		//bg_show_bottom - "1" - с тенью внтури, "0" - без тени внутри
		//input_focus - ИД текстового поля на котором будет фиксация
		//cache - "1" - кешировоть, "0" - не кешировать

		if(cache)
			if(ge('box_'+name)){
				Box.Close(name, cache);
				$('#box_'+name).show();
				$('#box_content_'+name).scrollTop(0);
				if(is_moz && !is_chrome)
					scrollTopForFirefox = $(window).scrollTop();
				
				$('html').css('overflow', 'hidden');

				if(is_moz && !is_chrome)
					$(window).scrollTop(scrollTopForFirefox);
				return false;
			}
		
		Page.Loading('start');
		$.post(url, data, function(html){
			if(!CheckRequestVideo(location.href))
				Box.Close(name, cache);
			Box.Show(name, width, title, html, cancel_text, func_text, func, height, overflow, bg_show, bg_show_bottom, cache);
			Page.Loading('stop');
			if(input_focus)
				$('#'+input_focus).focus();
		});
	},
	Show: function(name, width, title, content, close_text, func_text, func, height, overflow, bg_show, bg_show_bottom, cache){
		
		//name - id окна
		//width - ширина окна
		//title - заголовк окна
		//content - контент окна
		//close_text - текст закрытия
		//func_text - текст который будет выполнять функцию
		//func - функция текста "func_text"
		//height - высота окна
		//overflow - постоянный скролл
		//bg_show - тень внтури окна сверху
		//bg_show_bottom - тень внтури внтури снизу
		//cache - "1" - кешировоть, "0" - не кешировать
		
		if(func_text)
			var func_but = '<div class="button_div fl_r" style="margin-right:10px;" id="box_but"><button onClick="'+func+'" id="box_butt_create">'+func_text+'</button></div>';
		else
			var func_but = '';
			
		var close_but = '<div class="button_div_gray fl_r"><button onClick="Box.Close(\''+name+'\', '+cache+'); return false;">'+close_text+'</button></div>';
		
		var box_loading = '<img id="box_loading" style="display:none;padding-top:8px;padding-left:5px;" src="/templates/Default/images/loading_mini.gif" alt="" />';
		
		if(height)
			var top_pad = ($(window).height()-150-height)/2;
			if(top_pad < 0)
				top_pad = 100;
			
		if(overflow)
			var overflow = 'overflow-y:scroll;';
		else
			var overflow = '';
			
		if(bg_show)
			if(overflow)
				var bg_show = '<div class="bg_show" style="width:'+(width-19)+'px;"></div>';
			else
				var bg_show = '<div class="bg_show" style="width:'+(width-2)+'px;"></div>';
		else
			var bg_show = '';
		
		if(bg_show_bottom)
			if(overflow)
				var bg_show_bottom = '<div class="bg_show_bottom" style="width:'+(width-17)+'px;"></div>';
			else
				var bg_show_bottom = '<div class="bg_show_bottom" style="width:'+(width-2)+'px;"></div>';
		else
			var bg_show_bottom = '';
			
		if(height)
			var sheight = 'height:'+height+'px';
		else
			var sheight = '';

		$('body').append('<div id="modal_box"><div id="box_'+name+'" class="box_pos"><div class="box_bg" style="width:'+width+'px;margin-top:'+top_pad+'px;"><div class="box_title" id="box_title_'+name+'">'+title+'<div class="box_close" onClick="Box.Close(\''+name+'\', '+cache+'); return false;"></div></div><div class="box_conetnt" id="box_content_'+name+'" style="'+sheight+';'+overflow+'">'+bg_show+content+'<div class="clear"></div></div>'+bg_show_bottom+'<div class="box_footer"><div id="box_bottom_left_text" class="fl_l">'+box_loading+'</div>'+close_but+func_but+'</div></div></div></div>');
		
		$('#box_'+name).show();

		if(is_moz && !is_chrome)
			scrollTopForFirefox = $(window).scrollTop();
		
		$('html').css('overflow', 'hidden');

		if(is_moz && !is_chrome)
			$(window).scrollTop(scrollTopForFirefox);
		
		$(window).keydown(function(event){
			if(event.keyCode == 27) {
				Box.Close(name, cache);
			} 
		});
	},
	Close: function(name, cache){
	
		if(!cache)
			$('.box_pos').remove();
		else
			$('.box_pos').hide();

		if(CheckRequestVideo(location.href) == false && CheckRequestPhoto(location.href) == false)
			$('html, body').css('overflow-y', 'auto');
			
		if(CheckRequestVideo(location.href))
			$('#video_object').show();
			
		if(is_moz && !is_chrome)
			$(window).scrollTop(scrollTopForFirefox);
	},
	GeneralClose: function(){
		$('#modal_box').hide();
	},
	Info: function(bid, title, content, width, tout){
		var top_pad = ($(window).height()-115)/2;
		$('body').append('<div id="'+bid+'" class="box_info"><div class="box_info_margin" style="width: '+width+'px; margin-top: '+top_pad+'px"><b><span>'+title+'</span></b><br /><br />'+content+'</div></div>');
		$(bid).show();
		
		if(!tout)
			var tout = 1400;
		
		setTimeout("Box.InfoClose()", tout);
		
		$(window).keydown(function(event){
			if(event.keyCode == 27) {
				Box.InfoClose();
			} 
		});
	},
	InfoClose: function(){
		$('.box_info').fadeOut();
	}
}
function ge(i){
	return document.getElementById(i);
}
function in_array(myValue,myArray){
    function equals(a,b){return (a === b);}
    for (var i in myArray) if (equals(myArray[i],myValue) ) return true;
    return false;
}
function butloading(i, w, d, t){
	if(d == 'disabled'){
		$('#'+i).html('<div style="width:'+w+'px;text-align:center;"><img src="/templates/Default/images/loading_mini.gif" alt="" /></div>');
		ge(i).disabled = true;
	} else {
		$('#'+i).html(t);
		ge(i).disabled = false;
	}
}
function textLoad(i){
	$('#'+i).html('<img src="/templates/Default/images/loading_mini.gif" alt="" />').attr('onClick', '').attr('href', '#');
}
function updateNum(i, type){
	if(type)
		$(i).text(parseInt($(i).text())+1);
	else
		$(i).text($(i).text()-1);
}
$(document).ready(function(){
	setInterval(function(){
	$("#ads_view").show();
	}, 10000);
	setInterval(function(){
        $.ajax({
            url: "index.php?go=ads&act=ads_view",
            cache: true,
            success: function(html){
                $("#ads_view").html(html);
            }
        });
	}, 10000);
});	function ads_close(){
		$("#ads_view").fadeOut(400);
}
function setErrorInputMsg(i){
	$("#"+i).css('background', '#ffefef');
	$("#"+i).focus();
	setTimeout("$('#"+i+"').css('background', '#fff').focus()", 700);
}
function addAllErr(text, tim){
	if(!tim)
		var tim = 2500;
		
	$('.privacy_err').remove();
	$('body').append('<div class="privacy_err no_display">'+text+'</div>');
	$('.privacy_err').fadeIn('fast');
	setTimeout("$('.privacy_err').fadeOut('fast')", tim);
}
function langNumric(id, num, text1, text2, text3, text4, text5){
	strlen_num = num.length;
	
	if(num <= 21){
		numres = num;
	} else if(strlen_num == 2){
		parsnum = num.substring(1,2);
		numres = parsnum.replace('0','10');
	} else if(strlen_num == 3){
		parsnum = num.substring(2,3);
		numres = parsnum.replace('0','10');
	} else if(strlen_num == 4){
		parsnum = num.substring(3,4);
		numres = parsnum.replace('0','10');
	} else if(strlen_num == 5){
		parsnum = num.substring(4,5);
		numres = parsnum.replace('0','10');
	}
	
	if(numres <= 0)
		var gram_num_record = text5;
	else if(numres == 1)
		var gram_num_record = text1;
	else if(numres < 5)
		var gram_num_record = text2;
	else if(numres < 21)
		var gram_num_record = text3;
	else if(numres == 21)
		var gram_num_record = text4;
	else
		var gram_num_record = '';
	
	$('#'+id).html(gram_num_record);
}

var doLoad = {
data: function(i){
doLoad.js(i);
},
js: function(i){
var arr = ['rating'];
var check = $('#dojs'+arr[i]).length;
var template_dir = '/templates/Default';
if(!check) $('#doLoad').append('<div id="dojs'+arr[i]+'"><script type="text/javascript" src="'+template_dir+'/js/'+arr[i]+'.js"></script></div>');
}
}

var Apps = {
initAppView: function(params, options) {
    cur.nav.push(function(changed, old, n, opt) {
      if (changed['0'] === undefined && !changed['join'] && !opt.pass) {
        if (changed['#']) {
          cur.app.onLocChanged(changed['#']);
          if (opt.back) {
            if (vk.al != 3) {
              nav.setLoc(n);
            }
          } else {
            nav.setLoc(n);
          }
          return false;
        } else {
          nav.setLoc(n);
          return false;
        }
      }
    });

    var stateCallback = function(e) {
      if (e.type == 'block') {
        cur.app.runCallback('onWindowBlur');
      } else {
        cur.app.runCallback('onWindowFocus');
      }
    };

    cur.app.onReady.push(function() {
      //alert('inited');
      cur.app.onLocChanged(params.hash);
      addEvent(document, 'block unblock', stateCallback, true);
      cur.destroy.push(function() {
        removeEvent(document, 'block unblock', stateCallback);
      });
    });

    if (options.icon) {
      setFavIcon(options.icon);
      cur.destroy.push(function() {
        setFavIcon('/images/favicon' + (vk.intnat ? '_vk' : 'new') + '.ico');
      });
    }
}
}

//VII BOX
var viiBox = {
start: function(){
  Page.Loading('start');
},
stop: function(){
  Page.Loading('stop');
},
win: function(i, d, o, h){
  viiBox.stop();
  if(is_moz && !is_chrome) scrollTopForFirefox = $(window).scrollTop();
  $('html, body').css('overflow-y', 'hidden');
  if(is_moz && !is_chrome) $(window).scrollTop(scrollTopForFirefox);
  $('body').append('<div class="vii_box" id="newbox_miniature'+i+'">'+d+'</div>');
  var mw = ($('html, body').width()-$('.miniature_pos').width())/2;
  $('.miniature_pos').css('margin-left', mw+'px').css('margin-right', mw+'px');
  $(window).keydown(function(event){
   if(event.keyCode == 27)
viiBox.clos(i, o, h);
  });
},
clos: function(i, o, h){
  $('#newbox_miniature'+i).remove();
  if(o) $('html, body').css('overflow-y', 'auto');
  if(h) history.pushState({link:h}, null, h);
}
}

//LANG
template_dir					= '/templates/Default';
uploads_dir						= '/uploads';
uploads_smile_dir				= '/uploads/smiles';
lang_empty						= 'Поля не должны быть пустыми.';
lang_nosymbol					= 'Специальные символы и пробелы запрещены.';
lang_pass_none					= 'Пароли не совпадают';
lang_code_none					= 'Код безопасности не соответствует отображённому';
lang_please_code				= 'Введите код с картинки';
lang_bad_email					= 'Неправильный адрес';
lang_none_sex					= 'Укажите Ваш пол';
lang_no_vk						= 'Указанная Вами ссылка не является сайтом в контакте';
lang_no_od						= 'Указанная Вами ссылка не является сайтом однаклассники';
lang_no_fb						= 'Указанная Вами ссылка не является сайтом facebook';
lang_no_icq						= 'Номер ICQ должен состоять только из цифр';
lang_infosave					= 'Изменения сохранены';
lang_infosavepublic				= '<b>Изменения сохранены.</b><br><br>Основная информация и настройки сообщества сохранены.';
lang_bad_format					= 'Неверный формат файла';
lang_bad_size					= 'Файл не должен превышать 5 Mб';
lang_bad_aaa					= 'Неизвестная ошибка';
lang_del_photo					= 'Вы уверены, что хотите удалить фотографию?';
lang_del_album					= 'Вы уверены, что хотите удалить альбом?';
lang_title_del_photo			= 'Предупреждениe';
lang_box_canсel					= 'Отмена';
lang_box_yes					= 'Да';
lang_box_send					= 'Отправить';
lang_box_save					= 'Сохранить';
lang_box_insert					= 'Вставить';
lang_title_load_photo			= 'Загрузка главной фотографии';
lang_title_new_album			= 'Создание нового альбома';
lang_album_create				= 'Готово';
lang_nooo_er					= 'Код ошибки: 1';
lang_del_comm					= 'Комментарий успешно удален.';
lang_edit_albums				= 'Редактирование альбома';
lang_edit_cover_album			= 'Выберите фотографию на обложку';
lang_demand_ok					= 'Заявка отправлена';
lang_demand_no					= 'Повторно заявка отправлена не будет.';
lang_demand_sending				= 'Заявка отправляеться';
lang_demand_sending_t			= 'В данный момент заявка на дружбу отправляеться.';
lang_demand_s_ok				= ' получил уведомление и подтвердит, что Вы его друг.';
lang_take_ok					= 'Заявка принята.';
lang_take_no					= 'Заявка отклонена.';
lang_take_no2					= 'Вы отменили свою заявку и отписались.';
lang_dd2f_no					= 'Информация';
lang_dd2f22_no					= 'Этот пользователь есть у Вас в заявках.';
lang_22dd2f22_no				= 'Этот пользователь уже есть у Вас в друзьях.';
lang_no_user_fave				= 'Такого пользователя не существует.';
lang_yes_user_fave				= 'Этот пользователя уже есть у Вас в закладках.';
lang_del_fave					= 'Удалить из закладок';
lang_add_fave					= 'Добавить в закладки';
lang_fave_info					= 'Вы уверены, что хотите удалить этого пользователя из закладок?';
lang_fave_no_users				= '<div class="info_center">Вы можете добавлять сюда страницы интересных Вам людей.<br />Из этого раздела у Вас всегда будет быстрый доступ к ним.</div>';
lang_new_msg					= 'Новое сообщение';
lang_new_msg_send				= 'Отправить';
lang_msg_box					= 'Сообщения';
lang_msg_max_strlen				= 'Ваше сообщение слишком длинное.';
lang_msg_ok_title				= 'Сообщение отправлено.';
lang_msg_ok_text				= 'Ваше сообщение успешно отправлено.';
lang_msg_close					= 'Закрыть';
lang_photo_info_text			= 'Фотография удалена либо еще не загружена.';
lang_photo_info_delok			= '<br /><span class="online">Фотография удалена.</span>';
lang_albums_add_photo			= 'Описание фотографии';
lang_albums_set_cover			= 'Сделать обложкой альбома';
lang_albums_del_photo			= 'Удалить фотографию';
lang_albums_save_descr			= 'Сохранить описание';
lang_notes_no_title				= 'Введите заголовок заметки.';
lang_notes_no_text				= 'Введите текст заметки.';
lang_del_note					= 'Вы действительно хотите удалить эту заметку?';
lang_del_process				= 'Заметка удаляется...';
lang_notes_comm_max				= 'Ваш комментарий слишком длинный.';
lang_notes_setting_addphoto		= 'Настройки фотографии';
lang_notes_setting_addvdeio		= 'Настройки видеозаписи';
lang_notes_preview				= 'Как это будет после публикации';
lang_wysiwyg_title				= 'Настройки ссылки';
lang_unsubscribe				= 'Отписаться от обновлений';
lang_subscription				= 'Подписаться на обновления';
lang_subscription_box_title		= 'Подписки';
lang_max_albums					= 'Превышен лимит альбомов.';
lang_video_new					= 'Добавление нового видео';
lang_videos_no_url				= 'Введите ссылку на видеоролик.';
lang_videos_no_url				= 'Напишите название для видеоролика.';
lang_videos_sending				= 'В данный момент видео обрабатывается.';
lang_videos_del_text			= 'Вы действительно хотите удалить эту видеозапись?';
lang_videos_deletes				= 'Видеозапись удаляется...';
lang_videos_delok				= '<div class="videos_delok">Видеозапись удалена.</div>';
lang_videos_delok_2				= '<div class="online" style="margin-top:10px">Видеозапись удалена.</div>';
lang_video_edit					= 'Редактирование видеозаписи';
lang_video_info_text			= 'Видеозапись удалена либо еще не добавлена.';
lang_scroll_loading				= '<span id="scroll_loading"><center><img src="/templates/Default/images/loading_mini.gif" alt="" /></center><br /></span>';
lang_se_go						= 'Найти';
lang_bad_format					= 'Загружать разрешено только фотографии в формате JPG, PNG, GIF.';
lang_max_imgs					= 'Превышен лимит фотографий в одном альбоме.';
lang_max_size					= 'Превышен максимальный размер изображения.';
lang_news_prev					= 'Показать предыдущие новости &#8595;';
lang_editprof_text_1			= 'Укажите Вашу подругу';
lang_editprof_text_2			= 'Укажите Вашу невесту';
lang_editprof_text_3			= 'Укажите Вашу жену';
lang_editprof_text_4			= 'Укажите Вашу любимую';
lang_editprof_text_5			= 'Укажите Вашего партнёра';
lang_editprof_atext_1			= 'Укажите Вашего друга';
lang_editprof_atext_2			= 'Укажите Вашего жениха';
lang_editprof_atext_3			= 'Укажите Вашего мужа';
lang_editprof_atext_4			= 'Укажите Вашего любимого';
lang_editprof_atext_5			= 'Укажите Вашего партнёра';
lang_editprof_sptext_1			= 'Подруга:';
lang_editprof_sptext_2			= 'Невеста:';
lang_editprof_sptext_3			= 'Жена:';
lang_editprof_sptext_4			= 'Любимая:';
lang_editprof_sptext_5			= 'Партнёр:';
lang_editprof_asptext_1			= 'Друг:';
lang_editprof_asptext_2			= 'Жених:';
lang_editprof_asptext_3			= 'Муж:';
lang_editprof_asptext_4			= 'Любимый:';
lang_editprof_asptext_5			= 'Партнёр:';
lang_pr_no_title				= 'Ошибка доступа';
lang_pr_no_msg					= 'Вы не можете отправить сообщение данному пользователю, так как он ограничивает круг лиц, которые могут присылать ему сообщения.';
lang_support_text				= 'Вы действительно хотите удалить вопрос? Это действие нельзя будет отменить.';
lang_support_ltitle				= 'Лимит';
lang_support_ltext				= 'Следующий вопрос Вы сможете задать через час.';
lang_news_text					= 'Вы действительно хотите удалить новость? Это действие нельзя будет отменить.';
lang_gifts_title				= 'Выберите подарок';
lang_gifts_tnoubm				= 'У Вас недостаточно голосов для отправки этого подарка.';
lang_gifts_oktitle				= 'Подарок отправлен';
lang_gifts_oktext				= 'Ваш подарок был успешно отправлен.';
lang_groups_new					= 'Создание нового сообщества';
lang_groups_cretate				= 'Создать сообщество';
lang_audio_add					= 'Добавление новой песни';
lang_audio_err					= 'Формат не поддерживается либо ссылка является неправильной';
lang_audio_wall_attatch			= 'Выберите аудиозапись';
lang_wall_tell_tes				= 'Эта запись уже есть на стене';
lang_wall_text					= 'Что у Вас нового?';
lang_wall_del_ok				= '<div class="color777">Запись успешно удалена.</div>';
lang_wall_del_com_ok			= '<div class="online" style="margin-bottom:10px">Комментарий успешно удален.</div>';
lang_wall_all_lnk				= 'к предыдущим записям';
lang_wall_hide_comm				= 'Скрыть комментарии';
lang_wall_atttach_addsmile		= 'Выберите смайлик для отправки';
lang_wall_attatch_photos		= 'Выберите фотографию';
lang_wall_attatch_videos		= 'Выберите видеозапись';
lang_wall_no_atttach			= 'Не прикреплять';
lang_wall_max_smiles			= 'Максимально можно прикреплять 3 смайлика.';
lang_wall_liked_users			= 'Люди, которым это понравилось';
lang_wall_attach_smiles                        = '<img src="uploads/smiles/-6HZJBQFg7I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-9EE66SydIY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-jwhTxmYMIw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-LNBGOIOT78.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-NuM4p7rIc0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-QoZ1x18ibY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-TG-GEaxJJY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-tTRQCsb-8Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-x2rU3THJgU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/-ZASsa7q_4k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/02HwKImGWSk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/07cvq7ShZV0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0aTrTopOhRM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0FvdyOHnbgM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0hhKBO8jrGQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0IcaxEARAmw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0Ifcy1f25IA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0m978TSDwjg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0Mn71rgbpHM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0n18A1i7nPM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0Qevb4xoQkU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0R7VWb0uCmA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0sYv4hu_vQM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0WGUhiKh-Vg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0Y-4Mbwte-g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0y_6hJ7dvIk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/0zYHY6mG768.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/11ymBoEF6_A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/16e5rUXIW80.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1b6z_Q6zZ3I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1BAIeJ5Z278.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1eDmh7jpA0U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1IWDCKz9maE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1KkSVOl3zBs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1mAIQz5_Vc4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1RRodwOFCmo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1s1aqYdLwU0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1smz916EfZ8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1tCTQA00P5I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1VwATG9swG0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/1_0eQMMQTnQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/29me50QtW0E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2dfaH1CXDhc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2FwrkUi3L-E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2n3rFMbWYQU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2qehEEK16o0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2RmnkTZVa_U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2rVQk9RnvTY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2RYLJQqihg0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/2TvjiHt88rM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/31bgVrd5ILM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/36eaWGOb93c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/36mRQ5edljM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3amN-AOW4R4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3C8D5dAmawQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3FffS3shnFk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3fNwlY3SoE8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3G0_d4_AIxY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/3QrVxm-EixQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4-HUCcYn4Tg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/42iJMOt1in4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4dCxuTGXY9w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4DYzIlkubt0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4jmRaoIw4bI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4jSJiQiuNbo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4lG8m36xg3I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4OPth3WItSw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4oS2l2Q1GXY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4QXbn_XQPcQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4rA8Vd7-Qvg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4taZPgx5ZgY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4tCAc07c8_U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4wDWgVGvH1E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/4xfnnEzIWc8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/50U_dgwvzY0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/55R6PzwzPkk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/588cXavw3gc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5CttVrM5h64.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5EejVsRuaEU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5gUn2JcAM_8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5hTbHiQOcTs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5InRyVN7oP8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5jWRXyUJLwM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5KzQLH6-jP8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5PB4GYPKIlU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5VlhxY15WHI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5yjbxdmGs3w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5zV0g4qqOq0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/5_6fOAj021U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/62afIEStsFc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/62o2C6t3CQw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6GwGHvfe-6Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6h5_38uY9TU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6K10wJrAdJU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6lYOfEQCtW4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6nBN12JD2ko.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6NchzlcjQso.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6n_CA1tFqEk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6Sq0TK8kj6M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6sS9oXDdeSg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6tZA-jK7JR0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6Ve4ya9Sj9Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6WpjncFxTIY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/6YtCTJPN51g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/70mWz9daw-E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/73eZRklfnAw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/77SNv_KerQo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7e1tlFg9DXM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7EdfYAFUZRQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7fpT0odKtcQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7GmwijdwUqY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7H9AYhBZQx0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7hwilI1SUPY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7jW-X4l0eQE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7LJ4SP24C_Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7PYbt0n7OmI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7Ss_NBdyBik.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7SYtD-poAc8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7Vlzrq05T-0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/7y2_O4klAHo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/82BOEW7I6LM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8CPpnpTiH7g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8d_zHr8qxCY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8HhkprzJYXs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8iKG1N5Vm6Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8kXzh_uWi5E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8m_gBAOoROY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8Oan1BcQvjc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8OEev-nIVQM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/8SZkalJC3pU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/94oT4Iuisyw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9anWYjXiKrU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9APoFubNL9U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9ccouyIvAWE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9DpMmD_Hkd8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9f_X4j14bvA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9iIk9ILgCnw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9L2UkPYM_Q0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9mCKAWw-0hs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9n4UJ4MJT60.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9QtbAbb1Ev4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9T4A68UDoXk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/9XEloAESes0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/a25bWiIlIYU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AbKuqx9Z2GQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/acE_YgDLHYg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/acMKM74blQs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AeUkJr8pWeM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/af18R21cKfM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/aFjRa3VHKqg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/aFtok93fhL0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AFYT-mZFaH4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ag9x3Qr16AM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AkQNMBuIXS0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/akvBRRt8l-o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AlXTsuST6cU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/AoAHJljK65c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Arsgk8Q7HLM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ArYEy_0ZcMo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/aV0FNTmO3OI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ay6i_ZfFC9E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b0LcVof-nZY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b1WOjKbeB7I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b4ShBwvWTGw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b9AZisUXEZY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b9mDwKzBmUo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/baODDnBY5mM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BA_RBp35fbk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bbqEX3hhmCE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bbxCzz4DY-w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BcPl9scEzbQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bDUChTiav7g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BFeA1999G_4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bFNZSrLZj-4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bgAIjUj5-1Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bgfU32KPcdI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bHNfuH0rkOk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bjwkTTZfN9g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bJZSiK_5me0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bk5PPuXUNlc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BkMdyVKDdqc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bLS9dRJTQtc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BMBFWB62q0E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BnqMcET0bVM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BNuFqTKhCYM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bolFcsKVpto.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BriLPhAqEGw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BT5QToOP170.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Bv2PtRt3GTg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/bv8DpgBkTeg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BvPN6U93ZLk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/BWLopk8QZ2s.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/b_R4Rgoool8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/c3ALI7sd1l0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/c7yKBFTyOdo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/C8kqYRTt9h8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cAeXMu54N8o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Cb-qYYFgv5o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CCcFUwL5WCU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cE6hTrAvnhw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cefgc_GdMmo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CfqpabToh10.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cIskShiGCSE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ck5PJ1jlG1c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ckin6GlY5Ms.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ckIoHqZIZHA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CL5lbdPwVX0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CnKNVMS-vdw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cNR5qGAXdLQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/col25ZRkwGQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CTVYKfzh4vk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/CW1Ecmw6vzo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/cWrPMfJGHlk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/c_5KAG56zmM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/D-DOPmuvulo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/d-Q8YrUrSZg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/D2Df2Ys71xA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/d34wHI1NOb8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DDFDPaAUJQM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/dF27Vjxh95E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/dFpAj5a9S2Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DgWxRsNq0Ck.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DN5e1nX1Tp4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DrWZVyKjplk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DvFCpdpL-rA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DvrVf810gQc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DWECXBZT7Js.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DyT2WQNkmzk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/DZ-ARK3q9hE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Dz25LglIY94.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/dZCCDnNrU3k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/dZpSz5rJGiw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/e-YmIbD1hhs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/E-zjJjgpteU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/E05TfeF116s.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/E1ShveHwWQI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/E99b85Eo0cg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EE0pSyBSN_o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EImlldJB9ZI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Ek1y26Q02fM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ek81gTRXrrI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/eLDMVBU7i8A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EPxzzpfv-wk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EUDdbPdR25M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ewBd-kjt5vQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/eXXD7-Zoimw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EYH-Vw6AuSo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/eyU8mp7JBHg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/eZ6y_H_n0ts.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/EzXv8AKJgLc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/f-dqRpZDDZY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/F-dt_3ODHwo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/F3l0yS3F5bc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/F5Hgn0N-hGs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/f5ydLDPxt10.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fAF0IzrRZ9o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FajtIPsVFwY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/faPHsnBEhJU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FbYU8nK_NA8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FD7_H9h9tGQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fgVtWCPRLW4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fIi4IUxoStQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FjZrrLbVeBc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fKqhBf9uJtI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fmwsXrx8eyo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fnwe0UZXGG0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Fobv-u9b4rU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FoQfJYufkL0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Fr5ggYoI9nk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FrfoI17VsIg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/fSKrgDPW3vs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/FyW4d9WQnOA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/F_FbrpW7YBM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/G0FwF8pt7k4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/g0RGz9qmu6E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/g6vrgEkfJ5Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gEhWTP1LUwg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gfEtXP12iAQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gfWmIrFRcZ4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GI6ZfskORhE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GIc7XsOeih4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gIfWNjqxuLg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gj0n5VBkHrk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GjaDH8OU1B0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gkFV5B1KoX4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GKyn00iwp_4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GLW7ffHaYyU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GqAlK-0XDMY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GQbvj6MXzlg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GQOpDISRUwk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gsPMvJcOiQE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GsQtqNSpHVo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/gTj4-cVmjLc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Gv8MVLhv-_g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/GVcnnTHG-20.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/H-77ZFgW8Z4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/h0izzB9XJIs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/h1rIvsaa4gM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/h3rH19vICnY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/H7XLGGJeUN8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HBfAhuDn8Eg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hcnaySONRH4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hCvQJubwCqI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hfiFSB6_E5o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HGnG9WwPNQM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hgtNOhMCFMg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HGWmB8d30gQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hHVHs4jkdH8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HJnuBWwtUCo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Hk2xdTjJk6k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HktGhqbLLws.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HKX7daRjYtA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hMbAzuVxqhI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Hn5KBMSFkxI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hNNYx9V-9tA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HrUT44Q_OLg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hUqE3ju8erI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hwZ5LkBWfok.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hXv8UBbvcd0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HYF4JHYUaIo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/HYVEjkJo5RQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/hzZElw606cU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/h_2MRRRf5mA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/I873Vii4Mto.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Ic0Ff6ioN4Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/idkbOdWBw1A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IFmJShl7i0s.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IGEyITnaVdA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IGVXEVVS5Oo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iIUMP3Qob8w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ijsnsHe-wPo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IL1oQgOKUoA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iM3fLk-pFc0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iMGMIgnypw0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IpZEFk5y6os.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/IRJPm1RwAxQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ITc-4lCiASE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ItrtJrchTCs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iu8IKbiJRdY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ivQtsMiCVQQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ivsJPiH9-Ok.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iVxAQHriVaU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iwlsN7w3hnw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iWmLXpdQGRc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iXb8AB3BzBE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iXu1fA5TfFo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/iY4Kf07Tmlg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/I_AeUUOyEoU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/I_HBqnEUkNI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/j19Blzn_zGI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/J6VidH48ZSQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/J6_0vNR6f_E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jaDcgv4qcP4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jDrH4ZZnGz8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jdvCl5KDq-0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JG4RpHM24pY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JHTZ1af7COM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JhtZgLdWzFI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JjgKCuC5DOs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jJOJyQky4sk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jJU_nvblm6I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jJx3mBRFPjc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JkbWjchfH24.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JKfifwL0MkE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JkFzeqXMjjA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jKUs1qSw9h8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Jl2V61C-VB4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JL70wyIdyR0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JLVIsZf5M1Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jMhS5EBSln4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jmIBZweKh5g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JQHsGUsfsHk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JRTuyoy4b7k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Jr_h9linxqQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jttGiv7GPQY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jv0CxTsBYyw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/JWDxVxZFSno.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jwvuNu2M3Cw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jX7NFg-6ImM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/jYXYT8dJku8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/K4BU5gKvp1k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/K8J0LpSoouE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kAvQ6pDyokY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kc1O6VGBAKU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kcj6sLoY60g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kcZZzIXk3dM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kEPHRycr1-A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KFsiuLu7aVE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KfYfUF1CuiY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kLG1KwkRexs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kN6VKXeueEU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Kof83JO8Y1c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KPd_J0KicxU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KQnhhqeDD9A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kQOFbJB9G98.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KrCkj-1DrRA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/krs9U-dLA8k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/KsWAsDQ3lDI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ktvcU2OzXew.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/kxqJWVrQIbg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l16LcdzthlA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l3a0oX90aG0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/L49WMMsjpLU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l6L36-t2GS0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l7s030Y-d0Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l80hKk1G8gE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l9g3D8zQCMI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/L9W4rEraY_c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/l9_KdoLAa7M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LAdujSBPQcI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LAf-tEk15wI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LAgWUcIXF_Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LamAUctp1Ic.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LANPMoPRS8E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LAPmNEHG2ko.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lbjA6buurQQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lBZM5I7DXCY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lCRVjNa-icA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LeCwPmkEhDo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LEmxvjnnmgY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lh9zosj-CB4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lHMnxh9CYew.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LIEYuG8h0Oc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lJ0HhJyiPZE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LKv8gtgTgBE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LL8FuCEKfKg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lmMbdhw-dog.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lMtfyfJUz08.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LqKrpDs0f9I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LqXc_yxPDug.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/lrFlOmXlrSI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LUDE2ZxLcwo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LXAXuMt0DEA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/LySoAUYJIGM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/m03sNze7IY0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/M0Qe-QrjpsQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/M3Q_ssuneZA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/m4nvMFQcOdU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/m7EaKm6_StM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mAYaeERjk6c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Mc7kquxLOuk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ME-oLDgVs0E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mEFcLFEyzrI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/meN_SGN3TAw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MfE4XnyOTMQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MFQGSLWiWmM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Mi49H8QXy9Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MLpyEVQH0d4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mM3Fk-Br1ks.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mmFTu7ZlvvE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MmVS8rAWWEs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mMyGUm6rVHw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mR5u_zm05go.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MTuiBbBemjQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mvOjAYaH1Ac.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mXk-jfpVqEw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/MyPFmVZKHW0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/myrywmC87RA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/mZIaqa9Q2MI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/M_iWO3tpdnc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/N06hc5G2YZQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/n1pHpi1Xe-M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/N5M9ulpCFsU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/n6FYlT4SDmk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/n6grN_kaPIg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/nbCtmylE3OM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ncLvNST5E0w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ncwXLKYE9bY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/nDXZQrI4H-I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NfHOlgRWLO0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NL_I6DXazeo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NMA7EhMQ7q8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NOdxr1pWIes.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NOqlSfZd2vU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NqQL8x8DLY4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/nRcuMfCL9Ig.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NrPk8s1ypTU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NSvM-UES1mA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NsyqCWZu4nQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NUGNj5fCdBw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/NUV0kXRmkX8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Nx5IeujUn5I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Ny3vma0biw8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/O-WgILhQ-TM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/O2dge8fIbho.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o3T0yWoVJho.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o4GbJuBx8f0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o5dgmMpqB14.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o5zQ804CIX8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/O8GV__In8WU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/O9kLSrstfp4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/oAhasa_Obdw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/odyAo2GdCIw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OEwUJZiZ3L8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OG9chd9f40c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ohbc6oOYvd8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OJXv-oBXK0Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OLrI7edvono.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OmPIHi45EJ8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ONlIO_Y1n-8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OuFcvTBxCB8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Ox1u3OMH2II.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/OY8U7yxF1S0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/oYJ-QUN1RWk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/oYj0kkjR7ks.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/oz8GwRXkAII.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o_i26c1svTE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/o_L3gWTbS3E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/P0_10FeWL7M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/p2jy-JkbiIE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/p5OLylNCiRM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/p5tnM-ygwqY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/p7_-4p21SVg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/P9TT5JLYvaE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PaIeQecKDqg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pbB38Ow1qeQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pC1h-zgMF7U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PDD1El7h7cs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pdz6Rpis7m0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Pe2CsLj9Z2E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pfYWxB1CfmU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PHVj2E7KNVk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Pi1_fH2fBxY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PK8TICTyAJo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PkHKRKLozko.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pnxvscFytxQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Po8tGmlz4Zk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PQ0TCZQPE8U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pQYLS2itiME.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PR98ggtfXMk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PsuolEtkkgI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/psZjDry3NZ4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PTXdLY42200.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/PwKQOof87RU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pZRspNKHtbM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/pzx3XFQ4nSI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/P_-sb3ZLmJA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/P_DS0x7klbg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Q0B-ad0Tav4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Q0uGkhmxUSY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Q1DYyHu7SHI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/q3hc5KHDmJc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/q8EBmA2Uo-s.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/q8ggsgEdbhE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QDiBv0TPiGw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qFcjFThLSx4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qg62qCHMndM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QHdKwRpmGo4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qIoxQdVTLyw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qLn3jQtxFow.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QM-fGNM8qSM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QnWtDPZZkOU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qOvERPZS_kM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QpbUkmBftWk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QpYcE1MIrQE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/qQa26MdOoYg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Qrj8znpiXXA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QVgOmS07VJ8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QvJ5ELG3BAI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QVP0vcF87HI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QvXM58tESXg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/QzLzzAE4C0Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Q_u3ngnlGjs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/R5bMiUSZYpw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/r8Tz8ihIgdU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/RBSPjIKfgWg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rd0htF6ehRo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/RddrUTcgt1w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rirhpQryGVk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Rl0Bksjk6oo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/RL1hNI-F9vk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rNxbQIjwo90.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/RP_-yRxqPLk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rQJ64ayLySs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rrhzm8tlPIc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rSct3Hij7go.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rT_2JJJF1Sw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rw5FOTAv44Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rx_e6ZS4IGw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rYTdL254V5I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/rYTsKkZEOKc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/R_emvIhDZRI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/r_RvjSOfvi0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s-S89KLG5HQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s30mcasZRJo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s4ok2SIh_sM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s5XNtdNG2EE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s5zNd_cHweE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sAyGfC9rtio.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sc1Vg9CqX98.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SE2nqQ1l0Uk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SeAVTxPjGpU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sEzipAn996g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Sf7iz5an01A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SGvZEdDNbow.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sh0GTkble0g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/shhaCyJznzs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Si5M_h9wKA8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/siCvMCOJ1_o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sj2vj2oiZjQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SJnI-RyhwPA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sMJdkyS3aTo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SNgfx1KZ_1Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SoAO2Rud6uQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/soz3asBS6-k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sQqI2PWiDno.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/sRXBdmOzb4U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ssky819aTrc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/SSvYnbKCwEk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/StktHO3xUEQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/s_4bGDsf2jQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/T2p5OWf-lEQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/t6YiJ8NbRgY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/t9VOayuB9hM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TaQOq_IhH20.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ta_I_AkvRAA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tE-C3xqTKHU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TEhJJh472oE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Thumbs.db" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TifBNkZMR_M.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TIKKAEMIqMA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TJ59ka1rr4o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TJEv9G5TW8g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tKK-SXDGx7Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tMiqXT2uy74.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tNe_ms-DI7E.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TOBCC53vcr0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tpLVwJY7Cj4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tR-ekYSfs1I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TrdXoOr1xtU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TS93U2BsoOA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tt--P5R0wWk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tU4dxZthVfE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TuUuRNZbQhE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TVuP40znn04.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/TWRE0vv-86Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/txCd0Aik4W8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Tyg3N9wTBtk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/tZPm7ehAQ5k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/u3864xzW-kY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/U3yBNAQ5CI4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/U5vaSoMlfgA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/U9nW6Z1sYGA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/uAlBuzcrFpQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/uDQqTXo838g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UdvAOSLz27A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Ue5JSrSdm38.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/uEJTLTS2w50.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UF1aOtW_5UU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Uhhjc3HVFMs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/uHROhNOdUNQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UHXcSw_VIb0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UpT_mgUY62c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UsddR3ghM1k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/UU__pH3aPvY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/V-m5rIMrtT8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/v1ldODZjNvM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/v6M9q4Y4Mww.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/v7gCtEvajPo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vaNIXD8IxL8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vc_EJ5ks574.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Vgj75_hB2F4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VgqRJynIiwk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vH0rowOFFK8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VMmq95QcM_A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VN7Q-3G8ERA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vo6fNqV4ZsU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vOGJJryKUyY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VQ93if3pTfM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vQJ-pcQ48VE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VqkvflSMlX0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VQMD2m9AUBY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vQSsyN_R4Ng.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VRvbD01aCRE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vS79FlFqXiA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VwNKtWrynD4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VwoDsGDV1oA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VxugbkMhq1U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vXZCK1c9FCo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/vY7Q8wsLhaQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/VYweT1-i59I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/w00iiSTnhBw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/w0Sd5_tCVMI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/W0STiEoeX44.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/w25cos7mlWI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/w5bL54jTZBM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/W5IHYj2zE7w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/w9NjbnWkfJ4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Wa6q1BCC4GA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wag_oEW4PZ4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wDsMPPEkHKE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wejwmiqtCvo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wgSmNTcWJKQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Wk37qkVe0Oc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WlrMwKYIJuQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WoUSm_nvqCE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WOWqzJQ3Xio.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Wpm8AubAlRI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Wu6GMQi81Jo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WUA5vOtqsGc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wvfGZzDxOU4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WXGlO5Pihm8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/WXiMkdm6EgY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wYsUKu8eMtM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/wzmaJy41tr4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/X1-GG0QBf_c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/X1McKsOUT_U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/X6rqPJq4s5k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/X7XpQk4Pdfk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xD1dOLkHM64.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xeE8-mrIXF4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XfRpX1omAaw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XG-wwzL-zzU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XGYyk88nFFw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xIodU4lHn4w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XJjnlvUsDFo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XkVS9HjpA4U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XLYC85F1K04.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XOjnupbSh-Q.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Xp5D6GDz1Xo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xqHrLdYOQ7U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xqy-G6rH3G8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xRb0etB-odM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XSHDBmyzUEM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/XSHWVVe5Kh8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xX-ju267Tpc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/xZtl4KzaHQc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Y1kE3iYJHwE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Y2HDMx9fcng.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Y3bIXCHvzDY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/YAZEAzigPpU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ycs7bZZOpqc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ycwHB9RUH9g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yFHZuHfxwHM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yGSgmdsHR_c.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yhuSKmHbWd8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Yk4Z5UTSDJ0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yLEBztzHDuQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/YMfDCctYLCc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yN5hyBtE-xo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yNNo4_1GqtU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Yqlv0gcem8U.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/YRWT4t5S5qs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/yyNYkAeF64w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Yz5iE-WAv54.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Z0H3CQqHQ2w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Z0ZUkagAU-A.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Z1u3kNTEmhg.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Z3beAw9GO9k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/z5YnpVxp1qk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/z7B5wjjv7n8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zA1R6NXsqXY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zAb9hV9WxJ4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zAbGA7JmTxI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zc4WUAk1IIQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZCEo1vEEx_w.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZdVe0f_Y3QE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zEGollptMso.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZFh8t70KYYY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zGds5OY7Zxc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZhGtQIaKGvI.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZiMYMmMhJ-Y.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zkjLYbR7-u8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zNr-ZJBKxeM.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Zq0t82TfUhs.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZQHg1Yr_U2g.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZsjRd3qzS98.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Zt6WnZeGr68.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Zt7TmFLNWUQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZTUp3Fa_hDw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ztzN8gEVb3I.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zuFhkVtA7js.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZXsmDjuwUCo.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/ZXxN3JSLGM8.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/Zy6jN4xX5P0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/zz9_7CiO3gw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_-zUfh5stdw.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_6bj_5i1YkA.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_6C0kutm6FU.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_7xhLzE2t-k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_Ezhgz8SBL0.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_Fi8UQtTjcE.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_giopgxCCUk.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_lxkQL00jEc.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_MhCN-AgY4o.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_PQBp6nWR1k.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_pxQN2qLXZQ.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_Z7He61n1Ac.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_ZKufulWjL4.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" /><img src="uploads/smiles/_zYA6fPXWnY.jpg" class="wall_attach_smile" onClick="wall.attach_insert(\'smile\', this.src)" />';