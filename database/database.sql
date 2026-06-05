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
