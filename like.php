<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$idea_id = $_POST["idea_id"];
$user_id = $_SESSION["user_id"];

/* 🔍 Daha önce beğenmiş mi? */
$check = $pdo->prepare("
    SELECT * FROM likes 
    WHERE idea_id = ? AND user_id = ?
");
$check->execute([$idea_id, $user_id]);

if ($check->rowCount() == 0) {

    /* ❤️ BEĞEN */
    $stmt = $pdo->prepare("
        INSERT INTO likes (idea_id, user_id) 
        VALUES (?, ?)
    ");
    $stmt->execute([$idea_id, $user_id]);

    /* 🔔 BİLDİRİM EKLE */
    $stmt = $pdo->prepare("
        SELECT user_id FROM ideas WHERE id = ?
    ");
    $stmt->execute([$idea_id]);
    $ideaOwner = $stmt->fetchColumn();

    // Kendi fikrine bildirim gitmesin
    if ($ideaOwner && $ideaOwner != $user_id) {
        $msg = "Fikrin beğenildi ❤️";
        $link = "ideas.php";

        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, message, link)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$ideaOwner, $msg, $link]);
    }

} else {

    /* 🤍 BEĞENİYİ KALDIR */
    $stmt = $pdo->prepare("
        DELETE FROM likes 
        WHERE idea_id = ? AND user_id = ?
    ");
    $stmt->execute([$idea_id, $user_id]);
}

header("Location: ideas.php");
exit;
