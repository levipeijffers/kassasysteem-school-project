<?php
define('HOST', 'localhost'); 
define('DATABASE', 'kassasysteem'); 
define('USER', 'root');
define('PASSWORD', '');


try {
    $dbconn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
} catch (Exception $e) {
    $dbconn = $e;
}
function fnCloseDb($conn) {
    if (!$conn == false) {
        mysqli_close($conn) or die('Sluiten MySQL-db niet gelukt...');
    }
}
?>
