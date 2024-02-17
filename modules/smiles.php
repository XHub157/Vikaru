<?php

/**
 * @package   Zcore
 * @author     Artem Sokolovsky
 * @url           http://vk.com/x_s_s
 */


// Инклудим ядро

include_once ($_SERVER['DOCUMENT_ROOT']."/lite/core.php"); 


// Выводим шапку

    $title = 'Смайлы';

// Инклудим шапку

include_once (ROOT.'template/head.php');


if (isset($_GET['act'])){$act = $system->check($_GET['act']);}else{$act = 'index';} 
if (isset($_GET['start'])){$start = abs(intval($_GET['start']));}else{$start = 0;} 

switch ($act):
case 'index':
$config['post'] = 20;
$all = DB :: $dbh -> querySingle("SELECT count(*) FROM `smiles_dir`;");
if ($all > 0){
if ($start >= $all) {
$start = 0;
} 
$q = DB :: $dbh -> query("SELECT * FROM `smiles_dir` ORDER BY `pos` ASC LIMIT " . $start . ", " . $config['post'] . ";");
while ($act = $q -> fetch()) {
echo '<div class="block"><img src="/icons/files.png" alt="!"/> <a href="smiles.php?act=dir&id='.$act['id'].'">'.$act['name'].'</a></div>';
}
$page = new page;
$page->pages('smiles.php?', $config['post'], $start, $all);
} else {
echo'Base Error!';
} 
break;


case "dir":
$id =intval($_GET['id']);
echo "<div class=block>";
$all = DB :: $dbh -> querySingle("SELECT count(*) FROM `smiles` WHERE `dir`=?;", array($id));
if ($all > 0){
if ($start >= $all) {
$start = 0;
} 
$i =0;
$q = DB :: $dbh -> query("SELECT * FROM `smiles` WHERE `dir`=? ORDER BY `id` DESC LIMIT " . $start . ", " . $config['post'] . ";", array($id));
while ($act = $q -> fetch()) {
echo '<img src="/lite/chache/smiles/'.$act['name'].'.gif" alt="!"/> '.$act['sim'].'<br />';
++$i;
echo  ($i != $all) ? '<hr>' : '';
}
echo "</div>";
$page = new page;
$page->pages('smiles.php?act=dir&amp;id='.$id.'&', $config['post'], $start, $all);
} else {
echo'Base Error!';
} 
break;
default:

header("location: smiles.php?");
exit;
endswitch;

// Инклудим ноги

include_once (ROOT.'template/foot.php');

?>