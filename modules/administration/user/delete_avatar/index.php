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

// Выводим шапку

    $title = 'Аватар';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {
	
// Ищим пользователя в базе	

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {
    if ($data['avatar'] == 1) {
	
    @unlink(SERVER."/avatar/$data[id].png");
    @unlink(SERVER."/avatar/256/$data[id].png");	
    @unlink(SERVER."/avatar/128/$data[id].png");
    @unlink(SERVER."/avatar/64/$data[id].png");	

// Обновляем информацию в базе

    DB :: $dbh -> query("UPDATE `user` SET `avatar`=? WHERE `id`=? LIMIT 1;", array(0, $data['id']));
	
// Уведомляем

    $system->redirect("Аватар успешно удалён", "/modules/administration/user/".$data['id']."");	
	
    } else { $system->show("Выбранный вами пользователь не загружал аватар"); }
    } else { $system->show("Выбранный вами пользователь не существует"); }
    } else { $system->redirect("Отказано в доступе", "/"); }

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>