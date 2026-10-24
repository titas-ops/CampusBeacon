<?php
session_start();
require_once '../database/db_connect.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); exit; }
$stmt = $conn->prepare("SELECT pdf_data FROM notices WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();
if (!$row || empty($row['pdf_data'])) { http_response_code(404); exit; }
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=notice_$id.pdf");
echo $row['pdf_data'];
exit;
?>
