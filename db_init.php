<?php
\System.Management.Automation.Internal.Host.InternalHost = '127.0.0.1';
\ = 'root';
\ = '';

try {
    \ = new PDO(\"mysql:host=\System.Management.Automation.Internal.Host.InternalHost\", \, \);
    \->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    \ = file_get_contents('database.sql');
    \->exec(\);
    
    echo \"Database successfully initialized and seeded!\\n\";
} catch (PDOException \) {
    echo \"Database error: \" . \->getMessage() . \"\\n\";
}
?>
