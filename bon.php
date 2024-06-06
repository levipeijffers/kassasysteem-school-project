<?php
include("inc/header.php");

// Functie om de totale prijs van de producten te berekenen
function calculateTotalPrice($cart) {
    $totalPrice = 0;
    foreach ($cart as $product) {
        $totalPrice += $product['prijs'];
    }
    return $totalPrice;
}

// Functie om de totale btw van de producten te berekenen
function calculateTotalVAT($cart) {
    $totalVAT = 0;
    foreach ($cart as $product) {
        // Btw-tarief van 9%
        $vatRate = 0.09;
        $vatAmount = $product['prijs'] * $vatRate;
        $totalVAT += $vatAmount;
    }
    return $totalVAT;
}

// Functie om de voorraad van een product bij te werken na aankoop
function updateProductStock($barcode, $quantity) {
    global $dbconn;
    $query = "UPDATE producten SET aantal = aantal - $quantity WHERE artikelnummer = '$barcode'";
    mysqli_query($dbconn, $query);
}

session_start(); // Start de sessie om toegang te krijgen tot $_SESSION

// Controleer of $_SESSION['winkelwagen'] is ingesteld
if (isset($_SESSION['winkelwagen']) && is_array($_SESSION['winkelwagen'])) {
    // Bereken de totale prijs en btw
    $totalPrice = calculateTotalPrice($_SESSION['winkelwagen']);
    $totalVAT = calculateTotalVAT($_SESSION['winkelwagen']);
} else {
    // Als de winkelwagen leeg is, stel de totale prijs en btw in op 0
    $totalPrice = 0;
    $totalVAT = 0;
}

?>

<!-- Header -->
<?php include("inc/header.php") ?>

<!-- Bonoverzicht -->
<section id="bon-overzicht">
    <h1>Bon</h1>
    <ul>
        <?php if (isset($_SESSION['winkelwagen']) && is_array($_SESSION['winkelwagen'])): ?>
            <?php foreach ($_SESSION['winkelwagen'] as $product): ?>
                <li><?php echo $product['omschrijving']; ?> - €<?php echo number_format($product['prijs'], 2); ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Geen producten toegevoegd aan de winkelwagen.</li>
        <?php endif; ?>
    </ul>
    <p>Totaalprijs: €<?php echo number_format($totalPrice, 2); ?></p>
    <p>Btw (9%): €<?php echo number_format($totalVAT, 2); ?></p>
    <p>Totaal inclusief btw: €<?php echo number_format($totalPrice + $totalVAT, 2); ?></p>
<br>
<br>
<h3><a href="index.php">Terug naar winkelen</a></h3>
</section>

<?php
// Update de voorraad van elk product in de winkelwagen
if (isset($_SESSION['winkelwagen']) && is_array($_SESSION['winkelwagen'])) {
    foreach ($_SESSION['winkelwagen'] as $product) {
        updateProductStock($product['artikelnummer'], 1); // Verminder de voorraad met 1 per verkocht product
    }
    // Verwijder de winkelwagen na het bijwerken van de voorraad
    unset($_SESSION['winkelwagen']);
}

// Footer
include("inc/footer.php");
?>

</body>
</html>
