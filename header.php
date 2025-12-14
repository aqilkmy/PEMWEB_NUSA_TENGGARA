<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<nav id="navbar">
        <div class="isi-navbar">
            <div class="kiri-navbar">
                <img src="asset/ntt.png" alt="" class="logo" id="logo">
                <p style="margin: 0px 20px;" id="logo-text">WonderfulNTT</p>
            </div>
                <div class="kanan-navbar">
                    <div class="nav-links">
                        <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a>
                        <a href="destination.php" class="<?php echo ($current_page == 'destination.php') ? 'active' : ''; ?>">Destinasi</a>
                        <a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">Tentang</a>
                        <a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Kontak</a>
                    </div>
                    <div class="login-register">
                        <?php if (isset($_SESSION['user_id'])): ?>            
                            <a href="logout.php" class="btn-register">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn-login">Login</a>
                            <a href="register.php" class="btn-register">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
    </nav>

<script>
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        const logo = document.getElementById('logo');
        const logoText = document.getElementById('logo-text');
        
        if (window.scrollY > 200) {
            navbar.classList.add('scrolled');
            logo.style.opacity = '0';
            logo.style.width = '0';
            logoText.style.opacity = '0';
            logoText.style.width = '0';
        } else {
            navbar.classList.remove('scrolled');
            logo.style.opacity = '1';
            logo.style.width = '50px';
            logoText.style.opacity = '1';
            logoText.style.width = 'auto';
        }
    });
</script>