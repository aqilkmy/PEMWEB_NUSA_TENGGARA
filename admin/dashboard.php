<?php
require_once 'auth_check.php';
require_once '../config.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM destinasi WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Destinasi berhasil dihapus!";
    }
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = intval($_POST['id']);
        $query = "UPDATE destinasi SET nama='$nama', lokasi='$lokasi', deskripsi='$deskripsi', gambar='$gambar' WHERE id=$id";
        $message = "Destinasi berhasil diupdate!";
    } else {
        // Insert
        $query = "INSERT INTO destinasi (nama, lokasi, deskripsi, gambar) VALUES ('$nama', '$lokasi', '$deskripsi', '$gambar')";
        $message = "Destinasi berhasil ditambahkan!";
    }
    
    if (mysqli_query($conn, $query)) {
        $success_message = $message;
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Get all destinations
$query = "SELECT * FROM destinasi ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM destinasi WHERE id = $edit_id";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_data = mysqli_fetch_assoc($edit_result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Destinasi</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 120px auto 40px;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-header h1 {
            color: var(--primary-color);
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 100px;
        }
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            opacity: 0.9;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-action {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .destination-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Kelola Destinasi</h1>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h2><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Destinasi</h2>
            <form method="POST" action="">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Nama Destinasi *</label>
                    <input type="text" name="nama" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Lokasi *</label>
                    <input type="text" name="lokasi" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['lokasi']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="deskripsi" required><?php echo $edit_data ? htmlspecialchars($edit_data['deskripsi']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>URL Gambar *</label>
                    <input type="text" name="gambar" required placeholder="asset/nama-gambar.png atau https://..." value="<?php echo $edit_data ? htmlspecialchars($edit_data['gambar']) : ''; ?>">
                </div>
                
                <button type="submit" class="btn-submit">
                    <?php echo $edit_data ? 'Update' : 'Tambah'; ?> Destinasi
                </button>
                
                <?php if ($edit_data): ?>
                    <a href="dashboard.php" class="btn-submit" style="background-color: #6c757d; margin-left: 10px; text-decoration: none; display: inline-block;">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-container">
            <h2>Daftar Destinasi</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><img src="../<?php echo htmlspecialchars($row['gambar']); ?>" class="destination-img" alt=""></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                                <td><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 50) . '...'; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus destinasi ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center;">Belum ada destinasi</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
