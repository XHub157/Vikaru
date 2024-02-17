<?php  
class Smiles{ 
     private $smiles = array(); 
     private $array; 
     private $smiles_dir; 
     public $total = 0; 
     public $init = false; 
/* читаем конфиг */ 
function __construct($dir){
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$dir.'/smiles.dat')){ 
$this->array = file($_SERVER['DOCUMENT_ROOT'].'/'.$dir.'/smiles.dat'); 
$this->similes_dir = $dir; 
} 
   } 
/* создаем массив со смайлами, 
только при первом реплейсе, 
что бы не нагружать систему лишними циклами */ 
function read(){
   foreach($this->array as $sm){ 
$datas = explode('||',$sm); 
      foreach($datas as $l=>$s){ 
         if($l!=0 AND $s!=""){ 
$this->smiles[$s] = '<img src="/core/chache/smiles/'.$datas[0].'" alt="" />';
         } 
      } 
unset($sm); 
   } 
$this->total = count($this->smiles); 
unset($this->array); 
$this->init = true; 
   } 
/* печатаем смайлы */ 
   function replace($message){ 
if(!$this->init){ 
$this->read(); // если массив смайлов не создан то создаем 
} 
if($this->total>1 AND $message!=""){ 
return strtr($message,$this->smiles); 
}else{ 
return $message; 
} 
} 
} 
?>