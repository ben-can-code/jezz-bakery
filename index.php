<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:./login.php");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
if($_SESSION['type'] != 1 && in_array($page, array('maintenance','products','stocks','users'))){
    header("Location:./");
    exit;
}

$nav_items = [
    ['page' => 'home',         'label' => 'Dashboard',   'icon' => 'fas fa-th-large',       'admin_only' => false],
    ['page' => 'products',     'label' => 'Products',    'icon' => 'fas fa-box-open',        'admin_only' => true],
    ['page' => 'stocks',       'label' => 'Stocks',      'icon' => 'fas fa-layer-group',     'admin_only' => true],
    ['page' => 'sales',        'label' => 'POS',         'icon' => 'fas fa-cash-register',   'admin_only' => false],
    ['page' => 'sales_report', 'label' => 'Sales',       'icon' => 'fas fa-chart-line',      'admin_only' => false],
    ['page' => 'users',        'label' => 'Users & Logs','icon' => 'fas fa-users',           'admin_only' => true],
    ['page' => 'maintenance',  'label' => 'Maintenance', 'icon' => 'fas fa-tools',           'admin_only' => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_', ' ', $page)) ?> | Jezz Bakery Management System</title>
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./select2/css/select2.min.css">
    <link rel="stylesheet" href="./DataTables/datatables.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./DataTables/datatables.min.js"></script>
    <script src="./select2/js/select2.full.min.js"></script>
    <script src="./Font-Awesome-master/js/all.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:       #e67e22;
            --accent-dark:  #cf6d17;
            --accent-light: rgba(230,126,34,0.12);
            --sidebar-w:    240px;
            --sidebar-bg:   rgba(62, 28, 10, 0.82);
            --topbar-h:     60px;
            --body-bg:      #f4f6fb;
            --text-main:    #111827;
            --text-muted:   #6b7280;
            --border:       #e5e7eb;
            --card-bg:      #ffffff;
            --shadow-sm:    0 1px 4px rgba(0,0,0,0.06);
            --shadow-md:    0 4px 20px rgba(0,0,0,0.08);
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; color: var(--text-main); }

        /* ── Original wallpaper — no overlay filter ──────────── */
        body {
            background-image: url('./images/wallpaper.jfif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
        }

        /* Stack everything correctly */
        #sidebar       { z-index: 1000; }
        #topbar        { z-index: 900;  }
        #main-wrapper  { position: relative; z-index: 1; }
        .modal, .modal-backdrop { z-index: 1050; }

        /* ── Sidebar ─────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100%;
            background: linear-gradient(180deg,
                rgba(74, 28, 6, 0.90) 0%,
                rgba(50, 18, 4, 0.93) 50%,
                rgba(40, 14, 3, 0.95) 100%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            border-right: 1px solid rgba(251,146,60,0.18);
            box-shadow: 4px 0 24px rgba(0,0,0,0.25);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid rgba(251,146,60,0.18);
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #fb923c, #fcd34d);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(251,146,60,0.45);
        }
        .sidebar-brand-icon i { color: #fff; font-size: 1rem; }
        .sidebar-brand-text { line-height: 1.2; }
        .sidebar-brand-text strong {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fde8d0;
        }
        .sidebar-brand-text span {
            font-size: 0.68rem;
            color: rgba(253,216,180,0.5);
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem 0.6rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(251,146,60,0.2) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(251,146,60,0.25); border-radius: 3px; }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(253,186,116,0.45);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 0.65rem 0.35rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.62rem 0.75rem;
            border-radius: 9px;
            text-decoration: none;
            color: rgba(253,216,180,0.65);
            font-size: 0.855rem;
            font-weight: 500;
            transition: background 0.18s, color 0.18s;
            margin-bottom: 2px;
        }
        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .sidebar-link:hover {
            background: rgba(251,146,60,0.14);
            color: #fde8d0;
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(251,146,60,0.28), rgba(249,168,212,0.18));
            color: #fcd34d;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(251,146,60,0.25);
        }
        .sidebar-link.active i { color: #fb923c; }

        .sidebar-footer {
            border-top: 1px solid rgba(251,146,60,0.18);
            padding: 0.85rem 0.85rem;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #fb923c, #fcd34d);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 2px 8px rgba(251,146,60,0.4);
        }
        .user-info { flex: 1; min-width: 0; }
        .user-info strong {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #fde8d0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-info span {
            font-size: 0.68rem;
            color: rgba(253,216,180,0.45);
        }
        .user-actions { display: flex; gap: 4px; }
        .user-action-btn {
            width: 28px; height: 28px;
            background: rgba(251,146,60,0.12);
            border: none;
            border-radius: 7px;
            color: rgba(253,216,180,0.55);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            transition: background 0.18s, color 0.18s;
            text-decoration: none;
        }
        .user-action-btn:hover {
            background: rgba(251,146,60,0.28);
            color: #fde8d0;
        }

        /* ── Top bar ─────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255, 248, 240, 0.82);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(251,146,60,0.2);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            z-index: 900;
            box-shadow: 0 2px 12px rgba(251,146,60,0.10);
        }

        #sidebarToggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #92400e;
            cursor: pointer;
            padding: 0.25rem;
            line-height: 1;
        }

        .topbar-title {
            flex: 1;
            font-size: 0.95rem;
            font-weight: 600;
            color: #78350f;
        }

        .topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, rgba(251,146,60,0.18), rgba(249,168,212,0.18));
            color: #c2410c;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(251,146,60,0.25);
        }

        /* ── Main content ────────────────────────── */
        #main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100%;
        }

        #page-container {
            padding: 1.5rem;
        }

        /* ── Cards — warm frosted glass ──────────────────────── */
        .card {
            background: rgba(255, 252, 248, 0.92) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(251,146,60,0.15) !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 20px rgba(120,53,15,0.10) !important;
        }
        .card-header {
            background: rgba(255,245,235,0.95) !important;
            border-bottom: 1px solid rgba(251,146,60,0.15) !important;
            border-radius: 14px 14px 0 0 !important;
        }
        .card-title { color: #78350f !important; font-weight: 700; }

        /* ── Bootstrap button overrides ─────────────────────── */
        /* Primary → warm coral */
        .btn-primary {
            background: linear-gradient(135deg, #fb923c, #f97316) !important;
            border-color: #ea580c !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #f97316, #ea580c) !important;
            border-color: #c2410c !important;
            box-shadow: 0 4px 12px rgba(251,146,60,0.40) !important;
        }
        /* Dark → espresso brown */
        .btn-dark {
            background: linear-gradient(135deg, #78350f, #92400e) !important;
            border-color: #78350f !important;
            color: #fde8d0 !important;
        }
        .btn-dark:hover {
            background: linear-gradient(135deg, #92400e, #b45309) !important;
            border-color: #92400e !important;
        }
        /* Warning stays warm gold */
        .btn-warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
            border-color: #d97706 !important;
            color: #fff !important;
        }
        .btn-warning:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        }
        /* Danger stays red but warms slightly */
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            border-color: #b91c1c !important;
            color: #fff !important;
        }
        /* Light → warm off-white */
        .btn-light {
            background: rgba(255,245,235,0.95) !important;
            border-color: rgba(251,146,60,0.3) !important;
            color: #92400e !important;
        }
        .btn-light:hover {
            background: rgba(254,235,200,0.95) !important;
        }
        /* Outline variants */
        .btn-outline-secondary {
            border-color: rgba(251,146,60,0.4) !important;
            color: #92400e !important;
        }
        .btn-outline-secondary:hover {
            background: rgba(251,146,60,0.12) !important;
            color: #78350f !important;
        }
        .btn-outline-danger {
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }
        .btn-outline-danger:hover {
            background: #ef4444 !important;
            color: #fff !important;
        }

        /* ── Dropdown menus ──────────────────────────────────── */
        .dropdown-menu {
            background: rgba(255,252,248,0.97) !important;
            border: 1px solid rgba(251,146,60,0.18) !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 28px rgba(120,53,15,0.14) !important;
        }
        .dropdown-item { color: #78350f !important; font-size: 0.83rem; }
        .dropdown-item:hover {
            background: rgba(251,146,60,0.10) !important;
            color: #431407 !important;
        }
        .dropdown-item.text-danger { color: #dc2626 !important; }
        .dropdown-item.text-danger:hover { background: rgba(239,68,68,0.08) !important; }
        .dropdown-divider { border-color: rgba(251,146,60,0.15) !important; }

        /* ── Tables ──────────────────────────────────────────── */
        .table {
            background: transparent !important;
            color: #44403c !important;
        }
        .table thead th {
            background: rgba(254,235,200,0.7) !important;
            color: #78350f !important;
            border-bottom: 2px solid rgba(251,146,60,0.2) !important;
            font-weight: 700;
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background: rgba(255,247,237,0.55) !important;
        }
        .table-hover > tbody > tr:hover > * {
            background: rgba(251,146,60,0.08) !important;
        }
        .table td, .table th {
            border-color: rgba(251,146,60,0.12) !important;
        }

        /* ── Badges ──────────────────────────────────────────── */
        .badge.bg-success   { background: #16a34a !important; }
        .badge.bg-danger    { background: #dc2626 !important; }
        .badge.bg-primary   { background: linear-gradient(135deg,#fb923c,#f97316) !important; }
        .badge.bg-warning   { background: linear-gradient(135deg,#fbbf24,#f59e0b) !important; color:#fff !important; }
        .badge.bg-info      { background: #0891b2 !important; }
        .badge.bg-secondary { background: #78716c !important; }
        .badge.bg-dark      { background: #78350f !important; }
        .badge.bg-light     { background: rgba(255,245,235,0.9) !important; color:#92400e !important; border:1px solid rgba(251,146,60,0.25) !important; }

        /* ── Form controls ───────────────────────────────────── */
        .form-control, .form-select {
            border-color: rgba(251,146,60,0.3) !important;
            background: rgba(255,252,248,0.95) !important;
            color: #44403c !important;
            border-radius: 8px !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #fb923c !important;
            box-shadow: 0 0 0 3px rgba(251,146,60,0.15) !important;
        }

        /* ── Nav tabs (Users & Logs page) ────────────────────── */
        .nav-tabs {
            border-bottom: 2px solid rgba(251,146,60,0.2) !important;
        }
        .nav-tabs .nav-link {
            color: #92400e !important;
            font-weight: 600;
            border: none !important;
            border-bottom: 2px solid transparent !important;
        }
        .nav-tabs .nav-link.active {
            color: #fb923c !important;
            border-bottom: 2px solid #fb923c !important;
            background: transparent !important;
        }
        .nav-tabs .nav-link:hover { color: #c2410c !important; }

        /* ── Select2 ─────────────────────────────────────────── */
        .select2-container--default .select2-selection--single {
            border-color: rgba(251,146,60,0.3) !important;
            background: rgba(255,252,248,0.95) !important;
            border-radius: 8px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #fb923c !important;
            box-shadow: 0 0 0 3px rgba(251,146,60,0.15) !important;
        }
        .select2-dropdown {
            border-color: rgba(251,146,60,0.2) !important;
            background: rgba(255,252,248,0.98) !important;
            border-radius: 10px !important;
        }
        .select2-container--default .select2-results__option--highlighted {
            background: rgba(251,146,60,0.12) !important;
            color: #78350f !important;
        }

        /* ── Flash alert ─────────────────────────── */
        .flash-alert {
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: var(--shadow-sm);
        }
        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            opacity: 0.5;
            line-height: 1;
        }
        .flash-close:hover { opacity: 1; }

        /* ── Modals ──────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.25rem 0.85rem;
            border-radius: 14px 14px 0 0;
        }
        .modal-title { font-size: 0.95rem; font-weight: 600; }
        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 0.75rem 1.25rem;
            border-radius: 0 0 14px 14px;
        }
        .modal-body { padding: 1.25rem; }
        .modal-dialog.large    { width: 80% !important; max-width: unset; }
        .modal-dialog.mid-large{ width: 50% !important; max-width: unset; }

        /* ── Misc helpers ────────────────────────── */
        .thumbnail-img    { width: 50px; height: 50px; margin: 2px; object-fit: cover; border-radius: 6px; }
        .display-select-image { width: 60px; height: 60px; margin: 2px; object-fit: cover; border-radius: 6px; }
        img.display-image { width: 100%; height: 45vh; object-fit: cover; background: #000; }

        .truncate-1 {
            overflow: hidden; text-overflow: ellipsis;
            display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;
        }
        .truncate-3 {
            overflow: hidden; text-overflow: ellipsis;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        .img-del-btn { right: 2px; top: -3px; }
        .img-del-btn > .btn { font-size: 10px; padding: 0 2px !important; }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(calc(-1 * var(--sidebar-w)));
            }
            #sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.35);
            }
            #main-wrapper { margin-left: 0; }
            #topbar { left: 0; }
            #sidebarToggle { display: block; }
            .modal-dialog.large,
            .modal-dialog.mid-large { width: 100% !important; }
            #page-container { padding: 1rem; }

            /* Overlay */
            #sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            #sidebar-overlay.show { display: block; }
        }

        @media print {
            #sidebar, #topbar { display: none !important; }
            #main-wrapper { margin-left: 0; padding-top: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar overlay (mobile) -->
<div id="sidebar-overlay"></div>

<!-- ── Sidebar ── -->
<aside id="sidebar">
    <a class="sidebar-brand" href="./">
        <div class="sidebar-brand-icon">
            <i class="fas fa-bread-slice"></i>
        </div>
        <div class="sidebar-brand-text">
            <strong>Jezz Bakery</strong>
            <span>Management System</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main Menu</div>
        <?php foreach($nav_items as $item): ?>
            <?php if($item['admin_only'] && $_SESSION['type'] != 1) continue; ?>
            <a href="<?php echo $item['page'] === 'home' ? './' : './?page='.$item['page'] ?>"
               class="sidebar-link <?php echo ($page === $item['page']) ? 'active' : '' ?>">
                <i class="<?php echo $item['icon'] ?>"></i>
                <?php echo $item['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['fullname'], 0, 1)); ?>
            </div>
            <div class="user-info">
                <strong title="<?php echo htmlspecialchars($_SESSION['fullname']) ?>">
                    <?php echo htmlspecialchars($_SESSION['fullname']) ?>
                </strong>
                <span><?php echo $_SESSION['type'] == 1 ? 'Administrator' : 'Staff' ?></span>
            </div>
            <div class="user-actions">
                <a href="./?page=manage_account" class="user-action-btn" title="Manage Account">
                    <i class="fas fa-user-cog"></i>
                </a>
                <a href="./Actions.php?a=logout" class="user-action-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
</aside>

<!-- ── Top bar ── -->
<header id="topbar">
    <button id="sidebarToggle" title="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">
        <?php
            $labels = [
                'home'           => 'Dashboard',
                'products'       => 'Products',
                'stocks'         => 'Stocks',
                'sales'          => 'Point of Sale',
                'sales_report'   => 'Sales Report',
                'users'          => 'Users & Logs',
                'maintenance'    => 'Maintenance',
                'manage_account' => 'Manage Account',
            ];
            echo $labels[$page] ?? ucwords(str_replace('_', ' ', $page));
        ?>
    </div>
    <div class="topbar-badge">
        <i class="fas fa-circle" style="font-size:0.45rem;"></i>
        <?php echo $_SESSION['type'] == 1 ? 'Administrator' : 'Staff' ?>
    </div>
</header>

<!-- ── Main content ── -->
<div id="main-wrapper">
    <div id="page-container">

        <?php if(isset($_SESSION['flashdata'])): ?>
        <div class="flash-alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
            <i class="fas <?php echo $_SESSION['flashdata']['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
            <?php echo $_SESSION['flashdata']['msg'] ?>
            <button class="flash-close" onclick="$(this).closest('.flash-alert').hide('fast').remove()">&times;</button>
        </div>
        <?php unset($_SESSION['flashdata']); endif; ?>

        <?php include $page . '.php'; ?>
    </div>
</div>

<!-- ── Modals ── -->
<div class="modal fade" id="uni_modal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uni_modal_secondary" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="submit" onclick="$('#uni_modal_secondary form').submit()">Save</button>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm_modal" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
            </div>
            <div class="modal-body">
                <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="confirm">Continue</button>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    $('#sidebarToggle').on('click', function(){
        $('#sidebar').toggleClass('open');
        $('#sidebar-overlay').toggleClass('show');
    });
    $('#sidebar-overlay').on('click', function(){
        $('#sidebar').removeClass('open');
        $(this).removeClass('show');
    });
</script>
</body>
</html>
