<?php
$pageTitle = 'Faculty';
require 'header.php';
require '../db.php';

$msg = '';

if (isset($_POST['add'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $gender= mysqli_real_escape_string($conn, $_POST['gender']);
    $contact=mysqli_real_escape_string($conn, $_POST['contact']);
    $pos   = mysqli_real_escape_string($conn, $_POST['position']);
    $user  = mysqli_real_escape_string($conn, $_POST['username']);
    $pass  = $_POST['password'];
    $q = "INSERT INTO faculty (Name,Gender,Contact,Position,Username,Password) VALUES ('$name','$gender','$contact','$pos','$user',MD5('$pass'))";
    if (mysqli_query($conn, $q)) $msg = 'success:Faculty added successfully!';
    else $msg = 'error:Error: ' . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM faculty WHERE F_ID=$id");
    header("Location: faculty.php?deleted=1"); exit;
}

if (isset($_GET['deleted'])) $msg = 'success:Faculty deleted.';

$faculty = mysqli_query($conn, "SELECT * FROM faculty ORDER BY F_ID DESC");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add New Faculty</div>
  <form method="POST">
    <div class="form-grid">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="e.g. Dr. Priya Desai" required>
      </div>
      <div class="form-group">
        <label>Gender</label>
        <select name="gender">
          <option>Male</option><option>Female</option><option>Other</option>
        </select>
      </div>
      <div class="form-group">
        <label>Contact</label>
        <input type="text" name="contact" placeholder="e.g. 9876543210">
      </div>
      <div class="form-group">
        <label>Position</label>
        <input type="text" name="position" placeholder="e.g. Professor, HOD">
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="e.g. priya.desai" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Set a password" required>
      </div>
    </div>
    <div style="margin-top:16px">
      <button type="submit" name="add" class="btn btn-primary">➕ Add Faculty</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">👨‍🏫 All Faculty</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Name</th><th>Gender</th><th>Contact</th><th>Position</th><th>Username</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($faculty)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['Name']) ?></td>
          <td><?= $row['Gender'] ?></td>
          <td><?= $row['Contact'] ?></td>
          <td><span class="badge badge-orange"><?= htmlspecialchars($row['Position']) ?></span></td>
          <td><?= htmlspecialchars($row['Username']) ?></td>
          <td>
            <a href="?delete=<?= $row['F_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this faculty?')">🗑 Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>