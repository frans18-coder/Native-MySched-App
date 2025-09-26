<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $date  = trim($_POST['event_date'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if($title === '') $errors[] = "Judul wajib diisi";
    if($date === '') $errors[] = "Tanggal wajib diisi";

    if(empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO events (user_id, title, event_date, description) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isss", $_SESSION['user']['id'], $title, $date, $desc);
        mysqli_stmt_execute($stmt);
        header('Location: index.php');
        exit;
    }
}

require 'includes/header.php';
require 'includes/navbar.php';
?>
<div class="container">
  <h2>Tambah Kegiatan</h2>
  <?php if($errors): foreach($errors as $e): ?>
    <div class="alert"><?=$e?></div>
  <?php endforeach; endif; ?>

  <form method="post" action="">
    <label>Judul</label>
    <input type="text" name="title" required>
    <label>Tanggal</label>
    <input type="date" name="event_date" required>
    <label>Deskripsi</label>
    <textarea name="description"></textarea>
    <button type="submit">Simpan</button>
  </form>
</div>
<?php require 'includes/footer.php'; ?>