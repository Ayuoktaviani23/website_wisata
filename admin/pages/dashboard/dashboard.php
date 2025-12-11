<?php
session_start();
require_once __DIR__ . '/../../../config/koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Tentukan base URL untuk gambar
define('BASE_URL', '/pesona_jateng/admin/');
define('IMG_PATH', '../../pages/galeri/img/'); // Path ke folder gambar
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Pesona Jateng</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="<?= BASE_URL ?>includes/sidebar/sidebar.css">
<style>
  /* CSS sama seperti sebelumnya */
  body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; display: flex; margin: 0; }
  main { flex: 1; padding: 2rem; margin-left: 260px; transition: all 0.3s ease; }
  .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
  .card { background: #fff; border-radius: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 1.5rem; text-align: center; }
  .card i { font-size: 2rem; color: #007bff; margin-bottom: 0.5rem; }
  .card h3 { font-size: 1.2rem; margin: 0; color: #555; }
  .card p { font-size: 1.5rem; font-weight: bold; margin-top: 0.5rem; color: #000; }
  .section-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem; color: #333; }
  .table-container { background: #fff; border-radius: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; }
  table thead { background: #007bff; color: #fff; }
  th, td { padding: 0.8rem 1rem; text-align: left; }
  tbody tr:nth-child(even) { background-color: #f4f4f4; }
  img.thumb { width: 70px; height: 50px; object-fit: cover; border-radius: 8px; }
</style>
</head>
<body>

<?php include __DIR__ . '/../../includes/sidebar/sidebar.php'; ?>

<main>
<h1>Dashboard Admin</h1>
<p>Selamat datang di halaman dashboard Pesona Jateng!</p>

<?php
$totalWisata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM wisata"))['total'];
$totalKategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori"))['total'];
$totalUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$totalFavorit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM favorite"))['total'];

$queryWisata = "
    SELECT w.*, k.nama_kategori 
    FROM wisata w
    LEFT JOIN kategori k ON w.id_kategori = k.id_kategori
    ORDER BY w.tanggal_ditambahkan DESC
    LIMIT 5
";
$wisataTerbaru = mysqli_query($conn, $queryWisata);
?>

<div class="cards">
  <div class="card">
    <i class='bx bxs-map'></i>
    <h3>Total Wisata</h3>
    <p><?= $totalWisata ?></p>
  </div>
  <div class="card">
    <i class='bx bxs-category'></i>
    <h3>Total Kategori</h3>
    <p><?= $totalKategori ?></p>
  </div>
  <div class="card">
    <i class='bx bxs-user'></i>
    <h3>Total Pengguna</h3>
    <p><?= $totalUser ?></p>
  </div>
  <div class="card">
    <i class='bx bxs-heart'></i>
    <h3>Total Favorit</h3>
    <p><?= $totalFavorit ?></p>
  </div>
</div>

<div class="section-title">🗺️ Wisata Terbaru</div>
<div class="table-container">
<table>
<thead>
<tr>
  <th>Gambar</th>
  <th>Nama Wisata</th>
  <th>Kategori</th>
  <th>Lokasi</th>
  <th>Rating</th>
  <th>Tanggal Ditambahkan</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while ($w = mysqli_fetch_assoc($wisataTerbaru)) : ?>
<tr>
  <td><img src="<?= IMG_PATH . htmlspecialchars($w['gambar']) ?>" class="thumb" onerror="this.src='<?= IMG_PATH ?>default.jpg'"></td>
  <td><?= htmlspecialchars($w['nama_wisata']) ?></td>
  <td><?= htmlspecialchars($w['nama_kategori'] ?? '-') ?></td>
  <td><?= htmlspecialchars($w['lokasi']) ?></td>
  <td><?= htmlspecialchars($w['rating']) ?></td>
  <td><?= date('d M Y', strtotime($w['tanggal_ditambahkan'])) ?></td>
  <td>
    <a href="../wisata/edit_wisata.php?id=<?= $w['id_wisata'] ?>" style="color: #28a745; margin-right: 10px;">
      <i class='bx bxs-edit'></i>
    </a>
    <a href="pages/wisata/hapus.php?id=<?= $w['id_wisata'] ?>" 
       onclick="return confirm('Yakin ingin menghapus?')"
       style="color: #dc3545;">
      <i class='bx bxs-trash'></i>
    </a>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</main>
</body>
</html>