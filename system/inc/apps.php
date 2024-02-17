<?
if(!defined('MOZG'))
	die('И че ты тут забыл??');


if(isset($_GET['act'])){

//Если нажали "Добавить"
if(isset($_POST['saves'])){
	$id = intval($_POST['id']);
	$price = htmlspecialchars($_POST['title']);
	$desc = htmlspecialchars($_POST['desc']);


	$db->query("UPDATE `".PREFIX."_apps` SET title='{$price}', desk='{$desc}' WHERE id='{$id}'");

	if($_POST['original'] != ''){
	//Разришенные форматы
	$allowed_files = array('jpg','gif','png','swf');

	//Получаем данные о фотографии ОРИГИНАЛ
	$image_tmp = $_FILES['original']['tmp_name'];
	$image_name = totranslit($_FILES['original']['name']); // оригинальное название для оприделения формата
	$image_size = $_FILES['original']['size']; // размер файла
	$type = end(explode(".", $image_name)); // формат файла

	//Получаем данные о фотографии КОПИЯ
	$image_tmp_2 = $_FILES['thumbnail']['tmp_name'];
	$image_name_2 = totranslit($_FILES['thumbnail']['name']); // оригинальное название для оприделения формата
	$image_size_2 = $_FILES['thumbnail']['size']; // размер файла
	$type_2 = end(explode(".", $image_name_2)); // формат файла

	//Проверям если, формат верный то пропускаем
	if($price){
		if(in_array(strtolower($type), $allowed_files) AND in_array(strtolower($type_2), $allowed_files)){

			$rand_name = rand(0, 1000);

			$db->query("UPDATE `".PREFIX."_apps` SET app='".$rand_name.".swf', img= '".$rand_name.".".$type_2."' WHERE id='".$id."'");

			$album_dir = ROOT_DIR.'/uploads/apps/'.$row['id'].'/';
			if(!is_dir($album_dir)){
				@mkdir($album_dir, 0777);
				@chmod($album_dir, 0777);
			}

			move_uploaded_file($image_tmp, $album_dir.$rand_name.'.swf');
			move_uploaded_file($image_tmp_2, $album_dir.$rand_name.'.'.$type_2);
			msgbox('Информация', 'Игра успешно добавлена', '?mod=apps');
		} else
			msgbox('Ошибка', 'Неправильный формат', 'javascript:history.go(-1)');
	} else
		msgbox('Ошибка', 'Укажите название игры', 'javascript:history.go(-1)');
	}else{
	msgbox('Информация', 'Игра успешно обновлена', '?mod=apps');
	}
	die();
}

	$id = intval($_GET['id']);

	$row = $db->super_query("SELECT * FROM `".PREFIX."_apps` WHERE id='{$id}'");

	echoheader();
	echohtmlstart('Редактирование игры');
echo'
<form action="?mod=apps&act=edit" enctype="multipart/form-data" method="POST">
<input type="hidden" name="id" value="'.$row['id'].'" />
<div class="fllogall" style="width:180px">Название:</div>
 <input type="text" name="title" class="inpu" value="'.$row['title'].'" />
<div class="mgcler"></div>
<div class="fllogall" style="width:180px">Описание:</div>
<textarea class="inpu" name="desc" style="height: 57px;">'.$row['desk'].'</textarea>
<div class="mgcler"></div>
<div class="fllogall" style="width:180px">SWF файл игры:</div>
 <input type="file" name="original" class="inpu" style="width:300px" />
<div class="mgcler"></div>
<div class="fllogall" style="width:180px">Изображение игры .PNG,JPG :</div>
 <input type="file" name="thumbnail" class="inpu" style="width:300px" />
<div class="mgcler"></div>
<div class="fllogall" style="width:180px">&nbsp;</div>
 <input type="submit" value="Редактировать" class="inp" name="saves" style="margin-top:0px" />
</form>
<div style="width:234px;margin:20px auto;">
<img src="/uploads/apps/'.$row['id'].'/'.$row['img'].'" width="215">
</div>
';

}else{

//Если нажали "Добавить"
if(isset($_POST['save'])){
	$price = htmlspecialchars($_POST['title']);
	$desc = htmlspecialchars($_POST['desc']);

	$db->query("INSERT INTO `".PREFIX."_apps` (title,desk) VALUE ('".$price."','".$desc."')");
	//Разришенные форматы
	$allowed_files = array('jpg','gif','png','swf');

	//Получаем данные о фотографии ОРИГИНАЛ
	$image_tmp = $_FILES['original']['tmp_name'];
	$image_name = totranslit($_FILES['original']['name']); // оригинальное название для оприделения формата
	$image_size = $_FILES['original']['size']; // размер файла
	$type = end(explode(".", $image_name)); // формат файла

	//Получаем данные о фотографии КОПИЯ
	$image_tmp_2 = $_FILES['thumbnail']['tmp_name'];
	$image_name_2 = totranslit($_FILES['thumbnail']['name']); // оригинальное название для оприделения формата
	$image_size_2 = $_FILES['thumbnail']['size']; // размер файла
	$type_2 = end(explode(".", $image_name_2)); // формат файла

	//Проверям если, формат верный то пропускаем
	if($price){
		if(in_array(strtolower($type), $allowed_files) AND in_array(strtolower($type_2), $allowed_files)){

			$rand_name = rand(0, 1000);
			$row = $db->super_query("SELECT id FROM `".PREFIX."_apps` WHERE title='".$price."'");
			$db->query("UPDATE `".PREFIX."_apps` SET app='".$rand_name.".swf', img= '".$rand_name.".".$type_2."' WHERE id='".$row['id']."'");

			$album_dir = ROOT_DIR.'/uploads/apps/'.$row['id'].'/';
			if(!is_dir($album_dir)){
				@mkdir($album_dir, 0777);
				@chmod($album_dir, 0777);
			}

			move_uploaded_file($image_tmp, $album_dir.$rand_name.'.swf');
			move_uploaded_file($image_tmp_2, $album_dir.$rand_name.'.'.$type_2);
			msgbox('Информация', 'Игра успешно добавлена', '?mod=apps');
		} else
			msgbox('Ошибка', 'Неправильный формат', 'javascript:history.go(-1)');
	} else
		msgbox('Ошибка', 'Укажите нащвание игры', 'javascript:history.go(-1)');
	die();
}


	echoheader();
	echohtmlstart('Загрузка игр');

echo <<<HTML
<form action="" enctype="multipart/form-data" method="POST">

<input type="hidden" name="mod" value="notes" />

<div class="fllogall" style="width:180px">Название:</div>
 <input type="text" name="title" class="inpu" />
<div class="mgcler"></div>
<div class="fllogall" style="width:180px">Описание:</div>
<textarea class="inpu" name="desc" style="height: 57px;"></textarea>
<div class="mgcler"></div>

<div class="fllogall" style="width:180px">SWF файл игры:</div>
 <input type="file" name="original" class="inpu" style="width:300px" />
<div class="mgcler"></div>

<div class="fllogall" style="width:180px">Изображение игры .PNG,JPG :</div>
 <input type="file" name="thumbnail" class="inpu" style="width:300px" />
<div class="mgcler"></div>

<div class="fllogall" style="width:180px">&nbsp;</div>
 <input type="submit" value="Добавить" class="inp" name="save" style="margin-top:0px" />
</form>

HTML;


	$sql_ = $db->super_query("SELECT id,app,cols,title,img FROM `".PREFIX."_apps` ORDER BY id DESC",1);
	$b=0;
	foreach($sql_ as $row){
		$num = $row['cols'];
		$words = Array("человек", "человека", "человек");
		$game .= '
		<div class="fl_l" style="float: left; text-align: center; height: 115px; margin-top: 15px; width: 150px;">
		<a href="?mod=apps&act=edit&id='.$row['id'].'">
		<img src="/uploads/apps/'.$row['id'].'/'.$row['img'].'" width="75" height="75">
		<div >'.$row['title'].'</div>
		</a>
		</div>';
		$b++;
	}
	echohtmlstart('Загруженные игры ('.$b.')');
echo <<<HTML
<div>{$game}</div>
<div class="clr"></div>
HTML;

}
echohtmlend();
?>