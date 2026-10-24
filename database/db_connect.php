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
 * Migrate legacy comments.student_id -> user_id so professors can comment too.
 */
$legacyCol = $conn->query("SHOW COLUMNS FROM comments LIKE 'student_id'");
if ($legacyCol && $legacyCol->num_rows > 0) {
    $conn->query("ALTER TABLE comments CHANGE student_id user_id INT(11) UNSIGNED NOT NULL");
}

/**
 * The $conn variable is now available globally for all scripts that include this file.
 */
?>
