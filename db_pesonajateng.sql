-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 03:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pesonajateng`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_wisata`
--

CREATE TABLE `detail_wisata` (
  `id_detail` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `full_deskripsi` text NOT NULL,
  `jam_operasional` varchar(100) DEFAULT NULL,
  `durasi_wisata` varchar(100) DEFAULT NULL,
  `waktu_terbaik` varchar(100) DEFAULT NULL,
  `cuaca_saat_ini` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_wisata`
--

INSERT INTO `detail_wisata` (`id_detail`, `id_wisata`, `full_deskripsi`, `jam_operasional`, `durasi_wisata`, `waktu_terbaik`, `cuaca_saat_ini`) VALUES
(1, 1, 'Candi Borobudur menawarkan pengalaman sejarah dan spiritual yang mendalam. Pengunjung dapat menyusuri relief-relief yang menggambarkan kisah Buddha dan kehidupan masa lalu. Di bagian puncak, wisatawan bisa menikmati panorama Gunung Merapi, Merbabu, dan perbukitan Menoreh. Banyak pengunjung datang saat sunrise karena cahaya pagi memberikan nuansa indah dan menenangkan.', '06.00 - 17.00 WIB', '2-3 jam', 'Pagi hari (sekitar pukul 06.00 - 09.00)', 'Cerah berawan'),
(2, 2, 'Dieng Plateau terkenal dengan kawah aktif, Telaga Warna, dan Candi Arjuna yang penuh sejarah. Udara dingin khas dataran tinggi memberikan suasana berbeda dari daerah lain di Jawa. Selain panorama pegunungan, Dieng juga memiliki fenomena langka seperti embun upas. Kawasan ini cocok untuk pecinta petualangan dan fotografi alam.', '07.00 - 17.00 WIB', '3-5 jam', 'Juli - September', 'Cerah'),
(3, 3, 'Baturaden terletak di lereng Gunung Slamet dan menawarkan suasana sejuk khas pegunungan. Pengunjung dapat menikmati pemandian air panas alami, air terjun, serta taman wisata keluarga yang luas. Area ini juga memiliki banyak spot foto menarik dengan panorama hijau. Cocok untuk liburan santai bersama keluarga maupun rekreasi alam.', '08.00 - 17.00 WIB', '2-4 jam', 'April - Oktober', 'Mendung ringan'),
(4, 4, 'Lawang Sewu merupakan bangunan kolonial Belanda yang memiliki banyak pintu dan jendela besar. Kini bangunan ini telah direstorasi dan difungsikan sebagai museum sejarah. Wisatawan dapat menyusuri ruang-ruang klasik dengan arsitektur khas Eropa. Lokasinya yang strategis membuatnya menjadi ikon wisata Kota Semarang.', '09.00 - 21.00 WIB', '1-2 jam', 'Sepanjang tahun', 'Cerah'),
(5, 5, 'Pantai Kartini menawarkan hamparan laut utara Jawa dengan angin sepoi-sepoi yang menyegarkan. Terdapat area bermain keluarga serta akuarium kura-kura raksasa sebagai ikon wisata. Pengunjung bisa menikmati sunset yang indah sambil bersantai di tepi pantai. Tempat ini cocok untuk rekreasi keluarga dan liburan ringan.', '06.00 - 18.00 WIB', '2-3 jam', 'Juni - Agustus', 'Cerah berawan'),
(6, 6, 'Candi Prambanan merupakan kompleks candi Hindu terbesar dengan arsitektur yang menjulang tinggi. Relief-reliefnya menggambarkan kisah Ramayana yang menarik untuk dipelajari. Cahaya sore membuat bangunan tampak lebih megah. Banyak wisatawan datang untuk menyaksikan Sendratari Ramayana yang dipentaskan di area terbuka.', '06.00 - 17.00 WIB', '2-3 jam', 'Mei - Oktober', 'Cerah'),
(7, 7, 'Kampung Batik Laweyan menawarkan suasana klasik dengan rumah-rumah berarsitektur Jawa dan Belanda. Pengunjung dapat melihat proses pembuatan batik secara langsung. Banyak toko kecil menjual batik tradisional hingga modern. Tempat ini cocok untuk wisata budaya dan belanja batik.', '08.00 - 17.00 WIB', '2-10jam', 'Sepanjang tahun', 'Cerah'),
(8, 8, 'Karimunjawa adalah gugusan pulau tropis dengan laut jernih dan terumbu karang yang masih terjaga. Aktivitas seperti snorkeling, diving, dan island hopping menjadi daya tarik utama. Suasananya tenang dan jauh dari keramaian kota. Cocok untuk wisatawan yang ingin relaksasi dan eksplorasi bawah laut.', '07.00 - 17.00 WIB', '1 hari penuh', 'Mei - September', 'Cerah'),
(9, 9, 'Goa Jatijajar merupakan goa kapur alami dengan stalaktit dan stalagmit yang terbentuk selama ribuan tahun. Di dalamnya terdapat diorama yang menceritakan legenda Kamandaka. Pencahayaan modern membuat suasana goa lebih menarik. Cocok untuk wisata edukasi dan petualangan ringan.', '08.00 - 16.00 WIB', '2 jam', 'Juli - September', 'Lembab');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas_wisata`
--

CREATE TABLE `fasilitas_wisata` (
  `id_fasilitas` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `tersedia` enum('YA','TIDAK') DEFAULT 'YA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fasilitas_wisata`
--

INSERT INTO `fasilitas_wisata` (`id_fasilitas`, `id_wisata`, `nama_fasilitas`, `tersedia`) VALUES
(1, 1, 'Area Parkir Luas', 'YA'),
(2, 1, 'Toilet Umum', 'YA'),
(3, 1, 'Pusat Oleh-oleh', 'YA'),
(4, 1, 'Pemandu Wisata', 'YA'),
(5, 1, 'Area Makan', 'YA'),
(6, 1, 'Spot Foto', 'YA'),
(7, 2, 'Area Parkir', 'YA'),
(8, 2, 'Toilet Umum', 'YA'),
(9, 2, 'Tempat Makan', 'YA'),
(10, 2, 'Penginapan', 'YA'),
(11, 2, 'Pemandu Wisata', 'YA'),
(12, 3, 'Kolam Air Panas', 'YA'),
(13, 3, 'Toilet Umum', 'YA'),
(14, 3, 'Gazebo dan Taman', 'YA'),
(15, 3, 'Tempat Ibadah', 'YA'),
(16, 4, 'Pusat Informasi', 'YA'),
(17, 4, 'Area Parkir', 'YA'),
(18, 4, 'Toilet Umum', 'YA'),
(19, 4, 'Pemandu Wisata', 'YA'),
(20, 5, 'Area Bermain Anak', 'YA'),
(21, 5, 'Warung Makan', 'YA'),
(22, 5, 'Toilet Umum', 'YA'),
(23, 5, 'Gazebo', 'YA'),
(24, 6, 'Area Parkir Luas', 'YA'),
(25, 6, 'Toilet Umum', 'YA'),
(26, 6, 'Pusat Informasi', 'YA'),
(27, 6, 'Tempat Ibadah', 'YA'),
(28, 7, 'Area Parkir', 'YA'),
(29, 7, 'Galeri Batik', 'YA'),
(30, 7, 'Pemandu Wisata', 'YA'),
(31, 7, 'Toilet Umum', 'YA'),
(32, 8, 'Penginapan', 'YA'),
(33, 8, 'Tempat Makan', 'YA'),
(34, 8, 'Area Diving', 'YA'),
(35, 8, 'Transport Laut', 'YA'),
(36, 9, 'Area Parkir', 'YA'),
(37, 9, 'Toilet Umum', 'YA'),
(38, 9, 'Tempat Ibadah', 'YA'),
(39, 9, 'Warung Makan', 'YA');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `id_favorite` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `tanggal_favorite` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`id_favorite`, `id_user`, `id_wisata`, `tanggal_favorite`) VALUES
(9, 6, 9, '2025-11-12 14:05:42'),
(10, 6, 8, '2025-11-12 14:05:44'),
(11, 1, 9, '2025-11-13 14:06:03'),
(35, 7, 9, '2025-11-19 12:30:04'),
(36, 7, 8, '2025-11-19 12:30:06'),
(37, 5, 5, '2025-12-10 22:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `galeri_wisata`
--

CREATE TABLE `galeri_wisata` (
  `id_galeri` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri_wisata`
--

INSERT INTO `galeri_wisata` (`id_galeri`, `id_wisata`, `foto`) VALUES
(1, 1, 'borobudur1.jpg'),
(2, 1, 'borobudur2.jpg'),
(3, 1, 'borobudur3.jpg'),
(4, 1, 'borobudur_sunrise.jpg'),
(5, 2, 'dieng1.jpg'),
(6, 2, 'dieng2.jpg'),
(7, 3, 'baturaden1.jpg'),
(8, 3, 'baturaden2.jpg'),
(9, 4, 'lawangsewu1.jpg'),
(10, 4, 'lawangsewu2.jpg'),
(11, 5, 'pantai_kartini1.jpg'),
(12, 6, 'prambanan1.jpg'),
(13, 6, 'prambanan2.jpg'),
(14, 7, 'laweyan1.jpg'),
(15, 7, 'laweyan2.jpg'),
(16, 8, 'karimunjawa1.jpg'),
(17, 8, 'karimunjawa2.jpg'),
(18, 9, 'jatijajar1.jpg'),
(19, 9, 'jatijajar2.jpg'),
(20, 3, 'baturaden3.jpg'),
(21, 3, 'baturaden4.jpg'),
(22, 3, 'baturaden5.jpg'),
(23, 3, 'baturaden6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(3, 'Budaya'),
(1, 'Gunung'),
(2, 'Pantai');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `aksi` enum('TAMBAH','HAPUS') NOT NULL,
  `id_wisata` int(11) DEFAULT NULL,
  `nama_wisata` varchar(255) DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `aksi`, `id_wisata`, `nama_wisata`, `waktu`, `keterangan`) VALUES
(1, 'TAMBAH', 1, 'Candi Borobudur', '2025-10-15 13:32:31', 'Wisata Candi Borobudur telah ditambahkan.'),
(2, 'TAMBAH', 2, 'Dieng Plateau', '2025-11-12 08:18:39', 'Wisata Dieng Plateau telah ditambahkan.'),
(3, 'TAMBAH', 3, 'Baturaden', '2025-11-12 08:18:39', 'Wisata Baturaden telah ditambahkan.'),
(4, 'TAMBAH', 4, 'Lawang Sewu', '2025-11-12 08:18:39', 'Wisata Lawang Sewu telah ditambahkan.'),
(5, 'TAMBAH', 5, 'Pantai Kartini', '2025-11-12 08:18:39', 'Wisata Pantai Kartini telah ditambahkan.'),
(6, 'TAMBAH', 6, 'Candi Prambanan', '2025-11-12 08:18:39', 'Wisata Candi Prambanan telah ditambahkan.'),
(7, 'TAMBAH', 7, 'Kampung Batik Laweyan', '2025-11-12 08:18:39', 'Wisata Kampung Batik Laweyan telah ditambahkan.'),
(8, 'TAMBAH', 8, 'Karimunjawa', '2025-11-12 08:18:39', 'Wisata Karimunjawa telah ditambahkan.'),
(9, 'TAMBAH', 9, 'Goa Jatijajar', '2025-11-12 08:18:39', 'Wisata Goa Jatijajar telah ditambahkan.'),
(10, 'TAMBAH', 10, 'SAMSUNK', '2025-12-10 22:58:58', 'Wisata SAMSUNK telah ditambahkan.'),
(11, 'HAPUS', 10, 'SAMSUNK', '2025-12-11 08:56:11', 'Wisata SAMSUNK telah dihapus.');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata`
--

CREATE TABLE `tiket_wisata` (
  `id_tiket` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `harga_anak` decimal(10,2) DEFAULT NULL,
  `harga_dewasa` decimal(10,2) DEFAULT NULL,
  `harga_mancanegara` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata`
--

INSERT INTO `tiket_wisata` (`id_tiket`, `id_wisata`, `harga_anak`, `harga_dewasa`, `harga_mancanegara`) VALUES
(1, 1, 25000.00, 50000.00, 150000.00),
(2, 2, 15000.00, 25000.00, 100000.00),
(3, 3, 10000.00, 20000.00, 80000.00),
(4, 4, 20000.00, 30000.00, 75000.00),
(5, 5, 10000.00, 15000.00, 50000.00),
(6, 6, 25000.00, 50000.00, 300000.00),
(7, 7, 0.00, 10000.00, 20000.00),
(8, 8, 50000.00, 100000.00, 350000.00),
(9, 9, 10000.00, 20000.00, 75000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `role`) VALUES
(1, 'admin123', '202cb962ac59075b964b07152d234b70', 'CEO AYDES', 'ayuoktaviani@gmail.com', 'admin'),
(2, 'user', '202cb962ac59075b964b07152d234b70', 'AYDES', 'ayu@gmail.com', 'user'),
(3, 'ayu', '$2y$10$cr71FN9FchefyjJeJPE86ub2qKe9rOVuThv6znTEHfuqUi.oE8GM.', 'aydes', 'geertzfy@gmail.com', 'user'),
(4, 'april', '202cb962ac59075b964b07152d234b70', 'kanjeng april', 'apriliyul@gmail.com', 'user'),
(5, 'adminjawa', '202cb962ac59075b964b07152d234b70', 'adminjawa', 'admin@gmail.com', 'user'),
(6, 'doraemon', '202cb962ac59075b964b07152d234b70', 'dora', 'emon@gmail.com', 'user'),
(7, 'nabil', '827ccb0eea8a706c4c34a16891f84e7b', 'nabillllll', 'nabil@gmail.com', 'user'),
(8, 'fatih', '202cb962ac59075b964b07152d234b70', 'patihah', 'oktavianiasja@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `id_wisata` int(11) NOT NULL,
  `nama_wisata` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_ditambahkan` datetime NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`id_wisata`, `nama_wisata`, `lokasi`, `deskripsi`, `gambar`, `tanggal_ditambahkan`, `id_kategori`, `rating`) VALUES
(1, 'Candi Borobudur', 'Magelang, Jawa Tengah', 'Candi Buddha terbesar di dunia yang menjadi warisan budaya UNESCO, dikelilingi panorama perbukitan yang indah.', 'borobudur.jpg', '2025-10-15 13:32:31', 1, 4.8),
(2, 'Dieng Plateau', 'Banjarnegara, Jawa Tengah', 'Dataran tinggi berhawa sejuk dengan kawah aktif, telaga berwarna, dan kompleks candi kuno.', 'dieng.jpg', '2025-11-12 08:18:39', 1, 4.8),
(3, 'Baturaden', 'Banyumas, Jawa Tengah', 'Wisata alam di lereng Gunung Slamet dengan pemandian air panas dan suasana pegunungan.', 'baturaden.jpg', '2025-11-12 08:18:39', 1, 4.7),
(4, 'Lawang Sewu', 'Semarang, Jawa Tengah', 'Bangunan kolonial ikonik di Semarang yang kini menjadi museum sejarah populer.', 'lawangsewu.jpg', '2025-11-12 08:18:39', 3, 4.6),
(5, 'Pantai Kartini', 'Jepara, Jawa Tengah', 'Pantai santai dengan pemandangan laut utara Jawa dan ikon Kura-Kura Ocean Park.', 'pantai_kartini.jpg', '2025-11-12 08:18:39', 2, 4.4),
(6, 'Candi Prambanan', 'Klaten, Jawa Tengah', 'Kompleks candi Hindu terbesar di Indonesia dengan arsitektur megah dan legenda Roro Jonggrang.', 'prambanan.jpg', '2025-11-12 08:18:39', 3, 4.9),
(7, 'Kampung Batik Laweyan', 'Surakarta, Jawa Tengah', 'Kawasan bersejarah di Solo yang menjadi pusat industri batik tradisional.', 'laweyan.jpg', '2025-11-12 08:18:39', 3, 4.5),
(8, 'Karimunjawa', 'Jepara, Jawa Tengah', 'Gugusan pulau tropis dengan pantai putih dan destinasi snorkeling terbaik di Jawa Tengah.', 'karimunjawa.jpg', '2025-11-12 08:18:39', 2, 4.9),
(9, 'Goa Jatijajar', 'Kebumen, Jawa Tengah', 'Goa kapur alami dengan legenda Kamandaka dan pencahayaan modern.', 'jatijajar.jpg', '2025-11-12 08:18:39', 1, 4.3);

--
-- Triggers `wisata`
--
DELIMITER $$
CREATE TRIGGER `after_delete_wisata` AFTER DELETE ON `wisata` FOR EACH ROW BEGIN
    INSERT INTO log_aktivitas (aksi, id_wisata, nama_wisata, keterangan)
    VALUES ('HAPUS', OLD.id_wisata, OLD.nama_wisata, CONCAT('Wisata ', OLD.nama_wisata, ' telah dihapus.'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_wisata` AFTER INSERT ON `wisata` FOR EACH ROW BEGIN
    INSERT INTO log_aktivitas (aksi, id_wisata, nama_wisata, keterangan)
    VALUES ('TAMBAH', NEW.id_wisata, NEW.nama_wisata, CONCAT('Wisata ', NEW.nama_wisata, ' telah ditambahkan.'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_wisata`
--
ALTER TABLE `detail_wisata`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_wisata` (`id_wisata`);

--
-- Indexes for table `fasilitas_wisata`
--
ALTER TABLE `fasilitas_wisata`
  ADD PRIMARY KEY (`id_fasilitas`),
  ADD KEY `id_wisata` (`id_wisata`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`id_favorite`),
  ADD KEY `fk_favorite_wisata` (`id_wisata`),
  ADD KEY `fk_favorite_user` (`id_user`);

--
-- Indexes for table `galeri_wisata`
--
ALTER TABLE `galeri_wisata`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_wisata` (`id_wisata`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_wisata_log` (`id_wisata`);

--
-- Indexes for table `tiket_wisata`
--
ALTER TABLE `tiket_wisata`
  ADD PRIMARY KEY (`id_tiket`),
  ADD KEY `id_wisata` (`id_wisata`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `unique_user_username` (`username`);

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id_wisata`),
  ADD KEY `fk_wisata_kategori` (`id_kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_wisata`
--
ALTER TABLE `detail_wisata`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fasilitas_wisata`
--
ALTER TABLE `fasilitas_wisata`
  MODIFY `id_fasilitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `id_favorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `galeri_wisata`
--
ALTER TABLE `galeri_wisata`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tiket_wisata`
--
ALTER TABLE `tiket_wisata`
  MODIFY `id_tiket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id_wisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_wisata`
--
ALTER TABLE `detail_wisata`
  ADD CONSTRAINT `detail_wisata_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `fasilitas_wisata`
--
ALTER TABLE `fasilitas_wisata`
  ADD CONSTRAINT `fasilitas_wisata_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `fk_favorite_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorite_wisata` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `galeri_wisata`
--
ALTER TABLE `galeri_wisata`
  ADD CONSTRAINT `galeri_wisata_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_wisata_log` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`);

--
-- Constraints for table `tiket_wisata`
--
ALTER TABLE `tiket_wisata`
  ADD CONSTRAINT `tiket_wisata_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `wisata`
--
ALTER TABLE `wisata`
  ADD CONSTRAINT `fk_wisata_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
