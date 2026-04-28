<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../index.php"); exit;
}
require '../db.php';

$fid  = $_SESSION['user_id'];
$name = $_SESSION['name'];

$faculty   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM faculty WHERE F_ID=$fid"));
$subjects  = mysqli_query($conn, "SELECT * FROM subjects WHERE F_ID=$fid");
$students  = mysqli_query($conn, "SELECT s.* FROM student s JOIN teaches t ON s.SID=t.SID WHERE t.F_ID=$fid");
$sub_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM subjects WHERE F_ID=$fid"))['c'];
$stu_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM teaches WHERE F_ID=$fid"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Faculty Dashboard — CMS</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">
      <div class="logo-icon">🎓</div>
      <div><h2>CMS</h2><span>Faculty Portal</span></div>
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
    <h1>Faculty Dashboard</h1>
    <div class="topbar-user">
      <div class="avatar"><?= strtoupper($name[0]) ?></div>
      <span><?= htmlspecialchars($name) ?></span>
    </div>
  </div>
  <div class="content">

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🏷️</div>
        <div class="stat-num"><?= htmlspecialchars($faculty['Position'] ?? '—') ?></div>
        <div class="stat-label">Position</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📞</div>
        <div class="stat-num" style="font-size:18px"><?= $faculty['Contact'] ?? '—' ?></div>
        <div class="stat-label">Contact</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📖</div>
        <div class="stat-num"><?= $sub_count ?></div>
        <div class="stat-label">Subjects Teaching</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎓</div>
        <div class="stat-num"><?= $stu_count ?></div>
        <div class="stat-label">Students</div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">📖 My Subjects</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Subject Name</th></tr></thead>
          <tbody>
            <?php $i=1; while($row=mysqli_fetch_assoc($subjects)): ?>
            <tr><td><?= $i++ ?></td><td><?= htmlspecialchars($row['S_name']) ?></td></tr>
            <?php endwhile; ?>
            <?php if ($sub_count==0): ?><tr><td colspan="2" style="text-align:center;color:var(--text3)">No subjects assigned yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-title">🎓 My Students</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Name</th><th>Roll No</th><th>Semester</th><th>Phone</th></tr></thead>
          <tbody>
            <?php $i=1; while($row=mysqli_fetch_assoc($students)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['Name']) ?></td>
              <td><?= $row['RollNo'] ?></td>
              <td><span class="badge badge-blue">Sem <?= $row['SEM'] ?></span></td>
              <td><?= $row['Phone'] ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($stu_count==0): ?><tr><td colspan="5" style="text-align:center;color:var(--text3)">No students assigned yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
</div>
</body>
</html>