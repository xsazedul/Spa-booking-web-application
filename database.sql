-- Home Service Center MySQL Database Schema
-- cPanel phpMyAdmin Import File

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+06:00";

-- Table structure for table `bookings`
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` varchar(100) NOT NULL,
  `customer_name` varchar(191) NOT NULL,
  `provider_gender` varchar(50) NOT NULL,
  `customer_mobile` varchar(50) NOT NULL,
  `customer_age` varchar(50) DEFAULT NULL,
  `provider_age_range` varchar(100) DEFAULT NULL,
  `selected_services` text DEFAULT NULL,
  `service_date` varchar(50) DEFAULT NULL,
  `service_time` varchar(100) DEFAULT NULL,
  `service_address` text NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `gps_address` text DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `admins`
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Account
-- Username: hsc_admin_root
-- Password: Hsc#2026$Adm9!Kq8
INSERT INTO `admins` (`id`, `username`, `password_hash`, `name`, `created_at`) 
VALUES (1, 'hsc_admin_root', '$2y$10$vrqw4r2TAF0aZFLeKV1xE.ML3HRX2P1Rvt1z22MapTw9FnSbwf0w6', 'প্রধান অ্যাডমিন', NOW())
ON DUPLICATE KEY UPDATE `password_hash`='$2y$10$vrqw4r2TAF0aZFLeKV1xE.ML3HRX2P1Rvt1z22MapTw9FnSbwf0w6';

COMMIT;
