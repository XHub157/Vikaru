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

// Подключаем файловое ядро

    $files = new files();	
	
// Выводим шапку

    $title = 'Скриншот';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный файл существует
	
    if (!empty($act)) {
	
// Проверяем права

    if ($act['user'] == $user['id']) {

// Только для файлов определённого типа

    if ($act['section'] == 'audio' || $act['section'] == 'other') {
	
// Удаление скриншота

    if (isset($_POST['delete'])) {

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Проверяем наличие скриншота на сервере

    if (is_file(SERVER.'/screen/file'.$act['id'].'_'.$act['key'].'.png')) {	
	
// Удаляем скриншот из сервера

    @unlink(SERVER."/screen/file$act[id]_$act[key].png");	
	
// Уведомляем

    $system->redirect("Скриншот успешно удалён", "/modules/files/file/screen/".$act['id']."");	
	
// Выводим ошибки	
	
    } else { $system->show("Скришот не загружен"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 	

    }	

// Только если отправлен POST запрос	
	
    if (isset($_POST['upload'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Проверяем тип файла	
	
    $type = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));

// Выводим типы которые можно загружать	
	
    $screen = array('gif', 'jpg', 'jpeg', 'png');		

// Проверяем тип файла	
	
    if (in_array($type, $screen)) {
	
// Получаем информацию о скриншоте

    $info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];	

// Загружаем скриншот на сервер

    if ($width > 256 || $height > 256) {
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/screen/file'.$act['id'].'_'.$act['key'].'.png');		
    } else {
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/screen/file'.$act['id'].'_'.$act['key'].'.png');
    }
	
// Уведомляем

    $system->redirect("Скриншот успешно загружен", "/modules/files/file/".$act['id']."");

// Выводим ошибки
     
    } else { $system->show("Недопустимый тип файла"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/files/file/$act[id]");  
    }	
	
// Выводим форму

    echo '
    '.(is_file(SERVER.'/screen/file'.$act['id'].'_'.$act['key'].'.png') ? '
    <div class="block" style="text-align: center">
    <img class="middle" src="http://'.SERVER_DOMAIN.'/screen/file'.$act['id'].'_'.$act['key'].'.png">
    </div>' : '').'
    <div class="block">
    Скриншот к файлу 
    '.$files->type($act['type']).' <a href="/modules/files/file/'.$act['id'].'">
    '.$act['name'].'</a>
    </div>
    <div class="block">
    <FORM ENCTYPE="multipart/form-data" method="POST">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input name="file" type="file" accept="image/jpeg, image/png, image/gif"/> <br />
    </div>
    <div class="block">	
    <input type="submit" name="upload" value="Загрузить" />
    <input type="submit" name="back" value="Отмена" />
    '.(is_file(SERVER.'/screen/file'.$act['id'].'_'.$act['key'].'.png') ? '<input type="submit" name="delete" value="Удалить" />' : '').'
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Отказано в доступе"); } 	
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранный вами файл не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		