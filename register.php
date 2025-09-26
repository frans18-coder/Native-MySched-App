<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if($username === '') $errors[] = "Username wajib diisi";
    if($password === '') $errors[] = "Password wajib diisi";
    if($password !== $confirm) $errors[] = "Password dan konfirmasi tidak cocok";

    // cek username unik
    $s = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($s, "s", $username);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if(mysqli_num_rows($r) > 0) $errors[] = "Username sudah dipakai";

    if(empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = mysqli_prepare($conn, "INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($ins, "sss", $username, $hash, $email);
        mysqli_stmt_execute($ins);
        header('Location: login.php');
        exit;
    }
}

require 'includes/header.php';
require 'includes/navbar.php';
?>
<div class="container">
  <h2>Register</h2>
  <?php if($errors): foreach($errors as $e): ?>
    <div class="alert"><?=$e?></div>
  <?php endforeach; endif; ?>

  <form method="post" action="">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Email</label>
    <input type="email" name="email">
    <label>Password</label>
    <input type="password" name="password" required>
    <label>Confirm Password</label>
    <input type="password" name="confirm" required>
    <button type="submit">Register</button>
  </form>
</div>
<?php require 'includes/footer.php'; ?>