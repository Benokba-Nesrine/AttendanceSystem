<?php
require_once "db_connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

$student_id = trim($_POST['ID'] ?? '');
$last_name  = trim($_POST['LName'] ?? '');
$first_name = trim($_POST['FName'] ?? '');
$email      = trim($_POST['Email'] ?? '');

if (empty($student_id) || empty($last_name) || empty($first_name) || empty($email)) {
    die(json_encode(['success' => false, 'message' => 'All fields are required']));
}

try {
    $conn = getDatabaseConnection();

    // Check duplicate student_id or email
    $check = $conn->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
    $check->execute([$student_id, $email]);

    if ($check->rowCount() > 0) {
        die(json_encode(['success' => false, 'message' => 'Student ID or Email already exists']));
    }

    // Insert new student
    $sql = "INSERT INTO students (student_id, last_name, first_name, email)
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$student_id, $last_name, $first_name, $email]);

    echo json_encode(['success' => true, 'message' => 'Student added successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
