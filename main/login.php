<div class="container" style="display:flex; justify-content:center; align-items:center; min-height:70vh;">
    <div class="login-card" style="width:100%; max-width:450px;">
        <div style="text-align:center; margin-bottom:30px;">
            <h2 style="color:var(--accent-gold); font-family:'Playfair Display', serif; font-size:2.5rem; margin:0;">Welcome Back</h2>
            <p style="color:var(--text-muted);">Sign in to access your account</p>
        </div>

        <form action="auth_action.php?action=login" method="post">
            <div style="margin-bottom:20px;">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-input" placeholder="08XXXXXXXX" required>
            </div>
            <div style="margin-bottom:30px;">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login" style="width:100%;">Sign In</button>
        </form>

        <div style="text-align:center; margin-top:30px; border-top:1px solid #333; padding-top:20px;">
            <span style="color:var(--text-muted);">New to Momo Black?</span>
            <a href="index.php?page=register" style="color:var(--accent-gold); font-weight:bold;">Create Account</a>
        </div>
    </div>
</div>