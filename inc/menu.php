
<?php


$autRol = isset($_SESSION['rol']) ? strtolower($_SESSION['rol']) : '';
$inlognaam = isset($_SESSION['inlognaam']) ? $_SESSION['inlognaam'] : '';

// Initialize $menu
$menu = '';

// Samenstellen menu
switch ($autRol) {
    case 'directeur':
        $menu = '<nav class="menu">
                    <ul>
                        <li><a href="index.php">kassa</a></li>                        
                        <li><a href="importeren.php">importeren</a></li>
                        <li><a href="vooraad.php">vooraad</a></li>                       
                        <li><a href="logout.php">Uitloggen</a></li>
                    </ul>
                </nav>';
        break;
    case 'medewerker':
        $menu = '<nav class="menu">
                    <ul>
                        <li><a href="klant.php">Kassa</a></li>
                        <li><a href="logout.php">Uitloggen</a></li>
                    </ul>
                </nav>';
        break;
    default:
        // Handle default case if needed
}

echo $menu;
?>
