<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';

// Only professors can delete
requireRole('professor');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $notice_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    // Verify ownership: Notice must exist AND belong to the logged-in professor
    $stmt = $conn->prepare("SELECT id FROM notices WHERE id = ? AND professor_id = ?");
    $stmt->bind_param("ii", $notice_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $del_stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
        $del_stmt->bind_param("i", $notice_id);
        if ($del_stmt->execute()) {
            header("Location: index.php?success=notice_deleted");
        } else {
            header("Location: index.php?error=delete_failed");
        }
        $del_stmt->close();
    } else {
        // Not authorized or notice doesn't exist
        header("Location: index.php?error=unauthorized");
    }
    $stmt->close();
    exit();
}

header("Location: index.php");
exit();
?>
