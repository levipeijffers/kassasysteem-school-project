<?php
include("inc/header.php");
session_destroy();
session_unset();
header('refresh: 1; url=login.php');
include ("inc/footer.php");
?>
 