<?php
session_start();
include "connect.php";

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Access Denied'); window.location='index.php';</script>";
    exit();
}

$id = $_GET['id'];

// บันทึกการแก้ไข
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name_menu'];
    $price = $_POST['price'];
    $des = $_POST['des_menu'];
    $how = $_POST['how'];
    
    // ตรวจสอบการอัปโหลดรูปภาพใหม่
    if(!empty($_FILES['img']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)){
            $img = "prod_" . time() . "." . $ext;
            move_uploaded_file($_FILES['img']['tmp_name'], "image/" . $img);
            
            // อัปเดตข้อมูลรวมถึงรูปภาพ
            $sql = "UPDATE product SET name_menu=?, price=?, des_menu=?, how=?, img=? WHERE id_menu=?";
            $params = [$name, $price, $des, $how, $img, $id];
        }
    } else {
        // อัปเดตข้อมูลโดยไม่เปลี่ยนรูป
        $sql = "UPDATE product SET name_menu=?, price=?, des_menu=?, how=? WHERE id_menu=?";
        $params = [$name, $price, $des, $how, $id];
    }
    
    if(isset($sql)){
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo "<script>alert('Product updated successfully!'); window.location='index.php?page=admin_panel';</script>";
    }
}

// ดึงข้อมูลสินค้าเดิม
$stmt = $pdo->prepare("SELECT * FROM product WHERE id_menu = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if(!$p) {
    echo "Product not found.";
    exit();
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product - Momo Black</title>
<link rel="icon" href="image/icon.png" type="image/x-icon">
<link rel="stylesheet" href="assset/style.css">
<style>
    /* Override Body for centered form */
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: var(--bg-body);
    }
    
    .edit-wrapper {
        width: 100%;
        max-width: 600px;
        padding: 20px;
    }

    .img-preview-box {
        text-align: center;
        margin-bottom: 20px;
        background: #000;
        padding: 20px;
        border: 1px dashed #333;
        border-radius: 4px;
    }

    .current-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid var(--accent-gold);
        margin-bottom: 10px;
    }

    .btn-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-group button, .btn-group a {
        flex: 1;
        text-align: center;
    }
</style>
</head>
<body>

    <div class="edit-wrapper">
        <div class="form-card">
            <div style="text-align:center; margin-bottom:30px; border-bottom:1px solid #333; padding-bottom:20px;">
                <h2 style="color:var(--accent-gold); margin:0; font-family:'Playfair Display', serif;">EDIT PRODUCT</h2>
                <p style="color:var(--text-muted); margin-top:5px; font-size:0.9rem;">แก้ไขข้อมูล: <span style="color:white;"><?=$p['name_menu']?></span></p>
            </div>

            <form method="post" enctype="multipart/form-data">
                <div style="margin-bottom:20px;">
                    <label class="form-label">PRODUCT NAME</label>
                    <input type="text" name="name_menu" class="form-input" value="<?=$p['name_menu']?>" required>
                </div>
                
                <div style="display: flex; gap: 20px; margin-bottom:20px;">
                    <div style="flex: 1;">
                        <label class="form-label">PRICE (THB)</label>
                        <input type="number" name="price" class="form-input" value="<?=$p['price']?>" required>
                    </div>
                    
                    <div style="flex: 1;">
                        <label class="form-label">CATEGORY</label>
                        <input type="text" name="how" class="form-input" value="<?=$p['how']?>" placeholder="e.g. Sneaker">
                    </div>
                </div>
                
                <div style="margin-bottom:20px;">
                    <label class="form-label">DESCRIPTION</label>
                    <textarea name="des_menu" class="form-input" rows="4" style="resize:vertical;"><?=$p['des_menu']?></textarea>
                </div>
                
                <div style="margin-bottom:20px;">
                    <label class="form-label">PRODUCT IMAGE</label>
                    <div class="img-preview-box">
                        <img src="image/<?=$p['img']?>" class="current-img" id="imgPreview" onerror="this.src='https://via.placeholder.com/150x150/000000/D4AF37?text=No+Image'">
                        <input type="file" name="img" class="form-input" style="padding:10px; margin-bottom:0;" onchange="previewImage(this)">
                        <div style="font-size: 0.8rem; color: #666; margin-top: 10px;">Leave blank to keep current image</div>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn-primary">SAVE CHANGES</button>
                    <a href="index.php?page=admin_panel" class="btn-secondary" style="border-color:#333; color:#888;">CANCEL</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>