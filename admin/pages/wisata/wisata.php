<?php
// Mulai session di paling atas
session_start();

// Koneksi database
require_once '../../../config/koneksi.php';

// Cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit;
}

// Tangkap data user dari session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Ambil semua data wisata
$query = "
    SELECT w.*, k.nama_kategori 
    FROM wisata w 
    LEFT JOIN kategori k ON w.id_kategori = k.id_kategori
    ORDER BY w.tanggal_ditambahkan DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Wisata - Pesona Jateng</title>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pesona_jateng/admin/includes/sidebar/sidebar.css">
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f7f8fa;
        display: flex;
    }
    .container {
        flex: 1;
        margin-left: 260px; /* Sesuaikan dengan sidebar */
        padding: 25px;
    }
    h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }
    .btn-tambah {
        display: inline-flex;
        align-items: center;
        background: #3498db;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s;
    }
    .btn-tambah:hover {
        background: #2980b9;
    }
    .btn-tambah i {
        margin-right: 8px;
        font-size: 18px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        margin-top: 20px;
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        font-size: 14px;
    }
    th {
        background: #3498db;
        color: white;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    img.thumb {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }
    .action-btn {
        display: flex;
        gap: 8px;
    }
    .btn-detail {
        background: #2ecc71;
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
    }
    .btn-detail:hover {
        background: #27ae60;
    }
</style>
</head>
<body>
    <!-- Include sidebar yang sama dengan dashboard -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/pesona_jateng/admin/includes/sidebar/sidebar.php'; ?>

    <div class="container">
        <h1>Daftar Wisata</h1>
        <a href="tambah_wisata.php" class="btn-tambah">
            <i class='bx bx-plus'></i> Tambah Wisata
        </a>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama Wisata</th>
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th>Rating</th>
                    <th>Tanggal Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><img src="/pesona_jateng/assets/img/<?= htmlspecialchars($row['gambar']) ?>" class="thumb" alt=""></td>
                            <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                            <td><?= htmlspecialchars($row['lokasi']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_ditambahkan']) ?></td>
                            <td class="action-btn">
                                <a href="detail_wisata.php?id=<?= $row['id_wisata'] ?>" class="btn-detail">Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">Belum ada data wisata.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
