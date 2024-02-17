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

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Ищим категорию в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `gifts_dir` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная категория существует
	
    if (!empty($act)) {		

// Только если отправлен POST запрос	
	
    if (isset($_POST['upload'])) {		
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Только если файл выбран

    if (!empty($_FILES['file']['name'])) {		
	
// Выводим тип файла	
	
    $type = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
	
// Получаем информацию о файле

    $info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];		
	
// Выводим типы которые можно загружать	
	
    $gift = array('gif', 'jpg', 'jpeg', 'png');	
	
// Проверяем тип файла	
	
    if (in_array($type, $gift)) {
	
// Проверяем размер файла

    if ($width > 32 && $height > 32) {	
	
// Обработка стоимости

    $money = abs(intval($_POST['money']));	

// Обработка стоимости подарка
	
    if ($money > 4 && $money < 101) {	
	
// Добавляем подарок в базу
	
    DB :: $dbh -> query("INSERT INTO `gifts` (`dir`, `money`, `user`, `time`) VALUES (?, ?, ?, ?);", array($act['id'], $money, $user['id'], time()));	
	
// Получаем id подарка
 
    $id = DB :: $dbh -> lastInsertId();			

// Подарок 256x256
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(256,256);	
    $image->save(SERVER.'/gifts/256/'.$id.'.png');		

// Подарок 128x128
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(128,128);	
    $image->save(SERVER.'/gifts/128/'.$id.'.png');	

// Подарок 64x64
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(64,64);	
    $image->save(SERVER.'/gifts/64/'.$id.'.png');
	
// Подарок 32x32

    $image->load($_FILES['file']['tmp_name']);	
    $image->resize(32,32);	
    $image->save(SERVER.'/gifts/32/'.$id.'.png');	

// Подарок
	
    $image->load($_FILES['file']['tmp_name']);	
    $image->save(SERVER.'/gifts/'.$id.'.png');
	
// Уведомляем

    $system->redirect("Подарок успешно добавлен", "/modules/gifts/dir/".$act['id']."");	
	
// Выводим ошибки
    	
    } else { $system->show("Стоимость от 5 до 100 монет"); }	
    } else { $system->show("Минимальное разрешение 32x32"); }	
    } else { $system->show("Недопустимый тип файла"); }	
    } else { $system->show("Не выбран файл"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/gifts/dir/$act[id]");  
    }

// Выводим блок

    echo '
    <div class="block">
    <img class="middle" src="/icons/files.png"> Категория 
    <a href="/modules/gifts/dir/'.$act['id'].'">'.$act['name'].'</a> 
    </div>
    <div class="block">
    <FORM ENCTYPE="multipart/form-data" method="POST">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Добавить подарок: (>32x32) <br />
    <input name="file" type="file" accept="image/jpeg, image/png, image/gif"/> <br />
    </div>
    <div class="block">
    Стоимость: (от 5 до 100 монет) <br />
    <input type="text" name="money" value="5" size="3" maxlength="3"/>	
    </div>
    <div class="block">
    <input type="submit" name="upload" value="Загрузить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>
    ';	
	
// Выводим ошибки

    } else { $system->show("Выбранная вами категория не существует"); }
    } else { $system->show("Отказано в доступе"); }		
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	