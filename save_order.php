<?php
session_start();
include "connect.php";

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$uid = $_SESSION['user_id'];
$items_to_buy = isset($_POST['checkout_items']) ? $_POST['checkout_items'] : [];

// ตรวจสอบว่ามีการส่งรายการสินค้ามาหรือไม่
if(empty($items_to_buy)) { 
    echo "<script>alert('Error: No items selected.'); window.location='index.php';</script>"; 
    exit();
}

// รับข้อมูลจากฟอร์ม
$name = $_POST['receiver_name'];
$phone = $_POST['receiver_phone'];
$address = $_POST['receiver_address'];
$payment = $_POST['payment_method'];
$slip_name = null;

// จัดการอัปโหลดสลิป (กรณีโอนเงิน)
if ($payment == 'transfer' && isset($_FILES['slip_image']) && $_FILES['slip_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION));
    
    if (in_array($ext, $allowed)) {
        $slip_name = "slip_" . time() . "_" . $uid . "." . $ext;
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if(!is_dir("image/slips")) mkdir("image/slips", 0777, true);
        
        move_uploaded_file($_FILES['slip_image']['tmp_name'], "image/slips/" . $slip_name);
    }
}

// คำนวณยอดเงินรวมใหม่จากฐานข้อมูล (เพื่อความถูกต้องและปลอดภัย)
$total = 0;
// สร้างเครื่องหมาย ? ตามจำนวนสินค้าที่เลือก
$placeholders = implode(',', array_fill(0, count($items_to_buy), '?'));

// ** แก้ไขจุดสำคัญ: เปลี่ยนชื่อตารางเป็น product **
$stmt = $pdo->prepare("SELECT * FROM product WHERE id_menu IN ($placeholders)");
$stmt->execute($items_to_buy);
$products = $stmt->fetchAll();

// คำนวณยอดรวม
foreach($products as $p) {
    // เอาจำนวนสินค้าจาก Session
    if (isset($_SESSION['cart'][$p['id_menu']])) {
        $qty = $_SESSION['cart'][$p['id_menu']];
        $total += $p['price'] * $qty;
    }
}

try {
    // เริ่ม Transaction (ทำงานพร้อมกันทุกคำสั่ง ถ้าพลาดอย่างใดอย่างหนึ่งจะยกเลิกทั้งหมด)
    $pdo->beginTransaction();
    
    // 1. บันทึกข้อมูลลงตาราง orders
    $sql = "INSERT INTO orders (user_id, total_price, receiver_name, receiver_address, receiver_phone, status, payment_method, slip_image) 
            VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uid, $total, $name, $address, $phone, $payment, $slip_name]);
    
    // ดึง ID ของออเดอร์ล่าสุดที่เพิ่งสร้าง
    $order_id = $pdo->lastInsertId();

    // 2. บันทึกรายละเอียดสินค้าลงตาราง order_details
    $detailSql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmtDetail = $pdo->prepare($detailSql);

    foreach($products as $p) {
        if (isset($_SESSION['cart'][$p['id_menu']])) {
            $qty = $_SESSION['cart'][$p['id_menu']];
            $stmtDetail->execute([$order_id, $p['id_menu'], $qty, $p['price']]);
            
            // 3. ลบสินค้าที่ซื้อแล้วออกจากตะกร้า (Session)
            unset($_SESSION['cart'][$p['id_menu']]);
        }
    }

    // ยืนยันการทำงาน (Commit)
    $pdo->commit();
    
    // แจ้งเตือนและไปหน้าประวัติการสั่งซื้อ
    echo "<script>alert('Order placed successfully! Order ID: #$order_id'); window.location='index.php?page=my_orders';</script>";

} catch (Exception $e) {
    // หากเกิดข้อผิดพลาด ให้ยกเลิกการทำงานทั้งหมด (Rollback)
    $pdo->rollBack();
    echo "Transaction Error: " . $e->getMessage();
}
?>