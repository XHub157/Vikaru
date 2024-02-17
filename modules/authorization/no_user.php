<?php

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Считаем Наши Параметры
		
   $all_user = DB :: $dbh -> querySingle("SELECT count(*) FROM `user`;");
   $all_blog = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary`;");		
   $all_audio = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `section`=? AND `access`=?;", array('audio', 0));
   $all_feed = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed`;");
   
echo '
  <style>
#toolbar {
    background: rgba(63, 81, 181, 0.28);
}
  </style>
  
  <div id="wrapper_for_header">
<div id="wrapper_for_header_fix"><div class="index-cover">
     
    <div class="index-bl">
       
        <div class="fl_l" style="width: 60%;color: #FFF;font-size: 18px;margin-right: 10%;">
            <h1>Добро пожаловать на '.DOMAIN.' !</h1>
            Наш сайт - это универсальное средство для общения, поиска друзей и одноклассников, который ежедневно посещает всё больше и больше людей!
            
            <span style="color: #C5D4F5;padding-top: 15px;display: block;">
			Нас уже '.$all_user.' человек(а)<br>
            '.$all_feed.' Новостей<br>
			'.$all_audio.'  Аудиозаписей<br>
            '.$all_blog.' Записей<br></span>';
			
echo '<br><div class="blockqas" align="center">';

 $Q_avatar = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `date_aut`>? AND `hide`<?;", array(time()-60, time()));
		
// Выводим пользователей
	
    $q = DB :: $dbh -> query("SELECT * FROM `user` ORDER BY `rating` DESC LIMIT 4;");	

	
// Выводим пользователя

    while ($act = $q -> fetch()) {
    
    echo "<a href='/id$act[id]'>";
    
    echo ''.$avatar->micro($act['id'], 74,74).'';
    
    echo "</a> ";
}

echo '</div>';
        
		echo '</div>
        <div class="fl_r">
            <div class="index-block">
                <form method="post" action="/modules/authorization/aut">
                	<span class="_gray">Ваш E-mail на сайте:</span>
                	<div style="text-align: center;">
                	<input type="text" style="width: 100%;" class="loginFormIn" placeholder="E-mail..." name="aut_email" size="12">
                	</div>
                	<span class="_gray">Пароль:</span>
                	<div style="text-align: center;">
                	<input type="password" name="aut_password" placeholder="Пароль..." class="loginFormIn" style="width: 100%;" size="12" maxlength="32" value="">
                	</div>
                	<div style="text-align: center;"><input type="submit" name="save" value="Войти" class="loginFormButton"></div>
            	</form>
            </div>
            
            <div class="separator">
                <div class="separator_line"></div>
                <div class="separator_text">или</div>
            </div>
            
            <div class="index-block">
                <div class="_gray" style="    padding-bottom: 7px;
    border-bottom: 1px solid #BEBFD2;
    margin-bottom: 10px;    ">Впервые у нас? <b style="color: #292f33">Регистрируйтесь!</b></div>
                <form method="post" action="/modules/registration/enter"> 
                     <span class="_gray">Ваш E-mail на сайте:</span> <input type="text" name="email" placeholder="E-mail..." maxlength="32" class="loginFormIn" style="width: 100%;">
                    <span class="_gray" style="font-size: 0.9em;font-weight: bold;">Длина от 4 до 20 символов.</span>
                    <input type="submit" class="loginFormButton" value="Дальше"></form></div>
                
            </div>
        </div>
    </div>
 
</div>
</div></div></div></div></div></body></html>';

exit();

?>