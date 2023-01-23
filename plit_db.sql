-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for plit_test
DROP DATABASE IF EXISTS `plit_test`;
CREATE DATABASE IF NOT EXISTS `plit_test` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `plit_test`;

-- Dumping structure for table plit_test.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` decimal(20,0) DEFAULT 0,
  `price` decimal(20,2) DEFAULT 0.00,
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active,2=Inactive',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table plit_test.product: ~0 rows (approximately)
DELETE FROM `product`;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `slug`, `quantity`, `price`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
	(8, 'Cat\'s Eye Shirt Collection', 'cat-s-eye-shirt-collection', 200, 2000.00, '1', '2023-01-23 02:05:37', 1, '2023-01-23 02:44:30', 1),
	(9, 'A4Tech Mouse', 'a4tech-mouse', 125, 750.00, '1', '2023-01-23 02:52:48', 1, '2023-01-23 02:52:48', 1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Dumping structure for table plit_test.product_detail
DROP TABLE IF EXISTS `product_detail`;
CREATE TABLE IF NOT EXISTS `product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `features` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table plit_test.product_detail: ~1 rows (approximately)
DELETE FROM `product_detail`;
/*!40000 ALTER TABLE `product_detail` DISABLE KEYS */;
INSERT INTO `product_detail` (`id`, `product_id`, `description`, `features`, `image_url`) VALUES
	(1, 8, 'Cat\'s Eye shirt collection for all sizes', '<ul><li>Cat\'s Eye Shirt S</li><li>Cat\'s Eye Shirt M</li><li>Cat\'s Eye Shirt L</li><li>Cat\'s Eye Shirt XL</li><li>Cat\'s Eye Shirt XXL</li></ul>', 'https://www.observerbd.com/2019/08/02/observerbd.com_1564766709.jpg'),
	(2, 9, 'A4Tech Mouse', '<ul><li>A4Tech wired Mouse</li><li>A4Tech wireless Mouse<br></li></ul>', 'https://udvabony.com/wp-content/uploads/2022/01/b49ea539522adc2052708d540795b332-scaled.jpg');
/*!40000 ALTER TABLE `product_detail` ENABLE KEYS */;

-- Dumping structure for table plit_test.product_log
DROP TABLE IF EXISTS `product_log`;
CREATE TABLE IF NOT EXISTS `product_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `action` enum('0','1','2','3') COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '1=Create, 2=Update, 3=Delete',
  `taken_at` datetime DEFAULT NULL,
  `taken_by` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table plit_test.product_log: ~3 rows (approximately)
DELETE FROM `product_log`;
/*!40000 ALTER TABLE `product_log` DISABLE KEYS */;
INSERT INTO `product_log` (`id`, `product_id`, `action`, `taken_at`, `taken_by`) VALUES
	(1, 8, '2', '2023-01-23 02:41:41', 1),
	(2, 8, '2', '2023-01-23 02:44:30', 1),
	(3, 8, '2', '2023-01-23 02:45:29', 1),
	(4, 9, '1', '2023-01-23 02:52:49', 1);
/*!40000 ALTER TABLE `product_log` ENABLE KEYS */;

-- Dumping structure for table plit_test.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(12) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(12) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table plit_test.users: ~0 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `group_id`, `name`, `email`, `phone`, `photo`, `username`, `password`, `remember_token`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
	(1, 1, 'Sadrul Hossain', 'hossainsadrul@gmail.com', '+8801885547800', '63cd762d25d6d_1.PNG', 'admin', '$2y$10$REOAlERxTnbVmHytUw6qOOHv7OU.GYB4ur.lUuvhGMgCvPWiAmnQC', NULL, '1', '2023-01-22 16:58:20', 1, '2023-01-22 17:45:17', 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table plit_test.user_group
DROP TABLE IF EXISTS `user_group`;
CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table plit_test.user_group: ~0 rows (approximately)
DELETE FROM `user_group`;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` (`id`, `name`) VALUES
	(1, 'Admin');
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
