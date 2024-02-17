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
	
// Выводим шапку

    $title = 'Добавить';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим фотоальбом в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `photo_album` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный фотоальбом существует
	
    if (!empty($act)) {	

// Проверяем права

    if ($act['user'] == $user['id']) {	

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? AND `time`>?;", array($user['id'], time()-$config['antiflood_creation']));

// Только если был загружено фото в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {	

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
	
// Обработка названия
	
    $name = $system->check($_POST['name']);	

// Обработка описания
	
    $description = $system->check($_POST['description']);

// Обработка содержимого на метку 18+

    $censored = (empty($_POST['censored'])) ? 0 : 1;	

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 10000) {	
	
// Проверяем имя фото

    $photo = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `name`=? AND `user`=?;", array($name, $user['id']));

// Только если имя свободно	

    if (empty($photo)) {	

// Генерируем ключ

    $key = rand(1000000,9999999);
	
// Добавляем фотоальбом в базу
	
    DB :: $dbh -> query("INSERT INTO `photo` (`album`, `name`, `description`, `access`, `censored`, `user`, `time`, `ip`, `ua`, `key`, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", array($act['id'], $name, $description, $act['access'], $censored, $user['id'], time(), $system->ip(), $system->ua(), $key, $type));	
	
// Получаем id фото
 
    $id = DB :: $dbh -> lastInsertId();	

// Обновляем данные

    DB :: $dbh -> query("UPDATE `photo_album` SET `photos`=`photos`+1 WHERE `id`=?", array($act['id']));	
	
// Получаем информацию о фото

    $info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];		

// Фото 256x256
	
    if ($width > 256 || $height > 256) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/photo/256/photo'.$id.'_'.$key.'.'.$type.'');	
    } else {
    $image->load($_FILES['file']['tmp_name']);		
    $image->save(SERVER.'/photo/256/photo'.$id.'_'.$key.'.'.$type.'');	
    }	

// Фото 128x128
	
    if ($width > 128 || $height > 128) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(128,128);	
    $image->save(SERVER.'/photo/128/photo'.$id.'_'.$key.'.'.$type.'');		
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/photo/128/photo'.$id.'_'.$key.'.'.$type.'');		
    }	

// Фото 64x64
	
    if ($width > 64 || $height > 64) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(64,64);	
    $image->save(SERVER.'/photo/64/photo'.$id.'_'.$key.'.'.$type.'');	
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/photo/64/photo'.$id.'_'.$key.'.'.$type.'');		
    }	

// Фото
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/photo/photo'.$id.'_'.$key.'.'.$type.'');

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($name, 0, 50).".".$type."", "/modules/photo_album/photo/".$id."", "5");
	
// Уведомляем

    $system->redirect("Фото успешно добавлено", "/modules/photo_album/album/".$act['id']."");	
	
// Выводим ошибки
    
    } else { $system->show("У вас уже есть фото под именем ".$name.""); }		
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 
    } else { $system->show("Выбранное вами фото имеет размер болие 10мб"); }	
    } else { $system->show("Недопустимый тип файла"); }	
    } else { $system->show("Не выбран файл"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/photo_album/album/$act[id]");  
    }

// Выводим блок

    echo '
    <div class="block">
    <img class="middle" src="/icons/files.png"> Фотоальбом 
    <a href="/modules/photo_album/album/'.$act['id'].'">'.$act['name'].'</a> 
    </div>
    <div class="block">
    <FORM ENCTYPE="multipart/form-data" method="POST">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Добавить фото: (<10 Mb) <br />
    <input name="file" type="file" accept="image/jpeg, image/png, image/gif"/> <br />
    </div>
    <div class="block">
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    <input type="checkbox" class="middle" name="censored" value="1"/> Только для взрослых <br />
    </div>
    <div class="block">
    <input type="submit" name="upload" value="Загрузить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); }
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранный вами фотоальбом не существует"); } 	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	