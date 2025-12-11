<?php
include "../koneksi.php";
include "../navbar.php";

$id = $_GET['id'] ?? 0;
$query = mysqli_query($koneksi, "SELECT * FROM wisata WHERE id_wisata = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<p style='text-align:center;margin:50px;'>Data tidak ditemukan</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?php echo $data['nama_wisata']; ?></title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fdfeffff, #e9eeecff);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      margin-top: 20px;
    }

    .hero {
      height: 400px;
      overflow: hidden;
    }

    .hero img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .content {
      padding: 30px;
    }

    h1 {
      color: #2c3e50;
      margin-bottom: 10px;
      font-size: 2.2rem;
    }

    .location {
      color: #666;
      font-size: 1.1rem;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .rating {
      background: #ffd700;
      color: #333;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: bold;
      display: inline-block;
      margin-bottom: 25px;
    }

    .description {
      line-height: 1.7;
      color: #444;
      font-size: 1.05rem;
      margin-bottom: 30px;
    }

    .btn {
      display: inline-block;
      padding: 12px 30px;
      background: #667eea;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn:hover {
      background: #5a6fd8;
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .container {
        margin: 10px;
        border-radius: 10px;
      }
      
      .hero {
        height: 250px;
      }
      
      .content {
        padding: 20px;
      }
      
      h1 {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="hero">
    <img src="../img/<?php echo $data['gambar']; ?>" alt="<?php echo $data['nama_wisata']; ?>">
  </div>
  
  <div class="content">
    <h1><?php echo $data['nama_wisata']; ?></h1>
    
    <div class="location">📍 <?php echo $data['lokasi']; ?></div>
    
    <div class="rating">⭐ <?php echo $data['rating'] ?? '4.5'; ?></div>
    
    <div class="description">
      <?php echo !empty($data['deskripsi']) ? nl2br($data['deskripsi']) : 'Belum ada deskripsi.'; ?>
    </div>
    
    <a href="beranda.php" class="btn">← Kembali</a>
  </div>
</div>

</body>
</html>