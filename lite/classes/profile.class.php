<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки пользовательских данных~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class profile {

// Проверка прав пользователей и гостей

public static function access($access) {

    global $user;
    $system = new system();
    if (isset($user) && $access == false) {
    $system->redirect("Вы уже авторизованы", "/modules/startpage/");}
    if (!isset($user) && $access == true) {
    $system->redirect("Извините, эта функция доступна только зарегистрированным пользователям. <br /> Регистрация быстрая и бесплатная.", "/modules/registration");}
   
}

// Проверка существует ли такой пользователь

function check($id){

    global $user;
    $system = new system();
    if (DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `id`=?;", array($id))!=1) {
    if (isset($user)) {
    $system->redirect("Выбранный вами пользователь не существует", "/id".$user['id']."");
    } else {
    $system->redirect("Выбранный вами пользователь не существует", "/");
    } exit();}

} 

// Информация из базы

function data($id){

    $profile = DB :: $dbh -> queryFetch("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    return $profile;

}

// Проверяем пользователя на online

public static function online($id) {

    if (DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `id`=? AND `aut`>?;", array($id, time()-60))){
    return true;
    } else {
    return false;
    }

}

// Вывод иконки пользователя

public static function icons($id) {

    $profile = DB :: $dbh -> queryFetch("SELECT `id`, `sex`, `access` FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    if (isset($profile)) {
    $online = self::online($id);
    if ($profile['access'] == 0 ) {
    $ico = ($profile['sex'] == 0) ? '<img src="/icons/user/' . ($online?'man_on':'man_off') . '.gif" alt="м"/>' : '<img src="/icons/user/' . ($online?'woman_on':'woman_off') . '.gif" alt="ж"/>';    
    }
    if ($profile['access'] == 1) {    
    $ico = ($profile['sex'] == 0) ? '<img src="/icons/user/' . ($online?'creator_man_on':'creator_man_off') . '.gif" alt="м"/>' : '<img src="/icons/user/' . ($online?'creator_woman_on':'creator_woman_off') . '.gif" alt="ж"/>';
    }
    if ($profile['access'] == 2) {  
    $ico = ($profile['sex']==0) ? '<img src="/icons/user/' . ($online?'admin_man_on':'admin_man_off') . '.gif" alt="м"/>' : '<img src="/icons/user/' . ($online?'admin_woman_on':'admin_woman_off') . '.gif" alt="ж"/>';    
    }
    if ($profile['access'] >= 3) {  
    $ico = ($profile['sex'] == 0) ? '<img src="/icons/user/' . ($online?'mod_man_on':'mod_man_off') . '.gif" alt="м"/>' : '<img src="/icons/user/' . ($online?'mod_woman_on':'mod_woman_off') . '.gif" alt="ж"/>';
    }
    if ($profile['id'] == 0) {  
    $ico = '<img src="/icons/user/who_on.gif" alt="м"/>';
    }
    return $ico;
    }

}

// Вывод Логина

public static function login($id){

    $profile = DB :: $dbh -> queryFetch("SELECT `id`, `first_name`, `last_name` FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    if (isset($profile)) {
    return ''.($profile['id'] == 0 ? '<span style="font-weight: 700;color:#79358c">Система</span>' : '<a class="pi_author" href="/id'.$profile['id'].'"><span style="color:#79358c">'.$profile['first_name'].' '.$profile['last_name'].'</span></a>').'';
    } 

}

// День рождения

public static function birthday($id){

    $profile = DB :: $dbh -> queryFetch("SELECT `day`, `month`, `vip`, `rating` FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    if (isset($profile)) {
    $bronze = ($profile['rating'] > 999 && $profile['rating'] < 3000) ? '<img class="middle" src="/icons/user/bronze.png">' : ''; 
    $silver = ($profile['rating'] > 2999 && $profile['rating'] < 5000) ? '<img class="middle" src="/icons/user/silver.png">' : '';
    $gold = ($profile['rating'] > 4999) ? '<img class="middle" src="/icons/user/gold.png">' : '';
    return '
    '.($profile['day'] == date("d") && $profile['month'] == date("m") ? '<img class="middle" src="/icons/birthday.png">' : '').'
    '.($profile['vip'] > time() ? '<img class="middle" src="/icons/user/vip.png">' : '').' '.$bronze.$silver.$gold.'';
    } 

}

// Выводим полный профиль

public static function user($id){

    return ''.self::icons($id).' '.self::login($id).' '.self::birthday($id).'';

}

// Вывод Логина без ссылки

public static function us($id){

    $profile = DB :: $dbh -> queryFetch("SELECT `id`, `first_name`, `last_name` FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    if (isset($profile)) {
    return ''.($profile['id'] == 0 ? '<span style="font-weight: 700;color:#79358c">Система</span>' : '<span style="font-weight: 700;color:#79358c">'.$profile['first_name'].' '.$profile['last_name'].'</span>').'';
    } 

}

}

?>