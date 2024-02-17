<?php
define ("DBHOST", "localhost");  // Mysql Host (обычно localhost)
define ("DBNAME", "scvk"); 		 // Имя базы данных
define ("DBUSER", "root");	 	// Пользователь базы данных
define ("DBPASS", ""); 	 // Пароль
define ("PREFIX", "vii"); 		 // Префикс желательно не менять
define ("COLLATE", "cp1251"); 	 // Кодировка (не менять)
$db = new db;					 // Для дальнейших использований (не менять)
?>