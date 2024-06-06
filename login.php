<?php


include("inc/header.php");

// Databaseverbinding
require_once("inc/database.php");



?>

<form method="post" action="authorisatie.php">
    <label for="username">Username</label><br>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Submit">
</form>

<?php

include("inc/footer.php");
?>
