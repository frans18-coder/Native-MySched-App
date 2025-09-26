<?php
require_once '../db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header('Location: ../index.php');
    exit;
}
require '../includes/header.php';
require '../includes/navbar.php';
?>
<div class="container">
  <h2>Admin Dashboard</h2>
  <p>Selamat datang, admin <?=htmlspecialchars($_SESSION['user']['username'])?></p>
  <ul>
    <li><a href="manage_events.php">Kelola semua kegiatan</a></li>
    <li><a href="../index.php">Kembali ke website</a></li>
  </ul>
</div>
<?php require '../includes/footer.php'; ?>