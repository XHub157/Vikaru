<?


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


class navigation {

    var $total;          // количество страниц
    var $link;           // количество страниц
    var $posts = 10;     // количество пунктов на одну страницу
    var $start;          // текущая страница
	
public function pages($link, $posts, $start, $total, $koll = 3) {

    if ($total > $posts) {
	
    echo '

    <div class="navigation">';
	
    $ba = ceil($total / $posts); $ba2 = $ba * $posts - $posts; $min = $start - $posts * ($koll - 1); $max = $start + $posts * $koll;
	
    if ($min < $total && $min > 0) { 
    echo ''.($min - $posts > 0 ? '
    <a class="page" href="' . $link . 'page=0">1</a> ... ' : '
    <a class="page" href="' . $link . 'page=0">1</a>').'';
    } 
	
    for ($i = $min; $i < $max;) { 
    if ($i < $total && $i >= 0) {
    $ii = floor(1 + $i / $posts);
    echo ''.($start == $i ? '
    <a class="page"><span style="font-weight: bold; color: #686868;">' . $ii . '</span></a>' : '
    <a class="page" href="' . $link . 'page=' . $i . '">' . $ii . '</a>').'';	
    } $i += $posts; } 
	
    if ($max < $total) { 
    echo ''.($max + $posts < $total ? '
    ... <a class="page" href="' . $link . 'page=' . $ba2 . '">' . $ba . '</a>' : '
    <a class="page" href="' . $link . 'page=' . $ba2 . '">' . $ba . '</a>').'';	
    } 
	
    echo '
    </div>
    ';
	
    }
	
}
	
} 

?>