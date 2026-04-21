-- MySQL dump 10.13  Distrib 8.0.33, for macos13 (arm64)
--
-- Host: 127.0.0.1    Database: dbICA
-- ------------------------------------------------------
-- Server version	8.0.33

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
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned DEFAULT NULL,
  `jadwal_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `tanggal_absen` date NOT NULL,
  `pertemuan_ke` smallint unsigned DEFAULT NULL,
  `status_kehadiran` enum('hadir','sakit','izin','alfa','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_kelas_id_foreign` (`kelas_id`),
  KEY `absensi_guru_id_foreign` (`guru_id`),
  KEY `absensi_jadwal_id_foreign` (`jadwal_id`),
  KEY `absensi_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  KEY `absensi_siswa_id_tanggal_absen_index` (`siswa_id`,`tanggal_absen`),
  CONSTRAINT `absensi_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absensi_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absensi_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
INSERT INTO `absensi` VALUES (1,1,1,1,NULL,1,'2026-04-15',1,'izin','Izin untuk keperluan keluarga','2026-04-15 23:58:34','2026-04-16 01:08:27'),(2,2,2,2,NULL,1,'2026-04-14',2,'hadir','Hadir tepat waktu','2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,3,2,2,NULL,1,'2026-04-13',3,'hadir','Hadir tepat waktu','2026-04-16 01:08:27','2026-04-16 01:08:27'),(4,4,3,3,NULL,1,'2026-04-12',4,'hadir','Hadir tepat waktu','2026-04-16 01:08:27','2026-04-16 01:08:27'),(5,5,3,3,NULL,1,'2026-04-11',5,'izin','Izin untuk keperluan keluarga','2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guru`
--

DROP TABLE IF EXISTS `guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_guru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guru_user_id_unique` (`user_id`),
  UNIQUE KEY `guru_nip_unique` (`nip`),
  CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--

LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
INSERT INTO `guru` VALUES (1,2,'198812312024011001','Guru Demo','Laki-laki','1988-12-31','Jl. Pendidikan No. 1','081234567891',NULL,'2026-04-15 01:46:13','2026-04-15 01:46:13'),(2,6,'198901152024011002','Guru Matematika','Perempuan','1989-01-15','Jl. Pendidikan No. 2','081234567893',NULL,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,7,'199002202024011003','Guru Bahasa','Perempuan','1990-02-20','Jl. Pendidikan No. 3','081234567894',NULL,'2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `hari` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_kelas_id_foreign` (`kelas_id`),
  KEY `jadwal_guru_id_foreign` (`guru_id`),
  KEY `jadwal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `jadwal_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `jadwal_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (1,1,1,1,1,'Kamis','07:30:00','08:50:00','R-101',1,'2026-04-16 01:18:15','2026-04-16 01:45:51'),(2,2,2,4,1,'Kamis','09:00:00','10:20:00','R-102',1,'2026-04-16 01:18:15','2026-04-16 01:45:51'),(3,3,3,2,1,'Kamis','10:30:00','11:50:00','R-103',1,'2026-04-16 01:18:15','2026-04-16 01:45:51');
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_penilaian`
--

DROP TABLE IF EXISTS `jenis_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '0.00',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_penilaian`
--

LOCK TABLES `jenis_penilaian` WRITE;
/*!40000 ALTER TABLE `jenis_penilaian` DISABLE KEYS */;
INSERT INTO `jenis_penilaian` VALUES (1,'Tugas',20.00,'Penilaian tugas harian','2026-04-15 01:51:44','2026-04-15 01:51:44'),(2,'UTS',30.00,'Penilaian tengah semester','2026-04-15 01:51:44','2026-04-15 01:51:44'),(3,'UAS',50.00,'Penilaian akhir semester','2026-04-15 01:51:44','2026-04-15 01:51:44');
/*!40000 ALTER TABLE `jenis_penilaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kapasitas` smallint unsigned DEFAULT NULL,
  `wali_guru_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_wali_guru_id_foreign` (`wali_guru_id`),
  KEY `kelas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_wali_guru_id_foreign` FOREIGN KEY (`wali_guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (1,'X IPA 1','X','IPA',36,1,1,'2026-04-15 01:46:13','2026-04-15 01:46:13'),(2,'X IPA 2','X','IPA',36,2,1,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,'X IPS 1','X','IPS',36,3,1,'2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `modul` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_user_id_foreign` (`user_id`),
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_pelajaran`
--

DROP TABLE IF EXISTS `mata_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_pelajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelompok_mapel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mingguan` smallint unsigned DEFAULT NULL,
  `kkm` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_pelajaran_kode_mapel_unique` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_pelajaran`
--

LOCK TABLES `mata_pelajaran` WRITE;
/*!40000 ALTER TABLE `mata_pelajaran` DISABLE KEYS */;
INSERT INTO `mata_pelajaran` VALUES (1,'MTK-01','Matematika','Wajib',5,75,'2026-04-15 01:46:13','2026-04-15 01:46:13'),(2,'BIN-01','Bahasa Indonesia','Wajib',4,75,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,'BING-01','Bahasa Inggris','Wajib',4,75,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(4,'IPA-01','IPA','Wajib',4,75,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(5,'IPS-01','IPS','Wajib',4,75,'2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `mata_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_15_000001_add_school_fields_to_users_table',1),(5,'2026_04_15_000002_create_school_master_tables',1),(6,'2026_04_15_000003_create_school_academic_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned DEFAULT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `jenis_penilaian_id` bigint unsigned NOT NULL,
  `tanggal_nilai` date DEFAULT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nilai_guru_id_foreign` (`guru_id`),
  KEY `nilai_kelas_id_foreign` (`kelas_id`),
  KEY `nilai_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `nilai_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  KEY `nilai_jenis_penilaian_id_foreign` (`jenis_penilaian_id`),
  KEY `nilai_siswa_id_mata_pelajaran_id_tahun_ajaran_id_index` (`siswa_id`,`mata_pelajaran_id`,`tahun_ajaran_id`),
  CONSTRAINT `nilai_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nilai_jenis_penilaian_id_foreign` FOREIGN KEY (`jenis_penilaian_id`) REFERENCES `jenis_penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai`
--

LOCK TABLES `nilai` WRITE;
/*!40000 ALTER TABLE `nilai` DISABLE KEYS */;
INSERT INTO `nilai` VALUES (1,1,1,1,1,1,1,'2026-04-14',85.00,'Tugas 1','2026-04-15 23:58:34','2026-04-15 23:58:34'),(2,1,1,1,1,1,2,'2026-04-15',88.00,'UTS','2026-04-15 23:58:34','2026-04-15 23:58:34'),(3,1,1,1,1,1,3,'2026-04-16',90.00,'UAS','2026-04-15 23:58:34','2026-04-15 23:58:34'),(4,1,1,1,1,1,1,'2026-04-13',85.00,'Tugas 1','2026-04-16 01:08:27','2026-04-16 01:08:27'),(5,1,1,1,1,1,2,'2026-04-14',88.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(6,1,1,1,1,1,3,'2026-04-15',90.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(7,1,3,1,2,1,1,'2026-04-12',82.00,'Tugas 2','2026-04-16 01:08:27','2026-04-16 01:08:27'),(8,1,3,1,2,1,2,'2026-04-13',85.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(9,1,3,1,2,1,3,'2026-04-15',87.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(10,1,3,1,3,1,1,'2026-04-11',84.00,'Tugas 3','2026-04-16 01:08:27','2026-04-16 01:08:27'),(11,1,3,1,3,1,2,'2026-04-12',87.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(12,1,3,1,3,1,3,'2026-04-15',89.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(13,1,2,1,4,1,1,'2026-04-10',86.00,'Tugas 4','2026-04-16 01:08:27','2026-04-16 01:08:27'),(14,1,2,1,4,1,2,'2026-04-11',89.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(15,1,2,1,4,1,3,'2026-04-15',91.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(16,1,3,1,5,1,1,'2026-04-09',83.00,'Tugas 5','2026-04-16 01:08:27','2026-04-16 01:08:27'),(17,1,3,1,5,1,2,'2026-04-10',86.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(18,1,3,1,5,1,3,'2026-04-15',88.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(19,2,1,2,1,1,1,'2026-04-12',83.00,'Tugas 1','2026-04-16 01:08:27','2026-04-16 01:08:27'),(20,2,1,2,1,1,2,'2026-04-13',86.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(21,2,1,2,1,1,3,'2026-04-14',88.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(22,2,3,2,2,1,1,'2026-04-11',80.00,'Tugas 2','2026-04-16 01:08:27','2026-04-16 01:08:27'),(23,2,3,2,2,1,2,'2026-04-12',83.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(24,2,3,2,2,1,3,'2026-04-14',85.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(25,2,3,2,3,1,1,'2026-04-10',82.00,'Tugas 3','2026-04-16 01:08:27','2026-04-16 01:08:27'),(26,2,3,2,3,1,2,'2026-04-11',85.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(27,2,3,2,3,1,3,'2026-04-14',87.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(28,2,2,2,4,1,1,'2026-04-09',84.00,'Tugas 4','2026-04-16 01:08:27','2026-04-16 01:08:27'),(29,2,2,2,4,1,2,'2026-04-10',87.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(30,2,2,2,4,1,3,'2026-04-14',89.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(31,2,3,2,5,1,1,'2026-04-08',81.00,'Tugas 5','2026-04-16 01:08:27','2026-04-16 01:08:27'),(32,2,3,2,5,1,2,'2026-04-09',84.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(33,2,3,2,5,1,3,'2026-04-14',86.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(34,3,1,2,1,1,1,'2026-04-11',81.00,'Tugas 1','2026-04-16 01:08:27','2026-04-16 01:08:27'),(35,3,1,2,1,1,2,'2026-04-12',84.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(36,3,1,2,1,1,3,'2026-04-13',86.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(37,3,3,2,2,1,1,'2026-04-10',78.00,'Tugas 2','2026-04-16 01:08:27','2026-04-16 01:08:27'),(38,3,3,2,2,1,2,'2026-04-11',81.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(39,3,3,2,2,1,3,'2026-04-13',83.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(40,3,3,2,3,1,1,'2026-04-09',80.00,'Tugas 3','2026-04-16 01:08:27','2026-04-16 01:08:27'),(41,3,3,2,3,1,2,'2026-04-10',83.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(42,3,3,2,3,1,3,'2026-04-13',85.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(43,3,2,2,4,1,1,'2026-04-08',82.00,'Tugas 4','2026-04-16 01:08:27','2026-04-16 01:08:27'),(44,3,2,2,4,1,2,'2026-04-09',85.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(45,3,2,2,4,1,3,'2026-04-13',87.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(46,3,3,2,5,1,1,'2026-04-07',79.00,'Tugas 5','2026-04-16 01:08:27','2026-04-16 01:08:27'),(47,3,3,2,5,1,2,'2026-04-08',82.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(48,3,3,2,5,1,3,'2026-04-13',84.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(49,4,1,3,1,1,1,'2026-04-10',79.00,'Tugas 1','2026-04-16 01:08:27','2026-04-16 01:08:27'),(50,4,1,3,1,1,2,'2026-04-11',82.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(51,4,1,3,1,1,3,'2026-04-12',84.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(52,4,3,3,2,1,1,'2026-04-09',76.00,'Tugas 2','2026-04-16 01:08:27','2026-04-16 01:08:27'),(53,4,3,3,2,1,2,'2026-04-10',79.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(54,4,3,3,2,1,3,'2026-04-12',81.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(55,4,3,3,3,1,1,'2026-04-08',78.00,'Tugas 3','2026-04-16 01:08:27','2026-04-16 01:08:27'),(56,4,3,3,3,1,2,'2026-04-09',81.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(57,4,3,3,3,1,3,'2026-04-12',83.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(58,4,2,3,4,1,1,'2026-04-07',80.00,'Tugas 4','2026-04-16 01:08:27','2026-04-16 01:08:27'),(59,4,2,3,4,1,2,'2026-04-08',83.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(60,4,2,3,4,1,3,'2026-04-12',85.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(61,4,3,3,5,1,1,'2026-04-06',77.00,'Tugas 5','2026-04-16 01:08:27','2026-04-16 01:08:27'),(62,4,3,3,5,1,2,'2026-04-07',80.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(63,4,3,3,5,1,3,'2026-04-12',82.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(64,5,1,3,1,1,1,'2026-04-09',77.00,'Tugas 1','2026-04-16 01:08:27','2026-04-16 01:08:27'),(65,5,1,3,1,1,2,'2026-04-10',80.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(66,5,1,3,1,1,3,'2026-04-11',82.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(67,5,3,3,2,1,1,'2026-04-08',74.00,'Tugas 2','2026-04-16 01:08:27','2026-04-16 01:08:27'),(68,5,3,3,2,1,2,'2026-04-09',77.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(69,5,3,3,2,1,3,'2026-04-11',79.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(70,5,3,3,3,1,1,'2026-04-07',76.00,'Tugas 3','2026-04-16 01:08:27','2026-04-16 01:08:27'),(71,5,3,3,3,1,2,'2026-04-08',79.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(72,5,3,3,3,1,3,'2026-04-11',81.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(73,5,2,3,4,1,1,'2026-04-06',78.00,'Tugas 4','2026-04-16 01:08:27','2026-04-16 01:08:27'),(74,5,2,3,4,1,2,'2026-04-07',81.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(75,5,2,3,4,1,3,'2026-04-11',83.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(76,5,3,3,5,1,1,'2026-04-05',75.00,'Tugas 5','2026-04-16 01:08:27','2026-04-16 01:08:27'),(77,5,3,3,5,1,2,'2026-04-06',78.00,'UTS','2026-04-16 01:08:27','2026-04-16 01:08:27'),(78,5,3,3,5,1,3,'2026-04-11',80.00,'UAS','2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `nilai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_akhir`
--

DROP TABLE IF EXISTS `nilai_akhir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_akhir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `semester` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_akhir` decimal(5,2) NOT NULL,
  `predikat` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ranking` smallint unsigned DEFAULT NULL,
  `status_lulus` tinyint(1) NOT NULL DEFAULT '0',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilai_akhir_unique` (`siswa_id`,`mata_pelajaran_id`,`tahun_ajaran_id`,`semester`),
  KEY `nilai_akhir_kelas_id_foreign` (`kelas_id`),
  KEY `nilai_akhir_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `nilai_akhir_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `nilai_akhir_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_akhir_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_akhir_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_akhir_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_akhir`
--

LOCK TABLES `nilai_akhir` WRITE;
/*!40000 ALTER TABLE `nilai_akhir` DISABLE KEYS */;
INSERT INTO `nilai_akhir` VALUES (1,1,1,1,1,'Ganjil',89.00,'A',1,1,'Data seed testing','2026-04-15 23:58:34','2026-04-16 01:08:27'),(2,1,1,2,1,'Ganjil',86.00,'A',1,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,1,1,3,1,'Ganjil',88.00,'A',1,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(4,1,1,4,1,'Ganjil',90.00,'A',1,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(5,1,1,5,1,'Ganjil',87.00,'A',1,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(6,2,2,1,1,'Ganjil',87.00,'A',2,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(7,2,2,2,1,'Ganjil',84.00,'B',2,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(8,2,2,3,1,'Ganjil',86.00,'A',2,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(9,2,2,4,1,'Ganjil',88.00,'A',2,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(10,2,2,5,1,'Ganjil',85.00,'A',2,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(11,3,2,1,1,'Ganjil',85.00,'A',3,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(12,3,2,2,1,'Ganjil',82.00,'B',3,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(13,3,2,3,1,'Ganjil',84.00,'B',3,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(14,3,2,4,1,'Ganjil',86.00,'A',3,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(15,3,2,5,1,'Ganjil',83.00,'B',3,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(16,4,3,1,1,'Ganjil',83.00,'B',4,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(17,4,3,2,1,'Ganjil',80.00,'B',4,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(18,4,3,3,1,'Ganjil',82.00,'B',4,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(19,4,3,4,1,'Ganjil',84.00,'B',4,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(20,4,3,5,1,'Ganjil',81.00,'B',4,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(21,5,3,1,1,'Ganjil',81.00,'B',5,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(22,5,3,2,1,'Ganjil',78.00,'B',5,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(23,5,3,3,1,'Ganjil',80.00,'B',5,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(24,5,3,4,1,'Ganjil',82.00,'B',5,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27'),(25,5,3,5,1,'Ganjil',79.00,'B',5,1,'Data seed testing','2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `nilai_akhir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5V5iEq7jQQZpRPD0R0qfXsPu79B3sibbF56Kmfja',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15','eyJfdG9rZW4iOiJ0Umc5ZEUyb1pIZlQ0TDNwUm5QZDk5MGxQS2tKc01pTXZNdXJyUTFRIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDA4XC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1776406419),('aJHDEeuPqoWMCrXMH7DqS3iIvVBAcy3ctA0BbNY9',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15','eyJfdG9rZW4iOiJqMzlaN1RNMFY1RDdrVjB6d05KNUhzMVBja1J3bHM0cTl4M3l4SXg2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDdcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1776340210),('Kf0ITy1kquzjTNvQAlHHJeI6wVtkn4EhChKQU1XE',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15','eyJfdG9rZW4iOiJQTUxJMGtudkhiZ0FVUnlFM2pPWUY3cmYxRzFnQmNTRnU5TmxyblVLIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDhcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1776345618);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `nis` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswa_nis_unique` (`nis`),
  UNIQUE KEY `siswa_user_id_unique` (`user_id`),
  UNIQUE KEY `siswa_nisn_unique` (`nisn`),
  KEY `siswa_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` VALUES (1,3,'20250001','9988776655','Siswa Demo','Perempuan','2008-07-15','Jl. Pelajar No. 12','081234567892',NULL,1,'2026-04-15 01:46:13','2026-04-15 01:46:13'),(2,8,'20250002','9988776656','Siswa Dua','Laki-laki','2008-03-10','Jl. Pelajar 20250002','081234567895',NULL,2,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(3,9,'20250003','9988776657','Siswa Tiga','Perempuan','2008-05-21','Jl. Pelajar 20250003','081234567896',NULL,2,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(4,10,'20250004','9988776658','Siswa Empat','Laki-laki','2008-08-11','Jl. Pelajar 20250004','081234567897',NULL,3,'2026-04-16 01:08:27','2026-04-16 01:08:27'),(5,11,'20250005','9988776659','Siswa Lima','Perempuan','2008-11-02','Jl. Pelajar 20250005','081234567898',NULL,3,'2026-04-16 01:08:27','2026-04-16 01:08:27');
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tahun_ajaran`
--

DROP TABLE IF EXISTS `tahun_ajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tahun_ajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tahun_ajaran`
--

LOCK TABLES `tahun_ajaran` WRITE;
/*!40000 ALTER TABLE `tahun_ajaran` DISABLE KEYS */;
INSERT INTO `tahun_ajaran` VALUES (1,'2025/2026','Ganjil','2026-04-01','2026-09-30',1,'2026-04-15 01:46:13','2026-04-16 01:45:51');
/*!40000 ALTER TABLE `tahun_ajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tu',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Sekolah','admin','tu@sekolah.test','081234567890','2026-04-15 01:20:13','$2y$12$gbBnIYlaI/.5qUcnGH2KgO9mM2Fso3UdG4otUP3kRLFJ.6jQ2gseu',1,'WLDwuzRkY1pCFtaRVtDLTaBAuLFFEvyzsVyDBW0Kr6JVBlpyAmsbHTZ3m2L0','2026-04-15 01:20:14','2026-04-16 01:45:46'),(2,'Guru Demo','guru','guru@sekolah.test','081234567891','2026-04-15 01:20:14','$2y$12$zsxLIsyLuKaK2RrYfkRLcuvhr8ca.Tnblz6em2aSyRD6hha1Be4Dy',1,'0MsbPGZLsKJTpyrGbUfA2xSrmkmemCElYskPtyxhyvS75J9S0TPOEu2oEyHc','2026-04-15 01:20:14','2026-04-16 01:45:46'),(3,'Siswa Demo','siswa','siswa@sekolah.test','081234567892','2026-04-15 01:20:14','$2y$12$EWI63vbHx2ULf0rYkBBNDe268AX4JmNauLz/XkbW2D0LWMlY4CswW',1,'btKSW0bw2NrbBqonzVX1eFztUK22lfm0hs1wvwERBERgTtv7D8u0g3c8MKz4','2026-04-15 01:20:14','2026-04-16 01:45:46'),(5,'Admin Sistem','admin','admin@sekolah.test','081234567899',NULL,'$2y$12$9NeJWiQp67HD0TrWvZ0vgudrEHRatjcFCzrjNuD40HCJI21GYPoD2',1,NULL,'2026-04-15 01:59:53','2026-04-16 01:45:47'),(6,'Guru Matematika','guru','guru2@sekolah.test','081234567893',NULL,'$2y$12$8.1pAuY5UAiHbJmJ2mZCVeAwn/dle/FNVKNy2XZWxC1Y7tCcUditq',1,NULL,'2026-04-16 01:08:26','2026-04-16 01:45:47'),(7,'Guru Bahasa','guru','guru3@sekolah.test','081234567894',NULL,'$2y$12$ARY32ij.MnK8UQf34JWgNeh3fsbOviK18nR5mickvK2MCywfcfsHu',1,NULL,'2026-04-16 01:08:26','2026-04-16 01:45:48'),(8,'Siswa Dua','siswa','siswa2@sekolah.test','081234567895',NULL,'$2y$12$3HY2YvPrpKzY/NtFnXE0v.Z2tXKi57mE6nLY7N3NVu9nbrKfPbaZ2',1,NULL,'2026-04-16 01:08:26','2026-04-16 01:45:49'),(9,'Siswa Tiga','siswa','siswa3@sekolah.test','081234567896',NULL,'$2y$12$vdLfbYhQNK6VHGo2Nrd98O/1nwR.qF09k5Mbicgk8r25YDLDxgJEy',1,NULL,'2026-04-16 01:08:26','2026-04-16 01:45:50'),(10,'Siswa Empat','siswa','siswa4@sekolah.test','081234567897',NULL,'$2y$12$v4SlSQScYEGB7jp7oqhel.4KeeScuRLSRDaklfXiRL5XmKycWNtyW',1,NULL,'2026-04-16 01:08:27','2026-04-16 01:45:50'),(11,'Siswa Lima','siswa','siswa5@sekolah.test','081234567898',NULL,'$2y$12$55ivhPQlugxWp85/lpa/Z.2sCpzMYKQNpP2yQnrjybGlv78GsVriK',1,NULL,'2026-04-16 01:08:27','2026-04-16 01:45:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dbICA'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-21 17:41:34
