<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<nav class="nav">
  <div class="nav-left">
    <a href="/Native-MySched-App/index.php" class="brand">MySched</a>
  </div>
  <div class="nav-right">
    <?php if(isset($_SESSION['user'])): ?>
      <span>Hello, <?=htmlspecialchars($_SESSION['user']['username'])?></span>
      <a href="/Native-MySched-App/add_event.php">Tambah</a>
      <?php if($_SESSION['user']['role']==='admin'): ?>
        <a href="/Native-MySched-App/admin/index.php">Admin</a>
      <?php endif; ?>
      <a href="/Native-MySched-App/logout.php">Logout</a>
    <?php else: ?>
      <a href="/Native-MySched-App/login.php">Login</a>
      <a href="/Native-MySched-App/register.php">Register</a>
    <?php endif; ?>
  </div>
</nav>
<hr>