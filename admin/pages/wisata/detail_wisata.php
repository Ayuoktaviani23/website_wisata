<?php
require_once '../../../config/koneksi.php';
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query data utama wisata
$query = "
    SELECT w.*, k.nama_kategori, d.full_deskripsi, d.jam_operasional, 
           d.durasi_wisata, d.waktu_terbaik, d.cuaca_saat_ini
    FROM wisata w
    LEFT JOIN kategori k ON w.id_kategori = k.id_kategori
    LEFT JOIN detail_wisata d ON w.id_wisata = d.id_wisata
    WHERE w.id_wisata = $id
";
$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

if(!$data) {
    echo "Data wisata tidak ditemukan!";
    exit;
}

// Query data terkait
$fasilitas = mysqli_query($conn, "SELECT * FROM fasilitas_wisata WHERE id_wisata = $id");
$tiket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tiket_wisata WHERE id_wisata = $id"));
$galeri = mysqli_query($conn, "SELECT * FROM galeri_wisata WHERE id_wisata = $id");


$galeri_path = "galeri/img/"; // Sesuaikan dengan struktur folder Anda
?>
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($data['nama_wisata']) ?> - Detail Wisata</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
    --primary: #3498db;
    --primary-dark: #2980b9;
    --secondary: #2ecc71;
    --danger: #e74c3c;
    --warning: #f39c12;
    --light: #f8f9fa;
    --dark: #343a40;
    --gray: #6c757d;
    --border: #dee2e6;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7fa;
    color: var(--dark);
    line-height: 1.6;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--primary);
}

.header h1 {
    font-size: 28px;
    font-weight: 600;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.header h1 i {
    color: var(--primary);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.btn-secondary {
    background-color: var(--light);
    color: var(--dark);
    border: 1px solid var(--border);
}

.btn-secondary:hover {
    background-color: var(--border);
    transform: translateY(-2px);
}

.card {
    background-color: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 25px;
}

.card-header {
    background: linear-gradient(to right, var(--primary), var(--primary-dark));
    color: white;
    padding: 20px 25px;
}

.card-header h2 {
    font-size: 22px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.info-group {
    margin-bottom: 20px;
}

.info-label {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    padding: 12px 15px;
    background-color: var(--light);
    border-radius: 8px;
    border-left: 4px solid var(--primary);
}

.rating-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rating-stars {
    display: flex;
    gap: 3px;
}

.rating-stars i {
    color: #ffc107;
    font-size: 18px;
}

.main-image {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
}

.fasilitas-list {
    list-style: none;
    padding: 0;
}

.fasilitas-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--light);
    margin-bottom: 8px;
    border-radius: 8px;
    border-left: 4px solid var(--secondary);
}

.fasilitas-item.unavailable {
    border-left-color: var(--danger);
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-available {
    background: rgba(46, 204, 113, 0.1);
    color: var(--secondary);
}

.status-unavailable {
    background: rgba(231, 76, 60, 0.1);
    color: var(--danger);
}

.ticket-prices {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.ticket-item {
    background: var(--light);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    border-top: 4px solid var(--primary);
}

.ticket-type {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: 5px;
}

.ticket-price {
    font-size: 24px;
    font-weight: 600;
    color: var(--primary);
}

.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.gallery img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.gallery img:hover {
    transform: scale(1.05);
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--gray);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: var(--border);
}

.admin-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

@media (max-width: 768px) {
    .container {
        padding: 0 15px;
    }
    
    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-actions {
        flex-direction: column;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-map-marked-alt"></i> Detail Wisata</h1>
            <div>
                <a href="wisata.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="edit_wisata.php?id=<?= $id ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Wisata
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informasi Utama -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Informasi Utama</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div>
                        <img src="../../admin/pages/galeri/img/<?= htmlspecialchars($data['gambar']) ?>" 
                             alt="<?= htmlspecialchars($data['nama_wisata']) ?>" 
                             class="main-image"
                             onerror="this.src='../../admin/pages/galeri/img/default.jpg'">
                    </div>
                    
                    <div>
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-map-pin"></i> Nama Wisata
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['nama_wisata']) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-location-dot"></i> Lokasi
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['lokasi']) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-tags"></i> Kategori
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['nama_kategori'] ?? 'Tidak ada kategori') ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-star"></i> Rating
                            </div>
                            <div class="info-value rating-container">
                                <div class="rating-stars">
                                    <?php
                                    $rating = floatval($data['rating']);
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($i <= $fullStars): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif;
                                    endfor;
                                    ?>
                                </div>
                                <span>(<?= number_format($rating, 1) ?>)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </div>
                    <div class="info-value">
                        <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Tambahan -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-list-alt"></i> Detail Tambahan</h2>
            </div>
            <div class="card-body">
                <?php if(empty($data['full_deskripsi'])): ?>
                    <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <h3>Data Detail Belum Tersedia</h3>
                        <p>Informasi detail tentang wisata ini belum ditambahkan.</p>
                    </div>
                <?php else: ?>
                    <div class="info-grid">
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-align-justify"></i> Deskripsi Lengkap
                            </div>
                            <div class="info-value">
                                <?= nl2br(htmlspecialchars($data['full_deskripsi'])) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> Jam Operasional
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['jam_operasional']) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-hourglass-half"></i> Durasi Wisata
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['durasi_wisata']) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i> Waktu Terbaik
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['waktu_terbaik']) ?>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-cloud-sun"></i> Cuaca Saat Ini
                            </div>
                            <div class="info-value">
                                <?= htmlspecialchars($data['cuaca_saat_ini']) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fasilitas -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-concierge-bell"></i> Fasilitas</h2>
            </div>
            <div class="card-body">
                <?php if(mysqli_num_rows($fasilitas) > 0): ?>
                    <ul class="fasilitas-list">
                        <?php while($f = mysqli_fetch_assoc($fasilitas)): ?>
                            <li class="fasilitas-item <?= $f['tersedia'] == 'TIDAK' ? 'unavailable' : '' ?>">
                                <span><?= htmlspecialchars($f['nama_fasilitas']) ?></span>
                                <span class="status-badge <?= $f['tersedia'] == 'YA' ? 'status-available' : 'status-unavailable' ?>">
                                    <?= $f['tersedia'] == 'YA' ? 'Tersedia' : 'Tidak Tersedia' ?>
                                </span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-concierge-bell"></i>
                        <h3>Belum Ada Fasilitas</h3>
                        <p>Fasilitas untuk wisata ini belum ditambahkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Harga Tiket -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-ticket-alt"></i> Harga Tiket</h2>
            </div>
            <div class="card-body">
                <?php if($tiket): ?>
                    <div class="ticket-prices">
                        <div class="ticket-item">
                            <div class="ticket-type">Anak-anak</div>
                            <div class="ticket-price">Rp <?= number_format($tiket['harga_anak'], 0, ',', '.') ?></div>
                        </div>
                        <div class="ticket-item">
                            <div class="ticket-type">Dewasa</div>
                            <div class="ticket-price">Rp <?= number_format($tiket['harga_dewasa'], 0, ',', '.') ?></div>
                        </div>
                        <div class="ticket-item">
                            <div class="ticket-type">Mancanegara</div>
                            <div class="ticket-price">Rp <?= number_format($tiket['harga_mancanegara'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>Data Tiket Belum Tersedia</h3>
                        <p>Informasi harga tiket untuk wisata ini belum ditambahkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Galeri -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-images"></i> Galeri</h2>
            </div>
            <div class="card-body">
                <?php if(mysqli_num_rows($galeri) > 0): ?>
                    <div class="gallery">
                        <?php while($g = mysqli_fetch_assoc($galeri)): ?>
                            <img src="<?= $galeri_path . htmlspecialchars($g['foto']) ?>" 
                                 alt="Galeri <?= htmlspecialchars($data['nama_wisata']) ?>"
                                 onerror="this.src='<?= $galeri_path ?>default.jpg'">
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h3>Belum Ada Foto Galeri</h3>
                        <p>Galeri foto untuk wisata ini belum ditambahkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
        <!-- Tombol Admin -->
        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
            <div class="admin-actions">
                <a href="edit_wisata.php?id=<?= $id ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Semua Data Wisata
                </a>

            </div>
        <?php endif; ?>
    </div>
</body>
</html>