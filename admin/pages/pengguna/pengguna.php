<?php
// Jalankan session paling atas
session_start();

// Koneksi ke database
require_once __DIR__ . '/../../../config/koneksi.php';

// Cek login admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../../login.php");
    exit;
}

// Ambil data user login
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// BASE_URL didefinisikan satu kali
define('BASE_URL', '/pesona_jateng/admin/');

// Ambil semua pengguna
$queryUsers = "SELECT id_user, username, nama_lengkap, email, role FROM users ORDER BY id_user DESC";
$resultUsers = mysqli_query($conn, $queryUsers);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pengguna - Pesona Jateng</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="<?= BASE_URL ?>includes/sidebar/sidebar.css">
<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; display: flex; margin: 0; }
  main { flex: 1; padding: 2rem; margin-left: 260px; transition: all 0.3s ease; }
  .section-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem; color: #333; }
  .table-container { background: #fff; border-radius: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; }
  table thead { background: #007bff; color: #fff; }
  th, td { padding: 0.8rem 1rem; text-align: left; }
  tbody tr:nth-child(even) { background-color: #f4f4f4; }
  .role-user { color: #28a745; font-weight: bold; }
  .role-admin { color: #dc3545; font-weight: bold; }
  .actions a { margin-right: 0.5rem; color: #007bff; text-decoration: none; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<?php include __DIR__ . '/../../includes/sidebar/sidebar.php'; ?>

<main>
<h1>Data Pengguna</h1>
<p>Daftar semua pengguna terdaftar di sistem Pesona Jateng.</p>

<div class="section-title">👥 Pengguna</div>
<div class="table-container">
<table>
<thead>
<tr>
  <th>ID</th>
  <th>Username</th>
  <th>Nama Lengkap</th>
  <th>Email</th>
  <th>Role</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($user = mysqli_fetch_assoc($resultUsers)): ?>
<tr>
  <td><?= $user['id_user'] ?></td>
  <td><?= htmlspecialchars($user['username']) ?></td>
  <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
  <td><?= htmlspecialchars($user['email']) ?></td>
  <td class="<?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>"><?= $user['role'] ?></td>
  <td class="actions">
    <a href="edit_user.php?id=<?= $user['id_user'] ?>">Edit</a>
    <a href="hapus_user.php?id=<?= $user['id_user'] ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</main>
</body>
</html>
