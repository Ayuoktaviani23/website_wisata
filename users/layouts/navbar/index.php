<?php
session_start();
require_once '../../config/koneksi.php';

// Ambil current page untuk active link
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil info user jika sudah login
$user = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT id_user, username, nama_lengkap, role FROM users WHERE id_user='$user_id'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar Pesona Jateng</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    /* CSS sama persis seperti sebelumnya */
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif;}
    .navbar { position: fixed; top:0; left:0; width:100%; background:#fff; display:flex; justify-content:space-between; align-items:center; padding:12px 50px; box-shadow:0 2px 10px rgba(0,0,0,0.08); z-index:1000; transition:0.3s ease; }
    .navbar.shrink { padding:8px 40px; box-shadow:0 2px 12px rgba(0,0,0,0.1); }
    .logo img { height:55px; width:auto; transition: all 0.3s ease; }
    .navbar.shrink .logo img { height:45px; }
    .nav-links { display:flex; align-items:center; gap:25px; }
    .nav-links a { text-decoration:none; color:#333; font-weight:500; font-size:15px; position:relative; padding:5px 0; transition: all 0.3s ease; }
    .nav-links a::after { content:""; position:absolute; bottom:-4px; left:0; width:0%; height:2px; background:#007bff; transition: width 0.3s ease; border-radius:5px; }
    .nav-links a:hover::after, .nav-links a.active::after { width:100%; }
    .nav-links a.active { color:#007bff; font-weight:600; }
    .nav-actions { display:flex; align-items:center; gap:15px; }
    .icon-btn { display:flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:50%; background:#f1f3f5; color:#333; text-decoration:none; font-size:18px; transition:all 0.3s ease; border:none; }
    .icon-btn:hover { background:#007bff; color:#fff; transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,123,255,0.3); }
    .user-name { font-weight:500; color:#2c3e50; margin-right:8px; }
    .burger-menu { display:none; flex-direction:column; cursor:pointer; gap:5px; }
    .burger-menu span { width:26px; height:3px; background:#333; transition: all 0.3s ease; }
    .burger-menu.active span:nth-child(1) { transform:rotate(45deg) translate(5px,5px); }
    .burger-menu.active span:nth-child(2) { opacity:0; }
    .burger-menu.active span:nth-child(3) { transform:rotate(-45deg) translate(5px,-5px); }
    @media(max-width:768px) { .burger-menu { display:flex; } .nav-links { position:fixed; top:70px; left:0; width:100%; flex-direction:column; background:#fff; padding:20px 0; box-shadow:0 5px 10px rgba(0,0,0,0.1); transform:translateY(-100%); opacity:0; transition:all 0.3s ease; } .nav-links.active { transform:translateY(0); opacity:1; } .nav-actions { display:none; } }
  </style>
</head>
<body>
  <header class="navbar" id="navbar">
    <div class="logo">
      <img src="../assets/logo.png" alt="Pesona Jateng Logo">
    </div>

    <nav class="nav-links" id="navLinks">
      <a href="../home/index.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'home') !== false ? 'active' : '' ?>">Beranda</a>
      <a href="../wisata/index.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'wisata') !== false ? 'active' : '' ?>">Wisata</a>
      <a href="../favorite/index.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'favorite') !== false ? 'active' : '' ?>">Favorit</a>
    
      <?php if($user && $user['role'] === 'admin'): ?>
          <a href="../../admin/pages/dashboard/dashboard.php"><i class="bi bi-gear-fill"></i> Admin Panel</a>
      <?php endif; ?>
    </nav>

    <div class="nav-actions">
      <?php if($user): ?>
          <span class="user-name"><?= htmlspecialchars($user['nama_lengkap']); ?></span>
          <a href="../login/logout.php" class="icon-btn" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
      <?php else: ?>
          <a href="../login/index.php" class="icon-btn" title="Login"><i class="bi bi-box-arrow-in-right"></i></a>
      <?php endif; ?>
    </div>

    <div class="burger-menu" id="burgerMenu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>

  <script>
    const burgerMenu = document.getElementById('burgerMenu');
    const navLinks = document.getElementById('navLinks');
    burgerMenu.addEventListener('click', () => {
      burgerMenu.classList.toggle('active');
      navLinks.classList.toggle('active');
    });

    window.addEventListener("scroll", function() {
      const navbar = document.getElementById("navbar");
      navbar.classList.toggle("shrink", window.scrollY > 10);
    });
  </script>
</body>
</html>
