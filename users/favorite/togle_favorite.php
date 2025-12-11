<?php
session_start();
require_once '../../config/koneksi.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'not_logged_in']);
    exit;
}

$id_user = $_SESSION['user_id'];
$id_wisata = isset($_POST['id_wisata']) ? (int) $_POST['id_wisata'] : 0;

if ($id_wisata <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID wisata tidak valid']);
    exit;
}

$cek = $conn->query("SELECT * FROM favorite WHERE id_user = $id_user AND id_wisata = $id_wisata");

if ($cek->num_rows > 0) {
    $conn->query("DELETE FROM favorite WHERE id_user = $id_user AND id_wisata = $id_wisata");
    echo json_encode(['status' => 'removed']);
} else {
    $conn->query("INSERT INTO favorite (id_user, id_wisata, tanggal_favorite) VALUES ($id_user, $id_wisata, NOW())");
    echo json_encode(['status' => 'added']);
}

$conn->close();
?>
