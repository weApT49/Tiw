<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// ตั้งค่าชื่อร้านที่นี่ (เปลี่ยนได้ตามต้องการ)
define('SHOP_NAME', 'TIMELESS LUXURY');

// เชื่อมต่อฐานข้อมูล
$host = "localhost";
$dbname = "luxury_watch"; // ใช้ชื่อ DB ใหม่
$username = "root";
$password = "12345678"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>