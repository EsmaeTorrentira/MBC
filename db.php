<?php
$host = "127.0.0.1";
$port = "3307"; // your XAMPP MariaDB port
$dbname = "mbc_db";
$user = "root";
$pass = ""; // no password in your setup

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
