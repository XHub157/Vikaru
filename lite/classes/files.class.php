<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки файлов~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class files {

// Скриншот к файлам

function view($id, $name, $key, $type) {

    if ($type == 'jpeg' || $type == 'jpg' || $type == 'png' || $type == 'gif') {
    return '
    <div class="block" style="text-align: center;">
    <img class="middle photo" src="http://'.SERVER_DOMAIN.'/screen/file'.$id.'_'.$key.'.png">
    </div>
    ';
    } else if ($type == '3gp' || $type == '3gp' || $type == 'mp4' || $type == 'avi') {
    return '
    <div class="block" style="text-align: center;">
    <div id="player"><noscript>Для работы плеера JavaScript должен быть включен</noscript></div>
    <script type="text/javascript" src="/modules/files/player/swfobject.js"></script>
    <script type="text/javascript">
    var flashInstalled = false;
    if (typeof(navigator.plugins)!="undefined" && typeof(navigator.plugins["Shockwave Flash"])=="object") {
    flashInstalled = true; 
    } else if (typeof  window.ActiveXObject !=  "undefined") { try {
    if (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")) {
    flashInstalled = true; } } catch(e) {};};
    if (!flashInstalled) { document.getElementById("player").innerHTML="Для просмотра необходим Flash Player"; } else {
    var flashvars = { file:"http://'.SERVER_DOMAIN.'/video/file'.$id.'_'.$key.'.'.$type.'", streamer:"start", provider:"http" };
    var params = { allowfullscreen:"true", allowscriptaccess:"always", wmode:"opaque" };
    var attributes = { id:"player", name:"player" };
    swfobject.embedSWF("/modules/files/player/video.swf","player", 320, 240, "9.0.115", "false", flashvars, params, attributes);
    }
    </script>
    </div>
    ';
    } else if ($type == 'mp3' || $type == 'aac' || $type == 'wav' || $type == 'wma' || $type == 'amr') {
    return ''.(is_file(SERVER.'/screen/file'.$id.'_'.$key.'.png') ? '
    <div class="block" style="text-align: center;">
    <img class="middle photo" src="http://'.SERVER_DOMAIN.'/screen/file'.$id.'_'.$key.'.png">
    </div>
    ' : '').'
    <div class="block" style="text-align: center;">
    <div id="player"><noscript>Для работы плеера JavaScript должен быть включен</noscript></div>
    <script type="text/javascript" src="/modules/files/player/swfobject.js"></script><script type="text/javascript">
    var flashInstalled = false;
    if (typeof(navigator.plugins)!="undefined" && typeof(navigator.plugins["Shockwave Flash"])=="object") {
    flashInstalled = true; 
    } else if (typeof  window.ActiveXObject !=  "undefined") { try {
    if (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")) {
    flashInstalled = true; } } catch(e) {};};
    if (!flashInstalled) { document.getElementById("player").innerHTML="Для просмотра необходим Flash Player"; } else {
    var flashvars = { soundFile:"http://'.SERVER_DOMAIN.'/audio/file'.$id.'_'.$key.'.'.$type.'", titles:"'.$name.'", artists:"'.$name.'" };
    var params = { quality:"high", wmode:"transparent" };
    var attributes = { id:"player", name:"player" };
    swfobject.embedSWF("/modules/files/player/audio.swf","player", 320, 20, "9.0.115", "false", flashvars, params, attributes);
    }</script></div>';
    } else {
    return '
    '.(is_file(SERVER.'/screen/file'.$id.'_'.$key.'.png') ? '
    <div class="block" style="text-align: center;">
    <img class="middle photo" src="http://'.SERVER_DOMAIN.'/screen/file'.$id.'_'.$key.'.png">
    </div>
    ' : '').'';	
    }
		
}

// Тип файла

function type($type) {

    if ($type == 'jpeg' || $type == 'jpg' || $type == 'png' || $type == 'gif') {
    return '<img class="middle" src="/icons/type/image.png">';
    } else if ($type == '3gp' || $type == '3gp' || $type == 'mp4' || $type == 'avi') {
    return '<img class="middle" src="/icons/type/video.png">';
    } else if ($type == 'mp3' || $type == 'aac' || $type == 'wav' || $type == 'wma' || $type == 'amr') {
    return '<img class="middle" src="/icons/type/audio.png">';
    } else if ($type == 'zip' || $type == 'rar' || $type == 'gzip' || $type == 'bzip2') {
    return '<img class="middle" src="/icons/type/archive.png">';
    } else if ($type == 'doc' || $type == 'docx') {
    return '<img class="middle" src="/icons/type/doc.png">';
    } else if ($type == 'xls' || $type == 'xlsx') {
    return '<img class="middle" src="/icons/type/xls.png">';
    } else if ($type == 'jar' || $type == 'jad') {
    return '<img class="middle" src="/icons/type/java.png">';
    } else if ($type == 'sis' || $type == 'sisx') {
    return '<img class="middle" src="/icons/type/sis.png">';
    } else if ($type == 'apk') {
    return '<img class="middle" src="/icons/type/apk.png">';
    } else if ($type == 'thm') {
    return '<img class="middle" src="/icons/type/thm.png">';
    } else if ($type == 'txt') {
    return '<img class="middle" src="/icons/type/txt.png">';
    } else if ($type == 'php' || $type == 'html' || $type == 'xhtml' || $type == 'pl' || $type == 'asp' || $type == 'aspx' || $type == 'js' || $type == 'sql') {
    return '<img class="middle" src="/icons/type/web_document.png">';
    } else {
    return '<img class="middle" src="/icons/type/unknown.png">';
    }

} 

// Функция скачивания файла

function download($path, $id, $key, $type) {
	
// Проверяем тип файла

    if ($type == 'png' || $type == 'jpg' || $type == 'gif' || $type == 'jpeg') {
    if (file_exists($path)) {
    header('Location: http://'.SERVER_DOMAIN.'/image/file'.$id.'_'.$key.'.'.$type.'');
    } else { die('Файл не найден'); }	
    } else {

// Начало

    if (headers_sent()) 
    die('Headers Sent'); 
	
// Требуется для некоторых браузеров
   
    if(ini_get('zlib.output_compression')) 
    ini_set('zlib.output_compression', 'Off'); 

// Файл существует 

    if (file_exists($path)) { 
     
// Информация о файле / Получаем тип

    $fsize = filesize($path); 
    $path_parts = pathinfo($path); 
    $ext = strtolower($path_parts["extension"]); 
     
// Определяем тип содержимого

    switch ($ext) { 
    case "pdf": $ctype="application/pdf"; break; 
    case "exe": $ctype="application/octet-stream"; break; 
    case "zip": $ctype="application/zip"; break; 
    case "doc": $ctype="application/msword"; break; 
    case "xls": $ctype="application/vnd.ms-excel"; break; 
    case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
    default: $ctype="application/force-download"; 
    } 

// Отправка файла	
	
    header("Pragma: public"); 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); 
    header("Content-Type: $ctype"); 
    header("Content-Disposition: attachment; filename=\"".basename($path)."\";" ); 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Length: ".$fsize); 
    ob_clean(); 
    flush(); 
    readfile($path); 
	
// Выводим ошибку	
	
    } else { die('Файл не найден'); }
    }	
}

}

?>