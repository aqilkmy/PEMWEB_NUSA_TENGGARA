<?php
require "db_config.php";
session_start();

// Cek apakah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth.php?page=login");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kontak Masuk</title>
</head>
<body>

<h2>Pesan Kontak</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Pesan</th>
        <th>Tanggal</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM kontak ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) :
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['pesan'] ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
