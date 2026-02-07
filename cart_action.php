<?php
include "connect.php";

$action = $_GET['action'];

// เพิ่มสินค้า
if ($action == 'add') {
    $id = $_GET['id'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: index.php?page=cart");
}

// ลบสินค้า
elseif ($action == 'delete') {
    $id = $_GET['id'];
    unset($_SESSION['cart'][$id]);
    header("Location: index.php?page=cart");
}

// อัปเดตจำนวน
elseif ($action == 'update') {
    foreach ($_POST['qty'] as $id => $num) {
        if ($num <= 0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id] = $num;
    }
    header("Location: index.php?page=cart");
}
?>