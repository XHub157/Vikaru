<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $text = new text();

// Выводим шапку

    $title = 'Справка';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим статью в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `reference` WHERE `id`=? LIMIT 1;", array($id));
    $reference = $queryguest -> fetch();

// Только если данная статья существует
	
    if (!empty($reference)) {

// Проверяем просмотры
	
    if (isset($user)) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference_view` WHERE `user`=? AND `reference`=?  LIMIT 1;", array($user['id'], $reference['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `reference_view` (`user`, `time`, `reference`) VALUES (?, ?, ?);", array($user['id'], time(), $reference['id']));
    DB :: $dbh -> query("UPDATE `reference` SET `view`=`view`+1 WHERE `id`=?", array($reference['id']));
    } else {
    DB :: $dbh -> query("UPDATE `reference_view` SET `time`=? WHERE `user`=? AND `reference`=? LIMIT 1;", array(time(), $user['id'], $reference['id']));
    }}	
	
// Подчёт рейтинга

    $rating_plus = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference_rating` WHERE `reference`=? AND `section`=?;", array($reference['id'], 1));
    $rating_minus = DB :: $dbh -> querySingle("SELECT count(*) FROM `reference_rating` WHERE `reference`=? AND `section`=?;", array($reference['id'], 0));	

// Выводим блок

    echo '
    <div class="hide">
    <div class="zcoreq">
    <center><span style="font-weight: bold;">'.$reference['name'].'</span></center>
	</div>
    <br />
    '.$text->check($reference['description']).'
    </div>
   
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    <div class="hide">
    [<a href="/modules/reference/edit_reference/'.$reference['id'].'">Редактировать</a>]
    [<a href="/modules/reference/delete_reference/'.$reference['id'].'">Удалить</a>]
    </div>
    ' : '').'	
    ';
	
// Выводим ошибки

    } else { $system->show("Выбранная вами статья не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>