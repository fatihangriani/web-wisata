<?php
include "../koneksi.php";
include "../navbar.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Wisata Pantai</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
    }
    header {
      background: #0077b6;
      padding: 10px 20px;
      color: #fff;
    }
    h2 {
      text-align: center;
      margin: 20px 0;
      color: #0077b6;
    }
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
    }
    .card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 6px rgba(0,0,0,0.2);
      text-align: center;
      padding: 10px;
    }
    .card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 6px;
    }
    .card h3 {
      margin: 8px 0 4px 0;
      font-size: 16px;
    }
    .card p {
      color: #444;
      font-size: 13px;
      min-height: 20px;
    }
    .btn, .fav-btn {
      font-size: 13px;
      padding: 6px 12px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      margin: 4px;
    }
    .btn {
      background: #4CAF50;
      color: white;
    }
    .btn:hover {
      background: #45a049;
    }
    .fav-btn {
      background: #eee;
      color: black;
    }
    .fav-btn.active {
      background: #ffdddd;
      color: #a00;
    }
  </style>
</head>
<body>

  <section>
    <h2>Wisata Pantai di Jawa Timur</h2>
    <div class="card-container">

      <?php
      // ambil data wisata kategori Pantai
      $query = "SELECT * FROM wisata WHERE kategori='Pantai'";
      $result = mysqli_query($koneksi, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<div class='card'>
            <img src='../img/{$row['gambar']}' alt='{$row['nama']}'>
            <h3>{$row['nama']}</h3>
            <p>Lokasi: {$row['lokasi']}</p>
            <a href='detail.php?id={$row['id']}' class='btn'>Detail</a>
            <button class='fav-btn' data-name='{$row['nama']}' data-img='../img/{$row['gambar']}'>Tambah Favorite</button>
          </div>
          ";
        }
      } else {
        echo "<p style='text-align:center;'>Belum ada data wisata pantai.</p>";
      }
      ?>
      
    </div>
  </section>

  <script>
    const favButtons = document.querySelectorAll(".fav-btn");
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

    function updateButtons() {
      favButtons.forEach(btn => {
        const name = btn.dataset.name;
        if (favorites.find(item => item.name === name)) {
          btn.textContent = "Hapus Favorite";
          btn.classList.add("active");
        } else {
          btn.textContent = "Tambah Favorite";
          btn.classList.remove("active");
        }
      });
    }

    favButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        const name = btn.dataset.name;
        const img = btn.dataset.img;
        const index = favorites.findIndex(item => item.name === name);
        if (index !== -1) {
          favorites.splice(index, 1);
          alert(`${name} dihapus dari favorite`);
        } else {
          favorites.push({ name, img });
          alert(`${name} ditambahkan ke favorite`);
        }
        localStorage.setItem("favorites", JSON.stringify(favorites));
        updateButtons();
      });
    });

    updateButtons();
  </script>
</body>
</html>
