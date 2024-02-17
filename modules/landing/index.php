<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */

// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Выводим меню

    echo '
    
	<div class="info-block"><div class="info-block">
    Приветствуем вас на <b>'.DOMAIN.'</b> - новом мобильном портале!</div>


<div class="info-block">
	<img style="margin-right:10px;" class="left" alt="" src="/icons/landing/upload-24.png">
	<div class="overfl_hid">
	    <b>Скачивайте файлы</b><br> 
	    <span class="grey">Бесплатная музыка, картинки, видео, программы, игры, книги.</span>
	</div>
</div>

<div class="info-block">
	<img src="/icons/landing/chat-4-24.png" alt="" class="left" style="margin-right:10px;">
	<div class="overfl_hid">
	    <b>Общайтесь с друзьями</b><br>
	    <span class="grey">Используйте бесплатный чат, знакомства и форум,общения в почте .</span>
	</div>
</div>

<div class="info-block">
	<img src="/icons/landing/24-hour-service-24.png" alt="" class="left" style="margin-right:10px;">
	<div class="overfl_hid">
	    <b>Используйте сервисы</b><br>
	    <span class="grey">Добавляйте бесплатные объявления, ведите блоги.</span>
	</div>
</div>



<div class="info-block">
	<img src="/icons/landing/newspaper-10-24.png" alt="" class="left" style="margin-right:10px;">
	<div class="overfl_hid">
	    <b>Интересные новости</b><br>
	    <span class="grey">Читайте интересные записи и добавляйте свои .</span>
	</div>
</div>

<div class="info-block">
	<img class="left" alt="" style="margin-right:10px;" src="/icons/landing/slot-machine-24.png">
	<div class="overfl_hid">
	    <b>Отдыхайте</b><br>
	    <span class="grey">Играйте в бесплатные онлайн-игры.</span>
	</div>
</div>


<div class="info-block">
	<img src="/icons/landing/star-24.png" alt="" class="left" style="margin-right:10px;">
	<div class="overfl_hid">
	    <b>Разделы сайта</b><br>
	    <span class="grey">Фото,почта,форум,музыка,лента и так дальше ,все доступно после регистрации.</span>
	</div>
</div>


</div>
	
    ';


// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>