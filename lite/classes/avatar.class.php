<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки аватаров~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class avatar {

    var $id; // номер пользователя
    var $width;  // ширина
    var $height; // высота

function micro($id, $width, $height){

    if (is_file(SERVER."/avatar/64/$id.png")) {
    return '<img class="photos" src="http://'.SERVER_DOMAIN.'/avatar/64/' . $id . '.png" width="'.$width.'" height="'.$height.'" />';
    } else {
    return '<img class="photos" src="http://'.SERVER_DOMAIN.'/avatar/64/avatar.png" width="'.$width.'" height="'.$height.'"  />';
    }
	
}	

function mini_lefts0($id, $width, $height){

    if (is_file(SERVER."/avatar/64/$id.png")) {
    return '<img class="" src="http://'.SERVER_DOMAIN.'/avatar/64/' . $id . '.png" width="'.$width.'" height="'.$height.'" />';
    } else {
    return '<img class="" src="http://'.SERVER_DOMAIN.'/avatar/64/avatar.png" width="'.$width.'" height="'.$height.'"  />';
    }
	
}


function left_font0($id, $width, $height){

    if (is_file(SERVER."/avatar/64/$id.png")) {
    return '<div class="left font0 avatar_wrap padd_right"><img class="photos" src="http://'.SERVER_DOMAIN.'/avatar/64/' . $id . '.png" width="'.$width.'" height="'.$height.'" /></div>';
    } else {
    return '<div class="left font0 avatar_wrap padd_right"><img class="photos" src="http://'.SERVER_DOMAIN.'/avatar/64/avatar.png" width="'.$width.'" height="'.$height.'"  /></div>';
    }
	
}

function mini($id, $width, $height){

    if (is_file(SERVER."/avatar/128/$id.png")) {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/avatar/128/' . $id . '.png" width="'.$width.'" height="'.$height.'"  />';
    } else {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/avatar/128/avatar.png" width="'.$width.'" height="'.$height.'"  />';
    }
	
}

function mini_left($id, $width, $height){

    if (is_file(SERVER."/avatar/128/$id.png")) {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/128/' . $id . '.png" width="'.$width.'" height="'.$height.'" class="op_fimg _u1" />';
    } else {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/128/avatar.png" width="'.$width.'" height="'.$height.'" class="op_fimg _u1" />';
    }
	
}
	
function profile_z($id, $width, $height){

    if (is_file(SERVER."/avatar/256/$id.png")) {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/256/' . $id . '.png" width="'.$width.'" height="'.$height.'" class="__ava-size" />';
    } else {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/256/avatar.png" width="'.$width.'" height="'.$height.'" class="__ava-size" />';
    }
	
}

function mail($id, $width, $height){

    if (is_file(SERVER."/avatar/128/$id.png")) {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/128/' . $id . '.png" width="'.$width.'" height="'.$height.'" class="di_img" />';
    } else {
    return '<img src="http://'.SERVER_DOMAIN.'/avatar/128/avatar.png" width="'.$width.'" height="'.$height.'" class="di_img" />';
    }
	
}	
	
function small($id, $width, $height){

    if (is_file(SERVER."/avatar/256/$id.png")) {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/avatar/256/' . $id . '.png" width="'.$width.'" height="'.$height.'"  />';
    } else {
    return '<img class="photo" src="http://'.SERVER_DOMAIN.'/avatar/256/avatar.png" width="'.$width.'" height="'.$height.'" />';
    }
	
}		
		
}
	
?>