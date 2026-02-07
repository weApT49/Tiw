<?php
session_start();
include "connect.php";

// ตรวจสอบว่าเป็นแอดมินหรือไม่ (เพื่อความปลอดภัย)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

// ตรวจสอบว่ามีการส่ง ID และ Status มาหรือไม่
if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $new_status = $_GET['status'];

    // ป้องกัน SQL Injection และอัปเดตสถานะ
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    
    if ($stmt->execute([$new_status, $order_id])) {
        // อัปเดตสำเร็จ ส่งกลับไปหน้า Admin Panel
        echo "<script>
            alert('อัปเดตสถานะเรียบร้อยแล้ว');
            window.location='index.php?page=admin_panel';
        </script>";
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการอัปเดต');
            window.history.back();
        </script>";
    }
} else {
    // ถ้าไม่มีข้อมูลส่งมา ให้กลับหน้า Admin Panel
    header("Location: index.php?page=admin_panel");
    exit();
}
?>