<?php
session_start();
require_once '../../config/koneksi.php';

// ambil id wisata dari URL
$id_wisata = 0;
if(isset($_GET['id'])) {
    $id_wisata = intval($_GET['id']);
}

// ===========================
// AMBIL DATA WISATA UTAMA
// ===========================
$sql_wisata = "SELECT w.*, k.nama_kategori 
               FROM wisata w
               LEFT JOIN kategori k ON w.id_kategori = k.id_kategori
               WHERE w.id_wisata = '$id_wisata' 
               LIMIT 1";
$result_wisata = mysqli_query($conn, $sql_wisata);
$wisata = mysqli_fetch_assoc($result_wisata);

if (!$wisata) {
    echo "Data wisata tidak ditemukan.";
    exit;
}

// ===========================
// DETAIL WISATA (TABEL BARU)
// ===========================
$sql_detail = "SELECT * FROM detail_wisata WHERE id_wisata = '$id_wisata' LIMIT 1";
$result_detail = mysqli_query($conn, $sql_detail);
$detail = mysqli_fetch_assoc($result_detail);

// fallback jika kolom tidak ada
$full_deskripsi  = isset($detail['full_deskripsi']) ? $detail['full_deskripsi'] : '-';
$jam_operasional = isset($detail['jam_operasional']) ? $detail['jam_operasional'] : '08:00 - 17:00';
$durasi_wisata   = isset($detail['durasi_wisata']) ? $detail['durasi_wisata'] : '-';
$waktu_terbaik   = isset($detail['waktu_terbaik']) ? $detail['waktu_terbaik'] : '-';
$cuaca           = isset($detail['cuaca_saat_ini']) ? $detail['cuaca_saat_ini'] : '-';

// ===========================
// FOTO UTAMA (dari tabel wisata)
// ===========================
$foto_utama = $wisata['gambar'];
if(empty($foto_utama)) {
    $foto_utama = 'default.jpg';
}

// ===========================
// GALERI TAMBAHAN
// ===========================
$sql_galeri = "SELECT foto FROM galeri_wisata WHERE id_wisata = '$id_wisata'";
$result_galeri = mysqli_query($conn, $sql_galeri);
$gallery = array();

// tambahkan foto utama sebagai gambar pertama
$gallery[] = $foto_utama;

// tambahkan foto dari galeri
while($row = mysqli_fetch_assoc($result_galeri)) {
    $gallery[] = $row['foto'];
}

// ===========================
// FASILITAS
// ===========================
$sql_fasilitas = "SELECT * FROM fasilitas_wisata WHERE id_wisata = '$id_wisata'";
$result_fasilitas = mysqli_query($conn, $sql_fasilitas);
$fasilitas = array();
while($row = mysqli_fetch_assoc($result_fasilitas)) {
    $fasilitas[] = $row;
}

// ===========================
// TIKET
// ===========================
$sql_tiket = "SELECT * FROM tiket_wisata WHERE id_wisata = '$id_wisata' LIMIT 1";
$result_tiket = mysqli_query($conn, $sql_tiket);
$tiket = mysqli_fetch_assoc($result_tiket);

// ===========================
// CEK FAVORIT
// ===========================
$is_fav = false;
if(isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $sql_check_fav = "SELECT * FROM favorite WHERE id_user = '$id_user' AND id_wisata = '$id_wisata'";
    $result_check = mysqli_query($conn, $sql_check_fav);
    if(mysqli_num_rows($result_check) > 0) {
        $is_fav = true;
    }
}

// ===========================
// PROSES TAMBAH/HAPUS FAVORIT
// ===========================
if(isset($_POST['action_fav'])) {
    if(!isset($_SESSION['id_user'])) {
        echo "<script>alert('Silakan login terlebih dahulu!');</script>";
        echo "<script>window.location.href = '../../login.php';</script>";
    } else {
        $id_user = $_SESSION['id_user'];
        
        if($_POST['action_fav'] == 'tambah') {
            // cek dulu apakah sudah favorit
            $sql_check = "SELECT * FROM favorite WHERE id_user = '$id_user' AND id_wisata = '$id_wisata'";
            $result_check = mysqli_query($conn, $sql_check);
            
            if(mysqli_num_rows($result_check) == 0) {
                // tambah ke favorit
                $sql_tambah = "INSERT INTO favorite (id_user, id_wisata) VALUES ('$id_user', '$id_wisata')";
                if(mysqli_query($conn, $sql_tambah)) {
                    echo "<script>alert('Berhasil ditambahkan ke favorit!');</script>";
                    $is_fav = true;
                } else {
                    echo "<script>alert('Gagal menambahkan ke favorit!');</script>";
                }
            }
            
        } elseif($_POST['action_fav'] == 'hapus') {
            // hapus dari favorit
            $sql_hapus = "DELETE FROM favorite WHERE id_user = '$id_user' AND id_wisata = '$id_wisata'";
            if(mysqli_query($conn, $sql_hapus)) {
                echo "<script>alert('Berhasil dihapus dari favorit!');</script>";
                $is_fav = false;
            } else {
                echo "<script>alert('Gagal menghapus dari favorit!');</script>";
            }
        }
    }
}

// ===========================
// HITUNG JUMLAH FAVORIT
// ===========================
$sql_jumlah_fav = "SELECT COUNT(*) as total FROM favorite WHERE id_wisata = '$id_wisata'";
$result_jumlah = mysqli_query($conn, $sql_jumlah_fav);
$row_jumlah = mysqli_fetch_assoc($result_jumlah);
$reviews_count = $row_jumlah['total'];

// rating
$rating = isset($wisata['rating']) ? floatval($wisata['rating']) : 0.0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $wisata['nama_wisata']; ?> - Detail Wisata</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: #f3f4f6;
        }

        .container {
            width: 92%;
            max-width: 1300px;
            margin: 40px auto;
        }

        /* HERO IMAGE */
        .hero {
            width: 100%;
            height: 420px;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            background: #000;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .92;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 25px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* RATING STAR */
        .rating-box {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .star {
            color: gold;
            font-size: 20px;
        }

        /* TOMBOL FAVORIT */
        .fav-btn {
            font-size: 26px;
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
        }

        .fav-btn:hover {
            background: #f0f0f0;
            transform: scale(1.1);
        }

        .fav-btn.active {
            color: red;
            background: white;
        }

        /* MAIN CONTENT */
        .content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.09);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        /* GALERI */
        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 14px;
            cursor: pointer;
            transition: .2s;
        }

        .gallery img:hover {
            opacity: .85;
            transform: scale(1.02);
        }

        /* FASILITAS */
        .fasilitas-list div {
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }

        /* PANEL SAMPING */
        .sidebar-box {
            padding: 20px;
            background: white;
            border-radius: 18px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.09);
            margin-bottom: 20px;
        }

        .sidebar-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .info-item {
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 10px;
            background: #f3f4f6;
            color: #555;
        }

        .info-item strong {
            color: #333;
        }
        
        /* FORM FAVORIT */
        .fav-form {
            display: inline;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- FORM UNTUK FAVORIT -->
    <form method="POST" class="fav-form" id="favForm">
        <input type="hidden" name="id_wisata" value="<?php echo $id_wisata; ?>">
        <input type="hidden" name="action_fav" value="<?php echo $is_fav ? 'hapus' : 'tambah'; ?>">
    </form>

    <!-- HERO IMAGE -->
    <div class="hero">
        <img src="../../admin/pages/galeri/img/<?php echo $foto_utama; ?>" 
             alt="<?php echo htmlspecialchars($wisata['nama_wisata']); ?>"
             onerror="this.src='../../admin/pages/galeri/img/default.jpg'">

        <!-- TOMBOL FAVORIT -->
        <?php if(isset($_SESSION['id_user'])): ?>
            <!-- Jika sudah login, tampilkan tombol favorit -->
            <button type="submit" form="favForm" class="fav-btn <?php echo $is_fav ? 'active' : ''; ?>" 
                    onclick="return confirm('<?php echo $is_fav ? 'Yakin ingin menghapus dari favorit?' : 'Yakin ingin menambahkan ke favorit?'; ?>')"
                    title="<?php echo $is_fav ? 'Hapus dari favorit' : 'Tambahkan ke favorit'; ?>">
                ♥
            </button>
        <?php else: ?>
            <!-- Jika belum login, tombol favorit tetap ada tapi redirect ke login -->
            <button type="button" class="fav-btn" 
                    onclick="if(confirm('Anda harus login terlebih dahulu. Lanjut ke halaman login?')) { window.location.href='../../login.php'; }"
                    title="Login untuk menambahkan favorit">
                ♥
            </button>
        <?php endif; ?>

        <div class="hero-overlay">
            <div class="hero-title"><?php echo htmlspecialchars($wisata['nama_wisata']); ?></div>

            <div class="rating-box">
                <span class="star">★</span>
                <span><?php echo number_format($rating, 1); ?></span>
                <span style="font-size:14px; opacity:.85;">
                    (<?php echo $reviews_count; ?> suka)
                </span>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- KIRI -->
        <div>

            <!-- DESKRIPSI -->
            <div class="card">
                <div class="section-title">Deskripsi Lengkap</div>
                <p style="line-height: 1.7; color: #555;">
                    <?php echo nl2br(htmlspecialchars($full_deskripsi)); ?>
                </p>
            </div>

            <!-- GALERI -->
            <?php if(count($gallery) > 0): ?>
            <div class="card" style="margin-top:25px;">
                <div class="section-title">Galeri Foto</div>

                <div class="gallery">
                    <?php foreach($gallery as $index => $g): ?>
                        <img src="../../admin/pages/galeri/img/<?php echo $g; ?>" 
                             alt="Foto Wisata <?php echo $index + 1; ?>"
                             onerror="this.src='../../admin/pages/galeri/img/default.jpg'">
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- FASILITAS -->
            <?php if(count($fasilitas) > 0): ?>
            <div class="card" style="margin-top:25px;">
                <div class="section-title">Fasilitas Wisata</div>

                <div class="fasilitas-list">
                    <?php foreach($fasilitas as $fas): ?>
                        <div>
                            <span><?php echo htmlspecialchars($fas['nama_fasilitas']); ?></span>
                            <strong><?php echo htmlspecialchars($fas['tersedia']); ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- KANAN -->
        <div>

            <!-- INFORMASI -->
            <div class="sidebar-box">
                <div class="sidebar-title">Informasi Wisata</div>

                <div class="info-item">
                    <strong>📍 Lokasi</strong><br>
                    <?php echo htmlspecialchars($wisata['lokasi']); ?>
                </div>
                
                <div class="info-item">
                    <strong>⏰ Jam Operasional</strong><br>
                    <?php echo htmlspecialchars($jam_operasional); ?>
                </div>
                
                <div class="info-item">
                    <strong>⏳ Durasi Wisata</strong><br>
                    <?php echo htmlspecialchars($durasi_wisata); ?>
                </div>
                
                <div class="info-item">
                    <strong>🌤 Cuaca</strong><br>
                    <?php echo htmlspecialchars($cuaca); ?>
                </div>
                
                <div class="info-item">
                    <strong>⭐ Waktu Terbaik</strong><br>
                    <?php echo htmlspecialchars($waktu_terbaik); ?>
                </div>
                
                <div class="info-item">
                    <strong>📁 Kategori</strong><br>
                    <?php echo htmlspecialchars($wisata['nama_kategori']); ?>
                </div>
            </div>

            <!-- TIKET -->
            <div class="sidebar-box">
                <div class="sidebar-title">Harga Tiket</div>

                <?php if($tiket): ?>
                    <div class="info-item">
                        <strong>Anak-anak</strong><br>
                        Rp <?php echo number_format($tiket['harga_anak'],0,',','.'); ?>
                    </div>
                    
                    <div class="info-item">
                        <strong>Dewasa</strong><br>
                        Rp <?php echo number_format($tiket['harga_dewasa'],0,',','.'); ?>
                    </div>
                    
                    <div class="info-item">
                        <strong>WNA</strong><br>
                        Rp <?php echo number_format($tiket['harga_mancanegara'],0,',','.'); ?>
                    </div>
                <?php else: ?>
                    <div class="info-item">
                        <strong>Harga Tiket</strong><br>
                        Belum tersedia
                    </div>
                <?php endif; ?>
                
           
            </div>

        </div>

    </div>
</div>

<script>
// Fungsi sederhana untuk menampilkan gambar besar
document.addEventListener('DOMContentLoaded', function() {
    // Saat gambar di galeri diklik
    var galeriGambar = document.querySelectorAll('.gallery img');
    galeriGambar.forEach(function(gambar) {
        gambar.addEventListener('click', function() {
            // Tampilkan gambar di jendela baru
            window.open(this.src, '_blank');
        });
    });
});
</script>

</body>
</html>