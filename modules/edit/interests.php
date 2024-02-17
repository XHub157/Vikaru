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

// Меню

    echo '
    <div class="hide">
    <a href="/modules/edit/main" class="link">Основное</a>
    | <a href="/modules/edit/contacts" class="link">Контакты</a>
    | <a href="/modules/edit/interests">Интересы</a>
    | <a href="/modules/edit/type" class="link">Типаж</a>
    | <a href="/modules/edit/additionally" class="link">Дополнительно</a>
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
	
    DB :: $dbh -> query("UPDATE `user` SET `interests`=?,`fav_music`=?,`fav_films`=?,`fav_books`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($interests, $fav_music, $fav_films, $fav_books, $user['id'], time(), $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/interests");	
	
// Выводим ошибки	
		
    } else { $system->show("Слишком длинное описание любимых книг"); }
    } else { $system->show("Слишком длинное описание любимых фильмов"); }
    } else { $system->show("Слишком длинное описание любимой музыки"); }
    } else { $system->show("Слишком длинное описание интересов"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/profile/$user[id]");   
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Интересы: (1000 символов) <br />
    <textarea name="interests"  class="comment" />'.$user['interests'].'</textarea> <br />
    </div>
    <div class="block">
    Любимая музыка: (1000 символов) <br />
    <textarea name="fav_music"  class="comment" />'.$user['fav_music'].'</textarea> <br />
    </div>	
    <div class="block">
    Любимые фильмы: (1000 символов) <br />
    <textarea name="fav_films"  class="comment" />'.$user['fav_films'].'</textarea> <br />
    </div>	
    <div class="block">
    Любимые книги: (1000 символов) <br />
    <textarea name="fav_books"  class="comment" />'.$user['fav_books'].'</textarea> <br />
    </div>	
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>