<div align="center">

# 🍞 Jezz Bakery Management System

**A warm, charming, full-featured bakery point-of-sale and management system built with PHP & MySQL.**

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mariadb.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![XAMPP](https://img.shields.io/badge/XAMPP-Localhost-FB7A24?style=flat-square&logo=xampp&logoColor=white)](https://apachefriends.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

> Developed by **Bernard Mwangi** — [github.com/ben-can-code](https://github.com/ben-can-code)

</div>

---

## ✨ Overview

Jezz Bakery Management System is a web-based application designed for small to mid-sized bakeries. It handles everything from product management and stock tracking to point-of-sale transactions, sales reporting, user management, and full activity logging — all wrapped in a warm, bakery-themed UI with the original bakery wallpaper background.

---

## 🖥️ Screenshots

### 1. Login Page
> Charming hand-drawn style login with role tabs (Cashier / Administrator), floating bakery doodles, pastel gradient title, and the original bakery wallpaper.

![Login Page](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/login.png)

---

### 2. Dashboard — Home
> Summary cards showing total Categories, Products, Stock, and Today's Sales. Full stock availability table with restock alerts.

![Dashboard](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/dashboard.png)

---

### 3. Products Page
> Full product list with image thumbnails, category, product code (auto-generated), price, restock alert level, and status badges.

![Products](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/products.png)

---

### 4. Point of Sale (POS)
> Cashier-friendly transaction screen. Search and add products, view live cart with quantities and totals. Save transaction generates a receipt.

![POS](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/pos.png)

---

### 5. POS — Transaction with Items
> Live cart updating in real-time with sub-total, tax (12%), and change calculation.

![POS Transaction](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/pos_transaction.png)

---

### 6. Sales Report
> Filterable sales report by date range. Shows receipt numbers, item counts, total amounts, and the staff member who processed each transaction.

![Sales Report](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/sales_report.png)

---

### 7. Receipt View
> Clean printable receipt showing transaction date, receipt number, product lines, subtotal, 12% tax, tendered amount, and change.

![Receipt](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/receipt.png)

---

### 8. Users & Logs — Users Tab
> Administrator-only user management. Shows all users with their role, status, and a count of logged actions. Protected row for the main admin.

![Users](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/users.png)

---

### 9. Users & Logs — Activity Log
> System-wide activity log showing every action taken (login, logout, create, update, delete) with timestamps, user name, role, and IP address.

![Activity Log](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/activity_log.png)

---

### 10. Users & Logs — Login Attempts
> Full login attempt history including failed attempts (highlighted in red), browser/device detection, IP address, and timestamp.

![Login Attempts](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/login_attempts.png)

---

### 11. Maintenance — Categories
> Manage bakery product categories. Add, edit, delete, and toggle status. Each category has a product list view.

![Maintenance](https://raw.githubusercontent.com/ben-can-code/jezz-bakery/main/screenshots/maintenance.png)

---

## 🚀 Features

| Feature | Description |
|---|---|
| 🔐 Role-based login | Administrator & Cashier roles with different access levels |
| 🏪 Point of Sale | Fast product search, cart management, receipt generation |
| 📦 Product Management | Add/edit products with auto-generated codes, images via URL |
| 📊 Stock Tracking | Stock levels with expiry dates and restock alerts |
| 📈 Sales Reports | Date-filtered reports with printable receipts |
| 👥 User Management | Create users with auto-generated usernames and admin-set passwords |
| 📋 Activity Log | Every action logged with user, IP, and timestamp |
| 🚨 Login Attempt Log | Tracks all login attempts — success and failed |
| 🛠️ Maintenance | Category management with product sub-lists |
| 🎨 Warm Bakery UI | Custom pastel theme, wallpaper background, warm sidebar |

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.0+
- **Database:** MySQL / MariaDB (via XAMPP)
- **Frontend:** Bootstrap 5, jQuery 3.6, DataTables, Select2
- **Icons:** Font Awesome 6
- **Fonts:** Inter (dashboard), Pacifico + Quicksand (login)
- **Server:** Apache (XAMPP localhost)

---

## ⚙️ Installation

### Requirements
- XAMPP (Apache + MySQL + PHP 8.0+)
- A web browser

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/ben-can-code/jezz-bakery.git
```
Move the folder to your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\jezz bakery managment system\
```

**2. Create the database**
- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Create a new database named `bsms_db`
- Import the file: `database/bsms_db.sql`

**3. Configure the database connection**
- Create `DBConnection.php` in the project root with:
```php
<?php
Class DBConnection {
    protected $db;
    function __construct() {
        $this->db = new mysqli('localhost', 'root', '', 'bsms_db');
    }
    function db_connect() { return $this->db; }
    function __destruct() { $this->db->close(); }
}
$db   = new DBConnection();
$conn = $db->db_connect();
```

**4. Open in browser**
```
http://localhost/jezz%20bakery%20managment%20system/
```

---

## 🔑 Default Login Credentials

| Role | Username | Password |
|---|---|---|
| Administrator | `admin` | `admin123` |
| Cashier | `cblake` | `cblake` |
| Administrator | `mcooper` | `mcooper` |

> See `LOGIN_INFO.txt` for the full credentials reference file.

---

## 📁 Project Structure

```
jezz bakery managment system/
├── index.php              # Main app shell (sidebar, topbar, routing)
├── login.php              # Login page
├── Actions.php            # All AJAX action handlers
├── DBConnection.php       # Database connection (excluded from git)
├── home.php               # Dashboard page
├── products.php           # Products list
├── manage_product.php     # Add/Edit product form
├── users.php              # Users & Logs (3 tabs)
├── manage_user.php        # Add/Edit user form
├── sales.php              # POS / Point of Sale
├── sales_report.php       # Sales report with date filter
├── stocks.php             # Stock management
├── maintenance.php        # Category management
├── view_receipt.php       # Printable receipt
├── LOGIN_INFO.txt         # Login credentials reference
├── database/
│   ├── bsms_db.sql        # Full database dump
│   └── patch_products.sql # Product data patch script
├── css/                   # Bootstrap CSS
├── js/                    # jQuery, Bootstrap JS
├── DataTables/            # DataTables library
├── select2/               # Select2 library
├── Font-Awesome-master/   # Icons
└── images/
    └── wallpaper.jfif     # Bakery background image
```

---

## 👨‍💻 Developer

**Bernard Mwangi**
- GitHub: [@ben-can-code](https://github.com/ben-can-code)
- Repository: [github.com/ben-can-code/jezz-bakery](https://github.com/ben-can-code/jezz-bakery)

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<div align="center">
Made with 🧁 by <strong>Bernard Mwangi</strong>
</div>
