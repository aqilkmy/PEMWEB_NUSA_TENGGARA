<?php defined('APP_ACCESS') or die('Direct access not permitted'); ?>
<nav id="navbar">
    <div class="isi-navbar">
        <div class="kiri-navbar">
            <img src="<?php echo BASE_URL; ?>assets/images/ntt.png" alt="<?php echo APP_NAME; ?>" class="logo" id="logo">
            <p style="margin: 0px 20px;" id="logo-text"><?php echo APP_NAME; ?></p>
        </div>
        <div class="kanan-navbar">
            <div class="nav-links">
                <a href="<?php echo BASE_URL; ?>index.php" class="<?php echo active_menu('index.php'); ?>">Beranda</a>
                <a href="<?php echo BASE_URL; ?>pages/destination.php" class="<?php echo active_menu('destination.php'); ?>">Destinasi</a>
                <a href="<?php echo BASE_URL; ?>pages/about.php" class="<?php echo active_menu('about.php'); ?>">Tentang</a>
                <a href="<?php echo BASE_URL; ?>pages/contact.php" class="<?php echo active_menu('contact.php'); ?>">Kontak</a>
            </div>
            <div class="login-register">
                <?php if (is_logged_in()): ?>
                    <span style="color: white; margin-right: 15px;">Halo, <?php echo htmlspecialchars(user_name()); ?></span>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="btn-login">Dashboard</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>auth/logout.php" class="btn-register">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn-login">Login</a>
                    <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn-register">Register</a>
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