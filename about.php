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
    <title>Destinasi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="asset/ntt.png">
</head>

<body>
    <div class="gradient-bg"></div>
    <?php include 'header.php'; ?>

    <section class="destination-page">
        <div class="search-container">
            <h1>Tentang Kami</h1>
        </div>

        <p>WonderfulNTT adalah platform informasi wisata yang bertujuan memperkenalkan keindahan alam, budaya, dan kekayaan kuliner Nusa Tenggara Timur. Kami hadir untuk membantu wisatawan menemukan referensi perjalanan terbaik melalui informasi destinasi, tips perjalanan, dan cerita pengalaman dari para traveler.</p>
        <p>Kami percaya bahwa pariwisata dapat menjadi jembatan untuk memajukan ekonomi lokal. Karena itu, kami berkomitmen menyediakan informasi yang akurat dan bermanfaat bagi wisatawan, sekaligus ruang untuk pelaku wisata mempromosikan layanan mereka.</p>
        <p>Website ini dikelola oleh komunitas pecinta wisata NTT, dan terbuka untuk kolaborasi dan kontribusi konten. Mari bersama memajukan pariwisata NTT!</p>
    </section>
</body>

</html>
<?php
mysqli_close($conn);
?>