<?php
if (!isset($_SESSION['user_id'])) { echo "<script>window.location='index.php';</script>"; exit(); }

// รับค่าจากหน้า Cart
$selected_ids = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];
$qtys = isset($_POST['qty']) ? $_POST['qty'] : [];

if (empty($selected_ids)) {
    echo "<script>alert('Please select at least one item.'); window.history.back();</script>";
    exit();
}

// อัปเดตจำนวนสินค้าล่าสุดลง Session
foreach($qtys as $pid => $q) {
    if(isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] = $q;
    }
}

$uid = $_SESSION['user_id'];
$user = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$user->execute([$uid]);
$userData = $user->fetch();

// ดึงข้อมูลสินค้าเฉพาะที่เลือก
$ids_str = implode(",", $selected_ids);
$products = $pdo->query("SELECT * FROM product WHERE id_menu IN ($ids_str)")->fetchAll();

$total = 0;
?>

<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 50px;
        font-family: 'Kanit', sans-serif;
    }

    .checkout-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--accent-gold);
        margin-bottom: 30px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .checkout-layout {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
    }

    /* Left Section */
    .form-section {
        background: var(--bg-card);
        padding: 30px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .section-header {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--text-main);
        margin-bottom: 20px;
        border-bottom: 1px solid #333;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-header span { color: var(--accent-gold); }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; color: var(--accent-gold); font-size: 0.9rem; }
    .form-input {
        width: 100%;
        padding: 12px;
        background: #000;
        border: 1px solid #333;
        color: #fff;
        border-radius: 4px;
        transition: 0.3s;
    }
    .form-input:focus { border-color: var(--accent-gold); outline: none; }

    /* Payment Options */
    .payment-option {
        background: #000;
        border: 1px solid #333;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.3s;
    }
    .payment-option:hover { border-color: var(--accent-gold); }
    .payment-option input { accent-color: var(--accent-gold); width: 18px; height: 18px; }
    .payment-title { font-weight: 500; color: var(--text-main); font-size: 1rem; }
    .payment-desc { font-size: 0.8rem; color: var(--text-muted); display: block; }

    /* Right Section (Summary) */
    .summary-section {
        background: var(--bg-card);
        padding: 30px;
        border-radius: 8px;
        border: 1px solid var(--accent-gold);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #333;
        font-size: 0.95rem;
    }
    .item-name { color: var(--text-main); }
    .item-qty { color: var(--text-muted); font-size: 0.85rem; }
    .item-price { color: var(--accent-gold); font-weight: 500; }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--accent-gold);
    }
    .total-label { font-size: 1.1rem; color: var(--text-main); font-family: 'Playfair Display', serif; }
    .total-amount { font-size: 1.8rem; color: var(--accent-gold); font-weight: 700; }

    /* Transfer Info */
    #transfer-details {
        display: none;
        background: #000;
        padding: 20px;
        border: 1px dashed var(--accent-gold);
        margin-top: 20px;
        border-radius: 4px;
        text-align: center;
    }
    .qr-img { width: 160px; border-radius: 8px; border: 2px solid white; margin-bottom: 15px; }

    @media (max-width: 768px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .summary-section { position: static; margin-bottom: 30px; order: -1; }
    }
</style>

<div class="container checkout-container">
    <h2 class="checkout-title">Checkout</h2>

    <form action="save_order.php" method="post" enctype="multipart/form-data" onsubmit="return confirm('Confirm your order?');">
        
        <?php foreach($selected_ids as $sid): ?>
            <input type="hidden" name="checkout_items[]" value="<?=$sid?>">
        <?php endforeach; ?>

        <div class="checkout-layout">
            
            <!-- Left: Info & Payment -->
            <div class="form-section">
                
                <!-- Address -->
                <div class="section-header"><span>📍</span> Shipping Information</div>
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="receiver_name" value="<?=$userData['fullname']?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="receiver_phone" value="<?=$userData['phone']?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="receiver_address" rows="3" class="form-input" required><?=$userData['address']?></textarea>
                </div>

                <div style="margin: 40px 0; border-top:1px solid #333;"></div>

                <!-- Payment -->
                <div class="section-header"><span>💳</span> Payment Method</div>
                
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cod" onclick="togglePayment('cod')" checked>
                    <div>
                        <div class="payment-title">Cash on Delivery (COD)</div>
                        <span class="payment-desc">Pay cash upon arrival</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="transfer" onclick="togglePayment('transfer')">
                    <div>
                        <div class="payment-title">Bank Transfer / QR Code</div>
                        <span class="payment-desc">Direct bank transfer via Mobile Banking</span>
                    </div>
                </label>

                <!-- Transfer QR -->
                <div id="transfer-details">
                    <div style="color:var(--accent-gold); font-size:1.1rem; margin-bottom:10px;">Scan to Pay</div>
                    <!-- QR Code -->
                    <img src="https://promptpay.io/0826599047/<?=$total?>.png" class="qr-img">
                    
                    <div style="text-align:left; font-size:0.9rem; color:#ccc; margin-bottom:15px; border-top:1px solid #333; padding-top:10px;">
                        <strong>Bank:</strong> K-Bank (กสิกรไทย)<br>
                        <strong>Account:</strong> 123-4-56789-0 (Momo Black)<br>
                    </div>

                    <div style="text-align:left;">
                        <label class="form-label">Upload Slip *</label>
                        <input type="file" name="slip_image" id="slip_input" class="form-input" accept="image/*">
                    </div>
                </div>

            </div>

            <!-- Right: Summary -->
            <div class="summary-section">
                <div class="section-header" style="justify-content:center; border-bottom-color:var(--accent-gold);">Order Summary</div>
                
                <?php foreach($products as $p): 
                    $q = $_SESSION['cart'][$p['id_menu']];
                    $sum = $p['price'] * $q;
                    $total += $sum;
                ?>
                <div class="order-item">
                    <div>
                        <div class="item-name"><?=$p['name_menu']?></div>
                        <span class="item-qty">Qty: <?=$q?></span>
                    </div>
                    <div class="item-price"><?=number_format($sum)?></div>
                </div>
                <?php endforeach; ?>

                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-amount"><?=number_format($total)?> <small style="font-size:0.5em;">THB</small></span>
                </div>

                <button type="submit" class="btn-confirm" style="width:100%; margin-top:20px; font-size:1.1rem; padding:15px;">CONFIRM ORDER</button>
            </div>

        </div>
    </form>
</div>

<script>
function togglePayment(method) {
    const details = document.getElementById('transfer-details');
    const input = document.getElementById('slip_input');
    
    // Update QR Code Amount
    if(method === 'transfer') {
        details.style.display = 'block';
        input.required = true;
        // Update QR Code dynamically
        document.querySelector('.qr-img').src = "https://promptpay.io/0826599047/<?=$total?>.png";
    } else {
        details.style.display = 'none';
        input.required = false;
    }
}
</script>