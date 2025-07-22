-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table master_as.absensi_khusus
CREATE TABLE IF NOT EXISTS `absensi_khusus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT 0,
  `lokasi_absen` int(11) DEFAULT 0,
  `remark` text DEFAULT NULL,
  `created_by` int(11) DEFAULT 0,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT 0,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.absensi_khusus: ~0 rows (approximately)

-- Dumping structure for table master_as.account
CREATE TABLE IF NOT EXISTS `account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_code` varchar(20) NOT NULL,
  `account_description` text NOT NULL,
  `account_note` int(11) NOT NULL,
  `account_category` int(11) NOT NULL,
  `account_status` int(11) NOT NULL DEFAULT 1,
  `account_majors_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.account: 7 rows
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`account_id`, `account_code`, `account_description`, `account_note`, `account_category`, `account_status`, `account_majors_id`, `sekolah_id`) VALUES
	(1, '1-10000', 'AKTIVA', 0, 0, 0, 0, 1),
	(2, '2-20000', 'PASIVA', 0, 0, 0, 0, 1),
	(3, '3-30000', 'MODAL', 0, 0, 0, 0, 1),
	(4, '4-40000', 'PENDAPATAN', 0, 0, 0, 0, 1),
	(5, '5-50000', 'BEBAN', 0, 0, 0, 0, 1),
	(6, '6-60000', 'PENDAPATAN LAIN-LAIN', 0, 0, 0, 0, 1),
	(7, '7-70000', 'BEBAN LAIN-LAIN', 0, 0, 0, 0, 1);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;

-- Dumping structure for table master_as.aktivitas
CREATE TABLE IF NOT EXISTS `aktivitas` (
  `aktivitas_id` int(11) NOT NULL AUTO_INCREMENT,
  `aktivitas_standar_id` int(11) DEFAULT NULL,
  `aktivitas_kode` varchar(20) NOT NULL,
  `aktivitas_account_id` int(11) NOT NULL,
  `aktivitas_name` text NOT NULL,
  PRIMARY KEY (`aktivitas_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.aktivitas: ~0 rows (approximately)

-- Dumping structure for table master_as.akun
CREATE TABLE IF NOT EXISTS `akun` (
  `akun_id` int(11) NOT NULL AUTO_INCREMENT,
  `akun_account_id` int(11) NOT NULL,
  `akun_employee_id` int(11) NOT NULL,
  `akun_sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`akun_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.akun: 0 rows
/*!40000 ALTER TABLE `akun` DISABLE KEYS */;
/*!40000 ALTER TABLE `akun` ENABLE KEYS */;

-- Dumping structure for table master_as.akun_ipaymu
CREATE TABLE IF NOT EXISTS `akun_ipaymu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `akun_id` int(11) NOT NULL,
  `tipe` enum('pembayaran','tabungan') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.akun_ipaymu: ~6 rows (approximately)
INSERT INTO `akun_ipaymu` (`id`, `unit_id`, `akun_id`, `tipe`) VALUES
	(1, 4, 204, 'pembayaran'),
	(2, 4, 205, 'tabungan'),
	(3, 2, 206, 'pembayaran'),
	(4, 2, 207, 'tabungan'),
	(5, 3, 208, 'pembayaran'),
	(6, 3, 209, 'tabungan');

-- Dumping structure for table master_as.alumni
CREATE TABLE IF NOT EXISTS `alumni` (
  `alumni_id` int(11) NOT NULL AUTO_INCREMENT,
  `alumni_nis` varchar(45) DEFAULT NULL,
  `alumni_nisn` varchar(45) DEFAULT NULL,
  `alumni_password` varchar(100) DEFAULT NULL,
  `alumni_full_name` varchar(255) DEFAULT NULL,
  `alumni_gender` enum('L','P') DEFAULT NULL,
  `alumni_born_place` varchar(45) DEFAULT NULL,
  `alumni_born_date` date DEFAULT NULL,
  `alumni_img` varchar(255) DEFAULT NULL,
  `alumni_phone` varchar(45) DEFAULT NULL,
  `alumni_hobby` varchar(100) DEFAULT NULL,
  `alumni_address` text DEFAULT NULL,
  `alumni_name_of_mother` varchar(255) DEFAULT NULL,
  `alumni_name_of_father` varchar(255) DEFAULT NULL,
  `alumni_parent_phone` varchar(45) DEFAULT NULL,
  `alumni_unit` int(11) DEFAULT NULL,
  `alumni_kelas` int(11) DEFAULT NULL,
  `alumni_tahun_id` int(11) DEFAULT NULL,
  `alumni_input_date` timestamp NULL DEFAULT current_timestamp(),
  `alumni_last_update` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`alumni_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.alumni: ~0 rows (approximately)

-- Dumping structure for table master_as.anggaran
CREATE TABLE IF NOT EXISTS `anggaran` (
  `anggaran_id` int(11) NOT NULL AUTO_INCREMENT,
  `anggaran_period_id` int(11) DEFAULT NULL,
  `anggaran_account_id` int(11) DEFAULT NULL,
  `anggaran_input_date` timestamp NULL DEFAULT NULL,
  `anggaran_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`anggaran_id`),
  KEY `fk_anggaran_account1_idx` (`anggaran_account_id`),
  KEY `fk_anggaran_period1_idx` (`anggaran_period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.anggaran: ~0 rows (approximately)

-- Dumping structure for table master_as.anggaran_detail
CREATE TABLE IF NOT EXISTS `anggaran_detail` (
  `anggaran_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `anggaran_detail_anggaran_id` int(11) NOT NULL,
  `anggaran_detail_month_id` int(11) NOT NULL,
  `anggaran_detail_nominal` int(11) NOT NULL,
  PRIMARY KEY (`anggaran_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.anggaran_detail: ~0 rows (approximately)

-- Dumping structure for table master_as.area_absensi
CREATE TABLE IF NOT EXISTS `area_absensi` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `nama_area` varchar(100) DEFAULT NULL,
  `longi` varchar(100) DEFAULT NULL,
  `lati` varchar(100) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id_area`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.area_absensi: ~0 rows (approximately)

-- Dumping structure for table master_as.arsip
CREATE TABLE IF NOT EXISTS `arsip` (
  `id_arsip` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis` varchar(15) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama_arsip` varchar(50) NOT NULL,
  `file_arsip` varchar(50) NOT NULL,
  `jumlah` varchar(20) NOT NULL,
  `id_satuan` varchar(20) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `ket_isi` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `permision` varchar(30) NOT NULL,
  PRIMARY KEY (`id_arsip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.arsip: ~0 rows (approximately)

-- Dumping structure for table master_as.ars_jenis
CREATE TABLE IF NOT EXISTS `ars_jenis` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_arsip` varchar(50) NOT NULL,
  `majors_id` varchar(50) NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.ars_jenis: ~0 rows (approximately)

-- Dumping structure for table master_as.ars_lokasi
CREATE TABLE IF NOT EXISTS `ars_lokasi` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(80) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.ars_lokasi: ~0 rows (approximately)

-- Dumping structure for table master_as.ars_pengajuan
CREATE TABLE IF NOT EXISTS `ars_pengajuan` (
  `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT,
  `id_pejabat` varchar(60) NOT NULL,
  `id_satuan` varchar(50) NOT NULL,
  `nama_arsip` varchar(60) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `tujuan` text NOT NULL,
  `file_arsip` varchar(50) DEFAULT NULL,
  `id_jenis` varchar(50) NOT NULL,
  `nonaktif` enum('n','y') NOT NULL,
  PRIMARY KEY (`id_pengajuan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.ars_pengajuan: ~0 rows (approximately)

-- Dumping structure for table master_as.ars_satuan
CREATE TABLE IF NOT EXISTS `ars_satuan` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(30) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.ars_satuan: ~0 rows (approximately)

-- Dumping structure for table master_as.asets
CREATE TABLE IF NOT EXISTS `asets` (
  `id_aset` varchar(128) NOT NULL,
  `kode_aset` varchar(128) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `total_harga` double DEFAULT NULL,
  `kondisi` varchar(128) DEFAULT 'Baik',
  `status_aset` varchar(50) DEFAULT NULL,
  `umur_ekonomis` int(11) DEFAULT NULL,
  `jenis_bantuan` varchar(128) DEFAULT NULL,
  `jenis_aset` varchar(128) DEFAULT 'Berwujud',
  `qr_code` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_aset`),
  KEY `id_barang` (`id_barang`),
  KEY `id_lokasi` (`id_lokasi`),
  CONSTRAINT `asets_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asets_ibfk_2` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_aset` (`id_lokasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.asets: ~0 rows (approximately)

-- Dumping structure for table master_as.banking
CREATE TABLE IF NOT EXISTS `banking` (
  `banking_id` int(11) NOT NULL AUTO_INCREMENT,
  `banking_period_id` int(11) NOT NULL,
  `banking_noref` varchar(50) DEFAULT NULL,
  `banking_account_id` varchar(50) DEFAULT NULL,
  `banking_employee_id` int(11) NOT NULL DEFAULT 0,
  `banking_debit` int(11) NOT NULL,
  `banking_kredit` int(11) NOT NULL,
  `banking_date` date NOT NULL,
  `banking_code` int(11) NOT NULL,
  `banking_student_id` int(11) NOT NULL,
  `banking_note` text NOT NULL,
  `user_user_id` int(11) NOT NULL,
  `ipaymu_no_trans` varchar(50) DEFAULT NULL,
  `ipaymu_status` varchar(50) DEFAULT NULL,
  `banking_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`banking_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.banking: 0 rows
/*!40000 ALTER TABLE `banking` DISABLE KEYS */;
/*!40000 ALTER TABLE `banking` ENABLE KEYS */;

-- Dumping structure for table master_as.banking_limit
CREATE TABLE IF NOT EXISTS `banking_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(100) NOT NULL,
  `limit` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.banking_limit: ~0 rows (approximately)

-- Dumping structure for table master_as.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merek` varchar(128) NOT NULL,
  `tahun_perolehan` year(4) NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `id_jenis` (`id_kategori`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_barang` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.barang: ~0 rows (approximately)

-- Dumping structure for table master_as.barang_guru
CREATE TABLE IF NOT EXISTS `barang_guru` (
  `barang_guru_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_guru_no` varchar(50) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`barang_guru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.barang_guru: 0 rows
/*!40000 ALTER TABLE `barang_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang_guru` ENABLE KEYS */;

-- Dumping structure for table master_as.barang_permintaan_guru
CREATE TABLE IF NOT EXISTS `barang_permintaan_guru` (
  `permintaan_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `barang_guru_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`permintaan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.barang_permintaan_guru: 0 rows
/*!40000 ALTER TABLE `barang_permintaan_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang_permintaan_guru` ENABLE KEYS */;

-- Dumping structure for table master_as.barang_permintaan_siswa
CREATE TABLE IF NOT EXISTS `barang_permintaan_siswa` (
  `permintaan_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `barang_siswa_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`permintaan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.barang_permintaan_siswa: 0 rows
/*!40000 ALTER TABLE `barang_permintaan_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang_permintaan_siswa` ENABLE KEYS */;

-- Dumping structure for table master_as.barang_siswa
CREATE TABLE IF NOT EXISTS `barang_siswa` (
  `barang_siswa_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_siswa_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`barang_siswa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.barang_siswa: 0 rows
/*!40000 ALTER TABLE `barang_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang_siswa` ENABLE KEYS */;

-- Dumping structure for table master_as.batchpayment
CREATE TABLE IF NOT EXISTS `batchpayment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `bp_majors_id` int(11) NOT NULL,
  `bp_period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.batchpayment: ~0 rows (approximately)

-- Dumping structure for table master_as.batch_item
CREATE TABLE IF NOT EXISTS `batch_item` (
  `batch_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_item_payment_id` int(11) DEFAULT NULL,
  `batch_item_batchpayment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`batch_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.batch_item: ~0 rows (approximately)

-- Dumping structure for table master_as.bebas
CREATE TABLE IF NOT EXISTS `bebas` (
  `bebas_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_student_id` int(11) DEFAULT NULL,
  `payment_payment_id` int(11) DEFAULT NULL,
  `bebas_bill` decimal(10,0) DEFAULT NULL,
  `bebas_diskon` decimal(10,0) NOT NULL DEFAULT 0,
  `bebas_total_pay` decimal(10,0) DEFAULT 0,
  `sekolah_id` char(100) NOT NULL,
  `bebas_input_date` date DEFAULT NULL,
  `bebas_last_update` date DEFAULT NULL,
  PRIMARY KEY (`bebas_id`),
  KEY `fk_bebas_payment1_idx` (`payment_payment_id`),
  KEY `fk_bebas_student1_idx` (`student_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bebas: ~0 rows (approximately)

-- Dumping structure for table master_as.bebas_jurnal
CREATE TABLE IF NOT EXISTS `bebas_jurnal` (
  `bebas_jurnal_id` int(11) NOT NULL AUTO_INCREMENT,
  `bebas_jurnal_noref` varchar(50) NOT NULL,
  `bebas_jurnal_account_code` varchar(30) NOT NULL,
  `bebas_jurnal_bebas_id` int(11) NOT NULL,
  `bebas_jurnal_nominal` int(11) NOT NULL,
  PRIMARY KEY (`bebas_jurnal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.bebas_jurnal: ~0 rows (approximately)

-- Dumping structure for table master_as.bebas_pay
CREATE TABLE IF NOT EXISTS `bebas_pay` (
  `bebas_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `bebas_bebas_id` int(11) DEFAULT NULL,
  `bebas_pay_noref` varchar(50) DEFAULT NULL,
  `bebas_pay_account_id` int(11) DEFAULT NULL,
  `bebas_pay_number` varchar(100) DEFAULT NULL,
  `bebas_pay_bill` decimal(10,0) DEFAULT NULL,
  `bebas_pay_desc` varchar(100) DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `bebas_pay_input_date` date DEFAULT NULL,
  `bebas_pay_last_update` timestamp NULL DEFAULT NULL,
  `ipaymu_no_trans` varchar(100) DEFAULT NULL,
  `ipaymu_status` varchar(100) DEFAULT NULL,
  `flip_no_trans` varchar(100) DEFAULT NULL,
  `flip_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bebas_pay_id`),
  KEY `fk_bebas_pay_bebas1_idx` (`bebas_bebas_id`),
  KEY `fk_bebas_pay_users1_idx` (`user_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bebas_pay: ~0 rows (approximately)
INSERT INTO `bebas_pay` (`bebas_pay_id`, `bebas_bebas_id`, `bebas_pay_noref`, `bebas_pay_account_id`, `bebas_pay_number`, `bebas_pay_bill`, `bebas_pay_desc`, `user_user_id`, `sekolah_id`, `bebas_pay_input_date`, `bebas_pay_last_update`, `ipaymu_no_trans`, `ipaymu_status`, `flip_no_trans`, `flip_status`) VALUES
	(1, 5, 'SPSD2025020517012501', 14, '20250100001', 150000, 'OK', 4662, '', '2025-01-16', '2025-01-15 10:00:00', NULL, NULL, NULL, NULL);

-- Dumping structure for table master_as.bebas_pay_mobile
CREATE TABLE IF NOT EXISTS `bebas_pay_mobile` (
  `bebas_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `bebas_bebas_id` int(11) DEFAULT NULL,
  `bebas_pay_noref` varchar(50) DEFAULT NULL,
  `bebas_pay_account_id` int(11) DEFAULT NULL,
  `bebas_pay_number` varchar(100) DEFAULT NULL,
  `bebas_pay_bill` decimal(10,0) DEFAULT NULL,
  `bebas_pay_desc` varchar(100) DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `bebas_pay_input_date` date DEFAULT NULL,
  `bebas_pay_last_update` timestamp NULL DEFAULT NULL,
  `flip_no_trans` varchar(100) DEFAULT NULL,
  `flip_status` varchar(100) DEFAULT NULL,
  `ipaymu_status` varchar(100) DEFAULT NULL,
  `ipaymu_no_trans` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bebas_pay_id`),
  KEY `fk_bebas_pay_bebas1_idx` (`bebas_bebas_id`),
  KEY `fk_bebas_pay_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.bebas_pay_mobile: ~0 rows (approximately)

-- Dumping structure for table master_as.bebas_pay_tmp
CREATE TABLE IF NOT EXISTS `bebas_pay_tmp` (
  `bebas_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `bebas_bebas_id` int(11) DEFAULT NULL,
  `bebas_pay_noref` varchar(50) DEFAULT NULL,
  `bebas_pay_account_id` int(11) DEFAULT NULL,
  `bebas_pay_number` varchar(100) DEFAULT NULL,
  `bebas_pay_bill` decimal(10,0) DEFAULT NULL,
  `bebas_pay_desc` varchar(100) DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `bebas_pay_input_date` date DEFAULT NULL,
  `bebas_pay_last_update` timestamp NULL DEFAULT NULL,
  `ipaymu_no_trans` varchar(100) DEFAULT NULL,
  `ipaymu_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bebas_pay_id`),
  KEY `fk_bebas_pay_tmp_bebas1_idx` (`bebas_bebas_id`),
  KEY `fk_bebas_pay_tmp_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bebas_pay_tmp: ~0 rows (approximately)

-- Dumping structure for table master_as.bmi_inquiry
CREATE TABLE IF NOT EXISTS `bmi_inquiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CCY` varchar(50) DEFAULT NULL,
  `VANO` varchar(50) DEFAULT NULL,
  `TRXDATE` varchar(50) DEFAULT NULL,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `CHANNELID` varchar(50) DEFAULT NULL,
  `REFNO` varchar(50) DEFAULT NULL,
  `BILL` varchar(50) DEFAULT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `DESCRIPTION2` text DEFAULT NULL,
  `ERR` varchar(50) DEFAULT NULL,
  `METHOD` varchar(50) DEFAULT NULL,
  `CUSTNAME` varchar(50) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bmi_inquiry: ~0 rows (approximately)

-- Dumping structure for table master_as.bmi_log
CREATE TABLE IF NOT EXISTS `bmi_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt` text DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bmi_log: ~0 rows (approximately)

-- Dumping structure for table master_as.bmi_payment
CREATE TABLE IF NOT EXISTS `bmi_payment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TRXDATE` varchar(100) DEFAULT NULL,
  `CCY` varchar(100) DEFAULT NULL,
  `REFNO` varchar(100) DEFAULT NULL,
  `BILL` varchar(100) DEFAULT NULL,
  `PAYMENT` varchar(100) DEFAULT NULL,
  `CHANNELID` varchar(100) DEFAULT NULL,
  `METHOD` varchar(100) DEFAULT NULL,
  `VANO` varchar(100) DEFAULT NULL,
  `CUSTNAME` varchar(100) DEFAULT NULL,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `DESCRIPTION2` varchar(100) DEFAULT NULL,
  `DESCRIPTION` varchar(100) DEFAULT NULL,
  `ERR` varchar(100) DEFAULT NULL,
  `IP_SOURCE` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `TRXDATE_REFNO_VANO` (`TRXDATE`,`REFNO`,`VANO`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bmi_payment: ~0 rows (approximately)

-- Dumping structure for table master_as.bmi_refersal
CREATE TABLE IF NOT EXISTS `bmi_refersal` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CCY` varchar(100) DEFAULT NULL,
  `CUSTNAME` varchar(100) DEFAULT NULL,
  `TRXDATE` varchar(100) DEFAULT NULL,
  `REFNO` varchar(100) DEFAULT NULL,
  `BILL` varchar(100) DEFAULT NULL,
  `CHANNELID` varchar(100) DEFAULT NULL,
  `METHOD` varchar(100) DEFAULT NULL,
  `PYMTDATE` varchar(100) DEFAULT NULL,
  `PAYMENT` varchar(100) DEFAULT NULL,
  `VANO` varchar(100) DEFAULT NULL,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `ERR` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `REFNO_PYMTDATE_VANO` (`REFNO`,`PYMTDATE`,`VANO`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bmi_refersal: ~0 rows (approximately)

-- Dumping structure for table master_as.bmi_transaksi
CREATE TABLE IF NOT EXISTS `bmi_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `va_transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.bmi_transaksi: ~0 rows (approximately)

-- Dumping structure for table master_as.bni_callbacks
CREATE TABLE IF NOT EXISTS `bni_callbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(50) NOT NULL,
  `trx_amount` decimal(10,2) NOT NULL,
  `virtual_account` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `payment_amount` decimal(10,0) NOT NULL,
  `cumulative_payment_amount` decimal(11,0) NOT NULL,
  `payment_ntb` char(6) NOT NULL,
  `datetime_payment` datetime NOT NULL,
  `datetime_payment_iso8601` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.bni_callbacks: ~0 rows (approximately)

-- Dumping structure for table master_as.bni_config
CREATE TABLE IF NOT EXISTS `bni_config` (
  `mode` enum('DEV','PROD') NOT NULL DEFAULT 'DEV',
  `client_id` varchar(20) NOT NULL,
  `secret_key` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `expired_time` tinyint(4) NOT NULL DEFAULT 24,
  `majors_id` int(11) DEFAULT NULL,
  `is_active` enum('1','0') DEFAULT '0',
  PRIMARY KEY (`mode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.bni_config: ~0 rows (approximately)

-- Dumping structure for table master_as.bni_trx
CREATE TABLE IF NOT EXISTS `bni_trx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `client_id` int(11) NOT NULL,
  `trx_id` varchar(50) NOT NULL,
  `noref` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `trx_amount` decimal(10,2) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `billing_type` char(1) NOT NULL,
  `trx_status` enum('READY','PENDING','PAID','EXPIRED') NOT NULL DEFAULT 'PENDING',
  `datetime_expired` datetime NOT NULL,
  `virtual_account` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_amount` decimal(10,0) NOT NULL,
  `cumulative_payment_amount` decimal(11,0) NOT NULL,
  `payment_ntb` char(6) NOT NULL,
  `datetime_payment` datetime NOT NULL,
  `datetime_payment_iso8601` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.bni_trx: ~0 rows (approximately)

-- Dumping structure for table master_as.budgeting
CREATE TABLE IF NOT EXISTS `budgeting` (
  `budgeting_id` int(11) NOT NULL AUTO_INCREMENT,
  `budgeting_worksheet_id` int(11) NOT NULL,
  `budgeting_aktivitas_id` int(11) NOT NULL,
  `budgeting_uraian` text NOT NULL,
  `budgeting_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `budgeting_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`budgeting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.budgeting: ~0 rows (approximately)

-- Dumping structure for table master_as.budget_detail
CREATE TABLE IF NOT EXISTS `budget_detail` (
  `budget_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `budget_detail_budgeting_id` int(11) NOT NULL,
  `budget_detail_bulan_id` int(11) NOT NULL,
  `budget_detail_satuan_id` int(11) NOT NULL,
  `budget_detail_nominal` int(11) NOT NULL,
  `budget_detail_jumlah` int(11) NOT NULL,
  `budget_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `budget_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`budget_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.budget_detail: ~0 rows (approximately)

-- Dumping structure for table master_as.bulan
CREATE TABLE IF NOT EXISTS `bulan` (
  `bulan_id` int(11) NOT NULL AUTO_INCREMENT,
  `bulan_noref` varchar(50) DEFAULT NULL,
  `bulan_account_id` int(11) DEFAULT NULL,
  `student_student_id` int(11) DEFAULT NULL,
  `payment_payment_id` int(11) DEFAULT NULL,
  `month_month_id` int(11) DEFAULT NULL,
  `bulan_bill` decimal(10,0) DEFAULT NULL,
  `bulan_status` tinyint(1) DEFAULT 0,
  `bulan_number_pay` varchar(100) DEFAULT NULL,
  `bulan_date_pay` date DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `bulan_input_date` timestamp NULL DEFAULT NULL,
  `bulan_last_update` timestamp NULL DEFAULT NULL,
  `ipaymu_no_trans` varchar(50) DEFAULT NULL,
  `ipaymu_status` varchar(50) DEFAULT NULL,
  `flip_no_trans` varchar(50) DEFAULT NULL,
  `flip_status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bulan_id`),
  KEY `fk_bulan_payment1_idx` (`payment_payment_id`),
  KEY `fk_bulan_month1_idx` (`month_month_id`),
  KEY `fk_bulan_student1_idx` (`student_student_id`),
  KEY `fk_bulan_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.bulan: ~0 rows (approximately)

-- Dumping structure for table master_as.bulan_jurnal
CREATE TABLE IF NOT EXISTS `bulan_jurnal` (
  `bulan_jurnal_id` int(11) NOT NULL AUTO_INCREMENT,
  `bulan_jurnal_noref` varchar(50) NOT NULL,
  `bulan_jurnal_account_code` varchar(30) NOT NULL,
  `bulan_jurnal_bulan_id` int(11) NOT NULL,
  `bulan_jurnal_nominal` int(11) NOT NULL,
  PRIMARY KEY (`bulan_jurnal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.bulan_jurnal: ~0 rows (approximately)

-- Dumping structure for table master_as.callback_donasi
CREATE TABLE IF NOT EXISTS `callback_donasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `sid` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `status_code` varchar(100) DEFAULT NULL,
  `via` varchar(100) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `va` varchar(100) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `fees` varchar(100) DEFAULT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(100) DEFAULT NULL,
  `buyer_phone` varchar(100) DEFAULT NULL,
  `is_escrow` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.callback_donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.cards
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(100) NOT NULL,
  `rfid` varchar(100) DEFAULT NULL,
  `pin` varchar(100) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.cards: ~0 rows (approximately)

-- Dumping structure for table master_as.ci_sessions
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.ci_sessions: ~0 rows (approximately)

-- Dumping structure for table master_as.class
CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(45) DEFAULT NULL,
  `majors_majors_id` int(11) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.class: ~0 rows (approximately)

-- Dumping structure for table master_as.data_absensi
CREATE TABLE IF NOT EXISTS `data_absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `jenis_absen` varchar(100) DEFAULT NULL,
  `area_absen` int(11) NOT NULL,
  `statushadir` varchar(50) DEFAULT NULL,
  `bulan` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `jam` timestamp NULL DEFAULT current_timestamp(),
  `longi` varchar(100) DEFAULT NULL,
  `lati` varchar(100) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `catatan_absen` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  `keterlambatan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tanggal` (`tanggal`),
  KEY `time` (`time`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `bulan` (`bulan`),
  KEY `FK_bmt_data_absensi_area_absensi` (`area_absen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_absensi: ~0 rows (approximately)

-- Dumping structure for table master_as.data_absensi_pelajaran
CREATE TABLE IF NOT EXISTS `data_absensi_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `jenis_absen` varchar(100) DEFAULT NULL,
  `statushadir` varchar(50) DEFAULT NULL,
  `keterlambatan` text DEFAULT NULL,
  `area_absen` int(11) NOT NULL,
  `bulan` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `jam` timestamp NULL DEFAULT current_timestamp(),
  `longi` varchar(100) DEFAULT NULL,
  `lati` varchar(100) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `catatan_absen` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  `id_class` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `id_schedule` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_absensi_pelajaran: ~0 rows (approximately)

-- Dumping structure for table master_as.data_aset
CREATE TABLE IF NOT EXISTS `data_aset` (
  `id_aset` int(11) NOT NULL AUTO_INCREMENT,
  `nama_aset` varchar(128) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  PRIMARY KEY (`id_aset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.data_aset: ~0 rows (approximately)

-- Dumping structure for table master_as.data_briva_notif
CREATE TABLE IF NOT EXISTS `data_briva_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt` text DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_briva_notif: ~0 rows (approximately)

-- Dumping structure for table master_as.data_flip_callback
CREATE TABLE IF NOT EXISTS `data_flip_callback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `bill_id` varchar(100) DEFAULT NULL,
  `bill_link` varchar(255) DEFAULT NULL,
  `bill_title` varchar(255) DEFAULT NULL,
  `sender_bank_type` varchar(100) DEFAULT NULL,
  `sender_bank` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_flip_callback: ~0 rows (approximately)

-- Dumping structure for table master_as.data_flip_callback_donasi
CREATE TABLE IF NOT EXISTS `data_flip_callback_donasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `bill_id` varchar(100) DEFAULT NULL,
  `bill_link` varchar(255) DEFAULT NULL,
  `bill_title` varchar(255) DEFAULT NULL,
  `sender_bank_type` varchar(100) DEFAULT NULL,
  `sender_bank` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_flip_callback_donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.data_flip_callback_tabungan
CREATE TABLE IF NOT EXISTS `data_flip_callback_tabungan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `bill_id` varchar(100) DEFAULT NULL,
  `bill_link` varchar(255) DEFAULT NULL,
  `bill_title` varchar(255) DEFAULT NULL,
  `sender_bank_type` varchar(100) DEFAULT NULL,
  `sender_bank` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_flip_callback_tabungan: ~0 rows (approximately)

-- Dumping structure for table master_as.data_flip_channel
CREATE TABLE IF NOT EXISTS `data_flip_channel` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_channel` varchar(250) NOT NULL DEFAULT '',
  `bank` varchar(250) NOT NULL DEFAULT '',
  `logo` varchar(250) NOT NULL DEFAULT '',
  `status` varchar(250) NOT NULL DEFAULT 'N',
  `kode` varchar(250) NOT NULL DEFAULT 'N',
  `cara_bayar` text DEFAULT NULL,
  `fee` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_flip_channel: ~0 rows (approximately)

-- Dumping structure for table master_as.data_hari_libur
CREATE TABLE IF NOT EXISTS `data_hari_libur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hari` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_hari_libur: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_callback
CREATE TABLE IF NOT EXISTS `data_ipaymu_callback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `sid` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `status_code` varchar(100) DEFAULT NULL,
  `via` varchar(100) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `va` varchar(100) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `fees` varchar(100) DEFAULT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(100) DEFAULT NULL,
  `buyer_phone` varchar(100) DEFAULT NULL,
  `is_escrow` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_ipaymu_callback: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_callback_donasi
CREATE TABLE IF NOT EXISTS `data_ipaymu_callback_donasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `sid` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `status_code` varchar(100) DEFAULT NULL,
  `via` varchar(100) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `va` varchar(100) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `fees` varchar(100) DEFAULT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(100) DEFAULT NULL,
  `buyer_phone` varchar(100) DEFAULT NULL,
  `is_escrow` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_ipaymu_callback_donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_callback_tabungan
CREATE TABLE IF NOT EXISTS `data_ipaymu_callback_tabungan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `sid` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `status_code` varchar(100) DEFAULT NULL,
  `via` varchar(100) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `va` varchar(100) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `fees` varchar(100) DEFAULT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(100) DEFAULT NULL,
  `buyer_phone` varchar(100) DEFAULT NULL,
  `is_escrow` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_ipaymu_callback_tabungan: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_channel
CREATE TABLE IF NOT EXISTS `data_ipaymu_channel` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_channel` varchar(250) NOT NULL DEFAULT '',
  `bank` varchar(250) NOT NULL DEFAULT '',
  `logo` varchar(250) NOT NULL DEFAULT '',
  `status` varchar(250) NOT NULL DEFAULT 'N',
  `kode` varchar(250) NOT NULL DEFAULT 'N',
  `fee` int(11) NOT NULL DEFAULT 0,
  `cara_bayar` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_ipaymu_channel: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_new
CREATE TABLE IF NOT EXISTS `data_ipaymu_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(100) DEFAULT NULL,
  `TransactionId` varchar(100) DEFAULT NULL,
  `Fee` varchar(100) DEFAULT NULL,
  `Expired` varchar(100) DEFAULT NULL,
  `PaymentNo` varchar(100) DEFAULT NULL,
  `PaymentName` varchar(100) DEFAULT NULL,
  `Total` varchar(100) DEFAULT NULL,
  `Via` varchar(100) DEFAULT NULL,
  `Channel` varchar(100) DEFAULT NULL,
  `paymentChannel` varchar(100) DEFAULT NULL,
  `paymentMethod` varchar(100) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `jumlah` varchar(100) DEFAULT NULL,
  `nominal` varchar(100) DEFAULT NULL,
  `bulan_id` varchar(100) DEFAULT NULL,
  `student_id` varchar(100) DEFAULT NULL,
  `nama_tagihan` varchar(150) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'BARU',
  `remark` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table master_as.data_ipaymu_new: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_notif
CREATE TABLE IF NOT EXISTS `data_ipaymu_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt` text DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_ipaymu_notif: ~0 rows (approximately)

-- Dumping structure for table master_as.data_ipaymu_notif_log
CREATE TABLE IF NOT EXISTS `data_ipaymu_notif_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table master_as.data_ipaymu_notif_log: ~0 rows (approximately)

-- Dumping structure for table master_as.data_log_callback_bni
CREATE TABLE IF NOT EXISTS `data_log_callback_bni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(100) DEFAULT NULL,
  `bill_id` varchar(100) DEFAULT NULL,
  `bill_link` varchar(255) DEFAULT NULL,
  `bill_title` varchar(255) DEFAULT NULL,
  `sender_bank_type` varchar(100) DEFAULT NULL,
  `sender_bank` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.data_log_callback_bni: ~0 rows (approximately)

-- Dumping structure for table master_as.data_waktu
CREATE TABLE IF NOT EXISTS `data_waktu` (
  `data_waktu_id` int(11) NOT NULL AUTO_INCREMENT,
  `data_waktu_majors_id` int(11) NOT NULL,
  `data_waktu_day_id` int(11) NOT NULL,
  `data_waktu_masuk` time NOT NULL,
  `data_waktu_pulang` time NOT NULL,
  PRIMARY KEY (`data_waktu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_waktu: ~7 rows (approximately)
INSERT INTO `data_waktu` (`data_waktu_id`, `data_waktu_majors_id`, `data_waktu_day_id`, `data_waktu_masuk`, `data_waktu_pulang`) VALUES
	(71, 2, 1, '00:00:00', '00:00:00'),
	(72, 2, 2, '07:00:00', '16:00:00'),
	(73, 2, 3, '07:00:00', '16:00:00'),
	(74, 2, 4, '07:00:00', '16:00:00'),
	(75, 2, 5, '07:00:00', '16:00:00'),
	(76, 2, 6, '07:00:00', '16:00:00'),
	(77, 2, 7, '07:00:00', '16:00:00');

-- Dumping structure for table master_as.data_waktu_siswa
CREATE TABLE IF NOT EXISTS `data_waktu_siswa` (
  `data_waktu_id` int(11) NOT NULL AUTO_INCREMENT,
  `data_waktu_majors_id` int(11) NOT NULL,
  `data_waktu_day_id` int(11) NOT NULL,
  `data_waktu_masuk` time NOT NULL,
  `data_waktu_pulang` time NOT NULL,
  PRIMARY KEY (`data_waktu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.data_waktu_siswa: ~0 rows (approximately)

-- Dumping structure for table master_as.day
CREATE TABLE IF NOT EXISTS `day` (
  `day_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_name` text NOT NULL,
  PRIMARY KEY (`day_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.day: ~7 rows (approximately)
INSERT INTO `day` (`day_id`, `day_name`) VALUES
	(1, 'Senin'),
	(2, 'Selasa'),
	(3, 'Rabu'),
	(4, 'Kamis'),
	(5, 'Jumat'),
	(6, 'Sabtu'),
	(7, 'Minggu');

-- Dumping structure for table master_as.debit
CREATE TABLE IF NOT EXISTS `debit` (
  `debit_id` int(11) NOT NULL AUTO_INCREMENT,
  `debit_date` date DEFAULT NULL,
  `debit_desc` varchar(100) DEFAULT NULL,
  `debit_value` decimal(10,0) DEFAULT NULL,
  `debit_account_id` int(11) NOT NULL,
  `debit_kas_noref` varchar(50) NOT NULL,
  `debit_kas_account_id` int(11) NOT NULL,
  `debit_tax_id` int(11) NOT NULL,
  `debit_item_id` int(11) NOT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `debit_input_date` date DEFAULT NULL,
  `debit_last_update` date DEFAULT NULL,
  PRIMARY KEY (`debit_id`),
  KEY `fk_jurnal_debit_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.debit: ~0 rows (approximately)

-- Dumping structure for table master_as.debit_temp
CREATE TABLE IF NOT EXISTS `debit_temp` (
  `debit_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `debit_temp_majors_id` int(11) NOT NULL,
  `debit_temp_date` date NOT NULL,
  `debit_temp_noref` varchar(50) NOT NULL,
  `debit_temp_kas_account_id` int(11) NOT NULL,
  `debit_temp_account_id` int(11) NOT NULL,
  `debit_temp_desc` varchar(50) NOT NULL,
  `debit_temp_value` decimal(11,0) NOT NULL,
  `debit_temp_tax_id` int(11) NOT NULL,
  `debit_temp_item_id` int(11) NOT NULL,
  `user_user_id` int(11) NOT NULL,
  `debit_temp_input_date` date NOT NULL,
  `debit_temp_last_update` date NOT NULL,
  PRIMARY KEY (`debit_temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.debit_temp: 0 rows
/*!40000 ALTER TABLE `debit_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `debit_temp` ENABLE KEYS */;

-- Dumping structure for table master_as.dokumen
CREATE TABLE IF NOT EXISTS `dokumen` (
  `dokumen_id` int(11) NOT NULL AUTO_INCREMENT,
  `dokumen_file` varchar(50) NOT NULL,
  `dokumen_note` text NOT NULL,
  `dokumen_employee_id` int(11) NOT NULL,
  PRIMARY KEY (`dokumen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.dokumen: ~0 rows (approximately)

-- Dumping structure for table master_as.donasi
CREATE TABLE IF NOT EXISTS `donasi` (
  `donasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `program_program_id` int(11) NOT NULL,
  `donasi_nominal` int(11) NOT NULL DEFAULT 10000,
  `donasi_status` tinyint(1) NOT NULL DEFAULT 0,
  `donasi_ref_id` varchar(10) NOT NULL,
  `donasi_name` varchar(100) NOT NULL,
  `donasi_alamat` varchar(100) DEFAULT NULL,
  `donasi_hp` varchar(20) NOT NULL,
  `donasi_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`donasi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.employee
CREATE TABLE IF NOT EXISTS `employee` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_nip` varchar(20) DEFAULT NULL,
  `employee_name` varchar(50) DEFAULT NULL,
  `employee_gender` char(2) DEFAULT NULL,
  `employee_position_id` int(11) DEFAULT NULL,
  `employee_majors_id` int(11) DEFAULT NULL,
  `employee_category` int(11) DEFAULT NULL,
  `employee_born_place` varchar(20) DEFAULT NULL,
  `employee_born_date` varchar(50) DEFAULT NULL,
  `employee_strata` char(5) DEFAULT NULL,
  `employee_phone` varchar(15) DEFAULT NULL,
  `employee_address` text DEFAULT NULL,
  `employee_photo` text DEFAULT NULL,
  `employee_start` varchar(50) DEFAULT NULL,
  `employee_end` varchar(50) DEFAULT NULL,
  `employee_status` int(11) DEFAULT 1,
  `employee_email` varchar(50) DEFAULT NULL,
  `employee_password` varchar(100) DEFAULT NULL,
  `sekolah_id` int(11) DEFAULT NULL,
  `employee_input_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `employee_last_update` varchar(50) DEFAULT NULL,
  `status_absen` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status_absen_temp` enum('LOCK','UNLOCK') NOT NULL DEFAULT 'LOCK',
  `area_absen` varchar(50) DEFAULT '1',
  `jarak_radius` int(11) NOT NULL DEFAULT 500,
  `status_present` enum('0','1','2') NOT NULL DEFAULT '1',
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.employee: 0 rows
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;

-- Dumping structure for table master_as.employee_achievement
CREATE TABLE IF NOT EXISTS `employee_achievement` (
  `achievement_id` int(11) NOT NULL AUTO_INCREMENT,
  `achievement_year` char(5) NOT NULL,
  `achievement_name` varchar(50) NOT NULL,
  `achievement_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`achievement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.employee_achievement: 0 rows
/*!40000 ALTER TABLE `employee_achievement` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_achievement` ENABLE KEYS */;

-- Dumping structure for table master_as.employee_education
CREATE TABLE IF NOT EXISTS `employee_education` (
  `education_id` int(11) NOT NULL AUTO_INCREMENT,
  `education_start` char(5) NOT NULL,
  `education_end` char(5) NOT NULL,
  `education_name` varchar(50) NOT NULL,
  `education_location` varchar(50) NOT NULL,
  `education_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`education_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.employee_education: 0 rows
/*!40000 ALTER TABLE `employee_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_education` ENABLE KEYS */;

-- Dumping structure for table master_as.employee_fam
CREATE TABLE IF NOT EXISTS `employee_fam` (
  `fam_id` int(11) NOT NULL AUTO_INCREMENT,
  `fam_name` varchar(50) NOT NULL,
  `fam_desc` int(11) NOT NULL,
  `fam_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`fam_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.employee_fam: 0 rows
/*!40000 ALTER TABLE `employee_fam` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_fam` ENABLE KEYS */;

-- Dumping structure for table master_as.entry_detail
CREATE TABLE IF NOT EXISTS `entry_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `inventoryCode` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `volume` int(11) NOT NULL DEFAULT 0,
  `unitPrice` int(11) NOT NULL DEFAULT 0,
  `totalPrice` int(11) NOT NULL DEFAULT 0,
  `executionTime` varchar(30) DEFAULT NULL,
  `status` enum('NEW','SUBMITED','APPROVED','CREATED') NOT NULL DEFAULT 'CREATED',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.entry_detail: ~0 rows (approximately)

-- Dumping structure for table master_as.faq
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pertanyaan` text DEFAULT NULL,
  `jawaban` text DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.faq: ~0 rows (approximately)

-- Dumping structure for table master_as.flip_donasi
CREATE TABLE IF NOT EXISTS `flip_donasi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.flip_donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.flip_transaksi
CREATE TABLE IF NOT EXISTS `flip_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.flip_transaksi: ~0 rows (approximately)

-- Dumping structure for table master_as.flip_transaksi_tabungan
CREATE TABLE IF NOT EXISTS `flip_transaksi_tabungan` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.flip_transaksi_tabungan: ~0 rows (approximately)

-- Dumping structure for table master_as.gaji
CREATE TABLE IF NOT EXISTS `gaji` (
  `gaji_id` int(11) NOT NULL AUTO_INCREMENT,
  `gaji_pokok` int(11) NOT NULL,
  `gaji_potongan` int(11) NOT NULL,
  `gaji_jumlah` int(11) NOT NULL,
  `gaji_month_id` int(11) NOT NULL,
  `gaji_period_id` int(11) NOT NULL,
  `gaji_catatan` text NOT NULL,
  `gaji_employee_id` int(11) NOT NULL,
  `gaji_tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_user_id` int(11) NOT NULL,
  PRIMARY KEY (`gaji_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.gaji: 0 rows
/*!40000 ALTER TABLE `gaji` DISABLE KEYS */;
/*!40000 ALTER TABLE `gaji` ENABLE KEYS */;

-- Dumping structure for table master_as.gaji_default
CREATE TABLE IF NOT EXISTS `gaji_default` (
  `default_gaji_id` int(11) NOT NULL AUTO_INCREMENT,
  `default_gaji_name` varchar(225) DEFAULT NULL,
  `default_gaji_mode` int(11) DEFAULT NULL,
  `default_gaji_majors_id` int(11) NOT NULL,
  PRIMARY KEY (`default_gaji_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.gaji_default: 7 rows
/*!40000 ALTER TABLE `gaji_default` DISABLE KEYS */;
INSERT INTO `gaji_default` (`default_gaji_id`, `default_gaji_name`, `default_gaji_mode`, `default_gaji_majors_id`) VALUES
	(1, 'Gaji Pokok', 0, 2),
	(2, 'Tunjangan Kehadiran', 0, 2),
	(3, 'Tunjangan Harian', 0, 2),
	(4, 'BPJS', 1, 2),
	(5, 'Perjalanan Dinas', 0, 2),
	(6, 'Potongan', 1, 2),
	(7, 'Koperasi', 1, 2);
/*!40000 ALTER TABLE `gaji_default` ENABLE KEYS */;

-- Dumping structure for table master_as.gaji_setting
CREATE TABLE IF NOT EXISTS `gaji_setting` (
  `gaji_setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gaji_setting_name` varchar(225) NOT NULL,
  `gaji_setting_default_id` int(11) NOT NULL,
  `gaji_majors_id` int(11) NOT NULL,
  `gaji_setting_nominal` int(11) NOT NULL DEFAULT 0,
  `gaji_setting_employee_id` int(11) NOT NULL,
  `gaji_setting_date_input` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gaji_setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.gaji_setting: 12 rows
/*!40000 ALTER TABLE `gaji_setting` DISABLE KEYS */;
INSERT INTO `gaji_setting` (`gaji_setting_id`, `gaji_setting_name`, `gaji_setting_default_id`, `gaji_majors_id`, `gaji_setting_nominal`, `gaji_setting_employee_id`, `gaji_setting_date_input`) VALUES
	(1, 'Gaji Pokok', 1, 2, 1500000, 1, '2025-04-25 10:58:07'),
	(2, 'Tunjangan Kehadiran', 2, 2, 250000, 1, '2025-04-25 10:58:07'),
	(3, 'Tunjangan Harian', 3, 2, 300000, 1, '2025-04-25 10:58:07'),
	(4, 'Perjalanan Dinas', 5, 2, 100000, 1, '2025-04-25 10:58:07'),
	(5, 'Gaji Pokok', 1, 2, 3000000, 2, '2025-04-28 16:44:07'),
	(6, 'Tunjangan Kehadiran', 2, 2, 225000, 2, '2025-04-28 16:44:07'),
	(7, 'Tunjangan Harian', 3, 2, 0, 2, '2025-04-28 16:44:07'),
	(8, 'Perjalanan Dinas', 5, 2, 0, 2, '2025-04-28 16:44:07'),
	(9, 'Gaji Pokok', 1, 2, 200, 6, '2025-07-12 13:47:17'),
	(10, 'Tunjangan Kehadiran', 2, 2, 0, 6, '2025-07-12 13:47:17'),
	(11, 'Tunjangan Harian', 3, 2, 0, 6, '2025-07-12 13:47:17'),
	(12, 'Perjalanan Dinas', 5, 2, 0, 6, '2025-07-12 13:47:17');
/*!40000 ALTER TABLE `gaji_setting` ENABLE KEYS */;

-- Dumping structure for table master_as.gaji_slip
CREATE TABLE IF NOT EXISTS `gaji_slip` (
  `gaji_slip_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gaji_slip_name` varchar(225) NOT NULL,
  `gaji_slip_setting_id` int(11) NOT NULL,
  `gaji_slip_nominal` int(11) NOT NULL DEFAULT 0,
  `gaji_slip_gaji_id` int(11) NOT NULL,
  `gaji_slip_date_input` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gaji_slip_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.gaji_slip: 12 rows
/*!40000 ALTER TABLE `gaji_slip` DISABLE KEYS */;
INSERT INTO `gaji_slip` (`gaji_slip_id`, `gaji_slip_name`, `gaji_slip_setting_id`, `gaji_slip_nominal`, `gaji_slip_gaji_id`, `gaji_slip_date_input`) VALUES
	(1, 'Gaji Pokok', 1, 500000, 1, '2025-04-25 10:58:35'),
	(2, 'Tunjangan Kehadiran', 2, 1000000, 1, '2025-04-25 10:58:35'),
	(3, 'Tunjangan Harian', 3, 0, 1, '2025-04-25 10:58:35'),
	(4, 'Perjalanan Dinas', 4, 0, 1, '2025-04-25 10:58:35'),
	(5, 'Gaji Pokok', 5, 3000000, 2, '2025-04-28 16:44:42'),
	(6, 'Tunjangan Kehadiran', 6, 225000, 2, '2025-04-28 16:44:42'),
	(7, 'Tunjangan Harian', 7, 0, 2, '2025-04-28 16:44:42'),
	(8, 'Perjalanan Dinas', 8, 0, 2, '2025-04-28 16:44:42'),
	(17, 'Gaji Pokok', 1, 1500000, 5, '2025-07-02 15:11:33'),
	(18, 'Tunjangan Kehadiran', 2, 250000, 5, '2025-07-02 15:11:33'),
	(19, 'Tunjangan Harian', 3, 300000, 5, '2025-07-02 15:11:33'),
	(20, 'Perjalanan Dinas', 4, 100000, 5, '2025-07-02 15:11:33');
/*!40000 ALTER TABLE `gaji_slip` ENABLE KEYS */;

-- Dumping structure for function master_as.get_status_absen
DELIMITER //
CREATE FUNCTION `get_status_absen`(`p_status_absen_temp` VARCHAR(50), `p_status_absen` VARCHAR(50)) RETURNS varchar(100) CHARSET latin1 COLLATE latin1_swedish_ci
BEGIN

declare v_return varchar(100);

if p_status_absen_temp = 'UNLOCK' then
	set v_return := 'N' ;
	else
	set v_return := p_status_absen ;
	end if;
	return v_return;

END//
DELIMITER ;

-- Dumping structure for table master_as.hak_akses
CREATE TABLE IF NOT EXISTS `hak_akses` (
  `hak_akses_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`hak_akses_id`)
) ENGINE=MyISAM AUTO_INCREMENT=526 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.hak_akses: 392 rows
/*!40000 ALTER TABLE `hak_akses` DISABLE KEYS */;
INSERT INTO `hak_akses` (`hak_akses_id`, `menu_id`, `role_id`) VALUES
	(4, 14, 8),
	(5, 15, 8),
	(7, 8, 8),
	(11, 2, 1),
	(12, 9, 1),
	(13, 10, 1),
	(14, 3, 1),
	(15, 11, 1),
	(16, 12, 1),
	(17, 4, 1),
	(18, 13, 1),
	(19, 14, 1),
	(20, 15, 1),
	(21, 5, 1),
	(22, 16, 1),
	(23, 17, 1),
	(24, 20, 1),
	(25, 21, 1),
	(26, 22, 1),
	(27, 18, 1),
	(28, 23, 1),
	(29, 24, 1),
	(30, 19, 1),
	(31, 25, 1),
	(32, 26, 1),
	(33, 6, 1),
	(34, 27, 1),
	(35, 29, 1),
	(36, 30, 1),
	(37, 31, 1),
	(38, 32, 1),
	(39, 28, 1),
	(40, 33, 1),
	(41, 7, 1),
	(44, 36, 1),
	(47, 1, 1),
	(48, 1, 3),
	(49, 7, 3),
	(50, 34, 3),
	(51, 35, 3),
	(52, 2, 3),
	(53, 8, 3),
	(54, 37, 3),
	(55, 38, 3),
	(57, 2, 7),
	(58, 9, 7),
	(59, 10, 7),
	(60, 4, 7),
	(61, 14, 7),
	(62, 15, 7),
	(63, 1, 4),
	(64, 5, 4),
	(65, 16, 4),
	(67, 20, 4),
	(68, 21, 4),
	(69, 22, 4),
	(70, 18, 4),
	(71, 23, 4),
	(72, 24, 4),
	(73, 19, 4),
	(74, 25, 4),
	(75, 26, 4),
	(76, 6, 4),
	(88, 16, 2),
	(89, 19, 2),
	(90, 26, 2),
	(91, 5, 2),
	(92, 6, 2),
	(93, 27, 2),
	(94, 29, 2),
	(95, 30, 2),
	(96, 31, 2),
	(98, 37, 1),
	(99, 38, 1),
	(102, 10, 6),
	(103, 9, 6),
	(106, 4, 6),
	(107, 13, 6),
	(109, 15, 6),
	(110, 14, 6),
	(126, 30, 6),
	(127, 31, 6),
	(136, 39, 1),
	(137, 40, 1),
	(143, 34, 1),
	(144, 43, 1),
	(146, 47, 1),
	(147, 45, 1),
	(148, 46, 1),
	(149, 48, 1),
	(150, 49, 1),
	(151, 50, 1),
	(152, 51, 1),
	(153, 41, 1),
	(155, 52, 1),
	(157, 53, 1),
	(158, 54, 1),
	(159, 55, 1),
	(162, 56, 1),
	(163, 57, 1),
	(167, 58, 1),
	(168, 59, 1),
	(169, 60, 1),
	(170, 61, 1),
	(171, 62, 1),
	(172, 63, 1),
	(173, 65, 1),
	(174, 65, 2),
	(178, 2, 6),
	(184, 8, 6),
	(185, 65, 6),
	(192, 56, 6),
	(209, 66, 1),
	(210, 67, 1),
	(211, 68, 1),
	(212, 69, 1),
	(213, 70, 1),
	(214, 71, 1),
	(215, 72, 1),
	(216, 73, 1),
	(217, 74, 1),
	(218, 75, 1),
	(219, 76, 1),
	(220, 77, 1),
	(221, 78, 1),
	(222, 79, 1),
	(223, 80, 1),
	(227, 73, 5),
	(233, 71, 8),
	(234, 75, 8),
	(235, 70, 8),
	(236, 74, 8),
	(238, 72, 8),
	(240, 71, 6),
	(241, 75, 6),
	(242, 70, 6),
	(243, 74, 6),
	(244, 76, 6),
	(245, 78, 6),
	(246, 77, 6),
	(247, 79, 6),
	(248, 80, 6),
	(249, 27, 6),
	(250, 29, 6),
	(251, 66, 6),
	(254, 76, 8),
	(255, 78, 8),
	(256, 77, 8),
	(257, 79, 8),
	(258, 80, 8),
	(259, 27, 8),
	(260, 29, 8),
	(261, 30, 8),
	(262, 31, 8),
	(264, 6, 8),
	(265, 8, 7),
	(266, 66, 7),
	(267, 67, 7),
	(268, 68, 7),
	(269, 65, 7),
	(270, 13, 7),
	(271, 71, 7),
	(272, 70, 7),
	(273, 75, 7),
	(274, 74, 7),
	(275, 73, 7),
	(276, 72, 7),
	(277, 5, 7),
	(278, 16, 7),
	(279, 6, 7),
	(280, 76, 7),
	(281, 78, 7),
	(282, 77, 7),
	(283, 79, 7),
	(284, 80, 7),
	(285, 27, 7),
	(286, 29, 7),
	(287, 30, 7),
	(288, 31, 7),
	(289, 67, 6),
	(290, 68, 6),
	(291, 73, 6),
	(292, 72, 6),
	(293, 5, 6),
	(294, 16, 6),
	(295, 24, 6),
	(296, 56, 4),
	(297, 17, 4),
	(298, 45, 4),
	(299, 46, 4),
	(300, 39, 4),
	(301, 47, 4),
	(302, 43, 4),
	(303, 44, 4),
	(304, 55, 4),
	(305, 48, 4),
	(306, 49, 4),
	(307, 50, 4),
	(308, 51, 4),
	(309, 54, 4),
	(310, 3, 6),
	(311, 11, 6),
	(312, 12, 6),
	(313, 58, 6),
	(314, 59, 6),
	(315, 60, 6),
	(316, 61, 6),
	(317, 62, 6),
	(318, 63, 6),
	(319, 69, 6),
	(320, 72, 5),
	(322, 5, 8),
	(323, 16, 8),
	(324, 56, 8),
	(325, 17, 8),
	(326, 20, 8),
	(327, 21, 8),
	(328, 22, 8),
	(329, 45, 8),
	(330, 46, 8),
	(331, 39, 8),
	(334, 25, 8),
	(335, 26, 8),
	(336, 55, 8),
	(337, 48, 8),
	(338, 49, 8),
	(339, 50, 8),
	(340, 51, 8),
	(341, 54, 8),
	(342, 32, 8),
	(343, 28, 8),
	(344, 33, 8),
	(345, 41, 8),
	(346, 57, 8),
	(347, 42, 8),
	(348, 52, 8),
	(349, 40, 8),
	(350, 7, 8),
	(351, 34, 8),
	(352, 35, 8),
	(353, 36, 8),
	(354, 37, 8),
	(355, 38, 8),
	(356, 53, 8),
	(362, 18, 8),
	(363, 69, 8),
	(365, 61, 8),
	(366, 59, 8),
	(368, 58, 8),
	(369, 60, 8),
	(371, 3, 8),
	(372, 62, 8),
	(373, 65, 8),
	(374, 68, 8),
	(375, 67, 8),
	(376, 66, 8),
	(377, 63, 8),
	(378, 66, 2),
	(380, 67, 2),
	(381, 68, 2),
	(382, 73, 8),
	(383, 65, 5),
	(386, 2, 8),
	(387, 81, 1),
	(388, 82, 1),
	(390, 56, 0),
	(391, 82, 8),
	(392, 81, 8),
	(394, 4, 8),
	(395, 13, 8),
	(396, 1, 8),
	(397, 11, 8),
	(398, 12, 8),
	(400, 82, 5),
	(401, 2, 5),
	(402, 8, 5),
	(403, 9, 5),
	(404, 10, 5),
	(406, 9, 8),
	(407, 10, 8),
	(408, 40, 4),
	(409, 83, 1),
	(410, 84, 1),
	(411, 85, 1),
	(412, 86, 1),
	(413, 87, 1),
	(414, 88, 1),
	(415, 90, 1),
	(417, 91, 1),
	(418, 92, 1),
	(419, 93, 1),
	(420, 94, 1),
	(421, 95, 1),
	(422, 96, 1),
	(423, 97, 1),
	(424, 99, 1),
	(425, 98, 1),
	(426, 97, 3),
	(427, 5, 3),
	(428, 100, 3),
	(429, 101, 1),
	(430, 101, 2),
	(431, 101, 3),
	(433, 103, 1),
	(435, 105, 1),
	(436, 106, 1),
	(437, 107, 1),
	(438, 108, 1),
	(439, 109, 1),
	(440, 110, 1),
	(441, 111, 1),
	(442, 112, 1),
	(443, 113, 1),
	(444, 114, 1),
	(445, 115, 1),
	(446, 116, 1),
	(447, 117, 1),
	(448, 118, 1),
	(449, 119, 1),
	(450, 120, 1),
	(451, 121, 1),
	(452, 122, 1),
	(453, 123, 1),
	(454, 124, 1),
	(458, 137, 1),
	(459, 138, 1),
	(460, 146, 1),
	(461, 144, 1),
	(462, 145, 1),
	(463, 143, 1),
	(464, 142, 1),
	(465, 141, 1),
	(466, 140, 1),
	(467, 139, 1),
	(468, 148, 1),
	(469, 147, 1),
	(470, 132, 1),
	(471, 136, 1),
	(472, 135, 1),
	(473, 134, 1),
	(474, 133, 1),
	(475, 149, 1),
	(476, 150, 1),
	(477, 154, 1),
	(478, 155, 1),
	(479, 153, 1),
	(480, 152, 1),
	(481, 157, 1),
	(482, 151, 1),
	(483, 156, 1),
	(484, 158, 1),
	(485, 159, 1),
	(486, 160, 1),
	(487, 161, 1),
	(488, 162, 1),
	(489, 163, 1),
	(490, 164, 1),
	(491, 128, 1),
	(492, 165, 1),
	(493, 166, 1),
	(494, 167, 1),
	(495, 168, 1),
	(496, 169, 1),
	(497, 170, 1),
	(498, 171, 1),
	(499, 172, 1),
	(500, 173, 1),
	(501, 174, 1),
	(502, 175, 1),
	(503, 176, 1),
	(504, 177, 1),
	(505, 178, 1),
	(506, 179, 1),
	(507, 180, 1),
	(508, 181, 1),
	(509, 182, 1),
	(510, 183, 1),
	(511, 184, 1),
	(512, 185, 1),
	(513, 186, 1),
	(514, 187, 1),
	(515, 188, 1),
	(516, 189, 3),
	(517, 190, 1),
	(518, 191, 1),
	(519, 192, 1),
	(520, 193, 1),
	(521, 194, 1),
	(522, 195, 1),
	(523, 196, 1),
	(524, 197, 1),
	(525, 198, 1);
/*!40000 ALTER TABLE `hak_akses` ENABLE KEYS */;

-- Dumping structure for table master_as.holiday
CREATE TABLE IF NOT EXISTS `holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` year(4) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.holiday: ~0 rows (approximately)

-- Dumping structure for table master_as.homestay
CREATE TABLE IF NOT EXISTS `homestay` (
  `homestay_id` int(11) NOT NULL AUTO_INCREMENT,
  `homestay_name` varchar(100) NOT NULL,
  `homestay_price` double NOT NULL,
  `homestay_desc` text NOT NULL,
  `homestay_user_id` int(11) NOT NULL,
  `homestay_longitude` text NOT NULL,
  `homestay_latitude` text NOT NULL,
  `homestay_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `homestay_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`homestay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.homestay: ~0 rows (approximately)

-- Dumping structure for table master_as.hutang
CREATE TABLE IF NOT EXISTS `hutang` (
  `hutang_id` int(11) NOT NULL AUTO_INCREMENT,
  `hutang_noref` varchar(25) NOT NULL,
  `hutang_employee_id` int(11) DEFAULT NULL,
  `hutang_settinghutang_id` int(11) DEFAULT NULL,
  `hutang_bill` decimal(10,0) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `hutang_input_date` date DEFAULT NULL,
  `hutang_last_update` date DEFAULT NULL,
  PRIMARY KEY (`hutang_id`),
  KEY `fk_hutang_settinghutang1_idx` (`hutang_settinghutang_id`),
  KEY `fk_hutang_employee1_idx` (`hutang_employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.hutang: ~0 rows (approximately)

-- Dumping structure for table master_as.hutang_pay
CREATE TABLE IF NOT EXISTS `hutang_pay` (
  `hutang_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `hutang_pay_hutang_id` int(11) DEFAULT NULL,
  `hutang_pay_noref` varchar(50) NOT NULL,
  `hutang_pay_order` int(11) NOT NULL,
  `hutang_pay_account_id` int(11) NOT NULL DEFAULT 0,
  `hutang_pay_number` varchar(100) DEFAULT '0',
  `hutang_pay_status` int(11) NOT NULL DEFAULT 0,
  `hutang_pay_bill` decimal(10,0) DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `hutang_pay_input_date` date DEFAULT NULL,
  `hutang_pay_last_update` date DEFAULT NULL,
  PRIMARY KEY (`hutang_pay_id`),
  KEY `fk_hutang_pay_hutang1_idx` (`hutang_pay_hutang_id`),
  KEY `fk_hutang_pay_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.hutang_pay: ~0 rows (approximately)

-- Dumping structure for table master_as.information
CREATE TABLE IF NOT EXISTS `information` (
  `information_id` int(11) NOT NULL AUTO_INCREMENT,
  `information_title` varchar(100) DEFAULT NULL,
  `information_desc` text DEFAULT NULL,
  `information_img` varchar(255) DEFAULT NULL,
  `information_publish` tinyint(1) DEFAULT 0,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `information_input_date` timestamp NULL DEFAULT NULL,
  `information_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`information_id`),
  KEY `fk_information_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.information: ~0 rows (approximately)

-- Dumping structure for table master_as.information_siswa
CREATE TABLE IF NOT EXISTS `information_siswa` (
  `information_id` int(11) NOT NULL AUTO_INCREMENT,
  `information_title` varchar(100) DEFAULT NULL,
  `information_desc` text DEFAULT NULL,
  `information_img` varchar(255) DEFAULT NULL,
  `information_publish` tinyint(1) DEFAULT 0,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `information_input_date` timestamp NULL DEFAULT NULL,
  `information_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`information_id`),
  KEY `fk_information_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.information_siswa: ~0 rows (approximately)

-- Dumping structure for table master_as.info_app
CREATE TABLE IF NOT EXISTS `info_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `info` text DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.info_app: ~0 rows (approximately)

-- Dumping structure for table master_as.input_banking
CREATE TABLE IF NOT EXISTS `input_banking` (
  `input_banking_id` int(11) NOT NULL AUTO_INCREMENT,
  `input_banking_period_id` int(11) NOT NULL,
  `input_banking_noref` varchar(50) DEFAULT NULL,
  `input_banking_account_id` int(11) DEFAULT NULL,
  `input_banking_debit` int(11) NOT NULL,
  `input_banking_kredit` int(11) NOT NULL,
  `input_banking_date` date NOT NULL,
  `input_banking_code` int(11) NOT NULL,
  `input_banking_student_id` int(11) NOT NULL,
  `input_banking_note` text NOT NULL,
  `input_user_user_id` int(11) NOT NULL,
  `input_ipaymu_no_trans` varchar(50) DEFAULT NULL,
  `input_ipaymu_status` varchar(50) DEFAULT NULL,
  `input_banking_status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`input_banking_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.input_banking: 0 rows
/*!40000 ALTER TABLE `input_banking` DISABLE KEYS */;
/*!40000 ALTER TABLE `input_banking` ENABLE KEYS */;

-- Dumping structure for table master_as.ipaymu_donasi
CREATE TABLE IF NOT EXISTS `ipaymu_donasi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `va_transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.ipaymu_donasi: ~0 rows (approximately)

-- Dumping structure for table master_as.ipaymu_transaksi
CREATE TABLE IF NOT EXISTS `ipaymu_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `va_transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.ipaymu_transaksi: ~0 rows (approximately)

-- Dumping structure for table master_as.ipaymu_transaksi_tabungan
CREATE TABLE IF NOT EXISTS `ipaymu_transaksi_tabungan` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pondok` int(11) NOT NULL,
  `noref` varchar(50) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `status` varchar(11) NOT NULL DEFAULT 'READY',
  `di_bayar` int(11) NOT NULL DEFAULT 0,
  `va_no` varchar(50) DEFAULT NULL,
  `va_nama` varchar(50) DEFAULT NULL,
  `va_channel` varchar(50) DEFAULT NULL,
  `va_bank` varchar(50) DEFAULT NULL,
  `va_transactionId` varchar(50) DEFAULT NULL,
  `Expired` varchar(50) DEFAULT NULL,
  `va_fee` varchar(50) DEFAULT NULL,
  `create_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.ipaymu_transaksi_tabungan: ~0 rows (approximately)

-- Dumping structure for table master_as.item
CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(50) NOT NULL,
  `item_majors_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.item: 0 rows
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
/*!40000 ALTER TABLE `item` ENABLE KEYS */;

-- Dumping structure for table master_as.izin
CREATE TABLE IF NOT EXISTS `izin` (
  `izin_id` int(11) NOT NULL AUTO_INCREMENT,
  `izin_date` date NOT NULL,
  `izin_time` text NOT NULL,
  `izin_period_id` int(11) NOT NULL,
  `izin_student_id` int(11) NOT NULL,
  `izin_user_id` int(11) DEFAULT NULL,
  `izin_note` varchar(255) NOT NULL,
  `izin_status` varchar(255) NOT NULL DEFAULT 'Disetujui',
  PRIMARY KEY (`izin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.izin: ~0 rows (approximately)

-- Dumping structure for table master_as.jam_pelajaran
CREATE TABLE IF NOT EXISTS `jam_pelajaran` (
  `jam_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT,
  `jam_pelajaran_name` text DEFAULT NULL,
  `jam_pelajaran_start` time DEFAULT NULL,
  `jam_pelajaran_end` time DEFAULT NULL,
  PRIMARY KEY (`jam_pelajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.jam_pelajaran: ~0 rows (approximately)

-- Dumping structure for table master_as.jenis_bayar
CREATE TABLE IF NOT EXISTS `jenis_bayar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metode_bayar` varchar(50) NOT NULL,
  `jenis_bayar` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.jenis_bayar: ~0 rows (approximately)

-- Dumping structure for table master_as.jenis_keterangan
CREATE TABLE IF NOT EXISTS `jenis_keterangan` (
  `keterangan_id` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan_nama` varchar(225) NOT NULL,
  `keterangan_isi` text NOT NULL,
  `keterangan_update` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan_kop` text NOT NULL,
  PRIMARY KEY (`keterangan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.jenis_keterangan: 0 rows
/*!40000 ALTER TABLE `jenis_keterangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `jenis_keterangan` ENABLE KEYS */;

-- Dumping structure for table master_as.jurnal_umum
CREATE TABLE IF NOT EXISTS `jurnal_umum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sekolah_id` int(11) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `noref` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `pencatatan` enum('manual','auto') NOT NULL DEFAULT 'manual',
  `waktu_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `keterangan_lainnya` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.jurnal_umum: ~0 rows (approximately)

-- Dumping structure for table master_as.jurnal_umum_detail
CREATE TABLE IF NOT EXISTS `jurnal_umum_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_jurnal` int(10) unsigned NOT NULL,
  `account_code` varchar(20) NOT NULL,
  `debet` decimal(30,2) unsigned DEFAULT NULL,
  `kredit` decimal(30,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_jurnal` (`id_jurnal`),
  KEY `kode_akun` (`account_code`),
  KEY `account_code` (`account_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.jurnal_umum_detail: ~0 rows (approximately)

-- Dumping structure for table master_as.kas
CREATE TABLE IF NOT EXISTS `kas` (
  `kas_id` int(11) NOT NULL AUTO_INCREMENT,
  `kas_noref` varchar(50) NOT NULL,
  `kas_period` int(11) NOT NULL,
  `kas_date` date NOT NULL,
  `kas_month_id` int(11) NOT NULL,
  `kas_account_id` int(11) NOT NULL,
  `kas_majors_id` int(11) NOT NULL,
  `kas_note` text NOT NULL,
  `kas_category` int(11) NOT NULL,
  `kas_receipt` text NOT NULL,
  `kas_tax_receipt` text NOT NULL,
  `kas_debit` int(11) NOT NULL,
  `kas_kredit` int(11) NOT NULL,
  `kas_user_id` int(11) NOT NULL,
  `kas_input_date` date NOT NULL,
  `kas_last_update` date NOT NULL,
  PRIMARY KEY (`kas_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kas: 0 rows
/*!40000 ALTER TABLE `kas` DISABLE KEYS */;
/*!40000 ALTER TABLE `kas` ENABLE KEYS */;

-- Dumping structure for table master_as.kategori_barang
CREATE TABLE IF NOT EXISTS `kategori_barang` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(128) DEFAULT NULL,
  `nama_kategori` varchar(128) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.kategori_barang: ~0 rows (approximately)

-- Dumping structure for table master_as.kategori_pelanggaran
CREATE TABLE IF NOT EXISTS `kategori_pelanggaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kategori_pelanggaran: ~4 rows (approximately)
INSERT INTO `kategori_pelanggaran` (`id`, `kategori`) VALUES
	(1, 'Perilaku'),
	(2, 'Kedisiplinan'),
	(3, 'Kerapian'),
	(4, 'Akademik');

-- Dumping structure for table master_as.kategori_prestasi
CREATE TABLE IF NOT EXISTS `kategori_prestasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kategori_prestasi: ~2 rows (approximately)
INSERT INTO `kategori_prestasi` (`id`, `kategori`) VALUES
	(1, 'PRESTASI AKADEMIK'),
	(2, 'NON AKADEMIK');

-- Dumping structure for table master_as.kegiatan
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `kegiatan_id` int(11) NOT NULL AUTO_INCREMENT,
  `kegiatan_name` varchar(45) DEFAULT NULL,
  `kegiatan_majors_id` int(11) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`kegiatan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kegiatan: ~0 rows (approximately)

-- Dumping structure for table master_as.keputusan_pengadaan
CREATE TABLE IF NOT EXISTS `keputusan_pengadaan` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_aset` int(11) DEFAULT NULL,
  `id_spesifikasi` int(11) DEFAULT NULL,
  `id_kualitas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `id_spesifikasi` (`id_spesifikasi`),
  KEY `id_kualitas` (`id_kualitas`),
  KEY `id_aset` (`id_aset`),
  CONSTRAINT `keputusan_pengadaan_ibfk_1` FOREIGN KEY (`id_spesifikasi`) REFERENCES `kriteria_spesifikasi` (`id_spesifikasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keputusan_pengadaan_ibfk_2` FOREIGN KEY (`id_kualitas`) REFERENCES `kriteria_kualitas` (`id_kualitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keputusan_pengadaan_ibfk_3` FOREIGN KEY (`id_aset`) REFERENCES `data_aset` (`id_aset`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.keputusan_pengadaan: ~0 rows (approximately)

-- Dumping structure for table master_as.kesehatan
CREATE TABLE IF NOT EXISTS `kesehatan` (
  `kesehatan_id` int(11) NOT NULL AUTO_INCREMENT,
  `kesehatan_period_id` int(11) NOT NULL,
  `kesehatan_student_id` int(11) NOT NULL,
  `kesehatan_date` date DEFAULT NULL,
  `kesehatan_ill` text DEFAULT NULL,
  `kesehatan_cure` text DEFAULT NULL,
  `kesehatan_note` text DEFAULT NULL,
  `kesehatan_user_id` int(11) NOT NULL,
  `kesehatan_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `kesehatan_last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`kesehatan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kesehatan: ~0 rows (approximately)

-- Dumping structure for table master_as.keterangan_lampiran
CREATE TABLE IF NOT EXISTS `keterangan_lampiran` (
  `lampiran_id` int(11) NOT NULL AUTO_INCREMENT,
  `lampiran_isi` text NOT NULL,
  `lampiran_surat_keterangan_id` int(11) NOT NULL,
  `lampiran_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`lampiran_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.keterangan_lampiran: 0 rows
/*!40000 ALTER TABLE `keterangan_lampiran` DISABLE KEYS */;
/*!40000 ALTER TABLE `keterangan_lampiran` ENABLE KEYS */;

-- Dumping structure for table master_as.konseling
CREATE TABLE IF NOT EXISTS `konseling` (
  `konseling_id` int(11) NOT NULL AUTO_INCREMENT,
  `konseling_mode` enum('PELANGGARAN','KONSULTASI','PRESTASI') DEFAULT NULL,
  `konseling_period_id` int(11) NOT NULL,
  `konseling_student_id` int(11) NOT NULL,
  `konseling_date` date DEFAULT NULL,
  `konseling_foul` text DEFAULT NULL,
  `pelanggaran_id` int(11) DEFAULT NULL,
  `prestasi_id` smallint(6) NOT NULL DEFAULT 0,
  `konseling_action` text DEFAULT NULL,
  `konseling_poin` text DEFAULT NULL,
  `bukti` varchar(50) DEFAULT NULL,
  `konseling_note` text DEFAULT NULL,
  `konseling_user_id` int(11) NOT NULL,
  `konseling_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `konseling_last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`konseling_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.konseling: ~0 rows (approximately)

-- Dumping structure for table master_as.kredit
CREATE TABLE IF NOT EXISTS `kredit` (
  `kredit_id` int(11) NOT NULL AUTO_INCREMENT,
  `kredit_date` date DEFAULT NULL,
  `kredit_desc` varchar(100) DEFAULT NULL,
  `kredit_value` decimal(10,0) DEFAULT NULL,
  `kredit_account_id` int(11) NOT NULL,
  `kredit_gaji_id` int(11) DEFAULT NULL,
  `kredit_kas_noref` varchar(50) NOT NULL,
  `kredit_kas_account_id` int(11) NOT NULL,
  `kredit_tax_id` int(11) NOT NULL,
  `kredit_item_id` int(11) NOT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `kredit_input_date` date DEFAULT NULL,
  `kredit_last_update` date DEFAULT NULL,
  PRIMARY KEY (`kredit_id`),
  KEY `fk_jurnal_kredit_users1_idx` (`user_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kredit: ~0 rows (approximately)

-- Dumping structure for table master_as.kredit_temp
CREATE TABLE IF NOT EXISTS `kredit_temp` (
  `kredit_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `kredit_temp_majors_id` int(11) NOT NULL,
  `kredit_temp_date` date NOT NULL,
  `kredit_temp_noref` varchar(50) NOT NULL,
  `kredit_temp_kas_account_id` int(11) NOT NULL,
  `kredit_temp_account_id` int(11) NOT NULL,
  `kredit_temp_desc` varchar(50) NOT NULL,
  `kredit_temp_value` decimal(11,0) NOT NULL,
  `kredit_temp_tax_id` int(11) NOT NULL,
  `kredit_temp_item_id` int(11) NOT NULL,
  `user_user_id` int(11) NOT NULL,
  `kredit_temp_input_date` date NOT NULL,
  `kredit_temp_last_update` date NOT NULL,
  PRIMARY KEY (`kredit_temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.kredit_temp: 0 rows
/*!40000 ALTER TABLE `kredit_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `kredit_temp` ENABLE KEYS */;

-- Dumping structure for table master_as.kriteria_kualitas
CREATE TABLE IF NOT EXISTS `kriteria_kualitas` (
  `id_kualitas` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(128) DEFAULT NULL,
  `nilai` double DEFAULT NULL,
  PRIMARY KEY (`id_kualitas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.kriteria_kualitas: ~0 rows (approximately)

-- Dumping structure for table master_as.kriteria_spesifikasi
CREATE TABLE IF NOT EXISTS `kriteria_spesifikasi` (
  `id_spesifikasi` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(128) DEFAULT NULL,
  `nilai` double DEFAULT NULL,
  PRIMARY KEY (`id_spesifikasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.kriteria_spesifikasi: ~0 rows (approximately)

-- Dumping structure for table master_as.lainnya_guru
CREATE TABLE IF NOT EXISTS `lainnya_guru` (
  `lainnya_guru_id` int(11) NOT NULL AUTO_INCREMENT,
  `lainnya_guru_no` varchar(225) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`lainnya_guru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.lainnya_guru: 0 rows
/*!40000 ALTER TABLE `lainnya_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `lainnya_guru` ENABLE KEYS */;

-- Dumping structure for table master_as.lainnya_siswa
CREATE TABLE IF NOT EXISTS `lainnya_siswa` (
  `lainnya_siswa_id` int(11) NOT NULL AUTO_INCREMENT,
  `lainnya_siswa_no` varchar(225) NOT NULL,
  `student_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`lainnya_siswa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.lainnya_siswa: 0 rows
/*!40000 ALTER TABLE `lainnya_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `lainnya_siswa` ENABLE KEYS */;

-- Dumping structure for table master_as.layanan_publik
CREATE TABLE IF NOT EXISTS `layanan_publik` (
  `layanan_publik_id` int(11) NOT NULL AUTO_INCREMENT,
  `layanan_nama` varchar(225) NOT NULL,
  `layanan_order` int(11) NOT NULL,
  `layanan_submenu` int(11) NOT NULL,
  `layanan_link` varchar(225) NOT NULL,
  PRIMARY KEY (`layanan_publik_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.layanan_publik: 0 rows
/*!40000 ALTER TABLE `layanan_publik` DISABLE KEYS */;
/*!40000 ALTER TABLE `layanan_publik` ENABLE KEYS */;

-- Dumping structure for table master_as.lesson
CREATE TABLE IF NOT EXISTS `lesson` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_code` varchar(10) NOT NULL,
  `lesson_name` text NOT NULL,
  `lesson_teacher` varchar(10) NOT NULL,
  `lesson_majors_id` int(11) NOT NULL,
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.lesson: ~0 rows (approximately)

-- Dumping structure for table master_as.letter
CREATE TABLE IF NOT EXISTS `letter` (
  `letter_id` int(11) NOT NULL AUTO_INCREMENT,
  `letter_number` varchar(100) DEFAULT NULL,
  `letter_month` int(11) DEFAULT NULL,
  `letter_year` year(4) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`letter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.letter: ~0 rows (approximately)

-- Dumping structure for table master_as.letter_hutang
CREATE TABLE IF NOT EXISTS `letter_hutang` (
  `letter_hutang_id` int(11) NOT NULL AUTO_INCREMENT,
  `letter_hutang_number` varchar(100) DEFAULT NULL,
  `letter_hutang_month` int(11) NOT NULL,
  `letter_hutang_year` year(4) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`letter_hutang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.letter_hutang: ~0 rows (approximately)

-- Dumping structure for table master_as.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` timestamp NULL DEFAULT NULL,
  `log_action` varchar(45) DEFAULT NULL,
  `log_module` varchar(45) DEFAULT NULL,
  `log_info` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_g_activity_log_g_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.logs: ~0 rows (approximately)

-- Dumping structure for table master_as.log_hutang
CREATE TABLE IF NOT EXISTS `log_hutang` (
  `log_hutang_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_hutang_employee_id` int(11) DEFAULT NULL,
  `log_hutang_hutang_id` int(11) DEFAULT NULL,
  `log_hutang_pay_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `log_hutang_input_date` timestamp NULL DEFAULT NULL,
  `log_hutang_last_update` timestamp NULL DEFAULT NULL,
  `user_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_hutang_id`),
  KEY `fk_log_hutang_hutang_pay1_idx` (`log_hutang_pay_id`),
  KEY `fk_log_hutang_hutang_idx` (`log_hutang_hutang_id`),
  KEY `fk_log_hutang_employee_idx` (`log_hutang_employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.log_hutang: ~0 rows (approximately)

-- Dumping structure for table master_as.log_tf
CREATE TABLE IF NOT EXISTS `log_tf` (
  `log_tf_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_tf_kredit_id` int(11) DEFAULT NULL,
  `log_tf_debit_id` int(11) DEFAULT NULL,
  `log_tf_account_id` int(11) NOT NULL,
  `log_tf_balance_id` varchar(50) NOT NULL,
  `log_tf_date` date NOT NULL,
  `log_tf_note` text NOT NULL,
  `log_tf_majors_id` int(11) NOT NULL,
  `log_tf_user_id` int(11) NOT NULL,
  `log_tf_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_tf_last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_tf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.log_tf: ~0 rows (approximately)

-- Dumping structure for table master_as.log_trx
CREATE TABLE IF NOT EXISTS `log_trx` (
  `log_trx_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_student_id` int(11) DEFAULT NULL,
  `bulan_bulan_id` int(11) DEFAULT NULL,
  `bebas_pay_bebas_pay_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `log_trx_input_date` timestamp NULL DEFAULT NULL,
  `log_trx_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_trx_id`),
  KEY `fk_log_trx_bebas_pay1_idx` (`bebas_pay_bebas_pay_id`),
  KEY `fk_log_trx_bulan1_idx` (`bulan_bulan_id`),
  KEY `fk_log_trx_student1_idx` (`student_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.log_trx: ~0 rows (approximately)

-- Dumping structure for table master_as.lokasi_aset
CREATE TABLE IF NOT EXISTS `lokasi_aset` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(128) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id_lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.lokasi_aset: ~0 rows (approximately)

-- Dumping structure for table master_as.ltrx
CREATE TABLE IF NOT EXISTS `ltrx` (
  `ltrx_id` int(11) NOT NULL AUTO_INCREMENT,
  `ltrx_date` timestamp NULL DEFAULT NULL,
  `ltrx_action` varchar(45) DEFAULT NULL,
  `ltrx_module` varchar(45) DEFAULT NULL,
  `ltrx_info` text DEFAULT NULL,
  `ltrx_browser` text DEFAULT NULL,
  `ltrx_version` text DEFAULT NULL,
  `ltrx_os` text DEFAULT NULL,
  `ltrx_ip` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`ltrx_id`),
  KEY `fk_g_activity_ltrx_g_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.ltrx: ~0 rows (approximately)

-- Dumping structure for table master_as.majors
CREATE TABLE IF NOT EXISTS `majors` (
  `majors_id` int(11) NOT NULL AUTO_INCREMENT,
  `majors_name` varchar(100) DEFAULT NULL,
  `majors_short_name` varchar(45) DEFAULT NULL,
  `majors_school_name` text DEFAULT NULL,
  `majors_logo` text DEFAULT NULL,
  `majors_address` text DEFAULT NULL,
  `majors_wa_center` text DEFAULT NULL,
  `majors_phone` text DEFAULT NULL,
  `majors_status` int(11) NOT NULL DEFAULT 0,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`majors_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.majors: ~0 rows (approximately)

-- Dumping structure for table master_as.menu_paket
CREATE TABLE IF NOT EXISTS `menu_paket` (
  `menu_paket_id` int(11) NOT NULL AUTO_INCREMENT,
  `navmenu_menu_id` int(11) DEFAULT NULL,
  `package_package_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_paket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=443 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.menu_paket: ~237 rows (approximately)
INSERT INTO `menu_paket` (`menu_paket_id`, `navmenu_menu_id`, `package_package_id`) VALUES
	(1, 1, 3),
	(2, 2, 3),
	(4, 53, 3),
	(5, 38, 3),
	(6, 37, 3),
	(7, 36, 3),
	(8, 35, 3),
	(9, 34, 3),
	(10, 9, 3),
	(11, 10, 3),
	(12, 66, 3),
	(13, 67, 3),
	(14, 68, 3),
	(15, 65, 3),
	(16, 3, 3),
	(17, 11, 3),
	(18, 12, 3),
	(19, 58, 3),
	(20, 59, 3),
	(21, 60, 3),
	(22, 61, 3),
	(23, 62, 3),
	(24, 63, 3),
	(25, 69, 3),
	(26, 4, 3),
	(27, 13, 3),
	(28, 71, 3),
	(29, 75, 3),
	(30, 70, 3),
	(31, 74, 3),
	(32, 73, 3),
	(33, 72, 3),
	(34, 14, 3),
	(35, 15, 3),
	(36, 81, 3),
	(37, 5, 3),
	(38, 16, 3),
	(39, 56, 3),
	(40, 17, 3),
	(41, 20, 3),
	(42, 21, 3),
	(43, 22, 3),
	(44, 45, 3),
	(45, 46, 3),
	(46, 39, 3),
	(47, 18, 3),
	(49, 24, 3),
	(51, 23, 3),
	(53, 47, 3),
	(54, 43, 3),
	(56, 19, 3),
	(57, 25, 3),
	(58, 26, 3),
	(59, 55, 3),
	(60, 48, 3),
	(61, 49, 3),
	(62, 50, 3),
	(63, 51, 3),
	(64, 54, 3),
	(65, 6, 3),
	(66, 76, 3),
	(67, 78, 3),
	(68, 77, 3),
	(69, 79, 3),
	(70, 80, 3),
	(71, 27, 3),
	(72, 29, 3),
	(73, 30, 3),
	(74, 31, 3),
	(75, 32, 3),
	(76, 28, 3),
	(77, 33, 3),
	(78, 41, 3),
	(79, 57, 3),
	(81, 52, 3),
	(82, 40, 3),
	(83, 7, 3),
	(84, 1, 2),
	(85, 2, 2),
	(86, 9, 2),
	(87, 10, 2),
	(88, 66, 2),
	(89, 67, 2),
	(90, 68, 2),
	(91, 3, 2),
	(92, 11, 2),
	(93, 12, 2),
	(94, 4, 2),
	(95, 13, 2),
	(96, 71, 2),
	(97, 75, 2),
	(98, 70, 2),
	(99, 74, 2),
	(100, 14, 2),
	(101, 15, 2),
	(102, 5, 2),
	(103, 16, 2),
	(104, 56, 2),
	(105, 17, 2),
	(106, 20, 2),
	(107, 21, 2),
	(108, 22, 2),
	(110, 46, 2),
	(111, 39, 2),
	(112, 18, 2),
	(113, 47, 2),
	(114, 23, 2),
	(115, 24, 2),
	(116, 19, 2),
	(117, 25, 2),
	(118, 26, 2),
	(119, 6, 2),
	(120, 76, 2),
	(121, 78, 2),
	(122, 27, 2),
	(123, 29, 2),
	(124, 30, 2),
	(125, 31, 2),
	(126, 32, 2),
	(127, 28, 2),
	(128, 33, 2),
	(129, 41, 2),
	(130, 40, 2),
	(131, 7, 2),
	(132, 34, 2),
	(133, 36, 2),
	(134, 37, 2),
	(136, 53, 2),
	(137, 1, 1),
	(138, 2, 1),
	(139, 9, 1),
	(140, 10, 1),
	(141, 66, 1),
	(142, 3, 1),
	(143, 11, 1),
	(144, 12, 1),
	(145, 4, 1),
	(146, 13, 1),
	(147, 71, 1),
	(148, 14, 1),
	(149, 15, 1),
	(150, 5, 1),
	(151, 16, 1),
	(153, 17, 1),
	(154, 20, 1),
	(155, 21, 1),
	(156, 22, 1),
	(157, 39, 1),
	(158, 18, 1),
	(159, 47, 1),
	(160, 23, 1),
	(161, 24, 1),
	(162, 19, 1),
	(163, 25, 1),
	(164, 26, 1),
	(165, 76, 1),
	(166, 78, 1),
	(167, 27, 1),
	(168, 29, 1),
	(169, 30, 1),
	(170, 31, 1),
	(171, 32, 1),
	(172, 28, 1),
	(173, 33, 1),
	(174, 41, 1),
	(175, 40, 1),
	(176, 7, 1),
	(177, 34, 1),
	(178, 36, 1),
	(179, 37, 1),
	(181, 53, 1),
	(182, 82, 3),
	(185, 6, 1),
	(188, 52, 1),
	(191, 84, 3),
	(193, 85, 3),
	(195, 86, 3),
	(197, 87, 3),
	(198, 88, 3),
	(199, 90, 3),
	(200, 91, 3),
	(201, 92, 3),
	(202, 93, 3),
	(203, 94, 3),
	(204, 95, 3),
	(205, 96, 3),
	(206, 97, 3),
	(207, 97, 2),
	(208, 98, 3),
	(209, 99, 3),
	(213, 101, 1),
	(214, 99, 1),
	(215, 98, 1),
	(216, 82, 2),
	(217, 45, 2),
	(218, 101, 2),
	(219, 55, 2),
	(220, 54, 2),
	(221, 88, 2),
	(222, 90, 2),
	(223, 91, 2),
	(224, 92, 2),
	(225, 93, 2),
	(226, 94, 2),
	(227, 96, 2),
	(228, 98, 2),
	(229, 99, 2),
	(230, 57, 2),
	(232, 52, 2),
	(235, 101, 3),
	(236, 83, 3),
	(393, 158, 3),
	(394, 159, 3),
	(395, 160, 3),
	(396, 161, 3),
	(397, 162, 3),
	(398, 163, 3),
	(399, 164, 3),
	(400, 128, 3),
	(401, 165, 3),
	(426, 190, 3),
	(427, 191, 3),
	(428, 192, 2),
	(429, 193, 2),
	(430, 192, 3),
	(431, 193, 3),
	(432, 194, 2),
	(433, 195, 2),
	(434, 196, 2),
	(435, 197, 2),
	(436, 194, 3),
	(437, 195, 3),
	(438, 196, 3),
	(439, 197, 3),
	(440, 198, 3),
	(441, 198, 2),
	(442, 198, 1);

-- Dumping structure for table master_as.menu_paket_mobile
CREATE TABLE IF NOT EXISTS `menu_paket_mobile` (
  `menu_paket_mobile_id` int(11) NOT NULL AUTO_INCREMENT,
  `navmenu_mobile_id` int(11) NOT NULL,
  `package_package_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_paket_mobile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.menu_paket_mobile: ~19 rows (approximately)
INSERT INTO `menu_paket_mobile` (`menu_paket_mobile_id`, `navmenu_mobile_id`, `package_package_id`) VALUES
	(1, 2, 1),
	(2, 3, 1),
	(3, 9, 1),
	(4, 1, 2),
	(5, 2, 2),
	(6, 3, 2),
	(7, 5, 2),
	(8, 6, 2),
	(9, 7, 2),
	(10, 9, 2),
	(11, 1, 3),
	(12, 2, 3),
	(13, 3, 3),
	(14, 4, 3),
	(15, 5, 3),
	(16, 6, 3),
	(17, 7, 3),
	(18, 8, 3),
	(19, 9, 3);

-- Dumping structure for table master_as.metode_bayar
CREATE TABLE IF NOT EXISTS `metode_bayar` (
  `metode_id` bigint(20) NOT NULL,
  `metode_channel` varchar(100) NOT NULL,
  `metode_bank` varchar(100) NOT NULL,
  `metode_bank_logo` varchar(100) NOT NULL,
  `kode` varchar(100) NOT NULL,
  `status_metode` varchar(100) NOT NULL,
  `fee` int(11) NOT NULL,
  `jenis_bayar_id` int(11) NOT NULL,
  `cara_bayar` text DEFAULT NULL,
  PRIMARY KEY (`metode_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.metode_bayar: ~0 rows (approximately)

-- Dumping structure for table master_as.monitoring_aset
CREATE TABLE IF NOT EXISTS `monitoring_aset` (
  `id_monitoring` int(11) NOT NULL AUTO_INCREMENT,
  `id_aset` varchar(128) DEFAULT NULL,
  `kerusakan` text DEFAULT NULL,
  `akibat` text DEFAULT NULL,
  `faktor` text DEFAULT NULL,
  `monitoring` text DEFAULT NULL,
  `pemeliharaan` text DEFAULT NULL,
  `jml_rusak` varchar(128) DEFAULT NULL,
  `foto` varchar(128) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_monitoring`),
  KEY `id_aset` (`id_aset`),
  CONSTRAINT `monitoring_aset_ibfk_1` FOREIGN KEY (`id_aset`) REFERENCES `asets` (`id_aset`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.monitoring_aset: ~0 rows (approximately)

-- Dumping structure for table master_as.month
CREATE TABLE IF NOT EXISTS `month` (
  `month_id` int(11) NOT NULL AUTO_INCREMENT,
  `month_name` varchar(45) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`month_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.month: ~12 rows (approximately)
INSERT INTO `month` (`month_id`, `month_name`, `sekolah_id`) VALUES
	(1, 'Juli', '1'),
	(2, 'Agustus', '1'),
	(3, 'September', '1'),
	(4, 'Oktober', '1'),
	(5, 'November', '1'),
	(6, 'Desember', '1'),
	(7, 'Januari', '1'),
	(8, 'Februari', '1'),
	(9, 'Maret', '1'),
	(10, 'April', '1'),
	(11, 'Mei', '1'),
	(12, 'Juni', '1');

-- Dumping structure for table master_as.navmenu
CREATE TABLE IF NOT EXISTS `navmenu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_nama` char(50) NOT NULL,
  `menu_link` char(100) NOT NULL,
  `menu_icon` char(30) NOT NULL,
  `menu_child` int(11) NOT NULL,
  `menu_order` int(11) NOT NULL,
  `menu_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.navmenu: 192 rows
/*!40000 ALTER TABLE `navmenu` DISABLE KEYS */;
INSERT INTO `navmenu` (`menu_id`, `menu_nama`, `menu_link`, `menu_icon`, `menu_child`, `menu_order`, `menu_status`) VALUES
	(1, 'Dashboard', 'manage', 'fa fa-th', 0, 1, 0),
	(2, 'Kesiswaan', '#', 'fa fa-users text-stock', 0, 2, 0),
	(3, 'Kepegawaian', '#', 'fa fa-suitcase text-stock', 0, 3, 0),
	(4, 'Akademik', '#', 'fa fa-graduation-cap text-stoc', 0, 4, 0),
	(5, 'Keuangan', '#', 'fa fa-money text-stock', 0, 5, 0),
	(6, 'Laporan', '#', 'fa fa-file-text-o text-stock', 0, 7, 0),
	(7, 'Pengaturan', '#', 'fa fa-gear text-stock', 0, 8, 0),
	(8, 'Unit', 'manage/majors', 'fa fa-circle-o', 2, 1, 0),
	(9, 'Kelas', 'manage/class', 'fa fa-circle-o', 2, 2, 0),
	(10, 'Siswa', 'manage/student', 'fa fa-circle-o', 2, 3, 0),
	(11, 'Jabatan Pegawai', 'manage/position', 'fa fa-circle-o', 3, 1, 0),
	(12, 'Pegawai', 'manage/employees', 'fa fa-circle-o', 3, 2, 0),
	(13, 'Tahun Ajaran', 'manage/period', 'fa fa-circle-o', 4, 1, 0),
	(14, 'Kenaikan/Pindah Kelas', 'manage/student/upgrade', 'fa fa-circle-o', 4, 8, 0),
	(15, 'Kelulusan', 'manage/student/pass', 'fa fa-circle-o', 4, 9, 0),
	(16, 'Pembayaran Siswa', 'manage/payout', 'fa fa-circle-o', 5, 1, 0),
	(17, 'Setting Pembayaran', '#', 'fa fa-circle-o', 5, 2, 0),
	(18, 'Kas & Bank', '#', 'fa fa-circle-o', 5, 6, 0),
	(19, 'Penggajian', '#', 'fa fa-circle-o', 5, 7, 0),
	(20, 'Akun Biaya', 'manage/account', 'fa fa-circle-o', 17, 1, 0),
	(21, 'Pos Bayar', 'manage/pos', 'fa fa-circle-o', 17, 2, 0),
	(22, 'Jenis Bayar', 'manage/payment', 'fa fa-circle-o', 17, 3, 0),
	(23, 'Kas Keluar', 'manage/kredit', 'fa fa-circle-o', 18, 2, 0),
	(24, 'Kas Masuk', 'manage/debit', 'fa fa-circle-o', 18, 3, 0),
	(25, 'Setting Gaji', 'manage/penggajian', 'fa fa-circle-o', 19, 1, 0),
	(26, 'Slip Gaji', 'manage/slip', 'fa fa-circle-o', 19, 2, 0),
	(27, 'Lap. Pembayaran', '#', 'fa fa-circle-o', 6, 3, 0),
	(28, 'Lap. Keuangan', '#', 'fa fa-circle-o', 6, 4, 0),
	(29, 'Per. Kelas', 'manage/report', 'fa fa-circle-o', 27, 1, 0),
	(30, 'Per Tanggal', 'manage/report/report_period', 'fa fa-circle-o', 27, 2, 0),
	(31, 'Tagihan Siswa', 'manage/billing', 'fa fa-circle-o', 27, 3, 0),
	(32, 'Rekap Pembayaran', 'manage/report/report_bill', 'fa fa-circle-o', 27, 4, 0),
	(33, 'Lap. Kas Tunai', 'manage/report/report_date', 'fa fa-circle-o', 28, 2, 0),
	(34, 'Sekolah', 'manage/setting', 'fa fa-circle-o', 7, 1, 0),
	(35, 'Bulan', 'manage/month', 'fa fa-circle-o', 7, 2, 0),
	(36, 'Informasi', 'manage/information', 'fa fa-circle-o', 7, 3, 0),
	(37, 'Manajemen Pengguna', 'manage/users', 'fa fa-circle-o', 7, 4, 0),
	(38, 'Pemeliharaan', 'manage/maintenance', 'fa fa-circle-o', 7, 5, 0),
	(39, 'Tabungan Siswa', 'manage/banking', 'fa fa-circle-o', 5, 4, 0),
	(40, 'Lap. Tabungan', '#', 'fa fa-circle-o', 6, 5, 0),
	(41, 'Lap. Kas Bank', 'manage/report/report_bank', 'fa fa-circle-o', 28, 3, 0),
	(43, 'Transfer Kas', 'manage/transfer', 'fa fa-circle-o', 18, 4, 0),
	(45, 'Pajak', 'manage/tax', 'fa fa-circle-o', 17, 4, 0),
	(46, 'Unit POS', 'manage/item', 'fa fa-circle-o', 17, 5, 0),
	(47, 'Saldo Awal', 'manage/saldo', 'fa fa-circle-o', 18, 1, 0),
	(48, 'Hutang', '#', 'fa fa-circle-o', 5, 8, 0),
	(49, 'Pos Hutang', 'manage/poshutang', 'fa fa-circle-o', 48, 1, 0),
	(50, 'Setting Hutang', 'manage/settinghutang', 'fa fa-circle-o', 48, 2, 0),
	(51, 'Bayar Hutang', 'manage/bayarhutang', 'fa fa-circle-o', 48, 3, 0),
	(52, 'Lap. Gaji', 'manage/penggajian/report', 'fa fa-circle-o', 28, 4, 0),
	(53, 'Logs Transaksi', 'manage/logs/trx_keuangan', 'fa fa-circle-o', 7, 6, 0),
	(54, 'Kirim Tagihan', 'manage/billing/send_billing', 'fa fa-circle-o', 5, 10, 0),
	(55, 'Kirim Notif Gaji', 'manage/slip/send_slip', 'fa fa-circle-o', 19, 3, 0),
	(56, 'Bukti Transfer Wali Murid', 'manage/prove', 'fa fa-circle-o', 5, 3, 0),
	(57, 'Laporan Unit Pos', 'manage/unit_pos', 'fa fa-circle-o', 28, 5, 0),
	(58, 'Presensi', '#', 'fa fa-circle-o', 3, 3, 0),
	(59, 'Area Presensi Pegawai', 'manage/presensi_area', 'fa fa-circle-o', 58, 1, 0),
	(60, 'Rekap Presensi di Web', 'manage/rekap_presensi', 'fa fa-circle-o', 58, 2, 0),
	(61, 'Data Area Presensi', 'manage/presensi_data_area', 'fa fa-circle-o', 58, 3, 0),
	(62, 'Presensi Khusus', 'manage/presensi_khusus', 'fa fa-circle-o', 58, 4, 0),
	(63, 'Data Libur Presensi', 'manage/presensi_data_libur', 'fa fa-circle-o', 58, 5, 0),
	(65, 'Kirim Pengumuman', 'manage/pengumuman', 'fa fa-circle-o', 2, 12, 0),
	(66, 'Tahfidz', 'manage/tahfidz', 'fa fa-circle-o', 2, 1, 0),
	(67, 'Izin Keluar', 'manage/izin', 'fa fa-circle-o', 2, 5, 0),
	(68, 'Izin Pulang', 'manage/pulang', 'fa fa-circle-o', 2, 6, 0),
	(69, 'Jam Masuk & Pulang', 'manage/presensi_waktu', 'fa fa-circle-o', 58, 6, 0),
	(70, 'Jam Pelajaran', 'manage/jam_pelajaran', 'fa fa-circle-o', 4, 4, 0),
	(71, 'Semester', 'manage/semester', 'fa fa-circle-o', 4, 2, 0),
	(72, 'Presensi Pelajaran', 'manage/presensi_pelajaran', 'fa fa-circle-o', 4, 7, 0),
	(73, 'Presensi Harian', 'manage/presensi_harian', 'fa fa-circle-o', 4, 6, 0),
	(74, 'Jadwal Pelajaran', 'manage/schedule', 'fa fa-circle-o', 4, 5, 0),
	(75, 'Mata Pelajaran', 'manage/lesson', 'fa fa-circle-o', 4, 3, 0),
	(76, 'Lap. Kesiswaan', '#', 'fa fa-circle-o', 6, 1, 0),
	(77, 'Lap. Akademik', '#', 'fa fa-circle-o', 6, 2, 0),
	(78, 'Rekap Tahfidz', 'manage/tahfidz/report', 'fa fa-circle-o', 76, 1, 0),
	(79, 'Rekap Presensi Harian', 'manage/presensi_harian/report', 'fa fa-circle-o', 77, 1, 0),
	(80, 'Rekap Presensi Pelajaran', 'manage/presensi_pelajaran/report', 'fa fa-circle-o', 77, 2, 0),
	(81, 'Alumni', 'manage/alumni', 'fa fa-circle-o', 4, 11, 0),
	(82, 'Konseling', 'manage/konseling', 'fa fa-circle-o', 2, 10, 0),
	(83, 'Jurnal Mengajar', 'manage/presensi_pelajaran/report_mengajar', 'fa fa-circle-o', 77, 3, 0),
	(84, 'Pimpinan', '#', 'fa fa-line-chart', 0, 8, 0),
	(85, 'Analisa', 'manage/summary', 'fa fa-circle-o', 84, 1, 0),
	(86, 'Statistik', 'manage/statistik', 'fa fa-circle-o', 84, 2, 0),
	(87, 'Kesehatan', 'manage/kesehatan', 'fa fa-circle-o', 2, 11, 0),
	(88, 'Akuntansi', '#', 'fa fa-credit-card', 0, 6, 0),
	(90, 'Jurnal Umum', 'manage/jurnal_umum', 'fa fa-circle-o', 88, 1, 0),
	(91, 'Buku Besar', 'manage/buku_besar', 'fa fa-circle-o', 88, 2, 0),
	(92, 'Arus Kas/Bank', 'manage/arus_kas', 'fa fa-circle-o', 88, 3, 0),
	(93, 'Laba Rugi', 'manage/laba_rugi', 'fa fa-circle-o', 88, 4, 0),
	(94, 'Perubahan Modal', 'manage/modal', 'fa fa-circle-o', 88, 5, 0),
	(96, 'Neraca', 'manage/neraca', 'fa fa-circle-o', 88, 7, 0),
	(98, 'Per Tanggal', 'manage/banking/report_date', 'fa fa-circle-o', 40, 1, 0),
	(99, 'Rekap Tabungan', 'manage/banking/report', 'fa fa-circle-o', 40, 2, 0),
	(100, 'Gaji Setting', 'manage/gaji_setting', 'fa fa-circle-o', 5, 11, 0),
	(101, 'Pembayaran Paket', 'manage/batchpayment', 'fa fa-circle-o', 17, 4, 0),
	(102, 'PPDB', 'manage/ppdb', 'fa fa-file', 0, 1, 1),
	(103, 'eOffice(PTSP)', '#', 'fa fa-archive', 0, 2, 1),
	(104, 'eAsset', '#', 'fa fa-check-square-o', 0, 3, 1),
	(105, 'Arsip Dokumentasi', '#', 'fa fa-circle-o', 103, 1, 1),
	(106, 'Arsip Penyuratan', '#', 'fa fa-circle-o', 103, 2, 1),
	(107, 'Surat Perintah Dinas', 'manage/sppd', 'fa fa-circle-o', 103, 3, 1),
	(108, 'Arsip Surat Siswa', '#', 'fa fa-circle-o', 103, 4, 1),
	(109, 'Jenis Arsip', 'manage/ars_jenis', 'fa fa-circle-o', 105, 1, 1),
	(110, 'Satuan Arsip', 'manage/ars_satuan', 'fa fa-circle-o', 105, 2, 1),
	(111, 'Lokasi Arsip', 'manage/ars_lokasi', 'fa fa-circle-o', 105, 3, 1),
	(112, 'Pengajuan Arsip', 'manage/ars_data/pengajuan', 'fa fa-circle-o', 105, 4, 1),
	(113, 'Data Arsip', 'manage/ars_data', 'fa fa-circle-o', 105, 5, 1),
	(114, 'Jenis Surat', 'manage/surat_jenis', 'fa fa-circle-o', 106, 1, 1),
	(115, 'Surat Masuk', 'manage/surat_masuk', 'fa fa-circle-o', 106, 2, 1),
	(116, 'Surat Keluar', 'manage/surat_keluar', 'fa fa-circle-o', 106, 3, 1),
	(117, 'Histori Disposisi Surat', 'manage/surat_history', 'fa fa-circle-o', 106, 4, 1),
	(118, 'Jenis Keterangan', 'manage/keterangan', 'fa fa-circle-o', 108, 1, 1),
	(119, 'Surat Keterangan Siswa', 'manage/surat_keterangan', 'fa fa-circle-o', 108, 2, 1),
	(120, 'Surat Dispensasi KBM', 'manage/dispensasi', 'fa fa-circle-o', 108, 3, 1),
	(121, 'Surat Rekomendasi Siswa', 'manage/rekomendasi', 'fa fa-circle-o', 108, 4, 1),
	(122, 'Donasi', '#', 'fa fa-dropbox', 0, 4, 1),
	(123, 'Daftar Program', 'manage/program', 'fa fa-circle-o', 122, 1, 1),
	(124, 'Pendayagunaan Program', 'manage/pendayagunaan', 'fa fa-circle-o', 122, 2, 1),
	(125, 'Penginapan', '#', 'fa fa-bed', 0, 5, 1),
	(126, 'Data Penginapan', 'manage/homestay', 'fa fa-circle-o', 125, 1, 1),
	(127, 'Lap. Penginapan', 'manage/homestay/report', 'fa fa-circle-o', 125, 2, 1),
	(128, 'Kartu RFID', 'manage/cardregister', 'fa fa-circle-o', 2, 13, 0),
	(129, 'Inventaris Barang', '#', 'fa fa-cubes', 0, 6, 1),
	(130, 'Stok Barang', 'manage/barang', 'fa fa-circle-o', 129, 2, 1),
	(131, 'Satuan Barang', 'manage/satuan_barang', 'fa fa-circle-o', 129, 1, 1),
	(132, 'Layanan Eksternal', '#', 'fa fa-rss', 0, 9, 1),
	(133, 'E-Legalisir', 'manage/elegalisir', 'fa fa-circle-o', 132, 4, 1),
	(134, 'Survei Kepuasan', 'manage/survei', 'fa fa-circle-o', 132, 3, 1),
	(135, 'Layanan Publik', 'manage/publik', 'fa fa-circle-o', 132, 2, 1),
	(136, 'Layanan Pengaduan', 'manage/pengaduan', 'fa fa-circle-o', 132, 1, 1),
	(137, 'Layanan Siswa', '#', 'fa fa-child', 0, 8, 1),
	(138, 'Layanan Surat Siswa', '#', 'fa fa-circle-o', 137, 1, 1),
	(139, 'Surat Lain', 'manage/siswa_surat_lain', 'fa fa-circle-o', 138, 8, 1),
	(140, 'Surat Keterangan Prestasi', 'manage/siswa_prestasi', 'fa fa-circle-o', 138, 7, 1),
	(141, 'Surat Izin Penelitian', 'manage/siswa_penelitian', 'fa fa-circle-o', 138, 6, 1),
	(142, 'Surat Mutasi Siswa', 'manage/siswa_mutasi', 'fa fa-circle-o', 138, 5, 1),
	(143, 'Surat Rekomendasi Siswa', 'manage/siswa_rekomendasi', 'fa fa-circle-o', 138, 4, 1),
	(144, 'Surat Dispensasi KBM', 'manage/dispensasi', 'fa fa-circle-o', 138, 2, 1),
	(145, 'Surat Kelakuan Baik', 'manage/siswa_ket_baik', 'fa fa-circle-o', 138, 3, 1),
	(146, 'Surat Ket. Siswa Aktif', 'manage/siswa_ket_aktif', 'fa fa-circle-o', 138, 1, 1),
	(147, 'Lainnya', 'manage/lainnya_siswa', 'fa fa-circle-o', 137, 3, 1),
	(148, 'Permintaan Barang', 'manage/barang_siswa', 'fa fa-circle-o', 137, 2, 1),
	(149, 'Layanan Guru', '#', 'fa fa-bullhorn', 0, 7, 1),
	(150, 'Layanan Surat Guru', '#', 'fa fa-circle-o', 149, 1, 1),
	(151, 'Surat Lainnya', 'manage/surat_lain_guru', 'fa fa-circle-o', 150, 5, 1),
	(152, 'Surat Pengajuan Cuti', 'manage/surat_cuti', 'fa fa-circle-o', 150, 4, 1),
	(153, 'Surat Ket. Mengajar', 'manage/surat_mengajar', 'fa fa-circle-o', 150, 3, 1),
	(154, 'Surat Tugas', 'manage/surat_tugas', 'fa fa-circle-o', 150, 1, 1),
	(155, 'Surat Perintah Dinas', 'manage/sppd', 'fa fa-circle-o', 150, 2, 1),
	(156, 'Lainnya', 'manage/lainnya_guru', 'fa fa-circle-o', 149, 3, 1),
	(157, 'Permintaan Barang', 'manage/barang_guru', 'fa fa-circle-o', 149, 2, 1),
	(158, 'Anggaran', '#', 'fa fa-circle-o', 5, 9, 0),
	(159, 'Satuan', 'manage/satuan', 'fa fa-circle-o', 158, 1, 0),
	(160, 'Setting Standar/Program', 'manage/standar', 'fa fa-circle-o', 158, 2, 0),
	(161, 'Lembar Kerja/Anggaran', 'manage/worksheet', 'fa fa-circle-o', 158, 3, 0),
	(162, 'RAKS', '#', 'fa fa-money text-stock', 0, 5, 0),
	(163, 'RAKS Approval', 'manage/raks_approval', 'fa fa-circle-o', 162, 4, 0),
	(164, 'RAKS Entry', 'manage/raks_entry', 'fa fa-circle-o', 162, 2, 0),
	(165, 'Pengajuan Izin', 'manage/pengajuan', 'fa fa-circle-o', 2, 7, 0),
	(166, 'Syncronisasi Data', 'manage/ppdb', 'fa fa-circle-o', 2, 1, 1),
	(167, 'Statistik', 'manage/easet/statistik', 'fa fa-circle-o', 104, 1, 1),
	(168, 'Data Master', '#', 'fa fa-circle-o', 104, 2, 1),
	(169, 'Data Aset', '#', 'fa fa-circle-o', 104, 3, 1),
	(170, 'Keputusan Pengadaan', '#', 'fa fa-circle-o', 104, 4, 1),
	(171, 'Pengadaan', '#', 'fa fa-circle-o', 104, 5, 1),
	(172, 'Monitoring', 'manage/easet/monitoring', 'fa fa-circle-o', 104, 6, 1),
	(173, 'Penyusutan', 'manage/easet/penyusutan', 'fa fa-circle-o', 104, 7, 1),
	(174, 'Penghapusan', 'manage/easet/penghapusan', 'fa fa-circle-o', 104, 8, 1),
	(175, 'Laporan', '#', 'fa fa-circle-o', 104, 9, 1),
	(176, 'Barang', 'manage/easet/barang', 'fa fa-circle-o', 168, 1, 1),
	(177, 'Kategori Barang', 'manage/easet/kategori', 'fa fa-circle-o', 168, 2, 1),
	(178, 'Lokasi Aset', 'manage/easet/lokasi', 'fa fa-circle-o', 168, 3, 1),
	(179, 'Berwujud', 'manage/easet/aset', 'fa fa-circle-o', 169, 1, 1),
	(180, 'Dihapuskan', 'manage/easet/aset/dihapuskan', 'fa fa-circle-o', 169, 2, 1),
	(181, 'Data Kriteria', 'manage/easet/pengadaan', 'fa fa-circle-o', 170, 1, 1),
	(182, 'Data Aset', 'manage/easet/pengadaan/aset', 'fa fa-circle-o', 170, 2, 1),
	(183, 'Nilai/Pross SPK', 'manage/easet/pengadaan/spk', 'fa fa-circle-o', 170, 3, 1),
	(184, 'Pengajuan', 'manage/easet/pengadaan/pengajuan', 'fa fa-circle-o', 171, 1, 1),
	(185, 'Lihat Data', 'manage/easet/pengadaan/pengadaan', 'fa fa-circle-o', 171, 2, 1),
	(186, 'ePos Minimarket', 'https://epos.co.id', 'fa fa-cart-plus', 0, 10, 1),
	(187, 'eKoperasi', 'https://ekoperasi.co.id', 'fa fa-university', 0, 11, 1),
	(188, 'eKlinik', 'https://eklinik.co', 'fa fa-hospital-o', 0, 12, 1),
	(189, 'eKantin', 'https://play.google.com/store/apps/dev?id=6395890906220495321&hl=en', 'fa fa-shopping-cart', 0, 13, 1),
	(190, 'Lap. Jurnal', 'manage/report/report_jurnal', 'fa fa-circle-o', 28, 1, 0),
	(191, 'Lap. Mutasi', 'manage/report/report_mutasi', 'fa fa-circle-o', 28, 7, 0),
	(192, 'Prestasi', 'manage/prestasi', 'fa fa-circle-o', 2, 8, 0),
	(193, 'Pelanggaran', 'manage/pelanggaran', 'fa fa-circle-o', 2, 9, 0),
	(194, 'Rekap Izin Keluar', 'manage/izin/report', 'fa fa-circle-o', 76, 2, 0),
	(195, 'Rekap Izin Pulang', 'manage/pulang/report', 'fa fa-circle-o', 76, 3, 0),
	(196, 'Rekap Konseling', 'manage/konseling/report', 'fa fa-circle-o', 76, 4, 0),
	(197, 'Rekap Kesehatan', 'manage/kesehatan/report', 'fa fa-circle-o', 76, 5, 0),
	(198, 'Limit Tarik Tabungan', 'manage/banking_limit', 'fa fa-circle-o', 5, 5, 0);
/*!40000 ALTER TABLE `navmenu` ENABLE KEYS */;

-- Dumping structure for table master_as.navmenu_mobile
CREATE TABLE IF NOT EXISTS `navmenu_mobile` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_nama` char(50) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.navmenu_mobile: ~0 rows (approximately)

-- Dumping structure for table master_as.otp
CREATE TABLE IF NOT EXISTS `otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) NOT NULL,
  `code` text NOT NULL,
  `expired` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.otp: ~0 rows (approximately)

-- Dumping structure for table master_as.package
CREATE TABLE IF NOT EXISTS `package` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(50) NOT NULL,
  `package_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.package: ~3 rows (approximately)
INSERT INTO `package` (`package_id`, `package_name`, `package_status`) VALUES
	(1, 'DASAR', 1),
	(2, 'MENENGAH', 1),
	(3, 'ATAS', 1);

-- Dumping structure for table master_as.payment
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` enum('BEBAS','BULAN') DEFAULT NULL,
  `payment_mode` enum('TETAP','TIDAK TETAP','','') NOT NULL,
  `payment_is_batch` enum('0','1') DEFAULT '0',
  `period_period_id` int(11) DEFAULT NULL,
  `pos_pos_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `payment_input_date` timestamp NULL DEFAULT NULL,
  `payment_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `fk_payment_pos1_idx` (`pos_pos_id`),
  KEY `fk_payment_period1_idx` (`period_period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.payment: ~0 rows (approximately)

-- Dumping structure for table master_as.pelanggaran
CREATE TABLE IF NOT EXISTS `pelanggaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pelanggaran` text NOT NULL,
  `id_kategori` tinyint(4) NOT NULL,
  `poin` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pelanggaran: ~59 rows (approximately)
INSERT INTO `pelanggaran` (`id`, `pelanggaran`, `id_kategori`, `poin`) VALUES
	(1, 'Membuat kegaduhan di kelas atau dilingkungan sekolah', 1, 10),
	(2, 'Mencoret-coret atau mengotori dinding, pintu, meja, kursi, pagar sekolah/Sarana dan Prasarana', 1, 10),
	(3, 'Membawa/bermain kartu permainan (misalnya : remi, domino dan sejenisnya) dilingkungan sekolah.', 1, 10),
	(4, 'Memarkir sepeda/motor tidak pada tempatnya.', 1, 10),
	(5, 'Berperilaku jorok di lingkungan sekolah', 1, 10),
	(6, 'Bermain bola di koridor dan didalam kelas.', 1, 15),
	(7, 'Melindungi teman yang melakukan pelanggaran.', 1, 15),
	(8, 'Menyontek dan memberi jawaban (bekerjasama) pada saat ulangan harian/ujian semester.', 1, 20),
	(9, 'Menggunakan handphone ketika tidak diperlukan saat KBM.', 1, 20),
	(10, 'Merayakan ulang tahun berlebihan (perpeloncoan)', 1, 20),
	(11, 'Menyalahgunakan uang partisipasi atau uang sekolah.', 1, 25),
	(12, 'Membawa atau membunyikan petasan.', 1, 30),
	(13, 'Berperilaku Asusila dilingkungan sekolah', 1, 40),
	(14, 'Membuat surat izin palsu.', 1, 40),
	(15, 'Meloncat jendela dan pagar sekolah.', 1, 40),
	(16, 'Merusak Sarana dan Prasarana sekolah.', 1, 40),
	(17, 'Bertindak tidak sopan/melecehkan Kepala Sekolah, guru, TU dan karyawan sekolah.', 1, 50),
	(18, 'Mengancam/mengintimidasi teman sekelas/teman sekolah', 1, 50),
	(19, 'Berpacaran dilingkungan sekolah (bermesraan dilingkungan sekolah)/berpacaran/bermesraan diluar sekolah selama memakai atribut sekolah.', 1, 100),
	(20, 'Membawa/merokok (tembakau/elektrik) dilingkungan sekolah baik mengenakan seragam ataupun pakaian bebas atau di luar sekolah menggunakan atribut sekolah.', 1, 100),
	(21, 'Menyalahgunakan media social yang merugikan pihak lain yang berhubungan dengan sekolah.', 1, 100),
	(22, 'Berjudi dalam bentuk apapun dilingkungan sekolah.', 1, 150),
	(23, 'Membawa senjata tajam, senjata api dan sejenisnya dilingkungan sekolah', 1, 150),
	(24, 'Terlibat langsung maupun tidak langsung perkelahian/tawuran disekolah, diluar sekolah atau antar sekolah.', 1, 150),
	(25, 'Mengikuti aliran/perkumpulan/geng terlarang/komunitas LGBT (Lesbi, Gay, Biseksual, Transgender), melakukan praktik LGBT dan radikalisme (Aliran keras)', 1, 200),
	(26, 'Membawa dan atau membuat Video Porno, buku porno, majalah porno atau sesuatu yang berbau pornografi dan pornoaksi.', 1, 200),
	(27, 'Mencuri dilingkungan sekolah dan diluar sekolah (termasuk sarana prasarana sekolah).', 1, 200),
	(28, 'Membawa, menggunakan atau mengedarkan miras dan narkoba dilingkungan sekolah.', 1, 250),
	(29, 'Mengancam/mengintimidasi Kepala Sekolah, guru, TU dan karyawan sekolah.', 1, 250),
	(30, 'Menyalahgunakan wewenang sekolah untuk kepentingan pribadi atau kelompok.', 1, 250),
	(31, 'Memalsukan stemple sekolah, edaran sekolah atau tandatangan Kepala Sekolah, guru, TU dan karyawan sekolah.', 1, 250),
	(32, 'Terlibat tindakan kriminal (melanggar hukum), mencemarkan nama baik sekolah.', 1, 250),
	(33, 'Terbukti hamil atau menghamili', 1, 250),
	(34, 'Terbukti menikah', 1, 250),
	(35, 'Datang terlambat.', 2, 10),
	(36, 'Tidak mengikuti dan melaksanakan piket 7K', 2, 10),
	(37, 'Tidur di kelas saat pelajaran berlangsung.', 2, 10),
	(38, 'Tidak mengikuti pelajaran tanpa izin.', 2, 20),
	(39, 'Meninggalkan kelas tanpa izin.', 2, 20),
	(40, 'Di kantin saat jam pelajaran tanpa izin', 2, 20),
	(41, 'Tidak membawa buku yang berkaitan dengan pelajaran.', 2, 20),
	(42, 'Tidak masuk sekolah tanpa keterangan.', 2, 20),
	(43, 'Tidak mengikuti upacara.', 2, 20),
	(44, 'Tidak mengikuti kegiatan sekolah.', 2, 20),
	(45, 'Tidak mengikuti kegiatan ekstrakulikuler berdasarkan absensi kehadiran siswa dalam pantauan pembina.', 2, 20),
	(46, 'Pulang sebelum waktunya tanpa izin dari sekolah.', 2, 40),
	(47, 'Menggunakan kendaraan bermotor tidak sesuai standart UU lalulintas', 2, 50),
	(48, 'Tidak berseragam sesuai dengan ketentuan.', 3, 10),
	(49, 'Tidak berpakain rapi sesuai aturan sekolah.', 3, 10),
	(50, 'Memakai sepatu & kaos kaki tidak sesuai ketentuan Sepatu Hitam & Kaos Kaki Putih : Senin, Selasa Sepatu & Kaos Kaki Bebas : Rabu Sepatu Hitam Kaos Kaki Hitam : Kamis, Jumat', 3, 10),
	(51, '', 3, 10),
	(52, 'Putra dan Putri menyemir rambut', 3, 10),
	(53, 'Putra potongan rambut maks 2:3:4', 3, 10),
	(54, 'Putri bermakeup', 3, 10),
	(55, 'Putri memakai perhiasan berlebihan', 3, 10),
	(56, 'Memakai sandal dilingkungan sekolah', 3, 10),
	(57, 'Putra dan putri bertato', 3, 40),
	(58, 'Putra bertindik', 3, 40),
	(59, 'berantem', 1, 10);

-- Dumping structure for table master_as.pelayanan
CREATE TABLE IF NOT EXISTS `pelayanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` text DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `link` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pelayanan: ~0 rows (approximately)

-- Dumping structure for table master_as.pendayagunaan
CREATE TABLE IF NOT EXISTS `pendayagunaan` (
  `pendayagunaan_id` int(11) NOT NULL AUTO_INCREMENT,
  `program_program_id` int(11) NOT NULL,
  `pendayagunaan_title` varchar(100) DEFAULT NULL,
  `pendayagunaan_nominal` int(11) NOT NULL DEFAULT 0,
  `pendayagunaan_note` varchar(100) NOT NULL,
  `pendayagunaan_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pendayagunaan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.pendayagunaan: ~0 rows (approximately)

-- Dumping structure for table master_as.pengadaan
CREATE TABLE IF NOT EXISTS `pengadaan` (
  `id_pengadaan` int(11) NOT NULL AUTO_INCREMENT,
  `id_lokasi` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_aset` varchar(128) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `satuan` varchar(128) DEFAULT NULL,
  `harga_satuan` double DEFAULT NULL,
  `tahun_pengadaan` varchar(4) DEFAULT NULL,
  `status` enum('0','1','2') DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pengadaan`),
  KEY `id_lokasi` (`id_lokasi`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pengadaan_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_aset` (`id_lokasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengadaan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pengadaan: ~0 rows (approximately)

-- Dumping structure for table master_as.pengaduan
CREATE TABLE IF NOT EXISTS `pengaduan` (
  `pengaduan_id` int(11) NOT NULL AUTO_INCREMENT,
  `pengaduan_no` varchar(255) NOT NULL,
  `pengaduan_nama` int(11) NOT NULL,
  `pengaduan_tujuan` int(11) NOT NULL,
  `pengaduan_detail` text NOT NULL,
  `pengaduan_status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pengaduan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.pengaduan: 0 rows
/*!40000 ALTER TABLE `pengaduan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengaduan` ENABLE KEYS */;

-- Dumping structure for table master_as.pengajuan_izin
CREATE TABLE IF NOT EXISTS `pengajuan_izin` (
  `pengajuan_izin_id` int(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_izin_date` date NOT NULL,
  `pengajuan_izin_time` text NOT NULL,
  `pengajuan_izin_period_id` int(11) NOT NULL,
  `pengajuan_izin_majors_id` int(11) NOT NULL,
  `pengajuan_izin_student_id` int(11) NOT NULL,
  `pengajuan_izin_note` text NOT NULL,
  `pengajuan_izin_status` varchar(50) DEFAULT NULL,
  `pengajuan_izin_user_id` int(11) DEFAULT NULL,
  `pengajuan_izin_created_at` datetime DEFAULT current_timestamp(),
  `pengajuan_izin_updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`pengajuan_izin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pengajuan_izin: ~0 rows (approximately)

-- Dumping structure for table master_as.pengajuan_pulang
CREATE TABLE IF NOT EXISTS `pengajuan_pulang` (
  `pengajuan_pulang_id` int(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_pulang_date` date NOT NULL,
  `pengajuan_pulang_days` int(11) NOT NULL,
  `pengajuan_pulang_period_id` int(11) NOT NULL,
  `pengajuan_pulang_majors_id` int(11) NOT NULL,
  `pengajuan_pulang_student_id` int(11) NOT NULL,
  `pengajuan_pulang_note` text NOT NULL,
  `pengajuan_pulang_status` varchar(20) NOT NULL,
  `pengajuan_pulang_user_id` int(11) DEFAULT NULL,
  `pengajuan_pulang_created_at` datetime DEFAULT current_timestamp(),
  `pengajuan_pulang_updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`pengajuan_pulang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pengajuan_pulang: ~0 rows (approximately)

-- Dumping structure for table master_as.pengajuan_surat_masuk
CREATE TABLE IF NOT EXISTS `pengajuan_surat_masuk` (
  `id_pengajuan_s` int(11) NOT NULL AUTO_INCREMENT,
  `no_agenda` varchar(15) NOT NULL,
  `jenis_surat` varchar(15) NOT NULL,
  `tanggal_kirim` datetime NOT NULL,
  `tanggal_terima` datetime NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `pengirim` varchar(50) NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `nama_file` varchar(50) NOT NULL,
  PRIMARY KEY (`id_pengajuan_s`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.pengajuan_surat_masuk: 0 rows
/*!40000 ALTER TABLE `pengajuan_surat_masuk` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengajuan_surat_masuk` ENABLE KEYS */;

-- Dumping structure for table master_as.penghapusan
CREATE TABLE IF NOT EXISTS `penghapusan` (
  `id_penghapusan` int(11) NOT NULL AUTO_INCREMENT,
  `id_aset` varchar(128) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `faktor` text DEFAULT NULL,
  `tgl_penghapusan` date DEFAULT NULL,
  `status` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_penghapusan`),
  KEY `id_aset` (`id_aset`),
  CONSTRAINT `penghapusan_ibfk_1` FOREIGN KEY (`id_aset`) REFERENCES `asets` (`id_aset`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.penghapusan: ~0 rows (approximately)

-- Dumping structure for table master_as.pengumuman
CREATE TABLE IF NOT EXISTS `pengumuman` (
  `pengumuman_id` int(11) NOT NULL AUTO_INCREMENT,
  `pengumuman_teks` text DEFAULT NULL,
  `pengumuman_student` int(11) NOT NULL,
  `pengumuman_phone` text NOT NULL,
  PRIMARY KEY (`pengumuman_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pengumuman: ~0 rows (approximately)

-- Dumping structure for table master_as.period
CREATE TABLE IF NOT EXISTS `period` (
  `period_id` int(11) NOT NULL AUTO_INCREMENT,
  `period_start` year(4) DEFAULT NULL,
  `period_end` year(4) DEFAULT NULL,
  `period_status` tinyint(1) DEFAULT 0,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.period: ~3 rows (approximately)
INSERT INTO `period` (`period_id`, `period_start`, `period_end`, `period_status`, `sekolah_id`) VALUES
	(1, '2024', '2025', 0, ''),
	(2, '2025', '2026', 1, ''),
	(3, '2026', '2027', 0, '');

-- Dumping structure for table master_as.pesan
CREATE TABLE IF NOT EXISTS `pesan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_funding` int(11) DEFAULT NULL,
  `no_wa` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status_kirim` varchar(50) DEFAULT 'PENDING',
  `created_date` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pesan: ~0 rows (approximately)

-- Dumping structure for table master_as.pos
CREATE TABLE IF NOT EXISTS `pos` (
  `pos_id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(100) DEFAULT NULL,
  `pos_description` varchar(100) DEFAULT NULL,
  `account_account_id` char(10) NOT NULL,
  `piutang_account_id` char(10) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pos: ~0 rows (approximately)

-- Dumping structure for table master_as.poshutang
CREATE TABLE IF NOT EXISTS `poshutang` (
  `poshutang_id` int(11) NOT NULL AUTO_INCREMENT,
  `poshutang_name` varchar(100) DEFAULT NULL,
  `poshutang_description` varchar(100) DEFAULT NULL,
  `poshutang_account_id` char(10) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  PRIMARY KEY (`poshutang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.poshutang: ~0 rows (approximately)

-- Dumping structure for table master_as.position
CREATE TABLE IF NOT EXISTS `position` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_code` char(10) NOT NULL,
  `position_name` varchar(50) NOT NULL,
  `position_majors_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.position: 0 rows
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
/*!40000 ALTER TABLE `position` ENABLE KEYS */;

-- Dumping structure for table master_as.position_history
CREATE TABLE IF NOT EXISTS `position_history` (
  `poshistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `poshistory_start` date NOT NULL,
  `poshistory_end` date NOT NULL,
  `poshistory_desc` text NOT NULL,
  `poshistory_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`poshistory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.position_history: 0 rows
/*!40000 ALTER TABLE `position_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `position_history` ENABLE KEYS */;

-- Dumping structure for table master_as.pos_debit
CREATE TABLE IF NOT EXISTS `pos_debit` (
  `pos_debit_id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_debit_account_id` int(11) DEFAULT NULL,
  `pos_debit_item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pos_debit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pos_debit: ~0 rows (approximately)

-- Dumping structure for table master_as.pos_kredit
CREATE TABLE IF NOT EXISTS `pos_kredit` (
  `pos_kredit_id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_kredit_account_id` int(11) DEFAULT NULL,
  `pos_kredit_item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pos_kredit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pos_kredit: ~0 rows (approximately)

-- Dumping structure for table master_as.potongan
CREATE TABLE IF NOT EXISTS `potongan` (
  `potongan_id` int(11) NOT NULL AUTO_INCREMENT,
  `potongan_pinjam_bank` int(11) NOT NULL,
  `potongan_organisasi` int(11) NOT NULL,
  `potongan_koperasi` int(11) NOT NULL,
  `potongan_program_bahasa` int(11) NOT NULL,
  `potongan_majalah` int(11) NOT NULL,
  `potongan_admin_bank` int(11) NOT NULL,
  `potongan_infaq` int(11) NOT NULL,
  `potongan_bpjs` int(11) NOT NULL,
  `potongan_telemarket` int(11) NOT NULL,
  `potongan_lain` int(11) NOT NULL,
  `potongan_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`potongan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.potongan: 0 rows
/*!40000 ALTER TABLE `potongan` DISABLE KEYS */;
/*!40000 ALTER TABLE `potongan` ENABLE KEYS */;

-- Dumping structure for table master_as.potongan_setting
CREATE TABLE IF NOT EXISTS `potongan_setting` (
  `potongan_setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `potongan_setting_name` varchar(225) DEFAULT NULL,
  `potongan_setting_default_id` int(11) DEFAULT NULL,
  `potongan_majors_id` int(11) NOT NULL,
  `potongan_setting_nominal` int(11) DEFAULT 0,
  `potongan_setting_employee_id` int(11) DEFAULT NULL,
  `potongan_setting_date_input` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`potongan_setting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.potongan_setting: 0 rows
/*!40000 ALTER TABLE `potongan_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `potongan_setting` ENABLE KEYS */;

-- Dumping structure for table master_as.potongan_slip
CREATE TABLE IF NOT EXISTS `potongan_slip` (
  `potongan_slip_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `potongan_slip_name` varchar(225) DEFAULT NULL,
  `potongan_slip_setting_id` int(11) DEFAULT NULL,
  `potongan_slip_nominal` int(11) DEFAULT NULL,
  `potongan_slip_gaji_id` int(11) DEFAULT NULL,
  `potongan_slip_date_input` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`potongan_slip_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.potongan_slip: 0 rows
/*!40000 ALTER TABLE `potongan_slip` DISABLE KEYS */;
/*!40000 ALTER TABLE `potongan_slip` ENABLE KEYS */;

-- Dumping structure for table master_as.premier
CREATE TABLE IF NOT EXISTS `premier` (
  `premier_id` int(11) NOT NULL AUTO_INCREMENT,
  `premier_pokok` int(11) NOT NULL,
  `premier_fungsional` int(11) NOT NULL,
  `premier_struktural` int(11) NOT NULL,
  `premier_kesra` int(11) NOT NULL,
  `premier_khusus` int(11) NOT NULL,
  `premier_prestasi` int(11) NOT NULL,
  `premier_poin` int(11) NOT NULL,
  `premier_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`premier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.premier: 0 rows
/*!40000 ALTER TABLE `premier` DISABLE KEYS */;
/*!40000 ALTER TABLE `premier` ENABLE KEYS */;

-- Dumping structure for view master_as.presensi_area_v
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `presensi_area_v` (
	`id_pegawai` INT(11) NOT NULL,
	`nip` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_pegawai` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`id_unit` INT(11) NULL,
	`id_jabatan` INT(11) NULL,
	`status_absen` ENUM('Y','N') NOT NULL COLLATE 'latin1_swedish_ci',
	`status_absen_temp` ENUM('LOCK','UNLOCK') NOT NULL COLLATE 'latin1_swedish_ci',
	`area_absen` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`jarak_radius` INT(11) NOT NULL
);

-- Dumping structure for table master_as.presensi_harian
CREATE TABLE IF NOT EXISTS `presensi_harian` (
  `presensi_harian_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `presensi_harian_period_id` int(11) DEFAULT NULL,
  `presensi_harian_month_id` int(11) DEFAULT NULL,
  `presensi_harian_date` text DEFAULT NULL,
  `presensi_harian_class_id` int(11) DEFAULT NULL,
  `presensi_harian_student_id` int(11) DEFAULT NULL,
  `presensi_harian_status` char(2) DEFAULT NULL,
  `presensi_harian_created_date` timestamp NULL DEFAULT current_timestamp(),
  `presensi_harian_updated_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`presensi_harian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.presensi_harian: ~0 rows (approximately)

-- Dumping structure for table master_as.presensi_pelajaran
CREATE TABLE IF NOT EXISTS `presensi_pelajaran` (
  `presensi_pelajaran_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `presensi_teaching_id` int(11) NOT NULL,
  `presensi_pelajaran_period_id` int(11) DEFAULT NULL,
  `presensi_pelajaran_month_id` int(11) DEFAULT NULL,
  `presensi_pelajaran_date` text DEFAULT NULL,
  `presensi_pelajaran_class_id` int(11) DEFAULT NULL,
  `presensi_pelajaran_lesson_id` int(11) DEFAULT NULL,
  `presensi_pelajaran_student_id` int(11) DEFAULT NULL,
  `presensi_pelajaran_status` char(2) DEFAULT NULL,
  `presensi_pelajaran_created_date` timestamp NULL DEFAULT current_timestamp(),
  `presensi_pelajaran_updated_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`presensi_pelajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.presensi_pelajaran: ~0 rows (approximately)

-- Dumping structure for table master_as.presensi_student
CREATE TABLE IF NOT EXISTS `presensi_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` varchar(45) NOT NULL,
  `id_class` int(11) NOT NULL,
  `id_area_absensi` int(11) NOT NULL,
  `jenis_absen` enum('DATANG','PULANG','SAKIT','IZIN','ALPHA') NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `longi` varchar(25) DEFAULT NULL,
  `lati` varchar(25) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` text DEFAULT NULL,
  `catatan_absen` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_presensi_student_area_absensi` (`id_area_absensi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.presensi_student: ~0 rows (approximately)

-- Dumping structure for table master_as.prestasi
CREATE TABLE IF NOT EXISTS `prestasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prestasi` text NOT NULL,
  `id_kategori` tinyint(4) NOT NULL,
  `poin` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.prestasi: ~6 rows (approximately)
INSERT INTO `prestasi` (`id`, `prestasi`, `id_kategori`, `poin`) VALUES
	(1, 'Juara 1 Olimpiade Matematika tingkat provinsi', 1, 50),
	(2, 'Menjadi tutor sebaya pelajaran Matematika untuk teman sekelas', 1, 10),
	(3, 'Peringkat 1 paralel kelas', 1, 10),
	(4, 'Juara 1 Lomba Atletik', 2, 50),
	(5, 'Menjabat sebagai Ketua OSIS', 2, 60),
	(6, 'Panitia Kegiatan', 2, 20);

-- Dumping structure for table master_as.program
CREATE TABLE IF NOT EXISTS `program` (
  `program_id` int(11) NOT NULL AUTO_INCREMENT,
  `program_name` varchar(100) DEFAULT NULL,
  `program_description` varchar(100) DEFAULT NULL,
  `account_account_id` char(10) NOT NULL,
  `pendayagunaan_account_id` int(11) DEFAULT NULL,
  `program_gambar` text DEFAULT NULL,
  `program_end` date NOT NULL,
  `program_target` int(11) NOT NULL,
  `program_earn` int(11) DEFAULT 0,
  `program_realisasi` int(11) DEFAULT 0,
  `program_status` int(11) NOT NULL,
  `program_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `program_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.program: ~0 rows (approximately)

-- Dumping structure for table master_as.prove
CREATE TABLE IF NOT EXISTS `prove` (
  `prove_id` int(11) NOT NULL AUTO_INCREMENT,
  `prove_date` timestamp NULL DEFAULT current_timestamp(),
  `prove_img` text DEFAULT NULL,
  `prove_note` text DEFAULT NULL,
  `prove_status` int(11) DEFAULT NULL,
  `prove_student_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`prove_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.prove: ~0 rows (approximately)

-- Dumping structure for table master_as.publik
CREATE TABLE IF NOT EXISTS `publik` (
  `publik_id` int(11) NOT NULL AUTO_INCREMENT,
  `publik_no` varchar(50) NOT NULL,
  `publik_nama` varchar(225) NOT NULL,
  `publik_alamat` varchar(255) NOT NULL,
  `publik_telepon` varchar(50) NOT NULL,
  `publik_nik` varchar(25) NOT NULL,
  `publik_position_id` int(11) NOT NULL,
  `keperluan` text NOT NULL,
  `publik_status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`publik_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.publik: 0 rows
/*!40000 ALTER TABLE `publik` DISABLE KEYS */;
/*!40000 ALTER TABLE `publik` ENABLE KEYS */;

-- Dumping structure for table master_as.pulang
CREATE TABLE IF NOT EXISTS `pulang` (
  `pulang_id` int(11) NOT NULL AUTO_INCREMENT,
  `pulang_date` date NOT NULL,
  `pulang_days` int(11) NOT NULL,
  `pulang_note` text NOT NULL,
  `pulang_period_id` int(11) NOT NULL,
  `pulang_student_id` int(11) NOT NULL,
  `pulang_user_id` int(11) NOT NULL,
  PRIMARY KEY (`pulang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.pulang: ~0 rows (approximately)

-- Dumping structure for table master_as.query_logs
CREATE TABLE IF NOT EXISTS `query_logs` (
  `sqlnya` text DEFAULT NULL,
  `created_date` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.query_logs: ~0 rows (approximately)

-- Dumping structure for table master_as.raks_entry
CREATE TABLE IF NOT EXISTS `raks_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0,
  `majors_id` tinyint(3) NOT NULL,
  `period_id` int(11) NOT NULL,
  `proposal_status` enum('EMPTY','CREATED','SUBMITED','APPROVED') NOT NULL DEFAULT 'EMPTY',
  `approval_status` enum('PROCESS','APPROVED','KOORDINATOR','KEPALA SEKOLAH','KEPALA PENGEMBANG','KEPALA OPERASIONAL','KEPALA KEUANGAN','OWNER') NOT NULL DEFAULT 'PROCESS',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.raks_entry: ~0 rows (approximately)

-- Dumping structure for table master_as.request_proposal
CREATE TABLE IF NOT EXISTS `request_proposal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_id` int(11) NOT NULL,
  `noPermintaan` varchar(50) NOT NULL,
  `tanggalPermintaan` date NOT NULL DEFAULT current_timestamp(),
  `targetDate` date NOT NULL DEFAULT current_timestamp(),
  `requestType` enum('PROPOSAL','BARANG') DEFAULT NULL,
  `costCenter` tinyint(2) DEFAULT NULL,
  `attachment1` varchar(60) DEFAULT NULL,
  `attachment2` varchar(60) DEFAULT NULL,
  `attachment3` varchar(60) DEFAULT NULL,
  `attachment4` varchar(60) DEFAULT NULL,
  `attachment5` varchar(60) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_noPermintaan` (`noPermintaan`),
  KEY `fk_entry_proposal` (`detail_id`),
  CONSTRAINT `fk_entry_proposal` FOREIGN KEY (`detail_id`) REFERENCES `entry_detail` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.request_proposal: ~0 rows (approximately)

-- Dumping structure for table master_as.saldo
CREATE TABLE IF NOT EXISTS `saldo` (
  `saldo_id` int(11) NOT NULL AUTO_INCREMENT,
  `saldo_date` date NOT NULL,
  `saldo_debit` int(11) NOT NULL,
  `saldo_kredit` int(11) NOT NULL,
  `saldo_desc` text NOT NULL,
  `saldo_banking_id` int(11) NOT NULL,
  PRIMARY KEY (`saldo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.saldo: 0 rows
/*!40000 ALTER TABLE `saldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `saldo` ENABLE KEYS */;

-- Dumping structure for table master_as.saldo_awal
CREATE TABLE IF NOT EXISTS `saldo_awal` (
  `saldo_awal_id` int(11) NOT NULL AUTO_INCREMENT,
  `saldo_awal_account` int(11) NOT NULL,
  `saldo_awal_debit` int(11) NOT NULL DEFAULT 0,
  `saldo_awal_kredit` int(11) NOT NULL DEFAULT 0,
  `saldo_awal_date` date NOT NULL,
  `saldo_awal_user_id` int(11) NOT NULL,
  PRIMARY KEY (`saldo_awal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.saldo_awal: 0 rows
/*!40000 ALTER TABLE `saldo_awal` DISABLE KEYS */;
/*!40000 ALTER TABLE `saldo_awal` ENABLE KEYS */;

-- Dumping structure for table master_as.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `satuan_id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan_name` varchar(50) NOT NULL,
  `satuan_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`satuan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.satuan: 0 rows
/*!40000 ALTER TABLE `satuan` DISABLE KEYS */;
/*!40000 ALTER TABLE `satuan` ENABLE KEYS */;

-- Dumping structure for table master_as.satuan_barang
CREATE TABLE IF NOT EXISTS `satuan_barang` (
  `satuan_barang_id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(30) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`satuan_barang_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.satuan_barang: ~0 rows (approximately)

-- Dumping structure for table master_as.schedule
CREATE TABLE IF NOT EXISTS `schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_semester_id` int(11) NOT NULL,
  `schedule_class_id` int(11) NOT NULL,
  `schedule_lesson_id` int(11) NOT NULL,
  `schedule_day` text NOT NULL,
  `schedule_time` text NOT NULL,
  `schedule_jampel` int(11) NOT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.schedule: ~0 rows (approximately)

-- Dumping structure for table master_as.scheduled_emails
CREATE TABLE IF NOT EXISTS `scheduled_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_email` varchar(255) DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `last_attempt_at` datetime DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.scheduled_emails: ~0 rows (approximately)

-- Dumping structure for table master_as.scheduled_wa
CREATE TABLE IF NOT EXISTS `scheduled_wa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `receiver` varchar(20) NOT NULL,
  `msg` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.scheduled_wa: ~0 rows (approximately)

-- Dumping structure for table master_as.sekolah
CREATE TABLE IF NOT EXISTS `sekolah` (
  `sekolah_kode` int(11) NOT NULL AUTO_INCREMENT,
  `sekolah_id` char(10) NOT NULL,
  `sekolah_nama` varchar(200) NOT NULL,
  `sekolah_admin` char(100) NOT NULL,
  `sekolah_email` char(100) NOT NULL,
  `sekolah_password` char(100) NOT NULL,
  `sekolah_status` int(11) NOT NULL,
  `user_input_date` datetime NOT NULL,
  `user_modified_date` datetime NOT NULL,
  PRIMARY KEY (`sekolah_kode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.sekolah: 0 rows
/*!40000 ALTER TABLE `sekolah` DISABLE KEYS */;
/*!40000 ALTER TABLE `sekolah` ENABLE KEYS */;

-- Dumping structure for table master_as.semester
CREATE TABLE IF NOT EXISTS `semester` (
  `semester_id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_name` text NOT NULL,
  `semester_period_id` int(11) NOT NULL,
  `semester_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`semester_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.semester: ~2 rows (approximately)
INSERT INTO `semester` (`semester_id`, `semester_name`, `semester_period_id`, `semester_status`) VALUES
	(1, 'Semester 1', 2, 1),
	(2, 'Semester 2', 2, 0);

-- Dumping structure for table master_as.setting
CREATE TABLE IF NOT EXISTS `setting` (
  `setting_kode` int(11) NOT NULL AUTO_INCREMENT,
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(255) DEFAULT NULL,
  `setting_value` text DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `setting_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_kode`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.setting: ~39 rows (approximately)
INSERT INTO `setting` (`setting_kode`, `setting_id`, `setting_name`, `setting_value`, `sekolah_id`, `setting_last_update`) VALUES
	(1, 1, 'setting_school', 'DEMO ADMINSEKOLAH', '1', '2018-07-23 22:12:08'),
	(2, 2, 'setting_address', 'Jl. Cendrawasih EE 17 Kediri', '1', '2018-07-23 22:12:08'),
	(3, 3, 'setting_phone', '085767350081', '1', '2018-07-23 22:12:08'),
	(4, 4, 'setting_district', 'Kec. Ngasem', '1', '2018-07-23 22:12:08'),
	(5, 5, 'setting_city', 'Kediri', '1', '2018-07-23 22:12:08'),
	(6, 6, 'setting_logo', 'DEMO_ADMINSEKOLAH.png', '1', '2018-07-23 22:12:08'),
	(7, 7, 'setting_level', 'junior', '1', '2018-07-23 22:12:08'),
	(8, 8, 'setting_user_sms', 'demo@demo.net', '1', '2018-07-23 22:12:08'),
	(9, 9, 'setting_pass_sms', 'admin', '1', '2018-07-23 22:12:08'),
	(10, 10, 'setting_sms', 'N', '1', '2018-07-23 22:12:08'),
	(11, 11, 'setting_nip_kepsek', '-', '1', '2018-07-31 13:00:00'),
	(12, 12, 'setting_nama_kepsek', 'Abdullah', '1', '2018-07-31 13:00:00'),
	(13, 13, 'setting_nip_katu', '8951891859', '1', '2018-07-31 13:00:00'),
	(14, 14, 'setting_nama_katu', 'Supriadi', '1', '2018-07-31 13:00:00'),
	(15, 15, 'setting_nip_bendahara', '-', '1', '2018-07-31 13:00:00'),
	(16, 16, 'setting_nama_bendahara', 'khadijah kombih', '1', '2018-07-31 13:00:00'),
	(17, 17, 'setting_wa_center', '+6285767350081', '1', '2020-07-28 05:06:46'),
	(18, 18, 'setting_kartu_depan', '', '1', '2020-07-28 05:07:03'),
	(19, 19, 'setting_kartu_belakang', '', '1', '2020-07-28 05:07:07'),
	(20, 20, 'setting_notif', 'active', '1', '2020-07-28 05:07:07'),
	(21, 21, 'setting_wa_key', 'W81nF705d2IiFN6WW0CSbdeVY7lSOo', '', NULL),
	(90, 90, 'setting_email', '', '1', NULL),
	(91, 91, 'setting_website', 'https://adminsekolah.net/', '1', NULL),
	(92, 92, 'setting_whatsapp', '6285959475000', '1', NULL),
	(93, 93, 'setting_facebook', 'https://www.facebook.com/AdminSekolahNet/', '1', NULL),
	(94, 94, 'setting_instagram', 'https://instagram.com/adminsekolahnet/', '1', NULL),
	(95, 95, 'setting_youtube', 'https://www.youtube.com/channel/', '1', NULL),
	(96, 96, 'setting_telegram', 't.me/AdmSekolahIdw_bot', '1', NULL),
	(97, 97, 'setting_package', '3', '1', '2022-12-01 00:25:02'),
	(102, 102, 'va_ipaymu', '1179001233640003', 'Y', '2023-01-30 14:55:36'),
	(103, 103, 'key_ipaymu', '39F0ADF6-7E9D-4AEB-9934-53DB0145844E', 'Y', '2023-01-30 14:55:36'),
	(104, 104, 'mode_ipaymu', 'my', '', '2023-02-13 21:23:31'),
	(203, 203, 'mode_flip', 'my', '', NULL),
	(204, 204, 'api_secret_key', 'JDJ5JDEzJFNXMGFzVHpJcjRmRk1pWDZ2Z1g5SnV2TjN2QWpGSC51TE11NEdReTB0a0d5eUxmUU9CZVU2', '', '2024-07-25 20:57:45'),
	(205, 205, 'token_validasi', '$2y$13$MGI5fd8b8pbEjTbvxDREn.3wXojLa27SwEm/bvNEXOCcUOW87rUlG', '', '2024-07-25 20:58:25'),
	(206, 206, 'api_secret_key_test', 'JDJ5JDEzJHNXdkVvL2FNUkFrTTAxUDU0d0gxcC52YWFEdHU4NmgwR1c2NXdScS8zenA2WXQ4Q283M3BL', '', NULL),
	(207, 207, 'token_validasi_test', '$2y$13$airzQQ4ocKvjKB3zJyI/2.hOiiZywNLNigiBJlYP4Jq5ZhTFgBWPC', '', NULL),
	(208, 208, 'use_limit', 'N', '', NULL),
	(209, 209, 'limit_tabungan', '0', '', NULL);

-- Dumping structure for table master_as.settinghutang
CREATE TABLE IF NOT EXISTS `settinghutang` (
  `settinghutang_id` int(11) NOT NULL AUTO_INCREMENT,
  `settinghutang_period_id` int(11) DEFAULT NULL,
  `settinghutang_poshutang_id` int(11) DEFAULT NULL,
  `sekolah_id` char(100) NOT NULL,
  `settinghutang_input_date` timestamp NULL DEFAULT NULL,
  `settinghutang_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`settinghutang_id`),
  KEY `fk_poshutang_pos1_idx` (`settinghutang_poshutang_id`),
  KEY `fk_poshutang_period1_idx` (`settinghutang_period_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.settinghutang: ~0 rows (approximately)

-- Dumping structure for table master_as.setting_presensi_siswa
CREATE TABLE IF NOT EXISTS `setting_presensi_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area_absensi` int(11) NOT NULL,
  `radius` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lokasi_id` (`id_area_absensi`),
  CONSTRAINT `setting_presensi_siswa_ibfk_1` FOREIGN KEY (`id_area_absensi`) REFERENCES `area_absensi` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.setting_presensi_siswa: ~0 rows (approximately)

-- Dumping structure for table master_as.siswa_ket_aktif
CREATE TABLE IF NOT EXISTS `siswa_ket_aktif` (
  `siswa_ket_aktif_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_ket_aktif_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `keperluan` varchar(225) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_ket_aktif_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_ket_aktif: 0 rows
/*!40000 ALTER TABLE `siswa_ket_aktif` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_ket_aktif` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_ket_baik
CREATE TABLE IF NOT EXISTS `siswa_ket_baik` (
  `siswa_ket_baik_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_ket_baik_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `keperluan` varchar(225) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_ket_baik_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_ket_baik: 0 rows
/*!40000 ALTER TABLE `siswa_ket_baik` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_ket_baik` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_mutasi
CREATE TABLE IF NOT EXISTS `siswa_mutasi` (
  `siswa_mutasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_mutasi_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `sekolah_tujuan` varchar(225) NOT NULL,
  `alamat_sekolah_tujuan` text NOT NULL,
  `alasan_pindah` text NOT NULL,
  `file` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_mutasi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_mutasi: 0 rows
/*!40000 ALTER TABLE `siswa_mutasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_mutasi` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_penelitian
CREATE TABLE IF NOT EXISTS `siswa_penelitian` (
  `siswa_penelitian_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_penelitian_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `anggota_id` varchar(225) NOT NULL,
  `nama_kegiatan` varchar(225) NOT NULL,
  `nama_instansi` varchar(225) NOT NULL,
  `alamat_instansi` text NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_penelitian_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_penelitian: 0 rows
/*!40000 ALTER TABLE `siswa_penelitian` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_penelitian` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_prestasi
CREATE TABLE IF NOT EXISTS `siswa_prestasi` (
  `siswa_prestasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_prestasi_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tujuan` varchar(225) NOT NULL,
  `jenis` varchar(225) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_prestasi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_prestasi: 0 rows
/*!40000 ALTER TABLE `siswa_prestasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_prestasi` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_rekomendasi
CREATE TABLE IF NOT EXISTS `siswa_rekomendasi` (
  `siswa_rekomendasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_rekomendasi_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `keperluan` varchar(225) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_rekomendasi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_rekomendasi: 0 rows
/*!40000 ALTER TABLE `siswa_rekomendasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_rekomendasi` ENABLE KEYS */;

-- Dumping structure for table master_as.siswa_surat_lain
CREATE TABLE IF NOT EXISTS `siswa_surat_lain` (
  `siswa_surat_lain_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_surat_lain_no` varchar(50) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tujuan` varchar(225) NOT NULL,
  `ditujukan` varchar(225) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`siswa_surat_lain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.siswa_surat_lain: 0 rows
/*!40000 ALTER TABLE `siswa_surat_lain` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa_surat_lain` ENABLE KEYS */;

-- Dumping structure for table master_as.source_entry_detail
CREATE TABLE IF NOT EXISTS `source_entry_detail` (
  `detail_id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nominal` int(11) NOT NULL,
  KEY `fk_entry_detail` (`detail_id`),
  CONSTRAINT `fk_entry_detail` FOREIGN KEY (`detail_id`) REFERENCES `entry_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.source_entry_detail: ~0 rows (approximately)

-- Dumping structure for table master_as.sppd
CREATE TABLE IF NOT EXISTS `sppd` (
  `id_sppd` int(11) NOT NULL AUTO_INCREMENT,
  `no_sppd` varchar(225) NOT NULL,
  `perintah_id` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `transportasi` varchar(225) NOT NULL,
  `tmp_berangkat` varchar(225) NOT NULL,
  `tmp_tujuan` varchar(225) NOT NULL,
  `tgl_berangkat` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `lama_perjalanan` varchar(11) NOT NULL,
  `diperintah_id` int(11) NOT NULL,
  `anggota_id` varchar(225) NOT NULL,
  `instansi_anggaran` varchar(225) NOT NULL,
  `mata_anggaran` varchar(50) NOT NULL,
  `dasar_surat` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `id_users` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_sppd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.sppd: 0 rows
/*!40000 ALTER TABLE `sppd` DISABLE KEYS */;
/*!40000 ALTER TABLE `sppd` ENABLE KEYS */;

-- Dumping structure for table master_as.standar
CREATE TABLE IF NOT EXISTS `standar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `standar_majors_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.standar: ~0 rows (approximately)

-- Dumping structure for table master_as.student
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_nis` varchar(45) DEFAULT NULL,
  `student_nisn` varchar(45) DEFAULT NULL,
  `student_nik` varchar(50) DEFAULT NULL,
  `student_password` varchar(100) DEFAULT NULL,
  `student_full_name` varchar(255) DEFAULT NULL,
  `student_gender` enum('L','P','') DEFAULT NULL,
  `student_born_place` varchar(45) DEFAULT NULL,
  `student_born_date` date DEFAULT NULL,
  `student_img` varchar(255) DEFAULT NULL,
  `student_phone` varchar(45) DEFAULT NULL,
  `student_hobby` varchar(100) DEFAULT NULL,
  `student_address` text DEFAULT NULL,
  `student_name_of_mother` varchar(255) DEFAULT NULL,
  `student_name_of_father` varchar(255) DEFAULT NULL,
  `student_parent_phone` varchar(45) DEFAULT NULL,
  `student_id_telegram` text DEFAULT NULL,
  `class_class_id` int(11) DEFAULT NULL,
  `majors_majors_id` int(11) DEFAULT NULL,
  `student_status` tinyint(1) DEFAULT 1,
  `sekolah_id` char(100) NOT NULL,
  `student_input_date` timestamp NULL DEFAULT NULL,
  `student_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  KEY `fk_student_class1_idx` (`class_class_id`),
  KEY `fk_student_majors1_idx` (`majors_majors_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.student: ~0 rows (approximately)

-- Dumping structure for table master_as.student_token
CREATE TABLE IF NOT EXISTS `student_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `token_student_id` int(11) NOT NULL,
  `token_student` varchar(255) NOT NULL,
  `jwt_token` varchar(255) NOT NULL,
  `update_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.student_token: ~0 rows (approximately)

-- Dumping structure for table master_as.student_va
CREATE TABLE IF NOT EXISTS `student_va` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `no_va` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.student_va: ~0 rows (approximately)

-- Dumping structure for table master_as.subsatu
CREATE TABLE IF NOT EXISTS `subsatu` (
  `subsatu_id` int(11) NOT NULL AUTO_INCREMENT,
  `subsatu_pokok` int(11) NOT NULL,
  `subsatu_fungsional` int(11) NOT NULL,
  `subsatu_struktural` int(11) NOT NULL,
  `subsatu_kesra` int(11) NOT NULL,
  `subsatu_khusus` int(11) NOT NULL,
  `subsatu_prestasi` int(11) NOT NULL,
  `subsatu_poin` int(11) NOT NULL,
  `subsatu_gaji_id` int(11) NOT NULL,
  `subsatu_sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`subsatu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.subsatu: 0 rows
/*!40000 ALTER TABLE `subsatu` DISABLE KEYS */;
/*!40000 ALTER TABLE `subsatu` ENABLE KEYS */;

-- Dumping structure for table master_as.subtiga
CREATE TABLE IF NOT EXISTS `subtiga` (
  `subtiga_id` int(11) NOT NULL AUTO_INCREMENT,
  `subtiga_pinjam_bank` int(11) NOT NULL,
  `subtiga_organisasi` int(11) NOT NULL,
  `subtiga_koperasi` int(11) NOT NULL,
  `subtiga_program_bahasa` int(11) NOT NULL,
  `subtiga_majalah` int(11) NOT NULL,
  `subtiga_admin_bank` int(11) NOT NULL,
  `subtiga_infaq` int(11) NOT NULL,
  `subtiga_bpjs` int(11) NOT NULL,
  `subtiga_telemarket` int(11) NOT NULL,
  `subtiga_lain` int(11) NOT NULL,
  `subtiga_gaji_id` int(11) NOT NULL,
  `subtiga_sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`subtiga_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.subtiga: 0 rows
/*!40000 ALTER TABLE `subtiga` DISABLE KEYS */;
/*!40000 ALTER TABLE `subtiga` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_cuti
CREATE TABLE IF NOT EXISTS `surat_cuti` (
  `surat_cuti_id` int(11) NOT NULL AUTO_INCREMENT,
  `surat_cuti_no` varchar(225) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`surat_cuti_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.surat_cuti: 0 rows
/*!40000 ALTER TABLE `surat_cuti` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_cuti` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_dispensasi
CREATE TABLE IF NOT EXISTS `surat_dispensasi` (
  `dispensasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `dispensasi_nomor_surat_id` varchar(50) NOT NULL,
  `dispensasi_desc` text NOT NULL,
  `dispensasi_student_pj` int(11) NOT NULL,
  `dispensasi_student_id` varchar(225) NOT NULL,
  `dispensasi_date` date NOT NULL,
  `dispensasi_time_start` varchar(25) NOT NULL,
  `dispensasi_time_end` varchar(25) NOT NULL,
  `dispensasi_lokasi` varchar(225) NOT NULL,
  `dispensasi_users` int(11) NOT NULL,
  `dispensasi_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dispensasi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_dispensasi: 0 rows
/*!40000 ALTER TABLE `surat_dispensasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_dispensasi` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_jenis
CREATE TABLE IF NOT EXISTS `surat_jenis` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) NOT NULL,
  `id_user` varchar(12) NOT NULL,
  `kode_surat` varchar(40) NOT NULL,
  `tanggal_create` date NOT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_jenis: ~0 rows (approximately)

-- Dumping structure for table master_as.surat_keterangan
CREATE TABLE IF NOT EXISTS `surat_keterangan` (
  `keterangan_id` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan_nomor_surat` varchar(50) NOT NULL,
  `keterangan_student_id` int(11) NOT NULL,
  `keterangan_jenis` int(11) NOT NULL,
  `keterangan_users` int(11) NOT NULL,
  `keterangan_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`keterangan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_keterangan: 0 rows
/*!40000 ALTER TABLE `surat_keterangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_keterangan` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_lain_guru
CREATE TABLE IF NOT EXISTS `surat_lain_guru` (
  `surat_lain_guru_id` int(11) NOT NULL AUTO_INCREMENT,
  `surat_lain_guru_no` varchar(225) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`surat_lain_guru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.surat_lain_guru: 0 rows
/*!40000 ALTER TABLE `surat_lain_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_lain_guru` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_mengajar
CREATE TABLE IF NOT EXISTS `surat_mengajar` (
  `surat_mengajar_id` int(11) NOT NULL AUTO_INCREMENT,
  `surat_mengajar_no` varchar(225) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`surat_mengajar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.surat_mengajar: 0 rows
/*!40000 ALTER TABLE `surat_mengajar` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_mengajar` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_nomor
CREATE TABLE IF NOT EXISTS `surat_nomor` (
  `nomor_surat` varchar(50) NOT NULL,
  `tahun` varchar(225) NOT NULL,
  `input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`nomor_surat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_nomor: 0 rows
/*!40000 ALTER TABLE `surat_nomor` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_nomor` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_rekomendasi
CREATE TABLE IF NOT EXISTS `surat_rekomendasi` (
  `rekomendasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `rekomendasi_nomor_surat_id` varchar(50) NOT NULL,
  `rekomendasi_desc` text NOT NULL,
  `rekomendasi_student_id` varchar(225) NOT NULL,
  `rekomendasi_date_start` date NOT NULL,
  `rekomendasi_date_end` date NOT NULL,
  `rekomendasi_time_start` varchar(25) NOT NULL,
  `rekomendasi_time_end` varchar(25) NOT NULL,
  `rekomendasi_lokasi` varchar(225) NOT NULL,
  `rekomendasi_users` int(11) NOT NULL,
  `rekomendasi_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`rekomendasi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_rekomendasi: 0 rows
/*!40000 ALTER TABLE `surat_rekomendasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_rekomendasi` ENABLE KEYS */;

-- Dumping structure for table master_as.surat_tugas
CREATE TABLE IF NOT EXISTS `surat_tugas` (
  `id_surat_tugas` int(11) NOT NULL AUTO_INCREMENT,
  `no_surat_tugas` varchar(225) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `pengikut_id` varchar(225) NOT NULL,
  `deskripsi` text NOT NULL,
  `tgl_kegiatan` date NOT NULL,
  `tmp_kegiatan` varchar(225) NOT NULL,
  `alamat_kegiatan` varchar(225) NOT NULL,
  `keterangan` text NOT NULL,
  `file` varchar(50) NOT NULL,
  `status_surat` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_surat_tugas`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.surat_tugas: 0 rows
/*!40000 ALTER TABLE `surat_tugas` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_tugas` ENABLE KEYS */;

-- Dumping structure for table master_as.survei
CREATE TABLE IF NOT EXISTS `survei` (
  `survei_id` int(11) NOT NULL AUTO_INCREMENT,
  `survei_nama` varchar(225) NOT NULL,
  `survei_alamat` text NOT NULL,
  `survei_respon` int(11) NOT NULL,
  `survei_catatan` text NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`survei_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table master_as.survei: 0 rows
/*!40000 ALTER TABLE `survei` DISABLE KEYS */;
/*!40000 ALTER TABLE `survei` ENABLE KEYS */;

-- Dumping structure for table master_as.tabtest
CREATE TABLE IF NOT EXISTS `tabtest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tabtest: ~0 rows (approximately)

-- Dumping structure for table master_as.tahfidz
CREATE TABLE IF NOT EXISTS `tahfidz` (
  `tahfidz_id` int(11) NOT NULL AUTO_INCREMENT,
  `tahfidz_period_id` int(11) NOT NULL,
  `tahfidz_student_id` int(11) NOT NULL,
  `tahfidz_date` date DEFAULT NULL,
  `tahfidz_new` text DEFAULT NULL,
  `tahfidz_new_note` text DEFAULT NULL,
  `tahfidz_murojaah` text DEFAULT NULL,
  `tahfidz_murojaah_note` text DEFAULT NULL,
  `tahfidz_user_id` int(11) NOT NULL,
  `tahfidz_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `tahfidz_last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tahfidz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tahfidz: ~0 rows (approximately)

-- Dumping structure for table master_as.tax
CREATE TABLE IF NOT EXISTS `tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(50) NOT NULL,
  `tax_number` double NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tax: 0 rows
/*!40000 ALTER TABLE `tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax` ENABLE KEYS */;

-- Dumping structure for table master_as.tbl_disposisi
CREATE TABLE IF NOT EXISTS `tbl_disposisi` (
  `id_disposisi` int(11) NOT NULL AUTO_INCREMENT,
  `tujuan` varchar(250) NOT NULL,
  `isi_disposisi` mediumtext NOT NULL,
  `sifat` varchar(100) NOT NULL,
  `batas_waktu` date NOT NULL,
  `catatan` varchar(250) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `id_user` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_disposisi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tbl_disposisi: ~0 rows (approximately)

-- Dumping structure for table master_as.tbl_group
CREATE TABLE IF NOT EXISTS `tbl_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(75) NOT NULL,
  `group_kegiatan_id` int(11) DEFAULT NULL,
  `group_date` date DEFAULT NULL,
  `group_tempat` varchar(225) DEFAULT NULL,
  `group_keterangan` text DEFAULT NULL,
  `group_input_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tbl_group: 0 rows
/*!40000 ALTER TABLE `tbl_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_group` ENABLE KEYS */;

-- Dumping structure for table master_as.tbl_surat_keluar
CREATE TABLE IF NOT EXISTS `tbl_surat_keluar` (
  `id_surat` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_surat` varchar(50) NOT NULL,
  `tujuan` varchar(250) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_catat` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(4) NOT NULL,
  `no_agenda` varchar(50) NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tbl_surat_keluar: ~0 rows (approximately)

-- Dumping structure for table master_as.tbl_surat_masuk
CREATE TABLE IF NOT EXISTS `tbl_surat_masuk` (
  `id_surat` int(11) NOT NULL AUTO_INCREMENT,
  `no_agenda` int(11) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `asal_surat` varchar(250) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `indeks` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_diterima` date NOT NULL,
  `file` varchar(250) DEFAULT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(4) NOT NULL,
  `disposisi` enum('n','y') NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.tbl_surat_masuk: ~0 rows (approximately)

-- Dumping structure for table master_as.teaching
CREATE TABLE IF NOT EXISTS `teaching` (
  `teaching_id` int(11) NOT NULL AUTO_INCREMENT,
  `teaching_employee_id` int(11) NOT NULL,
  `teaching_semester_id` int(11) NOT NULL,
  `teaching_class_id` int(11) NOT NULL,
  `teaching_materi` varchar(225) NOT NULL,
  `teaching_lesson_id` int(11) NOT NULL,
  `teaching_month_id` int(11) NOT NULL,
  `teaching_date_create` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`teaching_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.teaching: 0 rows
/*!40000 ALTER TABLE `teaching` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaching` ENABLE KEYS */;

-- Dumping structure for table master_as.teaching_history
CREATE TABLE IF NOT EXISTS `teaching_history` (
  `teaching_id` int(11) NOT NULL AUTO_INCREMENT,
  `teaching_start` date NOT NULL,
  `teaching_end` date NOT NULL,
  `teaching_lesson` varchar(50) NOT NULL,
  `teaching_desc` text NOT NULL,
  `teaching_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`teaching_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.teaching_history: 0 rows
/*!40000 ALTER TABLE `teaching_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaching_history` ENABLE KEYS */;

-- Dumping structure for table master_as.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(45) DEFAULT NULL,
  `user_password` varchar(45) DEFAULT NULL,
  `user_full_name` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_description` text DEFAULT NULL,
  `user_role_role_id` int(11) DEFAULT NULL,
  `user_majors_id` int(11) NOT NULL,
  `sekolah_id` char(100) NOT NULL,
  `sekolah_nama` varchar(200) NOT NULL,
  `user_is_deleted` tinyint(1) DEFAULT 0,
  `user_input_date` timestamp NULL DEFAULT NULL,
  `user_last_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  KEY `fk_user_user_role1_idx` (`user_role_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.users: ~2 rows (approximately)
INSERT INTO `users` (`user_id`, `user_email`, `user_password`, `user_full_name`, `user_image`, `user_description`, `user_role_role_id`, `user_majors_id`, `sekolah_id`, `sekolah_nama`, `user_is_deleted`, `user_input_date`, `user_last_update`) VALUES
	(1, 'super@indoweb.id', '1ea9face76df498d435b18d833b99352', 'Superi Admin', 'Superistator.jpg', 'Super Admin', 3, 0, '', 'SMA TAZKIA', 0, '2018-01-16 03:19:33', '2025-07-21 02:08:07'),
	(2, 'ts@indoweb.id', '1ea9face76df498d435b18d833b99352', 'TS Indoweb', NULL, 'TS Indoweb', 1, 0, '', '', 0, '2024-05-22 04:04:34', '2025-07-21 02:07:18');

-- Dumping structure for table master_as.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.user_roles: ~8 rows (approximately)
INSERT INTO `user_roles` (`role_id`, `role_name`) VALUES
	(1, 'ADMIN'),
	(2, 'KASIR'),
	(3, 'SUPERADMIN'),
	(4, 'KEUANGAN'),
	(5, 'GURU'),
	(6, 'TATA USAHA'),
	(7, 'KESISWAAN'),
	(8, 'AKADEMIK');

-- Dumping structure for view master_as.v_laba_rugi
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_laba_rugi` (
	`id` INT(10) UNSIGNED NOT NULL,
	`tanggal` DATE NOT NULL,
	`kd_aktiva` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`jns_trans` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`debet` DECIMAL(52,2) NULL,
	`kredit` DECIMAL(52,2) NULL,
	`majors_id` INT(11) NOT NULL
);

-- Dumping structure for view master_as.v_laporan_jurnal
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_laporan_jurnal` (
	`idPeriod` INT(11) NULL,
	`idMajors` INT(11) NOT NULL,
	`noref` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`namaKas` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`idKas` INT(11) NOT NULL,
	`kodeKas` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`tanggal` DATE NULL,
	`idAkun` INT(11) NOT NULL,
	`akunBayar` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`jenisBayar` TEXT NULL COLLATE 'latin1_swedish_ci',
	`namaBulan` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`keterangan` MEDIUMTEXT NULL COLLATE 'latin1_swedish_ci',
	`nis` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`kelas` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`penerimaan` DECIMAL(10,0) NULL,
	`pengeluaran` DECIMAL(10,0) NULL,
	`jenisTransaksi` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
);

-- Dumping structure for view master_as.v_limit_tabungan_siswa
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_limit_tabungan_siswa` (
	`student_id` INT(11) NOT NULL,
	`student_full_name` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`student_nis` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`class_name` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`majors_short_name` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`withdraw_limit` INT(11) NULL
);

-- Dumping structure for view master_as.v_transaksi_tertunda
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_transaksi_tertunda` (
	`idMajors` INT(11) NOT NULL,
	`noref` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`namaKas` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`idKas` INT(11) NOT NULL,
	`kodeKas` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`tanggal` DATE NULL,
	`idAkun` INT(11) NOT NULL,
	`akunBayar` VARCHAR(1) NOT NULL COLLATE 'latin1_swedish_ci',
	`jenisBayar` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`idPeriod` INT(11) NULL,
	`namaBulan` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`keterangan` MEDIUMTEXT NULL COLLATE 'latin1_swedish_ci',
	`nis` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`kelas` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`penerimaan` DECIMAL(10,0) NULL,
	`pengeluaran` INT(1) NOT NULL,
	`jenisTransaksi` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
);

-- Dumping structure for table master_as.wanotif_reports
CREATE TABLE IF NOT EXISTS `wanotif_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `pending` int(11) unsigned DEFAULT 0,
  `sent` int(11) unsigned DEFAULT 0,
  `failed` int(11) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table master_as.wanotif_reports: ~0 rows (approximately)

-- Dumping structure for table master_as.worksheet
CREATE TABLE IF NOT EXISTS `worksheet` (
  `worksheet_id` int(11) NOT NULL AUTO_INCREMENT,
  `worksheet_majors_id` int(11) NOT NULL,
  `worksheet_period_id` int(11) NOT NULL,
  `worksheet_nama_kepsek` varchar(100) NOT NULL,
  `worksheet_nama_bendahara` varchar(100) NOT NULL,
  `worksheet_nama_komite` varchar(100) NOT NULL,
  `worksheet_nip_kepsek` varchar(100) NOT NULL,
  `worksheet_nip_bendahara` varchar(100) NOT NULL,
  `worksheet_email_komite` varchar(100) NOT NULL,
  `worksheet_nominal` float NOT NULL,
  `worksheet_status` enum('A','B','TS','S','D') NOT NULL DEFAULT 'B',
  `worksheet_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `worksheet_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`worksheet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.worksheet: ~0 rows (approximately)

-- Dumping structure for table master_as.workshop_history
CREATE TABLE IF NOT EXISTS `workshop_history` (
  `workshop_id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_start` date NOT NULL,
  `workshop_end` date NOT NULL,
  `workshop_organizer` text NOT NULL,
  `workshop_location` varchar(50) NOT NULL,
  `workshop_employee_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  PRIMARY KEY (`workshop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table master_as.workshop_history: 0 rows
/*!40000 ALTER TABLE `workshop_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `workshop_history` ENABLE KEYS */;

-- Dumping structure for trigger master_as.banking_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `banking_after_update` AFTER UPDATE ON `input_banking` FOR EACH ROW BEGIN

if NEW.input_banking_status='1' then

INSERT INTO banking
SET banking_period_id = NEW.input_banking_period_id,
	banking_noref = NEW.input_banking_noref,
	banking_debit = NEW.input_banking_debit,
    banking_kredit = NEW.input_banking_kredit,
    banking_account_id = NEW.input_banking_account_id,
    banking_date = NEW.input_banking_date,
    banking_code = NEW.input_banking_code,
    banking_student_id = NEW.input_banking_student_id,
    banking_note = NEW.input_banking_note,
    user_user_id = NEW.input_user_user_id,
    ipaymu_no_trans = NEW.input_ipaymu_no_trans,
    ipaymu_status = NEW.input_ipaymu_status,
    banking_status = NEW.input_banking_status;
END if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.bmi_payment_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `bmi_payment_after_insert` AFTER INSERT ON `bmi_payment` FOR EACH ROW BEGIN

#update istatus='LUNAS'
UPDATE ipaymu_transaksi a SET a.status='LUNAS', di_bayar = NEW.PAYMENT*1/100 WHERE a.va_no = NEW.VANO ;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.data_flip_callback_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `data_flip_callback_after_insert` AFTER INSERT ON `data_flip_callback` FOR EACH ROW BEGIN

if NEW.status='SUCCESSFUL' then

UPDATE flip_transaksi
SET status='SUCCESSFUL',
    di_bayar = NEW.amount
WHERE transactionId = NEW.bill_id;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.data_flip_callback_donasi_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `data_flip_callback_donasi_after_insert` AFTER INSERT ON `data_flip_callback_donasi` FOR EACH ROW BEGIN

if NEW.status='SUCCESSFUL' then

UPDATE flip_donasi
SET status='SUCCESSFUL',
    di_bayar = NEW.amount
WHERE transactionId = NEW.bill_id;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.data_flip_callback_tabungan_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `data_flip_callback_tabungan_after_insert` AFTER INSERT ON `data_flip_callback_tabungan` FOR EACH ROW BEGIN

if NEW.status='SUCCESSFUL' then

UPDATE flip_transaksi_tabungan
SET status='SUCCESSFUL',
    di_bayar = NEW.amount
WHERE transactionId = NEW.bill_id;

END if;

if NEW.status='FAILED' then

UPDATE flip_transaksi_tabungan
SET status='FAILED',
    di_bayar = NEW.amount
WHERE transactionId = NEW.bill_id;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.data_ipaymu_callback_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `data_ipaymu_callback_after_insert` AFTER INSERT ON `data_ipaymu_callback` FOR EACH ROW BEGIN

if NEW.status='berhasil' then

UPDATE ipaymu_transaksi   
SET status='LUNAS',     
    di_bayar = NEW.total
WHERE va_no = NEW.va;

END if;

if NEW.status='expired' then

UPDATE ipaymu_transaksi   
SET status='EXPIRED',     
    di_bayar = NEW.total
WHERE va_no = NEW.va;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.data_ipaymu_callback_tabungan_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `data_ipaymu_callback_tabungan_after_insert` AFTER INSERT ON `data_ipaymu_callback_tabungan` FOR EACH ROW BEGIN

if NEW.status='berhasil' then

UPDATE ipaymu_transaksi_tabungan
SET status='LUNAS',
    di_bayar = NEW.total
WHERE va_no = NEW.va;

END if;

if NEW.status='expired' then

UPDATE ipaymu_transaksi_tabungan
SET status='EXPIRED',
    di_bayar = NEW.total
WHERE va_no = NEW.va;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.flip_bebas_pay_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `flip_bebas_pay_after_update` AFTER UPDATE ON `bebas_pay` FOR EACH ROW BEGIN

DECLARE v_sum_bayar BIGINT(11);
if NEW.flip_status = 'LUNAS' then

SELECT SUM(a.bebas_pay_bill) INTO v_sum_bayar
from bebas_pay a 
WHERE a.bebas_bebas_id = OLD.bebas_bebas_id;

UPDATE bebas b
SET b.bebas_total_pay = v_sum_bayar
WHERE b.bebas_id = OLD.bebas_bebas_id;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.flip_donasi_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `flip_donasi_after_update` AFTER UPDATE ON `flip_donasi` FOR EACH ROW BEGIN

if NEW.status='SUCCESFULL' then

UPDATE donasi a
SET a.donasi_status = '1'
WHERE a.donasi_ref_id = OLD.noref;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.flip_transaksi_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `flip_transaksi_after_update` AFTER UPDATE ON `flip_transaksi` FOR EACH ROW BEGIN

DECLARE v_period VARCHAR(100);
DECLARE v_month_id  VARCHAR(100);
DECLARE v_majors_id  VARCHAR(100);
DECLARE v_student_full_name VARCHAR(250);
DECLARE v_sum_bayar BIGINT(11);
DECLARE v_account_id VARCHAR (250);

if NEW.status='SUCCESSFUL' then

UPDATE bulan a
SET a.bulan_status=1, 
    a.flip_status='LUNAS',
    a.bulan_noref = OLD.noref
WHERE a.flip_no_trans = OLD.id_transaksi;

UPDATE bebas_pay b
SET b.flip_status='LUNAS',
	 b.bebas_pay_noref = OLD.noref
WHERE b.flip_no_trans = OLD.id_transaksi;

SET v_month_id := '';

SELECT 
month_id, majors_id, period_id, student_full_name
INTO v_month_id, v_majors_id,  v_period, v_student_full_name
FROM
(
SELECT 1 kode, ifnull(MAX(0),'') month_id, max(b.majors_majors_id) majors_id, max(c.period_period_id) period_id, max(b.student_full_name) student_full_name
FROM (SELECT aa.student_student_id, aa.payment_payment_id, bb.ipaymu_no_trans from bebas aa, bebas_pay bb
WHERE aa.bebas_id=bb.bebas_bebas_id) a, student b, payment c 
WHERE a.student_student_id=b.student_id
AND a.payment_payment_id = c.payment_id
AND a.ipaymu_no_trans = OLD.id_transaksi
UNION
SELECT 0, a.month_month_id, b.majors_majors_id, c.period_period_id, b.student_full_name 
from bulan a, student b, payment c 
WHERE a.student_student_id=b.student_id
AND a.payment_payment_id = c.payment_id
AND a.ipaymu_no_trans = OLD.id_transaksi
) AS aaa ORDER BY kode LIMIT 1;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.flip_transaksi_tabungan_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `flip_transaksi_tabungan_after_update` AFTER UPDATE ON `flip_transaksi_tabungan` FOR EACH ROW BEGIN

if NEW.status='FAILED' then

UPDATE input_banking a
SET a.input_banking_debit = '0',
	 a.input_flip_status = 'FAILED',
    a.input_banking_status = '-2'
WHERE a.input_flip_no_trans = OLD.id_transaksi;

DELETE FROM banking WHERE input_flip_no_trans = OLD.id_transaksi;

END if;


END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.ipaymu_bebas_pay_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ipaymu_bebas_pay_after_update` AFTER UPDATE ON `bebas_pay` FOR EACH ROW BEGIN

DECLARE v_sum_bayar BIGINT(11);
if NEW.ipaymu_status = 'LUNAS' then

SELECT SUM(a.bebas_pay_bill) INTO v_sum_bayar
from bebas_pay a 
WHERE a.bebas_bebas_id = OLD.bebas_bebas_id;

UPDATE bebas b
SET b.bebas_total_pay = v_sum_bayar
WHERE b.bebas_id = OLD.bebas_bebas_id;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.ipaymu_transaksi_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ipaymu_transaksi_after_update` AFTER UPDATE ON `ipaymu_transaksi` FOR EACH ROW BEGIN

if NEW.status='LUNAS' then

UPDATE bulan a
SET a.bulan_status=1, 
    a.ipaymu_status='LUNAS'
WHERE a.ipaymu_no_trans = OLD.id_transaksi;

UPDATE bebas_pay b
SET b.ipaymu_status='LUNAS'
WHERE b.ipaymu_no_trans = OLD.id_transaksi;

END if;

if NEW.status='EXPIRED' then

UPDATE bulan a
SET a.ipaymu_status='EXPIRED'
WHERE a.ipaymu_no_trans = OLD.id_transaksi;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.ipaymu_transaksi_tabungan_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ipaymu_transaksi_tabungan_after_update` AFTER UPDATE ON `ipaymu_transaksi_tabungan` FOR EACH ROW BEGIN

if NEW.status='LUNAS' then

UPDATE input_banking a
SET a.input_ipaymu_status = 'LUNAS',
    a.input_banking_status = '1',
    a.input_banking_noref = OLD.noref
WHERE a.input_ipaymu_no_trans = OLD.id_transaksi;

END if;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.trg_after_insert_scheduled_wa
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_after_insert_scheduled_wa` AFTER INSERT ON `scheduled_wa` FOR EACH ROW BEGIN
    -- Cek apakah sudah ada record di wanotif_reports untuk hari ini
    IF EXISTS (SELECT 1 FROM wanotif_reports WHERE date = CURRENT_DATE) THEN
        -- Jika ada, update kolom pending (tambah 1)
        UPDATE wanotif_reports
        SET pending = pending + 1
        WHERE date = CURRENT_DATE;
    ELSE
        -- Jika belum ada, insert baris baru dengan pending = 1
        INSERT INTO wanotif_reports (date, pending)
        VALUES (CURRENT_DATE, 1);
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger master_as.trg_after_update_scheduled_wa
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_after_update_scheduled_wa` AFTER UPDATE ON `scheduled_wa` FOR EACH ROW BEGIN
    -- Hanya jika status berubah
    IF NEW.status <> OLD.status THEN

        -- Jika status menjadi 'sent'
        IF NEW.status = 'sent' THEN
            UPDATE wanotif_reports
            SET sent = sent + 1,
                pending = pending - 1
            WHERE date = CURRENT_DATE;
        
        -- Jika status menjadi 'failed'
        ELSEIF NEW.status = 'failed' THEN
            UPDATE wanotif_reports
            SET failed = failed + 1,
                pending = pending - 1
            WHERE date = CURRENT_DATE;
        END IF;

    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `presensi_area_v`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `presensi_area_v` AS SELECT `a`.`employee_id` AS `id_pegawai`, `a`.`employee_nip` AS `nip`, `a`.`employee_name` AS `nama_pegawai`, `a`.`employee_majors_id` AS `id_unit`, `a`.`employee_position_id` AS `id_jabatan`, `a`.`status_absen` AS `status_absen`, `a`.`status_absen_temp` AS `status_absen_temp`, `a`.`area_absen` AS `area_absen`, `a`.`jarak_radius` AS `jarak_radius` FROM `employee` AS `a` 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_laba_rugi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_laba_rugi` AS SELECT `a`.`id` AS `id`, `b`.`tanggal` AS `tanggal`, `c`.`account_code` AS `kd_aktiva`, `c`.`account_description` AS `jns_trans`, sum(`a`.`debet`) AS `debet`, sum(`a`.`kredit`) AS `kredit`, `b`.`sekolah_id` AS `majors_id` FROM ((`jurnal_umum_detail` `a` join `jurnal_umum` `b` on(`a`.`id_jurnal` = `b`.`id`)) join `account` `c` on(`c`.`account_code` = `a`.`account_code`)) WHERE `c`.`account_code` like '4%' OR `c`.`account_code` like '5%' GROUP BY `c`.`account_code` 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_laporan_jurnal`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_laporan_jurnal` AS select `py`.`period_period_id` AS `idPeriod`,`ad`.`account_majors_id` AS `idMajors`,`bl`.`bulan_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`bl`.`bulan_date_pay` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`p`.`pos_name` AS `jenisBayar`,`m`.`month_name` AS `namaBulan`,concat(`ak`.`account_description`,' - T.A ',`pr`.`period_start`,'/',`pr`.`period_end`,' - ',`m`.`month_name`) AS `keterangan`,`s`.`student_nis` AS `nis`,`s`.`student_full_name` AS `nama`,`c`.`class_name` AS `kelas`,`bl`.`bulan_bill` AS `penerimaan`,0 AS `pengeluaran`,'Bulan' AS `jenisTransaksi` from ((((((((`bulan` `bl` join `account` `ad` on(`ad`.`account_id` = `bl`.`bulan_account_id`)) join `payment` `py` on(`py`.`payment_id` = `bl`.`payment_payment_id`)) join `pos` `p` on(`p`.`pos_id` = `py`.`pos_pos_id`)) join `account` `ak` on(`ak`.`account_id` = `p`.`account_account_id`)) join `period` `pr` on(`pr`.`period_id` = `py`.`period_period_id`)) join `month` `m` on(`m`.`month_id` = `bl`.`month_month_id`)) join `student` `s` on(`s`.`student_id` = `bl`.`student_student_id`)) join `class` `c` on(`c`.`class_id` = `s`.`class_class_id`)) where `bl`.`bulan_status` = 1 and `bl`.`bulan_bill` > 0 union all select `py`.`period_period_id` AS `idPeriod`,`ad`.`account_majors_id` AS `idMajors`,`bp`.`bebas_pay_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`bp`.`bebas_pay_input_date` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`p`.`pos_name` AS `jenisBayar`,'' AS `namaBulan`,concat(`ak`.`account_description`,' - T.A ',`pr`.`period_start`,'/',`pr`.`period_end`) AS `keterangan`,`s`.`student_nis` AS `nis`,`s`.`student_full_name` AS `nama`,`c`.`class_name` AS `kelas`,`bp`.`bebas_pay_bill` AS `penerimaan`,0 AS `pengeluaran`,'Bebas' AS `jenisTransaksi` from ((((((((`bebas_pay` `bp` join `bebas` `b` on(`b`.`bebas_id` = `bp`.`bebas_bebas_id`)) join `student` `s` on(`s`.`student_id` = `b`.`student_student_id`)) join `class` `c` on(`c`.`class_id` = `s`.`class_class_id`)) join `payment` `py` on(`py`.`payment_id` = `b`.`payment_payment_id`)) join `pos` `p` on(`p`.`pos_id` = `py`.`pos_pos_id`)) join `period` `pr` on(`pr`.`period_id` = `py`.`period_period_id`)) join `account` `ak` on(`ak`.`account_id` = `p`.`account_account_id`)) join `account` `ad` on(`ad`.`account_id` = `bp`.`bebas_pay_account_id`)) where `bp`.`bebas_pay_noref` is not null union all select 0 AS `idPeriod`,`ad`.`account_majors_id` AS `idMajors`,`d`.`debit_kas_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`d`.`debit_date` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`ak`.`account_description` AS `jenisBayar`,'' AS `namaBulan`,`d`.`debit_desc` AS `keterangan`,'' AS `nis`,'' AS `nama`,'' AS `kelas`,`d`.`debit_value` AS `penerimaan`,0 AS `pengeluaran`,'Debit' AS `jenisTransaksi` from ((`debit` `d` join `account` `ad` on(`ad`.`account_id` = `d`.`debit_kas_account_id`)) join `account` `ak` on(`ak`.`account_id` = `d`.`debit_account_id`)) union all select 0 AS `idPeriod`,`ad`.`account_majors_id` AS `idMajors`,`k`.`kredit_kas_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`k`.`kredit_date` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`ak`.`account_description` AS `jenisBayar`,'' AS `namaBulan`,`k`.`kredit_desc` AS `keterangan`,'' AS `nis`,'' AS `nama`,'' AS `kelas`,0 AS `penerimaan`,`k`.`kredit_value` AS `pengeluaran`,'Kredit' AS `jenisTransaksi` from ((`kredit` `k` join `account` `ad` on(`ad`.`account_id` = `k`.`kredit_kas_account_id`)) join `account` `ak` on(`ak`.`account_id` = `k`.`kredit_account_id`)) 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_limit_tabungan_siswa`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_limit_tabungan_siswa` AS SELECT `student`.`student_id` AS `student_id`, `student`.`student_full_name` AS `student_full_name`, `student`.`student_nis` AS `student_nis`, `class`.`class_name` AS `class_name`, `majors`.`majors_short_name` AS `majors_short_name`, coalesce(`banking_limit`.`limit`,0) AS `withdraw_limit` FROM (((`student` join `majors` on(`majors`.`majors_id` = `student`.`majors_majors_id`)) join `class` on(`class`.`class_id` = `student`.`class_class_id`)) left join `banking_limit` on(`banking_limit`.`nis` = `student`.`student_nis`)) 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_transaksi_tertunda`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_transaksi_tertunda` AS select `ad`.`account_majors_id` AS `idMajors`,`bl`.`bulan_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`bl`.`bulan_date_pay` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`p`.`pos_name` AS `jenisBayar`,`py`.`period_period_id` AS `idPeriod`,`m`.`month_name` AS `namaBulan`,concat(`ak`.`account_description`,' - T.A ',`pr`.`period_start`,'/',`pr`.`period_end`,' - ',`m`.`month_name`) AS `keterangan`,`s`.`student_nis` AS `nis`,`s`.`student_full_name` AS `nama`,`c`.`class_name` AS `kelas`,`bl`.`bulan_bill` AS `penerimaan`,0 AS `pengeluaran`,'Bulan' AS `jenisTransaksi` from ((((((((((`bulan` `bl` join `account` `ad` on(`ad`.`account_id` = `bl`.`bulan_account_id`)) join `payment` `py` on(`py`.`payment_id` = `bl`.`payment_payment_id`)) join `pos` `p` on(`p`.`pos_id` = `py`.`pos_pos_id`)) join `account` `ak` on(`ak`.`account_id` = `p`.`account_account_id`)) join `period` `pr` on(`pr`.`period_id` = `py`.`period_period_id`)) join `month` `m` on(`m`.`month_id` = `bl`.`month_month_id`)) join `student` `s` on(`s`.`student_id` = `bl`.`student_student_id`)) join `class` `c` on(`c`.`class_id` = `s`.`class_class_id`)) left join `kas` `ks` on(`ks`.`kas_noref` = `bl`.`bulan_noref`)) left join `jurnal_umum` `ju` on(`ju`.`noref` = `bl`.`bulan_noref`)) where `bl`.`bulan_status` = 1 and `bl`.`bulan_bill` > 0 and `ks`.`kas_noref` is null and `ju`.`noref` is null union all select `ad`.`account_majors_id` AS `idMajors`,`bp`.`bebas_pay_noref` AS `noref`,`ad`.`account_description` AS `namaKas`,`ad`.`account_id` AS `idKas`,`ad`.`account_code` AS `kodeKas`,`bp`.`bebas_pay_input_date` AS `tanggal`,`ak`.`account_id` AS `idAkun`,`ak`.`account_code` AS `akunBayar`,`p`.`pos_name` AS `jenisBayar`,`py`.`period_period_id` AS `idPeriod`,'' AS `namaBulan`,concat(`ak`.`account_description`,' - T.A ',`pr`.`period_start`,'/',`pr`.`period_end`) AS `keterangan`,`s`.`student_nis` AS `nis`,`s`.`student_full_name` AS `nama`,`c`.`class_name` AS `kelas`,`bp`.`bebas_pay_bill` AS `penerimaan`,0 AS `pengeluaran`,'Bebas' AS `jenisTransaksi` from ((((((((((`bebas_pay` `bp` join `bebas` `b` on(`b`.`bebas_id` = `bp`.`bebas_bebas_id`)) join `student` `s` on(`s`.`student_id` = `b`.`student_student_id`)) join `class` `c` on(`c`.`class_id` = `s`.`class_class_id`)) join `payment` `py` on(`py`.`payment_id` = `b`.`payment_payment_id`)) join `pos` `p` on(`p`.`pos_id` = `py`.`pos_pos_id`)) join `period` `pr` on(`pr`.`period_id` = `py`.`period_period_id`)) join `account` `ak` on(`ak`.`account_id` = `p`.`account_account_id`)) join `account` `ad` on(`ad`.`account_id` = `bp`.`bebas_pay_account_id`)) left join `kas` `ks` on(`ks`.`kas_noref` = `bp`.`bebas_pay_noref`)) left join `jurnal_umum` `ju` on(`ju`.`noref` = `bp`.`bebas_pay_noref`)) where `bp`.`bebas_pay_noref` is not null and `ks`.`kas_noref` is null and `ju`.`noref` is null 
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
