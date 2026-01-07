<?php
// ================================
// DB BAĞLANTISI (Railway)
// ================================
$host = getenv("MYSQLHOST");
$db = getenv("MYSQLDATABASE");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$port = getenv("MYSQLPORT");

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}

// ================================
// MYSQL 8 UYUMLU GROUP BY SORGUSU
// ================================
$sql = "
SELECT 
    c.id,
    c.name,
    COUNT(p.id) AS product_count
FROM categories c
LEFT JOIN products p ON p.category_id = c.id
GROUP BY c.id, c.name
ORDER BY c.name ASC
";

$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();

// ================================
// ÇIKTI
// ================================
echo '<pre>';
print_r($categories);
echo '</pre>';
