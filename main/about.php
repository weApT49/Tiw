<style>
    .about-hero {
        text-align: center;
        padding: 80px 0;
        background-color: var(--bg-dark);
        color: white;
        margin-bottom: 60px;
    }
    .about-hero h1 { font-size: 3rem; color: var(--accent-gold); margin-bottom: 10px; }
    .about-hero p { max-width: 600px; margin: 0 auto; opacity: 0.8; font-weight: 300; }

    .story-section {
        display: flex;
        align-items: center;
        gap: 60px;
        margin-bottom: 100px;
    }
    .story-img {
        flex: 1;
        position: relative;
    }
    .story-img img {
        width: 100%;
        display: block;
        box-shadow: 20px 20px 0 var(--bg-dark);
    }
    .story-text {
        flex: 1;
    }
    .story-text h2 {
        font-size: 2.5rem;
        color: var(--bg-dark);
        margin-bottom: 30px;
        border-left: 4px solid var(--accent-gold);
        padding-left: 20px;
    }
    .story-text p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 20px;
        font-size: 1.05rem;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        text-align: center;
        margin-bottom: 80px;
    }
    .value-box {
        padding: 40px;
        background: #FFF;
        border: 1px solid #E0E0E0;
        transition: 0.3s;
    }
    .value-box:hover { transform: translateY(-10px); border-color: var(--accent-gold); }
    .value-icon { font-size: 2.5rem; color: var(--accent-gold); margin-bottom: 20px; display: block; }
    .value-box h3 { font-family: 'Cinzel', serif; margin-bottom: 15px; }

    @media (max-width: 768px) {
        .story-section { flex-direction: column; }
        .values-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="about-hero">
    <h1>Our Heritage</h1>
    <p>สืบสานตำนานแห่งเวลา ด้วยความประณีตและความหรูหราที่เป็นอมตะ</p>
</div>

<div class="container">
    <div class="story-section">
        <div class="story-img">
            <img src="[Image of ช่างทำนาฬิกา]" onerror="this.src='https://images.unsplash.com/photo-1596516109370-29001ec8ec36?auto=format&fit=crop&w=800&q=80'">
        </div>
        <div class="story-text">
            <h2>The Art of Timekeeping</h2>
            <p>
                <strong>TIMELESS LUXURY</strong> ก่อตั้งขึ้นด้วยความหลงใหลในกลไกแห่งเวลา เราไม่ได้เป็นเพียงผู้จัดจำหน่าย แต่เราคือนักสะสมที่เข้าใจในคุณค่าของนาฬิกาทุกเรือน เราคัดสรรเฉพาะแบรนด์ระดับโลกที่มีประวัติศาสตร์ยาวนาน และงานฝีมือที่ไร้ที่ติ
            </p>
            <p>
                นาฬิกาจากเราทุกเรือน ผ่านการตรวจสอบโดยผู้เชี่ยวชาญ (Certified Authentic) เพื่อให้มั่นใจว่าคุณจะได้รับสิ่งที่ดีที่สุด ไม่ใช่แค่เครื่องบอกเวลา แต่คือมรดกที่ส่งต่อได้จากรุ่นสู่รุ่น
            </p>
        </div>
    </div>

    <div class="values-grid">
        <div class="value-box">
            <span class="value-icon">💎</span>
            <h3>Authenticity</h3>
            <p>การันตีของแท้ 100% พร้อมใบรับประกันจากแบรนด์ผู้ผลิต</p>
        </div>
        <div class="value-box">
            <span class="value-icon">⚙️</span>
            <h3>Precision</h3>
            <p>ดูแลและตรวจสอบกลไกโดยช่างผู้ชำนาญการระดับ Master Watchmaker</p>
        </div>
        <div class="value-box">
            <span class="value-icon">🛡️</span>
            <h3>Warranty</h3>
            <p>บริการหลังการขายและการรับประกันระดับพรีเมียมสำหรับลูกค้าคนพิเศษ</p>
        </div>
    </div>
</div>
