<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Только для зарегистрированых

    $profile->access(true);	
	
// Подключаем текстовое ядро
	
    $text = new text();	

// Выводим шапку

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим подарок в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `gifts_user` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный подарок существует
	
    if (!empty($act)) {	

// Проверяем права

    if ($act['profile'] == $user['id'] || $user['access'] > 0 && $user['access'] < 3) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `gifts_user` WHERE `id`=? LIMIT 1;", array($act['id']));

// Обновляем данные

    DB :: $dbh -> query("UPDATE `gifts` SET `send`=`send`-1 WHERE `id`=?", array($act['gift']));		

// Уведомляем

    $system->redirect("Подарок успешно удалён", "/modules/gifts/user/".$act['profile']."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/gifts/user/gift/$act[id]");
    }

// Обработка данных подарка

    if ($act['access'] == 2) {
    $login = 'Неизвестный';
    $section = 'Анонимный';
    $message = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$text->check($act['message']).'' : 'Скрыто').'';
    } else if ($act['access'] == 1) {
    $login = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$profile->user($act['user']).'' : 'Неизвестный').'';
    $section = 'Личный';
    $message = ''.($act['profile'] == $user['id'] || $act['user'] == $user['id'] ? ''.$text->check($act['message']).'' : 'Скрыто').'';
    } else {
    $login = ''.$profile->user($act['user']).'';
    $section = 'Публичный';
    $message = ''.$text->check($act['message']).'';
    }	
	
// Выводим блок

    echo '
    <div class="block">
    <img src="http://'.SERVER_DOMAIN.'/gifts/128/'.$act['gift'].'.png"/> <br />
    Подарил: '.$login.' <br />
    Дата: '.$system->system_time($act['time']).' <br />
    Тип подарка: '.$section.' <br />
    Сообщение: '.$message.'
    </div>
    <div class="block">
    Вы действительно хотите удалить подарок?
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки
 		
    } else { $system->show("Отказано в доступе"); } 
    } else { $system->show("Выбранный вами подарок не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>