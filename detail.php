<?php
session_start();
require_once 'config.php';

// Get destination ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch destination details
$query = "SELECT * FROM destinasi WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: destination.php');
    exit();
}

$destinasi = mysqli_fetch_assoc($result);

// Handle comment submission
$comment_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
        
        if (!empty($comment_text)) {
            $insert_query = "INSERT INTO komentar (destinasi_id, user_id, isi) VALUES ($id, $user_id, '$comment_text')";
            if (mysqli_query($conn, $insert_query)) {
                $comment_message = "Komentar berhasil ditambahkan!";
            } else {
                $comment_message = "Gagal menambahkan komentar.";
            }
        }
    } else {
        $comment_message = "Silakan login terlebih dahulu untuk memberikan komentar.";
    }
}

// Fetch comments for this destination
$comments_query = "SELECT k.*, u.nama, u.email FROM komentar k 
                   JOIN users u ON k.user_id = u.id 
                   WHERE k.destinasi_id = $id 
                   ORDER BY k.created_at DESC";
$comments_result = mysqli_query($conn, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($destinasi['nama']); ?> - WonderfulNTT</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="asset/ntt.png">
    <style>
        .detail-container {
            max-width: 1000px;
            margin: 120px auto 40px;
            padding: 20px;
        }
        .detail-header {
            margin-bottom: 30px;
        }
        .detail-header h1 {
            color: var(--primary-color);
            font-size: 36px;
            margin-bottom: 10px;
        }
        .detail-header .location {
            color: #666;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .detail-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .detail-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .detail-content h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .detail-content p {
            color: #333;
            line-height: 1.8;
            font-size: 16px;
        }
        .btn-back {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            margin-bottom: 30px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #0d4f5f;
        }
        
        /* Comment Section Styles */
        .comment-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .comment-section h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
        }
        .comment-form {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .comment-form textarea {
            width: 100%;
            min-height: 100px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
        }
        .comment-form textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        .btn-submit-comment {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-submit-comment:hover {
            background-color: #0d4f5f;
        }
        .comment-message {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .comment-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .comment-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .comments-list {
            margin-top: 20px;
        }
        .comment-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
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
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
        }
        .comment-author {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }
        .comment-date {
            color: #999;
            font-size: 13px;
            margin-left: auto;
        }
        .comment-text {
            color: #555;
            line-height: 1.6;
            font-size: 14px;
        }
        .no-comments {
            text-align: center;
            color: #999;
            padding: 30px;
            font-style: italic;
        }
        .login-prompt {
            text-align: center;
            padding: 20px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            color: #856404;
        }
        .login-prompt a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    <?php include 'header.php'; ?>
    
    <div class="detail-container">
        <a href="destination.php" class="btn-back">← Kembali</a>
        
        <div class="detail-header">
            <h1><?php echo htmlspecialchars($destinasi['nama']); ?></h1>
            <div class="location">
                📍 <?php echo htmlspecialchars($destinasi['lokasi']); ?>
            </div>
        </div>
        
        <img src="<?php echo htmlspecialchars($destinasi['gambar']); ?>" alt="<?php echo htmlspecialchars($destinasi['nama']); ?>" class="detail-image">
        
        <div class="detail-content">
            <h2>Tentang Destinasi Ini</h2>
            <p><?php echo nl2br(htmlspecialchars($destinasi['deskripsi'])); ?></p>
        </div>

        <section class="comment-section">
            <h2>Komentar (<?php echo mysqli_num_rows($comments_result); ?>)</h2>
            
            <?php if (!empty($comment_message)): ?>
                <div class="comment-message <?php echo strpos($comment_message, 'berhasil') !== false ? 'success' : 'error'; ?>">
                    <?php echo $comment_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <form method="POST" action="">
                        <textarea name="comment_text" placeholder="Tulis komentar Anda di sini..." required></textarea>
                        <button type="submit" name="submit_comment" class="btn-submit-comment">Kirim Komentar</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    Silakan <a href="login.php">login</a> untuk memberikan komentar.
                </div>
            <?php endif; ?>
            
            <div class="comments-list">
                <?php if (mysqli_num_rows($comments_result) > 0): ?>
                    <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-avatar">
                                    <?php echo strtoupper(substr($comment['nama'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="comment-author"><?php echo htmlspecialchars($comment['nama']); ?></div>
                                </div>
                                <div class="comment-date">
                                    <?php 
                                    $date = new DateTime($comment['created_at']);
                                    echo $date->format('d M Y, H:i'); 
                                    ?>
                                </div>
                            </div>
                            <div class="comment-text">
                                <?php echo nl2br(htmlspecialchars($comment['isi'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-comments">
                        Belum ada komentar. Jadilah yang pertama berkomentar!
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
