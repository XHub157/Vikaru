<?php

$nextgame = array (

'api_key' => "5A70890AADAF616EA89D939BAAC1BA03F4BD6BBE",

'site_id' => "3538",

'cache_time' => "60",

'per_page' => "20",

'site_player' => "yes",

'api_url' => "http://api2.nextgame.ru/",

'subj_pm' => "Персональное сообщение с Игры",

'subj_invite' => "Приглашение в игру",

'message_invite' => "{gamer} <b>приглашает</b> Вас сыграть в [game_link]Игру[/game_link]",

);
require_once ENGINE_DIR.'/modules/api/api.functions.php';
require_once ENGINE_DIR.'/modules/functions.php';
$app_id=intval($_REQUEST['about_app']);

if($app_id!=0)$method="apps.getInfo&app_id={$app_id}";else $method="apps.getInfo";
$cache_time=(intval($nextgame['cache_time'])!=0)?intval($nextgame['cache_time'])*60:3600;
$get_apps=game_from_cache('nextgame_'.$app_id,$cache_time);
if(!$get_apps)
{
    $get_apps=get_games($method);
    game_to_cache('nextgame_'.$app_id,$get_apps);    
}

$apps=json_decode($get_apps,true);
if($is_logged AND $nextgame['site_player']=="yes")$player="&user_id=".$member_id['user_id']."&usr_nickname=".$member_id['name']."&t=".time();else $player="";
$name_game=array();
foreach($apps['data'] as $data)     
{
    $arrs[]=$data;
    $name_game[$data['id']]=$data['title'];
    
}
//navigate
$page=(intval($_GET['page']))?intval($_GET['page']):1;
$total = count($arrs);
$pnumber = (intval($nextgame['per_page'])<5)?20:intval($nextgame['per_page']);
$number = (int)($total/$pnumber);
if((float)($total/$pnumber) - $number != 0) $number++;
$start = (($page - 1)*$pnumber);
$end = $page*$pnumber;
if($end > $total) $end = $total;
if($this_page < 1 || $this_page > $allpage)$this_page = 1;  
$prev_page = $page-1;  
$next_page = $page+1;  
////
if($app_id==0){
$link="page/";
$pages_prev=($prev_page<1)?"":"<a href=\"".$config['http_home_url'].$link.$prev_page."\">{$prev_page}</a>";
$pages_curr="<b>{$page}</b>";
$pages_next=($next_page>$number)?"":"<a href=\"".$config['http_home_url'].$link.$next_page."\">{$next_page}</a>";
$tpl->set("{pages}",$pages_prev.$pages_curr.$pages_next);
$tpl->set_block( "'\\[next-link].*?\\[/next-link]'si", "" );    
$tpl->set_block( "'\\[prev-link].*?\\[/prev-link]'si", "" );
$tpl->compile('nav');
}
if($app_id)
{
    $tpl->load_template("games/screens.tpl");
    foreach($arrs[0]['screenshots'] as $screen)
    {
    $tpl->set("{screen-link}",$screen['url']);
    $tpl->compile('screenshot');
    }
    
$tpl->load_template("games/game_info.tpl");
}else 
{   
    //сделано для вписывания игры в сообщения не плодя кучи запросов к сервису
    $id_name=game_from_cache('nextgame_name',36000);
    if(!$id_name) game_to_cache('nextgame_name',json_encode($name_game));
    //сделано для вписывания игры в сообщения не плодя кучи запросов к сервису
    $tpl->load_template("games/game.tpl");
}

if(intval($_GET['ref_id'])!=0)$ref_id="&ref_id=".intval($_GET['ref_id']);else $ref_id="";

for($i = $start; $i < $end; $i++)
{
     
    $tpl->set("{title}",convert_unicode($arrs[$i]['title']));
    $tpl->set("{description}",convert_unicode($arrs[$i]['description']));
    
    $tpl->set("{logo}",$arrs[$i]['logo']);
    $tpl->set("{about-link}",$config['http_home_url'].$arrs[$i]['id']);

    $play_link=$nextgame['api_url']."iframe/js/?app_id={$data['id']}&site_id=".intval($nextgame['site_id']).$player.$ref_id;
    $sig=($player)?"&sig=".gensign($play_link):"";
    $tpl->set("{screenshots}",$tpl->result['screenshot']);
    if($app_id)
    {
        $tpl->set("{play-link}","<script type='text/javascript' src='{$play_link}{$sig}'></script>");
            
    } else $tpl->set("{play-link}", $arrs[$i]['id']);
    $tpl->compile('game_list');
}
if(!$app_id)$tpl->result['content']="<div class=\"wgame\">".$tpl->result['game_list']."</div><div class=\"clear\"></div>".$tpl->result['nav'];else $tpl->result['content']=$tpl->result['game_list'];
	$tpl->clear();
?>

