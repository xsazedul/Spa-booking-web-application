<?php
/**
 * Database Connection & Auto-Migration Handler (cPanel & Localhost Ready)
 */
require_once __DIR__ . '/config.php';

function getDBConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $driverChoice = defined('DB_DRIVER_CHOICE') ? DB_DRIVER_CHOICE : 'auto';
    $connected = false;

    // ১. MySQL ট্রাই করা
    if ($driverChoice === 'mysql' || $driverChoice === 'auto') {
        try {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $port = defined('DB_PORT') ? DB_PORT : '3306';
            $dbname = defined('DB_NAME') ? DB_NAME : '';
            $user = defined('DB_USER') ? DB_USER : '';
            $pass = defined('DB_PASS') ? DB_PASS : '';

            if (!empty($dbname) && !empty($user)) {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                $pdo = new PDO($dsn, $user, $pass, $options);
                $connected = true;
                initMySQLTables($pdo);
            }
        } catch (Throwable $e) {
            if ($driverChoice === 'mysql') {
                die("MySQL Connection Error: " . $e->getMessage());
            }
            $connected = false;
        }
    }

    // ২. SQLite ফলব্যাক
    if (!$connected && ($driverChoice === 'sqlite' || $driverChoice === 'auto')) {
        $sqlitePath = defined('SQLITE_PATH') ? SQLITE_PATH : __DIR__ . '/../data/homeservice.sqlite';
        $sqliteDir = dirname($sqlitePath);
        if (!is_dir($sqliteDir)) {
            @mkdir($sqliteDir, 0777, true);
        }

        try {
            $dsn = "sqlite:" . $sqlitePath;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            $pdo = new PDO($dsn, null, null, $options);
            initSQLiteTables($pdo);
            $connected = true;
        } catch (Throwable $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    return $pdo;
}

function initMySQLTables(PDO $pdo) {
    try {
        // Bookings table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `bookings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `request_id` VARCHAR(100) NOT NULL UNIQUE,
            `customer_name` VARCHAR(191) NOT NULL,
            `provider_gender` VARCHAR(50) NOT NULL,
            `customer_mobile` VARCHAR(50) NOT NULL,
            `customer_age` VARCHAR(50) NULL,
            `provider_age_range` VARCHAR(100) NULL,
            `selected_services` TEXT NULL,
            `service_date` VARCHAR(50) NULL,
            `service_time` VARCHAR(100) NULL,
            `service_address` TEXT NOT NULL,
            `latitude` VARCHAR(50) NULL,
            `longitude` VARCHAR(50) NULL,
            `gps_address` TEXT NULL,
            `ip_address` VARCHAR(100) NULL,
            `user_agent` TEXT NULL,
            `status` VARCHAR(50) DEFAULT 'Pending',
            `admin_notes` TEXT NULL,
            `created_at` DATETIME NULL,
            `updated_at` DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Admins table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(100) NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL,
            `name` VARCHAR(100) NULL,
            `created_at` DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Check if admin exists
        $adminUser = defined('DEFAULT_ADMIN_USER') ? DEFAULT_ADMIN_USER : 'hsc_admin_root';
        $adminPass = defined('DEFAULT_ADMIN_PASS') ? DEFAULT_ADMIN_PASS : 'Hsc#2026$Adm9!Kq8';

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `admins` WHERE `username` = ?");
        $stmt->execute([$adminUser]);
        if ($stmt->fetchColumn() == 0) {
            $insertStmt = $pdo->prepare("INSERT INTO `admins` (`username`, `password_hash`, `name`, `created_at`) VALUES (?, ?, ?, ?)");
            $insertStmt->execute([
                $adminUser,
                password_hash($adminPass, PASSWORD_BCRYPT),
                'প্রধান অ্যাডমিন',
                date('Y-m-d H:i:s')
            ]);
        }
    } catch (Throwable $e) {
        // Table initialization notice
    }
}

function initSQLiteTables(PDO $pdo) {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            request_id TEXT NOT NULL UNIQUE,
            customer_name TEXT NOT NULL,
            provider_gender TEXT NOT NULL,
            customer_mobile TEXT NOT NULL,
            customer_age TEXT,
            provider_age_range TEXT,
            selected_services TEXT,
            service_date TEXT,
            service_time TEXT,
            service_address TEXT NOT NULL,
            latitude TEXT,
            longitude TEXT,
            gps_address TEXT,
            ip_address TEXT,
            user_agent TEXT,
            status TEXT DEFAULT 'Pending',
            admin_notes TEXT,
            created_at DATETIME,
            updated_at DATETIME
        );");

        $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            name TEXT,
            created_at DATETIME
        );");

        $adminUser = defined('DEFAULT_ADMIN_USER') ? DEFAULT_ADMIN_USER : 'hsc_admin_root';
        $adminPass = defined('DEFAULT_ADMIN_PASS') ? DEFAULT_ADMIN_PASS : 'Hsc#2026$Adm9!Kq8';

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
        $stmt->execute([$adminUser]);
        if ($stmt->fetchColumn() == 0) {
            $insertStmt = $pdo->prepare("INSERT INTO admins (username, password_hash, name, created_at) VALUES (?, ?, ?, ?)");
            $insertStmt->execute([
                $adminUser,
                password_hash($adminPass, PASSWORD_BCRYPT),
                'প্রধান অ্যাডমিন',
                date('Y-m-d H:i:s')
            ]);
        }
    } catch (Throwable $e) {
        // SQLite table initialization notice
    }
}
