<?php
session_start();
require_once '../../config/koneksi.php'; // pastikan path sesuai struktur kamu

header('Content-Type: application/json');

// 🧠 Sesuaikan nama session agar cocok dengan login.php
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'not_logged_in']);
    exit;
}

$id_user = (int) $_SESSION['user_id'];
$id_wisata = isset($_POST['id_wisata']) ? (int) $_POST['id_wisata'] : 0;

if ($id_wisata <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID wisata tidak valid']);
    exit;
}

// 🔎 Cek apakah sudah difavoritkan
$cek = $conn->query("SELECT * FROM favorite WHERE id_user = $id_user AND id_wisata = $id_wisata");

if ($cek && $cek->num_rows > 0) {
    // Jika sudah ada → hapus (unfavorite)
    $conn->query("DELETE FROM favorite WHERE id_user = $id_user AND id_wisata = $id_wisata");
    echo json_encode(['status' => 'removed']);
} else {
    // Jika belum → tambahkan ke favorite
    $stmt = $conn->prepare("INSERT INTO favorite (id_user, id_wisata, tanggal_favorite) VALUES (?, ?, NOW())");
    $stmt->bind_param('ii', $id_user, $id_wisata);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'added']);
}

$conn->close();
?>
