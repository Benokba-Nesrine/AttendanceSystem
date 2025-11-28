<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "attendance_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all students
$sql = "SELECT * FROM students ORDER BY id ASC";
$result = $conn->query($sql);

// Generate table rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>{$row['student_id']}</td>";
        echo "<td>{$row['last_name']}</td>";
        echo "<td>{$row['first_name']}</td>";

        // repeat for sessions s1 to s6
        for ($i = 1; $i <= 6; $i++) {
            echo '<td class="presence"><input type="checkbox" '.($row["s{$i}_p"]==1 ? "checked" : "").'></td>';
            echo '<td class="participation"><input type="checkbox" '.($row["s{$i}_pa"]==1 ? "checked" : "").'></td>';
        }

        echo '<td class="abs"></td>';
        echo '<td class="parNbr"></td>';
        echo '<td class="msg"></td>';

        echo "</tr>";
    }
}
$conn->close();
?>
