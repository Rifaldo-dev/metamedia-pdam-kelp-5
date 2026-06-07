CREATE DATABASE IF NOT EXISTS `pdamZernih`;
USE `pdamZernih`;

CREATE TABLE `kategori` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kodeKategori` varchar(5),
  `namaKategori` varchar(50),
  `biayaAdm` decimal,
  `createdAt` timestamp,
  `updatedAt` timestamp
);

CREATE TABLE `pelanggan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `noRekening` varchar(20) UNIQUE,
  `namaPelanggan` varchar(100),
  `alamat` text,
  `noHp` varchar(20),
  `kategoriId` int,
  `createdAt` timestamp,
  `updatedAt` timestamp
);

CREATE TABLE `karyawan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nik` varchar(20) UNIQUE,
  `namaKaryawan` varchar(100),
  `jabatan` varchar(50),
  `noHp` varchar(20),
  `alamat` text,
  `createdAt` timestamp,
  `updatedAt` timestamp
);

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `karyawanId` int UNIQUE,
  `username` varchar(50) UNIQUE,
  `password` varchar(255),
  `role` varchar(20),
  `lastLogin` datetime,
  `createdAt` timestamp,
  `updatedAt` timestamp
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
  `hargaPerKubikAir` decimal,
  `biayaAdm` decimal,
  `totalTagihan` decimal,
  `statusBayar` varchar(20),
  `createdAt` timestamp,
  `updatedAt` timestamp
);

ALTER TABLE `pelanggan` ADD FOREIGN KEY (`kategoriId`) REFERENCES `kategori` (`id`);

ALTER TABLE `tagihan` ADD FOREIGN KEY (`pelangganId`) REFERENCES `pelanggan` (`id`);

ALTER TABLE `tagihan` ADD FOREIGN KEY (`karyawanId`) REFERENCES `karyawan` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`karyawanId`) REFERENCES `karyawan` (`id`);

-- ========== DATA SAMPLE ==========

-- Kategori
INSERT INTO `kategori` (`kodeKategori`, `namaKategori`, `biayaAdm`, `createdAt`, `updatedAt`) VALUES
('K01', 'Rumah Tangga', 5000, NOW(), NOW()),
('K02', 'Komersial', 10000, NOW(), NOW()),
('K03', 'Industri', 15000, NOW(), NOW()),
('K04', 'Sosial', 3000, NOW(), NOW());

-- Karyawan
INSERT INTO `karyawan` (`nik`, `namaKaryawan`, `jabatan`, `noHp`, `alamat`, `createdAt`, `updatedAt`) VALUES
('KRY001', 'Rezky Subrata', 'Admin', '081234567890', 'Jl. Merdeka No. 10', NOW(), NOW()),
('KRY002', 'Budi Santoso', 'Petugas Lapangan', '081298765432', 'Jl. Sudirman No. 5', NOW(), NOW()),
('KRY003', 'Siti Aminah', 'Kasir', '085678901234', 'Jl. Gatot Subroto No. 8', NOW(), NOW());

-- Users (password: admin123)
INSERT INTO `users` (`karyawanId`, `username`, `password`, `role`, `createdAt`, `updatedAt`) VALUES
(1, 'admin', '$2y$10$ZPeCyeeoYIXX76aSCgLeLOQoK/qOuvWBkWD5aZ8xlxy6o6UT9bsb', 'admin', NOW(), NOW()),
(2, 'budi', '$2y$10$ZPeCyeeoYIXX76aSCgLeLOQoK/qOuvWBkWD5aZ8xlxy6o6UT9bsb', 'petugas', NOW(), NOW());

-- Pelanggan
INSERT INTO `pelanggan` (`noRekening`, `namaPelanggan`, `alamat`, `noHp`, `kategoriId`, `createdAt`, `updatedAt`) VALUES
('REK-001', 'Ahmad Hidayat', 'Jl. Anggrek No. 1', '081111222333', 1, NOW(), NOW()),
('REK-002', 'Dewi Lestari', 'Jl. Mawar No. 15', '082222333444', 1, NOW(), NOW()),
('REK-003', 'Toko Sejahtera', 'Jl. Pasar Baru No. 3', '083333444555', 2, NOW(), NOW()),
('REK-004', 'PT Maju Jaya', 'Jl. Industri No. 20', '084444555666', 3, NOW(), NOW()),
('REK-005', 'Masjid Al-Ikhlas', 'Jl. Raya No. 7', '085555666777', 4, NOW(), NOW());

-- Tagihan
INSERT INTO `tagihan` (`pelangganId`, `karyawanId`, `periodeBulan`, `periodeTahun`, `tglTagih`, `meterBulanLalu`, `meterBulanIni`, `pemakaian`, `hargaPerKubikAir`, `biayaAdm`, `totalTagihan`, `statusBayar`, `createdAt`, `updatedAt`) VALUES
(1, 1, 5, 2026, '2026-05-10', 100, 120, 20, 3500, 5000, 75000, 'Lunas', NOW(), NOW()),
(2, 1, 5, 2026, '2026-05-10', 50, 75, 25, 3500, 5000, 92500, 'Lunas', NOW(), NOW()),
(3, 2, 5, 2026, '2026-05-12', 200, 280, 80, 3500, 10000, 290000, 'Belum Bayar', NOW(), NOW()),
(4, 2, 5, 2026, '2026-05-12', 500, 650, 150, 3500, 15000, 540000, 'Belum Bayar', NOW(), NOW()),
(5, 1, 5, 2026, '2026-05-10', 30, 40, 10, 3500, 3000, 38000, 'Lunas', NOW(), NOW()),
(1, 1, 6, 2026, '2026-06-10', 120, 145, 25, 3500, 5000, 92500, 'Belum Bayar', NOW(), NOW()),
(2, 1, 6, 2026, '2026-06-10', 75, 95, 20, 3500, 5000, 75000, 'Belum Bayar', NOW(), NOW());
