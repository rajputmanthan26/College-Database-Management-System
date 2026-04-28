<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); exit;
}
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin' ?> — College Management</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">
      <div class="logo-icon">🎓</div>
      <div>
        <h2>CMS</h2>
        <span>Admin Panel</span>
      </div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Main</div>
    <a href="dashboard.php" class="nav-link <?= $current==='dashboard.php'?'active':'' ?>"><span class="icon">📊</span> Dashboard</a>

    <div class="nav-label">Manage</div>
    <a href="students.php" class="nav-link <?= $current==='students.php'?'active':'' ?>"><span class="icon">🎓</span> Students</a>
    <a href="faculty.php" class="nav-link <?= $current==='faculty.php'?'active':'' ?>"><span class="icon">👨‍🏫</span> Faculty</a>
    <a href="departments.php" class="nav-link <?= $current==='departments.php'?'active':'' ?>"><span class="icon">🏢</span> Departments</a>
    <a href="courses.php" class="nav-link <?= $current==='courses.php'?'active':'' ?>"><span class="icon">📚</span> Courses</a>
    <a href="subjects.php" class="nav-link <?= $current==='subjects.php'?'active':'' ?>"><span class="icon">📖</span> Subjects</a>
    <a href="exams.php" class="nav-link <?= $current==='exams.php'?'active':'' ?>"><span class="icon">📝</span> Exams</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../logout.php">🚪 Logout</a>
  </div>
</aside>
<div class="main">
  <div class="topbar">
    <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
    <div class="topbar-user">
      <div class="avatar">A</div>
      <span><?= $_SESSION['name'] ?></span>
    </div>
  </div>
  <div class="content">