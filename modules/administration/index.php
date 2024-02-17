<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Панель управления';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 5) {

    echo '
    <div class="hide">
    Панель управления
    </div>
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    <a class="touch" href="/modules/administration/system/">
    <img class="middle" src="/icons/settings.png">
    Настройки системы
    </a>
    <a class="touch" href="/modules/administration/mail/">
    <img class="middle" src="/icons/mail.png">
    Почтовый шпион
    </a>' : '').'';  	
	
// Выводим ошибки
	
    } else { $system->redirect("Отказано в доступе", "/"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>