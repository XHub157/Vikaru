<?php


/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */

 
// Стартуем SESSION

    session_start();

// Путь корневого каталога

    define("ROOT", $_SERVER["DOCUMENT_ROOT"].'/');

// Путь корневого каталога

    define("SERVER", 'C:/OpenServer/domains/localhost/lite/chache');

// Путь корневого каталога Файлового сервера 

    define("SERVER_DOMAIN", 'localhost/lite/chache');

// Домен

    define("DOMAIN", 'localhost');

// Обработка навигации

    if (isset($_GET['page'])) { $page = abs(intval($_GET['page'])); } else { $page = 0; } 

// Обработка переменной $id

    if (isset($_GET['id'])) { $id = abs(intval($_GET['id'])); } else { $id = 0; }

// Обработка переменной $add

    if (isset($_GET['add'])) { $add = '?add='.abs(intval($_GET['add'])); } else { $add = ''; }

/* Подключение к базе данных */

    define ('DBHOST', 'localhost'); // Хост
    define ('DBPORT', '3306'); // Порт
    define ('DBNAME', 'qzeez');
    define ('DBUSER', 'root');
    define ('DBPASS', ''); 

// ~~~~~~~~~~~~~~~~~~~~~~ Классы для работы PDO ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

    class PDO_ extends PDO {
    function __construct($dsn, $username, $password) {
    parent :: __construct($dsn, $username, $password);
    $this -> setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
    $this -> setAttribute(PDO :: ATTR_DEFAULT_FETCH_MODE, PDO :: FETCH_ASSOC);
    } 

    function prepare($sql) {
    $stmt = parent :: prepare($sql, array(
    PDO :: ATTR_STATEMENT_CLASS => array('PDOStatement_')
    )); 
    return $stmt; 
    }  
 
    function query($sql, $params = array()) {
    $stmt = $this -> prepare($sql); 
    $stmt -> execute($params); 
    return $stmt; 
    }  
 
    function querySingle($sql, $params = array()) { 
    $stmt = $this -> query($sql, $params); 
    $stmt -> execute($params); 
    return $stmt -> fetchColumn(0); 
    }  
 
    function queryFetch($sql, $params = array()) { 
    $stmt = $this -> query($sql, $params); 
    $stmt -> execute($params); 
    return $stmt -> fetch(); 
    }  
    }  

// ~~~~~~~~~~~~~~~~~~ Классы для работы PDOStatement ~~~~~~~~~~~~~~~~~~~~~~~~~~~ //	
	
    class PDOStatement_ extends PDOStatement { 
    function execute($params = array()) { 

    if (func_num_args() == 1) { 
    $params = func_get_arg(0); 
    } else { 
    $params = func_get_args(); 
    }  

    if (!is_array($params)) { 
    $params = array($params); 
    }  
    parent :: execute($params); 
    return $this; 
    }  
 
    function fetchSingle() { 
    return $this -> fetchColumn(0); 
    }  
 
    function fetchAssoc() { 
    $this -> setFetchMode(PDO :: FETCH_NUM); 
    $data = array(); 
    while ($row = $this -> fetch()) { 
    $data[$row[0]] = $row[1]; 
    }  
    return $data; 
    }  
    }  

// ~~~~~~~~~~~~~~~~~~~~ Классы для работы DB ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //		
	
    class DB { 

    static $dbh; 
 
    public function __construct() { 
    try { 
    self :: $dbh = new PDO_('mysql:host=' . DBHOST . ';port=' . DBPORT . ';dbname=' . DBNAME, DBUSER, DBPASS); 
    self :: $dbh -> exec('SET CHARACTER SET utf8'); 
    self :: $dbh -> exec('SET NAMES utf8'); 
    }  

    catch (PDOException $e) { 
    die('К сожалению, не доступен сервер MySQL.</br>Проверьте правильность данных в файле <b>/lite/core.php</b>.');} }  
 
    final public function __destruct() { 
    self :: $dbh = null; 
    }  
    }  
    $database = new DB(); 	

// ~~~~~~~~~~~~~~~~~~~~ Автоматическая загрузка классов ~~~~~~~~~~~~~~~~~~~~~~~ // 

    function Autoload($class_name) { 
    @include_once (ROOT.'lite/classes/'.strtolower($class_name).'.class.php'); 
    } 
    spl_autoload_register('Autoload'); 

// ~~~~~~~~~~~~~~~~~~~~ Стартуем наши классы ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

    $system = new system; 
    $profile = new profile; 

// ~~~~~~~~~~~~~~~~~~~~ Подключаем настройки системы ~~~~~~~~~~~~~~~~~~~~~~~~ //
	
    $queryguest = DB :: $dbh -> query("SELECT `name`, `value` FROM `config`;"); 
    while ($row = $queryguest -> fetch()) { 
    $config[$row['name']] = $row['value']; }  

// ~~~~~~~~~~~~~~~~~~~~ Авторизация Cookie ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

    if (isset($_COOKIE['sid'])) { 

// Обработка sid

    $sid = $system->check($_COOKIE['sid']);

// Проверяем существует ли такой пользователь

    $user = DB :: $dbh -> queryFetch("SELECT * FROM `user` WHERE `sid`=? LIMIT 1;", array($sid));

// Если авторизация прошла пишем информацию в базу

    if ($user['sid'] == $sid) { 
	
// Проверяем статус

    $aut = ($user['hide'] > time()) ? 0 : time();

// Время онлайн

    $user_online = time() - $user['date_aut'];
    $online = ($user_online < 60) ? $user['online'] + $user_online : $user['online'];

// Обновляем данные		

    DB :: $dbh -> query("UPDATE `user` SET `date_aut`=?, `aut`=?, `online`=? WHERE `id`=? LIMIT 1;", array(time(), $aut, $online, $user['id'])); 

// Постраничная навигация пользователя
	
    if ($user['post'] > 0){ $config['post'] = $user['post']; }   

// Если авторизация не удалась удаляем данные из куков

    } else {
    setcookie('sid');
    header('location: /index.html');
    
   }}
   
?>