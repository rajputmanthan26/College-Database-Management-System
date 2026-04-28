<?php
$pageTitle = 'Exams';
require 'header.php';
require '../db.php';

$msg = '';

if (isset($_POST['add'])) {
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $date = $_POST['date'];
    $did  = (int)$_POST['did'];
    if (mysqli_query($conn, "INSERT INTO exams (RoomNo,Time,Date,D_ID) VALUES ('$room','$time','$date',$did)"))
        $msg = 'success:Exam added!';
    else $msg = 'error:' . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM exams WHERE Exam_Code=" . (int)$_GET['delete']);
    header("Location: exams.php?deleted=1"); exit;
}
if (isset($_GET['deleted'])) $msg = 'success:Exam deleted.';

$exams = mysqli_query($conn, "SELECT e.*,d.D_name FROM exams e LEFT JOIN department d ON e.D_ID=d.D_ID ORDER BY e.Exam_Code DESC");
?>

<?php if ($msg): list($type,$text) = explode(':', $msg, 2); ?>
<div class="alert alert-<?= $type ?>"><?= $text ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-title">➕ Add Exam</div>
  <form method="POST">
    <div class="form-grid">
      <div class="form-group">
        <label>Room Number</label>
        <input type="text" name="room" placeholder="e.g. Room 101" required>
      </div>
      <div class="form-group">
        <label>Time</label>
        <input type="text" name="time" placeholder="e.g. 10:00 AM" required>
      </div>
      <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" required>
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
      <button type="submit" name="add" class="btn btn-primary">Add Exam</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">📝 All Exams</div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Exam Code</th><th>Room</th><th>Time</th><th>Date</th><th>Department</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($exams)): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><span class="badge badge-blue">#<?= $row['Exam_Code'] ?></span></td>
          <td><?= htmlspecialchars($row['RoomNo']) ?></td>
          <td><?= $row['Time'] ?></td>
          <td><?= $row['Date'] ?></td>
          <td><?= htmlspecialchars($row['D_name'] ?? '—') ?></td>
          <td><a href="?delete=<?= $row['Exam_Code'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">🗑 Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'footer.php'; ?>