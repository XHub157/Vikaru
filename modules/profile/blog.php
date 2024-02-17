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
	
    $count_user = new count_user();

// Подключаем текстовое ядро
	
    $avatar = new avatar();
    
    // Подключаем графическое ядро
	
    $photo = new photo();
	
// Обработка полученного id

    $profile->check($id);
    $data = $profile->data($id);	

// Выводим шапку

    $title = 'Профиль | '.$data['first_name'].' '.$data['last_name'].'';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем просмотры
	
    if (isset($user) && $data['id'] != $user['id'] && $user['hide'] < time()) {
    $view = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_view` WHERE `user`=? AND `profile`=?  LIMIT 1;", array($user['id'], $data['id']));	
    if (empty($view)) {
    DB :: $dbh -> query("INSERT INTO `user_view` (`user`, `profile`, `time`, `read`) VALUES (?, ?, ?, ?);", array($user['id'], $data['id'], time(), 1));
    } else {
    DB :: $dbh -> query("UPDATE `user_view` SET `time`=?, `read`=? WHERE `user`=? AND `profile`=? LIMIT 1;", array(time(), 1, $user['id'], $data['id']));
    }}
	
// Проверяем добавление в закладки

    $bookmarks = DB :: $dbh -> querySingle("SELECT count(*) FROM `bookmarks` WHERE `section`=? AND `element`=? AND `user`=?;", array(1, $data['id'], $user['id']));   

// Проверяем добавление в ленту

    $feed = DB :: $dbh -> querySingle("SELECT count(*) FROM `feed_user` WHERE `profile`=? AND `user`=?;", array($data['id'], $user['id'])); 

// Проверяем добавление в друзья

    $friends = DB :: $dbh -> querySingle("SELECT count(*) FROM `friends` WHERE `profile`=? AND `user`=?;", array($data['id'], $user['id'])); 

// Подсчёт количества гостей

    $guests = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_view` WHERE `profile`=?;", array($user['id']));
    $guests_new = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_view` WHERE `profile`=? AND `read`=?;", array($user['id'], 1));	

// Подсчёт подарков

    $gifts = DB :: $dbh -> querySingle("SELECT count(*) FROM `gifts_user` WHERE `profile`=?;", array($data['id']));

// Переменные анкеты

    $status = array('', '<span style="color: #209143;">Создатель '.DOMAIN.'</span>', '<span style="color: #209143;">Администратор</span>', '<span style="color: #209143;">Модератор форума</span>', '<span style="color: #209143;">Модератор Файлов</span>');
    $marital_status = ($data['sex'] == 0) ? array("", "В активном поиске", "Влюблён", "Встречаюсь", "Помовлен", "Женат", "Всё сложно", "Не женат") : array("", "В активном поиске", "Влюблёна", "Встречаюсь", "Помовлена", "Замужем", "Замужем", "Не женат");
    $orientation = ($data['sex'] == 0) ? array("", "Гетеро", "Гей", "Би") : array("", "Гетеро", "Лесби", "Би");
    $purpose = array("", "Дружба и общение", "Флирт, СМС-переписка", "Любовь, отношения", "Брак, создание семьи", "Виртуальный секс", "Секс в реале", "Ищу спонсора", "Стану спонсором");	
    $physique = array("", "Обычное", "Худощавое", "Спортивное", "Мускулистое", "Плотное");
    $smoke = array("", "Не курю", "Курю", "Иногда", "Бросаю"); 
    $business = array("", "Учусь в школе", "Учусь в колледже/лицее", "Учусь в ВУЗе", "Учусь в военном училище", "Служу в армии", "Работаю", "Не работаю");
    $policy = array("", "Индифферентные", "Коммунистические", "Социалистические", "Умеренные", "Либеральные", "Консервативные", "Монархические");
    $world_view = array("", "Иудаизм", "Православные", "Католицизм", "Протестантизм", "Ислам", "Буддизм", "Конфуцианство", "Светский гуманизм");
    $life = array("", "Семья и дети", "Карьера и деньги", "Развлечения и отдых", "Наука и исследования", "Совершенствования мира", "Саморазвитие", "Красота и искусство", "Слава и влияние");	
    $people = array("", "Ум и креативность", "Доброта и честность", "Красота и здоровье", "Власть и богатство", "Смелость и упорство", "Юмор и жизнелюбие");    

	// Подсчёт количества просмотров
	
    $views = DB :: $dbh -> querySingle("SELECT count(*) FROM `user_view` WHERE `profile`=?;", array($data['id']));

   	
echo '	
<div class="info-block" style="margin-bottom: 0px;">
    <div class="profile-info-name fl_l">
        <div class="fl_l profile-info-avatar">
            <a href="#">'.$avatar->profile_z($data['id'], 140,140).'</a><br>        </div>
        <div style="padding: 20px 0 0 10px;margin-left: 135px;">
            
            <span style="font-size: 1.7em;color: #3B3B3B;">'.$data['first_name'].' '.$data['last_name'].' </span>
			<br><br>
<div class="profile-info-status">
                '.($data['hello'] == NULL ? 'Я люблю '.DOMAIN.'' : ''.$data['hello'].'').'          </div>
        
		       <br>
		
      <div class="profile-info-status">
             Ыыы  не скажу ....          </div>         </div>
    </div>
    
    <div class="profile-info-links fl_l ">
        <a href="/modules/photo_album/'.$data['id'].'"><span class="profile-count">'.$count_user->photo($data['id']).'</span>Фото</a>

         <a href="/modules/friends/user/'.$data['id'].'"><span class="profile-count">'.$count_user->friends($data['id']).'</span>Друзья</a>
		 
         <a href="/modules/profile/blog/'.$data['id'].'"><span class="profile-count">'.$count_user->diary($data['id']).'</span>Блоги</a>
    </div>
    <div class="profile-info-buttons fl_l">
	'.($data['id'] == $user['id'] ? '	
            <a href="/modules/edit/main" class="profile-info-buttons-h">Редактировать профиль</a><br>
              <a href="/modules/diary/add_diary/" class="profile-info-buttons-h">Написать блог</a><br>
			  ' : '
			  <a href="/modules/mail/contact/'.$data['id'].'" class="profile-info-buttons-d">Написать сообщение</a><br>
			  
        <a href="/modules/gifts/?add='.$data['id'].'" class="profile-info-buttons-h">Отправить подарок</a><br>
		
		   '.(empty($friends) ? '
    <a href="/modules/friends/add/'.$data['id'].'" class="profile-info-buttons-h">Добавить в друзья</a>
    ' : '
    <a href="/modules/friends/delete/'.$data['id'].'" class="profile-info-buttons-h">Удалить из друзей</a>
   ').'	
	 ').'
         </div>
</div>';

// Подсчёт количества фото

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? AND `album`=?;", array($data['id'], 0));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим фотографии
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `user`=? AND `album`=? ORDER BY `time` DESC  LIMIT 6;", array($data['id'], 0));	
	
	echo '<div class="info-block" style="margin-bottom: 0px;"><center>';
	
// Выводим фото

    while ($act = $q -> fetch()) {	
	
    echo '
    <a href="/modules/photo_album/photo/'.$act['id'].'">
    '.$photo->micro($act['id'], 74, 74, $act['key'], $act['type']).'
    </a>
    ';
    }
	
	echo '</center></div>';
		
// Выводим ошибки
	 
    } else { $system->show("Фотографий нет"); } 

echo '
<div class="listbar">
    <a href="/id'.$data['id'].'" class="">О себе</a>
    <a href="/modules/profile/blog/'.$data['id'].'" class="listbar-act">Блог</a>
    <a href="/modules/profile/gifts/'.$data['id'].'" class="">Подарки</a>
	'.($user['access'] > 0 && $user['access'] < 3 && $data['id'] != $user['id'] ? '
	<a href="/modules/administration/user/'.$data['id'].'" class="" style="color: darkred;">Управление пользователем</a>
	 ' : '').'	
    </div>
	<style>
.lenta-next {
    border-bottom: 1px solid #DADAE2;
    margin: 14px 0;
}
    h4 {
    border-bottom: 1px solid #E8EBEE;
    color: #45688E;
    font-size: 11px;
    font-weight: bold;
    margin: 0px;
    padding: 10px 0px 10px;
}
</style>
	';
	
	

	// Подсчёт количества дневников

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `diary` WHERE `user`=?;", array($data['id']));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;
	
// Выводим дневники
	
    $q = DB :: $dbh -> query("SELECT * FROM `diary` WHERE `user`=? ORDER BY `time` DESC  LIMIT 4;", array($data['id']));	
	
// Выводим дневник

    while ($act = $q -> fetch()) {

    echo '
	<div class="info-block mg-b">
	<a href="/modules/diary/'.$act['id'].'">
    <img src="/icons/index/edit.png" class="m">
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'    	
    </span>
    <span style="font-weight: bold;">
    '.$act['name'].'
    </span>
    <br />
	</a>
    <span class="color">
    '.($act['censored'] == 1 || $act['access'] > 0 ? 'Доступ закрыт' : ''.$text->number($act['description'], 250).'').' <br />
    </span>
	</div>
    <a href="/modules/diary/'.$act['id'].'" class="info-block-link">Подробнее</a>
	
    ';
	
    }
	

// Выводим ошибки
	
 } else { $system->show("Дневников нет"); } 
	



// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>		