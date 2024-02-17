<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 

// Подключаем текстовое ядро
	
    $avatar = new avatar();

// Выводим шапку

    $title = 'Управление пользователем';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Проверяем права

    if ($user['access'] > 0 && $user['access'] < 3) {

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();

// Только если данный пользователь существует
	
    if (!empty($data) && $data['id'] != $user['id'] && $data['id'] != 1) {
	
    $status = array('Пользователь', '<span style="color: #209143;">Создатель '.DOMAIN.'</span>', '<span style="color: #209143;">Администратор</span>', '<span style="color: #209143;">Модератор форума</span>', '<span style="color: #209143;">Модератор Файлов</span>');	
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
	
    echo '
    <div class="hide">
    '.($data['hello'] == NULL ? '
    Я люблю '.DOMAIN.'' : ''.$data['hello'].'
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    <br /><a href="/modules/administration/user/edit/main/'.$data['id'].'">
    <img class="middle" src="/icons/edit.png">
    Редактировать</a>
    ' : '').'').'
    </div>
    <div class="block">
    '.$avatar->mini($data['id'], 128,128).'
    </div>
    '.($user['access'] > 0 && $user['access'] < 3 && $data['avatar'] == 1 ? '
    <a class="touch_black" href="/modules/administration/user/delete_avatar/'.$data['id'].'">
    <img class="middle" src="/icons/delete.png">
    Удалить Аватар</a>
    ' : '').'';
	
// Выводим данные пользователей

    echo '
    <div class="block">
    IP: '.$data['ip'].' <br />
    Браузер: '.$data['ua'].' <br />
    <a class="link" href="/modules/administration/user/history/'.$data['id'].'">История входов</a> |
    <a class="link" href="/modules/administration/user/accounts/'.$data['id'].'">Возможные аккаунты</a> |
    <a class="link" href="/modules/administration/user/logs/'.$data['id'].'">Журнал операций</a> |
    <a class="link" href="/modules/administration/user/ban/'.$data['id'].'">Заблокировать</a> <hr>
    Дата регистрации: '.$system->system_time($data['date_reg']).' <br />
    Статус: '.($data['activation'] == 2 ? 'Гражданин' : 'Гость').' <br />
    Последний визит: '.$system->system_time($data['date_aut']).' <br />
    Время онлайн: '.$system->online($data['online']).' <hr>
    '.$status[$data['access']].' <br />
    Рейтинг: '.($data['rating'] == 0 ? '0.00' : ''.$data['rating'] / 100 .'').' <br />
    Монеты: '.$data['money'].' <br />
    </div>
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    <a class="touch_black" href="/modules/administration/user/edit_data/'.$data['id'].'">
    <img class="middle" src="/icons/edit.png">
    Редактировать данные</a>
    ' : '').'	
    ';
	
// Выводим анкету

    echo '
    <div class="section">Основное</div><div class="block">
    '.($data['first_name'] == NULL ? '' : 'Имя: '.$data['first_name'].'<br />').' 
    '.($data['last_name'] == NULL ? '' : 'Фамилия: '.$data['last_name'].'<br />').' 
    '.($data['sex'] == 0 ? 'Пол: Мужской <br />' : 'Пол: Женский <br />').' 
    '.($data['day'] == NULL ? '' : 'День рождения: '.$data['day'].'.'.$data['month'].'.'.$data['year'].'<br />').' 
    '.($data['marital_status'] == 0 ? '' : 'Семейное положение: '.$marital_status[$data['marital_status']].'<br />').'
    </div>
    '.($data['city'] == NULL && $data['mobile_phone'] == NULL && $data['additional_phone'] == NULL && $data['skype'] == NULL && $data['icq'] == NULL && $data['vk'] == NULL && $data['twitter'] == NULL && $data['facebook'] == NULL  ? '' : '<div class="section">Контакты</div><div class="block">').'
    '.($data['city'] == NULL ? '' : ''.$data['city'].'. '.$data['region'].' '.$data['country'].'<br />').'
    '.($data['mobile_phone'] == NULL ? '' : 'Мобильный телефон: '.$data['mobile_phone'].'<br />').' 
    '.($data['additional_phone'] == NULL ? '' : 'Дополнительный телефон: '.$data['additional_phone'].'<br />').' 
    '.($data['skype'] == NULL ? '' : 'Skype: <a href="skype:'.$data['skype'].'?call">'.$data['skype'].'</a><br />').' 
    '.($data['icq'] == NULL ? '' : 'ICQ: '.$data['icq'].'<br />').' 
    '.($data['vk'] == NULL ? '' : 'VK: <a href="https://vk.com/'.$data['vk'].'">'.$data['vk'].'</a><br />').' 
    '.($data['twitter'] == NULL ? '' : 'Twitter: <a href="https://twitter.com/'.$data['twitter'].'">'.$data['twitter'].'</a><br />').' 
    '.($data['facebook'] == NULL ? '' : 'Facebook: <a href="https://facebook.com/'.$data['facebook'].'">'.$data['facebook'].'</a><br />').' 	
    '.($data['city'] == NULL && $data['mobile_phone'] == NULL && $data['additional_phone'] == NULL && $data['skype'] == NULL && $data['icq'] == NULL && $data['vk'] == NULL && $data['twitter'] == NULL && $data['facebook'] == NULL  ? '' : '</div>').'
    '.($data['orientation'] == 0 && $data['purpose'] == 0 && $data['physique'] == 0 && $data['smoke'] == 0 && $data['growth'] == 0 && $data['weight'] == 0 && $data['hair'] == NULL && $data['eyes'] == NULL ? '' : '<div class="section">Типаж</div><div class="block">').'
    '.($data['orientation'] == 0 ? '' : 'Ориентация: '.$orientation[$data['orientation']].'<br />').'
    '.($data['purpose'] == 0 ? '' : 'Цель знакомства: '.$purpose[$data['purpose']].'<br />').'
    '.($data['physique'] == 0 ? '' : 'Телосложение: '.$physique[$data['physique']].'<br />').'
    '.($data['smoke'] == 0 ? '' : 'Телосложение: '.$smoke[$data['smoke']].'<br />').'
    '.($data['growth'] == 0 ? '' : 'Рост: '.$data['growth'].'<br />').'
    '.($data['weight'] == 0 ? '' : 'Вес: '.$data['weight'].'<br />').'
    '.($data['hair'] == NULL ? '' : 'Цвет волос: '.$data['hair'].'<br />').' 
    '.($data['eyes'] == NULL ? '' : 'Цвет глаз: '.$data['eyes'].'<br />').' 
    '.($data['character'] == NULL ? '' : 'Характер: '.$data['character'].'<br />').' 
    '.($data['orientation'] == 0 && $data['purpose'] == 0 && $data['physique'] == 0 && $data['smoke'] == 0 && $data['growth'] == 0 && $data['weight'] == 0 && $data['hair'] == NULL && $data['eyes'] == NULL ? '' : '</div>').'
    '.($data['interests'] == NULL && $data['fav_music'] == NULL && $data['fav_films'] == NULL && $data['fav_books'] == NULL ? '' : '<div class="section">Интересы</div><div class="block">').'
    '.($data['interests'] == NULL ? '' : 'Интересы: '.$data['interests'].'<br />').' 
    '.($data['fav_music'] == NULL ? '' : 'Любимая музыка: '.$data['fav_music'].'<br />').' 
    '.($data['fav_films'] == NULL ? '' : 'Любимые фильмы: '.$data['fav_films'].'<br />').' 
    '.($data['fav_books'] == NULL ? '' : 'Любимые книги: '.$data['fav_books'].'<br />').' 
    '.($data['interests'] == NULL && $data['fav_music'] == NULL && $data['fav_films'] == NULL && $data['fav_books'] == NULL ? '' : '</div>').'
    '.($data['business'] == 0 && $data['profession'] == NULL && $data['policy'] == 0 && $data['world_view'] == 0 && $data['life'] == 0 && $data['people'] == 0 && $data['inspire'] == 0 && $data['about_me'] == NULL ? '' : '<div class="section">Дополнительно</div><div class="block">').'
    '.($data['business'] == 0 ? '' : 'Чем я занимаюсь: '.$business[$data['business']].'<br />').'
    '.($data['profession'] == NULL ? '' : 'Профессия: '.$data['profession'].'<br />').'
    '.($data['policy'] == 0 ? '' : 'Полит. предпочтения: '.$policy[$data['policy']].'<br />').'
    '.($data['world_view'] == 0 ? '' : 'Мировоззрение: '.$world_view[$data['world_view']].'<br />').'
    '.($data['life'] == 0 ? '' : 'Главное в жизни: '.$life[$data['life']].'<br />').'
    '.($data['people'] == 0 ? '' : 'Главное в людях: '.$people[$data['people']].'<br />').'
    '.($data['inspire'] == NULL ? '' : 'Источники вдохновения: '.$data['inspire'].'<br />').'
    '.($data['about_me'] == NULL ? '' : 'О себе: '.$data['about_me'].'<br />').'
    '.($data['business'] == 0 && $data['profession'] == NULL && $data['policy'] == 0 && $data['world_view'] == 0 && $data['life'] == 0 && $data['people'] == 0 && $data['inspire'] == 0 && $data['about_me'] == NULL ? '' : '</div>').'	
    '.($user['access'] > 0 && $user['access'] < 3 ? '
    <a class="touch_black" href="/modules/administration/user/edit/main/'.$data['id'].'">
    <img class="middle" src="/icons/edit.png">
    Редактировать Анкету</a>
    ' : '').'';	

// Выводим ошибки
	
    } else { $system->show("Выбранный вами пользователь не существует"); }	
    } else { $system->redirect("Отказано в доступе", "/"); }	
	
// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>