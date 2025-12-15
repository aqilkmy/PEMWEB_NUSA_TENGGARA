<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

// Get destination ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch destination
$query = "SELECT d.*, k.nama AS kategori_nama 
          FROM destinasi d
          LEFT JOIN kategori k ON d.kategori_id = k.id
          WHERE d.id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    redirect(BASE_URL . 'pages/destination.php');
}

$destinasi = mysqli_fetch_assoc($result);

// Handle comment submission
$comment_msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    if (is_logged_in()) {
        $user_id = user_id();
        $comment = clean($_POST['comment_text']);

        if (!empty($comment)) {
            $insert = "INSERT INTO komentar (destinasi_id, user_id, isi) 
                       VALUES ($id, $user_id, '$comment')";
            if (mysqli_query($conn, $insert)) {
                $comment_msg = '<div class="alert alert-success">Komentar berhasil ditambahkan!</div>';
            }
        }
    } else {
        $comment_msg = '<div class="alert alert-error">Silakan login terlebih dahulu.</div>';
    }
}

// Get comments
$comments_query = "SELECT k.*, u.nama 
                   FROM komentar k 
                   JOIN users u ON k.user_id = u.id 
                   WHERE k.destinasi_id = $id 
                   ORDER BY k.created_at DESC";
$comments = mysqli_query($conn, $comments_query);

// Maps URL
$maps_url = maps_embed($destinasi['link_gmaps'], $destinasi['lokasi']);

$page_title = htmlspecialchars($destinasi['nama']) . ' - ' . APP_NAME;
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
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('../assets/images/destination-bg.png');
            background-size: cover;
            z-index: -2;
        }

        .detail-container {
            max-width: 1200px;
            margin: 120px auto 40px;
            padding: 20px;
        }

        .btn-back {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 20px;
            text-decoration: none;
            margin-bottom: 30px;
        }

        .btn-back:hover {
            background-color: #0d4f5f;
        }

        .detail-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .detail-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 15px;
        }

        .detail-info h1 {
            color: white;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .detail-info p {
            color: #333;
            line-height: 1.8;
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sub-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin: 30px 0;
        }

        .sub-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .map-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }

        .map-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .comment-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .comment-form textarea {
            width: 100%;
            min-height: 100px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            margin-top: 10px;
        }

        .comment-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 4px solid var(--primary-color);
        }

        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="gradient-bg"></div>
    <?php include '../includes/header.php'; ?>

    <div class="detail-container">
        <a href="destination.php" class="btn-back">← Kembali</a>

        <div class="detail-main">
            <div>
                <img src="<?php echo BASE_URL . htmlspecialchars($destinasi['gambar']); ?>"
                    alt="<?php echo htmlspecialchars($destinasi['nama']); ?>" class="detail-image">
            </div>
            <div class="detail-info">
                <h1><?php echo htmlspecialchars($destinasi['nama']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($destinasi['deskripsi'])); ?></p>
            </div>
        </div>

        <?php if (!empty($destinasi['sub_gambar1']) || !empty($destinasi['sub_gambar2']) || !empty($destinasi['sub_gambar3'])): ?>
            <div class="sub-images">
                <?php if (!empty($destinasi['sub_gambar1'])): ?>
                    <img src="<?php echo BASE_URL . htmlspecialchars($destinasi['sub_gambar1']); ?>" class="sub-image" alt="">
                <?php endif; ?>
                <?php if (!empty($destinasi['sub_gambar2'])): ?>
                    <img src="<?php echo BASE_URL . htmlspecialchars($destinasi['sub_gambar2']); ?>" class="sub-image" alt="">
                <?php endif; ?>
                <?php if (!empty($destinasi['sub_gambar3'])): ?>
                    <img src="<?php echo BASE_URL . htmlspecialchars($destinasi['sub_gambar3']); ?>" class="sub-image" alt="">
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="map-section">
            <h2>Lokasi</h2>
            <iframe src="<?php echo htmlspecialchars($maps_url); ?>" width="100%" height="450" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="comment-section">
            <h2>Komentar (<?php echo mysqli_num_rows($comments); ?>)</h2>

            <?php echo $comment_msg; ?>

            <?php if (is_logged_in()): ?>
                <form method="POST" class="comment-form">
                    <textarea name="comment_text" placeholder="Tulis komentar Anda..." required></textarea>
                    <button type="submit" name="submit_comment" class="btn-submit">Kirim Komentar</button>
                </form>
            <?php else: ?>
                <div class="alert">
                    Silakan <a href="<?php echo BASE_URL; ?>auth/login.php" style="color: var(--primary-color); font-weight: 600;">login</a> untuk memberikan komentar.
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <?php if (mysqli_num_rows($comments) > 0): ?>
                    <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-avatar">
                                    <?php echo strtoupper(substr($comment['nama'], 0, 1)); ?>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($comment['nama']); ?></strong>
                                    <div style="font-size: 13px; color: #999;">
                                        <?php echo format_date($comment['created_at']); ?>
                                    </div>
                                </div>
                            </div>
                            <div><?php echo nl2br(htmlspecialchars($comment['isi'])); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 30px;">Belum ada komentar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
<?php mysqli_close($conn); ?>