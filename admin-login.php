<?php
require_once __DIR__ . '/../common.php';
// simple login form
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
    $stmt->execute([':u'=>$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password, $user['password_hash'])){
        $_SESSION['user_id'] = $user['id'];
        header('Location: /admin-dashboard.php'); exit;
    } else {
        $err = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login — Karam Real Estate</title>
  <link rel="stylesheet" href="/styles.css" />
</head>
<body>
<header><div class="brand"><div class="logo">MK</div><div><div style="font-weight:800">Karam Real Estate</div></div></div></header>
<main class="container">
  <div style="max-width:420px;margin:20px auto">
    <div class="card">
      <h3 style="margin-top:0">Admin Login</h3>
      <?php if(!empty($err)): ?><div style="color:#b00020"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <form method="post">
        <label>Username</label>
        <input name="username" class="input" />
        <label>Password</label>
        <input name="password" type="password" class="input" />
        <div style="margin-top:8px"><button type="submit">Login</button></div>
      </form>
      <div class="muted" style="margin-top:8px">Default admin: <strong>admin</strong> / <strong>admin123</strong></div>
    </div>
  </div>
</main>
</body>
</html>
