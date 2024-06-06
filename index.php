<style>
    form button {
    border: none;
    outline: none;
}


</style>
<?php
include("inc/header.php");

// Functie om het product inclusief btw te berekenen
function getProductPriceWithVAT($price) {
    // Btw-tarief van 9%
    $vatRate = 0.09;
    // Bereken de prijs inclusief btw
    return $price * (1 + $vatRate);
}

function getProductByDescription($description) {
    global $dbconn;
    $query = "SELECT * FROM producten WHERE omschrijving LIKE '%$description%'";
    $result = mysqli_query($dbconn, $query);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function getProductByBarcode($barcode) {
    global $dbconn;
    $query = "SELECT * FROM producten WHERE artikelnummer = '$barcode'";
    $result = mysqli_query($dbconn, $query);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function removeFromCart($index) {
    if (isset($_SESSION['winkelwagen'][$index])) {
        unset($_SESSION['winkelwagen'][$index]);
    }
}

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $product = getProductByDescription($description);
        if ($product) {
            // Voeg het nieuwe product toe aan de bestaande winkelwagen-array
            if (!isset($_SESSION['winkelwagen'])) {
                $_SESSION['winkelwagen'] = array();
            }
            // Bereken de prijs inclusief btw en voeg het product toe
            $product['prijs'] = getProductPriceWithVAT($product['prijs']);
            $_SESSION['winkelwagen'][] = $product;
        }
        header('refresh: 0; url=index.php');
    } else if (isset($_POST['barcode'])) {
        $barcode = $_POST['barcode'];
        $product = getProductByBarcode($barcode);
        if ($product) {
            // Voeg het nieuwe product toe aan de bestaande winkelwagen-array
            if (!isset($_SESSION['winkelwagen'])) {
                $_SESSION['winkelwagen'] = array();
            }
            // Bereken de prijs inclusief btw en voeg het product toe
            $product['prijs'] = getProductPriceWithVAT($product['prijs']);
            $_SESSION['winkelwagen'][] = $product;
        }
        header('refresh: 0; url=index.php');

    } else if (isset($_POST['remove_item'])) {
        if (isset($_POST['remove_index'])) {
            $index = $_POST['remove_index'];
            removeFromCart($index);
        }
    }
}
?>


<!-- Header -->
<?php include("inc/header.php") ?>

<!-- Zoekbalk -->
<section id="search-section">
    <h1>Zoek product</h1>
    <div id="search-container">
        <form id="search-form" method="POST" action="">
            <input id="search-input" type="text" name="description" placeholder="Zoek product...">
            <button type="submit" id="search-button">Zoek</button>
        </form>
    </div>
    <div id="search-results">
        <!-- Toon hier de zoekresultaten -->
    </div>
</section>

<!-- Calculator -->
<div id="calcu">
    <form method="POST" action="index.php" id="calculator-form">
        <input type="text" name="barcode" id="result" readonly>
        <div>
            <input type="button" class="numButton" value="1" onclick="addToInput(1)">
            <input type="button" class="numButton" value="2" onclick="addToInput(2)">
            <input type="button" class="numButton" value="3" onclick="addToInput(3)">
        </div>
        <div>
            <input type="button" class="numButton" value="4" onclick="addToInput(4)">
            <input type="button" class="numButton" value="5" onclick="addToInput(5)">
            <input type="button" class="numButton" value="6" onclick="addToInput(6)">
        </div>
        <div>
            <input type="button" class="numButton" value="7" onclick="addToInput(7)">
            <input type="button" class="numButton" value="8" onclick="addToInput(8)">
            <input type="button" class="numButton" value="9" onclick="addToInput(9)">
        </div>
        <div>
            <input type="button" class="numButton" value="0" onclick="addToInput(0)">
            <input type="button" id="delButton" value="DEL" onclick="deleteLast()">
            <input type="submit" id="equalsButton" value="=">
        </div>
    </form>
</div>

<!-- Winkelwagen -->
<section id="winkelwagen">
    <h1>Winkelwagen</h1>
    <ul id="cart-items">
        <?php if(isset($_SESSION['winkelwagen']) && !empty($_SESSION['winkelwagen'])): ?>
            <?php foreach ($_SESSION['winkelwagen'] as $key => $product): ?>
                <li>
                    <span><?php echo $product['omschrijving']; ?></span><span>€<?php echo number_format($product['prijs'], 2); ?></span>
                    <form method="POST" action="">
                        <input type="hidden" name="remove_index" value="<?php echo $key; ?>">
                        <button type="submit" name="remove_item">Verwijder</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Geen producten toegevoegd aan de winkelwagen.</li>
        <?php endif; ?>
    </ul>
    <a id="print" href="bon.php">Bon</a>
</section>


<!-- Footer -->
<?php include("inc/footer.php") ?>

<!-- JavaScript voor de calculator -->
<script>
    function addToInput(value) {
        document.getElementById('result').value += value;
    }

    function deleteLast() {
        var value = document.getElementById('result').value;
        document.getElementById('result').value = value.slice(0, -1);
    }
</script>
</body>
</html>
