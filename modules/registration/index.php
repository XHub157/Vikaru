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

    $title = 'Регистрация';

// Инклудим шапку

include_once (ROOT.'template/head.php');

	echo '
    <div class="info-block" style="text-align: center;">
    Для начала познакомимся с тобой!
    </div>
	<div class="block">
	<form method="post" action="/modules/registration/enter"> 
	Имя: <br />
	<input type="text" name="first_name" maxlength="32" value="" /> <br />	
	От 3 до 32 символов <br />
	</div>
	<div class="block">
	Фамилия: <br />
	<input type="text" name="last_name" maxlength="32" value="" /> <br />
	От 3 до 32 символов <br />
	</div>
	<div class="block">
	Пол: <br />
	<input type="radio" class="middle" name="sex" value="0" checked="checked" /> Мужской <br />   
	<input type="radio" class="middle" name="sex" value="1"/> Женский <br />
	</div><div class="block">
	E-mail: <br />
	<input type="text" name="email" maxlength="32" value="" /> <br />
	От 3 до 32 символов <br />
	</div>
	<div class="block">
	Пароль: <br />
	<input type="password" name="password" maxlength="16" value="" /> <br />
	От 3 до 16 символов (русские буквы нельзя) <br />
	</div><div class="block">
	<img src="/captcha.php" border="0" alt="."><br />
  Введите код с картинки<br/>
<input type=text name=kod maxlength=5><br/>
     </div><div class="hide">
	<input type="submit" name="save" value="Зарегистрироваться" /> <br />
	Нажимая кнопку "Зарегистрироваться" Вы автоматически соглашаетесь с Правилами сайта
	</div></form><div class="gen">
    Уже зарегистрированы? <br />
    <a href="/modules/authorization">Войди под своим именем!</a></div>
    ';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>