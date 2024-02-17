$(function(){		
	var ajaxLoad = $('#ajax-load');
	
	$('pre br').remove();

	if(history.pushState){
		/* ajaxLoad.on('click', 'a.ajax', function(){
			url = $(this).attr('href');
			var stateObj = { foo: "bar" };		   
			history.pushState(stateObj, $('#title').text(), url);
			ajaxLoad.html('<div id="loader"></div>');
			$.ajax({
				url: url, 
				type: 'POST',
				success: function(data){
					var a = $(data).find('#ajax-load')
					ajaxLoad.html(a);
					document.title = $('#title').text();
					//$('meta[name="keywords"]').attr('content', $('#keywords').text());
					//$('meta[name="description"]').attr('content', $('#description').text());	
				}
			}); 
			return false;
		});*/
		
		ajaxLoad.on('click', 'a.ajax', function(){
			url = $(this).attr('href');
			var stateObj = { foo: "bar" };		   
			history.pushState(stateObj, $('#title').text(), url);
			
			ajaxLoad.load(url + ' #ajax-load', function(){
				document.title=$('#title').text();	 
			});
			
			return false;
		});
	}
	
	// Прокрутка страницы вверх
    $("#back-top").hide();
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('#back-top').fadeIn();
		} else {
			$('#back-top').fadeOut();
		}
	});

	$('#back-top a').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	// Звуковое уведомление
	function loadNotice(){
		$.ajax({
			url: '/system/ajax/notice/count.close.php', 
			type: 'POST',
			success: function(data){
				if(data != 0){
					$('#count-notice').text(data).css({'display':'block'});
					$('audio#media-notice').attr('autoplay', 'autoplay');
				}else{
					$('#notice').css({'display':'none'});
				}
			}
		}); 
	}
	
	setInterval(loadNotice, 2000);
	
	
	/**
	* Считает введенные символы в поле ввода
	*/
	ajaxLoad.on('focus', '.length', function(){
		$(this).keyup(function(){
			var count = $(this).val().length;
			var maxlength = $(this).attr('maxlength');
			if((maxlength && maxlength < 50000) || count > 40000) 
				$(this).next().html(count+'/'+maxlength+' симв.');
			else 
				$(this).next().html(count+' симв.');
			return false;
		});
	});
	
	ajaxLoad.on('blur', '.length', function(){
		$('#count_length').html('');
	});
	
	ajaxLoad.on('click', '#tag-toggle', function(){
		$('#hide-tag').slideToggle();
	});
});// End jQuery

/**
* Вставка bb кодов
*/
function tag(text1, text2) {
	if ((document.selection)) {
		document.message.msg.focus();
		document.message.document.selection.createRange().text = text1 + document.message.document.selection.createRange().text + text2;
	} else if (document.forms['message'].elements['msg'].selectionStart != undefined) {
		var element = document.forms['message'].elements['msg'];
		var str = element.value;
		var start = element.selectionStart;
		var length = element.selectionEnd - element.selectionStart;
		element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
		document.forms['message'].elements['msg'].focus();
	} else
		document.message.msg.value += text1 + text2;
	document.forms['message'].elements['msg'].focus();
}