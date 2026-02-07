<?php
session_start();
include "connect.php";

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access Denied";
    exit();
}

$img_name = "";
// จัดการอัปโหลดรูปภาพ
if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
    
    if(in_array($ext, $allowed)){
        // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
        $img_name = "prod_" . time() . "." . $ext;
        move_uploaded_file($_FILES['img']['tmp_name'], "image/" . $img_name);
    }
}

// บันทึกข้อมูลลงตาราง product
$stmt = $pdo->prepare("INSERT INTO product (name_menu, des_menu, how, img, price) VALUES (?, ?, ?, ?, ?)");

if($stmt->execute([
    $_POST["name_menu"], 
    $_POST["des_menu"], 
    $_POST["how"], 
    $img_name, 
    $_POST["price"]
])){
    header("Location: index.php?page=admin_panel");
} else {
    echo "Error adding product.";
}
?>