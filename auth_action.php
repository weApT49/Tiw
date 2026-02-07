<?php
include "connect.php";

$action = $_GET['action'];

// --- สมัครสมาชิก ---
if ($action == 'register') {
    $phone = $_POST['phone'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสลับ
    $name = $_POST['fullname'];
    $addr = $_POST['address'];

    // เช็คเบอร์ซ้ำ
    $stmt = $pdo->prepare("SELECT phone FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    if($stmt->rowCount() > 0){
        echo "<script>alert('เบอร์โทรนี้เป็นสมาชิกแล้ว'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO users (phone, password, fullname, address) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$phone, $pass, $name, $addr])) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ! กรุณาล็อกอิน'); window.location='index.php?page=login';</script>";
    }
}

// --- เข้าสู่ระบบ ---
elseif ($action == 'login') {
    $phone = $_POST['phone'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>alert('เบอร์โทรหรือรหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
    }
}

// --- ออกจากระบบ ---
elseif ($action == 'logout') {
    session_destroy();
    echo "<script>window.location='index.php';</script>";
}
?>