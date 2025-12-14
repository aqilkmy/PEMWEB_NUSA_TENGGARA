<?php
require_once 'config.php';

// Handle search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch destinations from database with search
if (!empty($search)) {
    $query = "SELECT * FROM destinasi WHERE nama LIKE '%$search%' OR lokasi LIKE '%$search%' OR deskripsi LIKE '%$search%' ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM destinasi ORDER BY created_at DESC";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="asset/ntt.png">
</head>

<body style="background-image: url('asset/destination-bg.png');
    background-size: cover;">
    <div class="gradient-bg"></div>
    <?php include 'header.php'; ?>

    <section class="about-page">
        <div class="search-container">
            <h1>Tentang Kami</h1>
            <p style="color:white;">Kenali lebih dekat tentang WonderfulNTT</p>
        </div>

        <div class="info-selection">
            <div class="info-row1">
                <div class="info-box" style="background: white; padding: 30px; border-radius: 15px;">
                    <h1 style="color: var(--primary-color); margin-bottom: 15px;">Tentang WonderfulNTT</h1>
                    <p style="color: #333; line-height: 1.8; margin-bottom: 20px;">WonderfulNTT adalah platform informasi wisata yang bertujuan memperkenalkan keindahan alam, budaya, dan kekayaan kuliner Nusa Tenggara Timur. Kami hadir untuk membantu wisatawan menemukan referensi perjalanan terbaik melalui informasi destinasi, tips perjalanan, dan cerita pengalaman dari para traveler.</p>
                    
                    <h1 style="color: var(--primary-color); margin-bottom: 15px; margin-top: 25px;">Visi Kami</h1>
                    <p style="color: #333; line-height: 1.8; margin-bottom: 20px;">Kami percaya bahwa pariwisata dapat menjadi jembatan untuk memajukan ekonomi lokal. Karena itu, kami berkomitmen menyediakan informasi yang akurat dan bermanfaat bagi wisatawan, sekaligus ruang untuk pelaku wisata mempromosikan layanan mereka.</p>
                    
                    <h1 style="color: var(--primary-color); margin-bottom: 15px; margin-top: 25px;">Komunitas Kami</h1>
                    <p style="color: #333; line-height: 1.8;">Website ini dikelola oleh komunitas pecinta wisata NTT, dan terbuka untuk kolaborasi dan kontribusi konten. Mari bersama memajukan pariwisata NTT!</p>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>
<?php
mysqli_close($conn);
?>