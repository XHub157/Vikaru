<?php


/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */



// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $text = new text();
	
// Подключаем статистическое ядро
	
    $count = new count();
    $count_dating = new count_dating();

// Именуем страницу

    $title = 'Главная';

// Инклудим шапку

include_once (ROOT.'template/head.php');

if (isset($user)) {




// Содержимое страницы

   header('location: /id'.$user['id'].'');
	
}
	else
	{
	
	include_once ($_SERVER['DOCUMENT_ROOT']."/modules/authorization/no_user.php"); 
	
	}


	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

