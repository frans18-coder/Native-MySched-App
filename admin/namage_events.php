<?php
require_once '../db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header('Location: ../index.php');
    exit;
}

// ambil semua events + username
$q = "SELECT e.id, e.title, e.event_date, e.description, u.username FROM events e JOIN users u ON e.user_id = u.id ORDER BY e.event_date ASC";
$res = mysqli_query($conn, $q);
$events = mysqli_fetch_all($res, MYSQLI_ASSOC);

require '../includes/header.php';
require '../includes/navbar.php';
?>
<div class="container">
  <h2>Kelola Semua Kegiatan</h2>
  <?php if(empty($events)): ?>
    <p>Tidak ada kegiatan.</p>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>#</th><th>Judul</th><th>Tanggal</th><th>Oleh</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($events as $i => $ev): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?=htmlspecialchars($ev['title'])?></td>
          <td><?=htmlspecialchars($ev['event_date'])?></td>
          <td><?=htmlspecialchars($ev['username'])?></td>
          <td>
            <a href="../edit_event.php?id=<?= $ev['id'] ?>">Edit</a>
            <a href="../delete_event.php?id=<?= $ev['id'] ?>" onclick="return confirm('Hapus event?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php require '../includes/footer.php'; ?>