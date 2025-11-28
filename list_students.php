<?php
require_once "db_connect.php";
$conn = getDatabaseConnection();

$stmt = $conn->query("SELECT * FROM students");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($students as $s):
?>
<tr>
    <td><?= htmlspecialchars($s['student_id']) ?></td>
    <td><?= htmlspecialchars($s['last_name']) ?></td>
    <td><?= htmlspecialchars($s['first_name']) ?></td>
    
    <?php
    // Loop for 6 sessions, each with presence and participation
    for ($i = 1; $i <= 6; $i++):
        $pChecked = $s["s{$i}_p"] ? 'checked' : '';
        $paChecked = $s["s{$i}_pa"] ? 'checked' : '';
    ?>
        <td class="presence"><input type="checkbox" <?= $pChecked ?>></td>
        <td class="participation"><input type="checkbox" <?= $paChecked ?>></td>
    <?php endfor; ?>

    <td class="abs"></td>
    <td class="parNbr"></td>
    <td class="msg"></td>

    <!-- DELETE COLUMN -->
    <td style="text-align:center;">
    <img src="images/delete.png" alt="Delete Student"
         style="height:28px; width:28px; cursor:pointer;"
         onclick="deleteStudent(<?= $s['student_id'] ?>, this.closest('tr'))"/>
    </td>

</tr>
<?php endforeach; ?>
