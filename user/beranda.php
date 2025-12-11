<?php
session_start();
include "../koneksi.php";
include "../navbar.php";


// Ambil daftar favorit user jika sudah login
$user_favorites = [];
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $favorite_query = mysqli_query($koneksi, "SELECT id_wisata FROM favorit WHERE id_user = '$id_user'");
    while ($fav = mysqli_fetch_assoc($favorite_query)) {
        $user_favorites[] = $fav['id_wisata'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pariwisata</title>

  <!-- Font untuk tampilan lebih menarik -->
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      color: white;
      overflow-x: hidden;
      background-color: #000;
    }

    /* ===== Hero Section ===== */
    .hero {
      background: url('../img/cover.jpg') 
                  no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    /* Lapisan transparan di atas background */
    .overlay {
      background-color: rgba(0, 0, 0, 0.45);
      padding: 40px 50px;
      text-align: center;
      border-radius: 15px;
      max-width: 700px;
      animation: fadeInUp 1.2s ease-in-out;
    }

    .overlay h2 {
      font-size: 40px;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .overlay p {
      font-size: 18px;
      line-height: 1.7;
      margin-bottom: 35px;
    }

    /* Tombol utama */
    .btn {
      display: inline-block;
      background-color: #2f3eff;
      color: white;
      padding: 12px 28px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn:hover {
      background-color: #1f2ed6;
      transform: translateY(-3px);
    }

    /* Animasi halus saat muncul */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsif di HP */
    @media (max-width: 600px) {
      .overlay {
        padding: 25px;
      }
      .overlay h2 {
        font-size: 28px;
      }
      .overlay p {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>


  <section class="hero">
    <div class="overlay">
      <h2>🌍 Selamat Datang!</h2>
      <p>
        Jelajahi pesona pantai yang eksotis,<br>
        gunung yang menawan, dan jejak sejarah yang memikat.<br>
        Temukan pengalaman liburan tak terlupakan di sini. ✨
      </p>
      <a href="menu.php" class="btn">Mulai Jelajahi</a>
    </div>
  </section>

</body>
</html>
