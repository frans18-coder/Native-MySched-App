<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT user_id FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$ev = mysqli_fetch_assoc($res);
if(!$ev) {
    die("Event tidak ditemukan");
}

// cek hak akses
if($_SESSION['user']['id'] != $ev['user_id'] && $_SESSION['user']['role'] !== 'admin'){
    die("Tidak punya hak akses untuk menghapus event ini.");
}

$stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header('Location: index.php');
exit;