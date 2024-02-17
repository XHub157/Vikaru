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

// Подключаем обраотку аватаров
	
    $avatar = new avatar();	
	
// Подключаем графическое ядро
	
    $photo = new photo();	
	
// Выводим шапку

    $title = 'Аватар';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Только если отправлен POST запрос	
	
    if (isset($_POST['delete'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Проверяем наличие аватара на сервере

    if ($user['avatar'] == 1) {		

    @unlink(SERVER."/avatar/$user[id].png");
    @unlink(SERVER."/avatar/256/$user[id].png");	
    @unlink(SERVER."/avatar/128/$user[id].png");
    @unlink(SERVER."/avatar/64/$user[id].png");

// Обновляем информацию в базе

    DB :: $dbh -> query("UPDATE `user` SET `avatar`=? WHERE `id`=? LIMIT 1;", array(0, $user['id']));

// Уведомляем

    $system->redirect("Аватар успешно удалён", "/modules/settings/avatar");	
	
// Выводим ошибки	
	
    } else { $system->show("Аватар не загружен"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    }	

// Только если отправлен POST запрос	
	
    if (isset($_POST['upload'])) {		
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Только если файл выбран

    if (!empty($_FILES['file']['name'])) {	

// Выводим тип файла	
	
    $type = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));

// Выводим типы которые можно загружать	
	
    $photo = array('gif', 'jpg', 'jpeg', 'png');		
	
// Проверяем тип файла	
	
    if (in_array($type, $photo)) {	
	
// Проверяем размер файла

    if ($_FILES['file']['size'] < 10000000) {	
	
// Выводим информацию о фото	
	
    $info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];	

// Аватар 256x256
	
    if ($width > 256 || $height > 256) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/avatar/256/'.$user['id'].'.png');	
    } else {
    $image->load($_FILES['file']['tmp_name']);		
    $image->save(SERVER.'/avatar/256/'.$user['id'].'.png');	
    }	

// Аватар 128x128
	
    if ($width > 128 || $height > 128) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(128,128);	
    $image->save(SERVER.'/avatar/128/'.$user['id'].'.png');		
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/avatar/128/'.$user['id'].'.png');		
    }	

// Аватар 64x64
	
    if ($width > 64 || $height > 64) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(64,64);	
    $image->save(SERVER.'/avatar/64/'.$user['id'].'.png');	
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/avatar/64/'.$user['id'].'.png');		
    }	

// Аватар 
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/avatar/'.$user['id'].'.png');	
	
// Обновляем информацию в базе

    DB :: $dbh -> query("UPDATE `user` SET `avatar`=? WHERE `id`=? LIMIT 1;", array(1, $user['id']));	
	
// Уведомляем

    $system->redirect("Аватар успешно загружен", "/modules/settings/avatar");	

// Выводим ошибки	
	
    } else { $system->show("Выбранное вами фото имеет размер болие 10мб"); }	
    } else { $system->show("Недопустимый тип файла"); }		
    } else { $system->show("Не выбран файл"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }
	
// Выводим форму

    echo '

    <div class="title">
    Ваш текущий аватар, т.е главное фото на вашей странице.
    </div>
    <div class="block">
    '.$avatar->mini($user['id'], 128,128).'
    </div>';
	
// Выводим последние 3 фото

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? LIMIT 3;", array($user['id']));

// Только если фотографий больше 0	
	
    if ($count > 0) {

// Выводим блок

    echo '<div class="block">';

// Выводим фотографии

    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `user`=? ORDER BY `time` DESC  LIMIT 3;", array($user['id']));

// Выводим фото

    while ($act = $q -> fetch()) {

    echo '
    <a href="/modules/photo_album/photo/avatar/'.$act['id'].'">
    '.$photo->micro($act['id'], 64, 64, $act['key'], $act['type']).'
    </a>
    ';

    }
	
    echo '
    </div>
    <a class="touch" href="/modules/photo_album/'.$user['id'].'">Показать все фото</a>';

    }	
	
    echo '
    <div class="block">
    <FORM ENCTYPE="multipart/form-data" method="POST">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Добавить фото: (<10 Mb)<br />
    <input name="file" type="file" accept="image/jpeg, image/png, image/gif"/>
    </div>
    <div class="block">
    Вы можете загрузить сюда собственную фотографию расширения JPG, GIF, PNG. <br />
    Загрузка эротического изображения приведёт к блокировке вашей страницы.
    </div>
    <div class="block">
    <input type="submit" name="upload" value="Загрузить" />
    '.($user['avatar'] == 1 ? '
    <input type="submit" name="delete" value="Удалить" />
    ' : '').'	
    <input type="Reset" value="Сброс">
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		