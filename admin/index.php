<?php
require_once __DIR__ . '/auth.php';
requireAdminAuth();

$pdo = getDBConnection();

$genderFilter = $_GET['gender'] ?? 'all'; 
$statusFilter = $_GET['status'] ?? 'all'; 
$searchQuery  = trim($_GET['q'] ?? '');

$where = [];
$params = [];

if ($genderFilter === 'male') {
    $where[] = "(provider_gender = 'male' OR provider_gender = 'পুরুষ')";
} elseif ($genderFilter === 'female') {
    $where[] = "(provider_gender = 'female' OR provider_gender = 'মহিলা')";
}

if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $where[] = "status = ?";
    $params[] = $statusFilter;
}

if (!empty($searchQuery)) {
    $where[] = "(request_id LIKE ? OR customer_name LIKE ? OR customer_mobile LIKE ? OR service_address LIKE ?)";
    $like = "%{$searchQuery}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$stmt = $pdo->prepare("SELECT * FROM bookings {$whereSql} ORDER BY id DESC");
$stmt->execute($params);
$bookings = $stmt->fetchAll();

$totalCount = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Pending'")->fetchColumn();
$confirmedCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Confirmed'")->fetchColumn();
$completedCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Completed'")->fetchColumn();
$cancelledCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Cancelled'")->fetchColumn();

$maleCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE provider_gender = 'male' OR provider_gender = 'পুরুষ'")->fetchColumn();
$femaleCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE provider_gender = 'female' OR provider_gender = 'মহিলা'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন ড্যাশবোর্ড - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            background-color: #f8fafc;
        }
        .admin-layout {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px 16px 60px 16px;
        }
        .admin-header {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-subtle);
            border: 1px solid var(--border-subtle);
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #ffffff;
            border-radius: var(--radius-md);
            padding: 16px;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-subtle);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .stat-card.pending { border-left: 4px solid #f59e0b; }
        .stat-card.confirmed { border-left: 4px solid #0284c7; }
        .stat-card.completed { border-left: 4px solid #10b981; }
        .stat-card.total { border-left: 4px solid var(--primary); }
        
        .stat-num {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-heading);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .stat-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Controls */
        .controls-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 18px;
            margin-bottom: 20px;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-subtle);
        }
        .gender-tabs {
            display: flex;
            background: var(--bg-subtle);
            padding: 4px;
            border-radius: var(--radius-md);
            margin-bottom: 14px;
            gap: 4px;
        }
        .gender-tab-btn {
            flex: 1;
            text-align: center;
            padding: 9px 12px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .gender-tab-btn.active {
            background: #ffffff;
            color: var(--primary-hover);
            box-shadow: var(--shadow-subtle);
        }

        .filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 2;
            min-width: 220px;
        }
        .status-select {
            flex: 1;
            min-width: 140px;
        }

        /* Booking Cards */
        .booking-item-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 20px 18px;
            margin-bottom: 16px;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-subtle);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .booking-item-card:hover {
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.07);
        }

        .booking-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--bg-subtle);
            flex-wrap: wrap;
            gap: 8px;
        }

        .req-id-pill {
            font-family: monospace;
            font-weight: 700;
            font-size: 0.92rem;
            background: var(--primary-soft);
            color: var(--primary-hover);
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--primary-border);
        }

        .status-badge {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .status-Pending { background: #fef3c7; color: #b45309; }
        .status-Confirmed { background: #e0f2fe; color: #0369a1; }
        .status-Completed { background: #dcfce7; color: #15803d; }
        .status-Cancelled { background: #fee2e2; color: #b91c1c; }

        .gender-tag {
            display: inline-block;
            padding: 3px 9px;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: var(--radius-sm);
            margin-left: 6px;
        }
        .gender-tag.male { background: #e0f2fe; color: #0284c7; }
        .gender-tag.female { background: #fce7f3; color: #db2777; }

        .booking-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            font-size: 0.9rem;
            margin-bottom: 14px;
        }
        .data-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .data-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        .data-val {
            font-weight: 600;
            color: var(--text-heading);
        }

        .contact-links {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 3px;
        }
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-call-mini { background: #e0f2fe; color: #0284c7; }

        .service-highlight {
            background: #f8fafc;
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            border-left: 3px solid var(--primary);
            grid-column: span 2;
        }

        .location-highlight {
            background: #f0fdfa;
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            border: 1px solid var(--primary-border);
            grid-column: span 2;
            font-size: 0.86rem;
        }

        .meta-info-bar {
            background: var(--bg-subtle);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .booking-actions-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle);
            flex-wrap: wrap;
        }

        .status-update-form {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .booking-grid { grid-template-columns: 1fr; }
            .service-highlight, .location-highlight { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        
        <!-- অ্যাডমিন হেডার -->
        <header class="admin-header">
            <div class="admin-brand">
                <div class="brand-logo-icon" style="width: 38px; height: 38px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <h1 style="font-size: 1.15rem; font-weight: 700; color: var(--text-heading); margin: 0;">
                        অ্যাডমিন ড্যাশবোর্ড
                    </h1>
                    <span style="font-size: 0.78rem; color: var(--text-muted);">
                        স্বাগতম, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'অ্যাডমিন'); ?>
                    </span>
                </div>
            </div>

            <div class="admin-nav-actions">
                <a href="api.php?action=export_csv" class="btn btn-secondary" style="padding: 7px 12px; font-size: 0.82rem;">
                    📥 CSV এক্সপোর্ট
                </a>
                <button type="button" onclick="openPasswordModal()" class="btn btn-secondary" style="padding: 7px 12px; font-size: 0.82rem;">
                    🔑 পাসওয়ার্ড পরিবর্তন
                </button>
                <a href="../index.php" target="_blank" class="btn btn-secondary" style="padding: 7px 12px; font-size: 0.82rem;">
                    🌐 ওয়েবসাইট দেখুন
                </a>
                <a href="logout.php" class="btn btn-secondary" style="padding: 7px 12px; font-size: 0.82rem; color: #ef4444;" onclick="return confirm('আপনি কি নিশ্চিত যে লগআউট করতে চান?');">
                    লগআউট
                </a>
            </div>
        </header>

        <!-- পরিসংখ্যান কার্ড গ্রিড -->
        <section class="stats-grid">
            <div class="stat-card total">
                <span class="stat-num"><?php echo $totalCount; ?></span>
                <span class="stat-title">মোট বুকিং</span>
            </div>
            <div class="stat-card pending">
                <span class="stat-num"><?php echo $pendingCount; ?></span>
                <span class="stat-title">অপেক্ষমান (Pending)</span>
            </div>
            <div class="stat-card confirmed">
                <span class="stat-num"><?php echo $confirmedCount; ?></span>
                <span class="stat-title">নিশ্চিতকৃত (Confirmed)</span>
            </div>
            <div class="stat-card completed">
                <span class="stat-num"><?php echo $completedCount; ?></span>
                <span class="stat-title">সম্পন্ন (Completed)</span>
            </div>
        </section>

        <!-- ফিল্টার ও সার্চ -->
        <section class="controls-card">
            <div class="gender-tabs">
                <a href="index.php?gender=all&status=<?php echo urlencode($statusFilter); ?>" class="gender-tab-btn <?php echo $genderFilter === 'all' ? 'active' : ''; ?>">
                    সকল বুকিং (<?php echo $totalCount; ?>)
                </a>
                <a href="index.php?gender=male&status=<?php echo urlencode($statusFilter); ?>" class="gender-tab-btn <?php echo $genderFilter === 'male' ? 'active' : ''; ?>">
                    👨 পুরুষ প্রোভাইডার (<?php echo $maleCount; ?>)
                </a>
                <a href="index.php?gender=female&status=<?php echo urlencode($statusFilter); ?>" class="gender-tab-btn <?php echo $genderFilter === 'female' ? 'active' : ''; ?>">
                    👩 মহিলা প্রোভাইডার (<?php echo $femaleCount; ?>)
                </a>
            </div>

            <form method="GET" action="index.php" class="filter-row">
                <input type="hidden" name="gender" value="<?php echo htmlspecialchars($genderFilter); ?>">
                
                <div class="search-box">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-input no-icon" placeholder="রিকোয়েস্ট আইডি, নাম, মোবাইল বা ঠিকানা খুঁজুন..." style="height: 42px;">
                </div>

                <div class="status-select">
                    <select name="status" class="form-input no-icon" onchange="this.form.submit()" style="height: 42px;">
                        <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>সব স্ট্যাটাস</option>
                        <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>অপেক্ষমান (Pending)</option>
                        <option value="Confirmed" <?php echo $statusFilter === 'Confirmed' ? 'selected' : ''; ?>>নিশ্চিত (Confirmed)</option>
                        <option value="Completed" <?php echo $statusFilter === 'Completed' ? 'selected' : ''; ?>>সম্পন্ন (Completed)</option>
                        <option value="Cancelled" <?php echo $statusFilter === 'Cancelled' ? 'selected' : ''; ?>>বাতিল (Cancelled)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; height: 42px;">
                    ফিল্টার
                </button>
                
                <?php if (!empty($searchQuery) || $statusFilter !== 'all' || $genderFilter !== 'all'): ?>
                    <a href="index.php" class="btn btn-secondary" style="padding: 8px 14px; height: 42px; font-size: 0.85rem;">
                        রিসেট
                    </a>
                <?php endif; ?>
            </form>
        </section>

        <!-- বুকিং তালিকা -->
        <main>
            <?php if (empty($bookings)): ?>
                <div style="background: #ffffff; padding: 40px 20px; border-radius: var(--radius-lg); text-align: center; color: var(--text-muted); border: 1px solid var(--border-subtle);">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading);">কোনো বুকিং রিকোয়েস্ট পাওয়া যায়নি</h3>
                    <p style="font-size: 0.85rem; margin-top: 4px;">বর্তমান ফিল্টারে কোনো তথ্য নেই অথবা এখনো কোনো বুকিং করা হয়নি।</p>
                </div>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                    <?php 
                        $isMale = ($b['provider_gender'] === 'male' || $b['provider_gender'] === 'পুরুষ');
                    ?>
                    <article class="booking-item-card" id="booking-card-<?php echo $b['id']; ?>">
                        
                        <div class="booking-card-top">
                            <div>
                                <span class="req-id-pill">#<?php echo htmlspecialchars($b['request_id']); ?></span>
                                <span class="gender-tag <?php echo $isMale ? 'male' : 'female'; ?>">
                                    <?php echo $isMale ? 'পুরুষ প্রোভাইডার' : 'মহিলা প্রোভাইডার'; ?>
                                </span>
                            </div>

                            <span class="status-badge status-<?php echo htmlspecialchars($b['status']); ?>" id="status-badge-<?php echo $b['id']; ?>">
                                ● <?php echo htmlspecialchars($b['status']); ?>
                            </span>
                        </div>

                        <div class="booking-grid">
                            <div class="data-item">
                                <span class="data-label">গ্রাহকের নাম ও বয়স:</span>
                                <span class="data-val">
                                    <?php echo htmlspecialchars($b['customer_name']); ?>
                                    <?php if (!empty($b['customer_age'])): ?>
                                        (<?php echo htmlspecialchars($b['customer_age']); ?> বছর)
                                    <?php endif; ?>
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">মোবাইল নম্বর:</span>
                                <div class="contact-links">
                                    <span class="data-val" style="font-family: monospace; font-size: 0.95rem;"><?php echo htmlspecialchars($b['customer_mobile']); ?></span>
                                    <a href="tel:<?php echo htmlspecialchars($b['customer_mobile']); ?>" class="contact-btn btn-call-mini" title="কল দিন">
                                        📞 সরাসরি কল
                                    </a>
                                </div>
                            </div>

                            <div class="data-item">
                                <span class="data-label">সার্ভিসের তারিখ ও সময়:</span>
                                <span class="data-val">
                                    <?php echo htmlspecialchars($b['service_date']); ?> | <?php echo htmlspecialchars($b['service_time']); ?>
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">প্রোভাইডারের বয়স পছন্দ:</span>
                                <span class="data-val">
                                    <?php echo htmlspecialchars($b['provider_age_range'] ?: 'যেকোনো বয়সের'); ?>
                                </span>
                            </div>

                            <div class="service-highlight">
                                <span class="data-label">নির্বাচিত সার্ভিসসমূহ:</span>
                                <div class="data-val" style="color: var(--primary-hover); margin-top: 2px;">
                                    <?php echo htmlspecialchars($b['selected_services']); ?>
                                </div>
                            </div>

                            <div class="location-highlight">
                                <span class="data-label">সার্ভিস নেওয়ার ঠিকানা:</span>
                                <div class="data-val" style="margin-top: 2px;">
                                    <?php echo nl2br(htmlspecialchars($b['service_address'])); ?>
                                </div>

                                <?php if (!empty($b['latitude']) && !empty($b['longitude'])): ?>
                                    <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed var(--primary-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                                        <span style="font-size: 0.78rem; color: var(--primary-hover);">
                                            📍 GPS: <?php echo htmlspecialchars($b['latitude']); ?>, <?php echo htmlspecialchars($b['longitude']); ?>
                                        </span>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($b['latitude'] . ',' . $b['longitude']); ?>" target="_blank" class="contact-btn btn-call-mini" style="background: #ccfbf1; color: #0f766e;">
                                            Google Maps এ দেখুন ↗
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="meta-info-bar">
                            <span>🕒 বুকিং সময়: <?php echo htmlspecialchars($b['created_at']); ?></span>
                            <span>🌐 ক্লায়েন্ট IP: <?php echo htmlspecialchars($b['ip_address'] ?? 'N/A'); ?></span>
                        </div>

                        <div class="booking-actions-bar">
                            <form class="status-update-form" onsubmit="handleStatusUpdate(event, <?php echo $b['id']; ?>)">
                                <label style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted);">স্ট্যাটাস:</label>
                                <select name="status" class="form-input no-icon" style="height: 36px; padding: 4px 8px; font-size: 0.85rem; width: auto;">
                                    <option value="Pending" <?php echo $b['status'] === 'Pending' ? 'selected' : ''; ?>>অপেক্ষমান (Pending)</option>
                                    <option value="Confirmed" <?php echo $b['status'] === 'Confirmed' ? 'selected' : ''; ?>>নিশ্চিত (Confirmed)</option>
                                    <option value="Completed" <?php echo $b['status'] === 'Completed' ? 'selected' : ''; ?>>সম্পন্ন (Completed)</option>
                                    <option value="Cancelled" <?php echo $b['status'] === 'Cancelled' ? 'selected' : ''; ?>>বাতিল (Cancelled)</option>
                                </select>
                                <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.82rem; height: 36px;">
                                    সেভ করুন
                                </button>
                            </form>

                            <button type="button" onclick="deleteBooking(<?php echo $b['id']; ?>)" class="btn btn-secondary" style="color: #ef4444; padding: 6px 12px; font-size: 0.82rem; height: 36px;">
                                ডিলিট
                            </button>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

    </div>

    <!-- পাসওয়ার্ড মোডাল -->
    <div class="modal-overlay" id="passwordModal">
        <div class="modal-card" style="max-width: 380px;">
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 14px; color: var(--text-heading);">
                পাসওয়ার্ড পরিবর্তন
            </h3>
            <form id="changePasswordForm" onsubmit="handleChangePassword(event)">
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">বর্তমান পাসওয়ার্ড</label>
                    <input type="password" name="current_password" class="form-input no-icon" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">নতুন পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর)</label>
                    <input type="password" name="new_password" class="form-input no-icon" minlength="6" required>
                </div>
                <div class="modal-actions" style="margin-top: 18px;">
                    <button type="submit" class="btn btn-primary">পাসওয়ার্ড আপডেট করুন</button>
                    <button type="button" onclick="closePasswordModal()" class="btn btn-secondary">বাতিল</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function handleStatusUpdate(e, bookingId) {
            e.preventDefault();
            const form = e.target;
            const status = form.querySelector('select[name="status"]').value;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.textContent = '...';

            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', bookingId);
            formData.append('status', status);

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    const badge = document.getElementById(`status-badge-${bookingId}`);
                    if (badge) {
                        badge.className = `status-badge status-${status}`;
                        badge.innerHTML = `● ${status}`;
                    }
                    alert(data.message);
                } else {
                    alert(data.message || 'ত্রুটি ঘটেছে');
                }
            } catch (err) {
                alert('সার্ভার যোগাযোগ ব্যর্থ হয়েছে।');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'সেভ করুন';
            }
        }

        async function deleteBooking(bookingId) {
            if (!confirm('আপনি কি নিশ্চিত যে এই বুকিং রেকর্ডটি মুছে ফেলতে চান?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete_booking');
            formData.append('id', bookingId);

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    const card = document.getElementById(`booking-card-${bookingId}`);
                    if (card) {
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 300);
                    }
                } else {
                    alert(data.message);
                }
            } catch (err) {
                alert('সার্ভার যোগাযোগ ব্যর্থ হয়েছে।');
            }
        }

        function openPasswordModal() {
            document.getElementById('passwordModal').classList.add('active');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.remove('active');
        }

        async function handleChangePassword(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'change_password');

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                alert(data.message);
                if (data.success) {
                    form.reset();
                    closePasswordModal();
                }
            } catch (err) {
                alert('সার্ভার যোগাযোগ ব্যর্থ হয়েছে।');
            }
        }
    </script>
</body>
</html>
