<?php
try {
    $dbUrl = getenv("DATABASE_URL");

    if (!$dbUrl) {
        die("DATABASE_URL bulunamadı");
    }

    $parts = parse_url($dbUrl);

    $host = $parts["host"];
    $port = $parts["port"];
    $user = $parts["user"];
    $pass = $parts["pass"];
    $db = ltrim($parts["path"], "/");

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "✅ Database bağlantısı başarılı";

} catch (PDOException $e) {
    die("❌ Bağlantı hatası: " . $e->getMessage());
}
