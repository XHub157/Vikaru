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

// Ищим город в базе

    $queryguest = DB :: $dbh -> query("SELECT * FROM `geo_cities` WHERE `city_id`=? LIMIT 1;", array($id));
    $city = $queryguest -> fetch();

// Только если данный город существует
	
    if (!empty($city)) {
	
// Выводим регион

    $region = DB :: $dbh -> queryFetch("SELECT `region_name` FROM `geo_regions` WHERE `region_id`=? LIMIT 1;", array($city['rid']));

// Выводим страну

    $country = DB :: $dbh -> queryFetch("SELECT `country_name` FROM `geo_countries` WHERE `country_id`=? LIMIT 1;", array($city['cid']));	

// Запись в базу	
	
    DB :: $dbh -> query("UPDATE `user` SET `city`=?,`region`=?,`country`=?, `edit`=?, `edit_time`=?  WHERE `id`=? LIMIT 1;", array($city['city_name'], $region['region_name'], $country['country_name'], $user['id'], time(), $user['id']));
	
// Уведомляем

    $system->redirect("Изменения успешно сохранены", "/modules/edit/contacts");	

    } else { $system->redirect("Выбранный вами город не существует", "/modules/edit/contacts"); }
    
?>