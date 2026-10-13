-- Database: oa-is-god-school (Ob Anggen School)
-- Impor lewat phpMyAdmin: buat database 'oa-is-god-school' (utf8mb4_unicode_ci) lalu Import file ini.
-- Akun: master@obanggen.sch.id / publikasi@obanggen.sch.id / pendaftaran@obanggen.sch.id  (kata sandi: password123)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(40) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'teks',
  `title` varchar(255) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `video_url` varchar(255) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `button_label` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `image_position` enum('left','right') NOT NULL DEFAULT 'right',
  `width` tinyint(3) unsigned NOT NULL DEFAULT 12,
  `style` enum('plain','card','accent') NOT NULL DEFAULT 'plain',
  `align` enum('left','center','justify') NOT NULL DEFAULT 'left',
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blocks_menu_id_position_index` (`menu_id`,`position`),
  CONSTRAINT `blocks_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
INSERT INTO `blocks` VALUES
(1,3,'video',NULL,NULL,NULL,NULL,'https://www.youtube.com/watch?v=ysz5S6PUM-U',NULL,'Cerita singkat perjalanan Sekolah Ob Anggen',NULL,NULL,'right',12,'plain','left',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,3,'teks','Awal mula','<p>Ob Anggen berawal dari kerinduan keluarga-keluarga di Bokondini agar anak-anak mereka mendapat pendidikan yang baik tanpa harus meninggalkan kampung halaman. Bersama gereja setempat, sekolah kecil ini tumbuh menjadi tiga kampus yang melayani ratusan siswa.</p><p>Hingga hari ini, Ob Anggen tetap memegang prinsip bahwa keluarga adalah mitra utama sekolah dalam mendidik anak.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'right',12,'plain','justify',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,3,'gambar_teks','Tumbuh bersama masyarakat','<p>Sekolah dibangun bersama warga kampung: orang tua ikut mengangkat kayu, menyiapkan kebun, dan menjaga asrama. Kebersamaan inilah yang menjadi ciri khas Ob Anggen.</p>','assets/img/seed/family.jpg',NULL,NULL,NULL,NULL,NULL,NULL,'right',12,'plain','left',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,3,'galeri','Potret masa kini',NULL,NULL,'[\"assets\\/img\\/seed\\/students.jpg\",\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/group.jpg\",\"assets\\/img\\/seed\\/landscape.jpg\"]',NULL,NULL,NULL,NULL,NULL,'right',12,'plain','left',4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,4,'kutipan','Visi','Menjadi sekolah pemuridan yang melahirkan generasi Papua Pegunungan yang takut akan Tuhan, cerdas, dan berdampak bagi keluarga serta masyarakat.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'right',6,'card','left',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,4,'daftar','Misi',NULL,NULL,NULL,NULL,'Menyelenggarakan pendidikan berkualitas berbasis nilai Kristiani\nMemberdayakan keluarga sebagai mitra utama pendidikan\nMengembangkan keterampilan hidup yang relevan dengan budaya lokal\nMembangun keterlibatan gereja dan masyarakat',NULL,NULL,NULL,'right',6,'card','left',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,4,'kartu','Nilai-nilai dasar',NULL,NULL,NULL,NULL,'Iman | Bertumbuh dalam pengenalan akan Tuhan setiap hari | bi-heart\nKeluarga | Orang tua dan sekolah berjalan bersama | bi-people\nKetekunan | Tidak mudah menyerah menghadapi tantangan | bi-graph-up-arrow\nPelayanan | Belajar untuk memberi dampak bagi sesama | bi-hand-thumbs-up',NULL,NULL,NULL,'right',12,'plain','left',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,15,'teks','Bangga dengan capaian siswa','<p>Halaman ini dikelola oleh Admin Publikasi. Setiap prestasi siswa, baik akademik, olahraga, seni, maupun pelayanan, dapat ditambahkan sebagai blok baru.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'right',12,'plain','left',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,15,'kartu','Prestasi tahun ini',NULL,NULL,NULL,NULL,'Juara 1 Cerdas Cermat | Tingkat Kabupaten Tolikara | bi-trophy\nBeasiswa Kuliah | 8 lulusan diterima di perguruan tinggi | bi-mortarboard\nFestival Budaya | Penampilan tari terbaik se-Papua Pegunungan | bi-music-note-beamed',NULL,NULL,NULL,'right',12,'plain','left',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,23,'daftar','Langkah pendaftaran online',NULL,NULL,NULL,NULL,'Pilih jalur pendaftaran yang sedang dibuka\nBaca persyaratan, lalu klik tombol Daftar\nIsi formulir dan unggah dokumen\nSimpan nomor pendaftaran yang muncul setelah formulir terkirim\nPantau status & jadwal seleksi lewat menu Cek Status',NULL,NULL,NULL,'right',8,'card','left',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,23,'kutipan','Butuh bantuan?','Hubungi panitia lewat WhatsApp sekolah pada hari kerja pukul 08.00–15.00 WIT.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'right',4,'accent','left',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,23,'tombol','Siap mendaftar?','<p>Pendaftaran siswa baru tahun ajaran mendatang sedang dibuka.</p>',NULL,NULL,NULL,NULL,NULL,'Lihat jalur pendaftaran','/pendaftaran','right',12,'plain','left',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,1,'tombol','Mari bergabung bersama keluarga Ob Anggen','<p>Pendaftaran siswa baru dan rekrutmen guru sedang dibuka. Kenali jalurnya dan daftar secara online.</p>',NULL,NULL,NULL,NULL,NULL,'Lihat pendaftaran','/pendaftaran','right',12,'accent','left',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `regency` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES
(1,'Bokondini','Kabupaten Tolikara','Kampus Bokondini melayani anak-anak dari kampung sekitar mulai jenjang SD sampai SMA, lengkap dengan asrama.','assets/img/seed/landscape.jpg',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Eragayam','Kabupaten Mamberamo Tengah','Kampus Eragayam melayani anak-anak dari kampung sekitar mulai jenjang SD sampai SMA, lengkap dengan asrama.','assets/img/seed/landscape.jpg',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Dogobak','Kabupaten Mamberamo Tengah','Kampus Dogobak melayani anak-anak dari kampung sekitar mulai jenjang SD sampai SMA, lengkap dengan asrama.','assets/img/seed/landscape.jpg',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `time_info` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` enum('akademik','ujian','libur','kegiatan') NOT NULL DEFAULT 'akademik',
  `level` enum('semua','sd','smp','sma') NOT NULL DEFAULT 'semua',
  `academic_year` varchar(9) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_events_start_date_index` (`start_date`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `calendar_events` WRITE;
/*!40000 ALTER TABLE `calendar_events` DISABLE KEYS */;
INSERT INTO `calendar_events` VALUES
(1,'Awal tahun ajaran',NULL,'2026-07-13',NULL,'08.00 WIT','Kampus masing-masing','akademik','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Ujian Tengah Semester SD',NULL,'2026-09-15','2026-09-19','08.00 WIT','Kampus masing-masing','ujian','sd','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Ujian Tengah Semester SMP & SMA',NULL,'2026-09-21','2026-09-26','08.00 WIT','Kampus masing-masing','ujian','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'Pentas seni sekolah',NULL,'2026-10-09',NULL,'08.00 WIT','Kampus masing-masing','kegiatan','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,'OutTreat siswa SMA',NULL,'2026-10-19','2026-10-21','08.00 WIT','Kampus masing-masing','kegiatan','sma','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,'Hari Guru — ibadah syukur',NULL,'2026-11-25',NULL,'08.00 WIT','Kampus masing-masing','kegiatan','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,'Penilaian Akhir Semester',NULL,'2026-12-01','2026-12-10','08.00 WIT','Kampus masing-masing','ujian','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,'Pembagian rapor semester ganjil',NULL,'2026-12-17',NULL,'08.00 WIT','Kampus masing-masing','akademik','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,'Libur Natal & Tahun Baru',NULL,'2026-12-19','2027-01-04','08.00 WIT','Kampus masing-masing','libur','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,'Awal semester genap',NULL,'2027-01-11',NULL,'08.00 WIT','Kampus masing-masing','akademik','semua','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,'Lomba cerdas cermat SD',NULL,'2027-02-14',NULL,'08.00 WIT','Kampus masing-masing','kegiatan','sd','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,'Ujian sekolah SMP',NULL,'2027-03-09','2027-03-14','08.00 WIT','Kampus masing-masing','ujian','smp','2026/2027',1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `calendar_events` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('news','gallery') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_type_unique` (`slug`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Pengumuman','pengumuman','news','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Kegiatan Sekolah','kegiatan-sekolah','news','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Prestasi','prestasi','news','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'Kegiatan Belajar','kegiatan-belajar','gallery','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,'Asrama','asrama','gallery','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,'Life Skill','life-skill','gallery','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,'OutTreat','outtreat','gallery','2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('gedung','asrama') NOT NULL DEFAULT 'gedung',
  `level` enum('umum','tk','sd','smp','sma') NOT NULL DEFAULT 'umum',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `detail` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `capacity` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `used_for` varchar(255) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facilities_slug_unique` (`slug`),
  KEY `facilities_branch_id_foreign` (`branch_id`),
  CONSTRAINT `facilities_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES
(1,1,'gedung','tk','Kelas TK','bokondini-kelas-tk','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,1,'gedung','sd','SD Kelas 1','bokondini-sd-kelas-1','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,1,'gedung','sd','SD Kelas 2','bokondini-sd-kelas-2','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,1,'gedung','sd','SD Kelas 3','bokondini-sd-kelas-3','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,1,'gedung','sd','SD Kelas 4','bokondini-sd-kelas-4','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',5,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,1,'gedung','sd','SD Kelas 5','bokondini-sd-kelas-5','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,1,'gedung','sd','SD Kelas 6','bokondini-sd-kelas-6','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',7,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,1,'gedung','smp','SMP Kelas 7','bokondini-smp-kelas-7','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',8,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,1,'gedung','smp','SMP Kelas 8','bokondini-smp-kelas-8','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',9,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,1,'gedung','smp','SMP Kelas 9','bokondini-smp-kelas-9','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',10,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,1,'gedung','sma','Laboratorium Sains','bokondini-laboratorium-sains','Laboratorium','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Praktikum IPA',11,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,1,'gedung','umum','Perpustakaan','bokondini-perpustakaan','Ruang Baca','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',12,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,1,'asrama','umum','Asrama Putra','bokondini-asrama-putra','Kamar Putra','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(14,1,'asrama','umum','Asrama Putri','bokondini-asrama-putri','Kamar Putri','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(15,1,'asrama','umum','Ruang Makan Asrama','bokondini-ruang-makan-asrama','Ruang Bersama','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(16,2,'gedung','tk','Kelas TK','eragayam-kelas-tk','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(17,2,'gedung','sd','SD Kelas 1','eragayam-sd-kelas-1','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(18,2,'gedung','sd','SD Kelas 2','eragayam-sd-kelas-2','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(19,2,'gedung','sd','SD Kelas 3','eragayam-sd-kelas-3','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(20,2,'gedung','sd','SD Kelas 4','eragayam-sd-kelas-4','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',5,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(21,2,'gedung','sd','SD Kelas 5','eragayam-sd-kelas-5','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(22,2,'gedung','sd','SD Kelas 6','eragayam-sd-kelas-6','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',7,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(23,2,'gedung','smp','SMP Kelas 7','eragayam-smp-kelas-7','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',8,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(24,2,'gedung','smp','SMP Kelas 8','eragayam-smp-kelas-8','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',9,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(25,2,'gedung','smp','SMP Kelas 9','eragayam-smp-kelas-9','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',10,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(26,2,'gedung','sma','Laboratorium Sains','eragayam-laboratorium-sains','Laboratorium','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Praktikum IPA',11,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(27,2,'gedung','umum','Perpustakaan','eragayam-perpustakaan','Ruang Baca','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',12,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(28,2,'asrama','umum','Asrama Putra','eragayam-asrama-putra','Kamar Putra','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(29,2,'asrama','umum','Asrama Putri','eragayam-asrama-putri','Kamar Putri','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(30,2,'asrama','umum','Ruang Makan Asrama','eragayam-ruang-makan-asrama','Ruang Bersama','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(31,3,'gedung','tk','Kelas TK','dogobak-kelas-tk','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(32,3,'gedung','sd','SD Kelas 1','dogobak-sd-kelas-1','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(33,3,'gedung','sd','SD Kelas 2','dogobak-sd-kelas-2','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(34,3,'gedung','sd','SD Kelas 3','dogobak-sd-kelas-3','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(35,3,'gedung','sd','SD Kelas 4','dogobak-sd-kelas-4','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',5,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(36,3,'gedung','sd','SD Kelas 5','dogobak-sd-kelas-5','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(37,3,'gedung','sd','SD Kelas 6','dogobak-sd-kelas-6','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',7,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(38,3,'gedung','smp','SMP Kelas 7','dogobak-smp-kelas-7','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',8,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(39,3,'gedung','smp','SMP Kelas 8','dogobak-smp-kelas-8','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',9,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(40,3,'gedung','smp','SMP Kelas 9','dogobak-smp-kelas-9','Ruang Kelas','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/classroom.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',10,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(41,3,'gedung','sma','Laboratorium Sains','dogobak-laboratorium-sains','Laboratorium','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Praktikum IPA',11,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(42,3,'gedung','umum','Perpustakaan','dogobak-perpustakaan','Ruang Baca','Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.','<p>Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>','assets/img/seed/students.jpg','[\"assets\\/img\\/seed\\/classroom.jpg\",\"assets\\/img\\/seed\\/students.jpg\"]','30 siswa','64 m²','Kegiatan belajar mengajar',12,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(43,3,'asrama','umum','Asrama Putra','dogobak-asrama-putra','Kamar Putra','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(44,3,'asrama','umum','Asrama Putri','dogobak-asrama-putri','Kamar Putri','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(45,3,'asrama','umum','Ruang Makan Asrama','dogobak-ruang-makan-asrama','Ruang Bersama','Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',NULL,'assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/family.jpg\",\"assets\\/img\\/seed\\/group.jpg\"]','40 siswa',NULL,'Tempat tinggal & pembinaan karakter',3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_albums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`),
  KEY `gallery_albums_category_id_foreign` (`category_id`),
  KEY `gallery_albums_user_id_foreign` (`user_id`),
  CONSTRAINT `gallery_albums_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gallery_albums_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES
(1,4,2,'Belajar di kelas Bokondini','belajar-di-kelas-bokondini','Dokumentasi belajar di kelas bokondini bersama siswa dan guru Ob Anggen.',NULL,NULL,'2026-09-24',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,5,2,'Kehidupan asrama','kehidupan-asrama','Dokumentasi kehidupan asrama bersama siswa dan guru Ob Anggen.',NULL,NULL,'2026-09-12',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,6,2,'Berkebun bersama','berkebun-bersama','Dokumentasi berkebun bersama bersama siswa dan guru Ob Anggen.',NULL,NULL,'2026-08-31',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,7,2,'OutTreat 2026','outtreat-2026','Dokumentasi outtreat 2026 bersama siswa dan guru Ob Anggen.',NULL,'https://www.youtube.com/watch?v=ysz5S6PUM-U','2026-08-19',1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `gallery_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_album_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_photos_gallery_album_id_foreign` (`gallery_album_id`),
  CONSTRAINT `gallery_photos_gallery_album_id_foreign` FOREIGN KEY (`gallery_album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `gallery_photos` WRITE;
/*!40000 ALTER TABLE `gallery_photos` DISABLE KEYS */;
INSERT INTO `gallery_photos` VALUES
(1,1,'assets/img/seed/classroom.jpg','Foto 1',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,1,'assets/img/seed/students.jpg','Foto 2',2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,1,'assets/img/seed/students.jpg','Foto 3',3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,1,'assets/img/seed/group.jpg','Foto 4',4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,2,'assets/img/seed/family.jpg','Foto 1',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,2,'assets/img/seed/students.jpg','Foto 2',2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,2,'assets/img/seed/landscape.jpg','Foto 3',3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,3,'assets/img/seed/family.jpg','Foto 1',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,3,'assets/img/seed/group.jpg','Foto 2',2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,3,'assets/img/seed/landscape.jpg','Foto 3',3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,4,'assets/img/seed/landscape.jpg','Foto 1',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,4,'assets/img/seed/group.jpg','Foto 2',2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,4,'assets/img/seed/students.jpg','Foto 3',3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(14,4,'assets/img/seed/students.jpg','Foto 4',4,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `gallery_photos` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `graduates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `graduates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `level` enum('sd','smp','sma') NOT NULL DEFAULT 'sma',
  `graduation_year` year(4) NOT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `predicate` varchar(255) DEFAULT NULL,
  `continue_to` varchar(255) DEFAULT NULL,
  `testimony` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `graduates_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `graduates` WRITE;
/*!40000 ALTER TABLE `graduates` DISABLE KEYS */;
INSERT INTO `graduates` VALUES
(1,'Ruben Penggu','ruben-penggu-2026','assets/img/seed/graduate-boy.jpg','sma',2026,'XII IPA','Sangat Memuaskan','Universitas Cenderawasih','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Naomi Kogoya','naomi-kogoya-2026','assets/img/seed/graduate-girl.jpg','sma',2026,'XII IPA','Sangat Memuaskan','Universitas Pelita Harapan','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Yosua Wenda','yosua-wenda-2026','assets/img/seed/graduate-boy.jpg','sma',2026,'XII IPA','Sangat Memuaskan','Politeknik Negeri Jayapura','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'Debora Tabuni','debora-tabuni-2026','assets/img/seed/graduate-girl.jpg','sma',2026,'XII IPA','Memuaskan','Bekerja di kampung','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,'Samuel Yikwa','samuel-yikwa-2026','assets/img/seed/graduate-boy.jpg','sma',2026,'XII IPA','Memuaskan','Universitas Cenderawasih','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,'Rahel Karoba','rahel-karoba-2026','assets/img/seed/graduate-girl.jpg','sma',2026,'XII IPA','Memuaskan','Universitas Pelita Harapan','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,'Markus Gire','markus-gire-2026','assets/img/seed/graduate-boy.jpg','sma',2026,'XII IPA','Memuaskan','Politeknik Negeri Jayapura','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,'Ribka Enembe','ribka-enembe-2026','assets/img/seed/graduate-girl.jpg','sma',2026,'XII IPA','Memuaskan','Bekerja di kampung','Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,'Yonas Kogoya','yonas-kogoya-2025','assets/img/seed/graduate-boy.jpg','sma',2025,'XII IPS','Memuaskan','Universitas Cenderawasih',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,'Lea Tabuni','lea-tabuni-2025','assets/img/seed/graduate-girl.jpg','smp',2025,'IX','Memuaskan','SMA Ob Anggen',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,'Abner Wenda','abner-wenda-2025','assets/img/seed/graduate-boy.jpg','sma',2025,'XII IPS','Memuaskan','Universitas Cenderawasih',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,'Mince Yikwa','mince-yikwa-2025','assets/img/seed/graduate-girl.jpg','smp',2025,'IX','Memuaskan','SMA Ob Anggen',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `graduates` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'halaman',
  `param` varchar(60) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `intro` longtext DEFAULT NULL,
  `show_sidebar` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_highlight` tinyint(1) NOT NULL DEFAULT 0,
  `open_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `access_publikasi` tinyint(1) NOT NULL DEFAULT 0,
  `access_pendaftaran` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_slug_unique` (`slug`),
  KEY `menus_updated_by_foreign` (`updated_by`),
  KEY `menus_parent_id_position_index` (`parent_id`,`position`),
  KEY `menus_type_index` (`type`),
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menus_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES
(1,NULL,'Beranda','beranda','beranda',NULL,NULL,'bi-house-door',NULL,NULL,NULL,1,1,0,0,1,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,NULL,'Tentang Kami','tentang-kami','dropdown',NULL,NULL,NULL,NULL,NULL,NULL,1,1,0,0,2,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,2,'Profil & Sejarah','sejarah','halaman',NULL,NULL,NULL,'Perjalanan sekolah pemuridan di Papua Pegunungan',NULL,NULL,1,1,0,0,1,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,2,'Visi dan Misi','visi-misi','halaman',NULL,NULL,NULL,'Arah dan nilai yang kami pegang',NULL,NULL,1,1,0,0,2,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,2,'Keunikan Ob Anggen','keunikan-ob-anggen','keunikan',NULL,NULL,NULL,'Program khas yang membentuk karakter siswa',NULL,NULL,1,1,0,0,3,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,2,'Guru & Staf','guru-staf','guru_staf',NULL,NULL,NULL,'Pimpinan, guru, dan pembina yang melayani',NULL,NULL,1,1,0,0,4,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,NULL,'Akademik','akademik','dropdown',NULL,NULL,NULL,NULL,NULL,NULL,1,1,0,0,3,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,7,'Kalender Akademik SD','kalender-akademik-sd','kalender','sd',NULL,NULL,'Jadwal kegiatan jenjang SD',NULL,NULL,1,1,0,0,1,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,7,'Kalender Akademik SMP','kalender-akademik-smp','kalender','smp',NULL,NULL,'Jadwal kegiatan jenjang SMP',NULL,NULL,1,1,0,0,2,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,7,'Kalender Akademik SMA','kalender-akademik-sma','kalender','sma',NULL,NULL,'Jadwal kegiatan jenjang SMA',NULL,NULL,1,1,0,0,3,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,7,'Graduations','graduations','kelulusan',NULL,NULL,NULL,'Kenali para lulusan Ob Anggen',NULL,NULL,1,1,0,0,4,1,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,NULL,'Informasi','informasi','dropdown',NULL,NULL,NULL,NULL,NULL,NULL,1,1,0,0,4,1,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,12,'Berita','berita','berita',NULL,NULL,NULL,'Kabar terbaru dari kampus Ob Anggen',NULL,NULL,1,1,0,0,1,1,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(14,12,'Galeri','galeri','galeri',NULL,NULL,NULL,'Potret kegiatan siswa, guru, dan asrama',NULL,NULL,1,1,0,0,2,1,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(15,12,'Prestasi Siswa','prestasi-siswa','halaman',NULL,NULL,NULL,'Capaian siswa di bidang akademik dan non-akademik',NULL,NULL,1,1,0,0,3,1,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(16,NULL,'Fasilitas','fasilitas','dropdown',NULL,NULL,NULL,NULL,NULL,NULL,1,1,0,0,5,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(17,16,'Gedung Sekolah','gedung-sekolah','fasilitas','gedung',NULL,NULL,'Ruang kelas, laboratorium, dan perpustakaan tiap kampus',NULL,NULL,1,1,0,0,1,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(18,16,'Asrama','asrama','fasilitas','asrama',NULL,NULL,'Rumah kedua bagi siswa Ob Anggen',NULL,NULL,1,1,0,0,2,0,0,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(19,NULL,'Pendaftaran','pendaftaran','dropdown',NULL,NULL,'bi-pencil-square',NULL,NULL,NULL,1,1,1,0,6,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(20,19,'Pendaftaran Siswa','pendaftaran-siswa','pendaftaran','siswa',NULL,NULL,'Penerimaan siswa baru SD, SMP, dan SMA',NULL,NULL,1,1,0,0,1,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(21,19,'Rekrutmen Guru & Staf','rekrutmen-guru-staf','pendaftaran','staf',NULL,NULL,'Bergabung melayani bersama kami',NULL,NULL,1,1,0,0,2,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(22,19,'Info Pendaftaran','info-pendaftaran','berita','pendaftaran',NULL,NULL,'Pengumuman resmi panitia pendaftaran',NULL,NULL,1,1,0,0,3,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(23,19,'Panduan Pendaftaran','panduan-pendaftaran','halaman',NULL,NULL,NULL,'Langkah mudah mendaftar secara online',NULL,NULL,1,1,0,0,4,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(24,19,'Cek Status Pendaftaran','cek-status-pendaftaran','cek_status',NULL,NULL,NULL,'Pantau hasil seleksi dengan nomor pendaftaran',NULL,NULL,1,1,0,0,5,0,1,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_10_06_000001_create_settings_table',1),
(5,'2026_10_06_000002_create_menus_table',1),
(6,'2026_10_06_000003_create_blocks_table',1),
(7,'2026_10_06_000004_create_profile_tables',1),
(8,'2026_10_06_000005_create_publication_tables',1),
(9,'2026_10_06_000006_create_facilities_table',1),
(10,'2026_10_06_000007_create_registration_tables',1),
(11,'2026_10_06_000008_create_activity_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `channel` enum('umum','pendaftaran') NOT NULL DEFAULT 'umum',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `body` longtext NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_slug_unique` (`slug`),
  KEY `news_category_id_foreign` (`category_id`),
  KEY `news_user_id_foreign` (`user_id`),
  KEY `news_status_published_at_index` (`status`,`published_at`),
  CONSTRAINT `news_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES
(1,2,2,'umum','Tenaga pendidik Ob Anggen mengikuti pelatihan kurikulum','tenaga-pendidik-ob-anggen-mengikuti-pelatihan-kurikulum','assets/img/seed/group.jpg','Kabar terbaru dari Ob Anggen School: tenaga pendidik ob anggen mengikuti pelatihan kurikulum.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',0,'published','2026-10-04 16:26:36',96,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,2,2,'umum','Siswa SMA mengikuti kegiatan OutTreat di Eragayam','siswa-sma-mengikuti-kegiatan-outtreat-di-eragayam','assets/img/seed/students.jpg','Kabar terbaru dari Ob Anggen School: siswa sma mengikuti kegiatan outtreat di eragayam.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',0,'published','2026-09-30 16:26:36',268,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,3,2,'umum','Wisuda angkatan 2026: delapan lulusan melanjutkan kuliah','wisuda-angkatan-2026-delapan-lulusan-melanjutkan-kuliah','assets/img/seed/graduate-boy.jpg','Kabar terbaru dari Ob Anggen School: wisuda angkatan 2026: delapan lulusan melanjutkan kuliah.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',1,'published','2026-09-27 16:26:36',163,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,2,2,'umum','Panen kebun Life Skill siswa asrama Bokondini','panen-kebun-life-skill-siswa-asrama-bokondini','assets/img/seed/family.jpg','Kabar terbaru dari Ob Anggen School: panen kebun life skill siswa asrama bokondini.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',0,'published','2026-09-22 16:26:36',239,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,1,3,'pendaftaran','Pendaftaran siswa baru tahun ajaran 2027/2028 dibuka','pendaftaran-siswa-baru-tahun-ajaran-20272028-dibuka','assets/img/seed/students.jpg','Kabar terbaru dari Ob Anggen School: pendaftaran siswa baru tahun ajaran 2027/2028 dibuka.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',1,'published','2026-10-05 16:26:36',125,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,1,3,'pendaftaran','Jadwal wawancara calon siswa gelombang pertama','jadwal-wawancara-calon-siswa-gelombang-pertama','assets/img/seed/classroom.jpg','Kabar terbaru dari Ob Anggen School: jadwal wawancara calon siswa gelombang pertama.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',0,'published','2026-10-02 16:26:36',173,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,1,3,'pendaftaran','Ob Anggen membuka lowongan guru dan pembina asrama','ob-anggen-membuka-lowongan-guru-dan-pembina-asrama','assets/img/seed/teacher.jpg','Kabar terbaru dari Ob Anggen School: ob anggen membuka lowongan guru dan pembina asrama.','<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',0,'published','2026-09-29 16:26:36',71,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `registration_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `registration_type_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` enum('text','email','tel','number','date','textarea','select','radio','file') NOT NULL,
  `section` enum('data_diri','orang_tua','dokumen','lainnya') NOT NULL DEFAULT 'data_diri',
  `options` text DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `help_text` varchar(255) DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `width` tinyint(3) unsigned NOT NULL DEFAULT 6,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_fields_registration_type_id_name_unique` (`registration_type_id`,`name`),
  CONSTRAINT `registration_fields_registration_type_id_foreign` FOREIGN KEY (`registration_type_id`) REFERENCES `registration_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `registration_fields` WRITE;
/*!40000 ALTER TABLE `registration_fields` DISABLE KEYS */;
INSERT INTO `registration_fields` VALUES
(1,1,'Tempat lahir','tempat_lahir','text','data_diri',NULL,NULL,NULL,1,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,1,'Tanggal lahir','tanggal_lahir','date','data_diri',NULL,NULL,NULL,1,6,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,1,'Jenis kelamin','jenis_kelamin','radio','data_diri','Laki-laki\nPerempuan',NULL,NULL,1,6,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,1,'Alamat tinggal','alamat','textarea','data_diri',NULL,NULL,NULL,1,12,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,1,'Asal sekolah','asal_sekolah','text','data_diri',NULL,NULL,NULL,0,6,5,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,1,'Kampus pilihan','kampus','select','data_diri','Bokondini\nEragayam\nDogobak',NULL,NULL,1,6,6,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,1,'Nama orang tua / wali','nama_orang_tua','text','orang_tua',NULL,NULL,NULL,1,6,7,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,1,'No. HP orang tua','hp_orang_tua','tel','orang_tua',NULL,NULL,NULL,1,6,8,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,1,'Pekerjaan orang tua','pekerjaan_orang_tua','text','orang_tua',NULL,NULL,NULL,0,6,9,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,1,'Kartu Keluarga (KK)','file_kk','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',1,6,10,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,1,'Akta kelahiran','file_akta','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',0,6,11,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,1,'Pasfoto terbaru','file_foto','file','dokumen',NULL,NULL,'JPG/PNG, maks. 4 MB',1,6,12,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,2,'Tempat lahir','tempat_lahir','text','data_diri',NULL,NULL,NULL,1,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(14,2,'Tanggal lahir','tanggal_lahir','date','data_diri',NULL,NULL,NULL,1,6,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(15,2,'Jenis kelamin','jenis_kelamin','radio','data_diri','Laki-laki\nPerempuan',NULL,NULL,1,6,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(16,2,'Alamat tinggal','alamat','textarea','data_diri',NULL,NULL,NULL,1,12,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(17,2,'Asal sekolah','asal_sekolah','text','data_diri',NULL,NULL,NULL,0,6,5,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(18,2,'Kampus pilihan','kampus','select','data_diri','Bokondini\nEragayam\nDogobak',NULL,NULL,1,6,6,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(19,2,'Nama orang tua / wali','nama_orang_tua','text','orang_tua',NULL,NULL,NULL,1,6,7,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(20,2,'No. HP orang tua','hp_orang_tua','tel','orang_tua',NULL,NULL,NULL,1,6,8,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(21,2,'Pekerjaan orang tua','pekerjaan_orang_tua','text','orang_tua',NULL,NULL,NULL,0,6,9,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(22,2,'Kartu Keluarga (KK)','file_kk','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',1,6,10,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(23,2,'Akta kelahiran','file_akta','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',0,6,11,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(24,2,'Pasfoto terbaru','file_foto','file','dokumen',NULL,NULL,'JPG/PNG, maks. 4 MB',1,6,12,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(25,3,'Tempat lahir','tempat_lahir','text','data_diri',NULL,NULL,NULL,1,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(26,3,'Tanggal lahir','tanggal_lahir','date','data_diri',NULL,NULL,NULL,1,6,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(27,3,'Jenis kelamin','jenis_kelamin','radio','data_diri','Laki-laki\nPerempuan',NULL,NULL,1,6,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(28,3,'Alamat tinggal','alamat','textarea','data_diri',NULL,NULL,NULL,1,12,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(29,3,'Asal sekolah','asal_sekolah','text','data_diri',NULL,NULL,NULL,0,6,5,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(30,3,'Kampus pilihan','kampus','select','data_diri','Bokondini\nEragayam\nDogobak',NULL,NULL,1,6,6,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(31,3,'Nama orang tua / wali','nama_orang_tua','text','orang_tua',NULL,NULL,NULL,1,6,7,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(32,3,'No. HP orang tua','hp_orang_tua','tel','orang_tua',NULL,NULL,NULL,1,6,8,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(33,3,'Pekerjaan orang tua','pekerjaan_orang_tua','text','orang_tua',NULL,NULL,NULL,0,6,9,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(34,3,'Kartu Keluarga (KK)','file_kk','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',1,6,10,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(35,3,'Akta kelahiran','file_akta','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',0,6,11,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(36,3,'Pasfoto terbaru','file_foto','file','dokumen',NULL,NULL,'JPG/PNG, maks. 4 MB',1,6,12,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(37,4,'Tempat lahir','tempat_lahir','text','data_diri',NULL,NULL,NULL,1,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(38,4,'Tanggal lahir','tanggal_lahir','date','data_diri',NULL,NULL,NULL,1,6,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(39,4,'Jenis kelamin','jenis_kelamin','radio','data_diri','Laki-laki\nPerempuan',NULL,NULL,1,6,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(40,4,'Alamat tinggal','alamat','textarea','data_diri',NULL,NULL,NULL,1,12,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(41,4,'Posisi yang dilamar','posisi','text','data_diri',NULL,NULL,NULL,1,6,5,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(42,4,'Pendidikan terakhir','pendidikan','select','data_diri','SMA/SMK\nD3\nS1\nS2',NULL,NULL,1,6,6,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(43,4,'Pengalaman kerja','pengalaman','textarea','lainnya',NULL,NULL,NULL,0,12,7,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(44,4,'Gereja asal','gereja','text','lainnya',NULL,NULL,NULL,0,6,8,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(45,4,'Curriculum Vitae (CV)','file_cv','file','dokumen',NULL,NULL,'PDF, maks. 4 MB',1,6,9,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(46,4,'Scan KTP','file_ktp','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',1,6,10,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(47,4,'Ijazah terakhir','file_ijazah','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',0,6,11,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(48,5,'Tempat lahir','tempat_lahir','text','data_diri',NULL,NULL,NULL,1,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(49,5,'Tanggal lahir','tanggal_lahir','date','data_diri',NULL,NULL,NULL,1,6,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(50,5,'Jenis kelamin','jenis_kelamin','radio','data_diri','Laki-laki\nPerempuan',NULL,NULL,1,6,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(51,5,'Alamat tinggal','alamat','textarea','data_diri',NULL,NULL,NULL,1,12,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(52,5,'Posisi yang dilamar','posisi','text','data_diri',NULL,NULL,NULL,1,6,5,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(53,5,'Pendidikan terakhir','pendidikan','select','data_diri','SMA/SMK\nD3\nS1\nS2',NULL,NULL,1,6,6,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(54,5,'Pengalaman kerja','pengalaman','textarea','lainnya',NULL,NULL,NULL,0,12,7,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(55,5,'Gereja asal','gereja','text','lainnya',NULL,NULL,NULL,0,6,8,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(56,5,'Curriculum Vitae (CV)','file_cv','file','dokumen',NULL,NULL,'PDF, maks. 4 MB',1,6,9,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(57,5,'Scan KTP','file_ktp','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',1,6,10,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(58,5,'Ijazah terakhir','file_ijazah','file','dokumen',NULL,NULL,'PDF/JPG/PNG, maks. 4 MB',0,6,11,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `registration_fields` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `registration_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `registration_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_logs_registration_id_foreign` (`registration_id`),
  KEY `registration_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `registration_logs_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registration_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `registration_logs` WRITE;
/*!40000 ALTER TABLE `registration_logs` DISABLE KEYS */;
INSERT INTO `registration_logs` VALUES
(1,1,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,2,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-10-05 16:26:36','2026-10-06 16:26:36'),
(3,3,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-10-03 16:26:36','2026-10-06 16:26:36'),
(4,3,3,'verifikasi','Status: Berkas diverifikasi',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,4,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-10-02 16:26:36','2026-10-06 16:26:36'),
(6,4,3,'perbaikan','Status: Perlu perbaikan','Foto Kartu Keluarga kurang jelas. Mohon kirim ulang lewat WhatsApp panitia.',1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,5,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-09-30 16:26:36','2026-10-06 16:26:36'),
(8,5,3,'seleksi','Status: Tes / wawancara',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,6,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-09-26 16:26:36','2026-10-06 16:26:36'),
(10,6,3,'diterima','Status: Diterima',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,7,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-10-01 16:26:36','2026-10-06 16:26:36'),
(12,7,3,'seleksi','Status: Tes / wawancara',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,8,NULL,'baru','Formulir pendaftaran dikirim',NULL,1,'2026-09-24 16:26:36','2026-10-06 16:26:36'),
(14,8,3,'ditolak','Status: Tidak diterima',NULL,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `registration_logs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `registration_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `audience` enum('siswa','staf') NOT NULL DEFAULT 'siswa',
  `level_label` varchar(255) DEFAULT NULL,
  `academic_year` varchar(9) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `steps` text DEFAULT NULL,
  `accepted_message` text DEFAULT NULL,
  `open_at` date DEFAULT NULL,
  `close_at` date DEFAULT NULL,
  `quota` int(10) unsigned DEFAULT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `registration_types` WRITE;
/*!40000 ALTER TABLE `registration_types` DISABLE KEYS */;
INSERT INTO `registration_types` VALUES
(1,'Pendaftaran Siswa SD','siswa-sd','siswa','SD','2027/2028','assets/img/seed/students.jpg','Penerimaan siswa baru jenjang SD tahun ajaran 2027/2028.','<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>','Kartu Keluarga (KK)\nAkta kelahiran\nPasfoto terbaru','Isi formulir online & unggah dokumen\nPanitia memverifikasi berkas (maks. 3 hari kerja)\nTes & wawancara bersama orang tua\nPengumuman hasil lewat menu Cek Status','Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.','2026-09-01','2027-01-31',40,1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Pendaftaran Siswa SMP','siswa-smp','siswa','SMP','2027/2028','assets/img/seed/students.jpg','Penerimaan siswa baru jenjang SMP tahun ajaran 2027/2028.','<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>','Kartu Keluarga (KK)\nAkta kelahiran\nIjazah / SKL SD\nPasfoto terbaru','Isi formulir online & unggah dokumen\nPanitia memverifikasi berkas (maks. 3 hari kerja)\nTes & wawancara bersama orang tua\nPengumuman hasil lewat menu Cek Status','Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.','2026-09-01','2027-01-31',40,1,2,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Pendaftaran Siswa SMA','siswa-sma','siswa','SMA','2027/2028','assets/img/seed/students.jpg','Penerimaan siswa baru jenjang SMA tahun ajaran 2027/2028.','<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>','Kartu Keluarga (KK)\nAkta kelahiran\nIjazah / SKL SMP\nPasfoto terbaru','Isi formulir online & unggah dokumen\nPanitia memverifikasi berkas (maks. 3 hari kerja)\nTes & wawancara bersama orang tua\nPengumuman hasil lewat menu Cek Status','Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.','2026-09-01','2027-01-31',40,1,3,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'Rekrutmen Guru','guru','staf','Guru','2027/2028','assets/img/seed/teacher.jpg','Kesempatan melayani sebagai Guru di Papua Pegunungan.','<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>','Curriculum Vitae (CV)\nScan KTP\nScan ijazah terakhir\nSurat rekomendasi gereja','Isi formulir lamaran & unggah dokumen\nSeleksi berkas oleh tim\nWawancara & micro teaching\nPengumuman hasil lewat menu Cek Status','Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.','2026-09-01','2027-01-31',5,1,4,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,'Rekrutmen Pembina Asrama','pembina-asrama','staf','Pembina Asrama','2027/2028','assets/img/seed/teacher.jpg','Kesempatan melayani sebagai Pembina Asrama di Papua Pegunungan.','<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>','Curriculum Vitae (CV)\nScan KTP\nSurat rekomendasi gereja','Isi formulir lamaran & unggah dokumen\nSeleksi berkas oleh tim\nWawancara & micro teaching\nPengumuman hasil lewat menu Cek Status','Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.','2026-09-01','2027-01-31',5,0,5,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `registration_types` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `registration_type_id` bigint(20) unsigned NOT NULL,
  `number` varchar(30) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`files`)),
  `status` varchar(20) NOT NULL DEFAULT 'baru',
  `schedule_at` datetime DEFAULT NULL,
  `schedule_place` varchar(255) DEFAULT NULL,
  `public_note` text DEFAULT NULL,
  `internal_note` text DEFAULT NULL,
  `handled_by` bigint(20) unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_number_unique` (`number`),
  KEY `registrations_registration_type_id_foreign` (`registration_type_id`),
  KEY `registrations_handled_by_foreign` (`handled_by`),
  KEY `registrations_status_index` (`status`),
  CONSTRAINT `registrations_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `registrations_registration_type_id_foreign` FOREIGN KEY (`registration_type_id`) REFERENCES `registration_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
INSERT INTO `registrations` VALUES
(1,3,'OA-2026-0001','Yosia Kogoya','yosia.kogoya@contoh.id','081234560000','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-10\",\"jenis_kelamin\":\"Laki-laki\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Kogoya\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','baru',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,2,'OA-2026-0002','Elisabet Wenda','elisabet.wenda@contoh.id','081234561111','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-11\",\"jenis_kelamin\":\"Perempuan\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Wenda\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','baru',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-05 16:26:36','2026-10-05 16:26:36'),
(3,3,'OA-2026-0003','Natan Tabuni','natan.tabuni@contoh.id','081234562222','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-12\",\"jenis_kelamin\":\"Laki-laki\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Tabuni\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','verifikasi',NULL,NULL,NULL,NULL,3,NULL,NULL,'2026-10-03 16:26:36','2026-10-03 16:26:36'),
(4,1,'OA-2026-0004','Hana Yikwa','hana.yikwa@contoh.id','081234563333','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-13\",\"jenis_kelamin\":\"Perempuan\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Yikwa\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','perbaikan',NULL,NULL,'Foto Kartu Keluarga kurang jelas. Mohon kirim ulang lewat WhatsApp panitia.',NULL,3,NULL,NULL,'2026-10-02 16:26:36','2026-10-02 16:26:36'),
(5,3,'OA-2026-0005','Mikael Karoba','mikael.karoba@contoh.id','081234564444','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-14\",\"jenis_kelamin\":\"Laki-laki\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Karoba\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','seleksi','2026-10-13 09:00:00','Ruang guru kampus Bokondini',NULL,NULL,3,NULL,NULL,'2026-09-30 16:26:36','2026-09-30 16:26:36'),
(6,2,'OA-2026-0006','Priskila Murib','priskila.murib@contoh.id','081234565555','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-15\",\"jenis_kelamin\":\"Perempuan\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Murib\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','diterima',NULL,NULL,NULL,NULL,3,'2026-10-05 16:26:36',NULL,'2026-09-26 16:26:36','2026-09-26 16:26:36'),
(7,4,'OA-2026-0007','Timotius Gire','timotius.gire@contoh.id','081234566666','{\"tempat_lahir\":\"Wamena\",\"tanggal_lahir\":\"1995-03-12\",\"jenis_kelamin\":\"Laki-laki\",\"alamat\":\"Wamena, Jayawijaya\",\"posisi\":\"Guru Matematika\",\"pendidikan\":\"S1\",\"pengalaman\":\"Mengajar 3 tahun di SMP swasta\",\"gereja\":\"GIDI Wamena\"}','[]','seleksi','2026-10-15 09:00:00','Ruang guru kampus Bokondini',NULL,NULL,3,NULL,NULL,'2026-10-01 16:26:36','2026-10-01 16:26:36'),
(8,1,'OA-2026-0008','Debora Enembe','debora.enembe@contoh.id','081234567777','{\"tempat_lahir\":\"Bokondini\",\"tanggal_lahir\":\"2012-05-17\",\"jenis_kelamin\":\"Perempuan\",\"alamat\":\"Kampung Bokondini, Tolikara\",\"asal_sekolah\":\"SD Inpres Bokondini\",\"kampus\":\"Bokondini\",\"nama_orang_tua\":\"Bapak Enembe\",\"hp_orang_tua\":\"081290001234\",\"pekerjaan_orang_tua\":\"Petani\"}','[]','ditolak',NULL,NULL,NULL,NULL,3,'2026-10-05 16:26:36',NULL,'2026-09-24 16:26:36','2026-09-24 16:26:36');
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'site_name','Ob Anggen School','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'site_tagline','Education and family · Highlands of Papua','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'logo_main','assets/img/seed/logo.png','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'logo_sd','assets/img/seed/logo.png','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,'logo_smp','assets/img/seed/logo.png','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,'logo_sma','assets/img/seed/logo.png','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,'favicon','assets/img/seed/logo.png','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,'meta_description','Website resmi Ob Anggen School, sekolah Kristen di Papua Pegunungan.','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,'footer_about','Sekolah pemuridan yang menyiapkan anak-anak Papua Pegunungan untuk berdampak bagi keluarga, gereja, dan masyarakat.','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(10,'contact_address','Jl. MAF, Bokondini, Kabupaten Tolikara, Papua Pegunungan','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(11,'contact_phone','+62 812-0000-0000','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(12,'contact_whatsapp','081200000000','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(13,'contact_email','obanggenhs@gmail.com','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(14,'maps_url','','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(15,'social_facebook','https://facebook.com/','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(16,'social_instagram','https://instagram.com/','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(17,'social_youtube','https://youtube.com/','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(18,'social_tiktok','','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(19,'color_primary','#7A2B25','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(20,'color_primary_dark','#5C1F1A','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(21,'color_accent','#E39B24','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(22,'color_secondary','#2F5D50','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(23,'color_bg','#FBF5F1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(24,'color_footer','#2A1513','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(25,'font_heading','Lexend','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(26,'font_body','Nunito Sans','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(27,'page_header_image','assets/img/seed/group.jpg','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(28,'layout_sidebar','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(29,'dash_color_master','#7A2B25','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(30,'dash_color_publikasi','#2F5D50','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(31,'dash_color_pendaftaran','#1F4E79','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(32,'reg_prefix','OA','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(33,'reg_notify_email','obanggenhs@gmail.com','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(34,'reg_send_mail','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(35,'reg_success_message','Terima kasih, data pendaftaran sudah kami terima. Simpan nomor pendaftaran untuk memantau status seleksi.','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(36,'hero_title','Sekolah Pemuridan di Papua Pegunungan','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(37,'hero_subtitle','Sekolah Kristen yang menyiapkan anak-anak Papua menghadapi dunia global melalui pemberdayaan keluarga dan keterlibatan masyarakat.','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(38,'hero_image','assets/img/seed/classroom.jpg','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(39,'hero_button','Daftar sekarang','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(40,'hero_button_url','/pendaftaran','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(41,'stat_campus','3','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(42,'stat_students','275','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(43,'stat_teachers','45','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(44,'welcome_title','Selamat datang di Ob Anggen School','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(45,'welcome_text','<p>Ob Anggen adalah sekolah Kristen di Papua Pegunungan yang menyediakan pendidikan unggul untuk mempersiapkan anak-anak menghadapi dunia global, melalui pemberdayaan keluarga dan keterlibatan masyarakat.</p><p>Kami melayani sekitar 275 siswa bersama 45 guru dan staf di tiga kampus: Bokondini, Eragayam, dan Dogobak.</p>','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(46,'welcome_image','assets/img/seed/students.jpg','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(47,'welcome_link','/halaman/sejarah','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(48,'video_title','Tur video kehidupan di Ob Anggen','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(49,'video_text','Lihat keseharian siswa, guru, dan keluarga Ob Anggen di tengah pegunungan Papua.','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(50,'video_url','https://www.youtube.com/watch?v=ysz5S6PUM-U','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(51,'video_bg','assets/img/seed/group.jpg','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(52,'home_show_quick','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(53,'home_show_branches','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(54,'home_show_agenda','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(55,'home_show_news','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(56,'home_show_video','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(57,'home_show_testimonials','1','2026-10-06 16:26:36','2026-10-06 16:26:36'),
(58,'home_blocks_position','bottom','2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `category` varchar(30) NOT NULL DEFAULT 'guru_sd',
  `position_title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `joined_year` year(4) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `duties` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_slug_unique` (`slug`),
  KEY `staff_branch_id_foreign` (`branch_id`),
  CONSTRAINT `staff_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES
(1,1,'Yohana Kogoya','yohana-kogoya','assets/img/seed/teacher.jpg','pimpinan','Kepala Sekolah SD Bokondini',NULL,'S1 Pendidikan','yohana.kogoya@obanggen.sch.id',NULL,2016,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',1,1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,2,'Daniel Tabuni','daniel-tabuni','assets/img/seed/teacher2.jpg','pimpinan','Kepala Sekolah SD Eragayam',NULL,'S1 Pendidikan','daniel.tabuni@obanggen.sch.id',NULL,2017,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',1,2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,3,'Selina Wenda','selina-wenda','assets/img/seed/teacher.jpg','pimpinan','Kepala Sekolah SD Dogobak',NULL,'S1 Pendidikan','selina.wenda@obanggen.sch.id',NULL,2018,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',1,3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,1,'Martha Yikwa','martha-yikwa','assets/img/seed/teacher2.jpg','pimpinan','Kepala Sekolah SMP Bokondini',NULL,'S1 Pendidikan','martha.yikwa@obanggen.sch.id',NULL,2019,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',1,4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(5,1,'Ester Wanimbo','ester-wanimbo','assets/img/seed/teacher.jpg','guru_sma','Guru Matematika','Guru Matematika','S1 Pendidikan','ester.wanimbo@obanggen.sch.id',NULL,2020,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',0,5,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(6,1,'Yakob Enembe','yakob-enembe','assets/img/seed/teacher2.jpg','guru_smp','Guru IPA','Guru IPA','S1 Pendidikan','yakob.enembe@obanggen.sch.id',NULL,2021,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',0,6,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(7,2,'Lince Karoba','lince-karoba','assets/img/seed/teacher.jpg','guru_sd','Guru Kelas 3','Guru Kelas 3','S1 Pendidikan','lince.karoba@obanggen.sch.id',NULL,2016,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',0,7,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(8,1,'Petrus Gire','petrus-gire','assets/img/seed/teacher2.jpg','pembina_asrama','Pembina Asrama Putra',NULL,'S1 Pendidikan','petrus.gire@obanggen.sch.id',NULL,2017,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',0,8,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(9,1,'Agustina Murib','agustina-murib','assets/img/seed/teacher.jpg','staf','Staf Tata Usaha',NULL,'S1 Pendidikan','agustina.murib@obanggen.sch.id',NULL,2018,'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.','Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa',0,9,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES
(1,'Rosa Pagawak','Alumni 2024','Sekolah ini mengajarkan saya untuk tidak mudah menyerah. Guru-guru dan pembina asrama mendampingi kami seperti keluarga sendiri, sampai kami berani bermimpi lebih tinggi.','assets/img/seed/graduate-girl.jpg',1,1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'Ruben Penggu','Alumni 2026','Sekolah ini mengajarkan saya untuk tidak mudah menyerah. Guru-guru dan pembina asrama mendampingi kami seperti keluarga sendiri, sampai kami berani bermimpi lebih tinggi.','assets/img/seed/graduate-boy.jpg',1,2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Maria Wenda','Orang tua siswa','Sekolah ini mengajarkan saya untuk tidak mudah menyerah. Guru-guru dan pembina asrama mendampingi kami seperti keluarga sendiri, sampai kami berani bermimpi lebih tinggi.','assets/img/seed/graduate-girl.jpg',1,3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `uniquenesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uniquenesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `video_url` varchar(255) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniquenesses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `uniquenesses` WRITE;
/*!40000 ALTER TABLE `uniquenesses` DISABLE KEYS */;
INSERT INTO `uniquenesses` VALUES
(1,'Gereja dengan Sekolah','gereja-dengan-sekolah','bi-house-heart','Sekolah tumbuh bersama jemaat lokal sehingga pendidikan dan iman berjalan beriringan.','<p>Sekolah tumbuh bersama jemaat lokal sehingga pendidikan dan iman berjalan beriringan.</p><p>Ruben adalah anak yang pendiam di sekolah. Keluarganya berasal dari latar belakang yang sangat sederhana, namun orang tuanya membayangkan masa depan yang cerah. Ketekunan membantunya bangkit setiap kali menghadapi kesulitan, dan ia terus mencoba lagi sampai berhasil.</p>','assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/students.jpg\",\"assets\\/img\\/seed\\/classroom.jpg\"]','https://www.youtube.com/watch?v=ysz5S6PUM-U',1,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(2,'KGOT','kgot','bi-book','Kelompok belajar firman yang membentuk karakter siswa sejak dini.','<p>Kelompok belajar firman yang membentuk karakter siswa sejak dini.</p><p>Ruben adalah anak yang pendiam di sekolah. Keluarganya berasal dari latar belakang yang sangat sederhana, namun orang tuanya membayangkan masa depan yang cerah. Ketekunan membantunya bangkit setiap kali menghadapi kesulitan, dan ia terus mencoba lagi sampai berhasil.</p>','assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/students.jpg\",\"assets\\/img\\/seed\\/classroom.jpg\"]',NULL,2,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(3,'Life Skill','life-skill','bi-tools','Siswa belajar bertani, beternak, dan keterampilan praktis yang berguna di kampung.','<p>Siswa belajar bertani, beternak, dan keterampilan praktis yang berguna di kampung.</p><p>Ruben adalah anak yang pendiam di sekolah. Keluarganya berasal dari latar belakang yang sangat sederhana, namun orang tuanya membayangkan masa depan yang cerah. Ketekunan membantunya bangkit setiap kali menghadapi kesulitan, dan ia terus mencoba lagi sampai berhasil.</p>','assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/students.jpg\",\"assets\\/img\\/seed\\/classroom.jpg\"]',NULL,3,1,'2026-10-06 16:26:36','2026-10-06 16:26:36'),
(4,'OutTreat','outtreat','bi-tree','Kegiatan di alam terbuka untuk melatih kepemimpinan, kerja sama, dan keberanian.','<p>Kegiatan di alam terbuka untuk melatih kepemimpinan, kerja sama, dan keberanian.</p><p>Ruben adalah anak yang pendiam di sekolah. Keluarganya berasal dari latar belakang yang sangat sederhana, namun orang tuanya membayangkan masa depan yang cerah. Ketekunan membantunya bangkit setiap kali menghadapi kesulitan, dan ia terus mencoba lagi sampai berhasil.</p>','assets/img/seed/family.jpg','[\"assets\\/img\\/seed\\/students.jpg\",\"assets\\/img\\/seed\\/classroom.jpg\"]',NULL,4,1,'2026-10-06 16:26:36','2026-10-06 16:26:36');
/*!40000 ALTER TABLE `uniquenesses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` enum('master','publikasi','pendaftaran') NOT NULL DEFAULT 'publikasi',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_created_by_foreign` (`created_by`),
  CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Master Admin','master@obanggen.sch.id',NULL,'master',NULL,1,NULL,NULL,NULL,'$2y$12$SbUYuT8XmlK84llax80YEOKZIvFYP1sSK99Je9cQGgljixnECv60q',NULL,'2026-10-06 16:26:35','2026-10-06 16:26:35'),
(2,'Admin Publikasi','publikasi@obanggen.sch.id',NULL,'publikasi',NULL,1,NULL,1,NULL,'$2y$12$0VnT53RvsnBWZlsyN..YgeZxv2vIYjtLwp.MkOM.yCChpUL1QEJBu',NULL,'2026-10-06 16:26:35','2026-10-06 16:26:35'),
(3,'Admin Pendaftaran','pendaftaran@obanggen.sch.id',NULL,'pendaftaran',NULL,1,NULL,1,NULL,'$2y$12$ctem9MLBQhwxks.skjstH.5hrittQHyGzE1gepNoVB3aDdejzcCTi',NULL,'2026-10-06 16:26:35','2026-10-06 16:26:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


SET FOREIGN_KEY_CHECKS=1;
