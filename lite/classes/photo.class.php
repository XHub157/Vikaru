<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки фотографий~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class photo {

    var $id; // номер пользователя
    var $width;  // ширина
    var $height; // высота
    var $key; // ключ
    var $type; // тип

function micro($id, $width, $height, $key, $type){

    if (is_file(SERVER."/photo/64/photo".$id."_".$key.".".$type."")) {
    return '<img class="pictures" src="http://'.SERVER_DOMAIN.'/photo/64/photo' . $id . '_'.$key.'.'.$type.'" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').' />';
    } else {
    return '<img class="pictures" src="http://'.SERVER_DOMAIN.'/photo/64/photo.png" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').'  />';
    }
	
}	

function mini($id, $width, $height, $key, $type){

    if (is_file(SERVER."/photo/128/photo".$id."_".$key.".".$type."")) {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/photo/128/photo' . $id . '_'.$key.'.'.$type.'" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').' />';
    } else {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/photo/128/photo.png" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').'  />';
    }
	
}
	
function small($id, $width, $height, $key, $type){

    if (is_file(SERVER."/photo/256/photo".$id."_".$key.".".$type."")) {
    return '<img class="pic_pr" src="http://'.SERVER_DOMAIN.'/photo/256/photo' . $id . '_'.$key.'.'.$type.'" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').'  />';
    } else {
    return '<img class="pic_pr" src="http://'.SERVER_DOMAIN.'/photo/256/photo.png" '.(empty($width) ? '' : 'width="'.$width.'"').' '.(empty($height) ? '' : 'height="'.$height.'"').' />';
    }
	
}		
		
}
	
?>