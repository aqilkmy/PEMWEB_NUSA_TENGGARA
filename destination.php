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

<body class="destination-body">
    <div class="gradient-bg"></div>
    <div class="destination-parallax-bg"></div>
    <?php include 'header.php';  ?>

    <section class="destination-page">
        <div class="search-container">
            <h1>Mau ke mana?</h1>
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" placeholder="Cari destinasi..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            </form>
        </div>

        <div class="destination-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Truncate description to 100 characters
                    $short_desc = substr($row['deskripsi'], 0, 100);
                    if (strlen($row['deskripsi']) > 100) {
                        $short_desc .= '...';
                    }
                    ?>
                    <div class="destination-card">
                        <div class="des-card">
                            <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                            <div class="des-card-content">
                                <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
                                <p><?php echo htmlspecialchars($short_desc); ?></p>
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Detail</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p style="color: #666; text-align: center; width: 100%;">Destinasi tidak ditemukan.</p>';
            }
            ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>
<?php
mysqli_close($conn);
?>