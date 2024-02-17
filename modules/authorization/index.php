<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Только для гостей

    $profile->access(false);	

// Выводим шапку

    $title = 'Авторизация';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Содержимое страницы

    echo '	
    <div class="block">
	Добро пожаловать на портал <font color="red"><b>'.DOMAIN.'.</b></font> <br />
	Здесь вы можете найти новых друзей, поучаствовать в конкурсах, хранить свои личные файлы и многое другое. <br />
	Потрать одну минуту и пройди <a href="/modules/registration/">Регистрацию</a>.
	</div>
	<div class="block">
    <form method="post" action="/modules/authorization/aut">
	E-mail: <br />
	<input name="aut_email" type="text" maxlength="32" value="" /> <br /> 
	Пароль: <br />
	<input name="aut_password" type="password" maxlength="32" value="" /> <br />
	<input name="cookie" class="middle" type="checkbox" value="1" /> Запомнить меня <br />
	<input type="submit" name="save" value="Войти" />
	</form></div>
	<a class="touch" href="/modules/registration/">Регистрация</a> 
	<a class="touch" href="/modules/authorization/password/">Забыли пароль?</a>	
	';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>