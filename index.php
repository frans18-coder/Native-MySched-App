<?php
require_once 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();

// fetch events (for the logged in user if exists, otherwise all)
if(isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $stmt = mysqli_prepare($conn, "SELECT e.id, e.title, e.event_date, e.description, u.username FROM events e JOIN users u ON e.user_id = u.id WHERE e.user_id = ? ORDER BY e.event_date ASC");
    mysqli_stmt_bind_param($stmt, "i", $uid);
} else {
    $stmt = mysqli_prepare($conn, "SELECT e.id, e.title, e.event_date, e.description, u.username FROM events e JOIN users u ON e.user_id = u.id ORDER BY e.event_date ASC");
}
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$events = mysqli_fetch_all($res, MYSQLI_ASSOC);
require 'includes/header.php';
require 'includes/navbar.php';
?>

<div class="container">
  <h1>Daftar Kegiatan</h1>

  <?php if(empty($events)): ?>
    <p>Tidak ada kegiatan.</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr><th>#</th><th>Judul</th><th>Tanggal</th><th>Oleh</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach($events as $i => $ev): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($ev['title']) ?></td>
            <td><?= htmlspecialchars($ev['event_date']) ?></td>
            <td><?= htmlspecialchars($ev['username']) ?></td>
            <td>
              <?php if(isset($_SESSION['user']) && ($_SESSION['user']['id']==$ev['user_id'] || $_SESSION['user']['role']==='admin')): ?>
                <a href="edit_event.php?id=<?= $ev['id'] ?>">Edit</a>
                <a href="delete_event.php?id=<?= $ev['id'] ?>" onclick="return confirm('Hapus kegiatan ini?')">Hapus</a>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</div>

<?php require 'includes/footer.php'; ?>