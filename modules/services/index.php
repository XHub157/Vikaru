<?php

// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Только для зарегистрированых

    $profile->access(true);
	
// Выводим шапку

    $title = 'Сервисы';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Содержимое страницы

    echo '
    <div class="hide">
    Здесь Вы можете приобрести или продать услуги.
    </div>
    <div class="block">
    У вас на счету: '.$user['money'].' монет<br />
    <span style=" font-weight: bold; ">Что можно сделать:</span>
    </div>
    <div class="block">
    <img class="middle" src="/icons/services.png"> 
    Пополнить счет 
    <a href="/modules/services/payment/10"><span class="count">10</span></a>
    <a href="/modules/services/payment/50"><span class="count">50</span></a>
    <a href="/modules/services/payment/100"><span class="count">100</span></a>
    монет
    </div>
    <a class="touch" href="/modules/services/login_change">
    - Изменить логин </a> 
    <a class="touch" href="/modules/services/rating_change">
    - Купить рейтинг </a>
    <a class="touch" href="/modules/services/hide_change">
    - Купить статус невидимки </a>
    <a class="touch" href="/modules/services/vip_change">
    - Купить vip статус </a>
    <div class="hide">
    <a href="/modules/services/logs"> Журнал операций </a> <br />
    <a href="/modules/reference/"> Зачем нужны монеты? </a> <br />
    </div>	
    <div class="block">
    О проблемах с использованием платных сервисов пишите на <br />
    - <span style=" font-weight: bold; ">Artem.Sokolovsky@bk.ru</span> <br />
    - <span style=" font-weight: bold; ">Support@localhost.ru</span> <br />
    - <span style=" font-weight: bold; ">Admin@localhost.ru</span> 
    </div>
    ';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	