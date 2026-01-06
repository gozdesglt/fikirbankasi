<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["save"])) {

    $fileName = null;

    // 📁 Upload klasörü yoksa oluştur
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // 📎 Dosya yükleme
    if (!empty($_FILES["file"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $fileName);
    }

    // 💾 Veritabanına kaydet
    $stmt = $pdo->prepare("
        INSERT INTO ideas 
        (title, short_desc, description, category_id, tags, file, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST["title"],
        $_POST["short_desc"],
        $_POST["description"],
        $_POST["category_id"],
        $_POST["tags"],
        $fileName,
        $_SESSION["user_id"]
    ]);

    // 🔁 POST → REDIRECT → GET
    header("Location: add-idea.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Fikir Ekle</title>
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

            <span>👤 <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
            <a href="logout.php">Çıkış</a>
        </div>
    </div>
</header>

<div class="container">
    <h1>Fikir Ekle</h1>

    <?php if (isset($_GET["success"])): ?>
        <p style="color:green;text-align:center;">
            Fikir başarıyla kaydedildi!
        </p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" enctype="multipart/form-data">

            <label>Başlık</label>
            <input name="title" required>

            <label>Kısa Açıklama</label>
            <input name="short_desc">

            <label>Detaylı Açıklama</label>
            <textarea name="description"></textarea>

            <label>Kategori Seç</label>
            <select name="category_id" required>
                <option value="">Kategori Seç</option>
                <?php
                $cats = $pdo->query("SELECT * FROM categories");
                foreach ($cats as $cat) {
                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                }
                ?>
            </select>

            <label>Etiketler</label>
            <input name="tags">

            <label>Dosya Yükleme</label>
            <input type="file" name="file">

            <button name="save">Fikri Kaydet</button>
        </form>
    </div>
</div>

</body>
</html>
