<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки счётчиков~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class count {

// Подсчет пользователей

function users() {

    if (@filemtime(SERVER."/system/users.dat") < time()-60) {
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `date_reg`>?;", array(time()-86400 * 7));
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user`;");
    if (empty($totalnew)) {
    $count = '<span class="counts">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    } 
    file_put_contents(SERVER."/system/users.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/users.dat");
	
}

// Подсчет количества онлайн

function online() {

    if (@filemtime(SERVER."/system/dating/online.dat") < time()-10) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `date_aut`>? AND `hide`<?;", array(time()-60, time()));
    $count = ''.$total.'';
    file_put_contents(SERVER."/system/dating/online.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/online.dat");
	
}

// Подсчет оповещений в ленте

function feed() {

    if (@filemtime(SERVER."/system/feed.dat") < time()-60) {
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed` WHERE `time`>?;", array(time()-86400 * 7));
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed`;");
    if (empty($totalnew)) {
    $count = '<span class="counts">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    } 
    file_put_contents(SERVER."/system/feed.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/feed.dat");
	
}

// Подсчет комментариев в беседке

function arbor() {

    if (@filemtime(SERVER."/system/arbor.dat") < time()-60) {
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `time`>?;", array(time()-86400 * 1));
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor`;");
    if (empty($totalnew)) {
    $count = '<span class="counts">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    } 
    file_put_contents(SERVER."/system/arbor.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/arbor.dat");
	
} 

// Подсчет новостей

function news() {

    if (@filemtime(SERVER."/system/news.dat") < time()-600) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `news`;");
    if ($total > 0) {
    $system = new system();
    $text = new text();
    $news = DB :: $dbh -> query("SELECT * FROM `news` ORDER BY `time` DESC LIMIT 1;")-> fetch();  
    $count = '<span class="color">
    '.$system->system_time($news['time']).' <br />
    '.$text->number($news['description'], 250).'
    </span>';
    } else {
    $count = '<span class="counts">'.$total.'</span>';
    } 
    file_put_contents(SERVER."/system/news.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/news.dat");
	
} 

// Подсчет количества дневников

function diary() {

    if (@filemtime(SERVER."/system/diary.dat") < time()-60) {
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `time`>?;", array(time()-86400 * 1));
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary`;");
    if (empty($totalnew)) {
    $count = '<span class="counts">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    } 
    file_put_contents(SERVER."/system/diary.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/diary.dat");
	
} 

// Подсчет количества статей

function reference() {

    if (@filemtime(SERVER."/system/reference.dat") < time()-600) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference`;");
    $count = '<span class="counts">'.$total.'</span>';
    file_put_contents(SERVER."/system/reference.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/reference.dat");
	
} 

// Подсчет форума

function forum() {

    if (@filemtime(SERVER."/system/forum.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic`;");
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments`;");
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    file_put_contents(SERVER."/system/forum.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/forum.dat");
	
} 
	
// Подсчет количества файлов

function shared_zone() {

    if (@filemtime(SERVER."/system/shared_zone.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `shared_zone`>?;", array(0));
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `shared_zone`>? AND `shared_time`>?;", array(0, time()-86400 * 7));
    if (empty($totalnew)) {
    $count = '<span class="counts">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    }
    file_put_contents(SERVER."/system/shared_zone.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/shared_zone.dat");
	
} 	
	
// Подсчет количества музыки

function audio() {

    if (@filemtime(SERVER."/system/audio.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `section`=? AND `access`=?;", array('audio', 0));
    $count = '<span class="counts">'.$total.'</span>';
    file_put_contents(SERVER."/system/audio.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/audio.dat");
	
}	
	
} 

?>