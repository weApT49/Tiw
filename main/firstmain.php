<style>
    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }
    .section-title h2 {
        font-size: 2.5rem;
        color: var(--bg-dark);
        margin-bottom: 15px;
    }
    .divider {
        width: 80px; height: 3px; background: var(--accent-gold); margin: 0 auto;
    }
    
    .brand-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 100px;
    }
    .brand-box {
        position: relative;
        height: 350px;
        overflow: hidden;
        cursor: pointer;
    }
    .brand-box img {
        width: 100%; height: 100%; object-fit: cover; transition: 0.6s ease;
    }
    .brand-box:hover img { transform: scale(1.1); }
    .brand-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(10, 25, 41, 0.4);
        display: flex; align-items: center; justify-content: center;
        transition: 0.3s;
    }
    .brand-box:hover .brand-overlay { background: rgba(10, 25, 41, 0.2); }
    .brand-name {
        color: white; font-family: 'Cinzel', serif; font-size: 2rem; letter-spacing: 2px;
        border-bottom: 2px solid var(--accent-gold); padding-bottom: 5px;
    }
</style>

<div class="container">
    
    <!-- Brands -->
    <div class="brand-grid">
        <div class="brand-box">
            <img src="" onerror="this.src='https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=600&q=80'">
            <div class="brand-overlay"><span class="brand-name">ROLEX</span></div>
        </div>
        <div class="brand-box">
            <img src="[Image of นาฬิกา Patek Philippe]" onerror="this.src='https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80'">
            <div class="brand-overlay"><span class="brand-name">PATEK</span></div>
        </div>
        <div class="brand-box">
            <img src="" onerror="this.src='https://images.unsplash.com/photo-1547996160-81dfa63595aa?auto=format&fit=crop&w=600&q=80'">
            <div class="brand-overlay"><span class="brand-name">OMEGA</span></div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="section-title">
        <h2>Exclusive Arrivals</h2>
        <div class="divider"></div>
        <p style="color:#888; margin-top:15px; font-family:'Cinzel', serif;">Masterpieces of horology</p>
    </div>

    <div class="product-grid">
        <?php
        if(isset($pdo)) {
            $stmt = $pdo->prepare("SELECT * FROM product ORDER BY id_menu DESC LIMIT 4");
            $stmt->execute();
            while($row = $stmt->fetch()){
        ?>
        <div class="product-card">
            <div class="product-img-wrapper">
                <img src="image/<?=$row['img']?>" alt="<?=$row['name_menu']?>" class="product-img" onerror="this.src='image/icon.png'">
            </div>
            <div class="product-info">
                <div class="product-category"><?=$row['how']?></div>
                <div class="product-name"><?=$row['name_menu']?></div>
                <div class="product-price"><?=number_format($row['price'])?> THB</div>
                <a href="index.php?page=product" class="btn-secondary" style="display:inline-block; margin-top:15px;">View Details</a>
            </div>
        </div>
        <?php 
            } 
        } 
        ?>
    </div>
</div>