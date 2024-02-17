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

// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `user`=? AND `time`>?;", array($user['id'], time()-$config['antiflood_creation']));

// Только если был загружен файл в течении $config['antiflood_creation'] секунд
	
    if (empty($antiflood)) {	

// Только если отправлен POST запрос	
	
    if (isset($_POST['upload'])) {	
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Только если файл выбран

    if (!empty($_FILES['file']['name'])) {		
	
// Выводим тип файла	
	
    $check = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
	
// Проверяем размер файла

    if ($_FILES['file']['size'] < 30000000) {
	
// Проверяем тип файла
	
    $type = ($system->utf_strlen($check) > 0 && $system->utf_strlen($check) < 10 && preg_match('|^[a-z0-9\-]+$|i', $check)) ? $check : 'ext';	
	
// Обработка имени	

    $name_file = basename($_FILES['file']['name']);
    $name = (!empty($_POST['name'])) ? $system->check($_POST['name']) : substr(strstr($system->check($name_file), ".", true), 0, 49);	

// Обработка описания
	
    $description = $system->check($_POST['description']);
	
// Обработка совместимости
	
    $compatibility = $system->check($_POST['compatibility']);	

// Обработка содержимого на метку 18+

    $censored = (empty($_POST['censored'])) ? 0 : 1;	

// Обработка количества символов названия
	
    if ($system->utf_strlen($name) >= 3 && $system->utf_strlen($name) < 50) {	

// Обработка количества символов описания
	
    if ($system->utf_strlen($description) < 10000) {

// Обработка количества символов совместимости
	
    if ($system->utf_strlen($compatibility) < 100) {	
	
// Проверяем имя файла

    $file = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `name`=? AND `user`=?;", array($name, $user['id']));

// Только если имя свободно	

    if (empty($file)) {	

// Генерируем ключ

    $key = rand(1000000,9999999);

// Выводим секцию для загрузки файла

    if ($type == 'jpg' || $type == 'jpeg' || $type == 'png' || $type == 'gif') {
    $section = 'image';
    } else if ($type == '3gp' || $type == 'mp4' || $type == 'avi' || $type == 'mpeg' || $type == 'flv') {
    $section = 'video';
    } else if ($type == 'mp3' || $type == 'aac' || $type == 'wav' || $type == 'wma' || $type == 'amr') {
    $section = 'audio';
    } else {
    $section = 'other';
    }	
	
// Добавляем файл в базу
	
    DB :: $dbh -> query("INSERT INTO `files` (`section`, `name`, `description`, `compatibility`, `censored`, `user`, `time`, `ip`, `ua`, `key`, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", array($section, $name, $description, $compatibility, $censored, $user['id'], time(), $system->ip(), $system->ua(), $key, $type));	
	
// Получаем id файла
 
    $id = DB :: $dbh -> lastInsertId();	

// Картинки	
	
    if ($type == 'jpg' || $type == 'jpeg' || $type == 'png' || $type == 'gif') {   	
	
// Получаем информацию о файле

    $info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];		

// Картинка 256x256
	
    if ($width > 256 || $height > 256) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/image/256/file'.$id.'_'.$key.'.'.$type.'');	
    } else {
    $image->load($_FILES['file']['tmp_name']);		
    $image->save(SERVER.'/image/256/file'.$id.'_'.$key.'.'.$type.'');	
    }	

// Картинка 128x128
	
    if ($width > 128 || $height > 128) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(128,128);	
    $image->save(SERVER.'/image/128/file'.$id.'_'.$key.'.'.$type.'');		
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/image/128/file'.$id.'_'.$key.'.'.$type.'');		
    }	

// Картинка 64x64
	
    if ($width > 64 || $height > 64) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(64,64);	
    $image->save(SERVER.'/image/64/file'.$id.'_'.$key.'.'.$type.'');	
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/image/64/file'.$id.'_'.$key.'.'.$type.'');		
    }	

// Картинка
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/image/file'.$id.'_'.$key.'.'.$type.'');
	
// Скриншот

    if ($width > 256 || $height > 256) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/screen/file'.$id.'_'.$key.'.png');		
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/screen/file'.$id.'_'.$key.'.png');		
    }
	
    } else if ($type == '3gp' || $type == 'mp4' || $type == 'avi' || $type == 'mpeg' || $type == 'flv') { 
    if (@copy($_FILES['file']['tmp_name'], SERVER."/video/file".$id."_".$key.".".$type."")) {
    chmod(SERVER."/video/file".$id."_".$key.".".$type."", 0666); } 
    } else if ($type == 'mp3' || $type == 'aac' || $type == 'wav' || $type == 'wma' || $type == 'amr') {
    if (@copy($_FILES['file']['tmp_name'], SERVER."/audio/file".$id."_".$key.".".$type."")) {
    chmod(SERVER."/audio/file".$id."_".$key.".".$type."", 0666); }
    } else {
    if (@copy($_FILES['file']['tmp_name'], SERVER."/other/file".$id."_".$key.".".$type."")) {
    chmod(SERVER."/other/file".$id."_".$key.".".$type."", 0666); }
    }	

// Уведомление в ленту

    $system->feed("".$user['id']."", "".substr($name, 0, 50).".".$type."", "/modules/files/file/".$id."", "6");
	
// Уведомляем

    $system->redirect("Файл успешно добавлен", "/modules/files/".$user['id']."");	
	
// Выводим ошибки
    
    } else { $system->show("У вас уже есть файл под именем ".$name.""); }
    } else { $system->show("Слишком длинное описание совместимости"); }	
    } else { $system->show("Слишком длинное описание"); } 
    } else { $system->show("Слишком длинное или короткое название"); } 	
    } else { $system->show("Размер файла 30мб"); } 
    } else { $system->show("Не выбран файл"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/files/$user[id]");  
    }

// Выводим блок

    echo '
    <div class="block">
    <img class="middle" src="/icons/files.png"> Файлы 
    <a href="/modules/files/'.$user['id'].'">'.$user['login'].'</a> 
    </div>
    <div class="block">
    <FORM ENCTYPE="multipart/form-data" method="POST">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Добавить фото: (<30 Mb) <br />
    <input name="file" type="file" /> <br />
    </div>
    <div class="block">
    Название: (50 символов) <br />
    <input type="text" name="name" value=""/> <br />
    Описание: (10000 символов) <br />
    <textarea name="description" class="textarea" /></textarea> <br />
    </div>
    <div class="block">
    Совместимость: (важно для игр и программ, не более 100 символов) <br />
    <input type="text" name="compatibility" style="width: 70%;" value=""/> <br />	
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
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	