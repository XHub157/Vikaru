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

    $title = 'Контакты';

// Инклудим шапку

include_once (ROOT.'template/head.php');	

// Меню

    echo '
    <div class="hide">
    <a href="/modules/edit/main" class="link">Основное</a>
    | <a href="/modules/edit/contacts">Контакты</a>
    | <a href="/modules/edit/interests" class="link">Интересы</a>
    | <a href="/modules/edit/type" class="link">Типаж</a>
    | <a href="/modules/edit/additionally" class="link">Дополнительно</a>
    </div>';

    if (isset($_POST['save'])) {
	
// Проверяем sid	
	
    if (isset($_POST['sid']) && $system->check($_POST['sid']) == $user['sid']) {	

// Обработка переменных	
	
    $mobile_phone = $system->check($_POST['mobile_phone']);
    $additional_phone = $system->check($_POST['additional_phone']);
    $skype = $system->check($_POST['skype']); 
    $icq = $system->check($_POST['icq']);
    $vk = $system->check($_POST['vk']);
    $twitter = $system->check($_POST['twitter']);
    $facebook = $system->check($_POST['facebook']);	
	
// Обработка количества символов поля мобильный телефон
	
    if ($system->utf_strlen($mobile_phone) <= 20) {

// Обработка количества символов поля дополнительный телефон
	
    if ($system->utf_strlen($additional_phone) <= 20) {		

// Обработка количества символов поля skype
	
    if ($system->utf_strlen($skype) <= 20) {

// Обработка количества символов поля icq
	
    if ($system->utf_strlen($icq) <= 20) {

// Обработка количества символов поля vk
	
    if ($system->utf_strlen($vk) <= 20) {

// Обработка количества символов поля twitter
	
    if ($system->utf_strlen($twitter) <= 20) {	
	
// Обработка количества символов поля facebook
	
    if ($system->utf_strlen($facebook) <= 20) {		
	
// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `mobile_phone`=?, `additional_phone`=?,`skype`=?,`icq`=?,`vk`=?,`twitter`=?, `facebook`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($mobile_phone, $additional_phone, $skype, $icq, $vk, $twitter, $facebook, $user['id'], time(), $user['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/contacts");	
	
// Выводим ошибки	
	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Недопустимое количество символов"); }	
    } else { $system->show("Слишком длинный или короткий номер ICQ"); } 	
    } else { $system->show("Слишком длинный или короткий логин Skype"); }	
    } else { $system->show("Слишком длинный или короткий номер дополнительного телефона"); }	
    } else { $system->show("Слишком длинный или короткий номер мобильного телефона"); }	
    } else { $system->show("Замечена подозрительная активность, повторите действие"); }	
    } else if (isset($_POST['back'])) {
    header ("Location: /modules/profile/$user[id]");  
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    '.($user['city'] == NULL ? 'Город, страна: <a href="/modules/edit/country" class="link">Выбрать</a>
    ' : '
    <a href="/modules/edit/country">'.$user['city'].'. '.$user['region'].' '.$user['country'].'</a>').'
    </div>
    <div class="block">
    Мобильный телефон: (20 символов) <br />
    <input type="text" name="mobile_phone" value="'.$user['mobile_phone'].'"/> <br />
    </div>
    <div class="block">
    Дополнительный телефон: (20 символов) <br />
    <input type="text" name="additional_phone" value="'.$user['additional_phone'].'"/> <br />	
    </div>
    <div class="block">
    Skype: (20 символов) <br />
    <input type="text" name="skype" value="'.$user['skype'].'"/> <br />	
    </div>	
    <div class="block">
    ICQ: (20 символов) <br />
    <input type="text" name="icq" value="'.$user['icq'].'"/> <br />	
    </div>		
    <div class="block">
    VK: (20 символов) <br />
    <input type="text" name="vk" value="'.$user['vk'].'"/> <br />	
    </div>	
    <div class="block">
    Twitter: (20 символов) <br />
    <input type="text" name="twitter" value="'.$user['twitter'].'"/> <br />	
    </div>	
    <div class="block">
    Facebook: (20 символов) <br />
    <input type="text" name="facebook" value="'.$user['facebook'].'"/> <br />	
    </div>	
    <div class="block">
    <input type="submit" name="save" value="Сохранить" />
    <input type="submit" name="back" value="Отмена" />
    </form>
    </div>';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		