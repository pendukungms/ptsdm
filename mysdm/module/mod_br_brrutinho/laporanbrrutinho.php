<?php
    $piprint="";
    if (isset($_GET['iprint'])) $piprint=$_GET['iprint'];
    
    if ($piprint=="print") {
        include "pritnbrrutinho.php";
    }elseif ($piprint=="lihatgambar") {
        include "module/mod_br_brrutinho/lihatgambarho.php";
    }
?>

