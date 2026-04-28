<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php"); exit;
}
require '../db.php';

$sid  = $_SESSION['user_id'];
$name = $_SESSION['name'];

$student = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM student WHERE SID=$sid"));
$courses = mysqli_query($conn, "SELECT c.* FROM courses c JOIN enroll e ON c.C_ID=e.C_ID WHERE e.SID=$sid");
$exams   = mysqli_query($conn, "SELECT ex.* FROM exams ex JOIN belongs b ON ex.Exam_Code=b.Exam_Code WHERE b.SID=$sid ORDER BY ex.Date");
$c_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM enroll WHERE SID=$sid"))['c'];
$e_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM belongs WHERE SID=$sid"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Student Dashboard — CMS</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">
      <div class="logo-icon">🎓</div>
      <div><h2>CMS</h2><span>Student Portal</span></div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Menu</div>
    <a href="dashboard.php" class="nav-link active"><span class="icon">🏠</span> Dashboard</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../logout.php">🚪 Logout</a>
  </div>
</aside>
<div class="main">
  <div class="topbar">
    <h1>Student Dashboard</h1>
    <div class="topbar-user">
      <div class="avatar"><?= strtoupper($name[0]) ?></div>
      <span><?= htmlspecialchars($name) ?></span>
    </div>
  </div>
  <div class="content">

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🎓</div>
        <div class="stat-num"><?= $student['RollNo'] ?></div>
        <div class="stat-label">Roll Number</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-num">Sem <?= $student['SEM'] ?></div>
        <div class="stat-label">Current Semester</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-num"><?= $c_count ?></div>
        <div class="stat-label">Enrolled Courses</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-num"><?= $e_count ?></div>
        <div class="stat-label">Upcoming Exams</div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">📚 My Courses</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Course Name</th><th>Code</th></tr></thead>
          <tbody>
            <?php $i=1; while($row=mysqli_fetch_assoc($courses)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['C_name']) ?></td>
              <td><span class="badge badge-blue"><?= $row['C_code'] ?></span></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($c_count==0): ?><tr><td colspan="3" style="text-align:center;color:var(--text3)">No courses enrolled yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-title">📝 My Exams</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Exam Code</th><th>Room</th><th>Time</th><th>Date</th></tr></thead>
          <tbody>
            <?php $i=1; while($row=mysqli_fetch_assoc($exams)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><span class="badge badge-green">#<?= $row['Exam_Code'] ?></span></td>
              <td><?= htmlspecialchars($row['RoomNo']) ?></td>
              <td><?= $row['Time'] ?></td>
              <td><?= $row['Date'] ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($e_count==0): ?><tr><td colspan="5" style="text-align:center;color:var(--text3)">No exams assigned yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
</div>
</body>
</html>