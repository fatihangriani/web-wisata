<?php
session_start();
include "../koneksi.php";

// Cek apakah user adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah wisata
if (isset($_POST['tambah'])) {
    $nama_wisata = mysqli_real_escape_string($koneksi, $_POST['nama_wisata']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
    
    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_path = "../img/" . $gambar;
    
    if (move_uploaded_file($gambar_tmp, $gambar_path)) {
        $query = "INSERT INTO wisata (nama_wisata, deskripsi, lokasi, gambar, rating, id_kategori) 
                  VALUES ('$nama_wisata', '$deskripsi', '$lokasi', '$gambar', '$rating', '$id_kategori')";
        mysqli_query($koneksi, $query);
        $pesan = "Wisata berhasil ditambahkan!";
    }
}

// Edit wisata
if (isset($_POST['edit'])) {
    $id_wisata = mysqli_real_escape_string($koneksi, $_POST['id_wisata']);
    $nama_wisata = mysqli_real_escape_string($koneksi, $_POST['nama_wisata']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
    
    if ($_FILES['gambar']['name'] != "") {
        // Upload gambar baru
        $gambar = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_path = "../img/" . $gambar;
        move_uploaded_file($gambar_tmp, $gambar_path);
        
        $query = "UPDATE wisata SET 
                  nama_wisata = '$nama_wisata', 
                  deskripsi = '$deskripsi', 
                  lokasi = '$lokasi', 
                  gambar = '$gambar', 
                  rating = '$rating', 
                  id_kategori = '$id_kategori' 
                  WHERE id_wisata = '$id_wisata'";
    } else {
        $query = "UPDATE wisata SET 
                  nama_wisata = '$nama_wisata', 
                  deskripsi = '$deskripsi', 
                  lokasi = '$lokasi', 
                  rating = '$rating', 
                  id_kategori = '$id_kategori' 
                  WHERE id_wisata = '$id_wisata'";
    }
    
    mysqli_query($koneksi, $query);
    $pesan = "Wisata berhasil diupdate!";
}

// Hapus wisata
if (isset($_GET['hapus'])) {
    $id_wisata = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM wisata WHERE id_wisata = '$id_wisata'");
    $pesan = "Wisata berhasil dihapus!";
}

// Ambil data wisata
$wisata = mysqli_query($koneksi, "SELECT w.*, k.nama_kategori FROM wisata w 
                                 JOIN kategori k ON w.id_kategori = k.id_kategori 
                                 ORDER BY w.id_wisata DESC");

// Ambil data kategori untuk dropdown
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wisata</title>
    <style>
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
            max-width: 1200px;
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
            max-width: 600px;
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
        
        textarea.form-control {
            height: 100px;
            resize: vertical;
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
        
        .table img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .pesan {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            <h1>Kelola Data Wisata</h1>
            <div>
                <a href="dashboard.php" class="btn btn-warning">Kembali</a>
                <button onclick="bukaModalTambah()" class="btn btn-success">Tambah Wisata</button>
            </div>
        </div>

        <?php if (isset($pesan)): ?>
            <div class="pesan"><?php echo $pesan; ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Wisata</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Rating</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($wisata)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <img src="../img/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_wisata']; ?>">
                    </td>
                    <td><?php echo $row['nama_wisata']; ?></td>
                    <td><?php echo $row['nama_kategori']; ?></td>
                    <td><?php echo $row['lokasi']; ?></td>
                    <td><?php echo $row['rating']; ?></td>
                    <td class="actions">
                        <button onclick="bukaModalEdit(
                            '<?php echo $row['id_wisata']; ?>',
                            '<?php echo addslashes($row['nama_wisata']); ?>',
                            '<?php echo addslashes($row['deskripsi']); ?>',
                            '<?php echo addslashes($row['lokasi']); ?>',
                            '<?php echo $row['id_kategori']; ?>',
                            '<?php echo $row['rating']; ?>'
                        )" class="btn btn-warning btn-small">Edit</button>
                        <a href="?hapus=<?php echo $row['id_wisata']; ?>" 
                           class="btn btn-danger btn-small" 
                           onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
            <h2>Tambah Wisata Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Wisata</label>
                    <input type="text" name="nama_wisata" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <input type="number" name="rating" class="form-control" step="0.1" min="0" max="5" required>
                </div>
                <div class="form-group">
                    <label>Gambar</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" name="tambah" class="btn btn-success">Tambah Wisata</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="tutupModalEdit()">&times;</span>
            <h2>Edit Wisata</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_wisata" id="edit_id">
                <div class="form-group">
                    <label>Nama Wisata</label>
                    <input type="text" name="nama_wisata" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" id="edit_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php mysqli_data_seek($kategori, 0); ?>
                        <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <input type="number" name="rating" id="edit_rating" class="form-control" step="0.1" min="0" max="5" required>
                </div>
                <div class="form-group">
                    <label>Gambar (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
                <button type="submit" name="edit" class="btn btn-warning">Update Wisata</button>
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
        
        function bukaModalEdit(id, nama, deskripsi, lokasi, kategori, rating) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_lokasi').value = lokasi;
            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_rating').value = rating;
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