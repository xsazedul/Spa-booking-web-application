<?php
/**
 * Booking Submission API Endpoint
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'অননুমোদিত রিকোয়েস্ট মেথড।'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = $_POST;
if (empty($input)) {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true) ?: [];
}

$customerName    = trim($input['customer_name'] ?? '');
$providerGender  = trim($input['provider_gender'] ?? '');
$customerMobile  = trim($input['customer_mobile'] ?? '');
$customerAge     = trim($input['customer_age'] ?? '');
$serviceAddress  = trim($input['service_address'] ?? '');
$providerAgeRange= trim($input['provider_age_range'] ?? '');
$serviceDate     = trim($input['service_date'] ?? '');
$serviceTime     = trim($input['service_time'] ?? '');
$latitude        = trim($input['latitude'] ?? '');
$longitude       = trim($input['longitude'] ?? '');
$gpsAddress      = trim($input['gps_address'] ?? '');
$services        = $input['selected_services'] ?? [];

if (is_array($services)) {
    $servicesText = implode(', ', array_map('trim', $services));
} else {
    $servicesText = trim($services);
}

$errors = [];

if (empty($customerName)) {
    $errors[] = 'অনুগ্রহ করে গ্রাহকের নাম প্রদান করুন।';
}

if (empty($providerGender) || !in_array($providerGender, ['male', 'female', 'পুরুষ', 'মহিলা'])) {
    $errors[] = 'অনুগ্রহ করে আপনি কার থেকে সেবা নিতে চান তা নির্বাচন করুন।';
}

if (empty($customerMobile)) {
    $errors[] = 'অনুগ্রহ করে মোবাইল নম্বর প্রদান করুন।';
} elseif (!preg_match('/^(?:\+?88|01)?\d{9,11}$/', str_replace(['-', ' '], '', $customerMobile))) {
    $errors[] = 'অনুগ্রহ করে একটি সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন।';
}

if (empty($serviceAddress) && empty($gpsAddress)) {
    $errors[] = 'অনুগ্রহ করে সার্ভিস নেওয়ার ঠিকানা অথবা লোকেশন প্রদান করুন।';
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'message' => $errors[0],
        'errors' => $errors
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($serviceAddress) && !empty($gpsAddress)) {
    $serviceAddress = $gpsAddress;
}

function getClientIP() {
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ips = explode(',', $_SERVER[$key]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}

$clientIP = getClientIP();
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

$datePrefix = date('Ymd');
$randomSuffix = strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));
$requestId = "HSC-{$datePrefix}-{$randomSuffix}";

try {
    $pdo = getDBConnection();
    $now = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO bookings (
        request_id,
        customer_name,
        provider_gender,
        customer_mobile,
        customer_age,
        provider_age_range,
        selected_services,
        service_date,
        service_time,
        service_address,
        latitude,
        longitude,
        gps_address,
        ip_address,
        user_agent,
        status,
        created_at
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?
    )");

    $normalizedGender = ($providerGender === 'male' || $providerGender === 'পুরুষ') ? 'পুরুষ' : 'মহিলা';

    $stmt->execute([
        $requestId,
        $customerName,
        $normalizedGender,
        $customerMobile,
        $customerAge,
        $providerAgeRange,
        $servicesText,
        $serviceDate ?: date('Y-m-d'),
        $serviceTime ?: 'যেকোনো সুবিধাজনক সময়',
        $serviceAddress,
        $latitude ?: null,
        $longitude ?: null,
        $gpsAddress ?: null,
        $clientIP,
        $userAgent,
        $now
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'আপনার সার্ভিস বুকিং সফলভাবে গৃহীত হয়েছে! আমাদের প্রতিনিধি অতি দ্রুত আপনার সাথে যোগাযোগ করবেন।',
        'data' => [
            'request_id' => $requestId,
            'customer_name' => $customerName,
            'provider_gender' => $normalizedGender,
            'customer_mobile' => $customerMobile,
            'customer_age' => $customerAge,
            'selected_services' => $servicesText,
            'service_date' => $serviceDate ?: date('Y-m-d'),
            'service_time' => $serviceTime ?: 'যেকোনো সুবিধাজনক সময়',
            'service_address' => $serviceAddress,
            'telegram_url' => SITE_TELEGRAM_URL,
            'telegram_handle' => SITE_TELEGRAM
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'ডাটাবেজে তথ্য সংরক্ষণে সমস্যা হয়েছে: ' . $e->getMessage(),
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
