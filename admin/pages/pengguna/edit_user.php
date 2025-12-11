<?php
session_start();
require_once __DIR__ . '/../../../config/koneksi.php';

// Cek login admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// BASE_URL untuk sidebar
define('BASE_URL', '/pesona_jateng/admin/');

// Ambil ID pengguna yang akan diedit
$id_edit = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pengguna yang akan diedit
if($id_edit > 0){
    $query = "SELECT * FROM users WHERE id_user = $id_edit";
    $result = mysqli_query($conn, $query);
    $user_edit = mysqli_fetch_assoc($result);
    
    if(!$user_edit){
        $_SESSION['error'] = "Pengguna tidak ditemukan!";
        header("Location: pengguna.php");  // Diubah dari users.php ke pengguna.php
        exit;
    }
} else {
    $_SESSION['error'] = "ID pengguna tidak valid!";
    header("Location: pengguna.php");  // Diubah dari users.php ke pengguna.php
    exit;
}

// Proses update data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];
    
    // Validasi email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Format email tidak valid!";
    } else {
        // Cek apakah email sudah digunakan oleh user lain
        $check_email = mysqli_query($conn, "SELECT id_user FROM users WHERE email = '$email' AND id_user != $id_edit");
        if(mysqli_num_rows($check_email) > 0){
            $error = "Email sudah digunakan oleh pengguna lain!";
        } else {
            // Update data pengguna
            if(!empty($password)){
                // Jika password diisi, hash password baru
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query_update = "UPDATE users SET 
                                nama_lengkap = '$nama_lengkap', 
                                email = '$email', 
                                role = '$role', 
                                password = '$hashed_password' 
                                WHERE id_user = $id_edit";
            } else {
                // Jika password tidak diisi, jangan update password
                $query_update = "UPDATE users SET 
                                nama_lengkap = '$nama_lengkap', 
                                email = '$email', 
                                role = '$role' 
                                WHERE id_user = $id_edit";
            }
            
            if(mysqli_query($conn, $query_update)){
                $_SESSION['success'] = "Data pengguna berhasil diperbarui!";
                header("Location: pengguna.php");  // DIUBAH DARI users.php KE pengguna.php
                exit;
            } else {
                $error = "Gagal memperbarui data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Pengguna - Pesona Jateng</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="<?= BASE_URL ?>includes/sidebar/sidebar.css">
<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; display: flex; margin: 0; }
  main { flex: 1; padding: 2rem; margin-left: 260px; transition: all 0.3s ease; }
  
  .form-container {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 2rem;
    max-width: 600px;
    margin: 0 auto;
  }
  
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
  }
  
  .form-group input, 
  .form-group select {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
  }
  
  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
  }
  
  .btn {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .btn-primary {
    background: #007bff;
    color: white;
  }
  
  .btn-primary:hover {
    background: #0056b3;
  }
  
  .btn-secondary {
    background: #6c757d;
    color: white;
    text-decoration: none;
  }
  
  .btn-secondary:hover {
    background: #545b62;
  }
  
  .alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }
  
  .alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
  
  .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }
</style>
</head>
<body>

<!-- SIDEBAR -->
<?php include __DIR__ . '/../../includes/sidebar/sidebar.php'; ?>

<main>
<h1>Edit Pengguna</h1>

<div class="form-container">
  <?php if(isset($error)): ?>
    <div class="alert alert-danger">
      <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <i class='bx bx-check-circle'></i> <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  
  <form method="POST" action="">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" value="<?= htmlspecialchars($user_edit['username']) ?>" disabled>
      <small style="color: #666;">Username tidak dapat diubah</small>
    </div>
    
    <div class="form-group">
      <label for="nama_lengkap">Nama Lengkap *</label>
      <input type="text" id="nama_lengkap" name="nama_lengkap" 
             value="<?= htmlspecialchars($user_edit['nama_lengkap']) ?>" required>
    </div>
    
    <div class="form-group">
      <label for="email">Email *</label>
      <input type="email" id="email" name="email" 
             value="<?= htmlspecialchars($user_edit['email']) ?>" required>
    </div>
    
    <div class="form-group">
      <label for="role">Role *</label>
      <select id="role" name="role" required>
        <option value="user" <?= $user_edit['role'] == 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $user_edit['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="password">Password Baru</label>
      <input type="password" id="password" name="password" 
             placeholder="Kosongkan jika tidak ingin mengubah password">
      <small style="color: #666;">Minimal 6 karakter</small>
    </div>
    
    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
      <button type="submit" class="btn btn-primary">
        <i class='bx bx-save'></i> Simpan Perubahan
      </button>
      <a href="pengguna.php" class="btn btn-secondary"> <!-- Diubah dari users.php ke pengguna.php -->
        <i class='bx bx-arrow-back'></i> Kembali
      </a>
    </div>
  </form>
</div>

</main>

<script>
// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
  const password = document.getElementById('password').value;
  const email = document.getElementById('email').value;
  const nama = document.getElementById('nama_lengkap').value;
  
  // Validasi nama
  if(nama.trim() === '') {
    e.preventDefault();
    alert('Nama lengkap harus diisi!');
    return false;
  }
  
  // Validasi email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if(!emailRegex.test(email)) {
    e.preventDefault();
    alert('Format email tidak valid!');
    return false;
  }
  
  // Validasi password (jika diisi)
  if(password !== '' && password.length < 6) {
    e.preventDefault();
    alert('Password minimal 6 karakter!');
    return false;
  }
  
  return true;
});
</script>
</body>
</html>