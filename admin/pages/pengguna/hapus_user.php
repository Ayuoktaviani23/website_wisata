<?php
session_start();
require_once __DIR__ . '/../../../config/koneksi.php';

// Cek login admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../../login.php");
    exit;
}

// Ambil ID pengguna yang akan dihapus
$id_hapus = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id_hapus <= 0){
    $_SESSION['error'] = "ID pengguna tidak valid!";
    header("Location: users.php");
    exit;
}

// Cek apakah admin mencoba menghapus dirinya sendiri
if($id_hapus == $_SESSION['user_id']){
    $_SESSION['error'] = "Tidak dapat menghapus akun sendiri!";
    header("Location: users.php");
    exit;
}

// Cek apakah pengguna ada
$check = mysqli_query($conn, "SELECT username, role FROM users WHERE id_user = $id_hapus");
if(mysqli_num_rows($check) == 0){
    $_SESSION['error'] = "Pengguna tidak ditemukan!";
    header("Location: users.php");
    exit;
}

$user_data = mysqli_fetch_assoc($check);

// Cek apakah mencoba menghapus admin terakhir
if($user_data['role'] == 'admin'){
    $count_admins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'admin'"));
    if($count_admins['total'] <= 1){
        $_SESSION['error'] = "Tidak dapat menghapus admin terakhir!";
        header("Location: users.php");
        exit;
    }
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Nonaktifkan foreign key check sementara
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
    
    // 1. Hapus data favorit pengguna
    $query1 = "DELETE FROM favorite WHERE id_user = $id_hapus";
    if(!mysqli_query($conn, $query1)){
        throw new Exception("Gagal menghapus data favorit pengguna");
    }
    
    // 2. Hapus data pengguna
    $query2 = "DELETE FROM users WHERE id_user = $id_hapus";
    if(!mysqli_query($conn, $query2)){
        throw new Exception("Gagal menghapus data pengguna");
    }
    
    // Aktifkan kembali foreign key check
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    
    // Commit transaksi
    mysqli_commit($conn);
    
    $_SESSION['success'] = "Pengguna <strong>" . htmlspecialchars($user_data['username']) . "</strong> berhasil dihapus!";
    
} catch (Exception $e) {
    
    mysqli_rollback($conn);
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    $_SESSION['error'] = "Gagal menghapus pengguna: " . $e->getMessage();
}



header("Location: pengguna.php");  
exit;
?>