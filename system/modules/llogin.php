<?php

if(!defined('MOZG'))
    die('Hacking attempt!');

if($ajax == 'yes')
    NoAjaxQuery();
	
	$tpl->load_template('llogin/login.tpl');
    $tpl->compile('content');
	
    $tpl->clear();
    $db->free();
?>