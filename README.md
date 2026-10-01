# 🌿 Home Service Center & Spa Booking Web Application

A lightweight, modern, mobile-first Bengali Home Care & Personal Wellness Spa Service Booking web application built with **PHP 8.x** and Vanilla JavaScript. Designed specifically for standard cPanel hosting and shared servers with **dual database support (MySQL & Zero-Config SQLite)**.

---

## ✨ Key Features

### 📱 Customer Booking Interface (`index.php`)
- **100% Bengali Typography:** Clean, modern Bengali font rendering (*Hind Siliguri* & *Plus Jakarta Sans*).
- **Soft Light Theme:** Elegant soft white (`#ffffff`), light mint/teal (`#0d9488`, `#f0fdfa`), and subtle gray color palette.
- **Frictionless Experience:** No registration or customer login required.
- **3-Step Smart Booking Wizard:**
  - **Step 1:** Customer Name & Provider Selection (*Young Male Therapist* / *Young Female Therapist* with high-definition realistic profile portraits).
  - **Step 2:** Mobile Number, Age, and **📍 "Use My Location" (GPS)** button for automatic address reverse-geocoding via OpenStreetMap Nominatim + manual address input.
  - **Step 3:** Dynamic service catalog tailored to provider selection, eye-catching Provider Preferred Age Range cards, **⭐ VIP Special Service** highlight, and convenient date/time slot picker.
- **Digital Confirmation Receipt:** Generates a unique tracking ID (`HSC-YYYYMMDD-XXXXX`) and direct **Telegram Support (@kemlu09)** button.

---

### 🔐 Secure Admin Dashboard (`admin/`)
- **Mobile-Responsive Dashboard:** Manage all booking requests from desktop or mobile.
- **Gender & Status Filters:** Quick tabs for *All Requests*, *Male Providers*, and *Female Providers*, plus status filters (*Pending*, *Confirmed*, *Completed*, *Cancelled*).
- **Instant Live Search:** Filter requests by Tracking ID, customer name, mobile number, or address.
- **Security & Location Audit:** Displays customer Client IP, User-Agent, and one-click direct **Google Maps** link for GPS coordinates.
- **Export to CSV:** 1-Click download of booking reports for Excel / Google Sheets.
- **Self-Healing Admin Auth:** Secure Bcrypt session authentication with automatic credential synchronization.

---

## 🛠️ Tech Stack

| Component | Technology |
| :--- | :--- |
| **Backend** | PHP 7.4 - 8.x (Native PDO, Session, CSRF Protection) |
| **Database** | Dual Architecture: **MySQL** (cPanel) & **SQLite** (Zero-Config) |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript (ES6+) |
| **Fonts** | Google Fonts (*Hind Siliguri* + *Plus Jakarta Sans*) |
| **Icons** | Custom Stroke & Duotone Vector SVGs |
| **Deployment** | cPanel / Apache / Nginx / Localhost |

---

## 📁 Directory Structure

```text
├── index.php                # Main customer booking interface
├── database.sql             # MySQL schema for phpMyAdmin import
├── .htaccess                # Apache/cPanel security & gzip caching
├── README.md                # Documentation
├── api/
│   └── book.php             # Booking submission & validation API
├── admin/
│   ├── index.php            # Admin dashboard
│   ├── login.php            # Secure admin login
│   ├── logout.php           # Session logout
│   ├── auth.php             # Session & CSRF security handler
│   └── api.php              # Status update, delete & CSV export API
├── includes/
│   ├── config.php           # Global configuration & Telegram settings
│   └── db.php               # PDO database handler (MySQL + SQLite fallback)
└── assets/
    ├── css/
    │   └── style.css        # Mobile-first soft theme stylesheet
    ├── images/
    │   ├── provider_male.jpg    # Young male therapist profile photo
    │   └── provider_female.jpg  # Young female therapist profile photo
    └── js/
        └── app.js           # Multi-step wizard & GPS geolocation logic
```

---

## 🚀 Installation & Deployment Guide

### Option 1: Zero-Config Deployment (Recommended)
1. Compress all project files into a `.zip` archive.
2. Upload and extract into your cPanel `public_html` or subdomain directory.
3. **Done!** The system automatically initializes the database (`data/homeservice.sqlite`) and is ready to use immediately.

---

### Option 2: MySQL Database Setup on cPanel
1. In cPanel, navigate to **MySQL Database Wizard** and create a database and user (e.g., `fellesxy_service`).
2. Open **phpMyAdmin**, select your database, and **Import** `database.sql`.
3. Open `includes/config.php` and update your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'your_database_name');
   define('DB_USER', 'your_database_user');
   define('DB_PASS', 'your_database_password');
   ```

---

## 🔐 Default Admin Credentials

- **Admin Login URL:** `https://yourdomain.com/admin/login.php`
- **Username:** `hsc_admin_root`
- **Password:** `Hsc#2026$Adm9!Kq8`

*(You can change the password at any time from the admin dashboard)*

---

## 📞 Support & Contact

- **Telegram Support:** [@kemlu09](https://t.me/kemlu09)
- **Live Demo:** [http://service.felles.xyz](http://service.felles.xyz)

---

## 📄 License
This project is open-source and available under the **MIT License**.
