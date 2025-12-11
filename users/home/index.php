<?php
session_start();
require_once '../../config/koneksi.php'; // Gunakan koneksi dari sini
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesona Jateng</title>

  <link rel="stylesheet" href="../css/stylenavbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../layouts/footer.css">

  <style>
    /* CSS kamu tidak saya ubah, tetap sama */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #fff;
      color: #333;
    }

    main {
      margin-top: 90px;
    }

    .hero {
      position: relative;
      text-align: center;
      overflow: hidden;
    }

    .hero img {
      width: 100%;
      height: 450px;
      object-fit: cover;
      filter: brightness(70%);
    }

    .hero-text {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }

    .hero-text h2 {
      font-size: 2.5rem;
      margin-bottom: 15px;
    }

    .hero-text p {
      margin-top: 10px;
      font-size: 1.2rem;
    }

    .hero-text button {
      margin-top: 20px;
      padding: 12px 25px;
      background-color: #007BFF;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: 0.3s;
    }

    .hero-text button:hover {
      background-color: #0056b3;
      transform: translateY(-3px);
    }

    /* POPULER */
    .popular {
      text-align: center;
      padding: 70px 20px;
      background-color: #f8f9fa;
    }

    .popular h2 {
      margin-bottom: 40px;
      font-size: 2.2rem;
      position: relative;
      display: inline-block;
    }

    .popular h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background-color: #007BFF;
    }

    .cards {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
    }

    .card {
      background: white;
      width: 300px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      text-align: left;
      display: flex;
      flex-direction: column;
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: 0.5s;
    }

    .card:hover img {
      transform: scale(1.05);
    }

    .card-content {
      flex: 1;
    }

    .card h3 {
      padding: 15px;
      font-size: 1.3rem;
      min-height: 60px;
    }

    .card .rating {
      padding: 0 15px;
      color: #FFC107;
      margin-bottom: 10px;
    }

    .card p {
      padding: 0 15px 15px;
      color: #555;
      line-height: 1.5;
      min-height: 90px;
    }

    .card button {
      margin: 10px 15px 15px;
      padding: 8px 16px;
      background: #007BFF;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .card button:hover {
      background: #0056b3;
    }

    /* KATEGORI */
    .kategori {
      text-align: center;
      padding: 70px 20px;
    }

    .kategori h2 {
      font-size: 2.2rem;
      margin-bottom: 40px;
      position: relative;
      display: inline-block;
    }

    .kategori h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background-color: #007BFF;
    }

    .kategori-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 18px;
      max-width: 1000px;
      margin: auto;
    }

    .kategori-item {
      background: white;
      padding: 14px 25px;
      border: 2px solid #007BFF;
      color: #007BFF;
      border-radius: 30px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .kategori-item:hover {
      background: #007BFF;
      color: white;
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

  </style>
</head>

<body>

  <?php include '../layouts/navbar/index.php'; ?>

  <main>

    <!-- HERO -->
    <section class="hero">
      <img src="../assets/img/banner.jpg">
      <div class="hero-text">
        <h2>Jelajahi Keindahan<br>Jawa Tengah</h2>
        <p>Eksplorasi pesona wisata yang memukau</p>
        <button onclick="scrollToSection('popular')">Jelajahi Sekarang</button>
      </div>
    </section>

    <!-- DESTINASI POPULER -->
    <section id="popular" class="popular">
      <h2>Destinasi Populer</h2>

      <div class="cards">
      <?php
        $sql = "SELECT * FROM wisata ORDER BY rating DESC LIMIT 3";
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($result)) {
          echo '
          <div class="card">
            <img src="../../admin/pages/galeri/img/'.$row['gambar'].'">
            <div class="card-content">
              <h3>'.$row['nama_wisata'].'</h3>

              <div class="rating">';
                for ($i = 1; $i <= 5; $i++) {
                  echo ($i <= $row['rating'])
                    ? '<i class="fas fa-star"></i>'
                    : '<i class="far fa-star"></i>';
                }
          echo ' '.$row['rating'].'/5</div>

              <p>'.$row['deskripsi'].'</p>
            </div>

            <button onclick="showDetail('.$row['id_wisata'].')">Detail</button>
          </div>';
        }
      ?>
      </div>

    </section>

    <!-- KATEGORI -->
    <section class="kategori">
      <h2>Kategori Wisata</h2>

      <div class="kategori-container">
      <?php
        $sql = "SELECT * FROM kategori";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '
              <a href="../wisata/index.php?id_kategori='.$row['id_kategori'].'">
                <div class="kategori-item">'.$row['nama_kategori'].'</div>
              </a>';
        }
      ?>
      </div>
    </section>

  </main>

  <?php include '../layouts/footer.php'; ?>

  <script src="../js/navbar.js"></script>

  <script>
    function scrollToSection(id) {
      document.getElementById(id).scrollIntoView({ behavior: "smooth" });
    }

    function showDetail(id) {
      window.location.href = "detail_wisata.php?id=" + id;
    }
  </script>

</body>
</html>
