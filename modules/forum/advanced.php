<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Выводим шапку

    $title = 'Поиск';

// Инклудим шапку

include_once (ROOT.'template/head.php');

    echo '
    <div class="hide">
    <form method="post" action="/modules/forum/search">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" /> <br />
    <input type="radio" class="middle" name="where" value="0" checked="checked" /> Искать по названию <br />   
    <input type="radio" class="middle" name="where" value="1"/> Искать по описанию <br />	
    </div>
    <div class="block">
    <input type="submit" value="Искать" class="submit" />
    </form>  
    </div>
    ';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>