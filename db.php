<?php

$databaseUrl = getenv("DATABASE_URL");

if (!$databaseUrl) {
    die("DATABASE_URL bulunamadı");
}

$url = parse_url($databaseUrl);

$host = $url['host'];
$port = $url['port'];
$user = $url['user'];
$pass = $url['pass'];
$db = ltrim($url['path'], '/');

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

?>