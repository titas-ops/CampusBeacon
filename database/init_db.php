<?php
/**
 * Database Initialization Script
 * Automatically creates the database and tables if they don't exist.
 */
require_once 'config.php';

// Initial connection without database selected to create the DB
$conn_init = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn_init->connect_error) {
    die("Connection failed: " . $conn_init->connect_error);
}


$sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn_init->query($sql_create_db)) {
    die("Could not create database: " . $conn_init->error);
}


$conn_init->select_db(DB_NAME);


$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        roll_number VARCHAR(20) UNIQUE NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'professor', 'student') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB",

    "notices" => "CREATE TABLE IF NOT EXISTS notices (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        professor_id INT(11) UNSIGNED NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        pdf_data LONGBLOB,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (professor_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    "comments" => "CREATE TABLE IF NOT EXISTS comments (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        notice_id INT(11) UNSIGNED NOT NULL,
        student_id INT(11) UNSIGNED NOT NULL,
        comment_text TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB"
];

foreach ($tables as $tableName => $sql) {
    if (!$conn_init->query($sql)) {
        die("Error creating table $tableName: " . $conn_init->error);
    }
}

// 3. Create Default Admin User (if table is empty)
$check_users = $conn_init->query("SELECT id FROM users LIMIT 1");
if ($check_users->num_rows === 0) {
    $admin_username = 'admin';
    $admin_email = 'admin@campusbeacon.com';
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $admin_role = 'admin';

    $stmt = $conn_init->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $admin_username, $admin_email, $admin_pass, $admin_role);
    if (!$stmt->execute()) {
        error_log("Failed to create default admin: " . $stmt->error);
    }
    $stmt->close();
}

$conn_init->close();
?>
