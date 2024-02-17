<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


class email {

// Функция отправки письма	
	
function send($email, $subject, $text) {

    $message = '
    <div style="
    background-color: #FFFFFF;
    text-align: center;
    padding: 7px;
    border-radius: 20px 0px 0px 0px;
    border-top: 1px solid #dcdee2;
    border-left: 1px solid #dcdee2;
    border-right: 1px solid #dcdee2;
    box-shadow: 0 0 9px 1px rgba(71,79,87,0.15);
    ">
    <img src="http://'.DOMAIN.'/icons/template/logo.png"/>
    </div>
    <div style="
    background-color:#e8efff;
    color:#5F5F5F;
    text-align: center;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 10px;
    padding-right: 10px;	
    border-left:1px solid #dcdee2;
    border-right:1px solid #dcdee2;
    box-shadow: 0 0 9px 1px rgba(71,79,87,0.15);
    ">
    '.$subject.'
    </div>
    <div style="
    background-color: #FFFFFF;
    color: #686868;
    padding: 9px;
    word-wrap: break-word;
    border-bottom:1px solid #eee;
    border-left:1px solid #dcdee2;
    border-right:1px solid #dcdee2;
    box-shadow: 0 0 9px 1px rgba(71,79,87,0.15);
    ">
    '.$text.'
    <div style="
    margin-top: 10px;
    margin-bottom: 10px;
    border-top: 1px dotted #CCC;
    "></div>
    <span style="font-weight: bold;">
    - Support@lovesimka.ru <br />
    - Admin@lovesimka.ru <br />
    - A.Sokolovsky_xakz@mail.ru <br />
    </span>
    </div>
    <div style="
    background-color:#e8efff;
    color:#5F5F5F;
    text-align: center;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 10px;
    padding-right: 10px;	
    border-left:1px solid #dcdee2;
    border-right:1px solid #dcdee2;
    box-shadow: 0 0 9px 1px rgba(71,79,87,0.15);
    ">
    Изменить настройки оповещений <a href="http://'.DOMAIN.'/modules/settings/email">'.DOMAIN.'</a>
    </div>	
    <div style="
    background-color: #c4e4ff;
    color: #5C6070; 
    text-align: left;
    padding: 9px;
    border-radius: 0 0 25px 0;
    border-bottom:1px solid #b7d9fe;
    border-left:1px solid #b7d9fe;
    border-right:1px solid #b7d9fe;
    box-shadow: 0 0 9px 1px rgba(71,79,87,0.15);
    ">
    © <a href="http://'.DOMAIN.'">'.DOMAIN.'</a> 2013
    </div>
    ';

// Обработка в utf-8	
	
    $utf8 = 'Content-Type: text/html; charset=utf-8';

// Отправляем сообщение	
	
    mail($email,'=?utf-8?B?'.base64_encode($subject).'?=',$message,$utf8);

}

} 

?>