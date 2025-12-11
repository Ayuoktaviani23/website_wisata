<?php
session_start();
require_once '../../config/koneksi.php';

// Ambil id user jika login, kalau tidak maka null
$idUser = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Favorite Wisata — Pesona Jateng</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../layouts/footer.css">
  <style>
 /* reset */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

/* body */
body{
    background:#f8fafc;
    color:#333;
    line-height:1.6;
}

/* layout */
main{margin-top:90px;}
.container{
    max-width:1200px;
    margin:0 auto;
    padding:30px 20px;
}

/* header */
header{text-align:center;margin-bottom:40px;}
h1{
    font-size:2.8rem;
    background:linear-gradient(90deg,#3498db,#1d6fa5);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    font-weight:700;
}
.subtitle{
    color:#5f6f7f;
    font-size:1.1rem;
    margin-top:8px;
}

/* search */
.search-box{
    width:100%;
    max-width:600px;
    margin:0 auto 40px auto;
    position:relative;
}
.search-box input{
    width:100%;
    padding:15px 55px;
    border:2px solid #cfd8dc;
    border-radius:40px;
    font-size:1.1rem;
    transition:.3s;
    background:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}
.search-box i{
    position:absolute;
    left:20px;
    top:50%;
    transform:translateY(-50%);
    color:#7f8c8d;
    font-size:1.2rem;
}
.search-box input:focus{
    border-color:#3498db;
    box-shadow:0 0 10px rgba(52,152,219,0.3);
    outline:none;
}

/* kategori */
.kategori-section{text-align:center;margin-bottom:40px;}
.kategori-section h2{
    font-size:1.8rem;
    color:#2c3e50;
    font-weight:700;
    margin-bottom:8px;
}
.line-yellow{
    width:70px;
    height:4px;
    background:#f1c40f;
    margin:0 auto 25px auto;
    border-radius:3px;
}
.categories{
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:12px;
}
.category-btn{
    background:#f0f4f8;
    border:2px solid #d6e0ea;
    padding:10px 20px;
    border-radius:25px;
    cursor:pointer;
    transition:.3s;
    font-size:.95rem;
    font-weight:500;
    color:#2c3e50;
}
.category-btn:hover{
    background:#e3f2fd;
    border-color:#90caf9;
    color:#1565c0;
}
.category-btn.active{
    background:#3498db;
    color:#fff;
    border-color:#3498db;
}

/* grid */
.wisata-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(320px,1fr));
    gap:25px;
    margin-top:30px;
    place-items:center;
}

/* card */
.wisata-card{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    transition:.3s;
    width: 100%;
    max-width: 350px;
}
.wisata-card:hover{
    transform:translateY(-5px);
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
}

/* image */
.card-header{
    position:relative;
    height:200px;
    overflow:hidden;
}
.card-image{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform .5s;
}
.wisata-card:hover .card-image{
    transform:scale(1.05);
}

/* favorite */
.btn-favorite{
    position:absolute;
    top:10px;
    right:10px;
    background:rgba(255,255,255,0.9);
    color:#e74c3c;
    border:none;
    border-radius:50%;
    width:38px;
    height:38px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:18px;
    transition:all .3s;
}
.btn-favorite.active{
    background:#e74c3c;
    color:#fff;
}

/* content */
.card-body{padding:20px;}
.wisata-title{
    font-size:1.3rem;
    color:#1565c0;
    font-weight:600;
    margin-bottom:5px;
}
.wisata-location{
    color:#e74c3c;
    font-weight:600;
    margin-bottom:10px;
    display:flex;
    align-items:center;
    gap:5px;
}
.wisata-description{
    color:#7f8c8d;
    margin-bottom:15px;
    font-size:.95rem;
    line-height:1.5;
    height: 72px;
    overflow: hidden;
}

/* footer */
.card-footer{
    padding:15px 20px;
    background:#f8f9fa;
    border-top:1px solid #e9ecef;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.card-rating{
    display:flex;
    align-items:center;
    gap:5px;
    font-weight:bold;
    color:#f39c12;
    font-size:.9rem;
}
.btn-detail{
    background:#3498db;
    color:white;
    padding:8px 16px;
    border-radius:6px;
    border:none;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}
.btn-detail:hover{
    background:#2980b9;
}

/* empty */
.wisata-grid .empty-note{
    grid-column:1 / -1;  /* full */
    text-align:center;
    display:flex;
    flex-direction:column;
    align-items:center; /* center */
    justify-content:center; /* center */
    padding:20px 0;
}
.empty-note img{
    width:120px;
    margin-top:15px;
    opacity:0.9;
}

/* Responsive */
@media (max-width: 768px) {
    main {
        margin-top: 70px;
    }
    
    h1 {
        font-size: 2.2rem;
    }
    
    .wisata-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .categories {
        gap: 8px;
    }
    
    .category-btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 20px 15px;
    }
    
    h1 {
        font-size: 1.8rem;
    }
    
    .wisata-grid {
        grid-template-columns: 1fr;
    }
    
    .search-box input {
        padding: 12px 50px;
    }
}
</style>
</head>
<body>

<?php include '../layouts/navbar/index.php'; ?>

<main>
  <div class="container">
    <header>
      <h1>Favorite Wisata</h1>
      <p class="subtitle">Tempat wisata yang kamu simpan</p>
    </header>

    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" placeholder="Cari wisata favorite..." onkeyup="filterWisata()">
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

    <div class="wisata-grid" id="wisataGrid">
      <?php
      if (!$idUser) {
          echo '<div class="empty-note">Silakan login untuk melihat wisata favorit 😊</div>';
      } else {
          $sql = "SELECT w.id_wisata, w.nama_wisata, w.lokasi, w.deskripsi, w.gambar, w.rating, w.id_kategori
                  FROM favorite f
                  JOIN wisata w ON f.id_wisata = w.id_wisata
                  WHERE f.id_user = ?
                  ORDER BY f.tanggal_favorite DESC";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $idUser);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $id = $row['id_wisata'];
                  $nama = htmlspecialchars($row['nama_wisata']);
                  $lokasi = htmlspecialchars($row['lokasi']);
                  $des = htmlspecialchars(substr($row['deskripsi'], 0, 150)) . '...';
                  $img = htmlspecialchars($row['gambar']);
                  $rating = htmlspecialchars($row['rating']);
                  $kategori = $row['id_kategori'];
                  
                  // Validasi dan sanitasi path gambar
                  $gambarPath = "../../admin/pages/galeri/img/" . $row['gambar'];
                  $defaultImage = "../assets/img/image.png";
                  
                  // Cek apakah file gambar ada
                  if (!file_exists($gambarPath) || empty($row['gambar'])) {
                      $gambarPath = $defaultImage;
                  }

                  echo "
                  <div class='wisata-card' data-id='$id' data-nama='".strtolower($nama)."' data-kategori='$kategori'>
                    <div class='card-header'>
                       <img src='$gambarPath' class='card-image' alt='$nama'>
                      <button class='btn-favorite active'><i class='fas fa-heart'></i></button>
                    </div>
                    <div class='card-body'>
                      <div class='wisata-title'>$nama</div>
                      <div class='wisata-location'><i class='fas fa-map-marker-alt'></i> $lokasi</div>
                      <div class='wisata-description'>$des</div>
                    </div>
                    <div class='card-footer'>
                      <div class='card-rating'><i class='fas fa-star'></i> $rating</div>
                      <button class='btn-detail' onclick='showDetail({$row['id_wisata']})'>
                        <i class='fas fa-info-circle'></i> Detail
                      </button>
                    </div>
                  </div>";
              }
          } else {
              echo '
              <div class="empty-note" id="emptyMessage">
                <p><strong>Wisata favorite tidak ditemukan</strong></p>
                <p>Anda belum menambahkan wisata favorite</p>
                <p>Tambahkan wisata favorite anda</p>
                <img src="../assets/img/image.png" alt="Love icon">
              </div>';
          }
      }
      ?>
    </div>
  </div>
</main>

<script>
function showDetail(id) {
    window.location.href = "../wisata/detail.php?id=" + id;
}

function filterKategori(event, id) {
    document.querySelectorAll(".category-btn").forEach(btn => btn.classList.remove("active"));
    event.target.classList.add("active");

    let cards = document.querySelectorAll(".wisata-card");

    cards.forEach(card => {
        let kategoriCard = parseInt(card.getAttribute("data-kategori"));
        card.style.display = (id === 0 || id === kategoriCard) ? "block" : "none";
    });

    checkEmptyState();
}

function filterWisata() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".wisata-card");

    cards.forEach(card => {
        let nama = card.dataset.nama;
        card.style.display = nama.includes(input) ? "block" : "none";
    });

    checkEmptyState();
}

function checkEmptyState() {
    let visibleCards = [...document.querySelectorAll(".wisata-card")]
        .filter(card => card.style.display !== "none" && card.style.display !== "");

    if (visibleCards.length === 0) {
        if (!document.getElementById("emptyMessageJS")) {
            let div = document.createElement("div");
            div.id = "emptyMessageJS";
            div.className = "empty-note";
            div.innerHTML = `
                <p><strong>Tidak ada wisata ditemukan</strong></p>
                <p>Coba pilih kategori lain atau hapus pencarian</p>
                <img src="../assets/img/image.png" alt="No results">
            `;
            document.getElementById("wisataGrid").appendChild(div);
        }
    } else {
        let emptyMsg = document.getElementById("emptyMessageJS");
        if (emptyMsg) emptyMsg.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.wisata-card');
            const idWisata = card.getAttribute('data-id');

            fetch('toggle_favorite.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id_wisata=${idWisata}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'removed') {
                    card.remove();
                    checkEmptyState();
                    
                    // Jika tidak ada kartu lagi, reload halaman untuk menampilkan pesan kosong
                    if (document.querySelectorAll('.wisata-card').length === 0) {
                        location.reload();
                    }
                } else if (data.status === 'not_logged_in') {
                    alert('Silakan login terlebih dahulu untuk menambahkan favorit!');
                }
            })
            .catch(err => console.error('Error:', err));
        });
    });
});
</script>
<?php include '../layouts/footer.php'; ?>
</body>
</html>