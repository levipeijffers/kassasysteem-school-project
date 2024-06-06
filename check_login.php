<?php

$file=str_replace(dirname($_SERVER['PHP_SELF']).'/','',$_SERVER['PHP_SELF'] );


//inlog checken
$ingelogd=isset($_SESSION['ingelogd']) ? $_SESSION['ingelogd'] : false ;
if (($file!='login.php') AND ($file!='authorisatie.php'))  {
    if (!$ingelogd) {
        session_destroy();
        header("location: login.php");
        exit;
    }
}
?>