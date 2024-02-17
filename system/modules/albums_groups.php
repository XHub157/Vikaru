<?php
/* 
	Appointment: Альбомы
	File: albums.php 
 
*/
if(!defined('MOZG'))
	die('Hacking attempt!');

if($ajax == 'yes')
	NoAjaxQuery();

if($logged){
	$act = $_GET['act'];
	$pid = intval($_GET['pid']);
	$user_id = $user_info['user_id'];

	switch($act){

		//################### Создание альбома ###################//
		case "create":
			NoAjaxQuery();
			
			$name = ajax_utf8(textFilter($_POST['name'], false, true));
			$descr = ajax_utf8(textFilter($_POST['descr']));
			$pid = intval($_POST['pid']);
			
			if(isset($name) AND !empty($name)){
			
				$row = $db->super_query("SELECT albums_num FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
				
				if($row['albums_num'] < $config['max_albums']){
					//hash
					$hash = md5(md5($server_time).$name.$descr.md5($user_info['user_id']).md5($user_info['user_email']).$_IP);
					$date_create = date('Y-m-d H:i:s', $server_time);
					
					$sql_ = $db->query("INSERT INTO `".PREFIX."_communities_albums` (pid, name, descr, ahash, adate, position) VALUES ('{$pid}', '{$name}', '{$descr}', '{$hash}', '{$date_create}', '0')");
					$id = $db->insert_id();
					$db->query("UPDATE `".PREFIX."_communities` SET albums_num = albums_num+1 WHERE id = '{$pid}'");

					if($sql_)
						echo '/albums-'.$pid.'_'.$id.'/add/';
					else
						echo 'no';
				} else
					echo 'max';
			} else
				echo 'no_name';
			
			die();
		break;
		
		//################### Страница создания альбома ###################//
		case "create_page":
			NoAjaxQuery();
			$tpl->load_template('albums_groups/albums_create.tpl');
			$tpl->set('{pid}', $_POST['pid']);
			$tpl->compile('content');
			AjaxTpl();
			die();
		break;

		//################### Страница добавление фотографий в альбом ###################//
		case "add":
			$aid = intval($_GET['aid']);

			//Проверка на существование альбома
			$row = $db->super_query("SELECT name, aid FROM `".PREFIX."_communities_albums` WHERE aid = '{$aid}' and pid = '{$pid}'");
			if($row){
				$metatags['title'] = $lang['add_photo'];
				$tpl->load_template('albums_groups/albums_addphotos.tpl');
				$tpl->set('{aid}', $aid);
				$tpl->set('{album-name}', stripslashes($row['name']));
				$tpl->set('{pid}', $pid);
				$tpl->set('{PHPSESSID}', session_id());
				$tpl->compile('content');
			} else Hacking();
		break;
		
		//################### Загрузка фотографии в альбом ###################//
		case "upload":
			NoAjaxQuery();
			
			$aid = intval($_GET['aid']);
			
			
			//Проверка на существование альбома и то что загружает владелец альбома
			$row = $db->super_query("SELECT aid, photo_num, cover, system, privated FROM `".PREFIX."_communities_albums` WHERE aid = '{$aid}' AND pid = '{$pid}'");
			if($row['privated']) {
				$admin_albums = $db->super_query("SELECT level FROM `".PREFIX."_communities_admins` WHERE pid = '{$pid}' and user_id = '{$user_info['user_id']}'");
				if($admin_albums['level'] != 3) $privated_admin = true;
				else $privated_admin = false;
			} else $privated_admin = true;
			if($row AND !$row['system'] AND $privated_admin == true){
				
				//Проверка на кол-во фоток в альбоме
				if($row['photo_num'] < $config['max_album_photos']){
				
					//Директория юзеров
					$uploaddir = ROOT_DIR.'/uploads/groups/';
					
					//Если нет папок юзера, то создаём их
					if(!is_dir($uploaddir.$pid)){ 
						@mkdir($uploaddir.$pid, 0777 );
						@chmod($uploaddir.$pid, 0777 );
						@mkdir($uploaddir.$pid.'/albums', 0777 );
						@chmod($uploaddir.$pid.'/albums', 0777 );
					}
					
					//Если нет папки альбома, то создаём её
					$album_dir = ROOT_DIR.'/uploads/groups/'.$pid.'/albums/'.$aid.'/';
					if(!is_dir($album_dir)){ 
						@mkdir($album_dir, 0777);
						@chmod($album_dir, 0777);
					}

					//Разришенные форматы
					$allowed_files = explode(', ', $config['photo_format']);
				
					//Получаем данные о фотографии
					$image_tmp = $_FILES['uploadfile']['tmp_name'];
					$image_name = totranslit($_FILES['uploadfile']['name']); // оригинальное название для оприделения формата
					$image_rename = substr(md5($server_time+rand(1,100000)), 0, 20); // имя фотографии
					$image_size = $_FILES['uploadfile']['size']; // размер файла
					$type = end(explode(".", $image_name)); // формат файла
					
					//Проверям если, формат верный то пропускаем
					if(in_array(strtolower($type), $allowed_files)){
						$config['max_photo_size'] = $config['max_photo_size'] * 1000;
						if($image_size < $config['max_photo_size']){
							$res_type = strtolower('.'.$type);

							if(move_uploaded_file($image_tmp, $album_dir.$image_rename.$res_type)){
							
								//Подключаем класс для фотографий
								include ENGINE_DIR.'/classes/images.php';
				
								//Создание оригинала
								$tmb = new thumbnail($album_dir.$image_rename.$res_type);
								$tmb->size_auto('770');
								$tmb->jpeg_quality('85');
								$tmb->save($album_dir.$image_rename.$res_type);
								
								//Создание маленькой копии
								$tmb = new thumbnail($album_dir.$image_rename.$res_type);
								$tmb->size_auto('140x100');
								$tmb->jpeg_quality('90');
								$tmb->save($album_dir.'c_'.$image_rename.$res_type);
	
								$date = date('Y-m-d H:i:s', $server_time);
								
								$posdb = $db->super_query("SELECT position FROM `".PREFIX."_communities_photos` WHERE album_id = '{$aid}' AND pid = '{$pid}' ORDER by `position` DESC LIMIT 1");
								$position_all = $posdb['position']+1;
								$_SESSION['position_all'] = $position_all;
								

								//Вставляем фотографию
								$db->query("INSERT INTO `".PREFIX."_communities_photos` (album_id, photo_name, pid, date, position, userid) VALUES ('{$aid}', '{$image_rename}{$res_type}', '{$pid}', '{$date}', '{$position_all}', '{$user_info['user_id']}')");
								$ins_id = $db->insert_id();
								
								//Проверяем на наличии обложки у альбома, если нету то ставим обложку загруженную фотку
								if(!$row['cover'])
									$db->query("UPDATE `".PREFIX."_communities_albums` SET cover = '{$image_rename}{$res_type}' WHERE aid = '{$aid}'");

								$db->query("UPDATE `".PREFIX."_communities_albums` SET photo_num = photo_num+1, adate = '{$date}' WHERE aid = '{$aid}'");
								
								$img_url = $config['home_url'].'uploads/groups/'.$pid.'/albums/'.$aid.'/c_'.$image_rename.$res_type;
								
								//Результат для ответа
								echo $ins_id.'|||'.$img_url.'|||'.$pid;
									
								mozg_mass_clear_cache_file("user_{$user_info['user_id']}/position_photos_album_groups_{$aid}");
									
								$img_url = str_replace($config['home_url'], '/', $img_url);

							} else
								echo 'big_size';
						} else
							echo 'big_size';
					} else
						echo 'bad_format';
				} else
					echo 'max_img';
			} else
				echo 'hacking';
				
			die();
		break;
		
		//################### Удаление фотографии из альбома ###################//
		case "del_photo":
			NoAjaxQuery();
			$id = intval($_GET['id']);
			
			
			$row = $db->super_query("SELECT pid, album_id, photo_name, comm_num, position FROM `".PREFIX."_communities_photos` WHERE id = '{$id}'");
			$row_check = $db->super_query("SELECT admin FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
			$album_checkeds = $db->super_query("SELECT system FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
			
			//Если есть такая фотография и владельце действителен
			if(stripos($row_check['admin'], "id{$user_id}|") !== false){
			
			
				if($album_checkeds['system']) {
					$del_dir = ROOT_DIR.'/uploads/groups/'.$pid.'/';
					@unlink($del_dir.'50_'.$row['photo_name']);
					@unlink($del_dir.'100_'.$row['photo_name']);
					@unlink($del_dir.$row['photo_name']);
				} else {
					$del_dir = ROOT_DIR.'/uploads/groups/'.$pid.'/albums/'.$row['album_id'].'/';
					@unlink($del_dir.'c_'.$row['photo_name']);
					@unlink($del_dir.$row['photo_name']);
				}

				//Удаление фотки из БД
				$db->query("DELETE FROM `".PREFIX."_communities_photos` WHERE id = '{$id}'");
				
				$check_photo_album = $db->super_query("SELECT id FROM `".PREFIX."_communities_photos` WHERE album_id = '{$row['album_id']}'");
				$album_row = $db->super_query("SELECT cover, system, pid, ahash FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
				$album_systems = $db->super_query("SELECT photo FROM `".PREFIX."_communities` WHERE id = '{$album_row['pid']}'");
				
				//Если удаляемая фотография является обложкой то обновляем обложку на последнюю фотографию, если фотки еще есть из альбома
				if($album_row['cover'] == $row['photo_name'] AND $check_photo_album){
					$row_last_photo = $db->super_query("SELECT photo_name FROM `".PREFIX."_communities_photos` WHERE pid = '{$pid}' AND album_id = '{$row['album_id']}' ORDER by `id` DESC");
					$set_cover = ", cover = '{$row_last_photo['photo_name']}'";
				}
				
				if($album_systems['photo'] == $row['photo_name'] AND $album_row['system'] AND $check_photo_album) {
					$row_last_cover = $db->super_query("SELECT photo_name FROM `".PREFIX."_communities_photos` WHERE pid = '{$pid}' AND album_id = '{$row['album_id']}' ORDER by `position` DESC");
					$set_photo_cover = $row_last_cover['photo_name'];
				} else $set_photo_cover = '';
				
				if($album_systems['photo'] == $row['photo_name'] AND $album_row['system']) $db->query("UPDATE `".PREFIX."_communities` SET photo = '{$set_photo_cover}' WHERE id = '{$pid}'");
				
				//Если в альбоме уже нет фоток, то удаляем обложку
				if(!$check_photo_album)
					$set_cover = ", cover = ''";
					
				//Удаляем комментарии к фотографии
				$db->query("DELETE FROM `".PREFIX."_photos_comments` WHERE pid = '{$id}'");
				
				//Обновляем количество комментов у альбома
				$db->query("UPDATE `".PREFIX."_communities_albums` SET photo_num = photo_num-1, comm_num = comm_num-{$row['comm_num']} {$set_cover} WHERE aid = '{$row['album_id']}'");
				$db->query("DELETE FROM `".PREFIX."_communities_photos_comments` WHERE pid = '{$id}'");
				if($album_row['system']) {
					$album_photos = $db->super_query("SELECT photo_num FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
					if($album_photos['photo_num'] == 0) {
						@rmdir(ROOT_DIR.'/uploads/groups/'.$pid.'/albums/'.$row['album_id']);
						$db->query("DELETE FROM `".PREFIX."_communities_albums` WHERE ahash = '{$album_row['ahash']}'");
						$db->query("UPDATE `".PREFIX."_communities` SET albums_num = albums_num-1 WHERE id = '{$pid}'");
					}
				}
				
				mozg_mass_clear_cache_file("user_{$user_info['user_id']}/position_photos_album_groups_{$row['album_id']}");
				
				//Выводим и удаляем отметки если они есть
				$sql_mark = $db->super_query("SELECT muser_id FROM `".PREFIX."_communities_photos_mark` WHERE mphoto_id = '".$id."' AND mapprove = '0'", 1);
				if($sql_mark){
					foreach($sql_mark as $row_mark){
						$db->query("UPDATE `".PREFIX."_communities` SET new_mark_photos = new_mark_photos-1 WHERE id = '".$row_mark['muser_id']."'");
					}
				}
				$db->query("DELETE FROM `".PREFIX."_communities_photos_mark` WHERE mphoto_id = '".$id."'");
			}
			
			die();
		break;
		
		//################### Перемещение фотографии в другой альбом ###################//
		case "move_photo":
			NoAjaxQuery();
			$id = intval($_GET['id']);
			
			$from_album = intval($_GET['from_album']);
			
			$row = $db->super_query("SELECT pid, album_id, photo_name, comm_num, position FROM `".PREFIX."_communities_photos` WHERE id = '{$id}'");
			$row_ = $db->super_query("SELECT pid FROM `".PREFIX."_communities_albums` WHERE aid = '{$from_album}'");
			$row_check = $db->super_query("SELECT admin FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
			
			//Если есть такая фотография и владельце действителен
			if(stripos($row_check['admin'], "id{$user_id}|") !== false){
			
				//Директория фотографии
				$del_dir = ROOT_DIR.'/uploads/groups/'.$pid.'/albums/'.$row['album_id'].'/';
				$copy_dir = ROOT_DIR.'/uploads/groups/'.$pid.'/albums/'.$from_album.'/';
				
				// Перемещение фотографий
				@copy($del_dir.$row['photo_name'],$copy_dir.$row['photo_name']);
				@copy($del_dir.'c_'.$row['photo_name'],$copy_dir.'c_'.$row['photo_name']);
				
				//Удаление фотки с сервера
				@unlink($del_dir.'c_'.$row['photo_name']);
				@unlink($del_dir.$row['photo_name']);

				$db->query("UPDATE `".PREFIX."_communities_photos` SET album_id = '".$from_album."' WHERE id = '".$id."'");
				
				$check_photo_album = $db->super_query("SELECT id FROM `".PREFIX."_communities_photos` WHERE album_id = '{$row['album_id']}'");
				$album_row = $db->super_query("SELECT cover FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
				
				//Если удаляемая фотография является обложкой то обновляем обложку на последнюю фотографию, если фотки еще есть из альбома
				if($album_row['cover'] == $row['photo_name'] AND $check_photo_album){
					$row_last_photo = $db->super_query("SELECT photo_name FROM `".PREFIX."_communities_photos` WHERE pid = '{$pid}' AND album_id = '{$row['album_id']}' ORDER by `id` DESC");
					$set_cover = ", cover = '{$row_last_photo['photo_name']}'";
				}
				
				//Если в альбоме уже нет фоток, то удаляем обложку
				if(!$check_photo_album)
					$set_cover = ", cover = ''";
					
				$db->query("UPDATE `".PREFIX."_communities_photos_comments` SET album_id = '".$from_album."' WHERE pid = '".$id."'");
				
				//Обновляем количество комментов у альбома
				$db->query("UPDATE `".PREFIX."_communities_albums` SET photo_num = photo_num-1, comm_num = comm_num-{$row['comm_num']} {$set_cover} WHERE aid = '{$row['album_id']}'");
				$db->query("UPDATE `".PREFIX."_communities_albums` SET photo_num = photo_num+1, comm_num = comm_num+{$row['comm_num']} WHERE aid = '{$from_album}'");
					
				mozg_mass_clear_cache_file("user_{$user_info['user_id']}/position_photos_album_groups_{$row['album_id']}");
				mozg_mass_clear_cache_file("user_{$user_info['user_id']}/position_photos_album_groups_{$from_album}");
					
			}
			
			die();
		break;
		
		//################### Установка новой обложки для альбома ###################//
		case "set_cover":
			NoAjaxQuery();
			$id = intval($_GET['id']);
			
			
			//Выводи фотку из БД, если она есть
			$row = $db->super_query("SELECT album_id, photo_name FROM `".PREFIX."_communities_photos` WHERE id = '{$id}' AND pid = '{$pid}'");
			$system_albums = $db->super_query("SELECT aid FROM `".PREFIX."_communities_albums` WHERE system = '1' and pid = '{$pid}'");
			if($row AND $row['album_id'] != $system_albums['aid']){
				$db->query("UPDATE `".PREFIX."_communities_albums` SET cover = '{$row['photo_name']}' WHERE aid = '{$row['album_id']}'");
			}
			
			die();
		break;
		
		//################### Окно для перемещения фотки ###################//
		case "box_move_photo":
			NoAjaxQuery();
			$id = intval($_GET['aid']);
			$row_ = $db->super_query("SELECT aid,name FROM `".PREFIX."_communities_albums` WHERE pid = '{$pid}'",1);
			$row_d = $db->super_query("SELECT aid FROM `".PREFIX."_communities_albums` WHERE pid = '{$pid}' LIMIT 1");
			echo '<div class="load_photo_pad"><div class="err_red" style="display:none;font-weight:normal;"></div><div class="load_photo_quote">Для перемещения фотографии Вам нужно выбрать конечный альбом из списка ниже.</div><div class="mgclr"></div><div class="texta">Группа приложения:</div><div id="group_sel_w"><select id="change_move_box_album" onchange="Albums.ChangeMove();" class="inpst">';
			foreach($row_ as $group) {
				$var.= '<option value="'.$group['aid'].'">'.$group['name'].'</option>';
			}
			echo $var.'</select></div></div><input type="hidden" id="value_album" value="'.$row_d['aid'].'" />';
			echo '<div class="button_div fl_l" style="margin-bottom:15px;line-height:15px;margin-left: 20px;"><button onClick="Albums.MovingPhotos('.$pid.','.$id.'); return false" style="width:174px">Переместить фотографию</button></div>';
			die();
		break;
		
		//################### Сохранение описания к фотографии ###################//
		case "save_descr":
			NoAjaxQuery();
			$id = intval($_POST['id']);
			
			$descr = ajax_utf8(textFilter($_POST['descr']));
			
			//Выводим фотку из БД, если она есть
			$row = $db->super_query("SELECT id, album_id FROM `".PREFIX."_communities_photos` WHERE id = '{$id}' AND pid = '{$pid}'");
			$system_albums = $db->super_query("SELECT aid FROM `".PREFIX."_communities_albums` WHERE system = '1' and pid = '{$pid}'");
			if($row and $row['album_id'] != $system_albums['aid']){
				$db->query("UPDATE `".PREFIX."_communities_photos` SET descr = '{$descr}' WHERE id = '{$id}' AND pid = '{$pid}'");
				
				//Ответ скрипта
				echo stripslashes(myBr(htmlspecialchars(ajax_utf8(trim($_POST['descr'])))));
			}
			die();
		break;
		
		//################### Страница редактирование фотографии ###################//
		case "editphoto":
			NoAjaxQuery();
			$id = intval($_GET['id']);
			
			$row = $db->super_query("SELECT descr, system, album_id FROM `".PREFIX."_communities_photos` WHERE id = '{$id}' AND pid = '{$pid}'");
			$system_albums = $db->super_query("SELECT aid FROM `".PREFIX."_communities_albums` WHERE system = '1' and pid = '{$pid}'");
			if($row and $row['album_id'] != $system_albums['aid'])
				echo stripslashes(myBrRn($row['descr']));
			die();
		break;
		
		//################### Сохранение сортировки альбомов ###################//
		case "save_pos_albums";
			NoAjaxQuery();
			$array = $_POST['album'];
			$count = 1;
			
			//Если есть данные о масиве
			if($array){
				//Выводим масивом и обновляем порядок
				foreach($array as $idval){
					$idval = intval($idval);
					$db->query("UPDATE `".PREFIX."_communities_albums` SET position = ".$count." WHERE aid = '{$idval}' AND pid = '{$pid}'");
					$count++;	
				}
			}
			die();
		break;
		
		//################### Сохранение сортировки фотографий ###################//
		case "save_pos_photos";
			NoAjaxQuery();
			$array = $_POST['a_photo'];
			$count = 1;
			
			//Если есть данные о масиве
			if($array){
				//Выводим масивом и обновляем порядок
				$row = $db->super_query("SELECT album_id FROM `".PREFIX."_communities_photos` WHERE id = '{$array[1]}'");
				$albums = $db->super_query("SELECT system, pid FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
				if($albums['system']) $sum = 0;
				if($row){
					foreach($array as $idval){
						$idval = intval($idval);
						$db->query("UPDATE `".PREFIX."_communities_photos` SET position = '{$count}' WHERE id = '{$idval}' AND pid = '{$pid}'");
						$photo_info .= $count.'|'.$idval.'||';
						if(count($array) == $sum+1 AND $albums['system']) {
							$photo_name = $db->super_query("SELECT photo_name FROM `".PREFIX."_communities_photos` WHERE id = '{$idval}'");
							$db->query("UPDATE `".PREFIX."_communities_albums` SET cover = '{$photo_name['photo_name']}' WHERE aid = '{$row['album_id']}'");
							$db->query("UPDATE `".PREFIX."_communities` SET photo = '{$photo_name['photo_name']}' WHERE id = '{$albums['pid']}'");
						}
						$count ++; $sum ++;
					}
					mozg_create_cache('user_'.$user_info['user_id'].'/position_photos_album_groups_'.$row['album_id'], $photo_info);
				}
			}
			die();
		break;
		
		//################### Страница редактирование альбома ###################//
		case "edit_page";
			NoAjaxQuery();
			
			$id = $db->safesql(intval($_POST['id']));
			$row = $db->super_query("SELECT aid, name, descr, privated, system FROM `".PREFIX."_communities_albums` WHERE aid = '{$id}' AND pid = '{$pid}'");
			if($row AND $row['system'] != 1){
				$tpl->load_template('albums_groups/albums_edit.tpl');
				$tpl->set('{id}', $row['aid']);
				$tpl->set('{name}', stripslashes($row['name']));
				$tpl->set('{descr}', stripslashes(myBrRn($row['descr'])));
				if($row['privated']) $tpl->set('{checked-privated}', 'privated');
				else $tpl->set('{checked-privated}', 'none');
				$tpl->compile('content');
				AjaxTpl();
			}	
			die();
		break;
		
		//################### Сохранение настроек альбома ###################//
		case "save_album":
			NoAjaxQuery();
			$id = intval($_POST['id']);
			
			$name = ajax_utf8(textFilter($_POST['name'], false, true));
			$descr = ajax_utf8(textFilter($_POST['descr']));
			$privated = intval($_POST['privated']);
			
			if($privated<0 or $privated>1) $privated = 0;
			
			//Проверка на существование юзера
			$chekc_user = $db->super_query("SELECT aid, system FROM `".PREFIX."_communities_albums` WHERE aid = '{$id}' AND pid = '{$pid}'");
			if($chekc_user AND $chekc_user['system'] != 1){
				if(isset($name) AND !empty($name)){
					$db->query("UPDATE `".PREFIX."_communities_albums` SET name = '{$name}', descr = '{$descr}', privated = '{$privated}' WHERE aid = '{$id}'");
					echo stripslashes($name).'|#|||#row#|||#|'.stripslashes($descr);
				} else
					echo 'no_name';
			}
			die();
		break;

		//################### Страница изменения обложки ###################//
		case "edit_cover";
			NoAjaxQuery();
			
			
			$id = intval($_POST['id']);
			$system_albums = $db->super_query("SELECT aid FROM `".PREFIX."_communities_albums` WHERE system = '1' and pid = '{$pid}'");
			
			if($pid AND $id AND $system_albums['aid'] != $id){
				
				//Для навигатор
				if($_POST['page'] > 0) $page = intval($_POST['page']); else $page = 1;
				$gcount = 36;
				$limit_page = ($page-1)*$gcount;
				
				//Делаем SQL запрос на вывод
				$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS id, photo_name FROM `".PREFIX."_communities_photos` WHERE album_id = '{$id}' AND pid = '{$pid}' ORDER by `position` ASC LIMIT {$limit_page}, {$gcount}", 1);
				
				//Если есть SQL запрос то пропускаем
				if($sql_){
				
					//Выводим данные о альбоме (кол-во фотографй)
					$row_album = $db->super_query("SELECT photo_num FROM `".PREFIX."_communities_albums` WHERE aid = '{$id}' AND pid = '{$pid}'");
					
					$tpl->load_template('albums_groups/albums_editcover.tpl');
					$tpl->set('[top]', '');
					$tpl->set('[/top]', '');
					$tpl->set('{photo-num}', $row_album['photo_num'].' '.gram_record($row_album['photo_num'], 'photos'));
					$tpl->set_block("'\\[bottom\\](.*?)\\[/bottom\\]'si","");
					$tpl->compile('content');
					
					//Выводим масивом фотографии
					$tpl->load_template('albums_groups/albums_editcover_photo.tpl');
					foreach($sql_ as $row){
						$tpl->set('{photo}', $config['home_url'].'uploads/groups/'.$pid.'/albums/'.$id.'/'.$row['photo_name']);
						$tpl->set('{id}', $row['id']);
						$tpl->set('{aid}', $id);
						$tpl->compile('content');
					}
					box_navigation($gcount, $row_album['photo_num'], $id, 'AlbumsGroups.EditCover', '');
					
					$tpl->load_template('albums_groups/albums_editcover.tpl');
					$tpl->set('[bottom]', '');
					$tpl->set('[/bottom]', '');
					$tpl->set_block("'\\[top\\](.*?)\\[/top\\]'si","");
					$tpl->compile('content');
					
					AjaxTpl();
				} else
					echo $lang['no_photo_alnumx'];
			} else
				Hacking();
			
			die();
		break;
		
		//################### Удаление альбома ###################//
		case "del_album":
			NoAjaxQuery();
			$hash = $db->safesql(substr($_POST['hash'], 0, 32));
			$row = $db->super_query("SELECT aid, pid, photo_num, system FROM `".PREFIX."_communities_albums` WHERE ahash = '{$hash}'");
			
			if($row and !$row['system']){
				$aid = $row['aid'];
				$user_id = $row['pid'];
				
				//Удаляем альбом 
				$db->query("DELETE FROM `".PREFIX."_communities_albums` WHERE ahash = '{$hash}'");
				
				//Проверяем еслить ли фотки в альбоме
				if($row['photo_num']){
				
					//Удаляем фотки
					$db->query("DELETE FROM `".PREFIX."_communities_photos` WHERE album_id = '{$aid}'");
					
					//Удаляем комментарии к альбому
					$db->query("DELETE FROM `".PREFIX."_communities_photos_comments` WHERE album_id = '{$aid}'");
	
					//Удаляем фотки из папки на сервере
					$fdir = opendir(ROOT_DIR.'/uploads/groups/'.$user_id.'/albums/'.$aid);
					while($file = readdir($fdir))
						@unlink(ROOT_DIR.'/uploads/groups/'.$user_id.'/albums/'.$aid.'/'.$file);

					@rmdir(ROOT_DIR.'/uploads/groups/'.$user_id.'/albums/'.$aid);
				}
				
				//Обновлям кол-во альбом в юзера
				$db->query("UPDATE `".PREFIX."_communities` SET albums_num = albums_num-1 WHERE id = '{$user_id}'");
				
				mozg_clear_cache_file('user_'.$user_info['user_id'].'/position_photos_album_groups_'.$row['aid']);
				
			}
			
			die();
		break;
		
		//################### Ставим мне нравится ###################//
		case "wall_like_yes":
			NoAjaxQuery();
			$rec_id = intval($_POST['rec_id']);
			
			$row = $db->super_query("SELECT likes_users FROM `".PREFIX."_communities_photos_comments` WHERE id = '".$rec_id."'");
			if($row AND stripos($row['likes_users'], "id{$user_id}|") === false){
				$likes_users = "id{$user_id}|".$row['likes_users'];
				$db->query("UPDATE `".PREFIX."_communities_photos_comments` SET likes_num = likes_num+1, likes_users = '{$likes_users}' WHERE id = '".$rec_id."'");
				$db->query("INSERT INTO `".PREFIX."_communities_photos_comments_like` SET rec_id = '".$rec_id."', user_id = '".$user_id."', date = '".$server_time."'");
			}
			die();
		break;
		
		//################### Убираем мне нравится ###################//
		case "wall_like_remove":
			NoAjaxQuery();
			$rec_id = intval($_POST['rec_id']);
			
			$row = $db->super_query("SELECT likes_users FROM `".PREFIX."_communities_photos_comments` WHERE id = '".$rec_id."'");
			if(stripos($row['likes_users'], "id{$user_id}|") !== false){
				$likes_users = str_replace("id{$user_id}|", '', $row['likes_users']);
				$db->query("UPDATE `".PREFIX."_communities_photos_comments` SET likes_num = likes_num-1, likes_users = '{$likes_users}' WHERE id = '".$rec_id."'");
				$db->query("DELETE FROM `".PREFIX."_communities_photos_comments_like` WHERE rec_id = '".$rec_id."' AND user_id = '".$user_id."'");
			}
			die();
		break;
		
		//################### Выводим последних 7 юзеров кто поставил "Мне нравится" ###################//
		case "wall_like_users_five":
			NoAjaxQuery();
			$rec_id = intval($_POST['rec_id']);
			$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.user_id, tb2.user_photo FROM `".PREFIX."_communities_photos_comments_like` tb1, `".PREFIX."_users` tb2 WHERE tb1.user_id = tb2.user_id AND tb1.rec_id = '{$rec_id}' ORDER by `date` DESC LIMIT 0, 7", 1);
			if($sql_){
				foreach($sql_ as $row){
					if($row['user_photo']) $ava = '/uploads/users/'.$row['user_id'].'/50_'.$row['user_photo'];
					else $ava = '/templates/'.$config['temp'].'/images/no_ava_50.png';
					echo '<a href="/id'.$row['user_id'].'" id="Xlike_user'.$row['user_id'].'_'.$rec_id.'" onClick="Page.Go(this.href); return false"><img src="'.$ava.'" width="32" /></a>';
				}
			}
			die();
		break;
		
		//################### Выводим всех юзеров которые поставили "мне нравится" ###################//
		case "all_liked_users":
			NoAjaxQuery();
			$rid = intval($_POST['rid']);
			$liked_num = intval($_POST['liked_num']);

			if($_POST['page'] > 0) $page = intval($_POST['page']); else $page = 1;
			$gcount = 24;
			$limit_page = ($page-1)*$gcount;

			if(!$liked_num)
				$liked_num = 24;

			if($rid AND $liked_num){
				$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.user_id, tb2.user_photo, user_search_pref FROM `".PREFIX."_communities_photos_comments_like` tb1, `".PREFIX."_users` tb2 WHERE tb1.user_id = tb2.user_id AND tb1.rec_id = '{$rid}' ORDER by `date` DESC LIMIT {$limit_page}, {$gcount}", 1);

				if($sql_){
					$tpl->load_template('profile_subscription_box_top.tpl');
					$tpl->set('[top]', '');
					$tpl->set('[/top]', '');
					$tpl->set('{subcr-num}', 'Понравилось '.$liked_num.' '.gram_record($liked_num, 'like'));
					$tpl->set_block("'\\[bottom\\](.*?)\\[/bottom\\]'si","");
					$tpl->compile('content');

					$tpl->result['content'] = str_replace('Всего', '', $tpl->result['content']);

					$tpl->load_template('profile_friends.tpl');
					foreach($sql_ as $row){
						if($row['user_photo'])
							$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row['user_id'].'/50_'.$row['user_photo']);
						else
							$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
						$friend_info_online = explode(' ', $row['user_search_pref']);
						$tpl->set('{user-id}', $row['user_id']);
						$tpl->set('{name}', $friend_info_online[0]);
						$tpl->set('{last-name}', $friend_info_online[1]);
						$tpl->compile('content');
					}
					box_navigation($gcount, $liked_num, $rid, 'PhotoGroups.wall_all_liked_users', $liked_num);

					AjaxTpl();
				}
			}
			die();
		break;
		
		//################### Просмотр всех комментариев к альбому ###################//
		case "all_comments":
			
			$uid = intval($_GET['uid']);
			$aid = intval($_GET['aid']);
			
			if($aid) $uid = false;
			if($uid) $aid = false;

			if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
			$gcount = 25;
			$limit_page = ($page-1) * $gcount;

			//Если вызваны комменты к альбому
			if($aid AND !$uid){
				$row_album = $db->super_query("SELECT pid, name, comm_num, system FROM `".PREFIX."_communities_albums` WHERE aid = '{$aid}'");
				$uid = $row_album['pid'];
				if(!$uid)
					Hacking();
			}
			
			$CheckBlackList = $db->super_query("SELECT id FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$user_info['user_id']}' and pid = '{$pid}'");
			$row_check = $db->super_query("SELECT admin,title,adres FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
			
			//Приватность
			if(!$CheckBlackList){
				if($uid AND !$aid){
					$sql_tb3 = ", `".PREFIX."_albums` tb3";
					
					if(stripos($row_check['admin'], "id{$user_id}|") !== false){
						$privacy_sql = "";
						$sql_tb3 = "";
					}
				}
				
				//Если вызвана страница всех комментариев юзера, если нет, то значит вызвана страница оприделенго альбома
				if($uid AND !$aid)
					$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.user_id, text, date, id, hash, album_id, pid, owner_pid, photo_name, likes_users, likes_num, tb2.user_search_pref, user_photo, user_last_visit FROM `".PREFIX."_communities_photos_comments` tb1, `".PREFIX."_users` tb2 {$sql_tb3} WHERE tb1.owner_pid = '{$uid}' AND tb1.user_id = tb2.user_id {$privacy_sql} ORDER by `date` DESC LIMIT {$limit_page}, {$gcount}", 1);
				else
					$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS tb1.user_id, text, date, id, hash, album_id, pid, owner_pid, photo_name, likes_users, likes_num, tb2.user_search_pref, user_photo, user_last_visit FROM `".PREFIX."_communities_photos_comments` tb1, `".PREFIX."_users` tb2 WHERE tb1.album_id = '{$aid}' AND tb1.user_id = tb2.user_id ORDER by `date` DESC LIMIT {$limit_page}, {$gcount}", 1);
				
				//Если вызвана страница всех комментов
				if($uid AND !$aid){
					$user_speedbar = $lang['comm_form_album_all'];
					$metatags['title'] = $lang['comm_form_album_all'];
				} else {
					$user_speedbar = $lang['comm_form_album'];
					$metatags['title'] = $lang['comm_form_album'];
				}
				
				//Загружаем HEADER альбома
				$tpl->load_template('albums_groups/albums_top.tpl');
				$tpl->set('{user-id}', $uid);
				$tpl->set('{aid}', $aid);
				if($row_check['adres']) $alias = $row_check['adres'];
				else $alias = 'public'.$pid;
				if($row_album['comm_num']) $comments_num = $row_album['comm_num'].' '.gram_record($row_album['comm_num'], 'comments');
				else $comments_num = 'Комментариев пока нет';
				$tpl->set('{comments_num}', $comments_num);
				$tpl->set('{alias}', $alias);
				$tpl->set('{name}', $row_check['title']);
				$tpl->set('{album-name}', stripslashes($row_album['name']));
				$tpl->set('[comments]', '');
				$tpl->set('[/comments]', '');
				$tpl->set_block("'\\[all-albums\\](.*?)\\[/all-albums\\]'si","");
				$tpl->set_block("'\\[view\\](.*?)\\[/view\\]'si","");
				$tpl->set_block("'\\[editphotos\\](.*?)\\[/editphotos\\]'si","");
				$tpl->set_block("'\\[all-photos\\](.*?)\\[/all-photos\\]'si","");
				if($uid AND !$aid){
					$tpl->set_block("'\\[albums-comments\\](.*?)\\[/albums-comments\\]'si","");
				} else {
					$tpl->set('[albums-comments]', '');
					$tpl->set('[/albums-comments]', '');
					$tpl->set_block("'\\[comments\\](.*?)\\[/comments\\]'si","");
				}
				if(stripos($row_check['admin'], "id{$user_id}|") !== false){
					$tpl->set('[owner]', '');
					$tpl->set('[/owner]', '');
					$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
				} else {
					$tpl->set('[not-owner]', '');
					$tpl->set('[/not-owner]', '');
					$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
				}
				$tpl->compile('info');
				
				//Если есть ответ о запросе то выводим
				if($sql_){
		
					$tpl->load_template('albums_groups/albums_comment.tpl');
					foreach($sql_ as $row_comm){
						$tpl->set('{comment}', stripslashes($row_comm['text']));
						$tpl->set('{uid}', $row_comm['user_id']);
						$tpl->set('{id}', $row_comm['id']);
						$tpl->set('{hash}', $row_comm['hash']);
						$tpl->set('{pids}', $pid);
						$tpl->set('{author}', $row_comm['user_search_pref']);
						
						if($row_album['system']) $systems = $uid.'/100_';
						else $systems = $uid.'/albums/'.$row_comm['album_id'].'/c_';
						
						//Выводим данные о фотографии
						$tpl->set('{photo}', $config['home_url'].'uploads/groups/'.$systems.''.$row_comm['photo_name']);
						$tpl->set('{pid}', $row_comm['pid']);
						$tpl->set('{user-id}', $row_comm['owner_id']);
						
						if($aid){
							$tpl->set('{aid}', '_'.$aid);
							$tpl->set('{section}', 'album_comments');
						} else {
							$tpl->set('{aid}', '');
							$tpl->set('{section}', 'all_comments');
						}
						
						if($row_comm['user_photo'])
							$tpl->set('{ava}', $config['home_url'].'uploads/users/'.$row_comm['user_id'].'/50_'.$row_comm['user_photo']);
						else
							$tpl->set('{ava}', '{theme}/images/no_ava_50.png');
							
						megaDate(strtotime($row_comm['date']));
						
						if($row_comm['user_id'] == $user_info['user_id'] OR stripos($row_check['admin'], "id{$user_id}|") !== false){
							$tpl->set('[owner]', '');
							$tpl->set('[/owner]', '');
						} else 
							$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
							
						//Мне нравится
						if(stripos($row_comm['likes_users'], "id{$user_id}|") !== false){
							$tpl->set('{yes-like}', 'public_wall_like_yes');
							$tpl->set('{yes-like-color}', 'public_wall_like_yes_color');
							$tpl->set('{like-js-function}', 'PhotoGroups.wall_remove_like('.$row_comm['id'].', '.$user_id.')');
						} else {
							$tpl->set('{yes-like}', '');
							$tpl->set('{yes-like-color}', '');
							$tpl->set('{like-js-function}', 'PhotoGroups.wall_add_like('.$row_comm['id'].', '.$user_id.')');
						}
						
						if($row_comm['likes_num']){
							$tpl->set('{likes}', $row_comm['likes_num']);
							$tpl->set('{likes-text}', '<span id="like_text_num'.$row_comm['id'].'">'.$row_comm['likes_num'].'</span> '.gram_record($row_comm['likes_num'], 'like'));
						} else {
							$tpl->set('{likes}', '');
							$tpl->set('{likes-text}', '<span id="like_text_num'.$row_comm['id'].'">0</span> человеку');
						}
						
						//Выводим информцию о том кто смотрит страницу для себя
						$tpl->set('{viewer-id}', $user_id);
						if($user_info['user_photo'])
							$tpl->set('{viewer-ava}', '/uploads/users/'.$user_id.'/50_'.$user_info['user_photo']);
						else
							$tpl->set('{viewer-ava}', '{theme}/images/no_ava_50.png');

						$tpl->compile('content');
					}
					
					if($uid AND !$aid)
						if(stripos($row_check['admin'], "id{$user_id}|") !== false)
							$row_album = $db->super_query("SELECT SUM(comm_num) AS all_comm_num FROM `".PREFIX."_communities_albums` WHERE pid = '{$uid}'", false);
						else
							$row_album = $db->super_query("SELECT COUNT(*) AS all_comm_num FROM `".PREFIX."_communities_photos_comments` tb1, `".PREFIX."_albums` tb3 WHERE tb1.owner_pid = '{$uid}' {$privacy_sql}", false);
					else
						$row_album = $db->super_query("SELECT comm_num AS all_comm_num FROM `".PREFIX."_communities_albums` WHERE aid = '{$aid}'");
					
					if($uid AND !$aid)
						navigation($gcount, $row_album['all_comm_num'], $config['home_url'].'albums-'.$uid.'/comments/page/');
					else
						navigation($gcount, $row_album['all_comm_num'], $config['home_url'].'albums-'.$aid.'/comments/page/');
					
					$user_speedbar = $row_album['all_comm_num'].' '.gram_record($row_album['all_comm_num'], 'comments');
				} else
					msgbox('', '<br/><br/>Комментариев пока нет.<br/><br/>', 'info_2');
			} else {
				$user_speedbar = $lang['title_albums'];
				msgbox('', $lang['no_notes'], 'info');
			}
		break;
		
		//################### Просмотр альбома ###################//
		case "view":
			
			$aid = intval($_GET['aid']);

			if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
			$gcount = 25;
			$limit_page = ($page-1) * $gcount;

			//Выводим данные о фотках
			$sql_photos = $db->super_query("SELECT SQL_CALC_FOUND_ROWS id, photo_name FROM `".PREFIX."_communities_photos` WHERE album_id = '{$aid}' ORDER by `position` ASC", true);
			
			//Выводим данные о альбоме
			$row_album = $db->super_query("SELECT pid, name, photo_num, system, privated FROM `".PREFIX."_communities_albums` WHERE aid = '{$aid}'");
			
			//ЧС
			$CheckBlackList = $db->super_query("SELECT id FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$user_info['user_id']}' and pid = '{$pid}'");
			$row_check = $db->super_query("SELECT admin,title FROM `".PREFIX."_communities` WHERE id = '{$pid}'");
			
			if($row_album['privated']) {
				$admin_albums = $db->super_query("SELECT level FROM `".PREFIX."_communities_admins` WHERE pid = '{$row_album['pid']}' and user_id = '{$user_info['user_id']}'");
				if($admin_albums['level'] != 3) $privated_admin = true;
				else $privated_admin = false;
			} else $privated_admin = true;
			
			if(!$CheckBlackList){
				if(!$row_album)
					Hacking();
			
					//Выводим данные о владельце альбома(ов)
					$row_owner = $db->super_query("SELECT title,adres FROM `".PREFIX."_communities` WHERE id = '{$row_album['pid']}'");
					
					$tpl->load_template('albums_groups/albums_top.tpl');
					$tpl->set('{user-id}', $row_album['pid']);
					$tpl->set('{name}', $row_owner['title']);
					if($row_owner['adres']) $alias = $row_owner['adres'];
					else $alias = 'public'.$pid;
					$tpl->set('{alias}', $alias);
					$tpl->set('{aid}', $aid);
					$tpl->set('[view]', '');
					$tpl->set('[/view]', '');
					$tpl->set_block("'\\[all-albums\\](.*?)\\[/all-albums\\]'si","");
					$tpl->set_block("'\\[comments\\](.*?)\\[/comments\\]'si","");
					$tpl->set_block("'\\[editphotos\\](.*?)\\[/editphotos\\]'si","");
					$tpl->set_block("'\\[albums-comments\\](.*?)\\[/albums-comments\\]'si","");
					$tpl->set_block("'\\[all-photos\\](.*?)\\[/all-photos\\]'si","");
					if(stripos($row_check['admin'], "id{$user_id}|") !== false AND $privated_admin == true){
						if(!$row_album['system']) {
							$tpl->set('[owner]', '');
							$tpl->set('[/owner]', '');
							$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
						} else {
							$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
							$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
						}
					} else {
						$tpl->set('[not-owner]', '');
						$tpl->set('[/not-owner]', '');
						$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
					}
					$tpl->set('{album-name}', stripslashes($row_album['name']));
					$tpl->set('{all_p_num}', $row_album['photo_num']);
					$tpl->set('{photos_num}', $row_album['photo_num'].' '.gram_record($row_album['photo_num'], 'photos'));
					if($row_album['photo_num']) {
						$tpl->set('[photos_yes]', '');
						$tpl->set('[/photos_yes]', '');
					} else $tpl->set_block("'\\[photos_yes\\](.*?)\\[/photos_yes\\]'si","");
					$tpl->set('{count}', $limit_page);
					$tpl->compile('info');
					
					//Мета теги и формирование спидбара
					$metatags['title'] = stripslashes($row_album['name']).' | '.$row_album['photo_num'].' '.gram_record($row_album['photo_num'], 'photos');

					if($sql_photos){
						
						$tpl->result['content'] .= '<div id="dragndrop"><ul>';
						$tpl->load_template('albums_groups/album_photo.tpl');
						
						if($row_album['system']) $systems = $row_album['pid'].'/100_';
						else $systems = $row_album['pid'].'/albums/'.$aid.'/c_';
						
						foreach($sql_photos as $row){
							$tpl->set('{photo}', $config['home_url'].'uploads/groups/'.$systems.''.$row['photo_name']);
							$tpl->set('{id}', $row['id']);
							$tpl->set('{all}', '');
							$tpl->set('{uid}', $row_album['pid']);
							$tpl->set('{aid}', '_'.$aid);
							$tpl->set('{section}', '');
							if(stripos($row_check['admin'], "id{$user_id}|") !== false AND $privated_admin == true){
								if(!$row_album['system']) {
									$tpl->set('[owner]', '');
									$tpl->set('[/owner]', '');
									$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
								} else {
									$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
									$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
								}
							} else {
								$tpl->set('[not-owner]', '');
								$tpl->set('[/not-owner]', '');
								$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
							}
							$tpl->compile('content');
						}
						$tpl->result['content'] .= '</div></ul>';
						navigation($gcount, $row_album['photo_num'], $config['home_url'].'albums-'.$aid.'/page/');
					} else
						msgbox('', '<br /><br />В этом альбоме ещё нет фотографий<br /><br />', 'info_2');
						
					//Проверяем на наличии файла с позициям фоток
					$check_pos = mozg_cache('user_'.$row_album['user_id'].'/position_photos_album_groups_'.$aid);
					
					//Если нету, то вызываем функцию генерации
					if(!$check_pos)
						GenerateAlbumPhotosPositionGroups($row_album['user_id'], $aid);
				
			} else {
				$user_speedbar = $lang['title_albums'];
				msgbox('', $lang['no_notes'], 'info');
			}
		break;
		
		//################### Страница с новыми фотографиями ###################//
		case "new_photos":
			$rowMy = $db->super_query("SELECT user_new_mark_photos FROM `".PREFIX."_users` WHERE user_id = '".$user_info['user_id']."'");
			
			//Формирование тайтла браузера и спидбара
			$metatags['title'] = 'Новые фотографии со мной';
			$user_speedbar = 'Новые фотографии со мной';
			
			//Загрузка верхушки
			$tpl->load_template('albums_top_newphotos.tpl');
			$tpl->set('{user-id}', $user_info['user_id']);
			$tpl->set('{num}', $rowMy['user_new_mark_photos']);
			$tpl->compile('info');
			
			//Выводим сами фотографии
			if($_GET['page'] > 0) $page = intval($_GET['page']); else $page = 1;
			$gcount = 25;
			$limit_page = ($page-1) * $gcount;
			$sql_ = $db->super_query("SELECT tb1.mphoto_id, tb2.photo_name, album_id, user_id FROM `".PREFIX."_photos_mark` tb1, `".PREFIX."_photos` tb2 WHERE tb1.mphoto_id = tb2.id AND tb1.mapprove = 0 AND tb1.muser_id = '".$user_info['user_id']."' ORDER by `mdate` DESC LIMIT ".$limit_page.", ".$gcount, 1);
			$tpl->load_template('albums_top_newphoto.tpl');
			if($sql_){
				foreach($sql_ as $row){
					$tpl->set('{uid}', $row['user_id']);
					$tpl->set('{id}', $row['mphoto_id']);
					$tpl->set('{aid}', '_'.$row['album_id']);
					$tpl->set('{photo}', '/uploads/users/'.$row['user_id'].'/albums/'.$row['album_id'].'/c_'.$row['photo_name']);
					$tpl->compile('content');
				}
				$rowCount = $db->super_query("SELECT COUNT(*) AS cnt FROM `".PREFIX."_photos_mark` WHERE mapprove = 0 AND muser_id = '".$user_info['user_id']."'");
				navigation($gcount, $rowCount['cnt'], $config['home_url'].'albums/newphotos/');
			} else
				msgbox('', '<br /><br /><br />Отметок не найдено.<br /><br /><br />', 'info_2');
		break;
		
		default:
		
			//################### Просмотр всех альбомов юзера ###################//
			$uid = intval($_GET['uid']);
			
			$limit_select = 6; $limit_select_photos = 30;
			if($_POST['page_cnt'] > 0) $page_cnt = intval($_POST['page_cnt'])*$limit_select;
			else $page_cnt = 0;
			if($_POST['page_cnt_photos'] > 0) $page_cnt_photos = intval($_POST['page_cnt_photos'])*$limit_select_photos;
			else $page_cnt_photos = 0;
			
			//Выводим данные о владельце альбома(ов)
			$row_owner = $db->super_query("SELECT title, albums_num, new_mark_photos, adres, admin FROM `".PREFIX."_communities` WHERE id = '{$uid}'");
						
			if($row_owner){
				//ЧС
				
				if($row_owner['adres']) $alias = $row_owner['adres'];
				else $alias = 'public'.$pid;
				
				$check_blacklist = $db->super_query("SELECT id FROM `".PREFIX."_communities_blacklist` WHERE user_id = '{$user_info['user_id']}' and pid = '{$uid}'");
				if(!$check_blacklist){
					
					//Выводи данные о альбоме
					$sql_ = $db->super_query("SELECT SQL_CALC_FOUND_ROWS aid, name, adate, photo_num, descr, comm_num, cover, ahash, system FROM `".PREFIX."_communities_albums` WHERE pid = '{$uid}' ORDER by `position` ASC LIMIT {$page_cnt},6", 1);
						
					if(stripos($row_owner['admin'], "id{$user_info['user_id']}|") !== false) $public_admin = true;
					else $public_admin = false;
					
					//Если есть альбомы то выводи их
					if($sql_){
						$m_cnt = $row_owner['albums_num'];
						
						$metatags['title'] = 'Фотографии '.$row_owner['title'].' | '.$m_cnt.' '.gram_record($m_cnt, 'albums');
						
						$tpl->load_template('albums_groups/album.tpl');
		
						//Добавляем ID для DragNDrop jQuery
						if(!$_POST['page_cnt'] AND !$_POST['page_cnt_photos']) $tpl->result['content'] .= '<div id="dragndrop" style="padding-left: 9px;"><ul>';
						
						$tpl->set('{pid}', $uid);
						if(!$_POST['page_cnt_photos']) {
							foreach($sql_ as $row){
							
								if($public_admin == true){
									if(!$row['system']) {
										$tpl->set('[owner]', '');
										$tpl->set('[/owner]', '');
										$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
									} else {
										$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
										$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
									}
								} else {
									$tpl->set('[not-owner]', '');
									$tpl->set('[/not-owner]', '');
									$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
								}

								$tpl->set('{name}', stripslashes($row['name']));
								if($row['descr']) $tpl->set('{descr}', stripslashes($row['descr']));
								else $tpl->set('{descr}', '');
										
								$tpl->set('{photo-num}', $row['photo_num']);
								
								if(!$row['photo_num']) {
									$tpl->set('[yes-photos]', '');
									$tpl->set('[/yes-photos]', '');
								} else $tpl->set_block("'\\[yes-photos\\](.*?)\\[/yes-photos\\]'si","");
								
								if($row['descr']) {
									$tpl->set('[yesdescr]', '');
									$tpl->set('[/yesdescr]', '');
								} else $tpl->set_block("'\\[yesdescr\\](.*?)\\[/yesdescr\\]'si","");
									
								if($row['system']) $systems = $uid;
								else $systems = $uid.'/albums/'.$row['aid'];
									
								if($row['cover']) $tpl->set('{cover}', $config['home_url'].'uploads/groups/'.$systems.'/'.$row['cover']);
								else $tpl->set('{cover}', '{theme}/images/no_cover.png');
									
								$tpl->set('{pid}', $uid);
								$tpl->set('{aid}', $row['aid']);
								$tpl->set('{hash}', $row['ahash']);
									
								$tpl->compile('content');
								
							}
						}

						//Конец ID для DragNDrop jQuery
						if(!$_POST['page_cnt'] AND !$_POST['page_cnt_photos']) $tpl->result['content'] .= '</div></ul>';
						
						$row_owner['albums_num'] = $m_cnt;

						if($row_owner['albums_num']){
							if(!$_POST['page_cnt']) {
								$check_photos = $db->super_query("SELECT SUM(photo_num) as cnt FROM `".PREFIX."_communities_albums` WHERE pid = '{$uid}'");
								if(!$_POST['page_cnt_photos']) {
									$tpl->load_template('albums_groups/albums_top.tpl');
									$tpl->set('{user-id}', $uid);
									$tpl->set('{name}', $row_owner['title']);
									$tpl->set('{alias}', $alias);
									$tpl->set('{albums_num}', $row_owner['albums_num']);
									$tpl->set('{palbums_num}', gram_record($row_owner['albums_num'], 'albums'));
									$tpl->set('[all-albums]', '');
									$tpl->set('[/all-albums]', '');
									$tpl->set_block("'\\[view\\](.*?)\\[/view\\]'si","");
									$tpl->set_block("'\\[comments\\](.*?)\\[/comments\\]'si","");
									$tpl->set_block("'\\[editphotos\\](.*?)\\[/editphotos\\]'si","");
									$tpl->set_block("'\\[albums-comments\\](.*?)\\[/albums-comments\\]'si","");
									$tpl->set_block("'\\[all-photos\\](.*?)\\[/all-photos\\]'si","");
										
									if($public_admin == true){
										$tpl->set('[owner]', '');
										$tpl->set('[/owner]', '');
										$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
									} else {
										$tpl->set('[not-owner]', '');
										$tpl->set('[/not-owner]', '');
										$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
									}
										
									$tpl->set('[admin-drag]', '');
									$tpl->set('[/admin-drag]', '');
										
									if($row_owner['new_mark_photos']){
										$tpl->set('[new-photos]', '');
										$tpl->set('[/new-photos]', '');
										$tpl->set('{num}', $row_owner['new_mark_photos']);
									} else $tpl->set_block("'\\[new-photos\\](.*?)\\[/new-photos\\]'si","");
									
									$tpl->set('{count_photos}', $check_photos['cnt']);
										
									$tpl->compile('info');
									
									if($row_owner['albums_num']>6) {
										$tpl->load_template('albums_groups/albums_down.tpl');
										$tpl->set('{albums_num}', $row_owner['albums_num'].' '.gram_record($row_owner['albums_num'], 'albums'));
										$tpl->compile('content');
									}
								}
								
								
								if($check_photos['cnt']>=1 AND !$_POST['page_cnt']) {
									if(!$_POST['page_cnt_photos']) $tpl->result['content'] .= '<div class="summary_wrap" style="margin:0 20px;padding: 13px 0px 5px;"><b>Последние загруженные фотографии</b></div>';
									
									$sql_photos = $db->super_query("SELECT SQL_CALC_FOUND_ROWS id, photo_name, album_id FROM `".PREFIX."_communities_photos` WHERE pid = '{$uid}' and album_id != '0' ORDER by `date` DESC LIMIT {$page_cnt_photos},30", true);
									$tpl->load_template('albums_groups/album_photo.tpl');
									
									foreach($sql_photos as $row){
										$check_system = $db->super_query("SELECT system FROM `".PREFIX."_communities_albums` WHERE aid = '{$row['album_id']}'");
										if($check_system['system']) $systems = $uid.'/100_';
										else $systems = $uid.'/albums/'.$row['album_id'].'/';
										$tpl->set('{photo}', $config['home_url'].'uploads/groups/'.$systems.''.$row['photo_name']);
										$tpl->set('{id}', $row['id']);
										$tpl->set('{all}', '');
										$tpl->set('{uid}', $uid);
										$tpl->set('{aid}', '_'.$row['album_id']);
										$tpl->set('{section}', '');
										$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
										$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
										$tpl->compile('content');
									}
								}
							}
						} else msgbox('', $lang['no_albums'], 'info_2');
							
					} else {
					
						$metatags['title'] = 'Фотографии '.$row_owner['title'];
						
						$tpl->load_template('albums_groups/albums_info.tpl');

						$tpl->set('{alias}', $alias);
						$tpl->set('{pid}', $uid);
						
						if($public_admin == true){
							$tpl->set('[owner]', '');
							$tpl->set('[/owner]', '');
							$tpl->set_block("'\\[not-owner\\](.*?)\\[/not-owner\\]'si","");
						} else {
							$tpl->set('[not-owner]', '');
							$tpl->set('[/not-owner]', '');
							$tpl->set_block("'\\[owner\\](.*?)\\[/owner\\]'si","");
						}
						$tpl->compile('content');
					}
				} else {
					$user_speedbar = $lang['error'];
					msgbox('', $lang['no_notes'], 'info');
				}
				
				
			} else 
				Hacking();
	}
	$tpl->clear();
	$db->free($sql_);
} else {
	$user_speedbar = $lang['no_infooo'];
	msgbox('', $lang['not_logged'], 'info');
}
?>