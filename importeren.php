<style>
    .import-section{
        position: absolute;
    }
</style>

<?php

include('inc/header.php');

$status = 'failed';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

        if ($fileType != "csv") {
            echo "Sorry, only CSV files are allowed.";
        } else {
            $csvContent = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
            $csvRows = explode("\n", $csvContent);
            unset($csvRows[0]); 
            foreach ($csvRows as $row) {
                $columns = explode(";", $row);
                if (count($columns) !== 7) continue;
                $price = floatval(str_replace(",", ".", $columns[5]));

                $query = "SELECT `artikelnummer` FROM `Producten` WHERE omschrijving = '$columns[1]';";
                $result = mysqli_query($dbconn, $query);
                $data = mysqli_fetch_array($result);

                if ($data == null) {
                    $query = "INSERT INTO Producten (artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal)
                        VALUES ($columns[0], \"$columns[1]\", \"$columns[2]\", \"$columns[3]\", \"$columns[4]\", $price, $columns[6]);";
                    mysqli_query($dbconn, $query);
                } else {
                    $query = "
                        UPDATE Producten
                        SET aantal = aantal + $columns[6]
                        WHERE artikelnummer = $columns[0];
                    ";
                    mysqli_query($dbconn, $query);
                }
            }
            header("Refresh: 2; url=vooraad.php");
            $status = 'success';
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<div class="import-section">
    <h2>Upload nieuwe producten</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="fileToUpload">Selecteer een CSV-bestand:</label><br>
        <input type="file" name="fileToUpload"><br><br>
        <input type="submit" value="Uploaden">
    </form>
</div>

<?php
include "./inc/footer.php";
if ($status == "success") {
    echo '<p class="success-message">De nieuwe voorraad is geüpload naar de database! Je wordt nu binnen een paar seconden doorgestuurd!</p>';
}
?>
