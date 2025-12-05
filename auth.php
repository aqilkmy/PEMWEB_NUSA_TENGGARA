<?php
require "db_config.php";

// ================================
// ============ LOGOUT ============
// ================================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php?page=login");
    exit;
}

// Tentukan halaman
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// ================================
// =========== REGISTER ===========
// ================================
if (isset($_POST['register'])) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Konfirmasi password tidak sama!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (nama, email, password, role) 
                VALUES ('$nama', '$email', '$hashed', 'user')";

        if ($conn->query($sql)) {
            header("Location: auth.php?page=login&msg=registered");
            exit;
        } else {
            $error = "Email sudah digunakan!";
        }
    }
}

// ================================
// ============= LOGIN ============
// ================================
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $passwordInput = $_POST['password'];

    // ambil user berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // COCOKKAN HASH: hash('sha256', input_password) === password_diDB
        if (hash('sha256', $passwordInput) === $user['password']) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];

            // redirect sesuai role
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Autentikasi</title>
    <meta charset="UTF-8">
</head>
<body>

<h2 align="center">
<?php 
    echo ($page == "register") ? "REGISTER" : "LOGIN"; 
?>
</h2>

<?php if (isset($error)): ?>
    <p style="color:red; text-align:center;"><?= $error ?></p>
<?php endif; ?>

<?php if ($page == "register"): ?>

    <!-- ========================= FORM REGISTER ========================= -->
    <form method="POST" style="text-align:center;">
        <input type="text" name="nama" placeholder="Username" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required><br><br>
        <button type="submit" name="register">Daftar</button>
    </form>

    <p style="text-align:center;">Sudah punya akun?  
        <a href="auth.php?page=login">Login</a>
    </p>

<?php else: ?>

    <!-- ========================= FORM LOGIN ========================= -->
    <form method="POST" style="text-align:center;">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="login">Masuk</button>
    </form>

    <p style="text-align:center;">Belum punya akun?  
        <a href="auth.php?page=register">Register</a>
    </p>

<?php endif; ?>

</body>
</html>
