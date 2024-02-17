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
	
// Подключаем файловое ядро

    $files = new files();		

// Выводим шапку

    $title = 'Удаление';

// Инклудим шапку

include_once (ROOT.'template/head.php');
	
// Ищим файл в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `files` WHERE `id`=? LIMIT 1;", array($id));
    $act = $queryguest -> fetch();	
	
// Только если данный файл существует
	
    if (!empty($act)) {	
	
// Проверяем права

    if ($act['user'] == $user['id'] || $user['access'] == 1 || $user['access'] == 2 || $user['access'] == 4) {		
	
// Только если отправлен POST запрос
	
    if (isset($_POST['delete'])) {	

// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {
	
// Удаляем файлы из сервера

    @unlink(SERVER."/image/64/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/image/128/file$act[id]_$act[key].$act[type]");	
    @unlink(SERVER."/image/256/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/image/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/video/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/audio/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/other/file$act[id]_$act[key].$act[type]");
    @unlink(SERVER."/screen/file$act[id]_$act[key].png");
	
// Удаляем лайки комментариев из базы
	
    DB :: $dbh -> query("DELETE FROM `files_comments_like` WHERE `file`=?;", array($act['id']));	
	
// Удаляем комментарии из базы
	
    DB :: $dbh -> query("DELETE FROM `files_comments` WHERE `file`=?;", array($act['id']));	

// Удаляем лайки темы

    DB :: $dbh -> query("DELETE FROM `files_like` WHERE `file`=?;", array($act['id']));	

// Удаляем просмотры из базы
	
    DB :: $dbh -> query("DELETE FROM `files_view` WHERE `file`=?;", array($act['id']));	
	
// Удаляем закачки из базы
	
    DB :: $dbh -> query("DELETE FROM `files_download` WHERE `file`=?;", array($act['id']));		

// Удаляем тему из базы
	
    DB :: $dbh -> query("DELETE FROM `files` WHERE `id`=? LIMIT 1;", array($act['id']));	

// Обновляем данные

    if ($act['dir'] != 0) { DB :: $dbh -> query("UPDATE `files_dir` SET `files`=`files`-1 WHERE `id`=?", array($act['dir'])); }	

// Уведомляем

    $system->redirect("Файл успешно удалён", "".($act['dir'] == 0 ? "/modules/files/".$user['id']."" : "/modules/files/dir/".$act['dir']."")."");
	
// Выводим ошибки
	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); } 
    } else if (isset($_POST['back'])) {
    header("Location: /modules/files/file/$act[id]");
    }	
	
// Выводим блок

    echo '<div class="block">
    '.$files->type($act['type']).' 
    <a href="/modules/files/file/'.$act['id'].'">'.$act['name'].'</a>
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
    } else { $system->show("Выбранный вами файл не существует"); } 	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>