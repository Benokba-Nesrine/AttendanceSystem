<?php
require_once 'db_connect.php';

// Enable errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = getDatabaseConnection();

// Use COALESCE to avoid NULL problems
$total = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

$excellent = $pdo->query("
    SELECT COUNT(*) FROM students
    WHERE (6 - (COALESCE(s1_p,0) + COALESCE(s2_p,0) + COALESCE(s3_p,0) + COALESCE(s4_p,0) + COALESCE(s5_p,0) + COALESCE(s6_p,0))) < 3
")->fetchColumn();

$warning = $pdo->query("
    SELECT COUNT(*) FROM students
    WHERE (6 - (COALESCE(s1_p,0) + COALESCE(s2_p,0) + COALESCE(s3_p,0) + COALESCE(s4_p,0) + COALESCE(s5_p,0) + COALESCE(s6_p,0))) BETWEEN 3 AND 4
")->fetchColumn();

$excluded = $pdo->query("
    SELECT COUNT(*) FROM students
    WHERE (6 - (COALESCE(s1_p,0) + COALESCE(s2_p,0) + COALESCE(s3_p,0) + COALESCE(s4_p,0) + COALESCE(s5_p,0) + COALESCE(s6_p,0))) >= 5
")->fetchColumn();

$avgAbsences = $pdo->query("
    SELECT AVG(6 - (COALESCE(s1_p,0) + COALESCE(s2_p,0) + COALESCE(s3_p,0) + COALESCE(s4_p,0) + COALESCE(s5_p,0) + COALESCE(s6_p,0))) 
    FROM students
")->fetchColumn();

$avgAbsences = round($avgAbsences, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  canvas { height: 300px; width: 300px; margin-top: 20px; }
</style>
</head>
<body>
<nav class="nav-bar">
  <a href="index.html" class="navigation"><img src="images/home.png"><p>Home</p></a>
  <a href="AttendanceList.html" class="navigation"><img src="images/list.png"><p>Attendance List</p></a>
  <a href="addStudent.html" class="navigation"><img src="images/user.png"><p>Add Student</p></a>
  <a href="reports.html" class="navigation" id="current"><img src="images/report.png"><p>Reports</p></a>
  <a href="logout.html" class="navigation"><img src="images/out.png"><p>Logout</p></a>
</nav>

<div class="report">
  <h1>Attendance Report</h1>
  <div class="cards">
    <div class="card-reports"><h2><?php echo $total; ?></h2><p>Total Students</p></div>
    <div class="card-reports green"><h2><?php echo $excellent; ?></h2><p>Excellent<br>(&lt;3 absences)</p></div>
    <div class="card-reports yellow"><h2><?php echo $warning; ?></h2><p>Warning<br>(3–4 absences)</p></div>
    <div class="card-reports red"><h2><?php echo $excluded; ?></h2><p>Excluded<br>(&ge;5 absences)</p></div>
  </div>

  <h3>Average Absences: <?php echo $avgAbsences; ?></h3>

  <canvas id="chart"></canvas>
</div>

<script>
new Chart(document.getElementById('chart'), {
  type: 'doughnut',
  data: {
    labels: ['Excellent', 'Warning', 'Excluded'],
    datasets: [{
      data: [<?php echo $excellent; ?>, <?php echo $warning; ?>, <?php echo $excluded; ?>],
      backgroundColor: ['#28a745', '#ffc107', '#dc3545']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
</body>
</html>
