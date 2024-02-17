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
	
// Подключаем ядро пополнений

    $payment = new payment();
    $payment->setServiceId(8595); 
    $payment->useHeader('yes');
    $payment->useJQuery('yes');
    $payment->useLang('ru');
    $payment->useCss('http://'.DOMAIN.'/template/payment.css');	
	
// Выводим шапку

    $title = 'Пополнение';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Содержимое страници

    if (isset($_REQUEST['smsbill_password'])) {
	
    if ($payment->checkPassword($_REQUEST['smsbill_password'])) {
	
// Пополняем баланс	
	
    DB :: $dbh -> query("UPDATE `user` SET `money`=`money`+100 WHERE `id`=? LIMIT 1;", array($user['id']));
	
// Запись в логи

    $message = 'Пополнение внутреннего баланса';
    DB :: $dbh -> query("INSERT INTO `user_logs` (`section`, `user`, `message`, `price`, `money`, `ip`, `ua`, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array(1, $user['id'], $message, 100, $user['money'] + 100, $system->ip(),  $system->ua(), time()));

// Уведомляем

    $system->redirect("Ваш баланс успешно пополнен на 100 монет", "/modules/services/");	
	
// Выводим ошибки	
	
    } else { $system->show("Введён неверный код. Возможно, код уже был использован ранее."); }	
    }
    	
// Выводим блок		
		
    echo '
    <div class="hide">
    У вас на счету: '.$user['money'].' монет <img class="middle" src="/icons/censored.png"> <br />
    </div>
    '.$payment->getForm().'
    <div class="hide">
    О проблемах с использованием платных сервисов пишите на <br />
    - <span style=" font-weight: bold; ">A.Sokolovsky_xakZ@mail.ru</span> <br />
    - <span style=" font-weight: bold; ">Support@lovesimka.ru</span> <br />
    - <span style=" font-weight: bold; ">Admin@lovesimka.ru</span> 
    </div>
    ';

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	