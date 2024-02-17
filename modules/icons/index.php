<?php

// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/core/system.php"); 

// Подключаем текстовое ядро
	
    $text = new text();
	    $avatar = new avatar();

// Выводим шапку

    $title = 'Дневник';

// Инклудим шапку

include_once (ROOT.'template/head.php');



if(!isset($_GET['get'])){
echo '<div class="mess">';
echo '<span class="mess"><b> Магазин </b></span>';
echo '<a href="?get=my_icons"><span class="mess"><b> Мои иконки </b></span></a>';
echo '</div>';


echo '<div class="oh">';

$x=0;
while ($x++<125) echo '<a href ="pay.php?id='.$x.'"><span class="icon_s"><img src="png/'.$x.'.png" class="adv_user_link wa mt_m"></span></a>';

echo '</div>';
}
else
{
echo '<div class="mess">';
echo '<a href="?"><span class="mess"><b> Магазин </b></span></a>';
echo '<span class="mess"><b> Мои иконки </b></span>';
echo '</div>';


$icon= DB :: $dbh -> query("SELECT * FROM `us_icons` WHERE `id_user` = '".$user['id']."'  LIMIT 1") -> fetch();


if (empty($icon)) 
{
echo '<div class="err"> У вас нет активных иконок!</div>';
}
else
{
echo '<div class="mess">';
echo 'Иконка: <img src="png/'.$icon['id_icon'].'.png">';
if($icon['time'] > $time) {
echo '<br />До: <i>'.vremja($icon['time']).'</i>';
}
else
{
echo '<br />До: <i>срок истек</i>';
}
echo '<br /><a href="?get=my_icons&delete"> <small>Удалить</small></a>';

if(isset($_GET['delete']))
{
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно удалена!';

}


echo '</div>';

}





}


// Инклудим ноги	
	
include_once (ROOT.'template/foot.php');

?>