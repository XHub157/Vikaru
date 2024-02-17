<?php
if(!defined('MOZG'))
	die('Hacking attempt!');

function get_games($method)
{
    global $nextgame;
//    echo $nextgame['api_url'].'api/?method='.$method.'&site_id='.$nextgame['site_id'].'&format=json';
    return file_get_contents($nextgame['api_url'].'api/?method='.$method.'&site_id='.$nextgame['site_id'].'&format=json');
}

function gensig($params)
{
    global $nextgame;
    $sign="";
    $param=explode("&",$params);
    ksort($param);
    foreach($param as $sparam=>$value)
    {
        $sign .=$value;
        
    }
    $sign .=$nextgame['api_key'];
    
    return md5($sign);
}
function checksign($url)
{
    global $nextgame;
    $param = $url;
    $sig = $param['sig'];//берем подпись
    $line = '';
    unset($param['sig']);//удаляем из параметров подпись
    ksort($param);//сортируем параметры по алфавиту
    foreach ($param as $name => $value) {
        $line .= $name . '=' . $value;//добавляем строку пары ключ=значение
    }
    //echo md5($line.$api_key);
    return ($sig == md5($line.$nextgame['api_key']))?true:false;
    
}
function gensign($url) {
    global $nextgame;
    $param = '';
    parse_str(parse_url($url, PHP_URL_QUERY), $param);//получаем параметры из ссылки в массив
    $line = '';
    ksort($param);
    foreach ($param as $name => $value) {
        $line .= $name . '=' . $value;//добавляем строку пары ключ=значение
    }
    return md5($line.$nextgame['api_key']);//добавляем в ссылку подпись
    
}
//Одолжено с API THX!
function game_to_cache ($fname, $vars)
{
			$cache_dir=ENGINE_DIR."/cache/";
			$filename = $fname.".tmp";
			$f = @fopen($cache_dir.$filename, "w+");
			@chmod('0777', $cache_dir.$filename);
			@fwrite($f, $vars);
			@fclose($f);
			return $vars;
}
			
			
		/**
		 * Загрузка Игры из кеша
		 */
function game_from_cache ($game, $timeout=300)
{
            $cache_dir=ENGINE_DIR."/cache/games/";
            
			$filename = $fgame.".tmp";
			if (!file_exists($cache_dir.$filename)) return false;
			if ((filemtime($cache_dir.$filename)) < (time()-$timeout)) return false;
			return file_get_contents($this->cache_dir.$filename);
}

?>