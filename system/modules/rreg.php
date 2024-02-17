<?php

if(!defined('MOZG'))
    die('Hacking attempt!');

if($ajax == 'yes')
    NoAjaxQuery();
	
	$tpl->load_template('llogin/reg.tpl');
	//################## Загружаем Страны ##################//
$sql_country = $db->super_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".PREFIX."_country` ORDER by `name` ASC", true, "country", true);
foreach($sql_country as $row_country)
	$all_country .= '<option value="'.$row_country['id'].'">'.stripslashes($row_country['name']).'</option>';
			
$tpl->set('{country}', $all_country);
    $tpl->compile('content');
	
    $tpl->clear();
    $db->free();
?>