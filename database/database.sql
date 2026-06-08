CREATE DATABASE IF NOT EXISTS `pdamZernih`;
USE `pdamZernih`;

CREATE TABLE `kategori` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kodeKategori` varchar(5),
  `namaKategori` varchar(50),
  `biayaAdm` decimal(10,0),
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `pelanggan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `noRekening` varchar(20) UNIQUE,
  `namaPelanggan` varchar(100),
  `alamat` text,
  `noHp` varchar(20),
  `kategoriId` int,
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `karyawan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nik` varchar(20) UNIQUE,
  `namaKaryawan` varchar(100),
  `jabatan` varchar(50),
  `noHp` varchar(20),
  `alamat` text,
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `karyawanId` int UNIQUE,
  `username` varchar(50) UNIQUE,
  `password` varchar(255),
  `role` varchar(20),
  `lastLogin` datetime,
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tagihan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pelangganId` int,
  `karyawanId` int,
  `periodeBulan` int,
  `periodeTahun` int,
  `tglTagih` date,
  `meterBulanLalu` int,
  `meterBulanIni` int,
  `pemakaian` int,
  `hargaPerKubikAir` decimal(10,0),
  `biayaAdm` decimal(10,0),
  `totalTagihan` decimal(12,0),
  `statusBayar` varchar(20),
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `pelanggan` ADD FOREIGN KEY (`kategoriId`) REFERENCES `kategori` (`id`);
ALTER TABLE `tagihan` ADD FOREIGN KEY (`pelangganId`) REFERENCES `pelanggan` (`id`);
ALTER TABLE `tagihan` ADD FOREIGN KEY (`karyawanId`) REFERENCES `karyawan` (`id`);
ALTER TABLE `users` ADD FOREIGN KEY (`karyawanId`) REFERENCES `karyawan` (`id`);

-- ========== DATA SAMPLE ==========

-- Kategori
INSERT INTO `kategori` (`kodeKategori`, `namaKategori`, `biayaAdm`) VALUES
('K01', 'Rumah Tangga', 5000),
('K02', 'Komersial', 10000),
('K03', 'Industri', 15000),
('K04', 'Sosial', 3000);

-- Karyawan
INSERT INTO `karyawan` (`nik`, `namaKaryawan`, `jabatan`, `noHp`, `alamat`) VALUES
('KRY001', 'Rezky Subrata', 'Admin', '081234567890', 'Jl. Merdeka No. 10'),
('KRY002', 'Budi Santoso', 'Petugas Lapangan', '081298765432', 'Jl. Sudirman No. 5'),
('KRY003', 'Siti Aminah', 'Kasir', '085678901234', 'Jl. Gatot Subroto No. 8');

-- Users (username: admin / password: admin123)
--       (username: budi  / password: admin123)
INSERT INTO `users` (`karyawanId`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$/EyZaHoWqWenTGfkeRPbn.LvfAKiQUbyCsEgfenh8Z9tV11b7kQ8G', 'admin'),
(2, 'budi', '$2y$10$/EyZaHoWqWenTGfkeRPbn.LvfAKiQUbyCsEgfenh8Z9tV11b7kQ8G', 'petugas');

-- Pelanggan
INSERT INTO `pelanggan` (`noRekening`, `namaPelanggan`, `alamat`, `noHp`, `kategoriId`) VALUES
('REK-001', 'Ahmad Hidayat', 'Jl. Anggrek No. 1', '081111222333', 1),
('REK-002', 'Dewi Lestari', 'Jl. Mawar No. 15', '082222333444', 1),
('REK-003', 'Toko Sejahtera', 'Jl. Pasar Baru No. 3', '083333444555', 2),
('REK-004', 'PT Maju Jaya', 'Jl. Industri No. 20', '084444555666', 3),
('REK-005', 'Masjid Al-Ikhlas', 'Jl. Raya No. 7', '085555666777', 4);

-- Tagihan
INSERT INTO `tagihan` (`pelangganId`, `karyawanId`, `periodeBulan`, `periodeTahun`, `tglTagih`, `meterBulanLalu`, `meterBulanIni`, `pemakaian`, `hargaPerKubikAir`, `biayaAdm`, `totalTagihan`, `statusBayar`) VALUES
(1, 1, 5, 2026, '2026-05-10', 100, 120, 20, 3500, 5000, 75000, 'Lunas'),
(2, 1, 5, 2026, '2026-05-10', 50, 75, 25, 3500, 5000, 92500, 'Lunas'),
(3, 2, 5, 2026, '2026-05-12', 200, 280, 80, 3500, 10000, 290000, 'Belum Bayar'),
(4, 2, 5, 2026, '2026-05-12', 500, 650, 150, 3500, 15000, 540000, 'Belum Bayar'),
(5, 1, 5, 2026, '2026-05-10', 30, 40, 10, 3500, 3000, 38000, 'Lunas'),
(1, 1, 6, 2026, '2026-06-10', 120, 145, 25, 3500, 5000, 92500, 'Belum Bayar'),
(2, 1, 6, 2026, '2026-06-10', 75, 95, 20, 3500, 5000, 75000, 'Belum Bayar');
