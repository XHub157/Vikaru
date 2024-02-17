var login = {
  login:function(){
	$.post('/index.php?go=llogin&act=login',function(body){
		$('body').append('<div id="newbox_miniature"><div class="miniature_box"><div class="miniature_pos" style="width: 330px;padding:15px;height: 240px"><br>'+body+'</div></div></div>');
	});
  },
  reg:function(){
	$.post('/index.php?go=rreg&act=reg',function(body){
		$('body').append('<div id="newbox_miniature"><div class="miniature_box"><div class="miniature_pos" style="width: 370px;padding:15px;height: 300px"><br>'+body+'</div></div></div>');
	});
  }
}