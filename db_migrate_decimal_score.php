<?php
require_once 'config/db.php';
try {
    $conn->exec("ALTER TABLE results MODIFY COLUMN score DECIMAL(10,2) NOT NULL");
    echo "<h1>Database Updated!</h1><p>The 'score' column in the 'results' table now supports decimal values (e.g., 0.25).</p>";
} catch (PDOException $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
?>
