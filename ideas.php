<?php
session_start();
require "db.php";

/* 📂 TÜM KATEGORİLER */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

/* ✅ SEÇİLİ KATEGORİLER */
$selected = [];

/* KATEGORİ KARTINDAN GELDİYSE */
if (isset($_GET["category_id"])) {
    $selected[] = $_GET["category_id"];
}

/* SOL FİLTREDEN GELDİYSE */
if (isset($_GET["categories"]) && is_array($_GET["categories"])) {
    $selected = $_GET["categories"];
}

/* 🔎 SQL FİLTRE */
$where = "";
$params = [];

if (!empty($selected)) {
    $placeholders = implode(",", array_fill(0, count($selected), "?"));
    $where = "WHERE ideas.category_id IN ($placeholders)";
    $params = $selected;
}

/* 👤 GİRİŞ YAPAN KULLANICI */
$userId = $_SESSION["user_id"] ?? 0;

/* 💡 FİKİRLER + ❤️ BEĞENİ */
$stmt = $pdo->prepare("
    SELECT DISTINCT 
           ideas.id,
           ideas.title,
           ideas.short_desc,
           ideas.description,
           ideas.file,
           ideas.created_at,
           ideas.user_id,
           categories.name AS category_name,
           users.name AS user_name,

           /* ❤️ Beğeni sayısı */
           (SELECT COUNT(*) FROM likes WHERE likes.idea_id = ideas.id) AS like_count,

           /* 🤍 Kullanıcı beğenmiş mi */
           (SELECT COUNT(*) 
            FROM likes 
            WHERE likes.idea_id = ideas.id 
              AND likes.user_id = ?) AS liked

    FROM ideas
    LEFT JOIN categories ON ideas.category_id = categories.id
    LEFT JOIN users ON ideas.user_id = users.id
    $where
    ORDER BY ideas.created_at DESC
");

$stmt->execute(array_merge([$userId], $params));
$ideas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Fikirler</title>
<link rel="stylesheet" href="style.css">
<style>
.layout{display:flex;gap:25px}
.sidebar{width:260px;background:#fff;padding:20px;border-radius:14px;box-shadow:0 6px 20px rgba(0,0,0,.06)}
.sidebar h3{margin-bottom:15px}
.filter-item{display:flex;align-items:center;margin-bottom:10px}
.filter-item input{margin-right:10px}
.content{flex:1}
</style>
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
    <span>👤 <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
    <a href="logout.php">Çıkış</a>
<?php else: ?>
    <a href="login.php">Giriş</a>
<?php endif; ?>
</div>
</div>
</header>

<div class="container layout">

<!-- 🔍 SOL FİLTRE -->
<aside class="sidebar">
<form method="GET">
<h3>📂 Kategoriler</h3>

<?php foreach ($categories as $cat): ?>
<div class="filter-item">
    <input type="checkbox"
           name="categories[]"
           value="<?= $cat["id"] ?>"
           <?= in_array($cat["id"], $selected) ? "checked" : "" ?>>
    <label><?= htmlspecialchars($cat["name"]) ?></label>
</div>
<?php endforeach; ?>

<button style="margin-top:15px;width:100%;">Filtrele</button>

<?php if (!empty($selected)): ?>
<a href="ideas.php" style="display:block;margin-top:10px;text-align:center;">
Filtreyi Temizle
</a>
<?php endif; ?>
</form>
</aside>

<!-- 📄 FİKİRLER -->
<div class="content">
<h1>Paylaşılan Fikirler</h1>

<?php if (count($ideas) === 0): ?>
<p>Fikir bulunamadı.</p>
<?php endif; ?>

<?php foreach ($ideas as $idea): ?>
<div class="card" style="margin-bottom:20px;">

<h2><?= htmlspecialchars($idea["title"]) ?></h2>

<p><?= htmlspecialchars($idea["short_desc"]) ?></p>

<p><?= nl2br(htmlspecialchars($idea["description"])) ?></p>

<p style="font-size:14px;color:#555;">
🏷️ <?= htmlspecialchars($idea["category_name"] ?? "Kategori Yok") ?> |
👤 <?= htmlspecialchars($idea["user_name"] ?? "Bilinmiyor") ?> |
🕒 <?= $idea["created_at"] ?>
</p>

<!-- ❤️ BEĞENİ -->
<form action="like.php" method="POST" style="margin-top:10px;">
    <input type="hidden" name="idea_id" value="<?= $idea["id"] ?>">
    <button type="submit">
        <?= $idea["liked"] ? "❤️" : "🤍" ?>
        <?= $idea["like_count"] ?>
    </button>
</form>

<?php if (!empty($idea["file"])): ?>
<p>
    <a href="uploads/<?= htmlspecialchars($idea["file"]) ?>" target="_blank">
        📎 Dosya
    </a>
</p>
<?php endif; ?>

<?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $idea["user_id"]): ?>
<form action="delete-idea.php" method="POST"
      onsubmit="return confirm('Bu fikri silmek istiyor musun?');">
    <input type="hidden" name="id" value="<?= $idea["id"] ?>">
    <button style="background:#dc2626;">Sil</button>
</form>
<?php endif; ?>

</div>
<?php endforeach; ?>

</div>
</div>

</body>
</html>
