<?php include "connect.php"; ?>

<div class="container">
    <div style="text-align:center; margin-bottom:60px; border-bottom:1px solid #333; padding-bottom:40px;">
        <h1 style="font-size:4rem; margin:0; color:white;">ALL <span style="color:var(--accent-color);">DROPS</span></h1>
        <p style="color:#888; letter-spacing:1px; text-transform:uppercase;">Limited Stock Available</p>
    </div>

    <div class="product-grid">
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product ORDER BY id_menu DESC");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
        ?>
        <div class="product-card">
            <div style="position:absolute; top:10px; right:10px; background:var(--accent-color); color:white; padding:2px 8px; font-size:0.7rem; font-weight:bold; z-index:10;">NEW</div>
            
            <div class="product-img-wrapper">
                <img src="image/<?=$row['img']?>" alt="<?=$row['name_menu']?>" class="product-img" onerror="this.src='https://via.placeholder.com/300x300/1a1a1a/333333?text=SNEAKER'">
            </div>
            
            <div class="product-info">
                <div class="product-category"><?=!empty($row['how']) ? $row['how'] : 'Sneakers'?></div>
                <div class="product-name"><?=$row['name_menu']?></div>
                <div class="product-price"><?=number_format($row['price'])?> THB</div>
                
                <div style="margin-top:20px;">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="cart_action.php?action=add&id=<?=$row['id_menu']?>" class="btn-buy" style="width:100%; text-align:center;">
                            <span>ADD TO CART</span>
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn-secondary" style="width:100%; text-align:center; display:block; border-color:#333; color:#888;">
                            LOGIN TO COP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>