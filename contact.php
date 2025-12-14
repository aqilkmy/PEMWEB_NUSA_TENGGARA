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
            <h1>Hubungi Kami</h1>
            <p style="color:white;">Kami siap membantu terkait kebutuhanmu!</p>
    </div>

        <div class="info-selection">
        <div class="info-row1">

            <!-- INFO KONTAK -->
            <div class="info-box">
                <h1>Email</h1>
                <p>wonderfulntt@gmail.com</p>

                <h1>Telepon</h1>
                <p>0812-3456-7890</p>

                <h1>Alamat</h1>
                <p>Jl. Penuh Kepercayaan No. 67</p>
            </div>

            <!-- FORM -->
            <div class="info-box">
                <form action="kontak_process.php" method="POST">
                    <input type="text" name="nama" placeholder="Nama" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px;margin-bottom:10px;">
                    <textarea name="pesan" placeholder="Pesan" required style="width:100%;padding:10px;height:120px;"></textarea>

                    <button type="submit" class="btn-detail" style="margin-top:15px;">
                        Kirim
                    </button>
                </form>
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