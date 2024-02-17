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

    $title = 'Интересы';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {
	
// Антифлуд
	
    $antiflood = DB :: $dbh -> querySingle("SELECT count(*) FROM `user` WHERE `edit_time`>? AND `id`=?;", array(time()-$config['antiflood_edit'], $data['id']));

// Только если не было изменений в течении 5 секунд
	
    if (empty($antiflood)) {

// Меню

    echo '	
    <div class="hide">
    <a href="/modules/administration/user/edit_data/'.$data['id'].'" class="link">Данные</a>
    | <a href="/modules/administration/user/edit/main/'.$data['id'].'" class="link">Основное</a>
    | <a href="/modules/administration/user/edit/contacts/'.$data['id'].'" class="link">Контакты</a>
    | <a href="/modules/administration/user/edit/interests/'.$data['id'].'" >Интересы</a>
    | <a href="/modules/administration/user/edit/type/'.$data['id'].'" class="link">Типаж</a>
    | <a href="/modules/administration/user/edit/additionally/'.$data['id'].'" class="link">Дополнительно</a>
    </div>';

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $interests = $system->check($_POST['interests']);
    $fav_music = $system->check($_POST['fav_music']);
    $fav_films = $system->check($_POST['fav_films']);
    $fav_books = $system->check($_POST['fav_books']);
	
// Обработка количества символов интересов
	
    if ($system->utf_strlen($interests) <= 1000) {

// Обработка количества символов любимой музыки
	
    if ($system->utf_strlen($fav_music) <= 1000) {
	
// Обработка символов любимых фильмов
	
    if ($system->utf_strlen($fav_films) <= 1000) {
	
// Обработка символов любимых книг
	
    if ($system->utf_strlen($fav_books) <= 1000) {
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `interests`=?,`fav_music`=?,`fav_films`=?,`fav_books`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($interests, $fav_music, $fav_films, $fav_books, $user['id'], time(), $data['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/administration/user/".$data['id']."");	
	
// Выводим ошибки	
		
    } else { $system->show("Слишком длинное описание любимых книг"); }
    } else { $system->show("Слишком длинное описание любимых фильмов"); }
    } else { $system->show("Слишком длинное описание любимой музыки"); }
    } else { $system->show("Слишком длинное описание интересов"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/administration/user/$data[id]");   
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Интересы: (1000 символов) <br />
    <textarea name="interests"  class="comment" />'.$data['interests'].'</textarea> <br />
    </div>
    <div class="block">
    Любимая музыка: (1000 символов) <br />
    <textarea name="fav_music"  class="comment" />'.$data['fav_music'].'</textarea> <br />
    </div>	
    <div class="block">
    Любимые фильмы: (1000 символов) <br />
    <textarea name="fav_films"  class="comment" />'.$data['fav_films'].'</textarea> <br />
    </div>	
    <div class="block">
    Любимые книги: (1000 символов) <br />
    <textarea name="fav_books"  class="comment" />'.$data['fav_books'].'</textarea> <br />
    </div>	
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';

// Выводим ошибки

    } else { $system->show("Не так быстро, подождите немного"); } 	
    } else { $system->show("Выбранный вами пользователь не существует"); } 
    } else { $system->redirect("Отказано в доступе", "/"); }	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>