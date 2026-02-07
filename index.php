<?php 
if (session_status() == PHP_SESSION_NONE) { session_start(); }
date_default_timezone_set('Asia/Bangkok');
include "connect.php";
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=SHOP_NAME?> - Exclusive Timepieces</title>
<link rel="icon" href="image/icon.png" type="image/x-icon">
<link rel="stylesheet" href="assset/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">TIMELESS <span>LUXURY</span></a>
            
            <button class="nav-toggle" onclick="toggleMenu()" style="background:none; border:none; color:white; font-size:1.5rem; display:none; cursor:pointer;">☰</button>

            <div class="nav-menu" id="navMenu">
                <a href="index.php" class="nav-item">Home</a>
                <a href="index.php?page=product" class="nav-item">Collection</a>
                <a href="index.php?page=cart" class="nav-item">Cart</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=my_orders" class="nav-item">My Orders</a>
                <?php endif; ?>
                <a href="index.php?page=about" class="nav-item">About</a>
                <a href="index.php?page=contact" class="nav-item">Contact</a>
            </div>

            <div class="user-actions" style="display:flex; align-items:center; gap:20px;">
                <a href="index.php?page=cart" style="font-size:1.2rem; color:var(--accent-gold);" title="Shopping Cart">🛒</a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span style="font-size:0.8rem; color:#ccc; display:none; @media(min-width:900px){display:inline;}">Welcome, <strong style="color:white;"><?=$_SESSION['fullname']?></strong></span>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="index.php?page=admin_panel" style="color:var(--accent-gold); font-size:0.8rem; font-weight:bold;">[ADMIN]</a>
                    <?php endif; ?>
                    <a href="auth_action.php?action=logout" class="btn-secondary" style="padding:5px 15px; font-size:0.7rem;">LOGOUT</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="nav-item">LOGIN</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Banner (เฉพาะหน้าแรก) -->
    <?php if(empty($_GET['page']) || $_GET['page'] == 'firstmain'): ?>
    <div class="banner-wrapper" style="width:100%; height:600px; background:#000; position:relative; overflow:hidden;">
        <!-- ใช้รูปนาฬิกาหรู -->
        <img src="image/banner.jpg" alt="Luxury Watch" style="width:100%; height:100%; object-fit:cover; opacity:0.8; filter: brightness(0.7);" onerror="this.src=''">
        
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; width:100%;">
            <p style="color:var(--accent-gold); font-size:1.2rem; letter-spacing:3px; text-transform:uppercase; margin-bottom:20px;">Precision & Elegance</p>
            <h1 style="color:white; font-size:4.5rem; margin:0; font-family:'Cinzel', serif; letter-spacing:5px;">TIMELESS LUXURY</h1>
            <a href="index.php?page=product" class="btn-primary" style="margin-top:40px;">Explore Collection</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php 
            if(!empty($_GET['page'])){
                $page = $_GET['page'];
                if(file_exists("main/$page.php")) include("main/$page.php");
                else include("main/firstmain.php");
            }else{
                include("main/firstmain.php");
            }
        ?>
    </div>

    <!-- Footer -->
    <footer>
        <div style="font-family:'Cinzel', serif; font-size:2rem; color:white; margin-bottom:20px; letter-spacing:2px;">TIMELESS LUXURY</div>
        <p style="font-size:0.9rem; opacity:0.7; max-width:600px; margin:0 auto 30px;">
            We curate the finest timepieces from around the world. Authentic, certified, and timeless.
        </p>
        <div style="border-top:1px solid #333; padding-top:30px; font-size:0.8rem; opacity:0.5;">
            &copy; <?=date('Y')?> Timeless Luxury Watch. All rights reserved.
        </div>
    </footer>

    <script src="assset/javascript.js"></script>
    <script>
        function toggleMenu() {
            var menu = document.getElementById('navMenu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
                menu.style.flexDirection = 'column';
                menu.style.position = 'absolute';
                menu.style.top = '90px';
                menu.style.left = '0';
                menu.style.width = '100%';
                menu.style.background = '#0A1929';
                menu.style.padding = '20px';
            }
        }
    </script>
</body>
</html>