<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // Change according to your MySQL username
define('DB_PASS', '');         // Change according to your MySQL password
define('DB_NAME', 'task_manager');

// Create connection using PDO
// In config/db.php or where you establish your database connection
function connectDB()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        // Set PDO to throw exceptions on errors
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Set default fetch mode to associative array
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Set timezone in MySQL to match PHP timezone
        $pdo->exec("SET time_zone = '+08:00';"); // For Philippines timezone (UTC+8)

        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
