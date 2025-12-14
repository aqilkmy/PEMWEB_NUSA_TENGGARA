<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

$page_title = 'Tentang Kami - ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/ntt.png">
    <style>
        .about-container {
            max-width: 900px;
            margin: 120px auto 40px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .about-container h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }

        .about-container p {
            color: #333;
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: justify;
        }
    </style>
</head>

<body>
    <div class="gradient-bg"></div>
    <?php include '../includes/header.php'; ?>

    <div class="about-container">
        <h1>Tentang Kami</h1>

        <p>WonderfulNTT adalah platform informasi wisata yang bertujuan memperkenalkan keindahan alam, budaya, dan kekayaan kuliner Nusa Tenggara Timur. Kami hadir untuk membantu wisatawan menemukan referensi perjalanan terbaik melalui informasi destinasi, tips perjalanan, dan cerita pengalaman dari para traveler.</p>

        <p>Kami percaya bahwa pariwisata dapat menjadi jembatan untuk memajukan ekonomi lokal. Karena itu, kami berkomitmen menyediakan informasi yang akurat dan bermanfaat bagi wisatawan, sekaligus ruang untuk pelaku wisata mempromosikan layanan mereka.</p>

        <p>Website ini dikelola oleh komunitas pecinta wisata NTT, dan terbuka untuk kolaborasi dan kontribusi konten. Mari bersama memajukan pariwisata NTT!</p>

        <p style="text-align: center; margin-top: 40px;">
            <strong>Hubungi kami untuk kolaborasi atau informasi lebih lanjut.</strong>
        </p>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>