<?php
session_start();
define('APP_ACCESS', true);

require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

// Get featured destinations
$query = "SELECT d.*, k.nama AS kategori_nama 
          FROM destinasi d
          LEFT JOIN kategori k ON d.kategori_id = k.id
          ORDER BY d.created_at DESC 
          LIMIT 3";
$result = mysqli_query($conn, $query);
$destinations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinations[] = $row;
}

$page_title = APP_NAME . ' - Jelajahi Nusa Tenggara Timur';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/ntt.png">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Jelajahi Nusa Tenggara Timur</h1>
                <p>Temukan keindahan dan budaya Nusa Tenggara Timur, permata tersembunyi Indonesia. Dari pantai-pantai yang memukau hingga tradisi yang semarak, mulailah perjalanan yang tak terlupakan.</p>
                <a href="<?php echo BASE_URL; ?>pages/destination.php" class="btn-hero">Mulai Sekarang</a>
            </div>
            <div class="hero-img">
                <img src="https://2.bp.blogspot.com/-3sr5Ru0WLrU/WixvQ0KYFwI/AAAAAAAAHwQ/G92was3cxtwTjyykjq1wE1A6p5UTuhwqwCLcBGAs/s1600/pulau%2Bpadar.jpg" alt="Pulau Padar">
            </div>
        </div>
    </section>

    <section class="offer">
        <h1>Yang Kami Tawarkan</h1>
        <div class="info-selection">
            <div class="info-row1">
                <div class="info-box">
                    <img src="<?php echo BASE_URL; ?>assets/images/destinasi.png" alt="Destinasi">
                    <h1>Destinasi Lengkap</h1>
                    <p>Dari pantai eksotis, gugusan pulau menawan, hingga pegunungan megah—semuanya dapat kamu eksplorasi dalam satu tempat.</p>
                </div>
                <div class="info-box">
                    <img src="<?php echo BASE_URL; ?>assets/images/komunitas.png" alt="Komunitas">
                    <h1>Komunitas Aktif</h1>
                    <p>Terhubung dengan wisatawan dan lokal yang siap berbagi pengalaman, tips perjalanan, serta cerita inspiratif.</p>
                </div>
            </div>
            <div class="info-row2">
                <div class="info-box">
                    <img src="<?php echo BASE_URL; ?>assets/images/rekomendasi.png" alt="Rekomendasi">
                    <h1>Rekomendasi Terpercaya</h1>
                    <p>Ulasan asli, rekomendasi akurat, dan informasi terbaru untuk membantumu merencanakan perjalanan terbaik.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="destination">
        <h1>Destinasi Populer</h1>
        <p>Tembus batas petualanganmu dengan pilihan lokasi <span style="font-weight: bold; color: #106277;">terbaik</span> paling banyak dikunjungi wisatawan.</p>

        <div class="destination-list">
            <?php foreach ($destinations as $dest): ?>
                <div class="destination-card">
                    <div class="card">
                        <h3><?php echo htmlspecialchars($dest['nama']); ?></h3>
                        <img src="<?php echo BASE_URL . htmlspecialchars($dest['gambar']); ?>" alt="<?php echo htmlspecialchars($dest['nama']); ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Cari lebih lanjut tentang destinasi-destinasi yang menarik!</h3>
        <a href="<?php echo BASE_URL; ?>pages/destination.php" class="btn-des">Lihat</a>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>

</html>