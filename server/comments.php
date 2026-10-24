<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db_connect.php';
require_once 'auth_helpers.php';

if (!isLoggedIn() || !hasRole('student')) {
    header("Location: ../public/login.php?error=please_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_id = intval($_POST['notice_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($notice_id <= 0 || $comment === '') {
        header("Location: ../public/view_notice.php?id=$notice_id&error=empty_comment");
        exit();
    }
    $student_id = (int) $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO comments (notice_id, student_id, comment_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $notice_id, $student_id, $comment);
    $stmt->execute();
    $stmt->close();
    header("Location: ../public/view_notice.php?id=$notice_id");
    exit();
}
?>
