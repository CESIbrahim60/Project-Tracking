<?php
/**
 * Database Configuration
 * Maysan Al-Riyidh CCTV Security Systems
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'maysanit_tracking');
define('DB_PASSWORD', 'fcGu2r8C57Mtntd4FsYC');
define('DB_NAME', 'maysanit_tracking');
define('DB_PORT', 3306);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Set charset to UTF-8 for Arabic support
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Riyadh');

?>
