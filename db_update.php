<?php
require_once 'config/db.php';

try {
    // 1. Add subject_id column allowing NULL temporarily so we don't break existing data
    $conn->exec("ALTER TABLE questions ADD COLUMN subject_id INT(11) NULL AFTER id");
    
    // 2. Migrate existing data: Find what subject the question belonged to based on its old exam_id
    $conn->exec("UPDATE questions q JOIN exams e ON q.exam_id = e.id SET q.subject_id = e.subject_id");
    
    // 3. Drop the old foreign key constraint on exam_id (MySQL commonly names this questions_ibfk_1)
    try {
        $conn->exec("ALTER TABLE questions DROP FOREIGN KEY questions_ibfk_1");
    } catch(Exception $e) {}
    
    // 4. Drop the old exam_id column entirely
    $conn->exec("ALTER TABLE questions DROP COLUMN exam_id");
    
    // 5. Clean up any orphaned questions that didn't have a valid exam/subject
    $conn->exec("DELETE FROM questions WHERE subject_id IS NULL");
    
    // 6. Enforce NOT NULL and establish the new structural Foreign Key to subjects
    $conn->exec("ALTER TABLE questions MODIFY COLUMN subject_id INT(11) NOT NULL");
    $conn->exec("ALTER TABLE questions ADD CONSTRAINT fk_questions_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE");
    
    echo "<div style='font-family: sans-serif; padding: 40px; text-align: center;'>";
    echo "<h1 style='color: #10b981;'>Database Successfully Migrated!</h1>";
    echo "<p>The `questions` table has been successfully upgraded to use dynamic Subject Banks.</p>";
    echo "<a href='teacher/index.php' style='display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Return to Teacher Dashboard</a>";
    echo "</div>";

} catch (PDOException $e) {
    // If the column already exists, catch it gracefully
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<div style='font-family: sans-serif; padding: 40px; text-align: center;'>";
        echo "<h1 style='color: #3b82f6;'>Database Already Up To Date</h1>";
        echo "<p>It looks like the `subject_id` column already exists in your table.</p>";
        echo "<a href='teacher/index.php' style='display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Return to Teacher Dashboard</a>";
        echo "</div>";
    } else {
        echo "<h1>Migration Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
}
?>
