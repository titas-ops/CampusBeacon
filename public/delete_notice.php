<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';

$notice_id = (int)$_GET['id'] ?? 0;
$user_id = (int)$_SESSION['user_id'] ?? 0;
$role = $_SESSION['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $notice_id > 0) {

    // 1. Admin can delete anything
    if ($role === 'admin') {
        $del_stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
        $del_stmt->bind_param("i", $notice_id);
        if ($del_stmt->execute()) {
            header("Location: index.php?success=notice_deleted");
        } else {
            header("Location: index.php?error=delete_failed");
        }
        $del_stmt->close();
        exit();
    }

    // 2. Professors can only delete their OWN LATEST notice
    if ($role === 'professor') {
        // Check if this specific notice is indeed the LATEST notice in the system
        $stmt = $conn->prepare("SELECT id FROM notices ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();
        $latest = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($latest && (int)$latest['id'] === $notice_id) {
            // Now verify they actually own this latest notice
            $owner_stmt = $conn->prepare("SELECT id FROM notices WHERE id = ? AND professor_id = ?");
            $owner_stmt->bind_param("ii", $notice_id, $user_id);
            $owner_stmt->execute();
            if ($owner_stmt->get_result()->num_rows === 1) {
                $del_stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
                $del_stmt->bind_param("i", $notice_id);
                if ($del_stmt->execute()) {
                    header("Location: index.php?success=notice_deleted");
                } else {
                    header("Location: index.php?error=delete_failed");
                }
                $del_stmt->close();
                exit();
            }
            $owner_stmt->close();
        }
    }

    // If we reached here, the user is not an admin, or not the owner of the absolute latest notice
    header("Location: index.php?error=unauthorized");
    exit();
}

header("Location: index.php");
exit();
?>
