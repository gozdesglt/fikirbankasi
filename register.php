<?php
require "db.php";

$error = "";
$success = "";

if (isset($_POST["register"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (strlen($password) < 6) {
        $error = "Şifre en az 6 karakter olmalıdır.";
    } else {

        // E-posta var mı kontrol et
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $error = "Bu e-posta zaten kayıtlı.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)
      ");

            $stmt->execute([$name, $email, $hashedPassword]);

            $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="nav">
            <strong>Yaratıcı Fikir Bankası</strong>
        </div>
    </header>

    <div class="container">
        <h1>Kayıt Ol</h1>

        <div class="card" style="max-width:420px;margin:auto;">

            <?php if ($error): ?>
                <p style="color:red;margin-bottom:15px;">
                    <?= $error ?>
                </p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p style="color:green;margin-bottom:15px;">
                    <?= $success ?>
                </p>
            <?php endif; ?>

            <form method="POST">

                <label>Ad Soyad</label>
                <input type="text" name="name" required>

                <label>E-posta</label>
                <input type="email" name="email" required>

                <label>Şifre</label>
                <input type="password" name="password" required>

                <button name="register">Kayıt Ol</button>
            </form>

            <p style="margin-top:15px;text-align:center;">
                Zaten hesabın var mı? <a href="login.php">Giriş Yap</a>
            </p>

        </div>
    </div>

</body>

</html>