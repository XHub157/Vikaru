<?php

// Генерация

    $end_time = microtime(true); 

    
    echo '</div></body></html>
    <div class="zcore"><a href="http://vk.com/x_s_s">Zcore System</a> /  '.round(($end_time - $start_time),5).' сек</div>';
 
    ob_end_flush();

// Удаляем блок уведомлений

    if (isset($_SESSION['show'])) { unset($_SESSION['show']); }  

?>