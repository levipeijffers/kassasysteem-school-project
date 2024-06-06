<?php
// Bekende vars: $page, $total_pages, $page_url
$start_from = ($page - 1) * RECORDS_PER_PAGE; // Bereken de startindex voor de huidige pagina

// Begin met div...
echo '<div class="pagination">';
// Alle pagina's in nummers
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='{$page_url}&page=" . $i . "'";
    if ($i == $page) echo " class='active'";
    echo ">" . $i . "</a>";
}
echo "</div>";
?>
