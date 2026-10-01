<?php
require_once __DIR__ . '/auth.php';

$error = '';
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $csrf = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $error = 'নিরাপত্তা টোকেন মেয়াদোত্তীর্ণ হয়েছে। পুনরায় চেষ্টা করুন।';
    } elseif (empty($username) || empty($password)) {
        $error = 'অনুগ্রহ করে ইউজারনেম এবং পাসওয়ার্ড প্রদান করুন।';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            $authenticated = false;
            $adminId = 0;
            $adminName = 'প্রধান অ্যাডমিন';

            // ১. ডাটাবেজ ভেরিফিকেশন
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $authenticated = true;
                $adminId = $admin['id'];
                $adminName = $admin['name'] ?? 'প্রধান অ্যাডমিন';
            } 
            // ২. কনফিগ ভিত্তিক অটো-হিলিং ভেরিফিকেশন (যদি ডাটাবেজে হ্যাশ মিসম্যাচ থাকে)
            elseif ($username === DEFAULT_ADMIN_USER && $password === DEFAULT_ADMIN_PASS) {
                $newHash = password_hash(DEFAULT_ADMIN_PASS, PASSWORD_BCRYPT);
                if ($admin) {
                    $updateStmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE username = ?");
                    $updateStmt->execute([$newHash, DEFAULT_ADMIN_USER]);
                    $adminId = $admin['id'];
                } else {
                    $insertStmt = $pdo->prepare("INSERT INTO admins (username, password_hash, name, created_at) VALUES (?, ?, ?, ?)");
                    $insertStmt->execute([DEFAULT_ADMIN_USER, $newHash, 'প্রধান অ্যাডমিন', date('Y-m-d H:i:s')]);
                    $adminId = $pdo->lastInsertId();
                }
                $authenticated = true;
                $adminName = 'প্রধান অ্যাডমিন';
            }

            if ($authenticated) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $adminId;
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_name'] = $adminName;
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'ভুল ইউজারনেম অথবা পাসওয়ার্ড প্রদান করা হয়েছে।';
            }
        } catch (Throwable $e) {
            $error = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন লগইন - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
            padding: 16px;
        }
        .login-card {
            background: #ffffff;
            border-radius: var(--radius-xl);
            padding: 36px 26px;
            width: 100%;
            max-width: 400px;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-subtle);
            text-align: center;
        }
        .login-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
            box-shadow: 0 6px 16px var(--primary-glow);
        }
        .error-alert {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            margin-bottom: 18px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-icon-box">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        
        <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-heading); margin-bottom: 4px;">
            অ্যাডমিন প্যানেল
        </h2>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 22px;">
            নিরাপদ প্রবেশদ্বার
        </p>

        <?php if (!empty($error)): ?>
            <div class="error-alert">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">

            <div class="form-group" style="text-align: left;">
                <label class="form-label" for="username">ইউজারনেম</label>
                <div class="input-wrapper">
                    <span class="input-icon-svg">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" id="username" name="username" class="form-input" placeholder="ইউজারনেম লিখুন" required autofocus>
                </div>
            </div>

            <div class="form-group" style="text-align: left;">
                <label class="form-label" for="password">পাসওয়ার্ড</label>
                <div class="input-wrapper">
                    <span class="input-icon-svg">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                <span>লগইন করুন</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>

        <div style="margin-top: 24px;">
            <a href="../index.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">
                ⬅ মূল ওয়েবসাইটে ফিরে যান
            </a>
        </div>
    </div>

</body>
</html>
