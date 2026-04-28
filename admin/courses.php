<?php
$pageTitle = 'Courses';
require 'header.php';
require '../db.php';

$msg = '';

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cname']);
    $code = mysqli_real_escape_string($conn, $_POST['ccode']);
    $did  = (int)$_POST['did'];
    if (mysqli_query($conn, "INSERT INTO courses (C_name,C_code,D_ID) VALUES ('$name','$code',$did)"))
        $msg = 'success:Course added!';
    else $msg = 'error:' . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM courses WHERE C_ID=" . (int)$_GET['delete']);
    header("Location: courses.php?deleted=1"); exit;
}
if (isset($_GET['deleted'])) $msg = 'success:Course deleted.';

$courses = mysqli_query($conn, "SELECT c.*,d.D_name FROM courses c LEFT JOIN department d ON c.D_ID=d.D_ID ORDER BY c.C_ID DESC");
$depts   = mysqli_query($conn, "SELECT * FROM department");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add Course</div>
  <form method="POST">
    <div class="form-grid">
      <div class="form-group">
        <label>Course Name</label>
        <input type="text" name="cname" placeholder="e.g. Data Structures" required>
      </div>
      <div class="form-group">
        <label>Course Code</label>
        <input type="text" name="ccode" placeholder="e.g. CS301">
      </div>
      <div class="form-group">
        <label>Department</label>
        <select name="did">
          <option value="0">-- Select --</option>
          <?php $d = mysqli_query($conn,"SELECT * FROM department"); while($r=mysqli_fetch_assoc($d)): ?>
          <option value="<?= $r['D_ID'] ?>"><?= htmlspecialchars($r['D_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
    <div style="margin-top:16px">
      <button type="submit" name="add" class="btn btn-primary">Add Course</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">📚 All Courses</div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Course Name</th><th>Code</th><th>Department</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($courses)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['C_name']) ?></td>
          <td><span class="badge badge-blue"><?= $row['C_code'] ?></span></td>
          <td><?= htmlspecialchars($row['D_name'] ?? '—') ?></td>
          <td><a href="?delete=<?= $row['C_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">🗑 Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>