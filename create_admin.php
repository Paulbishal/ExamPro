<?php
require 'config/db.php';

$email = 'admin@example.com';
$password = 'password123';
$hashed = password_hash($password, PASSWORD_BCRYPT);

try {
    // Check if the admin account already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // Update existing admin account
        $update = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = ?");
        $update->execute([$hashed, $email]);
        echo "<div style='font-family: sans-serif; padding: 20px; border: 2px solid green; background: #e8f5e9; border-radius: 8px; max-width: 500px; margin: 50px auto; text-align: center;'>";
        echo "<h2 style='color: green;'>Success!</h2>";
        echo "<p>Admin password was updated successfully!</p>";
        echo "<p><strong>Email:</strong> admin@example.com<br><strong>Password:</strong> password123</p>";
        echo "<a href='index.php' style='display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;'>Go to Login</a>";
        echo "</div>";
    } else {
        // Create new admin account
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES ('System Admin', ?, ?, 'admin')");
        $insert->execute([$email, $hashed]);
        echo "<div style='font-family: sans-serif; padding: 20px; border: 2px solid green; background: #e8f5e9; border-radius: 8px; max-width: 500px; margin: 50px auto; text-align: center;'>";
        echo "<h2 style='color: green;'>Action Completed!</h2>";
        echo "<p>New Admin account created safely!</p>";
        echo "<p><strong>Email:</strong> admin@example.com<br><strong>Password:</strong> password123</p>";
        echo "<a href='index.php' style='display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;'>Go to Login</a>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
