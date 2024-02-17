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

// Подключаем графическое ядро

    $image = new image();	

// Ищим фото в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данное фото существует
	
    if (!empty($act)) {
	
// Проверяем права

    if ($act['user'] == $user['id']) {	

// Аватар 256x256 	
	
    $image->load(SERVER.'/photo/256/photo'.$act['id'].'_'.$act['key'].'.'.$act['type'].'');
    $image->save(SERVER.'/avatar/256/'.$user['id'].'.png');

// Аватар 128x128

    $image->load(SERVER.'/photo/128/photo'.$act['id'].'_'.$act['key'].'.'.$act['type'].'');
    $image->save(SERVER.'/avatar/128/'.$user['id'].'.png');

// Аватар 64x64

    $image->load(SERVER.'/photo/64/photo'.$act['id'].'_'.$act['key'].'.'.$act['type'].'');
    $image->save(SERVER.'/avatar/64/'.$user['id'].'.png');	
	
// Аватар

    $image->load(SERVER.'/photo/photo'.$act['id'].'_'.$act['key'].'.'.$act['type'].'');
    $image->save(SERVER.'/avatar/'.$user['id'].'.png');	

// Обновляем информацию в базе

    DB :: $dbh -> query("UPDATE `user` SET `avatar`=? WHERE `id`=? LIMIT 1;", array(1, $user['id']));

// Уведомляем

    $system->redirect("Аватар успешно загружен", "/modules/settings/avatar");	

// Выводим ошибки
     
    } else { $system->redirect("Отказано в доступе", "/modules/settings/avatar"); }		
    } else { $system->redirect("Выбранное вами фото не существует", "/modules/settings/avatar"); } 

?>		