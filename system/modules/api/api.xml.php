<?
/*
=====================================================
 DataLife Engine
-----------------------------------------------------
 http://dle.in.ua/
-----------------------------------------------------
 Copyright (c) 2011 Dimka
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: nexgame/nextgame.xml.php
-----------------------------------------------------
 Назначение: Взаимодействие сервером NextGame
=====================================================
*/
@session_start();
@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', ROOT_DIR . '/system' );
define ( 'NEXT_DIR', dirname ( __FILE__ ) );
require_once NEXT_DIR.'/api.functions.php';
require_once ENGINE_DIR.'/modules/functions.php';
include_once (ENGINE_DIR . '/classes/mysql.php');
include_once (ENGINE_DIR . '/data/dbconfig.php');
include_once (ENGINE_DIR . '/data/config.php');
if(!checksign($_GET)) die('SIG Error'); //check sig
switch(strtolower($_GET['method']))
{
// Показываем профиль..
case 'getprofile': 
$id=$_GET['uid'];

    $db->query("SELECT * FROM ".USERPREFIX."_users where user_id in($id)");
    $resp="<profiles>";
    while($row=$db->get_row())
        {
    $row['fullname']=iconv($config['charset'],"UTF-8",$row['fullname']);
    $row['land']=iconv($config['charset'],"UTF-8",$row['land']);
    
$resp .=<<<XML
<user>
        <uid>{$row['user_id']}</uid>
        <first_name>{$row['fullname']}</first_name>
        <last_name></last_name>
        <nickname>{$row['name']}</nickname>
        <birthday></birthday>
        <sex></sex>
        <avatar_url>{$config['http_home_url']}uploads/fotos/{$row['foto']}</avatar_url>
        <country>{$row['land']}</country>
        <city></city>
</user>
XML;
}
$resp.="</profiles>";
break;
// Отправляем ПМ
case "sendmessage": 
require_once ENGINE_DIR . '/classes/parse.class.php';
$parse = new ParseFilter( );
$parse->safe_mode = true;

$uid=intval($_GET['uid']);
$sender_id=intval($_GET['sender_id']);
$message=convert_unicode($_GET['message'],$config['charset']);

$message=$parse->BB_Parse( $parse->process($message ), false );

$subj=strip_tags($db->safesql($nextgame['subj_pm'],$config['charset']));
if($_GET['type']=='user'){
$user=$db->super_query("SELECT user_id,name from ".USERPREFIX."_users where user_id='{$sender_id}'");

}else
{
    $user['name']="NexGame Aplication";
}
$time = time() + ($config['date_adjust'] * 60);
$db->query( "INSERT INTO " . USERPREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) values ('$subj', '$message', '{$uid}', '{$user['name']}', '$time', 'no', 'inbox')" );
$db->query("UPDATE " . USERPREFIX . "_users set pm_all=pm_all+1, pm_unread=pm_unread+1  where user_id='{$uid}'");
$resp="<msg><uid>{$uid}</uid><delivered>1</delivered></msg>";
break;
case 'sendinvite': /// Инвайт отправим.

    if(empty($_GET['uid']) OR intval($_GET['sender_id'])==0 OR intval($_GET['app_id'])==0) die();
    $sender_id=intval($_GET['sender_id']);
    $game_id=intval($_GET['app_id']);
    $subj=(empty($nextgame['subj_invite']))?"Empty!":strip_tags($nextgame['subj_invite']);
    //$subj=strip_tags(trim(convert_unicode($nextgame['subj_invite'],$config['charset'])));
    $time = time() + ($config['date_adjust'] * 60);
    $game_link=($config['allow_alt_url'] == "yes")?$config['http_home_url']."game/".$game_id."/?ref_id=".$sender_id:$config['http_home_url']."?do=game&about_app=".$game_id."&ref_id=".$sender_id;
    $row=$db->super_query("SELECT name,user_id FROM ".USERPREFIX."_users where user_id='{$sender_id}'");
    if(!$row['user_id']) die("No Such User"); //Фтопку отправлять от анонимов
    $message=str_replace('&quot;', '"',$nextgame['message_invite']);
    $message=str_replace("[game_link]","<a href=\"{$game_link}\">",$message);
    $message=str_replace("[/game_link]","</a>",$message);
    $message=str_replace("{gamer}",$row['name'],$message);
    $message=$db->safesql($message);
    $users=explode(",",$_GET['uid']);
    $query=array();
    $users_id=array();
    foreach($users as $user)
        {
            $users_id[]=intval($user);
            $query[]="('$subj','$message','$user','{$row['name']}','$time','no','inbox')";
            $resp.="<user>$user</user>";
        }
    $invite_recipients=implode(",",$query);
    $invite_recipients_id=implode(",",$users_id);
    $db->query( "INSERT INTO " . USERPREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) values $invite_recipients;");  
     $db->query( "UPDATE " . USERPREFIX . "_users set pm_all=pm_all+1, pm_unread=pm_unread+1  where user_id in('{$invite_recipients_id}')" );
    $resp="<invite>{$resp}</invite>";
break;
default:
    $resp="<error>true</error>";
}
@header('Content-type: text/xml');
echo<<<XML
<?xml version="1.0" encoding="UTF-8"?>
$resp
XML;

?>