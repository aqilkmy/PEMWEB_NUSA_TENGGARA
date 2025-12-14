<?php defined('APP_ACCESS') or die('Direct access not permitted'); ?>
<footer id="footer">
    <div class="footer-content">
        <div class="footer-section-about">
            <h2><?php echo APP_NAME; ?></h2>
            <p>Menjelajahi keindahan Nusa Tenggara Timur bersama kami. Temukan destinasi impian Anda hari ini!</p>
            <div class="about-logo">
                <a href="#"><img src="<?php echo BASE_URL; ?>assets/images/fb.png" alt="Facebook"></a>
                <a href="#"><img src="<?php echo BASE_URL; ?>assets/images/ig.png" alt="Instagram"></a>
                <a href="#"><img src="<?php echo BASE_URL; ?>assets/images/yt.png" alt="YouTube"></a>
            </div>
        </div>
        <div class="footer-section-links">
            <h2>Menu</h2>
            <div class="footer-menu">
                <a href="<?php echo BASE_URL; ?>index.php">Beranda</a>
                <a href="<?php echo BASE_URL; ?>pages/destination.php">Destinasi</a>
                <a href="<?php echo BASE_URL; ?>pages/about.php">Tentang</a>
                <a href="<?php echo BASE_URL; ?>pages/contact.php">Kontak</a>
            </div>
        </div>
        <div class="footer-section-contact">
            <h2>Kontak Kami</h2>
            <div class="footer-contact">
                <div class="contact-type">
                    <p>Email</p>
                    <p>Telepon</p>
                    <p>Alamat</p>
                </div>
                <div class="contact-name">
                    <p><?php echo CONTACT_EMAIL; ?></p>
                    <p><?php echo CONTACT_PHONE; ?></p>
                    <p><?php echo CONTACT_ADDRESS; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>