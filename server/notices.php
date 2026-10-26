<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db_connect.php';
require_once 'auth_helpers.php';

requireRole('professor');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_notice'])) {
    handleCreateNotice();
}

function handleCreateNotice() {
    global $conn;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $content = mb_substr($content, 0, 300);

    if (empty($title) && !empty($content)) {
        $firstLine = strtok($content, "\n");
        $title = trim(ltrim($firstLine, '# '));
        if ($title === '') $title = 'Untitled Notice';
    }

    if (empty($title)) {
        header("Location: ../public/professor/create_notice.php?error=empty_title");
        exit();
    }

    $pdf_data = null;
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['pdf_file'];

        // 1. Validate File Size (Limit to 5MB)
        $max_size = 5 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            header("Location: ../public/professor/create_notice.php?error=file_too_large");
            exit();
        }

        // 2. Validate MIME Type (Must be application/pdf)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);
        if ($mime_type !== 'application/pdf') {
            header("Location: ../public/professor/create_notice.php?error=invalid_file_type");
            exit();
        }

        $tmp = $file['tmp_name'];
        $pdf_data = file_get_contents($tmp);
    }

    $professor_id = (int) $_SESSION['user_id'];

    if ($pdf_data !== null) {
        $stmt = $conn->prepare("INSERT INTO notices (professor_id, title, content, pdf_data) VALUES (?, ?, ?, ?)");
        $null = null;
        $stmt->bind_param("issb", $professor_id, $title, $content, $null);
        $stmt->send_long_data(3, $pdf_data);
    } else {
        $stmt = $conn->prepare("INSERT INTO notices (professor_id, title, content, pdf_data) VALUES (?, ?, ?, NULL)");
        $stmt->bind_param("iss", $professor_id, $title, $content);
    }

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../public/professor/create_notice.php?success=notice_created");
        exit();
    } else {
        $stmt->close();
        header("Location: ../public/professor/create_notice.php?error=create_failed");
        exit();
    }
}
?>
