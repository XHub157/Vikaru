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

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим папку в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files_dir` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данная папка существует
	
    if (!empty($act)) {	
	
// Проверяем доступ

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {	
	
// Проверяем существуют ли папка в папке
	
    $dir = DB :: $dbh -> querySingle("SELECT count(*) FROM `files_dir` WHERE `dir`=?;", array($act['id']));   

// Только если папок нет

    if (empty($dir)) { 

// Проверяем файлы
	
    $files = DB :: $dbh -> querySingle("SELECT count(*) FROM `files` WHERE `dir`=?;", array($act['id'])); 

// Только если файлов нет

    if (empty($files)) { 	
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {

// Удаляем из базы
	
    DB :: $dbh -> query("DELETE FROM `files_dir` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем данные

    if ($act['dir'] != 0) { DB :: $dbh -> query("UPDATE `files_dir` SET `dirs`=`dirs`-1 WHERE `id`=?", array($act['dir'])); }	

// Уведомляем

    $system->redirect("Папка успешно удалена", "".($act['dir'] == 0 ? "/modules/files/".$user['id']."" : "/modules/files/dir/".$act['dir']."")."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/files/dir/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    <img class="middle" src="/icons/files.png"> <a href="/modules/files/dir/'.$act['id'].'">'.$act['name'].'</a>
    </div>
    <form method="post">
    <div class="block">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    <input type="submit" name="delete" value="Удалить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';    

// Выводим ошибки
 
    } else { $system->show("Данная папка имеет вложенные файлы"); }	
    } else { $system->show("Данная папка имеет вложенные папки"); }	
    } else { $system->show("Отказано в доступе"); }	
    } else { $system->show("Выбранная вами папка не существует"); } 	 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>