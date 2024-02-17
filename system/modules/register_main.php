<?php
/* 
	Appointment: Вывод формы регистрации на главной
	File: register_main.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

$tpl->load_template('reg.tpl');

//################## Загружаем Страны ##################//
$sql_country = $db->super_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".PREFIX."_country` ORDER by `name` ASC", true, "country", true);
foreach($sql_country as $row_country)
	$all_country .= '<option value="'.$row_country['id'].'">'.stripslashes($row_country['name']).'</option>';
			
$tpl->set('{country}', $all_country);

$tpl->compile('content');
?>