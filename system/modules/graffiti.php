<?php
/* 
	Appointment: Граффити
	File: graffiti.php
	Autorsite: MainTalk.ru
 
*/
if(!defined('MOZG'))
	die("Hacking attempt!");
if($ajax == 'yes')
	NoAjaxQuery();

	$user_id = $user_info['user_id'];

if($logged){
	$act = $_GET['act'];
	$for_user_id =  intval($_GET['id']);
	$str_date = time();
	switch($act){
		
		//################### Отображения шаблона Граффити ###################//
		case "add":
			$tpl->load_template('graffiti.tpl');
                        $tpl->set('{fuid}', $for_user_id);
			$tpl->compile('content');
		AjaxTpl();
die();

		//################### Процесс сохранения Граффити ###################//
                case "send":

    $upload_dir = "/uploads/graffiti/";
      if(isset($_FILES['Filedata']))
      {
        $fHandle = fopen($_FILES['Filedata']['tmp_name'], "rb");
        if($fHandle)
        {
          $fData			= bin2hex(fread($fHandle, 32));
          if($fData == "89504e470d0a1a0a0000000d494844520000024a0000012508060000001b69cd")
          {
            $fImageData	= getimagesize($_FILES['Filedata']['tmp_name']);
            if($fImageData[0] == 586 && $fImageData[1] == 293)
            {
              $file_time	= time();
              $file_rand	= rand(0,9);
              $file_time	= $file_time . $file_rand;
              $file_name	= md5($file_time) . ".png";
              $i			= 0;
              while(file_exists($file_name) && $i < 20)
              {
                $i++;
                $file_time	= time();
                $file_rand	= rand(0,9)+$i;
                $file_time	= $file_time . $file_rand;
                $file_name	= md5($file_time) . ".png";
              }

              $origImage		= imagecreatefrompng($_FILES['Filedata']['tmp_name']);
              $newImage		= imagecreatetruecolor(587, 295);
              imagecopyresized($newImage, $origImage, 0, 0, 0, 0, 587, 295, $fImageData[0], $fImageData[1]);
              imagepng($newImage,  ROOT_DIR.$upload_dir.$file_name);
              $attach_files = '<img src="'.$upload_dir.$file_name.'" width="307" height="195">';
              $db->query("INSERT INTO `".PREFIX."_wall` SET author_user_id = '{$user_id}', for_user_id = '{$for_user_id}', text = '{$attach_files}', add_date = '{$str_date}'");
              $db->query("UPDATE `".PREFIX."_users` SET user_wall_num = user_wall_num+1 WHERE user_id = '{$for_user_id}'");
	      header("Location: /id{$for_user_id}");
            }
            }
	        }
            }

            break;
		
	}
	$tpl->clear();
	$db->free();
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>
