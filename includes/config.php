<?php
/**
 * Home Service Center - কনফিগারেশন ফাইল
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Dhaka');

define('SITE_NAME', 'হোম সার্ভিস সেন্টার');
define('SITE_SLOGAN', 'আপনার ঘরে নিরাপদ ও বিশ্বস্ত হোম সার্ভিস');
define('SITE_TELEGRAM', 'kemlu09');
define('SITE_TELEGRAM_URL', 'https://t.me/kemlu09');

define('DB_DRIVER_CHOICE', 'auto'); 

define('DB_HOST', 'localhost');
define('DB_NAME', 'fellesxy_service');
define('DB_USER', 'fellesxy_service');
define('DB_PASS', 'fellesxy_service');
define('DB_PORT', '3306');

define('SQLITE_PATH', __DIR__ . '/../data/homeservice.sqlite');

// স্ট্রং অ্যাডমিন ক্রেডেনশিয়াল
define('DEFAULT_ADMIN_USER', 'hsc_admin_root');
define('DEFAULT_ADMIN_PASS', 'Hsc#2026$Adm9!Kq8');
