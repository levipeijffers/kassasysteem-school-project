<?php
session_start(); // Start the session

include("inc/header.php");
//error_reporting(0);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $inlognaam = $_POST['username'];
    $wachtwoord = $_POST['password'];

    // Establish database connection - assuming $dbconn is defined elsewhere
    // Include proper error handling for database connection
    if (!$dbconn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute SQL query with parameters to prevent SQL injection
    $query = "SELECT gebruiker.id, gebruiker.inlognaam, gebruiker.wachtwoord, rol.naam FROM gebruiker
            INNER JOIN rol ON gebruiker.rol_id=rol.id
          WHERE inlognaam=? AND wachtwoord=?";
    $stmt = mysqli_prepare($dbconn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $inlognaam, $wachtwoord);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the result
    $aantal = mysqli_num_rows($result);
    if ($aantal == 1) {
        while ($row = mysqli_fetch_array($result)) {
            $rol = $row['naam'];
        }
        $_SESSION['inlognaam'] = $inlognaam;
        $_SESSION['wachtwoord'] = $wachtwoord;
        $_SESSION['rol'] = $rol;
        $_SESSION['ingelogd'] = true;
        header('Location: index.php');
        exit;
    } else {
        echo 'Helaas, uw inlognaam en/of wachtwoord corresponderen niet met onze gegevens. U wordt doorgestuurd...<br>';
        session_destroy();
        session_unset();
        // Close the database connection
        mysqli_close($dbconn);
        header('refresh: 1; url=login.php');
        exit;
    }
} else {
    header('Location: login.php'); // Redirect if form not submitted
    exit;
}

include("inc/footer.php");
?>