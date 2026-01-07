<?php
session_start();
require "db.php";

$categories = $pdo->query("
  SELECT 
    categories.id,
    MAX(categories.name) AS name,
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
    <title>Fikirler</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .layout {
            display: flex;
            gap: 25px
        }

        .sidebar {
            width: 260px;
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .06)
        }

        .sidebar h3 {
            margin-bottom: 15px
        }

        .filter-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px
        }

        .filter-item input {
            margin-right: 10px
        }

        .content {
            flex: 1
        }
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
                    <span>👤 <?= $_SESSION["user_name"] ?></span>
                    <a href="logout.php">Çıkış</a>
                <?php else: ?>
                    <a href="login.php">Giriş</a>
                <?php endif; ?>
            </div>
        </div>
    </header>


    <div style="padding:30px;max-width:1100px;margin:auto;">
        <h1>Kategoriler</h1>

        <!-- GRID -->
        <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
        gap:20px;
        margin-top:25px;
    ">
            <?php foreach ($categories as $cat): ?>

                <?php
                $icon = "📁";
                if ($cat["name"] == "Teknoloji")
                    $icon = "💻";
                elseif ($cat["name"] == "Eğitim")
                    $icon = "📚";
                elseif ($cat["name"] == "İş")
                    $icon = "💼";
                elseif ($cat["name"] == "Sağlık")
                    $icon = "🩺";
                elseif ($cat["name"] == "Sanat")
                    $icon = "🎨";
                ?>

                <a href="ideas.php?category_id=<?= $cat["id"] ?>" style="
               background:#fff;
               padding:25px;
               border-radius:12px;
               text-decoration:none;
               color:#111;
               box-shadow:0 6px 15px rgba(0,0,0,.1);
               text-align:center;
               display:block;
               ">

                    <div style="font-size:32px;margin-bottom:10px;"><?= $icon ?></div>
                    <h3 style="margin:10px 0;"><?= htmlspecialchars($cat["name"]) ?></h3>
                    <p style="color:#555;margin:0;"><?= $cat["idea_count"] ?> fikir</p>

                </a>

            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>