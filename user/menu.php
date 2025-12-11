<?php
session_start();
include "../koneksi.php";
include "../navbar.php";

// Ambil daftar favorit user dari database
$user_favorites = [];
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $favorite_query = mysqli_query($koneksi, "SELECT id_wisata FROM favorit WHERE id_user = '$id_user'");
    while ($fav = mysqli_fetch_assoc($favorite_query)) {
        $user_favorites[] = $fav['id_wisata'];
    }
}

// Ambil data wisata dengan lokasi dan rating
$query = mysqli_query($koneksi, "SELECT 
    w.*,
    w.id_kategori,
    k.nama_kategori
  FROM wisata w
  JOIN kategori k ON w.id_kategori = k.id_kategori
  ORDER BY w.nama_wisata ASC
");

// Ambil kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Siapkan data wisata untuk JavaScript
$wisata_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $harga = !empty($row['harga_tiket']) ? "Rp " . number_format($row['harga_tiket'], 0, ',', '.') : "Gratis";
    
    // Ambil rating dari database
    $rating = !empty($row['rating']) ? floatval($row['rating']) : 4.0;
    
    $wisata_data[$row['id_wisata']] = [
        'nama' => $row['nama_wisata'],
        'kategori' => $row['nama_kategori'],
        'gambar' => "../img/" . $row['gambar'],
        'deskripsi' => $row['deskripsi'],
        'lokasi' => !empty($row['lokasi']) ? $row['lokasi'] : 'Lokasi tidak tersedia',
        'harga' => $harga,
        'rating' => number_format($rating, 1)
    ];
}

// Reset pointer query untuk loop kartu wisata
mysqli_data_seek($query, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Wisata</title>

<style>
  :root {
    --primary: #0057a3;
    --secondary: #ff6b6b;
    --light: #f2f6fc;
    --dark: #333;
    --gray: #777;
    --light-gray: #e0e0e0;
    --white: #fff;
    --shadow: 0 4px 12px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
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
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
  }

  h2 {
    text-align: center;
    color: var(--primary);
    margin-bottom: 30px;
    font-size: 2rem;
    position: relative;
    padding-bottom: 15px;
  }

  h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: var(--primary);
  }

  /* FILTER */
  .filter-section {
    margin-bottom: 30px;
  }

  .filter-box {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .kategori-filter {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
  }

  .kategori-btn {
    padding: 10px 20px;
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 25px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
    font-weight: 600;
    color: var(--gray);
    white-space: nowrap;
  }

  .kategori-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
  }

  .kategori-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: var(--white);
  }

  .search-box {
    position: relative;
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
  }

  .search-box input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    font-size: 16px;
    border-radius: 25px;
    border: 2px solid var(--light-gray);
    background: var(--white);
    transition: var(--transition);
  }

  .search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 87, 163, 0.1);
  }

  .search-box i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    font-size: 18px;
  }

  /* CARD GRID */
  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
  }

  .card {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  }

  .card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
  }

  .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
  }

  .card:hover .card-image img {
    transform: scale(1.05);
  }

  .card-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary);
    color: var(--white);
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
  }

  .favorite-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 255, 255, 0.8);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    font-size: 1.2rem;
    color: var(--gray);
  }

  .favorite-btn:hover {
    background: var(--white);
    color: var(--secondary);
  }

  .favorite-btn.active {
    color: var(--secondary);
  }

  .card-content {
    padding: 20px;
  }

  .card-title {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: var(--dark);
    line-height: 1.3;
  }

  .card-category {
    color: var(--primary);
    font-size: 0.9rem;
    margin-bottom: 10px;
    font-weight: 600;
  }

  .card-location {
    color: var(--gray);
    font-size: 0.9rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .card-location::before {
    content: '📍';
    font-size: 0.8rem;
  }

  .card-description {
    color: var(--gray);
    font-size: 0.9rem;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .card-rating {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
  }

  .stars {
    display: flex;
    margin-right: 10px;
  }

  .star {
    color: var(--light-gray);
    font-size: 1rem;
    margin-right: 2px;
  }

  .star.filled {
    color: #ffc107;
  }

  .rating-value {
    font-size: 0.9rem;
    color: var(--gray);
    font-weight: 600;
  }

  .card-actions {
    display: flex;
    justify-content: space-between;
  }

  .btn {
    padding: 8px 15px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
  }

  .btn-detail {
    background: var(--primary);
    color: var(--white);
    flex-grow: 1;
    margin-right: 10px;
  }

  .btn-detail:hover {
    background: #004080;
  }

  .btn-favorite {
    background: var(--light-gray);
    color: var(--dark);
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-favorite:hover {
    background: #d0d0d0;
  }

  .btn-favorite.active {
    background: var(--secondary);
    color: var(--white);
  }

  /* MODAL */
  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    overflow-y: auto;
    padding: 20px;
  }

  .modal-content {
    background: var(--white);
    max-width: 800px;
    margin: 40px auto;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    animation: modalFadeIn 0.3s;
  }

  @keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--light-gray);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-title {
    font-size: 1.5rem;
    color: var(--primary);
  }

  .close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray);
    transition: var(--transition);
  }

  .close-modal:hover {
    color: var(--dark);
  }

  .modal-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .modal-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
  }

  .modal-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
  }

  .info-group {
    margin-bottom: 10px;
  }

  .info-label {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 5px;
  }

  .modal-description {
    line-height: 1.6;
  }

  .no-results {
    text-align: center;
    padding: 40px;
    color: var(--gray);
    grid-column: 1 / -1;
    font-size: 1.1rem;
  }

  .no-results i {
    font-size: 3rem;
    margin-bottom: 15px;
    display: block;
    color: var(--light-gray);
  }

  /* NOTIFICATION */
  .notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1001;
    animation: fadeIn 0.3s, fadeOut 0.3s 2s forwards;
    color: white;
    font-weight: 500;
  }
  
  .notification.success {
    background: #28a745;
  }
  
  .notification.error {
    background: var(--secondary);
  }
  
  .notification.info {
    background: var(--primary);
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  @keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(20px); }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .modal-info {
      grid-template-columns: 1fr;
    }
    
    .grid {
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .kategori-filter {
      gap: 8px;
    }
    
    .kategori-btn {
      padding: 8px 16px;
      font-size: 13px;
    }
  }

  @media (max-width: 576px) {
    .grid {
      grid-template-columns: 1fr;
    }
    
    .kategori-filter {
      justify-content: flex-start;
      overflow-x: auto;
      padding-bottom: 10px;
    }
    
    .search-box {
      max-width: 100%;
    }
    
    .container {
      width: 95%;
    }
  }
</style>
</head>

<body>

<div class="container">
  <h2>Daftar Wisata</h2>

  <!-- FILTER DAN PENCARIAN -->
  <div class="filter-section">
    <div class="filter-box">
      <!-- FILTER KATEGORI BERJEJER -->
      <div class="kategori-filter">
        <button class="kategori-btn active" data-kategori="all">Semua Kategori</button>
        <?php 
        mysqli_data_seek($kategori, 0);
        while ($kt = mysqli_fetch_assoc($kategori)) { ?>
          <button class="kategori-btn" data-kategori="<?= $kt['id_kategori'] ?>">
            <?= $kt['nama_kategori'] ?>
          </button>
        <?php } ?>
      </div>
      
      <!-- PENCARIAN -->
      <div class="search-box">
        <i>🔍</i>
        <input type="text" id="searchInput" placeholder="Cari nama wisata...">
      </div>
    </div>
  </div>

  <!-- GRID WISATA -->
  <div class="grid" id="listWisata">
    <?php 
    mysqli_data_seek($query, 0);
    while ($row = mysqli_fetch_assoc($query)) { 
      $rating = !empty($row['rating']) ? floatval($row['rating']) : 4.0;
      $is_favorite = in_array($row['id_wisata'], $user_favorites);
    ?>
      <div class="card" data-kategori="<?= $row['id_kategori'] ?>" data-nama="<?= strtolower($row['nama_wisata']) ?>">
        <div class="card-image">
          <img src="../img/<?= $row['gambar'] ?>" alt="<?= $row['nama_wisata'] ?>">
          <div class="card-badge"><?= $row['nama_kategori'] ?></div>
          <button class="favorite-btn <?= $is_favorite ? 'active' : '' ?>" 
                  data-id="<?= $row['id_wisata'] ?>"
                  <?= !isset($_SESSION['id_user']) ? 'title="Login untuk menambahkan favorit"' : '' ?>>
            ❤
          </button>
        </div>
        
        <div class="card-content">
          <h3 class="card-title"><?= $row['nama_wisata'] ?></h3>
          <div class="card-category"><?= $row['nama_kategori'] ?></div>
          
          <?php if (!empty($row['lokasi'])): ?>
            <div class="card-location"><?= $row['lokasi'] ?></div>
          <?php endif; ?>
          
          <p class="card-description"><?= substr($row['deskripsi'], 0, 100) ?>...</p>
          
          <div class="card-rating">
            <div class="stars">
              <?php
              $fullStars = floor($rating);
              $hasHalfStar = ($rating - $fullStars) >= 0.5;
              
              for ($i = 1; $i <= 5; $i++) {
                if ($i <= $fullStars) {
                  echo '<span class="star filled">★</span>';
                } else if ($i == $fullStars + 1 && $hasHalfStar) {
                  echo '<span class="star filled">★</span>';
                } else {
                  echo '<span class="star">★</span>';
                }
              }
              ?>
            </div>
            <span class="rating-value"><?= number_format($rating, 1) ?></span>
          </div>
          
          <div class="card-actions">
            <button class="btn btn-detail" data-id="<?= $row['id_wisata'] ?>">Detail</button>
            <button class="btn btn-favorite <?= $is_favorite ? 'active' : '' ?>" 
                    data-id="<?= $row['id_wisata'] ?>"
                    <?= !isset($_SESSION['id_user']) ? 'title="Login untuk menambahkan favorit"' : '' ?>>
              ❤
            </button>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- MODAL DETAIL WISATA -->
<div id="detailModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title" id="modalWisataName">Nama Wisata</h3>
      <button class="close-modal">&times;</button>
    </div>
    <div class="modal-body">
      <img id="modalWisataImage" src="" alt="" class="modal-image">
      
      <div class="modal-info">
        <div class="info-group">
          <div class="info-label">Kategori</div>
          <div id="modalWisataCategory">-</div>
        </div>
        
        <div class="info-group">
          <div class="info-label">Lokasi</div>
          <div id="modalWisataLocation">-</div>
        </div>
        
        <div class="info-group">
          <div class="info-label">Rating</div>
          <div id="modalWisataRating">-</div>
        </div>
      </div>
      
      <div class="info-group">
        <div class="info-label">Deskripsi</div>
        <div id="modalWisataDescription" class="modal-description">-</div>
      </div>
    </div>
  </div>
</div>

<!-- JAVASCRIPT -->
<script>
  // Data wisata untuk modal detail
  const wisataData = <?= json_encode($wisata_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

  // DOM Elements
  const kategoriBtns = document.querySelectorAll('.kategori-btn');
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.card');
  const detailModal = document.getElementById('detailModal');
  const closeModalBtn = document.querySelector('.close-modal');
  const favoriteBtns = document.querySelectorAll('.favorite-btn, .btn-favorite');

  // Fungsi notifikasi
  function showNotification(message, type = 'info') {
    // Hapus notifikasi sebelumnya
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
      existingNotification.remove();
    }
    
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 3000);
  }

  // Fungsi toggle favorit dengan AJAX
  function toggleFavorite(wisataId, button) {
    // Cek apakah user sudah login
    const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;
    
    if (!isLoggedIn) {
      showNotification('Silakan login terlebih dahulu!', 'error');
      // Redirect ke halaman login setelah 1.5 detik
      setTimeout(() => {
        window.location.href = '../login.php';
      }, 1500);
      return;
    }

    // Kirim request ke server
    fetch('proses_favorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'id_wisata=' + wisataId
    })
    .then(response => response.text())
    .then(result => {
      if (result === 'added') {
        button.classList.add('active');
        showNotification('Ditambahkan ke favorit', 'success');
      } else if (result === 'removed') {
        button.classList.remove('active');
        showNotification('Dihapus dari favorit', 'info');
      } else if (result === 'login_required') {
        showNotification('Silakan login terlebih dahulu!', 'error');
        setTimeout(() => {
          window.location.href = '../login.php';
        }, 1500);
      } else {
        showNotification('Terjadi kesalahan', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Gagal menyimpan favorit', 'error');
    });
  }

  // Filter berdasarkan kategori
  kategoriBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      kategoriBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      filterWisata();
    });
  });

  // Filter berdasarkan pencarian
  searchInput.addEventListener('input', function() {
    filterWisata();
  });

  // Fungsi filter wisata
  function filterWisata() {
    const activeKategori = document.querySelector('.kategori-btn.active').dataset.kategori;
    const searchValue = searchInput.value.toLowerCase().trim();
    
    let hasVisibleCards = false;
    
    cards.forEach(card => {
      const kategori = card.getAttribute('data-kategori');
      const namaWisata = card.querySelector('.card-title').textContent.toLowerCase();
      const kategoriWisata = card.querySelector('.card-category').textContent.toLowerCase();
      
      const categoryMatch = activeKategori === "all" || kategori === activeKategori;
      const searchMatch = searchValue === '' || 
                         namaWisata.includes(searchValue) || 
                         kategoriWisata.includes(searchValue);
      
      if (categoryMatch && searchMatch) {
        card.style.display = "block";
        hasVisibleCards = true;
      } else {
        card.style.display = "none";
      }
    });
    
    // Tampilkan pesan jika tidak ada hasil
    let noResults = document.querySelector('.no-results');
    
    if (!hasVisibleCards) {
      if (!noResults) {
        noResults = document.createElement('div');
        noResults.className = 'no-results';
        noResults.innerHTML = `
          <i>🔍</i>
          <p>Tidak ada wisata yang sesuai dengan kriteria pencarian.</p>
          <p style="font-size: 0.9rem; margin-top: 10px; color: var(--gray);">
            Coba ubah filter kategori atau kata kunci pencarian
          </p>
        `;
        document.getElementById('listWisata').appendChild(noResults);
      }
    } else if (noResults) {
      noResults.remove();
    }
  }
    

  // Modal detail
  document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function() {
      const wisataId = this.getAttribute('data-id');
      const data = wisataData[wisataId];
      
      if (data) {
        document.getElementById('modalWisataName').textContent = data.nama;
        document.getElementById('modalWisataImage').src = data.gambar;
        document.getElementById('modalWisataImage').alt = data.nama;
        document.getElementById('modalWisataCategory').textContent = data.kategori;
        document.getElementById('modalWisataLocation').textContent = data.lokasi;
        document.getElementById('modalWisataRating').textContent = data.rating + ' / 5.0';
        document.getElementById('modalWisataDescription').textContent = data.deskripsi;
        
        detailModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
      }
    });
  });

  // Tutup modal
  closeModalBtn.addEventListener('click', function() {
    detailModal.style.display = 'none';
    document.body.style.overflow = 'auto';
  });

  // Tutup modal saat klik di luar konten
  window.addEventListener('click', function(event) {
    if (event.target === detailModal) {
      detailModal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });

  // Event listener untuk tombol favorit
  favoriteBtns.forEach(button => {
    button.addEventListener('click', function() {
      const wisataId = this.getAttribute('data-id');
      toggleFavorite(wisataId, this);
    });
  });

  // Nonaktifkan tombol favorit jika belum login
  const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;
  if (!isLoggedIn) {
    favoriteBtns.forEach(btn => {
      btn.style.opacity = '0.6';
      btn.style.cursor = 'not-allowed';
    });
  }

  // Inisialisasi filter saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    filterWisata();
  });
</script>

</body>
</html>