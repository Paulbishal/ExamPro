<?php
require_once "config/helpers.php";
$result = sanitize("test", "Hello World");
echo "sanitize works: " . $result;
$result2 = sanitize("Direct string");
echo " | direct: " . $result2;
?>