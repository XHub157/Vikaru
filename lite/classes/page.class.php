<?


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


class page{

    var $total;          // количество страниц
    var $link;           // количество страниц
    var $posts = 10; // количество пунктов на одну страницу
    var $start;          // текущая страница
	
    public function pages($link, $posts, $start, $total, $koll = 3) {
    if ($total > $posts) {
    echo'<div class="navigation">';
        $ba = ceil($total / $posts);
    $ba2 = $ba * $posts - $posts;
    $min = $start - $posts * ($koll - 1);
    $max = $start + $posts * $koll;
    if ($min < $total && $min > 0) {
    if ($min - $posts > 0) {
    
    echo '<a class="page" href="' . $link . 'start=0">1</a> ... ';
    } else {
   
    echo '<a class="page" href="' . $link . 'start=0">1</a> ';
   
    } 
    } 
    for($i = $min; $i < $max;) {
    if ($i < $total && $i >= 0) {
    $ii = floor(1 + $i / $posts);
    if ($start == $i) {
    echo '<a class="page"><b>' . $ii . '</b></a> ';
    } else {
   
    echo ' <a class="page" href="' . $link . 'start=' . $i . '">' . $ii . '</a> ';
   
    }} 
    $i += $posts;
    } 
    if ($max < $total) {
    if ($max + $posts < $total) {
    echo ' ... <a class="page" href="' . $link . 'start=' . $ba2 . '">' . $ba . '</a> ';
    
    } else {
    
    echo '<a class="page" href="' . $link . 'start=' . $ba2 . '">' . $ba . '</a> ';
   
    }} 
    echo '</div>';
    }
    }
	
} 

?>