<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars(SITE_NAME); ?> - হোম সার্ভিস বুকিং</title>
    
    <meta name="description" content="হোম সার্ভিস সেন্টার - ঘরে বসেই পেশাদার সার্ভিস ও রিল্যাক্সেশন কেয়ার গ্রহণ করুন।">
    <meta name="theme-color" content="#0d9488">
    
    <!-- Google Fonts: Hind Siliguri + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ব্যাকগ্রাউন্ড ডেকোরেশন -->
    <div class="bg-decorations">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- মূল অ্যাপ্লিকেশন কন্টেইনার -->
    <div class="app-container">
        
        <!-- হেডার এরিয়া -->
        <header class="app-header">
            <div class="brand-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>নিরাপদ ও পেশাদার হোম সার্ভিস</span>
            </div>
            
            <div class="brand-logo-wrap">
                <div class="brand-logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h1 class="brand-title"><?php echo htmlspecialchars(SITE_NAME); ?></h1>
            </div>
            
            <!-- মূল হেডলাইন -->
            <p class="brand-headline">আপনার মোবাইল নম্বরে আমাদের প্রতিনিধি শীঘ্রই যোগাযোগ করবে।</p>
        </header>

        <!-- টেলিগ্রাম সাপোর্ট বার -->
        <div class="telegram-bar">
            <div class="telegram-label">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0088cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>টেলিগ্রাম সাপোর্ট: <strong>@<?php echo htmlspecialchars(SITE_TELEGRAM); ?></strong></span>
            </div>
            <a href="<?php echo htmlspecialchars(SITE_TELEGRAM_URL); ?>" target="_blank" class="telegram-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.932z"/></svg>
                <span>মেসেজ দিন</span>
            </a>
        </div>

        <!-- বুকিং কার্ড (৩-ধাপের উইজার্ড) -->
        <main class="booking-card">

            <!-- স্টেপ প্রোগ্রেস ইন্ডিকেটর -->
            <div class="step-progress-wrapper">
                <div class="step-indicators">
                    <div class="step-progress-bar-fill" id="stepProgressFill"></div>
                    
                    <div class="step-node active" data-step="1">
                        <div class="step-circle">১</div>
                        <div class="step-label">সেবা নির্বাচন</div>
                    </div>
                    
                    <div class="step-node" data-step="2">
                        <div class="step-circle">২</div>
                        <div class="step-label">ঠিকানা ও যোগাযোগ</div>
                    </div>
                    
                    <div class="step-node" data-step="3">
                        <div class="step-circle">৩</div>
                        <div class="step-label">সার্ভিস ও সময়</div>
                    </div>
                </div>
            </div>

            <!-- মূল ফর্ম -->
            <form id="bookingForm" autocomplete="off">
                <!-- হিডেন GPS ফিল্ডস -->
                <input type="hidden" id="latitude" name="latitude" value="">
                <input type="hidden" id="longitude" name="longitude" value="">
                <input type="hidden" id="gps_address" name="gps_address" value="">

                <!-- ================= STEP 1: নাম ও কার থেকে সেবা নিতে চান ================= -->
                <div class="step-pane active" data-step="1">
                    <div class="step-header">
                        <h2 class="step-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            ধাপ ১: আপনার তথ্য ও প্রোভাইডার পছন্দ
                        </h2>
                        <p class="step-desc">অনুগ্রহ করে আপনার নাম লিখুন এবং কার থেকে সেবা নিতে চান তা নির্বাচন করুন</p>
                    </div>

                    <!-- গ্রাহকের নাম -->
                    <div class="form-group">
                        <label class="form-label" for="customer_name">
                            গ্রাহকের পুরো নাম <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-svg">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" id="customer_name" name="customer_name" class="form-input" placeholder="আপনার পুরো নাম লিখুন..." required>
                        </div>
                    </div>

                    <!-- আপনি কার থেকে সেবা নিতে চান? -->
                    <div class="form-group">
                        <label class="form-label">
                            আপনি কার থেকে সেবা নিতে চান? <span class="required">*</span>
                        </label>
                        <div class="provider-cards-grid">
                            
                            <!-- পুরুষের কাছ থেকে সেবা নিতে চাই -->
                            <input type="radio" id="gender_male" name="provider_gender" value="male" class="provider-card-input" checked>
                            <label for="gender_male" class="provider-card">
                                <div class="provider-avatar-frame">
                                    <img src="assets/images/provider_male.jpg" alt="তরুণ পুরুষ প্রোভাইডার" class="provider-avatar-img">
                                </div>
                                <div class="provider-title">পুরুষের কাছ থেকে সেবা নিতে চাই</div>
                                <div class="provider-subtitle">দক্ষ ও তরুণ মেল থেরাপিস্ট</div>
                                <span class="provider-check-pill">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    নির্বাচিত
                                </span>
                            </label>

                            <!-- মহিলার কাছ থেকে সেবা নিতে চাই -->
                            <input type="radio" id="gender_female" name="provider_gender" value="female" class="provider-card-input">
                            <label for="gender_female" class="provider-card">
                                <div class="provider-avatar-frame">
                                    <img src="assets/images/provider_female.jpg" alt="তরুণী মহিলা প্রোভাইডার" class="provider-avatar-img">
                                </div>
                                <div class="provider-title">মহিলার কাছ থেকে সেবা নিতে চাই</div>
                                <div class="provider-subtitle">দক্ষ ও তরুণী ফিমেল থেরাপিস্ট</div>
                                <span class="provider-check-pill">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    নির্বাচিত
                                </span>
                            </label>

                        </div>
                    </div>

                    <!-- ধাপ ১ এর নেক্সট বাটন -->
                    <div class="step-actions">
                        <button type="button" class="btn btn-primary btn-next" data-next="2">
                            <span>পরবর্তী ধাপ</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 2: মোবাইল, বয়স ও লোকেশন ================= -->
                <div class="step-pane" data-step="2">
                    <div class="step-header">
                        <h2 class="step-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            ধাপ ২: যোগাযোগ ও সার্ভিস লোকেশন
                        </h2>
                        <p class="step-desc">আপনার সক্রিয় মোবাইল নম্বর এবং সার্ভিস নেওয়ার ঠিকানা প্রদান করুন</p>
                    </div>

                    <!-- মোবাইল নম্বর -->
                    <div class="form-group">
                        <label class="form-label" for="customer_mobile">
                            মোবাইল নম্বর <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-svg">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                            </span>
                            <input type="tel" id="customer_mobile" name="customer_mobile" class="form-input" placeholder="01700-000000" required>
                        </div>
                    </div>

                    <!-- গ্রাহকের বয়স -->
                    <div class="form-group">
                        <label class="form-label" for="customer_age">
                            গ্রাহকের বয়স (বছর)
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-svg">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="number" id="customer_age" name="customer_age" class="form-input" placeholder="যেমন: ২৮" min="15" max="100">
                        </div>
                        <div class="quick-chips-grid">
                            <input type="radio" id="chip_age_1" name="quick_age" value="22" class="chip-input">
                            <label for="chip_age_1" class="chip-label">১৮-২৫</label>

                            <input type="radio" id="chip_age_2" name="quick_age" value="30" class="chip-input">
                            <label for="chip_age_2" class="chip-label">২৬-৩৫</label>

                            <input type="radio" id="chip_age_3" name="quick_age" value="42" class="chip-input">
                            <label for="chip_age_3" class="chip-label">৩৬-৫০</label>

                            <input type="radio" id="chip_age_4" name="quick_age" value="55" class="chip-input">
                            <label for="chip_age_4" class="chip-label">৫০+</label>
                        </div>
                    </div>

                    <!-- GPS লোকেশন বাটন ও ঠিকানা -->
                    <div class="form-group">
                        <label class="form-label">
                            সার্ভিস গ্রহণের ঠিকানা <span class="required">*</span>
                        </label>
                        
                        <div class="gps-btn-wrap">
                            <button type="button" id="useGpsBtn" class="gps-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                                <span>আমার বর্তমান লোকেশন ব্যবহার করুন (GPS)</span>
                            </button>
                            <div class="gps-status-badge" id="gpsStatusBadge">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span id="gpsStatusText">লোকেশন সনাক্ত হয়েছে</span>
                            </div>
                        </div>

                        <!-- ম্যানুয়াল ঠিকানা এন্ট্রি -->
                        <div class="input-wrapper">
                            <textarea id="service_address" name="service_address" class="form-textarea" placeholder="বাসা/হোল্ডিং নং, ফ্ল্যাট, রোড নং, এলাকা ও ল্যান্ডমার্ক বিস্তারিত লিখুন..." required></textarea>
                        </div>
                    </div>

                    <!-- ধাপ ২ এর অ্যাকশন বাটন -->
                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary btn-prev" data-prev="1">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            <span>পূর্ববর্তী</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-next" data-next="3">
                            <span>পরবর্তী ধাপ</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 3: সার্ভিস ও সময়সূচী ================= -->
                <div class="step-pane" data-step="3">
                    <div class="step-header">
                        <h2 class="step-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            ধাপ ৩: সার্ভিস নির্বাচন ও সময়সূচী
                        </h2>
                        <p class="step-desc">প্রয়োজনীয় সার্ভিস এবং আপনার সুবিধাজনক শিডিউল নির্ধারণ করুন</p>
                    </div>

                    <!-- সার্ভিস নির্বাচন (সবার নিচে স্পেশাল সার্ভিস সহ) -->
                    <div class="form-group">
                        <label class="form-label">
                            প্রয়োজনীয় সার্ভিস নির্বাচন করুন <span class="required">*</span>
                        </label>
                        <div id="servicesListContainer" class="services-grid">
                            <!-- জাভাস্ক্রিপ্ট দিয়ে সার্ভিস কার্ডগুলো তৈরি হবে -->
                        </div>
                    </div>

                    <!-- ইউনিক ও সহজে নজরে আসার মতো প্রোভাইডার বয়স নির্বাচন -->
                    <div class="form-group">
                        <div class="provider-age-box">
                            <div class="provider-age-header">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                <span class="provider-age-header-title">🎯 সার্ভিস প্রদানকারীর পছন্দের বয়সসীমা:</span>
                            </div>
                            <div class="provider-age-grid">
                                
                                <input type="radio" id="age_opt_1" name="provider_age_range" value="২০ - ২৫ বছর (তরুণ/তরুণী)" class="age-option-input" checked>
                                <label for="age_opt_1" class="age-option-card">
                                    <span class="age-option-title">⚡ ২০ - ২৫ বছর</span>
                                    <span class="age-option-desc">তরুণ / তরুণী প্রোভাইডার</span>
                                </label>

                                <input type="radio" id="age_opt_2" name="provider_age_range" value="২৬ - ৩৫ বছর (অভিজ্ঞ)" class="age-option-input">
                                <label for="age_opt_2" class="age-option-card">
                                    <span class="age-option-title">⭐ ২৬ - ৩৫ বছর</span>
                                    <span class="age-option-desc">অভিজ্ঞ প্রোভাইডার</span>
                                </label>

                                <input type="radio" id="age_opt_3" name="provider_age_range" value="৩৬ - ৫০+ বছর (সিনিয়র)" class="age-option-input">
                                <label for="age_opt_3" class="age-option-card">
                                    <span class="age-option-title">👑 ৩৬ - ৫০+ বছর</span>
                                    <span class="age-option-desc">সিনিয়র স্পেশালিস্ট</span>
                                </label>

                                <input type="radio" id="age_opt_4" name="provider_age_range" value="যে কোনো বয়সের অভিজ্ঞ প্রোভাইডার" class="age-option-input">
                                <label for="age_opt_4" class="age-option-card">
                                    <span class="age-option-title">✨ যে কোনো বয়স</span>
                                    <span class="age-option-desc">অভিজ্ঞতার ভিত্তিতে সেরা</span>
                                </label>

                            </div>
                        </div>
                    </div>

                    <!-- সার্ভিসের তারিখ -->
                    <div class="form-group">
                        <label class="form-label" for="service_date">
                            সার্ভিস গ্রহণের তারিখ <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-svg">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                            <input type="date" id="service_date" name="service_date" class="form-input" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <!-- সার্ভিসের সময় -->
                    <div class="form-group">
                        <label class="form-label">
                            সার্ভিস গ্রহণের সুবিধাজনক সময় <span class="required">*</span>
                        </label>
                        <div class="time-slots-grid">
                            <input type="radio" id="time_1" name="service_time" value="সকাল ১০:০০ - দুপুর ১২:০০" class="time-slot-input" checked>
                            <label for="time_1" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>সকাল ১০:০০ - ১২:০০</span>
                            </label>

                            <input type="radio" id="time_2" name="service_time" value="দুপুর ১২:০০ - ০২:০০" class="time-slot-input">
                            <label for="time_2" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>দুপুর ১২:০০ - ০২:০০</span>
                            </label>

                            <input type="radio" id="time_3" name="service_time" value="বিকাল ০৩:০০ - ০৫:০০" class="time-slot-input">
                            <label for="time_3" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>বিকাল ০৩:০০ - ০৫:০০</span>
                            </label>

                            <input type="radio" id="time_4" name="service_time" value="সন্ধ্যা ০৬:০০ - ০৮:০০" class="time-slot-input">
                            <label for="time_4" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>সন্ধ্যা ০৬:০০ - ০৮:০০</span>
                            </label>

                            <input type="radio" id="time_5" name="service_time" value="রাত ০৮:০০ - ১০:০০" class="time-slot-input">
                            <label for="time_5" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>রাত ০৮:০০ - ১০:০০</span>
                            </label>

                            <input type="radio" id="time_6" name="service_time" value="জরুরি / যত দ্রুত সম্ভব" class="time-slot-input">
                            <label for="time_6" class="time-slot-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                <span>জরুরি সেবা</span>
                            </label>
                        </div>
                    </div>

                    <!-- ধাপ ৩ এর সাবমিট বাটন -->
                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary btn-prev" data-prev="2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            <span>পূর্ববর্তী</span>
                        </button>
                        <button type="submit" id="submitBookingBtn" class="btn btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>বুকিং রিকোয়েস্ট নিশ্চিত করুন</span>
                        </button>
                    </div>
                </div>

            </form>
        </main>

        <!-- ফুটার -->
        <footer class="app-footer">
            <p>© <?php echo date('Y'); ?> <?php echo htmlspecialchars(SITE_NAME); ?>। সর্বস্বত্ব সংরক্ষিত।</p>
        </footer>

    </div>

    <!-- ================= প্রিমিয়াম বুকিং কনফার্মেশন মোডাল ================= -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-card">
            
            <div style="text-align: center;">
                <div class="success-icon-wrap">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                
                <span class="success-badge-top">বুকিং রিকোয়েস্ট সফলভাবে গৃহীত হয়েছে</span>
                
                <h3 class="success-title">ধন্যবাদ! আপনার বুকিং সম্পন্ন হয়েছে</h3>
            </div>

            <!-- কনফার্মেশন বার্তা -->
            <div class="success-message-banner">
                আপনার মোবাইল নম্বরে আমাদের প্রতিনিধি শীঘ্রই যোগাযোগ করবে।
            </div>

            <!-- রিকোয়েস্ট আইডি ট্র্যাকিং কোড -->
            <div class="request-id-badge">
                <span class="request-id-label">রিকোয়েস্ট ট্র্যাকিং আইডি:</span>
                <span class="request-id-code" id="resRequestId">HSC-XXXXXX</span>
            </div>

            <!-- বুকিং সারসংক্ষেপ -->
            <div class="receipt-details">
                <div class="receipt-row">
                    <span class="receipt-label">গ্রাহকের নাম:</span>
                    <span class="receipt-value" id="resCustomerName">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">মোবাইল নম্বর:</span>
                    <span class="receipt-value" id="resMobile" style="font-family: monospace;">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">সার্ভিস প্রদানকারী:</span>
                    <span class="receipt-value" id="resProviderGender">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">নির্বাচিত সেবা:</span>
                    <span class="receipt-value" id="resServices">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">তারিখ ও সময়:</span>
                    <span class="receipt-value" id="resDateTime">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">সার্ভিস ঠিকানা:</span>
                    <span class="receipt-value" id="resAddress">-</span>
                </div>
            </div>

            <!-- মোডাল অ্যাকশন বাটন (টেলিগ্রাম সাপোর্ট) -->
            <div class="modal-actions">
                <a href="<?php echo htmlspecialchars(SITE_TELEGRAM_URL); ?>" id="telegramBtn" target="_blank" class="btn btn-telegram-modal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.932z"/></svg>
                    <span>টেলিগ্রামে যোগাযোগ করুন (@<?php echo htmlspecialchars(SITE_TELEGRAM); ?>)</span>
                </a>
                <button type="button" id="closeModalBtn" class="btn btn-secondary">
                    <span>ঠিক আছে / সম্পন্ন</span>
                </button>
            </div>
        </div>
    </div>

    <!-- স্ক্রিপ্ট -->
    <script src="assets/js/app.js"></script>
</body>
</html>
