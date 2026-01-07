<?php
session_start();
require "db.php";

$categories = $pdo->query("
  SELECT 
    categories.id,
    categories.name,
    COUNT(ideas.id) AS idea_count
  FROM categories
  LEFT JOIN ideas ON ideas.category_id = categories.id
  GROUP BY categories.id, categories.name
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Yaratıcı Fikir Bankası</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="nav">
            <strong>Yaratıcı Fikir Bankası</strong>
            <div>
                <a href="index.php">🏠 Anasayfa</a>
                <a href="ideas.php">💡 Fikirler</a>
                <a href="categories.php">📂 Kategoriler</a>
                <a href="add-idea.php">📝 Fikir Ekle</a>
                <a href="about.php">ℹ️ Hakkında</a>

                <?php if (isset($_SESSION["user_id"])): ?>
                    <span>👤 <?= $_SESSION["user_name"] ?></span>
                    <a href="logout.php">Çıkış</a>
                <?php else: ?>
                    <a href="login.php">Giriş</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- 🔥 HERO ALANI -->
    <div class="container">
        <div class="card" style="text-align:center; padding:30px;">
            <h1>💡 Yaratıcı Fikir Bankası</h1>
            <p style="color:#555; font-size:18px; margin-top:10px;">
                Fikirlerini özgürce paylaşabileceğin, başkalarının bakış açısından ilham alabileceğin ve
                ilgi alanına göre kategoriler üzerinden yeni düşünceler keşfedebileceğin bir platform.
                Burada ister teknoloji, ister tasarım, ister girişimcilik olsun; her fikir değerlidir.
                Yaratıcılığını ortaya koy, başkalarının fikirlerini incele ve birlikte daha güçlü
                projeler üret.

            </p>

            <div style="margin-top:20px;">
                <a href="ideas.php" class="btn">Fikirleri Gör</a>
                <a href="add-idea.php" class="btn" style="margin-left:10px;">Fikir Ekle</a>
            </div>
        </div>
    </div>

    <!-- 📂 KATEGORİLER -->
    <div class="container">
        <h2 style="margin-bottom:15px;">📂 Kategoriler</h2>

        <div class="grid">
            <?php foreach ($categories as $cat): ?>
                <a href="ideas.php?category_id=<?= $cat["id"] ?>" class="card"
                    style="text-align:center; text-decoration:none;">
                    <h3>📁 <?= htmlspecialchars($cat["name"]) ?></h3>
                    <p style="color:#555;"><?= $cat["idea_count"] ?> fikir</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>