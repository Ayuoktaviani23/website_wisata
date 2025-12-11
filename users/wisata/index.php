<?php
session_start();
require_once '../../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wisata Jawa Tengah</title>
  <?php include '../layouts/navbar/index.php'; ?>
   <link rel="stylesheet" href="../layouts/footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f8fafc;
      color: #333;
      line-height: 1.6;
    }

    main {
      margin-top: 90px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 30px 20px;
    }

    header {
      text-align: center;
      margin-bottom: 40px;
    }

    h1 {
      font-size: 2.8rem;
      background: linear-gradient(90deg, #3498db, #1d6fa5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .subtitle {
      color: #5f6f7f;
      font-size: 1.1rem;
      margin-top: 8px;
    }

    .search-box {
      width: 100%;
      max-width: 600px;
      margin: 0 auto 40px auto;
      position: relative;
    }

    .search-box input {
      width: 100%;
      padding: 15px 55px 15px 55px;
      border: 2px solid #cfd8dc;
      border-radius: 40px;
      font-size: 1.1rem;
      transition: 0.3s ease;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .search-box i {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #7f8c8d;
      font-size: 1.2rem;
    }

    .search-box input:focus {
      border-color: #3498db;
      box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
      outline: none;
    }

    .kategori-section {
      text-align: center;
      margin-bottom: 40px;
    }

    .kategori-section h2 {
      font-size: 1.8rem;
      color: #2c3e50;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .line-yellow {
      width: 70px;
      height: 4px;
      background-color: #f1c40f;
      margin: 0 auto 25px auto;
      border-radius: 3px;
    }

    .categories {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .category-btn {
      background-color: #f0f4f8;
      border: 2px solid #d6e0ea;
      padding: 10px 20px;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s ease;
      font-size: 0.95rem;
      font-weight: 500;
      color: #2c3e50;
    }

    .category-btn:hover {
      background-color: #e3f2fd;
      border-color: #90caf9;
      color: #1565c0;
    }

    .category-btn.active {
      background-color: #3498db;
      color: white;
      border-color: #3498db;
    }

    .daftar-wisata-title {
      text-align: center;
      margin: 60px 0 30px;
    }

    .daftar-wisata-title h2 {
      font-size: 1.8rem;
      color: #2c3e50;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .wisata-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 25px;
    }

    .wisata-card {
      background-color: white;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: 0.3s ease;
    }

    .wisata-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .card-header {
      position: relative;
      height: 200px;
      overflow: hidden;
    }

    .card-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .wisata-card:hover .card-image {
      transform: scale(1.05);
    }

    .btn-favorite {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(255,255,255,0.9);
      color: #e74c3c;
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .btn-favorite:hover,
    .btn-favorite.active {
      background-color: #e74c3c;
      color: white;
    }

    .card-body {
      padding: 20px;
    }

    .wisata-title {
      font-size: 1.3rem;
      color: #1565c0;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .wisata-location {
      color: #e74c3c;
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .wisata-description {
      color: #7f8c8d;
      margin-bottom: 15px;
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .card-footer {
      padding: 15px 20px;
      background-color: #f8f9fa;
      border-top: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-rating {
      display: flex;
      align-items: center;
      gap: 5px;
      font-weight: bold;
      color: #f39c12;
      font-size: 0.9rem;
    }

    .btn-detail {
      background-color: #3498db;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn-detail:hover {
      background-color: #2980b9;
    }

    .empty-state {
      grid-column: 1 / -1;
      text-align: center;
      padding: 60px 20px;
      color: #7f8c8d;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 20px;
      color: #bdc3c7;
    }

    @media (max-width: 768px) {
      h1 { font-size: 2rem; }
      .search-box input { font-size: 1rem; }
    }
  </style>
</head>
<body>

<main>
  <div class="container">
    <header>
      <h1>Wisata Jawa Tengah</h1>
      <p class="subtitle">Temukan keindahan dan pesona destinasi wisata di Jawa Tengah</p>
    </header>

    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" placeholder="Cari destinasi wisata..." onkeyup="filterWisata()">
    </div>

    <div class="kategori-section">
      <h2>Kategori Wisata</h2>
      <div class="line-yellow"></div>

      <div class="categories">
        <button class="category-btn active" onclick="filterKategori(event, 0)">Semua</button>
        <?php
        $kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
        if ($kategori->num_rows > 0) {
          while ($kat = $kategori->fetch_assoc()) {
            echo "<button class='category-btn' onclick='filterKategori(event, {$kat['id_kategori']})'>{$kat['nama_kategori']}</button>";
          }
        }
        ?>
      </div>
    </div>

    <div class="daftar-wisata-title">
      <h2>Daftar Wisata</h2>
      <div class="line-yellow"></div>
    </div>

    <div class="wisata-grid" id="wisataGrid">
      <?php
      
      $id_user = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
      
    
      $sql = "SELECT w.*, 
                     CASE WHEN f.id_user IS NOT NULL THEN 1 ELSE 0 END as is_favorite
              FROM wisata w
              LEFT JOIN favorite f ON w.id_wisata = f.id_wisata AND f.id_user = ?
              ORDER BY w.id_wisata DESC";
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id_user);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $activeClass = $row['is_favorite'] ? 'active' : '';
          $iconClass = $row['is_favorite'] ? 'fas fa-heart' : 'far fa-heart';
          
        
          $gambarPath = "../../admin/pages/galeri/img/" . $row['gambar'];
          $defaultImage = "../assets/img/image.png";
          
      
          if (!file_exists($gambarPath) || empty($row['gambar'])) {
              $gambarPath = $defaultImage;
          }
          
          echo "
          <div class='wisata-card' data-kategori='{$row['id_kategori']}' data-nama='" . strtolower(htmlspecialchars($row['nama_wisata'])) . "'>
            <div class='card-header'>
              <img src='$gambarPath' 
                   alt='{$row['nama_wisata']}' 
                   class='card-image'>

              <button class='btn-favorite $activeClass' data-wisata-id='{$row['id_wisata']}'>
                <i class='$iconClass'></i>
              </button>
            </div>
            <div class='card-body'>
              <h3 class='wisata-title'>{$row['nama_wisata']}</h3>
              <p class='wisata-location'><i class='fas fa-map-marker-alt'></i> {$row['lokasi']}</p>
              <p class='wisata-description'>" . substr($row['deskripsi'], 0, 150) . "...</p>
            </div>
            <div class='card-footer'>
              <div class='card-rating'><i class='fas fa-star'></i> {$row['rating']}</div>
              <button class='btn-detail' onclick='showDetail({$row['id_wisata']})'>
                <i class='fas fa-info-circle'></i> Detail
              </button>
            </div>
          </div>
          ";
        }
      } else {
        echo '
        <div class="empty-state">
          <i class="fas fa-map-marked-alt"></i>
          <h3>Tidak ada data wisata</h3>
          <p>Belum ada wisata yang tersedia saat ini.</p>
        </div>';
      }
      $stmt->close();
      $conn->close();
      ?>
    </div>
  </div>
</main>

<script>
function showDetail(id) {
  window.location.href = "detail.php?id=" + id;
}

// Event listener untuk semua tombol favorite
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-favorite').forEach(button => {
    button.addEventListener('click', function() {
      toggleFavorite(this);
    });
  });
});

function toggleFavorite(button) {
  const idWisata = button.getAttribute('data-wisata-id');
  const icon = button.querySelector('i');
  
  console.log('Toggling favorite for wisata ID:', idWisata);
  
  fetch('favorite_action.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'id_wisata=' + encodeURIComponent(idWisata)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    console.log('Response from server:', data);
    
    if (data.status === 'not_logged_in') {
      alert('Silakan login terlebih dahulu untuk menambahkan ke favorit.');
      return;
    }
    
    if (data.status === 'added') {
      button.classList.add('active');
      icon.className = 'fas fa-heart';
      // Tambahkan efek visual
      button.style.transform = 'scale(1.2)';
      setTimeout(() => {
        button.style.transform = 'scale(1)';
      }, 300);
      console.log('Favorite added successfully');
    } else if (data.status === 'removed') {
      button.classList.remove('active');
      icon.className = 'far fa-heart';
      console.log('Favorite removed successfully');
    } else if (data.status === 'error') {
      alert('Terjadi kesalahan: ' + data.message);
      console.error('Server error:', data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat memproses favorit. Silakan coba lagi.');
  });
}

function filterKategori(event, idKategori) {
  const allCards = document.querySelectorAll('.wisata-card');
  allCards.forEach(card => {
    if (idKategori === 0 || card.dataset.kategori == idKategori) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
  
  // Update active button
  document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
  
  checkEmptyState();
}

function filterWisata() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const allCards = document.querySelectorAll('.wisata-card');
  
  allCards.forEach(card => {
    const namaWisata = card.dataset.nama;
    if (namaWisata.includes(input)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
  
  checkEmptyState();
}

function checkEmptyState() {
  const visibleCards = Array.from(document.querySelectorAll('.wisata-card'))
    .filter(card => card.style.display !== 'none');
  const wisataGrid = document.getElementById('wisataGrid');
  
  // Hapus pesan kosong yang sudah ada
  const existingMessage = document.getElementById('emptyStateMessage');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // Jika tidak ada kartu yang terlihat, tampilkan pesan
  if (visibleCards.length === 0) {
    const emptyMessage = document.createElement('div');
    emptyMessage.id = 'emptyStateMessage';
    emptyMessage.className = 'empty-state';
    emptyMessage.innerHTML = `
      <i class="fas fa-search"></i>
      <h3>Tidak ada hasil ditemukan</h3>
      <p>Coba gunakan kata kunci lain atau pilih kategori berbeda</p>
    `;
    wisataGrid.appendChild(emptyMessage);
  }
}
</script>
  <?php include '../layouts/footer.php'; ?>
</body>
</html>