<?php
/**
 * Database Connection Handler
 * Establishes a connection to the database and ensures schema initialization.
 */

require_once 'config.php';
require_once 'init_db.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

/**
 * The $conn variable is now available globally for all scripts that include this file.
 */
?>
