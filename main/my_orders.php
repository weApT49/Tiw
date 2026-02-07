<?php
if (!isset($_SESSION['user_id'])) { 
    echo "<script>window.location='index.php?page=login';</script>"; 
    exit(); 
}
$uid = $_SESSION['user_id'];
date_default_timezone_set('Asia/Bangkok');
?>

<style>
    .order-page-header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        padding: 40px 0;
        border-bottom: 1px solid #E0E0E0;
    }
    .order-page-header h1 {
        color: var(--bg-dark);
        font-size: 2.5rem;
        margin: 0;
    }
    .order-page-header p {
        color: var(--text-light);
        font-style: italic;
        margin-top: 10px;
    }

    /* Order Card Luxury Style */
    .order-card {
        background: #FFFFFF;
        border: 1px solid #E0E0E0;
        margin-bottom: 30px;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    .order-card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 10px 30px rgba(10, 25, 41, 0.1);
    }

    .card-header {
        background: var(--bg-dark);
        color: var(--text-white);
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .card-body {
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 30px;
    }

    .info-group label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-light);
        margin-bottom: 5px;
    }
    .info-group span {
        font-family: 'Cinzel', serif;
        font-size: 1.2rem;
        color: var(--bg-dark);
        font-weight: 700;
    }

    /* Status Badges */
    .status-badge {
        padding: 8px 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        border: 1px solid;
    }
    .st-pending { color: #C0A062; border-color: #C0A062; background: rgba(192, 160, 98, 0.1); }
    .st-paid { color: #2E7D32; border-color: #2E7D32; background: rgba(46, 125, 50, 0.1); }
    .st-shipped { color: #0A1929; border-color: #0A1929; background: rgba(10, 25, 41, 0.1); }
    .st-cancelled { color: #C62828; border-color: #C62828; background: rgba(198, 40, 40, 0.1); }

    /* Modal Overlay */
    .modal-overlay {
        background: rgba(10, 25, 41, 0.85); /* Midnight Blue Overlay */
        backdrop-filter: blur(5px);
    }
    .modal-content {
        border: 2px solid var(--accent-gold);
        background: #FFF;
        padding: 40px;
    }
</style>

<div class="container">
    
    <div class="order-page-header">
        <h1>Purchase History</h1>
        <p>ประวัติการสั่งซื้อเรือนเวลาอันทรงคุณค่าของคุณ</p>
    </div>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC");
    $stmt->execute([$uid]);

    if ($stmt->rowCount() == 0) {
        echo "<div style='text-align:center; padding:80px; border:1px solid #ddd; background:#fff;'>
                <h3 style='font-family:Cinzel, serif; color:#0A1929;'>No Purchase History</h3>
                <p>คุณยังไม่มีประวัติการสั่งซื้อ</p>
                <a href='index.php?page=product' class='btn-primary' style='margin-top:20px;'>Discover Collection</a>
              </div>";
    }

    while($row = $stmt->fetch()){
        $st = $row['status'];
        $stClass = 'st-' . $st;
        $payMethod = ($row['payment_method'] == 'cod') ? 'Cash on Delivery' : 'Bank Transfer';
    ?>

    <div class="order-card">
        <div class="card-header">
            <div>
                <span style="color:var(--accent-gold); font-family:'Cinzel',serif;">ORDER NO.</span>
                <span style="font-size:1.2rem; margin-left:10px;">#<?=str_pad($row['order_id'], 6, '0', STR_PAD_LEFT)?></span>
            </div>
            <div style="font-size:0.9rem; opacity:0.8;">
                <?=date('F d, Y', strtotime($row['order_date']))?>
            </div>
        </div>
        
        <div class="card-body">
            <div class="info-group">
                <label>Payment Method</label>
                <span style="font-family:'Lato', sans-serif; font-size:1rem;"><?=$payMethod?></span>
            </div>
            <div class="info-group">
                <label>Total Amount</label>
                <span><?=number_format($row['total_price'])?> <small style="font-size:0.5em;">THB</small></span>
            </div>
            
            <div class="info-group">
                <label>Status</label>
                <span class="status-badge <?=$stClass?>"><?=$st?></span>
            </div>

            <div style="text-align:right;">
                <?php if($row['payment_method'] == 'transfer' && $st == 'pending' && empty($row['slip_image'])): ?>
                    <button onclick="openUploadModal(<?=$row['order_id']?>, <?=$row['total_price']?>)" class="btn-primary" style="padding:10px 25px; font-size:0.8rem;">
                        Confirm Payment
                    </button>
                <?php elseif(!empty($row['slip_image'])): ?>
                    <a href="image/slips/<?=$row['slip_image']?>" target="_blank" style="color:var(--bg-dark); text-decoration:underline; font-size:0.9rem;">View Receipt</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php } ?>
</div>

<!-- Modal Upload -->
<div id="uploadModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; justify-content:center; align-items:center;">
    <div class="modal-content" style="width:90%; max-width:450px; position:relative;">
        <span onclick="document.getElementById('uploadModal').style.display='none'" style="position:absolute; right:20px; top:20px; cursor:pointer; font-size:1.5rem;">&times;</span>
        
        <h3 style="color:var(--bg-dark); text-align:center; font-family:'Cinzel', serif; margin-bottom:30px;">Payment Confirmation</h3>
        
        <div style="text-align:center; margin-bottom:30px; padding:20px; background:#F9F9F9; border:1px solid #E0E0E0;">
            <p style="margin-bottom:10px; font-size:0.9rem;">Scan QR to pay</p>
            <img id="qrImg" style="width:150px; border:5px solid #fff; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
            <div style="margin-top:15px; font-family:'Cinzel', serif; font-weight:700; font-size:1.2rem; color:var(--bg-dark);">
                <span id="modalPrice"></span> THB
            </div>
        </div>

        <form action="upload_slip.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="order_id" id="modalOrderId">
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:10px; font-weight:700;">Upload Bank Slip</label>
                <input type="file" name="slip_image" required class="form-control">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;">Submit Payment</button>
        </form>
    </div>
</div>

<script>
function openUploadModal(oid, price) {
    document.getElementById('uploadModal').style.display = 'flex';
    document.getElementById('modalOrderId').value = oid;
    document.getElementById('modalPrice').innerText = price.toLocaleString();
    document.getElementById('qrImg').src = "https://promptpay.io/0888888888/" + price + ".png"; // เปลี่ยนเบอร์พร้อมเพย์ตรงนี้
}
window.onclick = function(e) {
    if(e.target == document.getElementById('uploadModal')) document.getElementById('uploadModal').style.display = 'none';
}
</script>