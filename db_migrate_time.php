<?php
require_once 'config/db.php';
try {
    $conn->exec("ALTER TABLE results ADD COLUMN time_taken INT(11) NULL AFTER percentage");
    echo "<h1>Database Updated Successfully!</h1><p>The 'time_taken' column was added to the results table.</p>";
} catch (PDOException $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
?>
