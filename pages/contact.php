<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean($_POST['nama']);
    $email = clean($_POST['email']);
    $pesan = clean($_POST['pesan']);

    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        if (is_valid_email($email)) {
            $query = "INSERT INTO kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";
            if (mysqli_query($conn, $query)) {
                $message = '<div class="alert alert-success">Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.</div>';
            } else {
                $message = '<div class="alert alert-error">Gagal mengirim pesan. Silakan coba lagi.</div>';
            }
        } else {
            $message = '<div class="alert alert-error">Email tidak valid.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Semua field harus diisi.</div>';
    }
}

$page_title = 'Hubungi Kami - ' . APP_NAME;
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
        .contact-container {
            max-width: 1000px;
            margin: 120px auto 40px;
            padding: 20px;
        }

        .contact-header {
            text-align: center;
            color: white;
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d4f5f 100%);
            padding: 50px 40px;
            border-radius: 20px;
            margin-bottom: 40px;
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .contact-info,
        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .contact-info h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .contact-item {
            margin-bottom: 20px;
        }

        .contact-item strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .contact-form textarea {
            min-height: 150px;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit:hover {
            background-color: #0d4f5f;
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

<body style="background-image: url('../assets/images/destination-bg.png'); background-size: cover;">
    <div class="gradient-bg"></div>
    <?php include '../includes/header.php'; ?>

    <div class="contact-container">
        <div class="contact-header">
            <h1>Hubungi Kami</h1>
            <p>Kami siap membantu terkait kebutuhanmu!</p>
        </div>

        <?php echo $message; ?>

        <div class="contact-content">
            <div class="contact-info">
                <h3>Informasi Kontak</h3>

                <div class="contact-item">
                    <strong>Email</strong>
                    <p><?php echo CONTACT_EMAIL; ?></p>
                </div>

                <div class="contact-item">
                    <strong>Telepon</strong>
                    <p><?php echo CONTACT_PHONE; ?></p>
                </div>

                <div class="contact-item">
                    <strong>Alamat</strong>
                    <p><?php echo CONTACT_ADDRESS; ?></p>
                </div>
            </div>

            <div class="contact-form">
                <h3>Kirim Pesan</h3>
                <form method="POST" action="">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <textarea name="pesan" placeholder="Pesan Anda" required></textarea>
                    <button type="submit" class="btn-submit">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
<?php mysqli_close($conn); ?>