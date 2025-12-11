<?php
session_start();
include "../koneksi.php";

// Cek apakah user adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    $pesan = "Kategori berhasil ditambahkan!";
}

// Edit kategori
if (isset($_POST['edit'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id_kategori'");
    $pesan = "Kategori berhasil diupdate!";
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    // Cek apakah kategori digunakan di wisata
    $cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wisata WHERE id_kategori = '$id_kategori'");
    $total = mysqli_fetch_assoc($cek)['total'];
    
    if ($total == 0) {
        mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori = '$id_kategori'");
        $pesan = "Kategori berhasil dihapus!";
    } else {
        $error = "Kategori tidak dapat dihapus karena masih digunakan oleh $total wisata!";
    }
}

// Ambil data kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <style>
        /* Gunakan style yang sama dengan crud_wisata */
        :root {
            --primary: #0057a3;
            --secondary: #ff6b6b;
            --success: #28a745;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --white: #fff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #004080;
        }
        
        .btn-success {
            background: var(--success);
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: var(--secondary);
        }
        
        .btn-danger:hover {
            background: #e55c5c;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal-content {
            background: var(--white);
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .table {
            width: 100%;
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: var(--primary);
            color: var(--white);
        }
        
        .pesan {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kelola Data Kategori</h1>
            <div>
                <a href="dashboard.php" class="btn btn-warning">Kembali</a>
                <button onclick="bukaModalTambah()" class="btn btn-success">Tambah Kategori</button>
            </div>
        </div>

        <?php if (isset($pesan)): ?>
            <div class="pesan"><?php echo $pesan; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_kategori']; ?></td>
                    <td class="actions">
                        <button onclick="bukaModalEdit(
                            '<?php echo $row['id_kategori']; ?>',
                            '<?php echo addslashes($row['nama_kategori']); ?>'
                        )" class="btn btn-warning btn-small">Edit</button>
                        <a href="?hapus=<?php echo $row['id_kategori']; ?>" 
                           class="btn btn-danger btn-small" 
                           onclick="return confirm('Yakin ingin menghapus kategori?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="tutupModalTambah()">&times;</span>
            <h2>Tambah Kategori Baru</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" required>
                </div>
                <button type="submit" name="tambah" class="btn btn-success">Tambah Kategori</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="tutupModalEdit()">&times;</span>
            <h2>Edit Kategori</h2>
            <form method="POST">
                <input type="hidden" name="id_kategori" id="edit_id">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit_nama" class="form-control" required>
                </div>
                <button type="submit" name="edit" class="btn btn-warning">Update Kategori</button>
            </form>
        </div>
    </div>

    <script>
        function bukaModalTambah() {
            document.getElementById('modalTambah').style.display = 'block';
        }
        
        function tutupModalTambah() {
            document.getElementById('modalTambah').style.display = 'none';
        }
        
        function bukaModalEdit(id, nama) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('modalEdit').style.display = 'block';
        }
        
        function tutupModalEdit() {
            document.getElementById('modalEdit').style.display = 'none';
        }
        
        // Tutup modal ketika klik di luar
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>