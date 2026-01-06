<?php
session_start();
require "db.php";

$error = "";

if (isset($_POST["login"])) {

  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user["password"])) {

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];

    header("Location: index.php");
    exit;

  } else {
    $error = "E-posta veya şifre hatalı!";
  }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <title>Giriş</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <header>
    <div class="nav">
      <strong>Yaratıcı Fikir Bankası</strong>
    </div>
  </header>

  <div class="container">
    <h1>Giriş</h1>

    <div class="card" style="max-width:420px;margin:auto;">

      <?php if ($error): ?>
        <p style="color:red;margin-bottom:15px;">
          <?= $error ?>
        </p>
      <?php endif; ?>

      <form method="POST">
        <label>E-posta</label>
        <input type="email" name="email" required>

        <label>Şifre</label>
        <input type="password" name="password" required>

        <button name="login">Giriş Yap</button>
        <p style="margin-top:15px;text-align:center;">
          Hesabın yok mu? <a href="register.php">Kayıt Ol</a>
        </p>
      </form>

    </div>
  </div>

</body>

</html>