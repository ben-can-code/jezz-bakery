<div align="center">

# 🍞 Jezz Bakery Management System

**A warm, charming, full-featured bakery point-of-sale and management system built with PHP & MySQL.**

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mariadb.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![XAMPP](https://img.shields.io/badge/XAMPP-Localhost-FB7A24?style=flat-square&logo=xampp&logoColor=white)](https://apachefriends.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

> Developed by **Bernard Mwangi** — [@ben-can-code](https://github.com/ben-can-code)

</div>

---

## ✨ Overview

Jezz Bakery Management System is a web-based application designed for small to mid-sized bakeries. It covers product management, stock tracking, point-of-sale transactions, sales reporting, user management, and full activity/login logging — all in a warm bakery-themed UI with a real bakery wallpaper background.

---

## 🖥️ Screenshots

### 🔐 Login — Pastel Bakery Design
![Login Pastel](screenshots/login_pastel_bakery.png)
> Charming pastel login with role tabs (Cashier / Administrator), floating bakery doodles, pastel gradient title, credential hints, and the real bakery wallpaper background.

---

### 🔐 Login — Clean Orange Tabs
![Login Tabs](screenshots/login_tabs_orange.png)
> Clean orange-themed login with Cashier / Administrator role tabs and password visibility toggle.

---

### 🏠 Dashboard
![Dashboard](screenshots/dashboard_stock_overview.png)
> Summary cards: Categories (10), Products (42), Total Stock, Today's Sales. Full stock availability table with low-stock restock alerts.

---

### 📦 Products List
![Products](screenshots/products_list_with_images.png)
> Product list with image thumbnails, auto-generated product codes by category prefix, English descriptions, price, restock alert, and status badges.

---

### ➕ Add Product — Image Preview
![Add Product Image](screenshots/add_product_image_preview.png)
> Add New Product modal with live image URL preview — paste any image link and see the product photo instantly.

---

### ➕ Add Product — Auto Code
![Add Product Code](screenshots/add_product_auto_code.png)
> Product code is automatically generated when a category is selected (e.g. Breads → BRD-006). Can be unlocked to edit manually.

---

### 🏪 POS — Empty Cart
![POS Empty](screenshots/pos_transaction_empty.png)
> Cashier-friendly transaction screen — full product list with category, code, name, price, and available quantity.

---

### 🏪 POS — Items in Cart
![POS Cart](screenshots/pos_transaction_with_items.png)
> Cart with multiple items, live sub-total, 12% inclusive tax, and change calculation ready for checkout.

---

### 📊 Sales Report
![Sales Report](screenshots/sales_report.png)
> Date-filtered sales report showing receipt numbers, item counts, totals, and the staff member who processed each sale.

---

### 🧾 Receipt
![Receipt](screenshots/receipt_modal.png)
> Clean printable receipt with transaction date, receipt number, line items, subtotal, 12% tax, tendered amount, and change.

---

### 👥 Users & Logs — Users Tab
![Users](screenshots/users_logs_users_tab.png)
> User management with role badges, status, logged action counts, and protected admin account.

---

### 📋 Activity Log
![Activity Log](screenshots/users_logs_activity_log.png)
> Every action logged: login, logout, create, update, delete — with user name, role, IP address, and timestamp.

---

### 🚨 Login Attempts
![Login Attempts](screenshots/users_logs_login_attempts.png)
> Full login attempt history — failed attempts highlighted in red, browser/device detected, IP and timestamp recorded.

---

### 🛠️ Maintenance
![Maintenance](screenshots/maintenance_categories.png)
> Category management — 10 real bakery categories, add, edit, delete, toggle active/inactive status.

---

## 🚀 Features

| Feature | Description |
|---|---|
| 🔐 Role-based login | Administrator & Cashier with different access levels |
| 🏪 Point of Sale | Fast search, cart management, receipt generation |
| 📦 Product Management | Add/edit products with auto-generated codes and image URLs |
| 📊 Stock Tracking | Stock levels, expiry dates, and restock alerts |
| 📈 Sales Reports | Date-filtered reports with printable receipts |
| 👥 User Management | Create users — auto-generated username, admin sets password |
| 📋 Activity Log | Every action logged with user, IP, and timestamp |
| 🚨 Login Attempt Log | Tracks success and failed logins |
| 🛠️ Maintenance | Category management with product sub-lists |
| 🎨 Warm Bakery UI | Pastel theme, real wallpaper background, warm sidebar |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.0+ |
| Database | MySQL / MariaDB (XAMPP) |
| Frontend | Bootstrap 5, jQuery 3.6 |
| Tables | DataTables 1.11 |
| Dropdowns | Select2 |
| Icons | Font Awesome 6 |
| Fonts | Inter · Pacifico · Quicksand |
| Server | Apache (XAMPP) |

---

## ⚙️ Installation

### Requirements
- XAMPP with Apache + MySQL + PHP 8.0+
- Git
- A browser

### Steps

**1. Clone**
```bash
git clone https://github.com/ben-can-code/jezz-bakery.git
```
Place the folder at:
```
C:\xampp\htdocs\jezz bakery managment system\
```

**2. Create the database**
- Open `http://localhost/phpmyadmin`
- Create database: `bsms_db`
- Import: `database/bsms_db.sql`

**3. Set up DBConnection.php**

Copy `DBConnection.example.php` → rename to `DBConnection.php`:
```php
$this->db = new mysqli('localhost', 'root', '', 'bsms_db');
```
Update host/user/password if your XAMPP is different.

**4. Open in browser**
```
http://localhost/jezz%20bakery%20managment%20system/
```

---

## 🔑 Default Login Credentials

| Full Name | Username | Password | Role |
|---|---|---|---|
| Administrator | `admin` | `admin123` | Administrator |
| Claire Blake | `cblake` | `cblake` | Cashier |
| Mark Cooper | `mcooper` | `mcooper` | Administrator |

> Full details in [`LOGIN_INFO.txt`](LOGIN_INFO.txt)

---

## 📁 Project Structure

```
jezz bakery managment system/
├── index.php                # Main shell — sidebar, topbar, routing
├── login.php                # Login page
├── Actions.php              # All AJAX action handlers
├── DBConnection.php         # DB connection (git-ignored)
├── DBConnection.example.php # DB connection template
├── home.php                 # Dashboard
├── products.php             # Product list
├── manage_product.php       # Add/Edit product form
├── users.php                # Users & Logs (3 tabs)
├── manage_user.php          # Add/Edit user form
├── sales.php                # POS / Point of Sale
├── sales_report.php         # Sales report
├── stocks.php               # Stock management
├── maintenance.php          # Category management
├── view_receipt.php         # Printable receipt
├── LOGIN_INFO.txt           # Login credentials reference
├── screenshots/             # README screenshot images
├── database/
│   ├── bsms_db.sql          # Full database dump
│   └── patch_products.sql   # Product data patch
├── css/                     # Bootstrap CSS
├── js/                      # jQuery, Bootstrap JS
├── DataTables/              # DataTables library
├── select2/                 # Select2 library
├── Font-Awesome-master/     # Icons
└── images/
    └── wallpaper.jfif       # Bakery background image
```

---

## 👨‍💻 Developer

**Bernard Mwangi**

- 🐙 GitHub: [@ben-can-code](https://github.com/ben-can-code)
- 📦 Repository: [github.com/ben-can-code/jezz-bakery](https://github.com/ben-can-code/jezz-bakery)

---

## 📄 License

This project is open source under the [MIT License](LICENSE).

---

<div align="center">
Made with 🧁 by <strong>Bernard Mwangi</strong>
</div>
