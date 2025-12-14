<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once 'auth_check.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get images
    $get_img = "SELECT gambar, sub_gambar1, sub_gambar2, sub_gambar3 FROM destinasi WHERE id = $id";
    $img_result = mysqli_query($conn, $get_img);
    if ($img_data = mysqli_fetch_assoc($img_result)) {
        // Delete all images
        delete_image($img_data['gambar']);
        delete_image($img_data['sub_gambar1']);
        delete_image($img_data['sub_gambar2']);
        delete_image($img_data['sub_gambar3']);
    }

    $delete = "DELETE FROM destinasi WHERE id = $id";
    if (mysqli_query($conn, $delete)) {
        $message = 'Destinasi berhasil dihapus!';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean($_POST['nama']);
    $lokasi = clean($_POST['lokasi']);
    $deskripsi = clean($_POST['deskripsi']);
    $link_gmaps = clean($_POST['link_gmaps']);
    $kategori_id = intval($_POST['kategori_id']);

    // Get old data if editing
    $old_gambar = '';
    $old_sub1 = '';
    $old_sub2 = '';
    $old_sub3 = '';

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $old_query = "SELECT gambar, sub_gambar1, sub_gambar2, sub_gambar3 FROM destinasi WHERE id = $id";
        $old_result = mysqli_query($conn, $old_query);
        if ($old_data = mysqli_fetch_assoc($old_result)) {
            $old_gambar = $old_data['gambar'];
            $old_sub1 = $old_data['sub_gambar1'];
            $old_sub2 = $old_data['sub_gambar2'];
            $old_sub3 = $old_data['sub_gambar3'];
        }
    }

    // Handle image uploads
    $gambar = upload_image($_FILES['gambar'], $old_gambar);
    $sub1 = upload_image($_FILES['sub_gambar1'], $old_sub1);
    $sub2 = upload_image($_FILES['sub_gambar2'], $old_sub2);
    $sub3 = upload_image($_FILES['sub_gambar3'], $old_sub3);

    // Check for errors
    if (isset($gambar['error'])) {
        $error = $gambar['error'];
    } elseif (isset($sub1['error'])) {
        $error = $sub1['error'];
    } elseif (isset($sub2['error'])) {
        $error = $sub2['error'];
    } elseif (isset($sub3['error'])) {
        $error = $sub3['error'];
    } else {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update
            $id = intval($_POST['id']);
            $update = "UPDATE destinasi SET 
                       nama='$nama', 
                       lokasi='$lokasi', 
                       deskripsi='$deskripsi', 
                       kategori_id=$kategori_id,
                       link_gmaps='$link_gmaps',
                       gambar='$gambar',
                       sub_gambar1='$sub1',
                       sub_gambar2='$sub2',
                       sub_gambar3='$sub3'
                       WHERE id=$id";

            if (mysqli_query($conn, $update)) {
                $message = 'Destinasi berhasil diupdate!';
            } else {
                $error = 'Gagal update destinasi.';
            }
        } else {
            // Insert
            $insert = "INSERT INTO destinasi (nama, lokasi, deskripsi, kategori_id, gambar, sub_gambar1, sub_gambar2, sub_gambar3, link_gmaps)
                       VALUES ('$nama', '$lokasi', '$deskripsi', $kategori_id, '$gambar', '$sub1', '$sub2', '$sub3', '$link_gmaps')";

            if (mysqli_query($conn, $insert)) {
                $message = 'Destinasi berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan destinasi.';
            }
        }
    }
}

// Get all destinations
$query = "SELECT d.*, k.nama AS kategori_nama 
          FROM destinasi d
          LEFT JOIN kategori k ON d.kategori_id = k.id
          ORDER BY d.id DESC";
$result = mysqli_query($conn, $query);

// Get categories
$kategori_query = "SELECT * FROM kategori ORDER BY nama ASC";
$kategori_result = mysqli_query($conn, $kategori_query);

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM destinasi WHERE id = $edit_id";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_data = mysqli_fetch_assoc($edit_result);
}

$page_title = 'Dashboard Admin - ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .admin-header h1 {
            color: white;
            font-size: 40px;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .btn-submit {
            background: var(--primary-color);
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

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: var(--primary-color);
            color: white;
        }

        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-edit {
            background: #ffc107;
            color: #000;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .dest-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
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

    <div class="admin-container">
        <div class="admin-header">
            <h1>Dashboard Admin</h1>
            <div>
                <span style="margin-right: 20px;">Halo, <?php echo htmlspecialchars(user_name()); ?></span>
                <a href="<?php echo BASE_URL; ?>index.php" class="btn-logout" style="background: #6c757d; margin-right: 10px;">Lihat Website</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h2><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Destinasi</h2>
            <form method="POST" enctype="multipart/form-data">
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
                    <label>Kategori *</label>
                    <select name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        mysqli_data_seek($kategori_result, 0);
                        while ($k = mysqli_fetch_assoc($kategori_result)):
                        ?>
                            <option value="<?php echo $k['id']; ?>" <?php echo ($edit_data && $edit_data['kategori_id'] == $k['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($k['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="url" name="link_gmaps" value="<?php echo $edit_data ? htmlspecialchars($edit_data['link_gmaps']) : ''; ?>">
                    <small>Optional</small>
                </div>

                <div class="form-group">
                    <label>Gambar Utama * <?php echo $edit_data && $edit_data['gambar'] ? '(Current: ' . basename($edit_data['gambar']) . ')' : ''; ?></label>
                    <input type="file" name="gambar" accept="image/*" <?php echo !$edit_data ? 'required' : ''; ?>>
                </div>

                <div class="form-group">
                    <label>Sub Gambar 1 <?php echo $edit_data && $edit_data['sub_gambar1'] ? '(Current: ' . basename($edit_data['sub_gambar1']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar1" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Sub Gambar 2 <?php echo $edit_data && $edit_data['sub_gambar2'] ? '(Current: ' . basename($edit_data['sub_gambar2']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar2" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Sub Gambar 3 <?php echo $edit_data && $edit_data['sub_gambar3'] ? '(Current: ' . basename($edit_data['sub_gambar3']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar3" accept="image/*">
                </div>

                <button type="submit" class="btn-submit">
                    <?php echo $edit_data ? 'Update' : 'Tambah'; ?> Destinasi
                </button>

                <?php if ($edit_data): ?>
                    <a href="dashboard.php" class="btn-cancel">Batal</a>
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
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <img src="<?php echo BASE_URL . htmlspecialchars($row['gambar']); ?>" class="dest-img" alt="">
                                </td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori_nama']); ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>