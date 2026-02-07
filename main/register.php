<div class="container" style="display:flex; justify-content:center; align-items:center; min-height:80vh;">
    <div class="reg-card" style="width:100%; max-width:550px;">
        <div style="text-align:center; margin-bottom:30px;">
            <h2 style="color:var(--accent-gold); font-family:'Playfair Display', serif; font-size:2.5rem; margin:0;">Join Momo Black</h2>
            <p style="color:var(--text-muted);">Exclusive privileges await you</p>
        </div>

        <form action="auth_action.php?action=register" method="post">
            <div style="margin-bottom:20px;">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-input" required placeholder="Your Name">
            </div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" required placeholder="08XXXXXXXX">
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
            </div>

            <div style="margin-bottom:30px;">
                <label class="form-label">Shipping Address</label>
                <textarea name="address" class="form-textarea" rows="3" required placeholder="Full Address"></textarea>
            </div>

            <button type="submit" class="btn-register" style="width:100%;">Create Account</button>
        </form>

        <div style="text-align:center; margin-top:30px; border-top:1px solid #333; padding-top:20px;">
            <span style="color:var(--text-muted);">Already a member?</span>
            <a href="index.php?page=login" style="color:var(--accent-gold); font-weight:bold;">Login here</a>
        </div>
    </div>
</div>