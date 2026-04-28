<?php
$pageTitle = 'Dashboard';
require 'header.php';
require '../db.php';

$students  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM student"))['c'];
$faculty   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM faculty"))['c'];
$courses   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM courses"))['c'];
$exams     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM exams"))['c'];
$depts     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM department"))['c'];
$subjects  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM subjects"))['c'];

// Recent students
$recent = mysqli_query($conn, "SELECT * FROM student ORDER BY SID DESC LIMIT 5");
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">🎓</div>
    <div class="stat-num"><?= $students ?></div>
    <div class="stat-label">Total Students</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">👨‍🏫</div>
    <div class="stat-num"><?= $faculty ?></div>
    <div class="stat-label">Faculty Members</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">📚</div>
    <div class="stat-num"><?= $courses ?></div>
    <div class="stat-label">Courses</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">📝</div>
    <div class="stat-num"><?= $exams ?></div>
    <div class="stat-label">Exams</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">🏢</div>
    <div class="stat-num"><?= $depts ?></div>
    <div class="stat-label">Departments</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">📖</div>
    <div class="stat-num"><?= $subjects ?></div>
    <div class="stat-label">Subjects</div>
  </div>
</div>

<div class="card">
  <div class="card-title">🎓 Recently Added Students</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Roll No</th><th>Name</th><th>Semester</th><th>Username</th><th>Phone</th></tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($recent)): ?>
        <tr>
          <td><?= $row['RollNo'] ?></td>
          <td><?= htmlspecialchars($row['Name']) ?></td>
          <td><span class="badge badge-blue">Sem <?= $row['SEM'] ?></span></td>
          <td><?= htmlspecialchars($row['Username']) ?></td>
          <td><?= $row['Phone'] ?></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($students == 0): ?>
        <tr><td colspan="5" style="text-align:center;color:var(--text3)">No students added yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>