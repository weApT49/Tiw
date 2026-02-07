<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Access Denied'); window.location='index.php';</script>";
    exit();
}

date_default_timezone_set('Asia/Bangkok');

if(isset($_GET['delete_prod'])) {
    $stmt = $pdo->prepare("DELETE FROM product WHERE id_menu = ?");
    $stmt->execute([$_GET['delete_prod']]);
    echo "<script>window.location='index.php?page=admin_panel';</script>";
}

// Stats
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$sales = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status IN ('paid','shipped')")->fetchColumn() ?: 0;
$products = $pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
?>

<div class="container">
    
    <div style="background:var(--bg-dark); color:white; padding:40px; border-bottom:4px solid var(--accent-gold); margin-bottom:40px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1 style="margin:0; font-family:'Cinzel', serif;">Executive Dashboard</h1>
            <p style="opacity:0.7; margin:5px 0 0;">Timeless Luxury Management</p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:1.2rem; color:var(--accent-gold);"><?=date('F d, Y')?></div>
            <div style="font-size:0.9rem;">Status: Online</div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:30px; margin-bottom:50px;">
        <div style="background:white; padding:30px; border:1px solid #E0E0E0; border-left:5px solid #C0A062; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
            <div style="font-size:0.9rem; text-transform:uppercase; color:#666;">Pending Orders</div>
            <div style="font-size:2.5rem; font-weight:700; color:var(--bg-dark);"><?=$pending?></div>
        </div>
        <div style="background:white; padding:30px; border:1px solid #E0E0E0; border-left:5px solid #2E7D32; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
            <div style="font-size:0.9rem; text-transform:uppercase; color:#666;">Total Revenue</div>
            <div style="font-size:2.5rem; font-weight:700; color:var(--bg-dark);"><?=number_format($sales)?></div>
        </div>
        <div style="background:white; padding:30px; border:1px solid #E0E0E0; border-left:5px solid #0A1929; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
            <div style="font-size:0.9rem; text-transform:uppercase; color:#666;">Inventory</div>
            <div style="font-size:2.5rem; font-weight:700; color:var(--bg-dark);"><?=$products?></div>
        </div>
    </div>

    <!-- Add Product Section -->
    <div style="background:#FFF; padding:40px; border:1px solid #E0E0E0; margin-bottom:50px;">
        <h3 style="color:var(--bg-dark); font-family:'Cinzel', serif; border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:30px;">Add New Timepiece</h3>
        <form action="add-db.php" method="post" enctype="multipart/form-data">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:20px;">
                <div>
                    <label class="form-label">Model Name</label>
                    <input type="text" name="name_menu" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Price (THB)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <label class="form-label">Brand / Category</label>
                <input type="text" name="how" class="form-control" placeholder="e.g. Rolex">
            </div>
            <div style="margin-bottom:20px;">
                <label class="form-label">Description</label>
                <textarea name="des_menu" class="form-control" rows="3"></textarea>
            </div>
            <div style="margin-bottom:30px;">
                <label class="form-label">Image</label>
                <input type="file" name="img" class="form-control" required style="padding:10px;">
            </div>
            <button type="submit" class="btn-primary">Add to Inventory</button>
        </form>
    </div>

    <!-- Inventory List -->
    <h3 style="color:var(--bg-dark); font-family:'Cinzel', serif; margin-bottom:20px;">Inventory List</h3>
    <div style="background:white; border:1px solid #E0E0E0; overflow-x:auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $prods = $pdo->query("SELECT * FROM product ORDER BY id_menu DESC");
                while($p = $prods->fetch()){
                ?>
                <tr>
                    <td><img src="image/<?=$p['img']?>" style="width:60px; border:1px solid #eee;"></td>
                    <td>
                        <strong style="color:var(--bg-dark);"><?=$p['name_menu']?></strong><br>
                        <small style="color:#888;"><?=$p['how']?></small>
                    </td>
                    <td style="color:#C0A062; font-weight:700;"><?=number_format($p['price'])?></td>
                    <td>
                        <a href="edit_product.php?id=<?=$p['id_menu']?>" style="color:#C0A062; margin-right:15px; font-weight:bold;">EDIT</a>
                        <a href="index.php?page=admin_panel&delete_prod=<?=$p['id_menu']?>" style="color:#C62828;" onclick="return confirm('Delete this item?')">DELETE</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>