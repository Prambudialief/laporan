-- MySQL dump 10.13  Distrib 8.0.37, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: laporan
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `laporan`
--

DROP TABLE IF EXISTS `laporan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laporan` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `user_id` int NOT NULL,
  `judul_laporan` varchar(255) NOT NULL COMMENT 'Judul Laporan',
  `nama_aplikasi` varchar(100) NOT NULL COMMENT 'Nama Aplikasi',
  `kantor_sar` varchar(150) NOT NULL COMMENT 'Kantor SAR / Unit Kerja',
  `nama_pelapor` varchar(150) NOT NULL COMMENT 'Nama Pelapor',
  `media_pelaporan` enum('Whatsapp','Email','Telepon') NOT NULL COMMENT 'Media Pelaporan',
  `waktu_pelaporan` datetime NOT NULL COMMENT 'Waktu Pelaporan',
  `nama_petugas` varchar(150) NOT NULL COMMENT 'Nama Petugas',
  `tanggal_pemutakhiran` datetime NOT NULL COMMENT 'Tanggal Pemutakhiran',
  `deskripsi_permasalahan` text NOT NULL COMMENT 'Deskripsi Permasalahan',
  `gambar_deskripsi` varchar(255) DEFAULT NULL COMMENT 'Lampiran Deskripsi (opsional)',
  `solusi_permasalahan` text NOT NULL COMMENT 'Solusi Permasalahan',
  `gambar_solusi` varchar(255) DEFAULT NULL COMMENT 'Lampiran Solusi (opsional)',
  `tanggal_penyelesaian` datetime NOT NULL COMMENT 'Tanggal Penyelesaian',
  `status_laporan` enum('Selesai','Proses','Pending') NOT NULL COMMENT 'Status Laporan',
  `durasi` varchar(50) DEFAULT NULL COMMENT 'Durasi Penyelesaian',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu laporan dibuat',
  `jenis_permasalahan` varchar(255) NOT NULL,
  `unit_kerja` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_laporan_user` (`user_id`),
  CONSTRAINT `fk_laporan_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabel Laporan Sistem';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `nama` varchar(255) NOT NULL,
  `nomer_hp` varchar(20) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Password di-hash (bcrypt/md5/sha256)',
  `role` enum('admin','user') NOT NULL DEFAULT 'user' COMMENT 'Role user',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan akun',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabel User dan Admin';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'laporan'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-04 12:57:42
