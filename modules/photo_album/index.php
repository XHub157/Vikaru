<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Подключаем графическое ядро
	
    $photo = new photo();	

// Выводим шапку

    $title = 'Фотоальбом';

// Инклудим шапку

include_once (ROOT.'template/head.php');

// Выводим поисковый блок

    echo '
    <div class="hide">
    <form method="post" action="/modules/photo_album/search/">
    <input type="text" name="search" value="'.(empty($_SESSION['search']) ? '' : ''.$system->check($_SESSION['search']).'').'"placeholder="Введите пару слов для поиска..." style="width: 70%;" />
    <input type="submit" value="Искать" class="submit" /></form>  
    </div>';

// Ищим пользователя в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `user` WHERE `id`=? LIMIT 1;", array($id));
    $data = $queryguest -> fetch();
	
// Только если данный пользователь существует
	
    if (!empty($data)) {
	
// Делаем подсчёт альбомов только на главной страничке

    if ($page == 0) {	
	
// Подсчёт количества фотоальбомов

    $album = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo_album` WHERE `user`=? AND `album`=?;", array($data['id'], 0));		
	
    if ($album > 0) {

// Выводим дневники
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo_album` WHERE `user`=? AND `album`=? ORDER BY `time` DESC  LIMIT 20;", array($data['id'], 0));	
	
// Выводим дневник

    while ($act = $q -> fetch()) {	
	
    echo '
    <a class="touch_white" href="/modules/photo_album/album/'.$act['id'].'">
    <img class="middle" src="/icons/files.png"> 
    <span class="color" style="float: right;">
    '.$system->system_time($act['time']).'
    '.($act['access'] == 0 ? '<img class="middle" src="/icons/access_all.png" title="Доступен всем">' : '').'
    '.($act['access'] == 1 ? '<img class="middle" src="/icons/access_friends.png" title="Доступен друзьям">' : '').'
    '.($act['access'] == 2 ? '<img class="middle" src="/icons/access_me.png" title="Доступен автору">' : '').'   	
    </span>	
    '.$act['name'].' 
    <span class="left_count">'.$act['albums'].'</span><span class="right_count">'.$act['photos'].'</span>
    </a>
	
	<style>
.photo {
    clear: both;
    background: #F5F8FA;
    overflow: hidden;
    padding: 9px 10px;
    border: 1px solid #DBE0E5;
    border-top: 0px;
    margin-bottom: 10px;
}



.pl_photo_item_them1{
vertical-align:top;
display:inline-block;
zoom:1;
}
.pl_photo_item1 {
  vertical-align: top;
  display: inline-block;
  margin: 0;
  background: none;
  border: none;
}
@media all and (min-width:494px){
.pl_photo_item1{
width: 14.2%;
}
}
@media all and (min-width:341px){
.pl_photo_item1{
width: 32.2%;
}
}
@media all and (min-width:302px){
.pl_photo_item1{
width: 49%;
}
}

@media all and (min-width:797px){
.pl_photo_item1{
width: 16.1%;
}
}

.pl_photo_item_inner{
padding:5px;
}
.pl_photo_image_cont{
background: #FFFFFF;
border: 1px solid #dbdbdb;
padding:5px;
}
.pl_photo_image_conts{
background-color: #fff8dc;
border: 1px solid #dbdbdb;
padding:5px;
}
.pl_photo_image_wrap{
position:relative;

}
.pl_photo_image_wrap1{
position:relative;
border: 1px solid #ccc;
}

.pl_photo_image_wrap .pl_photo_image_current img{
width:100%;
max-width:100% !important;
display:block;
}
.pl_photo_image_wrap_adult{
position:absolute;
right:2px;
bottom:2px;
}

.pl_photo_item1{

}
.pl_photo_image_info1{
  padding-top:5px;
}  
.pl_photo_image_cont .shz-ico{
  margin-top:3px;
}    
.pl_photo_item1 {
  vertical-align: top;
  display: inline-block;
  zoom: 1;
}
.pl_photo_item_inner {
  padding: 5px;
}
.pl_photo_image_cont {
  background: #fff;
  border: 1px solid #dbdbdb;
  padding: 5px;
}
.pl_photo_image_wrap {
  position: relative;
  border: 1px solid #ccc;
}
.pl_photo_image_wrap_adult {
  position: absolute;
  right: 2px;
  bottom: 2px;
}

</style>';
	
    }}}
	
// Подсчёт количества фото

    $count = DB :: $dbh -> querySingle("SELECT count(*) FROM `photo` WHERE `user`=? AND `album`=?;", array($data['id'], 0));		
	
    if ($count > 0) {
    if ($page >= $count) {
    $page = 0; } $i = 0;

// Выводим фотографии
	
    $q = DB :: $dbh -> query("SELECT * FROM `photo` WHERE `user`=? AND `album`=? ORDER BY `time` DESC  LIMIT " . $page . ", " . $config['post'] . ";", array($data['id'], 0));	
	
// Выводим фото

echo '<div class="stnd_padd light_border_bottom overfl_hid font0" style="background:#e5e5e5;">';

    while ($act = $q -> fetch()) {	
    echo '
       <div class="pl_photo_item1 " style="margin-bottom: 0px;">
      	<div class="pl_photo_item_inner">
        <div class="pl_photo_image_cont">              
          <div class="pl_photo_image_wrap">
            <div class="pl_photo_image_current">
              <a href="/modules/photo_album/photo/'.$act['id'].'">
                 '.$photo->micro($act['id'], 64, 64, $act['key'], $act['type']).'
              </a>
            </div>                  
              </div> 
                    </div>           
          </div>              
           </div>';
	
    }
	
  echo '</div>';
	
// Выводим навигацию
	
    $navigation = new navigation;
    $navigation->pages('/modules/photo_album/'.$data['id'].'/?', $config['post'], $page, $count);	 	
	
// Выводим ошибки
	 
    } else { $system->show("Фотографий нет"); } 
    } else { $system->show("Выбранный вами пользователь не существует"); } 	
	
// Выводим меню

    echo ''.($data['id'] == $user['id'] ? '
    <a class="info-block-link" href="/modules/photo_album/add_photo/" style = "text-align: left;"><img class="middle" src="/icons/add.png"> Добавить фото</a>
	    <a class="info-block-link" href="/modules/photo_album/add_album/" style = "text-align: left;"><img class="middle" src="/icons/add-dir.png"> Добавить фотоальбом</a>
    ' : '').'';	

// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>	