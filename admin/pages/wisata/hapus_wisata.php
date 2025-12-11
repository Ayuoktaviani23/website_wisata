<?php
session_start();
require_once __DIR__ . '/../../../config/koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../../login.php");
    exit;
}

$id_wisata = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id_wisata <= 0){
    $_SESSION['error'] = "ID wisata tidak valid!";
    header("Location: wisata.php");
    exit;
}

// Nonaktifkan foreign key check sementara
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Ambil data gambar sebelum dihapus
    $query_gambar = "SELECT gambar FROM wisata WHERE id_wisata = $id_wisata";
    $result_gambar = mysqli_query($conn, $query_gambar);
    $data_gambar = mysqli_fetch_assoc($result_gambar);
    $gambar_utama = $data_gambar['gambar'] ?? '';
    
    // Ambil data galeri
    $query_galeri = "SELECT foto FROM galeri_wisata WHERE id_wisata = $id_wisata";
    $result_galeri = mysqli_query($conn, $query_galeri);
    $foto_galeri = [];
    while($row = mysqli_fetch_assoc($result_galeri)){
        $foto_galeri[] = $row['foto'];
    }
    
    // Hapus data dari semua tabel terkait (URUTAN PENTING!)
    // 1. Hapus tabel yang bergantung pada wisata
    $tables = [
        'favorite' => "DELETE FROM favorite WHERE id_wisata = $id_wisata",
        'galeri_wisata' => "DELETE FROM galeri_wisata WHERE id_wisata = $id_wisata",
        'fasilitas_wisata' => "DELETE FROM fasilitas_wisata WHERE id_wisata = $id_wisata",
        'detail_wisata' => "DELETE FROM detail_wisata WHERE id_wisata = $id_wisata",
        'tiket_wisata' => "DELETE FROM tiket_wisata WHERE id_wisata = $id_wisata"
    ];
    
    foreach($tables as $table => $query){
        mysqli_query($conn, $query);
    }
    
    // 2. Hapus data utama wisata
    $delete_wisata = mysqli_query($conn, "DELETE FROM wisata WHERE id_wisata = $id_wisata");
    
    if(!$delete_wisata){
        throw new Exception("Gagal menghapus data wisata");
    }
    
    // Commit transaksi
    mysqli_commit($conn);
    
    // Aktifkan kembali foreign key check
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    
    // Hapus file gambar dari server
    $img_path = __DIR__ . '/../../galeri/img/';
    
    // Hapus gambar utama
    if(!empty($gambar_utama) && $gambar_utama !== 'default.jpg'){
        $file_utama = $img_path . $gambar_utama;
        if(file_exists($file_utama)){
            unlink($file_utama);
        }
    }
    
    // Hapus file galeri
    foreach($foto_galeri as $foto){
        if(!empty($foto) && $foto !== 'default.jpg'){
            $file_galeri = $img_path . $foto;
            if(file_exists($file_galeri)){
                unlink($file_galeri);
            }
        }
    }
    
    $_SESSION['success'] = "Wisata berhasil dihapus!";
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    $_SESSION['error'] = "Gagal menghapus wisata: " . $e->getMessage();
}

header("Location: wisata.php");
exit;
?>