<?php
require_once 'auth_check.php';
require_once '../config.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image paths before deletion
    $get_images_query = "SELECT gambar, sub_gambar1, sub_gambar2, sub_gambar3 FROM destinasi WHERE id = $id";
    $result_images = mysqli_query($conn, $get_images_query);
    if ($row_images = mysqli_fetch_assoc($result_images)) {
        // Delete image files
        $images = [$row_images['gambar'], $row_images['sub_gambar1'], $row_images['sub_gambar2'], $row_images['sub_gambar3']];
        foreach ($images as $img) {
            if (!empty($img) && file_exists("../" . $img)) {
                unlink("../" . $img);
            }
        }
    }
    
    $delete_query = "DELETE FROM destinasi WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Destinasi berhasil dihapus!";
    }
}

// Function to handle file upload
function handleFileUpload($file, $old_file = '') {
    if ($file['error'] == UPLOAD_ERR_NO_FILE) {
        return $old_file; // No new file uploaded, keep old file
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Upload error: " . $file['error']);
    }
    
    // Validate file type using getimagesize (more reliable)
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        throw new Exception("File is not a valid image.");
    }
    
    // Check allowed image types
    $allowed_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
    if (!in_array($image_info[2], $allowed_types)) {
        throw new Exception("File type not allowed. Only JPG, PNG, and GIF are allowed.");
    }
    
    // Validate file size (max 15MB per file)
    if ($file['size'] > 15 * 1024 * 1024) {
        throw new Exception("File size too large. Maximum 15MB per file.");
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $upload_path = '../uploads/' . $filename;
    
    // Delete old file if exists
    if (!empty($old_file) && file_exists('../' . $old_file)) {
        unlink('../' . $old_file);
    }
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception("Failed to move uploaded file.");
    }
    
    return 'uploads/' . $filename;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Check if POST data is empty (might be due to upload size limit)
        if (empty($_POST)) {
            throw new Exception('Upload gagal! Ukuran file terlalu besar. Total ukuran semua file tidak boleh lebih dari 50MB. Silakan gunakan gambar dengan ukuran lebih kecil.');
        }
        
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $link_gmaps = mysqli_real_escape_string($conn, $_POST['link_gmaps']);
        $kategori_id = intval($_POST['kategori_id']);
        
        // Get old data if editing
        $old_gambar = '';
        $old_sub1 = '';
        $old_sub2 = '';
        $old_sub3 = '';
        
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $get_old = "SELECT gambar, sub_gambar1, sub_gambar2, sub_gambar3 FROM destinasi WHERE id = $id";
            $old_result = mysqli_query($conn, $get_old);
            if ($old_row = mysqli_fetch_assoc($old_result)) {
                $old_gambar = $old_row['gambar'];
                $old_sub1 = $old_row['sub_gambar1'];
                $old_sub2 = $old_row['sub_gambar2'];
                $old_sub3 = $old_row['sub_gambar3'];
            }
        }
        
        // Handle file uploads
        $gambar = handleFileUpload($_FILES['gambar'], $old_gambar);
        $sub_gambar1 = handleFileUpload($_FILES['sub_gambar1'], $old_sub1);
        $sub_gambar2 = handleFileUpload($_FILES['sub_gambar2'], $old_sub2);
        $sub_gambar3 = handleFileUpload($_FILES['sub_gambar3'], $old_sub3);
        
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update
            $id = intval($_POST['id']);
            $query = "UPDATE destinasi SET 
                      nama='$nama', 
                      lokasi='$lokasi', 
                      deskripsi='$deskripsi', 
                      kategori_id='$kategori_id',
                      link_gmaps='$link_gmaps',
                      gambar='$gambar',
                      sub_gambar1='$sub_gambar1',
                      sub_gambar2='$sub_gambar2',
                      sub_gambar3='$sub_gambar3'
                      WHERE id=$id";
            $message = "Destinasi berhasil diupdate!";
        } else {
            // Insert
            $query = "INSERT INTO destinasi (nama, lokasi, deskripsi, kategori_id, gambar, sub_gambar1, sub_gambar2, sub_gambar3, link_gmaps)
            VALUES ('$nama', '$lokasi', '$deskripsi', '$kategori_id', '$gambar', '$sub_gambar1', '$sub_gambar2', '$sub_gambar3', '$link_gmaps')";
            $message = "Destinasi berhasil ditambahkan!";
        }
        
        if (mysqli_query($conn, $query)) {
            $success_message = $message;
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Get all destinations
$query = "
    SELECT 
        d.*,
        k.nama AS nama_kategori
    FROM destinasi d
    LEFT JOIN kategori k ON d.kategori_id = k.id
    ORDER BY d.id DESC
";
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
            <form method="POST" action="" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Nama Destinasi *</label>
                    <input type="text" name="nama" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Lokasi *</label>
                    <input type="text" name="lokasi" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['lokasi']) : ''; ?>" placeholder="Contoh: Pulau Komodo, Nusa Tenggara Timur">
                    <small style="color: #666; font-size: 13px;">Masukkan nama lokasi atau alamat lengkap. Lokasi ini akan ditampilkan di Google Maps pada halaman detail.</small>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="deskripsi" required><?php echo $edit_data ? htmlspecialchars($edit_data['deskripsi']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="url" name="link_gmaps" placeholder="https://maps..." value="<?php echo $edit_data ? htmlspecialchars($edit_data['link_gmaps']) : ''; ?>">
                    <small style="color:#666;">Boleh dikosongkan</small>
                </div>

                <div class="form-group">
                    <label>Kategori Destinasi *</label>
                    <select name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM kategori"); while ($k = mysqli_fetch_assoc($kategori)) :
                        ?>
                        <option value="<?= $k['id']; ?>" <?= ($edit_data && $edit_data['kategori_id'] == $k['id']) ? 'selected' : ''; ?>> <?= htmlspecialchars($k['nama']); ?> </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gambar Utama * <?php echo $edit_data && !empty($edit_data['gambar']) ? '(File saat ini: ' . basename($edit_data['gambar']) . ')' : ''; ?></label>
                    <input type="file" name="gambar" accept="image/*" <?php echo !$edit_data ? 'required' : ''; ?>>
                    <?php if ($edit_data && !empty($edit_data['gambar'])): ?>
                        <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Sub Gambar 1 <?php echo $edit_data && !empty($edit_data['sub_gambar1']) ? '(File saat ini: ' . basename($edit_data['sub_gambar1']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar1" accept="image/*">
                    <?php if ($edit_data && !empty($edit_data['sub_gambar1'])): ?>
                        <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Sub Gambar 2 <?php echo $edit_data && !empty($edit_data['sub_gambar2']) ? '(File saat ini: ' . basename($edit_data['sub_gambar2']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar2" accept="image/*">
                    <?php if ($edit_data && !empty($edit_data['sub_gambar2'])): ?>
                        <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Sub Gambar 3 <?php echo $edit_data && !empty($edit_data['sub_gambar3']) ? '(File saat ini: ' . basename($edit_data['sub_gambar3']) . ')' : ''; ?></label>
                    <input type="file" name="sub_gambar3" accept="image/*">
                    <?php if ($edit_data && !empty($edit_data['sub_gambar3'])): ?>
                        <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    <?php endif; ?>
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
                <th>Link Google Maps</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= $row['id']; ?></td>

                    <td>
                        <img src="../<?= htmlspecialchars($row['gambar']); ?>" 
                             class="destination-img" alt="">
                    </td>

                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['lokasi']); ?></td>

                    <td>
                        <?= substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...
                    </td>

                    <!-- LINK GMAPS -->
                    <td>
                        <?php if (!empty($row['link_gmaps'])) : ?>
                            <a href="<?= htmlspecialchars($row['link_gmaps']); ?>" 
                               target="_blank" class="btn-action btn-map">
                                Lihat Maps
                            </a>
                        <?php else : ?>
                            <span style="color:#999;">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- KATEGORI -->
                    <td>
                        <span class="badge-kategori">
                             <?= htmlspecialchars($row['nama_kategori']); ?>
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td>
                        <a href="?edit=<?= $row['id']; ?>" 
                           class="btn-action btn-edit">Edit</a>

                        <a href="?delete=<?= $row['id']; ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Yakin ingin menghapus destinasi ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo '<tr>
                        <td colspan="8" style="text-align:center;">
                            Belum ada destinasi
                        </td>
                      </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
mysqli_close($conn);
?>
