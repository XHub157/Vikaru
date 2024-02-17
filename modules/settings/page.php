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

    $title = 'Отображение';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Содержимое страницы

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных
	
    $post = abs(intval($_POST['post']));
	
// Обработка количества сообщений на страницу

    if ($post > 2 && $post < 11) { 		

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `post`=? WHERE `id`=? LIMIT 1;", array($post, $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/settings/page");	
	
// Выводим ошибки	
	
    } else { $system->show("Значение количества комментариев на странице должно быть от 3 до 30"); }
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/settings/");  
    }	
	
// Выводим форму	

    echo '
    <div class="title">
    Отображение количества контента на страницу.
    </div>
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Кол-во контента на странице: (от 3 до 10)<br />
    <input type="text" name="post" value="'.$user['post'].'" size="2" maxlength="2"/>
    </div>
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		