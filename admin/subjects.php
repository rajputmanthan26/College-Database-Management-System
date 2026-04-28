<?php
$pageTitle = 'Subjects';
require 'header.php';
require '../db.php';

$msg = '';

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['sname']);
    $fid  = (int)$_POST['fid'];
    if (mysqli_query($conn, "INSERT INTO subjects (S_name,F_ID) VALUES ('$name',$fid)"))
        $msg = 'success:Subject added!';
    else $msg = 'error:' . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM subjects WHERE S_ID=" . (int)$_GET['delete']);
    header("Location: subjects.php?deleted=1"); exit;
}
if (isset($_GET['deleted'])) $msg = 'success:Subject deleted.';

$subjects = mysqli_query($conn, "SELECT s.*,f.Name AS FName FROM subjects s LEFT JOIN faculty f ON s.F_ID=f.F_ID ORDER BY s.S_ID DESC");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add Subject</div>
  <form method="POST">
    <div class="form-grid">
      <div class="form-group">
        <label>Subject Name</label>
        <input type="text" name="sname" placeholder="e.g. Database Management" required>
      </div>
      <div class="form-group">
        <label>Assign Faculty</label>
        <select name="fid">
          <option value="0">-- Select Faculty --</option>
          <?php $f = mysqli_query($conn,"SELECT * FROM faculty"); while($r=mysqli_fetch_assoc($f)): ?>
          <option value="<?= $r['F_ID'] ?>"><?= htmlspecialchars($r['Name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
    <div style="margin-top:16px">
      <button type="submit" name="add" class="btn btn-primary">Add Subject</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">📖 All Subjects</div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Subject Name</th><th>Assigned Faculty</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($subjects)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['S_name']) ?></td>
          <td><?= htmlspecialchars($row['FName'] ?? '—') ?></td>
          <td><a href="?delete=<?= $row['S_ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">🗑 Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>