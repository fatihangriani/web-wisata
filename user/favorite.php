<?php
session_start();
include "../koneksi.php";
include "../navbar.php";

if (!isset($_SESSION['id_user'])) {
  echo "<div class='container'><p style='text-align:center;margin:50px;font-size:18px;color:#666;'>Silakan <a href='../login.php' style='color:#0077b6;text-decoration:none;'>login</a> terlebih dahulu untuk melihat favorit Anda.</p></div>";
  exit;
}

$id_user = $_SESSION['id_user'];

// Query dengan prepared statement
$stmt = $koneksi->prepare("SELECT w.*, k.nama_kategori FROM favorit f 
  JOIN wisata w ON f.id_wisata = w.id_wisata 
  JOIN kategori k ON w.id_kategori = k.id_kategori
  WHERE f.id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$query = $stmt->get_result();

// Siapkan data untuk JavaScript
$wisata_data = [];
while ($row = $query->fetch_assoc()) {
    $rating = !empty($row['rating']) ? floatval($row['rating']) : 4.0;
    $wisata_data[$row['id_wisata']] = [
        'nama' => $row['nama_wisata'],
        'kategori' => $row['nama_kategori'],
        'gambar' => "../img/" . $row['gambar'],
        'deskripsi' => $row['deskripsi'],
        'lokasi' => !empty($row['lokasi']) ? $row['lokasi'] : 'Lokasi tidak tersedia',
        'rating' => number_format($rating, 1)
    ];
}

// Reset pointer
$query->data_seek(0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Favorit Saya</title>
<style>
:root {
  --primary: #0077b6;
  --secondary: #ff6b6b;
  --light: #f8f9fa;
  --dark: #333;
  --gray: #666;
  --light-gray: #e9ecef;
  --white: #fff;
  --shadow: 0 4px 12px rgba(0,0,0,0.1);
  --transition: all 0.3s ease;
}
.container * {
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

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--gray);
  grid-column: 1 / -1;
}

.empty-state i {
  font-size: 4rem;
  margin-bottom: 20px;
  color: var(--light-gray);
}

.empty-state p {
  font-size: 1.2rem;
  margin-bottom: 15px;
}

.empty-state a {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
}

.empty-state a:hover {
  text-decoration: underline;
}

.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 25px;
  margin-top: 30px;
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
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
  z-index: 2;
}

.remove-btn {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(255, 255, 255, 0.9);
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
  color: var(--secondary);
  z-index: 2;
}

.remove-btn:hover {
  background: var(--white);
  transform: scale(1.1);
}

.card-content {
  padding: 20px;
}

.card-title {
  font-size: 1.3rem;
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
  line-height: 1.5;
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
  color: #e0e0e0;
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
  gap: 10px;
}

.btn {
  padding: 10px 15px;
  border-radius: 6px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  font-size: 0.9rem;
  flex: 1;
  text-align: center;
}

.btn-detail {
  background: var(--primary);
  color: var(--white);
}

.btn-detail:hover {
  background: #005a8c;
}

.btn-remove {
  background: var(--secondary);
  color: var(--white);
  max-width: 120px;
}

.btn-remove:hover {
  background: #e55c5c;
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

/* Notification */
.notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: var(--primary);
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  z-index: 1001;
  animation: fadeIn 0.3s, fadeOut 0.3s 2s forwards;
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
  
  .card-container {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  }
  
  .card-actions {
    flex-direction: column;
  }
  
  .btn-remove {
    max-width: none;
  }
}

@media (max-width: 576px) {
  .card-container {
    grid-template-columns: 1fr;
  }
  
  .container {
    width: 95%;
  }
}
</style>
</head>
<body>

<div class="container">
  <h2>❤️ Tempat Favorit Saya</h2>

  <div class="card-container">
    <?php
    if ($query->num_rows == 0) {
      echo "<div class='empty-state'>
              <div style='font-size:4rem;margin-bottom:20px;color:#e0e0e0;'>❤️</div>
              <p>Belum ada wisata favorit</p>
              <p>Tambahkan wisata ke favorit untuk melihatnya di sini</p>
              <p><a href='menu.php'>Jelajahi Wisata →</a></p>
            </div>";
    } else {
      while ($row = $query->fetch_assoc()) {
        $rating = !empty($row['rating']) ? floatval($row['rating']) : 4.0;
        ?>
        <div class="card" data-id="<?= $row['id_wisata'] ?>">
          <div class="card-image">
            <img src="../img/<?= $row['gambar'] ?>" alt="<?= $row['nama_wisata'] ?>">
            <div class="card-badge"><?= $row['nama_kategori'] ?></div>
            <button class="remove-btn" title="Hapus dari favorit" data-id="<?= $row['id_wisata'] ?>">×</button>
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
              <button class="btn btn-detail" data-id="<?= $row['id_wisata'] ?>">Lihat Detail</button>
              <button class="btn btn-remove" data-id="<?= $row['id_wisata'] ?>">Hapus</button>
            </div>
          </div>
        </div>
        <?php
      }
    }
    ?>
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

<script>
// Data wisata untuk modal detail
const wisataData = <?= json_encode($wisata_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

// DOM Elements
const cards = document.querySelectorAll('.card');
const detailModal = document.getElementById('detailModal');
const closeModalBtn = document.querySelector('.close-modal');
const removeBtns = document.querySelectorAll('.remove-btn, .btn-remove');

// Fungsi notifikasi
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;
  notification.style.background = type === 'error' ? '#ff6b6b' : '#0077b6';
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    if (notification.parentNode) {
      notification.parentNode.removeChild(notification);
    }
  }, 3000);
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

// Hapus dari favorit
removeBtns.forEach(button => {
  button.addEventListener('click', function() {
    const wisataId = this.getAttribute('data-id');
    const card = this.closest('.card');
    const wisataName = card.querySelector('.card-title').textContent;
    
    if (confirm(`Hapus "${wisataName}" dari favorit?`)) {
      // Kirim request hapus ke server
      fetch('proses_favorite.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id_wisata=' + wisataId
      })
      .then(res => res.text())
      .then(response => {
        if (response === 'removed') {
          // Animasi penghapusan
          card.style.opacity = '0';
          card.style.transform = 'scale(0.8)';
          setTimeout(() => {
            card.remove();
            showNotification(`"${wisataName}" dihapus dari favorit`);
            
            // Jika tidak ada card lagi, tampilkan empty state
            if (document.querySelectorAll('.card').length === 0) {
              document.querySelector('.card-container').innerHTML = `
                <div class='empty-state'>
                  <div style='font-size:4rem;margin-bottom:20px;color:#e0e0e0;'>❤️</div>
                  <p>Belum ada wisata favorit</p>
                  <p>Tambahkan wisata ke favorit untuk melihatnya di sini</p>
                  <p><a href='menu.php'>Jelajahi Wisata →</a></p>
                </div>
              `;
            }
          }, 300);
        } else {
          showNotification('Gagal menghapus dari favorit', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
      });
    }
  });
});
</script>
</body>
</html>