<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Только для гостей

    $profile->access(true);	

// Выводим шапку

    $title = 'Выход';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Только если был послан $_POST зпрос

    if (isset($_POST['output'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	
	
// Запись в базу о выходе из сайта

    DB :: $dbh -> query("UPDATE `user` SET  `aut`=?, `ip`=?, `ua`=? WHERE `id`=? LIMIT 1;", array(time()-60, $system->ip(), $system->ua(), $user['id'])); 	
	
// Удаляем COOKIE

    setcookie("sid", "", 0, "/", "".DOMAIN."");

// Запись данных в сессии

    $_SESSION['user'] = $user['id'];  
	
// Уведомляем

    $system->redirect("Мы ждем вас снова", "/modules/authorization");
	
    } else { $system->show("Ошибка! Замечена подозрительная активность, повторите действие!"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/startpage/");  
    }
	
// Выводим блок
	
    echo '<div class="block">
    <form method="post">
    Вы нас уже покидаете?</div>
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="output" value="Выйти" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	