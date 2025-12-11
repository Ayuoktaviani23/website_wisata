<?php
require_once '../../../config/koneksi.php';
session_start();

// Cek apakah admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data wisata
$query = "
    SELECT w.*, k.nama_kategori, d.full_deskripsi, d.jam_operasional, 
           d.durasi_wisata, d.waktu_terbaik, d.cuaca_saat_ini
    FROM wisata w
    LEFT JOIN kategori k ON w.id_kategori = k.id_kategori
    LEFT JOIN detail_wisata d ON w.id_wisata = d.id_wisata
    WHERE w.id_wisata = $id
";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data) {
    echo "Data wisata tidak ditemukan!";
    exit;
}

// Ambil data kategori untuk dropdown
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

// Ambil data terkait
$fasilitas = mysqli_query($conn, "SELECT * FROM fasilitas_wisata WHERE id_wisata = $id");
$tiket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tiket_wisata WHERE id_wisata = $id"));
$galeri = mysqli_query($conn, "SELECT * FROM galeri_wisata WHERE id_wisata = $id");

// Proses form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update data utama wisata
    $nama_wisata = mysqli_real_escape_string($conn, $_POST['nama_wisata']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $id_kategori = intval($_POST['id_kategori']);
    $rating = floatval($_POST['rating']);
    
    // Handle upload gambar baru
    $gambar = $data['gambar']; // Default: gambar lama
    
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($file_ext, $allowed_ext)) {
            if($file_size < 5000000) { // Max 5MB
                // Hapus gambar lama jika bukan default
                if($data['gambar'] != 'default.jpg') {
                    $old_image = '../../admin/pages/galeri/img/' . $data['gambar'];
                    if(file_exists($old_image)) {
                        unlink($old_image);
                    }
                }
                
                // Generate nama unik untuk gambar
                $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
                $upload_path = '../../admin/pages/galeri/img/' . $new_file_name;
                
                if(move_uploaded_file($file_tmp, $upload_path)) {
                    $gambar = $new_file_name;
                }
            }
        }
    }
    
    // Update data wisata
    $update_wisata = "UPDATE wisata SET 
                     nama_wisata = '$nama_wisata',
                     lokasi = '$lokasi',
                     deskripsi = '$deskripsi',
                     gambar = '$gambar',
                     id_kategori = $id_kategori,
                     rating = $rating
                     WHERE id_wisata = $id";
    mysqli_query($conn, $update_wisata);
    
    // Update detail wisata
    $full_deskripsi = mysqli_real_escape_string($conn, $_POST['full_deskripsi']);
    $jam_operasional = mysqli_real_escape_string($conn, $_POST['jam_operasional']);
    $durasi_wisata = mysqli_real_escape_string($conn, $_POST['durasi_wisata']);
    $waktu_terbaik = mysqli_real_escape_string($conn, $_POST['waktu_terbaik']);
    $cuaca_saat_ini = mysqli_real_escape_string($conn, $_POST['cuaca_saat_ini']);
    
    $check_detail = mysqli_query($conn, "SELECT * FROM detail_wisata WHERE id_wisata = $id");
    if(mysqli_num_rows($check_detail) > 0) {
        $update_detail = "UPDATE detail_wisata SET 
                         full_deskripsi = '$full_deskripsi',
                         jam_operasional = '$jam_operasional',
                         durasi_wisata = '$durasi_wisata',
                         waktu_terbaik = '$waktu_terbaik',
                         cuaca_saat_ini = '$cuaca_saat_ini'
                         WHERE id_wisata = $id";
    } else {
        $update_detail = "INSERT INTO detail_wisata (id_wisata, full_deskripsi, jam_operasional, durasi_wisata, waktu_terbaik, cuaca_saat_ini)
                         VALUES ($id, '$full_deskripsi', '$jam_operasional', '$durasi_wisata', '$waktu_terbaik', '$cuaca_saat_ini')";
    }
    mysqli_query($conn, $update_detail);
    
    // Update tiket
    $harga_anak = mysqli_real_escape_string($conn, $_POST['harga_anak']);
    $harga_dewasa = mysqli_real_escape_string($conn, $_POST['harga_dewasa']);
    $harga_mancanegara = mysqli_real_escape_string($conn, $_POST['harga_mancanegara']);
    
    $check_tiket = mysqli_query($conn, "SELECT * FROM tiket_wisata WHERE id_wisata = $id");
    if(mysqli_num_rows($check_tiket) > 0) {
        $update_tiket = "UPDATE tiket_wisata SET 
                        harga_anak = '$harga_anak',
                        harga_dewasa = '$harga_dewasa',
                        harga_mancanegara = '$harga_mancanegara'
                        WHERE id_wisata = $id";
    } else {
        $update_tiket = "INSERT INTO tiket_wisata (id_wisata, harga_anak, harga_dewasa, harga_mancanegara)
                        VALUES ($id, '$harga_anak', '$harga_dewasa', '$harga_mancanegara')";
    }
    mysqli_query($conn, $update_tiket);
    
    // Redirect dengan pesan sukses
    header("Location: detail_wisata.php?id=$id&success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Wisata - <?= htmlspecialchars($data['nama_wisata']) ?></title>
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
    max-width: 1000px;
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

.btn-success {
    background-color: var(--secondary);
    color: white;
}

.btn-success:hover {
    background-color: #27ae60;
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

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.form-image {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.current-image {
    flex: 0 0 200px;
}

.current-image img {
    width: 100%;
    border-radius: 8px;
    border: 2px solid var(--border);
}

.image-upload {
    flex: 1;
}

.image-preview {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px dashed var(--border);
    display: none;
}

.rating-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

.rating-input {
    width: 100px;
    text-align: center;
}

.rating-stars {
    display: flex;
    gap: 5px;
}

.rating-stars i {
    color: #ffc107;
    font-size: 20px;
    cursor: pointer;
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

.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.gallery img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--border);
}

.empty-state {
    text-align: center;
    padding: 20px;
    color: var(--gray);
}

.empty-state i {
    font-size: 36px;
    margin-bottom: 10px;
    color: var(--border);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.tab-navigation {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 10px;
}

.tab-btn {
    padding: 10px 20px;
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 6px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: var(--transition);
}

.tab-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
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
    
    .form-image {
        flex-direction: column;
    }
    
    .current-image {
        flex: none;
        width: 100%;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-edit"></i> Edit Wisata</h1>
            <div>
                <a href="detail_wisata.php?id=<?= $id ?>" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> Lihat Mode
                </a>
                <a href="wisata.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle"></i>
                Data berhasil diperbarui!
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button type="button" class="tab-btn active" onclick="showTab('tab1')">
                    <i class="fas fa-info-circle"></i> Informasi Utama
                </button>
                <button type="button" class="tab-btn" onclick="showTab('tab2')">
                    <i class="fas fa-list-alt"></i> Detail Tambahan
                </button>
                <button type="button" class="tab-btn" onclick="showTab('tab3')">
                    <i class="fas fa-ticket-alt"></i> Harga Tiket
                </button>
            </div>

            <!-- Tab 1: Informasi Utama -->
            <div class="tab-content active" id="tab1">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-info-circle"></i> Informasi Utama</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group form-image">
                            <div class="current-image">
                                <div class="form-label">Gambar Saat Ini</div>
                                <img src="galeri/img/<?= htmlspecialchars($data['gambar']) ?>" 
                                     alt="Current Image"
                                     onerror="this.src='galeri/img/default.jpg'">
                            </div>
                            <div class="image-upload">
                                <label class="form-label">Ubah Gambar</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <small style="color: var(--gray); display: block; margin-top: 5px;">Format: JPG, PNG, GIF | Max: 5MB</small>
                                <img id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-pin"></i> Nama Wisata
                            </label>
                            <input type="text" name="nama_wisata" class="form-control" 
                                   value="<?= htmlspecialchars($data['nama_wisata']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-location-dot"></i> Lokasi
                            </label>
                            <input type="text" name="lokasi" class="form-control" 
                                   value="<?= htmlspecialchars($data['lokasi']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tags"></i> Kategori
                            </label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php while($kategori = mysqli_fetch_assoc($kategori_result)): ?>
                                    <option value="<?= $kategori['id_kategori'] ?>" 
                                        <?= $kategori['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-star"></i> Rating
                            </label>
                            <div class="rating-container">
                                <input type="number" name="rating" class="form-control rating-input" 
                                       min="0" max="5" step="0.1" 
                                       value="<?= $data['rating'] ?>" required>
                                <div class="rating-stars" id="ratingStars">
                                    <?php
                                    $rating = floatval($data['rating']);
                                    for($i = 1; $i <= 5; $i++):
                                        if($i <= floor($rating)): ?>
                                            <i class="fas fa-star" data-rating="<?= $i ?>"></i>
                                        <?php elseif($i == ceil($rating) && ($rating - floor($rating)) >= 0.5): ?>
                                            <i class="fas fa-star-half-alt" data-rating="<?= $i ?>"></i>
                                        <?php else: ?>
                                            <i class="far fa-star" data-rating="<?= $i ?>"></i>
                                        <?php endif;
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Detail Tambahan -->
            <div class="tab-content" id="tab2">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-list-alt"></i> Detail Tambahan</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-justify"></i> Deskripsi Lengkap
                            </label>
                            <textarea name="full_deskripsi" class="form-control" 
                                      placeholder="Deskripsi lengkap tentang wisata..."><?= htmlspecialchars($data['full_deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i> Jam Operasional
                            </label>
                            <input type="text" name="jam_operasional" class="form-control" 
                                   value="<?= htmlspecialchars($data['jam_operasional'] ?? '') ?>" 
                                   placeholder="Contoh: 08:00 - 17:00">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-hourglass-half"></i> Durasi Wisata
                            </label>
                            <input type="text" name="durasi_wisata" class="form-control" 
                                   value="<?= htmlspecialchars($data['durasi_wisata'] ?? '') ?>" 
                                   placeholder="Contoh: 2-3 jam">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i> Waktu Terbaik
                            </label>
                            <input type="text" name="waktu_terbaik" class="form-control" 
                                   value="<?= htmlspecialchars($data['waktu_terbaik'] ?? '') ?>" 
                                   placeholder="Contoh: April - Oktober">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-cloud-sun"></i> Cuaca Saat Ini
                            </label>
                            <input type="text" name="cuaca_saat_ini" class="form-control" 
                                   value="<?= htmlspecialchars($data['cuaca_saat_ini'] ?? '') ?>" 
                                   placeholder="Contoh: Cerah, 28°C">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Harga Tiket -->
            <div class="tab-content" id="tab3">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-ticket-alt"></i> Harga Tiket</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-child"></i> Harga Tiket Anak
                            </label>
                            <input type="number" name="harga_anak" class="form-control" 
                                   value="<?= $tiket['harga_anak'] ?? '' ?>" 
                                   placeholder="Masukkan harga tiket anak"
                                   min="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Harga Tiket Dewasa
                            </label>
                            <input type="number" name="harga_dewasa" class="form-control" 
                                   value="<?= $tiket['harga_dewasa'] ?? '' ?>" 
                                   placeholder="Masukkan harga tiket dewasa"
                                   min="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-globe"></i> Harga Tiket Mancanegara
                            </label>
                            <input type="number" name="harga_mancanegara" class="form-control" 
                                   value="<?= $tiket['harga_mancanegara'] ?? '' ?>" 
                                   placeholder="Masukkan harga tiket mancanegara"
                                   min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="detail_wisata.php?id=<?= $id ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Semua Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        // Tab Navigation
        function showTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        // Image Preview
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Star Rating Interaction
        const stars = document.querySelectorAll('#ratingStars i');
        const ratingInput = document.querySelector('input[name="rating"]');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                // Update stars display
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.className = 'fas fa-star';
                    } else {
                        s.className = 'far fa-star';
                    }
                });
            });
            
            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-rating');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.className = 'fas fa-star';
                    } else {
                        s.className = 'far fa-star';
                    }
                });
            });
            
            star.addEventListener('mouseout', function() {
                const currentRating = parseFloat(ratingInput.value);
                stars.forEach((s, index) => {
                    if (index < Math.floor(currentRating)) {
                        s.className = 'fas fa-star';
                    } else if (index == Math.floor(currentRating) && (currentRating - Math.floor(currentRating)) >= 0.5) {
                        s.className = 'fas fa-star-half-alt';
                    } else {
                        s.className = 'far fa-star';
                    }
                });
            });
        });
    </script>
</body>
</html>