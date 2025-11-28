<?php
require_once "db_connect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false, 'message'=>'Invalid request method']);
    exit;
}

$student_id = $_POST['student_id'] ?? '';
$session = intval($_POST['session'] ?? 0);
$type = $_POST['type'] ?? '';
$value = isset($_POST['value']) ? intval($_POST['value']) : 0;

if (!$student_id || $session < 1 || $session > 6 || !in_array($type,['p','pa'])) {
    echo json_encode(['success'=>false, 'message'=>'Invalid parameters']);
    exit;
}

$column = "s{$session}_{$type}";

try {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("UPDATE students SET `$column` = ? WHERE student_id = ?");
    $stmt->execute([$value, $student_id]);
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
