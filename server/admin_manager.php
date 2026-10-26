<?php
/**
 * Admin Manager
 * Handles administrative tasks such as managing professor accounts.
 */

// FORCE ERROR REPORTING FOR DEBUGGING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db_connect.php';
require_once 'auth_helpers.php';

// Security check: Only admins can access this controller
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_professor'])) {
        handleAddProfessor();
    } elseif (isset($_POST['delete_professor'])) {
        handleDeleteProfessor();
    }
}

/**
 * Adds a new professor account.
 */
function handleAddProfessor() {
    global $conn;
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../public/admin/dashboard.php?error=empty_fields");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'professor';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        header("Location: ../public/admin/dashboard.php?success=professor_added");
    } else {
        header("Location: ../public/admin/dashboard.php?error=duplicate_user");
    }
    $stmt->close();
    exit();
}

/**
 * Deletes a professor account.
 */
function handleDeleteProfessor() {
    global $conn;
    $professor_id = intval($_POST['professor_id'] ?? 0);

    if ($professor_id <= 0) {
        header("Location: ../public/admin/dashboard.php?error=invalid_id");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'professor'");
    $stmt->bind_param("i", $professor_id);

    if ($stmt->execute()) {
        header("Location: ../public/admin/dashboard.php?success=professor_deleted");
    } else {
        header("Location: ../public/admin/dashboard.php?error=delete_failed");
    }
    $stmt->close();
    exit();
}
?>
