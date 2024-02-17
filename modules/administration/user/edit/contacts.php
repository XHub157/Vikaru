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
    | <a href="/modules/administration/user/edit/contacts/'.$data['id'].'">Контакты</a>
    | <a href="/modules/administration/user/edit/interests/'.$data['id'].'" class="link">Интересы</a>
    | <a href="/modules/administration/user/edit/type/'.$data['id'].'" class="link">Типаж</a>
    | <a href="/modules/administration/user/edit/additionally/'.$data['id'].'" class="link">Дополнительно</a>
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
	
    DB :: $dbh -> query("UPDATE `user` SET `mobile_phone`=?, `additional_phone`=?,`skype`=?,`icq`=?,`vk`=?,`twitter`=?, `facebook`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($mobile_phone, $additional_phone, $skype, $icq, $vk, $twitter, $facebook, $user['id'], time(), $data['id']));	

// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/administration/user/".$data['id']."");	
	
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
    header ("Location: /modules/administration/user/$data[id]");  
    }
	
// Выводим форму	

    echo '
    <div class="block">
    <form method="post">
    <input type="hidden" name="sid" value="'.$user['sid'].'" />
    Мобильный телефон: (20 символов) <br />
    <input type="text" name="mobile_phone" value="'.$data['mobile_phone'].'"/> <br />
    </div>
    <div class="block">
    Дополнительный телефон: (20 символов) <br />
    <input type="text" name="additional_phone" value="'.$data['additional_phone'].'"/> <br />	
    </div>
    <div class="block">
    Skype: (20 символов) <br />
    <input type="text" name="skype" value="'.$data['skype'].'"/> <br />	
    </div>	
    <div class="block">
    ICQ: (20 символов) <br />
    <input type="text" name="icq" value="'.$data['icq'].'"/> <br />	
    </div>		
    <div class="block">
    VK: (20 символов) <br />
    <input type="text" name="vk" value="'.$data['vk'].'"/> <br />	
    </div>	
    <div class="block">
    Twitter: (20 символов) <br />
    <input type="text" name="twitter" value="'.$data['twitter'].'"/> <br />	
    </div>	
    <div class="block">
    Facebook: (20 символов) <br />
    <input type="text" name="facebook" value="'.$data['facebook'].'"/> <br />	
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