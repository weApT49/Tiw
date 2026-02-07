<?php
session_start();
include "connect.php";

if(!isset($_SESSION['user_id'])) exit();

$oid = $_POST['order_id'];
$uid = $_SESSION['user_id'];

if(isset($_FILES['slip_image']) && $_FILES['slip_image']['error'] == 0) {
    $ext = pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION);
    $name = "slip_" . time() . "_" . $uid . "." . $ext;
    move_uploaded_file($_FILES['slip_image']['tmp_name'], "image/slips/" . $name);
    
    $stmt = $pdo->prepare("UPDATE orders SET slip_image = ?, status = 'pending' WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$name, $oid, $uid]);
    
    echo "<script>alert('อัปโหลดสลิปเรียบร้อย'); window.location='index.php?page=my_orders';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลด'); window.history.back();</script>";
}
?>