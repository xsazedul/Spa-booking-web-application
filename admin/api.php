<?php
/**
 * Admin AJAX & Action API Handler
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/auth.php';

if (!isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'অননুমোদিত অ্যাক্সেস। অনুগ্রহ করে লগইন করুন।'], JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_REQUEST['action'] ?? '';
$pdo = getDBConnection();

// ১. স্ট্যাটাস পরিবর্তন
if ($action === 'update_status') {
    $id = intval($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $notes = trim($_POST['admin_notes'] ?? '');

    $validStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
    if (!$id || !in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'ভুল তথ্য প্রদান করা হয়েছে।'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ?, admin_notes = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $id]);

        echo json_encode([
            'success' => true,
            'message' => "বুকিং স্ট্যাটাস সফলভাবে '{$status}' এ পরিবর্তন করা হয়েছে।"
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'ডাটাবেজ ত্রুটি: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ২. বুকিং ডিলিট
if ($action === 'delete_booking') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'অবৈধ আইডি।'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'বুকিংটি সফলভাবে মুছে ফেলা হয়েছে।'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'ডাটাবেজ ত্রুটি: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ৩. পাসওয়ার্ড পরিবর্তন
if ($action === 'change_password') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $adminId = $_SESSION['admin_id'];

    if (empty($currentPass) || strlen($newPass) < 6) {
        echo json_encode(['success' => false, 'message' => 'নতুন পাসওয়ার্ড অন্তত ৬ অক্ষরের হতে হবে।'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($currentPass, $hash)) {
            echo json_encode(['success' => false, 'message' => 'বর্তমান পাসওয়ার্ড সঠিক নয়।'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
        $updateStmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $updateStmt->execute([$newHash, $adminId]);

        echo json_encode(['success' => true, 'message' => 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'ডাটাবেজ ত্রুটি: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ৪. CSV এক্সপোর্ট
if ($action === 'export_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_export_' . date('Y-m-d_H-i') . '.csv');

    $output = fopen('php://output', 'w');
    // UTF-8 BOM for Excel Bengali rendering
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['ID', 'Request ID', 'Customer Name', 'Provider Gender', 'Mobile', 'Age', 'Selected Services', 'Date', 'Time', 'Address', 'Status', 'IP Address', 'GPS Lat', 'GPS Lon', 'Created At']);

    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY id DESC");
    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['id'],
            $row['request_id'],
            $row['customer_name'],
            $row['provider_gender'],
            $row['customer_mobile'],
            $row['customer_age'],
            $row['selected_services'],
            $row['service_date'],
            $row['service_time'],
            $row['service_address'],
            $row['status'],
            $row['ip_address'],
            $row['latitude'],
            $row['longitude'],
            $row['created_at']
        ]);
    }
    fclose($output);
    exit;
}

echo json_encode(['success' => false, 'message' => 'অজ্ঞাত অ্যাকশন।'], JSON_UNESCAPED_UNICODE);
