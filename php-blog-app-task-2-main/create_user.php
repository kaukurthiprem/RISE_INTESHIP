<?php
// Connect to the database
$pdo = new PDO("mysql:host=localhost;dbname=blog", "root", "");

// Hash the password
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT); // Use admin123 as password

// Insert into users table
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Admin user created successfully!";
?>
