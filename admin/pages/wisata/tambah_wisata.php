<?php
require_once '../../../config/koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_wisata'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['id_kategori'];
    $rating = $_POST['rating'];
    $gambar = $_FILES['gambar']['name'];

    // Upload gambar
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../galeri/img/" . $gambar);

    $tanggal = date('Y-m-d H:i:s');
    $query = "INSERT INTO wisata (nama_wisata, lokasi, deskripsi, gambar, tanggal_ditambahkan, id_kategori, rating)
              VALUES ('$nama', '$lokasi', '$deskripsi', '$gambar', '$tanggal', '$kategori', '$rating')";
    mysqli_query($conn, $query);

    header("Location: wisata.php");
    exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori");

// Ambil data lokasi - jika menggunakan tabel terpisah
// $lokasi_query = mysqli_query($koneksi, "SELECT * FROM lokasi ORDER BY nama_kota");

// Jika tidak ada tabel terpisah, gunakan array manual
$daftar_lokasi = [
    'Magelang, Jawa Tengah',
    'Banjarnegara, Jawa Tengah',
    'Banyumas, Jawa Tengah',
    'Semarang, Jawa Tengah',
    'Jepara, Jawa Tengah',
    'Klaten, Jawa Tengah',
    'Surakarta, Jawa Tengah',
    'Kebumen, Jawa Tengah'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wisata Baru | Sistem Manajemen Wisata</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --danger: #e74c3c;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
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

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--light);
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .back-btn:hover {
            background-color: var(--border);
            transform: translateY(-2px);
        }

        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
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
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
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

        .rating-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .rating-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-input {
            width: 80px;
            text-align: center;
        }

        .rating-slider {
            flex: 1;
            min-width: 200px;
        }

        .rating-preview {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            min-width: 60px;
        }

        .rating-stars-preview {
            display: flex;
            gap: 2px;
        }

        .rating-stars-preview i {
            font-size: 16px;
        }

        .star-filled {
            color: #ffc107;
        }

        .star-half {
            color: #ffc107;
            position: relative;
        }

        .star-half:before {
            content: '\f005';
            position: absolute;
            left: 0;
            width: 50%;
            overflow: hidden;
            color: #ffc107;
        }

        .star-empty {
            color: #ddd;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            background-color: var(--light);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .file-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .file-upload-label i {
            font-size: 40px;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .file-upload-label span {
            color: var(--gray);
            font-weight: 500;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .preview-container {
            margin-top: 15px;
            display: none;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--light);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background-color: var(--border);
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: none;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        .slider-value {
            font-weight: 500;
            color: var(--primary);
            min-width: 40px;
            text-align: center;
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
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .rating-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .rating-slider {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Tambah Wisata Baru</h1>
            <a href="wisata.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Wisata
            </a>
        </div>

        <div class="alert alert-error" id="errorAlert">
            <i class="fas fa-exclamation-circle"></i> <span id="errorMessage"></span>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Informasi Wisata</h2>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="wisataForm">
                    <div class="form-group">
                        <label for="nama_wisata" class="form-label">Nama Wisata</label>
                        <input type="text" id="nama_wisata" name="nama_wisata" class="form-control" placeholder="Masukkan nama wisata" required>
                    </div>

                    <div class="form-group">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <select id="lokasi" name="lokasi" class="form-control" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach($daftar_lokasi as $lokasi): ?>
                                <option value="<?= $lokasi ?>"><?= $lokasi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Deskripsikan tempat wisata ini" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="id_kategori" class="form-label">Kategori</label>
                        <select id="id_kategori" name="id_kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <div class="rating-container">
                            <div class="rating-input-group">
                                <input type="number" id="ratingInput" name="rating" class="form-control rating-input" 
                                       min="0" max="5" step="0.1" value="0.0" required>
                                <span class="slider-value" id="sliderValue">0.0</span>
                            </div>
                            <input type="range" id="ratingSlider" class="rating-slider" 
                                   min="0" max="5" step="0.1" value="0">
                            <div class="rating-preview">
                                <div class="rating-stars-preview" id="ratingStarsPreview">
                                    <i class="far fa-star star-empty"></i>
                                    <i class="far fa-star star-empty"></i>
                                    <i class="far fa-star star-empty"></i>
                                    <i class="far fa-star star-empty"></i>
                                    <i class="far fa-star star-empty"></i>
                                </div>
                                <small id="ratingText">Belum ada rating</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gambar Wisata</label>
                        <div class="file-upload">
                            <label class="file-upload-label" id="fileUploadLabel">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik untuk mengunggah gambar</span>
                                <small>Format yang didukung: JPG, PNG, GIF (Maks. 5MB)</small>
                            </label>
                            <input type="file" name="gambar" id="gambar" accept="image/*" required>
                        </div>
                        <div class="preview-container" id="previewContainer">
                            <img src="" alt="Preview" class="preview-image" id="previewImage">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary" id="resetBtn">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Wisata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Rating functionality dengan input desimal
        const ratingInput = document.getElementById('ratingInput');
        const ratingSlider = document.getElementById('ratingSlider');
        const sliderValue = document.getElementById('sliderValue');
        const ratingStarsPreview = document.getElementById('ratingStarsPreview');
        const ratingText = document.getElementById('ratingText');

        function updateRatingDisplay(rating) {
            // Update slider value display
            sliderValue.textContent = parseFloat(rating).toFixed(1);
            
            // Update stars preview
            const stars = ratingStarsPreview.querySelectorAll('i');
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            
            stars.forEach((star, index) => {
                star.className = 'far fa-star star-empty';
                
                if (index < fullStars) {
                    star.className = 'fas fa-star star-filled';
                } else if (index === fullStars && hasHalfStar) {
                    star.className = 'fas fa-star-half star-half';
                }
            });
            
            // Update rating text
            if (rating == 0) {
                ratingText.textContent = 'Belum ada rating';
            } else {
                ratingText.textContent = parseFloat(rating).toFixed(1) + ' / 5.0';
            }
        }

        // Sync input dan slider
        ratingInput.addEventListener('input', function() {
            let value = parseFloat(this.value);
            
            // Validate range
            if (value < 0) value = 0;
            if (value > 5) value = 5;
            
            this.value = value.toFixed(1);
            ratingSlider.value = value;
            updateRatingDisplay(value);
        });

        ratingSlider.addEventListener('input', function() {
            const value = parseFloat(this.value);
            ratingInput.value = value.toFixed(1);
            updateRatingDisplay(value);
        });

        // Initialize rating display
        updateRatingDisplay(0);

        // Image preview functionality
        const fileInput = document.getElementById('gambar');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const fileUploadLabel = document.getElementById('fileUploadLabel');
        
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImage.setAttribute('src', this.result);
                    previewContainer.style.display = 'block';
                    fileUploadLabel.innerHTML = '<i class="fas fa-check-circle" style="color: var(--secondary);"></i><span>Gambar dipilih: ' + file.name + '</span>';
                });
                
                reader.readAsDataURL(file);
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showError('Ukuran file terlalu besar. Maksimal 5MB.');
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    fileUploadLabel.innerHTML = '<i class="fas fa-cloud-upload-alt"></i><span>Klik untuk mengunggah gambar</span><small>Format yang didukung: JPG, PNG, GIF (Maks. 5MB)</small>';
                }
            }
        });
        
        // Form validation
        const form = document.getElementById('wisataForm');
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        const resetBtn = document.getElementById('resetBtn');
        
        function showError(message) {
            errorMessage.textContent = message;
            errorAlert.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }
        
        form.addEventListener('submit', function(e) {
            let valid = true;
            
            // Reset error state
            errorAlert.style.display = 'none';
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--danger)';
                } else {
                    field.style.borderColor = 'var(--border)';
                }
            });
            
            // Validate rating
            const rating = parseFloat(ratingInput.value);
            if (rating < 0 || rating > 5) {
                valid = false;
                ratingInput.style.borderColor = 'var(--danger)';
                showError('Rating harus antara 0.0 hingga 5.0');
            }
            
            if (!valid) {
                e.preventDefault();
                if (!errorAlert.style.display || errorAlert.style.display === 'none') {
                    showError('Harap lengkapi semua field yang wajib diisi.');
                }
            }
        });
        
        // Reset form functionality
        resetBtn.addEventListener('click', function() {
            // Reset rating display
            ratingInput.value = '0.0';
            ratingSlider.value = '0';
            updateRatingDisplay(0);
            
            // Reset file upload
            previewContainer.style.display = 'none';
            fileUploadLabel.innerHTML = '<i class="fas fa-cloud-upload-alt"></i><span>Klik untuk mengunggah gambar</span><small>Format yang didukung: JPG, PNG, GIF (Maks. 5MB)</small>';
            
            // Reset error states
            errorAlert.style.display = 'none';
            const formControls = form.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.style.borderColor = 'var(--border)';
            });
        });
        
        // Reset form border colors on input
        const formControls = form.querySelectorAll('.form-control');
        formControls.forEach(control => {
            control.addEventListener('input', function() {
                this.style.borderColor = 'var(--border)';
            });
        });
    </script>
</body>
</html>