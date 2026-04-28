<?php
$pageTitle = 'Students';
require 'header.php';
require '../db.php';

$msg = '';

// Add student
if (isset($_POST['add'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $roll  = (int)$_POST['rollno'];
    $sem   = (int)$_POST['sem'];
    $user  = mysqli_real_escape_string($conn, $_POST['username']);
    $pass  = $_POST['password'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $q = "INSERT INTO student (Name,RollNo,SEM,Username,Password,Phone) VALUES ('$name',$roll,$sem,'$user',MD5('$pass'),'$phone')";
    if (mysqli_query($conn, $q)) $msg = 'success:Student added successfully!';
    else $msg = 'error:Error: ' . mysqli_error($conn);
}

// Delete student
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM student WHERE SID=$id");
    header("Location: students.php?deleted=1"); exit;
}

if (isset($_GET['deleted'])) $msg = 'success:Student deleted.';

$students = mysqli_query($conn, "SELECT * FROM student ORDER BY SID DESC");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add New Student</div>
  <form method="POST">
    <div class="form-grid">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="e.g. Rahul Sharma" required>
      </div>
      <div class="form-group">
        <label>Roll Number</label>
        <input type="number" name="rollno" placeholder="e.g. 101" required>
      </div>
      <div class="form-group">
        <label>Semester</label>
        <select name="sem" required>
          <?php for($i=1;$i<=8;$i++) echo "<option value='$i'>Semester $i</option>"; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="e.g. rahul101" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Set a password" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" placeholder="e.g. 9876543210">
      </div>
    </div>
    <div style="margin-top:16px">
      <button type="submit" name="add" class="btn btn-primary">➕ Add Student</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">🎓 All Students</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Roll No</th><th>Name</th><th>Sem</th><th>Username</th><th>Phone</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($students)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= $row['RollNo'] ?></td>
          <td><?= htmlspecialchars($row['Name']) ?></td>
          <td><span class="badge badge-blue">Sem <?= $row['SEM'] ?></span></td>
          <td><?= htmlspecialchars($row['Username']) ?></td>
          <td><?= $row['Phone'] ?></td>
          <td>
            <a href="?delete=<?= $row['SID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?')">🗑 Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>