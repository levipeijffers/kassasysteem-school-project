<style>

</style>
<?php
define('RECORDS_PER_PAGE', 4); // Definieer als constante

require_once 'inc/database.php';

include 'inc/header.php';

echo '<header class="head">';
echo '</header>';

echo '<main class="main-content">';
?>

<table id="inventory">
    <tr>
        <th>Artikelnummer</th>
        <th>Omschrijving</th>
        <th>Leverancier</th>
        <th>Artikelgroep</th>
        <th>Eenheid</th>
        <th>Prijs</th>
        <th>Aantal</th>
    </tr>

    <?php
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }

    $start_from = ($page - 1) * RECORDS_PER_PAGE;

    $sql_count = "SELECT count(artikelnummer) as aantal FROM Producten;";
    $res_count = mysqli_query($dbconn, $sql_count);
    $row = mysqli_fetch_assoc($res_count);
    $total_rows = $row['aantal'];
    $total_pages = ceil($total_rows / RECORDS_PER_PAGE);

    // Check of er een startwaarde is opgegeven in de URL en pas de $start_from variabele aan
    if(isset($_GET['start']) && is_numeric($_GET['start'])) {
        $start_from = intval($_GET['start']);
    } else {
        $start_from = 0; // Standaardwaarde als er geen startwaarde is opgegeven
    }

    $query = "SELECT artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal FROM Producten
                ORDER BY artikelnummer
                LIMIT " . $start_from . "," . RECORDS_PER_PAGE . ";";

    $result = mysqli_query($dbconn, $query);
    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['artikelnummer'] . "</td>";
            echo "<td>" . $row['omschrijving'] . "</td>";
            echo "<td>" . $row['leverancier'] . "</td>";
            echo "<td>" . $row['artikelgroep'] . "</td>";
            echo "<td>" . $row['eenheid'] . "</td>";
            echo "<td>" . $row['prijs'] . "</td>";
            echo "<td>" . $row['aantal'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>
                <td colspan='7'>Geen gegevens om op te halen...</td>
            </tr>";
    }

    echo "</table><br>";

    // Include paginering.php buiten de hoofdcontentsectie
    echo '<div class="pagination">';
    $page_url = "vooraad.php?page"; // 
    
    // Bereken de startindex voor de huidige pagina in de paginering links
    $start_from = ($page - 1) * RECORDS_PER_PAGE;
    $page_url .= "&start=$start_from";

    include_once ('inc/paginering.php');
    echo "</div>";

    echo "</main>"; // Sluit hoofdcontentsectie

    include('inc/footer.php');
?>

</body>
</html>
