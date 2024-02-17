<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки счётчиков пользователей~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class count_user {

// Подсчет комментариев в гостевой

function guestbook($id) {

    if (@filemtime(SERVER."/users/guestbook/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `profile`=?;", array($id));	
    $count = ''.$total.'';
    file_put_contents(SERVER."/users/guestbook/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/guestbook/user_$id.dat");
	
} 

// Подсчет дневников

function diary($id) {
	
    if (@filemtime(SERVER."/users/diary/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `user`=?;", array($id));	
    $count = ''.$total.'';
    file_put_contents(SERVER."/users/diary/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/diary/user_$id.dat");
	
} 
	
// Подсчет закладок

function bookmarks($id) {

    if (@filemtime(SERVER."/users/bookmarks/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `user`=?;", array($id));	
    $count = ''.$total.'';
    file_put_contents(SERVER."/users/bookmarks/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/bookmarks/user_$id.dat");
	
} 

// Подсчет фотоальбомов/фото

function photo($id) {

    if (@filemtime(SERVER."/users/photo/user_$id.dat") < time()-60) {
    $album = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `user`=?;", array($id));	
    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=?;", array($id));
    $count = ''.$photo.'';
    file_put_contents(SERVER."/users/photo/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/photo/user_$id.dat");
	
} 

// Подсчет Папок/файлов

function files($id) {

    if (@filemtime(SERVER."/users/files/user_$id.dat") < time()-60) {
    $dirs = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `user`=?;", array($id));	
    $files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `user`=?;", array($id));
    $count = ''.$files.'';
    file_put_contents(SERVER."/users/files/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/files/user_$id.dat");
	
} 

// Подсчет пользователей которые добавили в закладки пользователя

function bookmarks_user($id) {

    if (@filemtime(SERVER."/users/bookmarks_user/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=?;", array(1, $id));	
    $count = '<span class="count">'.$total.'</span>';
    file_put_contents(SERVER."/users/bookmarks_user/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/bookmarks_user/user_$id.dat");
	
}

// Подсчет добавлений в друзья

function friends($id) {
	
    if (@filemtime(SERVER."/users/friends/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `profile`=?;", array($id));	
    $count = ''.$total.'';
    file_put_contents(SERVER."/users/friends/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/friends/user_$id.dat");
	
}

// Подсчет добавлений в ленту

function feed($id) {
	
    if (@filemtime(SERVER."/users/feed/user_$id.dat") < time()-60) {
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `profile`=?;", array($id));	
    $count = ''.$total.'';
    file_put_contents(SERVER."/users/feed/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/feed/user_$id.dat");
	
} 	

} 

?>