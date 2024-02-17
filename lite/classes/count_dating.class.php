<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки счётчиков~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class count_dating {

// Подсчет пользователей

function users() {

    if (@filemtime(SERVER."/system/dating/users.dat") < time()-60) {
    $totalnew = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `date_reg`>?;", array(time()-86400 * 7));
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user`;");
    if (empty($totalnew)) {
    $count = '<span class="count">'.$total.'</span>';
    } else {
    $count = '<span class="left_count">'.$total.'</span><span class="right_count">'.$totalnew.'</span>';
    } 
    file_put_contents(SERVER."/system/dating/users.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/users.dat");
	
}

// Подсчет количества парней

function male() {

    if (@filemtime(SERVER."/system/dating/male.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `sex`=?;", array(0));
    $count = '<span class="count">'.$total.'</span>';
    file_put_contents(SERVER."/system/dating/male.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/male.dat");
	
} 

// Подсчет количества девушек

function women() {

    if (@filemtime(SERVER."/system/dating/women.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `sex`=?;", array(1));
    $count = '<span class="count">'.$total.'</span>';
    file_put_contents(SERVER."/system/dating/women.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/women.dat");
	
}

// Подсчет количества онлайн

function online() {

    if (@filemtime(SERVER."/system/dating/online.dat") < time()-10) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `aut`>? AND `hide`=?;", array(time()-60, 0));
    $count = ''.$total.'';
    file_put_contents(SERVER."/system/dating/online.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/online.dat");
	
}

// Подсчет количества администрации

function administration() {

    if (@filemtime(SERVER."/system/dating/administration.dat") < time()-600) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `access`>?;", array(0));
    $count = '<span class="count">'.$total.'</span>';
    file_put_contents(SERVER."/system/dating/administration.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/dating/administration.dat");
	
}
	
} 

?>