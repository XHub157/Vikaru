<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Системное ядро~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class system {

// Функция обработки переменных
	
public static function check($msg) {

    if (is_array($msg)) {
    foreach($msg as $key => $val) {
    $msg[$key] = self::check($val);
    } 
    } else {
    $msg = htmlspecialchars($msg);
    $search = array(' ','|', '\'', '$', '\\', '^', '%', '`', "\0", "\x00", "\x1A", chr(226) . chr(128) . chr(174));
    $replace = array(' ','&#124;', '&#39;', '&#36;', '&#92;', '&#94;', '&#37;', '&#96;', '', '', '', '');
    $msg = str_replace($search, $replace, $msg);
    $msg = stripslashes(trim($msg));
    } return $msg;
	
} 

// Функция обработки количества символов в utf-8

public static function utf_strlen($msg) {

    if (function_exists('mb_strlen')) return mb_strlen($msg, 'utf-8');
    if (function_exists('iconv_strlen')) return iconv_strlen($msg, 'utf-8');
    if (function_exists('utf8_decode')) return strlen(utf8_decode($msg));
    return strlen(utf_to_win($msg));
	
} 

// Функция правильного окончания

public static function ending($num, $a, $b, $c){

    $q = ($num % 10 == 1 && $num % 100 != 11? 0: ($num % 10 >= 2 && $num % 10 <= 4 && ($num % 100 < 10 or $num % 100 >= 20)? 1: 2));
    return ($q == 0? $a: ($q == 1? $b: ($q == 2? $c: null)));
	
}

// Вывод ошибок, текста

public static function show($message) {

    echo '<div class="errors">' . $message . '</div>';

} 

// Вывод ошибок с редиректом

public static function redirect($message, $redirect) {

    $_SESSION['show'] = $message;
    header("Location: $redirect");
    exit();

} 

// Функция определения времени

public static function system_time($time = NULL) {

    global $user;
    if ($time == NULL) $time = time();
    if (isset($user)) $time = $time + $user['timezone']*60*60;
    $timep="".date("d.m.Y", $time)."";
    $time_p[0]=date("d.m.Y", $time);
    $time_p[1]=date("H:i", $time);
    if ($time_p[0]==date("d.m.Y"))$timep=date("H:i:s", $time);
    if (isset($user)) {
    if ($time_p[0]==date("d.m.Y", time()+$user['timezone']*60*60))$timep=date("H:i:s", $time);
    if ($time_p[0]==date("d.m.Y", time()-60*60*(24-$user['timezone'])))$timep="Вчера в $time_p[1]";
    } else {
    if ($time_p[0]==date("d.m.Y"))$timep=date("H:i:s", $time);
    if ($time_p[0]==date("d.m.Y", time()-60*60*24))$timep="Вчера в $time_p[1]";
    }
    $timep=str_replace("Jan","Янв",$timep);
    $timep=str_replace("Feb","Фев",$timep);
    $timep=str_replace("Mar","Марта",$timep);
    $timep=str_replace("May","Мая",$timep);
    $timep=str_replace("Apr","Апр",$timep);
    $timep=str_replace("Jun","Июня",$timep);
    $timep=str_replace("Jul","Июля",$timep);
    $timep=str_replace("Aug","Авг",$timep);
    $timep=str_replace("Sep","Сент",$timep);
    $timep=str_replace("Oct","Окт",$timep);
    $timep=str_replace("Nov","Ноября",$timep);
    $timep=str_replace("Dec","Дек",$timep);
    return $timep;

}

// Функция вывода формы	
	
public static function form($url, $message, $submit, $title, $num, $class, $placeholder, $sid, $name) {

     ?>
             <script type="text/javascript">
				function ctrlEnter(event, formElem) {
					if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
					{
                        var hd = document.createElement('input');
                        hd.type = 'hidden';
                        hd.name = 'enter';
                        hd.value = 1;
                        formElem.appendChild(hd);
                        formElem.submit();
					}
				}
			</script>
<?

    echo '
    <div class="form">		
    <form action="'.$url.'" method="post">	
    <span style="float:right;"><a href ="/modules/smiles.php"><img src="/icons/smile_light.png" class="mq_s"></a></span> '.$title.': ('.$num.' символов)<br />
    <textarea name="'.$name.'" placeholder="'.$placeholder.'" class="'.$class.'" onkeypress="ctrlEnter(event, this.form);" />'.$message.'</textarea><br/>
    <input type="hidden" name="sid" value="'.$sid.'" />
    <input type="submit" value="'.$submit.'" class="submit" /> (Ctrl + Enter) </form>
    </div>
    ';

}

// Браузер пользователя

public static function ua() {

    return self::check($_SERVER['HTTP_USER_AGENT']);

} 

// ip пользователя

public static function ip() {

    return self::check($_SERVER['HTTP_X_FORWARDED_FOR']);

} 

// Функция оповещения в журнал

public static function journal($user, $message, $url, $last_user, $section) {
    
    $email = new email();
    $profile = new profile();
    $us = DB :: $dbh -> queryFetch("SELECT `login`, `date_aut`, `activation`, `email`, `notice`, `notice_journal` FROM `user` WHERE `id`=? LIMIT 1;", array($last_user));
    if (isset($us) && $us['notice_journal'] == 0) {
    if ($us['activation'] == 2 && $us['notice'] == 1 && $us['date_aut'] < time()-300) {
    if ($section == 0) {
    $int = 'новое уведомление';
    } else if ($section == 1) {
    $int = 'новый комментарий';
    } else if ($section == 2) {
    $int = 'новый комментарий к новости';
    } else if ($section == 3) {
    $int = 'новый комментарий к теме';
    } else if ($section == 4) {
    $int = 'новый комментарий к дневнику';
    } else if ($section == 5) {
    $int = 'новый комментарий к фото';
    } else if ($section == 6) {
    $int = 'новый комментарий к файлу';
    } 
    $email->send($us['email'], 'Новое уведомление на '.DOMAIN.'', '
    Здравствуйте, <span style="font-weight: bold;">'.$us['login'].'</span>, у Вас '.$int.' <br />
    <a href="http://'.DOMAIN.''.$url.'">'.$message.'</a> <br />
    '.$us['login'].' / '.$profile->us($user).' / '.self::system_time(time()).'');
    }
    DB :: $dbh -> query("INSERT INTO `journal` (`user`, `message`, `url`, `profile`, `read`, `time`, `section`) VALUES (?, ?, ?, ?, ?, ?, ?);", array($user, $message, $url, $last_user, 1, time(), $section));	
    }

} 

// Функция оповещения в ленту

public static function feed($user, $message, $url, $section) {

    return DB :: $dbh -> query("INSERT INTO `feed` (`user`, `message`, `url`, `time`, `section`) VALUES (?, ?, ?, ?, ?);", array($user, $message, $url, time(), $section));	

}

// Функция онлайн

public function online($time){

    if($time < 60){ return $time.' '.self::ending($time, 'секунду', 'секунды', 'секунд'); 
    } else if($time < 3600){ return round($time/60).' '.self::ending(round($time/60), 'минуту', 'минуты', 'минут');
    } else { return round($time/3600).' '.self::ending(round($time/3600), 'час', 'часа', 'часов');
    }
	
} 

} 

?>