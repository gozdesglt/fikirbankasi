<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Hakkında | Yaratıcı Fikir Bankası</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- HEADER -->
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
                    <a href="login.php" class="btn-login">Giriş</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="container">
        <h1>Hakkımızda</h1>

        <div class="card">
            <p>
                <strong>Yaratıcı Fikir Bankası</strong>, farklı alanlardaki yaratıcı fikirlerin
                paylaşıldığı, geliştirildiği ve ilham kaynağı olduğu bir platformdur.
            </p>

            <p>
                Bu platform; teknoloji, eğitim, tasarım, mobil ve bilim gibi birçok kategoride
                fikirlerin tek bir yerde toplanmasını amaçlar.
            </p>

            <p>
                Kullanıcılar fikir ekleyebilir, fikirleri inceleyebilir ve
                kendi projeleri için ilham alabilir.
            </p>

            <p>
                Amacımız; yaratıcılığı desteklemek, üretkenliği artırmak ve
                fikirlerin kaybolmasını engellemektir.
            </p>
        </div>

        <div class="card">
            <h2>📌 Neler Yapabilirsin?</h2>
            <ul style="line-height:1.8;">
                <li>Fikir paylaşabilirsin</li>
                <li>Kategorilere göre fikirleri filtreleyebilirsin</li>
                <li>Fikirlerine dosya ekleyebilirsin</li>
                <li>Kendi fikirlerini silebilirsin</li>
            </ul>
        </div>
    </div>

</body>
</html>
