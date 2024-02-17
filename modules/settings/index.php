<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Только для зарегистрированых

    $profile->access(true);
	
// Выводим шапку

    $title = 'Настройки';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Содержимое страницы

    echo '

<div class="title" style="margin-bottom: 0px;"><center><b>Основные настройки:</b></center></div>
          <a href="/modules/settings/password" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Изменить пароль</div></a>
          <a href="/modules/edit/main" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Редактировать анкету</div></a>
          <a href="/modules/settings/avatar" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Настройки аватара</div></a>
			            <a href="/modules/settings/email" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Mail.png" class="m"> Настройки E-mail</div></a>
			            <a href="/modules/settings/mail" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Mail.png" class="m"> Настройки почты</div> </a>
		            <a href="/modules/settings/page" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Настройки отображения</div></a>
		            <a href="/modules/settings/journal" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Настройки журнала</div></a>
		            <a href="/modules/settings/guestbook" class="info-block-link">
          <div style="font-weight: _bold;"><img src="/icons/settings/Settings.png" class="m"> Настройки гостевой </div></a>
				<div class="title" style="margin-bottom: 0px;"><center><b>Настройки интерфейса:</b></center></div>
				<a href="/modules/settings/site_themes" class="info-block-link">
			       <div style="font-weight: _bold;"><img src="/icons/settings/Phone.png" class="m"> Дизайн сайта </div>
			    </a></div>';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	