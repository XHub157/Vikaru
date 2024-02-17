<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки изображений~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class image {
 
// Глобальные переменные 
 
    var $image;
    var $image_type;
 
// Загрузка изображения
 
function load($filename) {

    $image_info = getimagesize($filename);
    $this->image_type = $image_info[2];
    if ( $this->image_type == IMAGETYPE_JPEG ) {
    $this->image = imagecreatefromjpeg($filename);
    } else if ( $this->image_type == IMAGETYPE_GIF ) {
    $this->image = imagecreatefromgif($filename);
    } else if ( $this->image_type == IMAGETYPE_PNG ) {
    $this->image = imagecreatefrompng($filename);
    }
	
}


// Сохранение изображения

function save($filename, $image_type=IMAGETYPE_JPEG, $compression=90, $permissions=null) {

    if ( $image_type == IMAGETYPE_JPEG ) {
    imagejpeg($this->image,$filename,$compression);
    } else if ( $image_type == IMAGETYPE_GIF ) {
    imagegif($this->image,$filename);
    } else if ( $image_type == IMAGETYPE_PNG ) {
    imagepng($this->image,$filename);
    }
    if ($permissions != null) {
    chmod($filename,$permissions);
    }
	
}

// Ширина изображения

function getWidth() {

    return imagesx($this->image);
	
}

// Высота изображения

function getHeight() {
	
    return imagesy($this->image);
	
}
 
// Конвертируем

function resize($width,$height) {

    imagesavealpha($this->image, true);
    $new_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
	
}      
     
}

?>