<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки текста~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class text {

// Обработка

public static function check($msg) {

        $smiles = new Smiles('core/chache/smiles'); 

    return stripslashes(self::br(self::code($smiles->replace($msg))));
	
}

// Обработка пробелов
	
public static function br($msg, $br='<br />') {

    $msg = str_replace("\n", $br, $msg);
    $msg = str_replace("\r\n", $br, $msg);
    return $msg;
	
}

// Обработка количества символов
	
function number($msg, $len) {

    $wordsafe = FALSE; $dots = true; $slen = strlen($msg);
    if ($slen <= $len) {
    return $msg;}
    if ($wordsafe) {
    $end = $len;
    while (($msg[--$len] != ' ') && ($len > 0)) {};
    if ($len == 0) {
    $len = $end;}}
    if ((ord($msg[$len]) < 0x80) || (ord($msg[$len]) >= 0xC0)) {
    return substr($msg, 0, $len) . ($dots ? ' ...' : '');}
    while (--$len >= 0 && ord($msg[$len]) >= 0x80 && ord($msg[$len]) < 0xC0) {};
    return substr($msg, 0, $len) . ($dots ? ' ...' : '');
	
}

// BB-коды	
	
public static function code($msg) {

    $msg = preg_replace('~\[url=([a-z]+://[^ \r\n\t`\'"]+)\](.*?)\[/url\]~iu', '<a target="_blank" href="\1">\2</a>', $msg);
    $msg = preg_replace('~(^|\s)([a-z]+://([^ \r\n\t`\'"]+))(\s|$)~iu', '<a target="_blank" href="\2">\3</a>\4', $msg);
    $msg = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $msg);
    $msg = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $msg);
    $msg = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $msg);
    $msg = preg_replace('#\[small\](.*?)\[/small\]#si', '<small>\1</small>', $msg);
    $msg = preg_replace('#\[big\](.*?)\[/big\]#si', '<big>\1</big>', $msg);
    $msg = preg_replace('#\[fon=(.*?)\](.*?)\[/fon\]#si', '<div style="background-color:\1">\2</div>', $msg);
    $msg = preg_replace('#\[color=(.*?)\](.*?)\[/color\]#si', '<span style="color:\1">\2</span>', $msg);
    $msg = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class="quote">\1</div>', $msg);
    return $msg;
	
} 

}

?>