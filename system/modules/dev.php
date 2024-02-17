<?php

if(!defined('MOZG'))
    die('Hacking attempt!');

if($ajax == 'yes')
    NoAjaxQuery();
		
		if($logged){
		$id = $lang['id'];
		
		$tpl->load_template('page/dev.tpl');
		$row = $db->super_query("SELECT id, text, author, date FROM `".PREFIX."_dev_news` WHERE id = '{$id}' ORDER by `id` DESC LIMIT 0, 10");
		$tpl->set('{news}', ''.$row['text'].'');
		$tpl->set('{author}', ''.$row['author'].'');
		$tpl->set('{date}', ''.$row['date'].'');
		$tpl->compile('content');
		
		}

    
    $tpl->clear();
    $db->free();

?>