<?php
require_once 'config/db.php';
try {
    $sql = "CREATE TABLE IF NOT EXISTS `student_answers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `result_id` int(11) NOT NULL,
        `question_id` int(11) NOT NULL,
        `student_answer` varchar(1) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `result_id` (`result_id`),
        KEY `question_id` (`question_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $conn->exec($sql);
    echo "<h1>Database Updated!</h1><p>The 'student_answers' table has been created successfully.</p>";
} catch (PDOException $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
?>
