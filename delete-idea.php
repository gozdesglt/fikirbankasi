<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST["id"])) {
    header("Location: ideas.php");
    exit;
}

$idea_id = $_POST["id"];

// Fikir var mı + kullanıcıya mı ait?
$stmt = $pdo->prepare("SELECT * FROM ideas WHERE id = ?");
$stmt->execute([$idea_id]);
$idea = $stmt->fetch();

if (!$idea) {
    header("Location: ideas.php");
    exit;
}

// Yetki kontrolü
if ($idea["user_id"] != $_SESSION["user_id"]) {
    die("Bu fikri silme yetkin yok!");
}

// Dosya varsa sil
if (!empty($idea["file"])) {
    $filePath = "uploads/" . $idea["file"];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Veritabanından sil
$delete = $pdo->prepare("DELETE FROM ideas WHERE id = ?");
$delete->execute([$idea_id]);

header("Location: ideas.php");
exit;
