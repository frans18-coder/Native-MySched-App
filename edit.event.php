<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($res);
if(!$event) {
    die("Event tidak ditemukan");
}

// cek hak akses: pemilik atau admin
if($_SESSION['user']['id'] != $event['user_id'] && $_SESSION['user']['role'] !== 'admin'){
    die("Tidak punya hak akses untuk mengedit event ini.");
}

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $date  = trim($_POST['event_date'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if($title === '') $errors[] = "Judul wajib diisi";
    if($date === '') $errors[] = "Tanggal wajib diisi";

    if(empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, event_date = ?, description = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $title, $date, $desc, $id);
        mysqli_stmt_execute($stmt);
        header('Location: index.php');
        exit;
    }
}

require 'includes/header.php';
require 'includes/navbar.php';
?>
<div class="container">
  <h2>Edit Kegiatan</h2>
  <?php if($errors): foreach($errors as $e): ?>
    <div class="alert"><?=$e?></div>
  <?php endforeach; endif; ?>

  <form method="post" action="">
    <label>Judul</label>
    <input type="text" name="title" value="<?=htmlspecialchars($event['title'])?>" required>
    <label>Tanggal</label>
    <input type="date" name="event_date" value="<?=htmlspecialchars($event['event_date'])?>" required>
    <label>Deskripsi</label>
    <textarea name="description"><?=htmlspecialchars($event['description'])?></textarea>
    <button type="submit">Update</button>
  </form>
</div>
<?php require 'includes/footer.php'; ?>