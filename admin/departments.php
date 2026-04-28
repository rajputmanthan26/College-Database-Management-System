<?php
$pageTitle = 'Departments';
require 'header.php';
require '../db.php';

$msg = '';

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['dname']);
    if (mysqli_query($conn, "INSERT INTO department (D_name) VALUES ('$name')"))
        $msg = 'success:Department added!';
    else $msg = 'error:' . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM department WHERE D_ID=" . (int)$_GET['delete']);
    header("Location: departments.php?deleted=1"); exit;
}
if (isset($_GET['deleted'])) $msg = 'success:Department deleted.';

$depts = mysqli_query($conn, "SELECT * FROM department ORDER BY D_ID DESC");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add Department</div>
  <form method="POST" style="display:flex;gap:12px;align-items:flex-end">
    <div class="form-group" style="flex:1">
      <label>Department Name</label>
      <input type="text" name="dname" placeholder="e.g. Computer Science" required>
    </div>
    <button type="submit" name="add" class="btn btn-primary">Add</button>
  </form>
</div>

<div class="card">
  <div class="card-title">🏢 All Departments</div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Department Name</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($depts)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['D_name']) ?></td>
          <td><a href="?delete=<?= $row['D_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">🗑 Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>