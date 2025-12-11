<?php
// Koneksi ke database
require_once __DIR__ . '/../../../config/koneksi.php';
// Jalankan session paling atas sebelum HTML apapun
session_start();


// Cek apakah user login dan role admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    // jika tidak, redirect ke login
    header("Location: ../../../login.php");
    exit;
}

// Ambil data user dari session atau database
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];






// Ambil data galeri beserta nama wisata (opsional)
$queryGaleri = "
    SELECT g.*, w.nama_wisata 
    FROM galeri_wisata g
    LEFT JOIN wisata w ON g.id_wisata = w.id_wisata
    ORDER BY g.id_galeri DESC
";
$galeri = mysqli_query($conn, $queryGaleri);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Wisata - Pesona Jateng</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../../includes/sidebar/sidebar.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fc;
      display: flex;
      margin: 0;
    }

    main {
      flex: 1;
      padding: 2rem;
      margin-left: 260px;
    }

    h1 {
      margin-bottom: 1rem;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .gallery-item {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .gallery-item:hover {
      transform: scale(1.05);
    }

    .gallery-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: block;
      border-radius: 12px;
    }

    .caption {
      position: absolute;
      bottom: 0;
      width: 100%;
      background: rgba(0,0,0,0.6);
      color: #fff;
      padding: 0.5rem;
      text-align: center;
      font-size: 0.9rem;
      opacity: 0;
      transition: opacity 0.2s ease;
    }

    .gallery-item:hover .caption {
      opacity: 1;
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <?php include __DIR__ . '/../../includes/sidebar/sidebar.php'; ?>

  <main>
    <h1>Galeri Wisata</h1>
    <div class="gallery">
      <?php while($g = mysqli_fetch_assoc($galeri)) : ?>
        <div class="gallery-item" onclick="showInfo(<?= $g['id_galeri'] ?>, <?= $g['id_wisata'] ?>)">
          <img src="img/<?= htmlspecialchars($g['foto']) ?>" alt="<?= htmlspecialchars($g['nama_wisata'] ?? '') ?>">
          <div class="caption"><?= htmlspecialchars($g['nama_wisata'] ?? '-') ?></div>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <script>
    function showInfo(idGaleri, idWisata) {
      alert("ID Galeri: " + idGaleri + "\nID Wisata: " + idWisata);
    }
  </script>
</body>
</html>
