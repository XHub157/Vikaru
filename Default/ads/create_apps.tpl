<link media="screen" href="{theme}/style/ads_create.css" type="text/css" rel="stylesheet" />  
<div class="err_yellow no_display" id="result"></div> 

<div class="page_bg border_radius_5 margin_top_10"> 

<div class="texta" style="width:180px">Тематика:</div> 

<select id="category" class="inpst" style="width:250px"> 

<option value="18">Приложение</option>

</select> 

<div class="mgclr"></div> 

<div class="texta" style="width:180px">Название:</div> 

<input type="text" id="title" class="inpst ads_inp" style="width:239px" /> 

<div class="mgclr"></div>

<div class="texta" style="width:180px">Описание:</div> 

<input type="text" class="adsinpst" id="description" style="width:239px;padding-left:5px;padding-bottom:41px;" />
<div class="" style="color:#777;font-size:8px;margin-left: 185px">Всего 60 символов</div>
<div class="mgclr"></div>  

<div class="texta" style="width:180px">Ссылка на игру:</div> 

<input type="text" id="link_site" class="inpst ads_inp" style="width:239px" /> 

<div class="mgclr"></div> 

<div class="texta" style="width:180px">Ссылка на изображение:</div> 

<input type="text" id="link_photos" class="inpst ads_inp" style="width:239px" /> 

<div class="mgclr"></div> 

<div class="clear margin_top_10" style="margin-top:15px"></div> 

<div class="texta" style="width:180px">Сколько переходов:</div> 

<input type="text" class="inpst ads_inp" id="transitions" style="width:40px" onKeyUp="ads.update()" maxlength="6" /><br /> 

<div style="margin-left:185px"><small>К оплате <b><span id="cost_num">0</span> голос.</b></small></div> 

<div class="mgclr"></div> 

<div class="mgclr"></div>

<div class="texta" style="width:180px">&nbsp;</div> 

<div class="button_blue fl_r"><button onClick="ads.send()" id="sending">Заказать</button></div> 

<div class="mgclr"></div> 

</div>