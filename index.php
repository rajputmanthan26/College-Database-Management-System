<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    if ($role === 'admin') {
        $res = mysqli_query($conn, "SELECT * FROM admin WHERE Username='$username' AND Password=MD5('$password')");
        $user = mysqli_fetch_assoc($res);
        if ($user) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['role'] = 'admin';
            $_SESSION['name'] = 'Admin';
            header("Location: admin/dashboard.php");
            exit;
        }
    } elseif ($role === 'student') {
        $res = mysqli_query($conn, "SELECT * FROM student WHERE Username='$username' AND Password=MD5('$password')");
        $user = mysqli_fetch_assoc($res);
        if ($user) {
            $_SESSION['user_id'] = $user['SID'];
            $_SESSION['role'] = 'student';
            $_SESSION['name'] = $user['Name'];
            header("Location: student/dashboard.php");
            exit;
        }
    } elseif ($role === 'faculty') {
        $res = mysqli_query($conn, "SELECT * FROM faculty WHERE Username='$username' AND Password=MD5('$password')");
        $user = mysqli_fetch_assoc($res);
        if ($user) {
            $_SESSION['user_id'] = $user['F_ID'];
            $_SESSION['role'] = 'faculty';
            $_SESSION['name'] = $user['Name'];
            header("Location: faculty/dashboard.php");
            exit;
        }
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>College Management System — Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-page">
  <div class="login-box">
    <div class="logo-area">
      <div class="big-icon">🎓</div>
      <h2>College Management</h2>
      <p>Sign in to your account</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="role-tabs">
        <button type="button" class="role-tab <?= (!isset($_POST['role']) || $_POST['role']==='admin') ? 'active' : '' ?>" onclick="setRole('admin')">👤 Admin</button>
        <button type="button" class="role-tab <?= (isset($_POST['role']) && $_POST['role']==='student') ? 'active' : '' ?>" onclick="setRole('student')">🎓 Student</button>
        <button type="button" class="role-tab <?= (isset($_POST['role']) && $_POST['role']==='faculty') ? 'active' : '' ?>" onclick="setRole('faculty')">👨‍🏫 Faculty</button>
      </div>
      <input type="hidden" name="role" id="roleInput" value="<?= isset($_POST['role']) ? $_POST['role'] : 'admin' ?>">

      <div class="form-group" style="margin-bottom:14px">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter your username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
      </div>
      <div class="form-group" style="margin-bottom:20px">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px">Sign In</button>
    </form>

    <p style="text-align:center;font-size:12px;color:var(--text3);margin-top:20px">
      Default admin: <strong>admin</strong> / <strong>admin123</strong>
    </p>
  </div>
</div>
<script>
function setRole(r) {
    document.getElementById('roleInput').value = r;
    document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active');
}
</script>
</body>
</html>