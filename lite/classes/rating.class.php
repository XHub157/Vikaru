<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки рейтинга пользователей~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class rating {

// Функция подсчёта рейтинга

function user($id) {

    if (@filemtime(SERVER."/users/rating/user_$id.dat") < time()-43200) {
	
// Рейтинг пользователя	
	
    $user_rating = DB :: $dbh -> queryFetch("SELECT `rating`, `vip` FROM `user` WHERE `id`=? LIMIT 1;", array($id));	
	
// За первые сутки	
	
    $diary = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $topic = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
	
    $arbor = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $news_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $diary_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $forum_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $guestbook_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $photo_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
    $files_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_comments` WHERE `user`=? AND `time`>?;", array($id, time()-86400 * 1));
	
// За вторые сутки
  
    $yesterday_diary = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_topic = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));
    $yesterday_files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));
    $yesterday_photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));
    
    $yesterday_arbor = DB :: $dbh -> querySingle("SELECT count(*) FROM `arbor` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_news_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `news_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));
    $yesterday_diary_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_forum_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_guestbook_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `guestbook_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_photo_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	
    $yesterday_files_comments = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_comments` WHERE `user`=? AND `time`>? AND `time`<?;", array($id, time()-86400 * 3, time()-86400 * 2));	

// Подсчёт	
	
    $yesterday = $yesterday_diary + $yesterday_topic + $yesterday_files + $yesterday_photo + $yesterday_arbor + $yesterday_news_comments + $yesterday_diary_comments + $yesterday_forum_comments + $yesterday_guestbook_comments + $yesterday_photo_comments + $yesterday_files_comments;
    $today = $diary + $topic + $files + $photo + $arbor + $news_comments + $diary_comments + $forum_comments + $guestbook_comments + $photo_comments + $files_comments;

// Формула начисления	
	
    $sum = $today - $yesterday;
    $rand = rand(1,5);
    $rating = ($user_rating['vip'] > time()) ? $sum + rand(20,90) : $sum * $rand;
	
// Результат	
	
    if ($rating > 0) {
    DB :: $dbh -> query("UPDATE `user` SET `rating`=`rating`+".$rating." WHERE `id`=? LIMIT 1", array($id));
    $count = '<span style="color: #009933;">+'.$rating / 100 .'</span>';
    } else if ($rating < 0) {
    $sum_rating = ($user_rating['rating'] - abs($rating) < 0) ? $user_rating['rating'] : abs($rating);
    DB :: $dbh -> query("UPDATE `user` SET `rating`=`rating`-".$sum_rating." WHERE `id`=? LIMIT 1", array($id));
    $count = ''.($sum_rating == 0 ? '<span style="color: #D3D3D3;">0.00</span>' : '<span style="color: #FF0000;">-'.$sum_rating / 100 .'</span>').'';
    } else {
    $count = '<span style="color: #D3D3D3;">'.$rating.'.00</span>';
    }
	
    file_put_contents(SERVER."/users/rating/user_$id.dat", $count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/users/rating/user_$id.dat");
    } 
	
} 

?>	