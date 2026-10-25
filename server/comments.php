<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db_connect.php';
require_once 'auth_helpers.php';

if (!isLoggedIn()) {
    header("Location: ../public/login.php?error=please_login");
    exit();
}

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['student', 'professor'], true)) {
    header("Location: ../public/login.php?error=unauthorized");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_id = intval($_POST['notice_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $parent_id = intval($_POST['parent_id'] ?? 0);
    if ($parent_id <= 0) $parent_id = null;

    if ($notice_id <= 0 || $comment === '') {
        header("Location: ../public/view_notice.php?id=$notice_id&error=empty_comment");
        exit();
    }
    $user_id = (int) $_SESSION['user_id'];
    if ($parent_id === null) {
        $stmt = $conn->prepare("INSERT INTO comments (notice_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $notice_id, $user_id, $comment);
    } else {
        $stmt = $conn->prepare("INSERT INTO comments (notice_id, user_id, parent_id, comment_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $notice_id, $user_id, $parent_id, $comment);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: ../public/view_notice.php?id=$notice_id");
    exit();
}
?>
