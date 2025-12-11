<?php

if(!defined('BASE_URL')) define('BASE_URL','/pesona_jateng/admin/');

// Ambil data user
$user_available = false;
if (isset($user) && is_array($user)) {
    $user_available = true;
} elseif (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $q = "SELECT id_user, username, nama_lengkap, role FROM users WHERE id_user='$uid' LIMIT 1";
    $r = mysqli_query($conn, $q);
    if ($r && mysqli_num_rows($r)>0) {
        $user = mysqli_fetch_assoc($r);
        $user_available = true;
    }
}

// Siapkan avatar
$display_name = $user_available ? $user['nama_lengkap'] : 'Guest';
$display_role = $user_available ? $user['role'] : 'Pengunjung';
$avatar_text = 'G?';
if ($user_available && !empty($user['nama_lengkap'])) {
    $parts = explode(' ', trim($user['nama_lengkap']));
    $avatar_text = count($parts)>=2 ? strtoupper(substr($parts[0],0,1).substr($parts[1],0,1)) : strtoupper(substr($parts[0],0,2));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sidebar - Pesona Jateng</title>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* Reset & body */
body {margin:0; font-family:'Poppins',sans-serif; background:#f5f6fa;}
#sidebar {
    position: fixed; top:0; left:0; height:100vh; width:250px;
    background:#fff; color:#333; box-shadow:2px 0 10px rgba(0,0,0,0.08);
    display:flex; flex-direction:column; transition: width 0.3s; overflow:hidden;
    z-index:100;
}
#sidebar.collapsed { width:80px; }

.toggle-btn {
    position:absolute; top:15px; right:-15px;
    background:#fff; border-radius:50%; width:30px; height:30px;
    box-shadow:0 0 5px rgba(0,0,0,0.1);
    display:flex; align-items:center; justify-content:center; cursor:pointer;
    transition: transform 0.3s;
}
.toggle-btn:hover { transform:scale(1.1); }

/* Brand */
.brand { display:flex; align-items:center; padding:20px 15px; gap:10px; text-decoration:none; color:#333; flex-shrink:0; }
.brand img { width:45px; height:45px; object-fit:contain; }
.brand .text { font-size:18px; font-weight:600; white-space:nowrap; }
#sidebar.collapsed .brand .text { display:none; }

/* Search Box */
.search-box { display:flex; align-items:center; background:#f1f2f6; border-radius:10px; margin:0 15px; padding:8px 12px; gap:8px; flex-shrink:0; }
.search-box input { border:none; outline:none; background:none; width:100%; font-size:14px; }

/* Menu */
.menu-section { font-size:13px; font-weight:600; color:#888; margin:20px 15px 10px; flex-shrink:0; }
ul { list-style:none; padding:0; margin:0; }
ul li a { display:flex; align-items:center; gap:15px; padding:12px 15px; color:#333; text-decoration:none; transition:0.3s; border-radius:10px; }
ul li a:hover { background:#f0f0f0; }
ul li a i { font-size:20px; min-width:25px; text-align:center; }
.badge { background:#ff4757; color:#fff; font-size:11px; padding:2px 6px; border-radius:6px; margin-left:auto; }
.menu-divider { height:1px; background:#e0e0e0; margin:15px 0; }

/* Side Menu Scrollable */
.side-menu { flex:1; overflow-y:auto; padding-bottom:10px; }

/* User Profile Section */
.user-section { 
    background: #f9f9f9; 
    padding: 15px; 
    border-top: 1px solid #e0e0e0;
    flex-shrink:0;
}
.user-profile { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.user-avatar { background: linear-gradient(135deg, #74b9ff, #0984e3); color: #fff; font-weight:600; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; font-size:16px; flex-shrink:0; }
.user-info .user-name { font-weight:600; color:#333; font-size:14px; }
.user-info .user-role { font-size:11px; color:#666; text-transform:capitalize; }

/* User Actions */
.user-actions { display:flex; gap:8px; margin-top:10px; }
.user-action-btn {
    flex:1; display:flex; align-items:center; justify-content:center; gap:5px; padding:8px 12px; border:none; border-radius:8px; font-size:12px; font-weight:500; cursor:pointer; transition: all 0.3s ease; text-decoration:none; white-space:nowrap;
}
.profile-btn { background:#e8f4ff; color:#007bff; border:1px solid #cce5ff; }
.profile-btn:hover { background:#007bff; color:white; transform:translateY(-1px); }
.logout-btn { background:#ffe8e8; color:#dc3545; border:1px solid #ffcccc; }
.logout-btn:hover { background:#dc3545; color:white; transform:translateY(-1px); }
.user-action-btn i { font-size:14px; }

/* Collapsed Mode */
#sidebar.collapsed .text, 
#sidebar.collapsed .badge, 
#sidebar.collapsed .menu-section,
#sidebar.collapsed .user-info,
#sidebar.collapsed .user-action-btn span { display:none; }
#sidebar.collapsed .user-profile { justify-content:center; margin-bottom:15px; }
#sidebar.collapsed .user-actions { flex-direction:column; gap:5px; }
#sidebar.collapsed .user-action-btn { padding:8px; border-radius:50%; width:35px; height:35px; }
</style>
</head>
<body>

<aside id="sidebar">
    <div class="toggle-btn" id="toggleBtn"><i class='bx bx-chevron-left'></i></div>

    <a href="<?= BASE_URL ?>pages/dashboard/dashboard.php" class="brand">
        <img src="<?= BASE_URL ?>assets/img/logo.png" alt="Pesona Jateng Logo">
        <span class="text">Pesona Jateng</span>
    </a>

    <div class="search-box">
        <i class='bx bx-search'></i>
        <input type="text" placeholder="Cari...">
    </div>

    <div class="side-menu">
        <div class="menu-section">Menu Utama</div>
        <ul class="top">
            <li><a href="<?= BASE_URL ?>pages/dashboard/dashboard.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="<?= BASE_URL ?>pages/wisata/wisata.php"><i class='bx bxs-map'></i><span class="text">Wisata</span><span class="badge">5</span></a></li>
            <li><a href="<?= BASE_URL ?>pages/pengguna/pengguna.php"><i class='bx bxs-group'></i><span class="text">Pengguna</span><span class="badge">12</span></a></li>
        
        </ul>

        <div class="menu-divider"></div>

        <div class="menu-section">Konten</div>
        <ul class="middle">
    
            <li><a href="<?= BASE_URL ?>pages/galeri/galeri.php"><i class='bx bxs-image'></i><span class="text">Galeri</span></a></li>
        </ul>

        <div class="menu-divider"></div>

    
    </div>

    <!-- User Section -->
    <div class="user-section">
        <div class="user-profile">
            <div class="user-avatar"><?= $avatar_text ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($display_name) ?></div>
                <div class="user-role"><?= htmlspecialchars($display_role) ?></div>
            </div>
        </div>
        

          <a href="<?= BASE_URL ?>includes/sidebar/logout.php" class="user-action-btn logout-btn" title="Keluar" onclick="return confirm('Yakin ingin logout?')">
    <i class='bx bxs-log-out'></i>
    <span>Keluar</span>
</a>

            </a>
        </div>
    </div>
</aside>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggleBtn');
toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    toggleBtn.querySelector('i').classList.toggle('bx-chevron-right');
    toggleBtn.querySelector('i').classList.toggle('bx-chevron-left');
});
</script>
</body>
</html>
