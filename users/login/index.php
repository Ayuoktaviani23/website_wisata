<?php
session_start();

// Koneksi database
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'db_pesonajateng';
$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ---------- LOGIN ----------
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // sesuai struktur tabel

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $login_success = "Login berhasil! Mengalihkan...";
        $redirect = $user['role'] === 'admin' ? '../home/index.php' : '../home/index.php';
        echo "<meta http-equiv='refresh' content='1;url=$redirect'>";
    } else {
        $login_error = "Username atau password salah!";
    }
}

// ---------- REGISTRASI ----------
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // sesuai struktur tabel

    if (empty($username) || empty($nama_lengkap) || empty($email) || empty($_POST['password'])) {
        $register_error = "Semua field harus diisi!";
    } else {
        // Cek username
        $cekUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cekUser) > 0) {
            $register_error = "Username sudah digunakan!";
        } else {
            // Cek email
            $cekEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
            if (mysqli_num_rows($cekEmail) > 0) {
                $register_error = "Email sudah digunakan!";
            } else {
                // Insert user baru
                $insert = mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, email, role) VALUES ('$username', '$password', '$nama_lengkap', '$email', 'user')");
                if ($insert) {
                    $register_success = "Registrasi berhasil! Silakan login.";
                } else {
                    $register_error = "Terjadi kesalahan saat registrasi.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <title>Login & Registrasi - Pesona Jateng</title>
    <style>
        /* CSS Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            color: #333;
            line-height: 1.6;
        }

        .container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Notifikasi Toast Modern */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .toast {
            background: white;
            border-radius: 8px;
            padding: 16px 20px;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            animation: slideInRight 0.3s ease-out;
            border-left: 4px solid;
        }
        
        .toast.success {
            border-left-color: #10b981;
        }
        
        .toast.error {
            border-left-color: #ef4444;
        }
        
        .toast-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .toast.success .toast-icon {
            color: #10b981;
        }
        
        .toast.error .toast-icon {
            color: #ef4444;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .toast-message {
            font-size: 14px;
            color: #6b7280;
        }
        
        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #9ca3af;
            margin-left: 10px;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast.hide {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        /* Form Styles */
        .forms-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .signin-signup {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 75%;
            width: 50%;
            transition: 1s 0.7s ease-in-out;
            display: grid;
            grid-template-columns: 1fr;
            z-index: 5;
        }

        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 5rem;
            transition: all 0.2s 0.7s;
            overflow: hidden;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }

        form.sign-up-form {
            opacity: 0;
            z-index: 1;
        }

        form.sign-in-form {
            z-index: 2;
        }

        .title {
            font-size: 2.2rem;
            color: #444;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .input-field {
            max-width: 380px;
            width: 100%;
            background-color: #f8f9fa;
            margin: 10px 0;
            height: 55px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .input-field:focus-within {
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .input-field i {
            text-align: center;
            line-height: 55px;
            color: #6c757d;
            transition: 0.5s;
            font-size: 1.1rem;
        }

        .input-field input {
            background: none;
            outline: none;
            border: none;
            line-height: 1;
            font-weight: 500;
            font-size: 1rem;
            color: #333;
            font-family: 'Inter', sans-serif;
        }

        .input-field input::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }

        .btn {
            width: 150px;
            background-color: #3498db;
            border: none;
            outline: none;
            height: 49px;
            border-radius: 12px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
            margin: 10px 0;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        /* Panel Styles */
        .panels-container {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .container:before {
            content: "";
            position: absolute;
            height: 2000px;
            width: 2000px;
            top: -10%;
            right: 48%;
            transform: translateY(-50%);
            background-image: linear-gradient(-45deg, #3498db 0%, #2980b9 55%, #1c6ea4 100%);
            transition: 1.8s ease-in-out;
            border-radius: 50%;
            z-index: 6;
        }

        .image {
            width: 90%;
            max-width: 500px;
            transition: transform 1.1s ease-in-out;
            transition-delay: 0.4s;
        }

        .panel {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-around;
            text-align: center;
            z-index: 6;
        }

        .left-panel {
            pointer-events: all;
            padding: 3rem 12% 2rem 12%;
        }

        .right-panel {
            pointer-events: none;
            padding: 3rem 12% 2rem 12%;
        }

        .panel .content {
            color: #fff;
            transition: transform 0.9s ease-in-out;
            transition-delay: 0.6s;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .panel h3 {
            font-weight: 600;
            line-height: 1;
            font-size: 1.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .panel p {
            font-size: 0.95rem;
            padding: 0.7rem 0;
            font-weight: 300;
        }

        .btn.transparent {
            margin: 0;
            background: none;
            border: 2px solid #fff;
            width: 130px;
            height: 41px;
            font-weight: 600;
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
        }

        .btn.transparent:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .right-panel .image,
        .right-panel .content {
            transform: translateX(800px);
        }

        /* Animasi */
        .container.sign-up-mode:before {
            transform: translate(100%, -50%);
            right: 52%;
        }

        .container.sign-up-mode .left-panel .image,
        .container.sign-up-mode .left-panel .content {
            transform: translateX(-800px);
        }

        .container.sign-up-mode .signin-signup {
            left: 25%;
        }

        .container.sign-up-mode form.sign-up-form {
            opacity: 1;
            z-index: 2;
        }

        .container.sign-up-mode form.sign-in-form {
            opacity: 0;
            z-index: 1;
        }

        .container.sign-up-mode .right-panel .image,
        .container.sign-up-mode .right-panel .content {
            transform: translateX(0%);
        }

        .container.sign-up-mode .left-panel {
            pointer-events: none;
        }

        .container.sign-up-mode .right-panel {
            pointer-events: all;
        }

        /* Logo dan konten panel */
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .logo {
            width: auto;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
            background: none;
            border-radius: 0;
        }

        .logo img {
            width: 120px;
            height: auto;
            max-height: 120px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            letter-spacing: 1px;
            font-family: 'Poppins', sans-serif;
        }

        .brand-tagline {
            font-size: 0.85rem;
            font-weight: 400;
            opacity: 0.9;
            line-height: 1.4;
        }

        .slogan {
            font-size: 1rem;
            line-height: 1.6;
            margin: 1rem 0;
            text-align: center;
            max-width: 400px;
            font-weight: 300;
        }

        /* Logo di atas form */
        .form-logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .form-logo {
            width: auto;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .form-logo img {
            width: 80px;
            height: auto;
            max-height: 80px;
            object-fit: contain;
        }

        .form-brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            letter-spacing: 1px;
            color: #3498db;
            font-family: 'Poppins', sans-serif;
        }

        .form-brand-tagline {
            font-size: 0.75rem;
            font-weight: 400;
            color: #666;
            line-height: 1.4;
        }

        /* Perbaikan elemen form */
        .form-footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .form-footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .password-container {
            position: relative;
            width: 100%;
            max-width: 380px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 2;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #3498db;
        }

        /* Responsive Styles */
        @media (max-width: 870px) {
            .container {
                min-height: 800px;
                height: 100vh;
            }
            .signin-signup {
                width: 100%;
                top: 95%;
                transform: translate(-50%, -100%);
                transition: 1s 0.8s ease-in-out;
            }

            .signin-signup,
            .container.sign-up-mode .signin-signup {
                left: 50%;
            }

            .panels-container {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 2fr 1fr;
            }

            .panel {
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                padding: 2.5rem 8%;
                grid-column: 1 / 2;
            }

            .right-panel {
                grid-row: 3 / 4;
            }

            .left-panel {
                grid-row: 1 / 2;
            }

            .image {
                width: 250px;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.6s;
            }

            .panel .content {
                padding-right: 0;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.8s;
                align-items: center;
            }

            .panel h3 {
                font-size: 1.2rem;
            }

            .panel p {
                font-size: 0.7rem;
                padding: 0.5rem 0;
            }

            .btn.transparent {
                width: 110px;
                height: 35px;
                font-size: 0.7rem;
            }

            .container:before {
                width: 1500px;
                height: 1500px;
                transform: translateX(-50%);
                left: 30%;
                bottom: 68%;
                right: initial;
                top: initial;
                transition: 2s ease-in-out;
            }

            .container.sign-up-mode:before {
                transform: translate(-50%, 100%);
                bottom: 32%;
                right: initial;
            }

            .container.sign-up-mode .left-panel .image,
            .container.sign-up-mode .left-panel .content {
                transform: translateY(-300px);
            }

            .container.sign-up-mode .right-panel .image,
            .container.sign-up-mode .right-panel .content {
                transform: translateY(0px);
            }

            .right-panel .image,
            .right-panel .content {
                transform: translateY(300px);
            }

            .container.sign-up-mode .signin-signup {
                top: 5%;
                transform: translate(-50%, 0);
            }

            .logo img {
                width: 90px;
            }

            .brand-name {
                font-size: 1.5rem;
            }

            .slogan {
                font-size: 0.9rem;
                max-width: 300px;
            }

            .form-logo img {
                width: 70px;
            }

            .form-brand-name {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 570px) {
            form {
                padding: 0 1.5rem;
            }

            .image {
                display: none;
            }
            .panel .content {
                padding: 0.5rem 1rem;
            }
            .container {
                padding: 1.5rem;
            }

            .container:before {
                bottom: 72%;
                left: 50%;
            }

            .container.sign-up-mode:before {
                bottom: 28%;
                left: 50%;
            }

            .logo-container {
                margin-bottom: 0.5rem;
            }

            .logo img {
                width: 70px;
            }

            .brand-name {
                font-size: 1.3rem;
            }

            .brand-tagline {
                font-size: 0.75rem;
            }

            .slogan {
                font-size: 0.8rem;
                max-width: 250px;
            }

            .form-logo img {
                width: 60px;
            }

            .form-brand-name {
                font-size: 1.2rem;
            }

            .form-brand-tagline {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
   <!-- Container untuk notifikasi toast -->
<div class="toast-container" id="toastContainer"></div>

<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <!-- Form Login -->
            <form action="" method="POST" class="sign-in-form">
                <div class="form-logo-container">
                    <div class="form-logo">
                        <img src="../assets/logo.png" alt="Logo Pesona Jateng" />
                    </div>
                    <div class="form-brand-tagline">
                        <i class="fas fa-sign-in-alt"></i> Masuk ke akun Anda
                    </div>
                </div>
                <h2 class="title">Login</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required />
                </div>
                <div class="input-field password-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="login-password" placeholder="Password" required />
                    <button type="button" class="toggle-password" id="toggle-login-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <input type="submit" name="login" value="Login" class="btn solid" />
                <div class="form-footer">
                    <i class="fas fa-user-plus"></i> Belum punya akun? 
                    <a href="#" id="sign-up-link">Daftar di sini</a>
                </div>
            </form>

            <!-- Form Registrasi -->
            <form action="" method="POST" class="sign-up-form">
                <div class="form-logo-container">
                    <div class="form-logo">
                        <img src="../assets/logo.png" alt="Logo Pesona Jateng" />
                    </div>
                    <div class="form-brand-tagline">
                        <i class="fas fa-user-plus"></i> Buat akun baru Anda
                    </div>
                </div>
                <h2 class="title">Registrasi</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required />
                </div>
                <div class="input-field">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required />
                </div>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-field password-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="register-password" placeholder="Password" required />
                    <button type="button" class="toggle-password" id="toggle-register-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <input type="submit" class="btn" name="register" value="Daftar" />
                <div class="form-footer">
                    <i class="fas fa-sign-in-alt"></i> Sudah punya akun? 
                    <a href="#" id="sign-in-link">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panels-container">
        <!-- Panel Kiri (untuk Login) -->
        <div class="panel left-panel">
            <div class="content">
                <div class="logo-container">
                    <div class="brand-name">PESONA JATENG</div>
                    <div class="brand-tagline">
                        <i class="fas fa-map-marked-alt"></i> Jelajahi keindahan alam & budaya Jawa Tengah
                    </div>
                </div>
                <p class="slogan">
                    <i class="fas fa-compass"></i> Temukan pengalaman wisata, event, dan kuliner terbaik — semua dalam satu platform yang mudah digunakan.
                </p>
                <button class="btn transparent" id="sign-up-btn">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </div>
            <img src="img/login.png" class="image" alt="Ilustrasi Login" />
        </div>

        <!-- Panel Kanan (untuk Registrasi) -->
        <div class="panel right-panel">
            <div class="content">
                <div class="logo-container">
                    <div class="brand-name">PESONA JATENG</div>
                    <div class="brand-tagline">
                        <i class="fas fa-map-marked-alt"></i> Jelajahi keindahan alam & budaya Jawa Tengah
                    </div>
                </div>
                <p class="slogan">
                    <i class="fas fa-users"></i> Bergabunglah dengan komunitas kami dan nikmati akses eksklusif ke destinasi wisata terbaik di Jawa Tengah.
                </p>
                <button class="btn transparent" id="sign-in-btn">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </div>
            <img src="img/regis.png" class="image" alt="Ilustrasi Registrasi" />
        </div>
    </div>
</div>

<script>
    const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const sign_in_link = document.querySelector("#sign-in-link");
    const sign_up_link = document.querySelector("#sign-up-link");
    const container = document.querySelector(".container");

    sign_up_btn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    sign_in_btn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });

    sign_up_link.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    sign_in_link.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });

    // Fungsi untuk menampilkan notifikasi toast
    function showToast(title, message, type) {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        toast.innerHTML = `
            <i class="fas ${icon} toast-icon"></i>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove setelah 5 detik
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 5000);
        
        // Close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        });
    }

    // Fungsi untuk toggle password visibility
    function setupPasswordToggle(passwordId, toggleId) {
        const passwordInput = document.getElementById(passwordId);
        const toggleButton = document.getElementById(toggleId);
        
        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Ubah ikon mata
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }
    }

    // Setup toggle password untuk login dan registrasi
    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle('login-password', 'toggle-login-password');
        setupPasswordToggle('register-password', 'toggle-register-password');
    });

    // Tampilkan notifikasi berdasarkan status PHP
    <?php if (isset($login_error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Login Gagal', '<?php echo $login_error; ?>', 'error');
            container.classList.remove("sign-up-mode");
        });
    <?php endif; ?>
    
    <?php if (isset($register_error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Registrasi Gagal', '<?php echo $register_error; ?>', 'error');
            container.classList.add("sign-up-mode");
        });
    <?php endif; ?>
    
    <?php if (isset($register_success)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Registrasi Berhasil', '<?php echo $register_success; ?>', 'success');
            setTimeout(() => {
                container.classList.remove("sign-up-mode");
            }, 1000);
        });
    <?php endif; ?>
</script>
</body>
</html>