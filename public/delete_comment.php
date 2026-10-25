<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';

if (!isLoggedIn()) {
    header("Location: ../public/login.php?error=please_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);
    $notice_id = intval($_GET['notice_id'] ?? 0);
    $user_id = (int)$_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $del_stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $del_stmt->bind_param("i", $comment_id);
        $del_stmt->execute();
        $del_stmt->close();
        header("Location: ../public/view_notice.php?id=$notice_id&success=deleted");
    } else {
        header("Location: ../public/view_notice.php?id=$notice_id&error=unauthorized");
    }
    $stmt->close();
    exit();
}
?>
