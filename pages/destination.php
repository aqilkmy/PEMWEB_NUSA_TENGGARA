<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

// Handle search
$search = isset($_GET['search']) ? clean($_GET['search']) : '';

// Build query
if (!empty($search)) {
    $query = "SELECT d.*, k.nama AS kategori_nama 
              FROM destinasi d
              LEFT JOIN kategori k ON d.kategori_id = k.id
              WHERE d.nama LIKE '%$search%' 
              OR d.lokasi LIKE '%$search%' 
              OR d.deskripsi LIKE '%$search%'
              ORDER BY d.created_at DESC";
} else {
    $query = "SELECT d.*, k.nama AS kategori_nama 
              FROM destinasi d
              LEFT JOIN kategori k ON d.kategori_id = k.id
              ORDER BY k.id ASC, d.nama ASC";
}

$result = mysqli_query($conn, $query);

$page_title = 'Destinasi - ' . APP_NAME;
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

<body class="destination-body">
    <div class="gradient-bg"></div>
    <div class="destination-parallax-bg"></div>

    <?php include '../includes/header.php'; ?>

    <section class="destination-page">
        <div class="search-container">
            <h1>Mau ke mana?</h1>
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" placeholder="Cari destinasi..."
                    value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            </form>
        </div>

        <div class="destination-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php $short_desc = truncate($row['deskripsi'], 100); ?>
                    <div class="destination-card">
                        <div class="des-card">
                            <img src="<?php echo BASE_URL . htmlspecialchars($row['gambar']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>">
                            <div class="des-card-content">
                                <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
                                <p><?php echo htmlspecialchars($short_desc); ?></p>
                                <div class="action-row">
                                    <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Detail</a>
                                    <span class="label-kategori">
                                        <?php echo htmlspecialchars($row['kategori_nama']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #666; text-align: center; width: 100%;">Destinasi tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
<?php mysqli_close($conn); ?>