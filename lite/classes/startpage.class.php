<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */

// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки статистической информации на стартовой странице~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class startpage {

// Выводим блок новых дневников

function diary() {

    if (@filemtime(SERVER."/system/startpage_diary.dat") < time()-600) {
    $count = NULL;
    $block = NULL;
    $div = NULL;
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `access`=? LIMIT 5;", array(0));
    if ($total > 0) {
    $profile = new profile();
    $block = '
    <div class="hide">
    Новые дневники: 
    </div>';
    $q = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `access`=? ORDER BY `time` DESC  LIMIT 3;", array(0));
    while ($act = $q -> fetch()) {
    $count .=  '
    <a class="touch" href="/modules/diary/'.$act['id'].'">
    <img class="middle" src="/icons/diary.png">
    '.$act['name'].' 
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </a>';
    }
    }
    file_put_contents(SERVER."/system/startpage_diary.dat", $block.$count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/startpage_diary.dat");
	
}	
	
// Выводим блок новых тем в форуме

function forum() {

    if (@filemtime(SERVER."/system/startpage_forum.dat") < time()-600) {
    $count = NULL;
    $block = NULL;
    $div = NULL;
    $total = DB :: $dbh -> querySingle("SELECT count(*) FROM `forum_topic` LIMIT 5;");	
    if ($total > 0) {
    $profile = new profile();
    $block = '
    <div class="hide">
    Новые темы форума: 
    </div>';
    $q = DB :: $dbh -> query("SELECT * FROM `forum_topic` ORDER BY `time` DESC LIMIT 3;");	
    while ($act = $q -> fetch()) {
    $count .=  '
    <a class="touch" href="/modules/forum/topic/'.$act['id'].'">
    <img class="middle" src="/icons/topic.png">
    '.$act['name'].' 
    '.($act['censored'] == 1 ? '<img class="middle" src="/icons/censored.png">' : '').'
    </a>';
    }
    }
    file_put_contents(SERVER."/system/startpage_forum.dat", $block.$count, LOCK_EX);
    } 
    return file_get_contents(SERVER."/system/startpage_forum.dat");
	
}	

} 

?>