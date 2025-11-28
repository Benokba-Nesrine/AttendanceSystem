<?php
require_once "db_connect.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No student ID provided.");
}

$student_id = intval($_GET['id']);

try {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);

    // Redirect back to attendance list page
    header("Location: AttendanceList.php"); // or list table page
    exit;
} catch (Exception $e) {
    echo "Error deleting student: " . $e->getMessage();
}
