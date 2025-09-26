<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) session_start();

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if($username === '' || $password === '') $errors[] = "Username & password wajib diisi";

    if(empty($errors)) {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        if($user && password_verify($password, $user['password'])) {
            // simpan session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Username atau password salah";
        }
    }
}

require 'includes/header.php';
require 'includes/navbar.php';
?>
<div class="container">
  <h2>Login</h2>
  <?php if($errors): foreach($errors as $e): ?>
    <div class="alert"><?=$e?></div>
  <?php endforeach; endif; ?>

  <form method="post" action="">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>
</div>
<?php require 'includes/footer.php'; ?>