<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0){
    header("Location:./");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | Jezz Bakery Management System</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        /* ── Reset & base ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Quicksand', sans-serif; }

        /* ── Warm pastel background ───────────────────────────── */
        body {
            background-image: url('./images/wallpaper.jfif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        /* Warm pastel overlay on top of the wallpaper */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(252,228,236,0.55) 0%,
                rgba(255,248,225,0.45) 40%,
                rgba(253,243,231,0.50) 70%,
                rgba(252,228,236,0.55) 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Floating bakery doodle elements (pure CSS + SVG) ─── */
        .doodle-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        /* individual floating shapes */
        .doodle-bg span {
            position: absolute;
            display: block;
            opacity: 0.18;
            animation: floatDoodle linear infinite;
        }
        @keyframes floatDoodle {
            0%   { transform: translateY(0px) rotate(0deg); opacity: .18; }
            50%  { transform: translateY(-18px) rotate(8deg); opacity: .25; }
            100% { transform: translateY(0px) rotate(0deg); opacity: .18; }
        }

        /* ── Main layout wrapper ──────────────────────────────── */
        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* ── Hero title ───────────────────────────────────────── */
        .hero-title-wrap {
            text-align: center;
            margin-bottom: 1.6rem;
            position: relative;
        }
        /* Ribbon banner behind title */
        .ribbon-banner {
            display: inline-block;
            position: relative;
            padding: 0.6rem 3.5rem;
            margin-bottom: 0.4rem;
        }
        .ribbon-banner::before,
        .ribbon-banner::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 22px;
            background: linear-gradient(135deg, #f9a8d4, #fcd34d);
            clip-path: polygon(0 50%, 25% 0, 100% 0, 100% 100%, 25% 100%);
            border-radius: 3px;
            opacity: 0.7;
        }
        .ribbon-banner::before { left: 0; }
        .ribbon-banner::after  { right: 0; transform: translateY(-50%) scaleX(-1); }

        .sys-title {
            font-family: 'Pacifico', cursive;
            font-size: clamp(2rem, 6vw, 4.2rem);
            background: linear-gradient(135deg, #f9a8d4 0%, #fcd34d 40%, #fb923c 75%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(2px 4px 6px rgba(251,146,60,0.28));
            line-height: 1.2;
            letter-spacing: 0.01em;
            animation: titleFloat 4s ease-in-out infinite;
        }
        @keyframes titleFloat {
            0%, 100% { transform: translateY(0px) rotate(-0.5deg); }
            50%       { transform: translateY(-6px) rotate(0.5deg); }
        }
        .sys-subtitle {
            font-family: 'Quicksand', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: #d97706;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-top: 0.2rem;
            opacity: 0.85;
        }

        /* Small decorative icons row */
        .deco-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            margin: 0.5rem 0 1rem;
            font-size: 1.3rem;
            opacity: 0.65;
        }
        .deco-row span { animation: floatDoodle 3s ease-in-out infinite; }
        .deco-row span:nth-child(2) { animation-delay: .4s; font-size: 1rem; }
        .deco-row span:nth-child(3) { animation-delay: .8s; }
        .deco-row span:nth-child(4) { animation-delay: .3s; font-size: 1rem; }
        .deco-row span:nth-child(5) { animation-delay: .6s; }

        /* ── Login card ───────────────────────────────────────── */
        .login-card-outer {
            width: 100%;
            max-width: 420px;
            position: relative;
        }
        /* Lace ribbon above card */
        .lace-ribbon {
            text-align: center;
            margin-bottom: -1px;
            position: relative;
            z-index: 2;
        }
        .lace-ribbon svg {
            display: block;
            margin: 0 auto;
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            border: none;
            border-radius: 0 0 24px 24px;
            box-shadow:
                0 8px 32px rgba(251,146,60,0.18),
                0 2px 8px rgba(249,168,212,0.25),
                inset 0 0 0 1.5px rgba(255,255,255,0.7);
            background: rgba(255, 252, 248, 0.97);
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        /* Card header */
        .login-card .card-header {
            background: linear-gradient(135deg, #fb923c 0%, #f9a8d4 60%, #fcd34d 100%);
            padding: 1.3rem 1.75rem 1rem;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .login-card .card-header::after {
            content: '';
            position: absolute;
            right: -20px; top: -20px;
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
        }
        .login-card .card-header::before {
            content: '';
            position: absolute;
            right: 30px; top: 20px;
            width: 50px; height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .card-header-title {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .card-header-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card-header-text h5 {
            margin: 0;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: #fff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        .card-header-text small {
            color: rgba(255,255,255,0.85);
            font-size: 0.73rem;
            font-weight: 500;
        }

        /* Card body */
        .login-card .card-body {
            padding: 1.4rem 1.75rem 1.6rem;
            background: rgba(255,252,248,0.98);
        }

        /* ── Role tabs ────────────────────────────────────────── */
        .role-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.3rem;
            background: #fef3e8;
            border-radius: 50px;
            padding: 4px;
        }
        .role-tab {
            flex: 1;
            padding: 0.5rem 0;
            text-align: center;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 700;
            color: #b45309;
            border-radius: 50px;
            transition: all 0.22s cubic-bezier(.4,0,.2,1);
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .role-tab .tab-icon {
            width: 22px; height: 22px;
            border-radius: 50%;
            background: rgba(251,146,60,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
            transition: background 0.22s;
        }
        .role-tab.active {
            background: linear-gradient(135deg, #fb923c, #f9a8d4);
            color: #fff;
            box-shadow: 0 2px 10px rgba(251,146,60,0.35);
        }
        .role-tab.active .tab-icon {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }
        .role-tab:hover:not(.active) {
            background: rgba(251,146,60,0.1);
        }

        /* ── Form fields ──────────────────────────────────────── */
        .form-label-cute {
            font-size: 0.78rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .form-control-cute {
            border: 1.5px solid #fcd9a0;
            border-radius: 12px;
            background: #fffbf5;
            font-family: 'Quicksand', sans-serif;
            font-size: 0.88rem;
            color: #44403c;
            padding: 0.5rem 0.85rem;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-control-cute:focus {
            border-color: #fb923c;
            box-shadow: 0 0 0 3px rgba(251,146,60,0.15);
            background: #fff;
            outline: none;
        }
        .pw-wrapper { position: relative; }
        .pw-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #d4a37a;
            cursor: pointer;
            padding: 0;
            font-size: 0.82rem;
            line-height: 1;
            transition: color 0.18s;
        }
        .pw-toggle:hover { color: #fb923c; }

        /* ── Login button ─────────────────────────────────────── */
        .btn-cute-login {
            background: linear-gradient(135deg, #fb923c 0%, #f472b6 100%);
            border: none;
            border-radius: 50px;
            color: #fff;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.55rem 2rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(251,146,60,0.4);
            transition: all 0.22s;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }
        .btn-cute-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251,146,60,0.5);
            background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);
        }
        .btn-cute-login:active { transform: translateY(0); }
        .btn-icon-badge {
            width: 22px; height: 22px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
        }

        /* ── Floating background doodles ──────────────────────── */
        /* positioned across the viewport */
        .bg-doodle {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            opacity: 0.13;
            animation: floatDoodle ease-in-out infinite;
            font-size: 2.5rem;
            user-select: none;
        }

        /* ── Alert messages ───────────────────────────────────── */
        .pop_msg {
            border-radius: 12px;
            font-size: 0.82rem;
            font-family: 'Quicksand', sans-serif;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 480px) {
            .sys-title { font-size: 2rem; }
            .login-card .card-body { padding: 1.1rem 1.1rem 1.3rem; }
        }
    </style>
</head>
<body>

<!-- ── Floating background bakery doodles ────────────────────── -->
<div aria-hidden="true">
    <!-- cupcakes -->
    <span class="bg-doodle" style="top:6%;  left:4%;  font-size:3.5rem; animation-duration:5.2s; animation-delay:0s">🧁</span>
    <span class="bg-doodle" style="top:72%; left:8%;  font-size:2.8rem; animation-duration:4.8s; animation-delay:1.1s">🧁</span>
    <span class="bg-doodle" style="top:18%; right:5%; font-size:3rem;   animation-duration:6s;   animation-delay:0.5s">🧁</span>
    <span class="bg-doodle" style="top:80%; right:6%; font-size:3.2rem; animation-duration:5.5s; animation-delay:1.8s">🧁</span>
    <!-- breads & croissants -->
    <span class="bg-doodle" style="top:35%; left:2%;  font-size:2.5rem; animation-duration:7s;   animation-delay:0.3s">🥐</span>
    <span class="bg-doodle" style="top:55%; right:3%; font-size:2.8rem; animation-duration:6.5s; animation-delay:1.4s">🥐</span>
    <span class="bg-doodle" style="top:90%; left:30%; font-size:2.2rem; animation-duration:5.8s; animation-delay:0.9s">🍞</span>
    <span class="bg-doodle" style="top:3%;  right:25%;font-size:2rem;   animation-duration:6.2s; animation-delay:2s">🍞</span>
    <!-- cookies & cakes -->
    <span class="bg-doodle" style="top:48%; left:93%; font-size:2.4rem; animation-duration:5s;   animation-delay:0.7s">🍪</span>
    <span class="bg-doodle" style="top:12%; left:45%; font-size:1.8rem; animation-duration:7.5s; animation-delay:1.6s">🍰</span>
    <span class="bg-doodle" style="top:88%; right:22%;font-size:2rem;   animation-duration:6.8s; animation-delay:0.2s">🎂</span>
    <!-- hearts & stars -->
    <span class="bg-doodle" style="top:62%; left:50%; font-size:1.6rem; animation-duration:4.5s; animation-delay:2.3s; color:#f9a8d4">🩷</span>
    <span class="bg-doodle" style="top:28%; right:18%;font-size:1.4rem; animation-duration:5.3s; animation-delay:1.2s; color:#fcd34d">⭐</span>
    <span class="bg-doodle" style="top:94%; left:55%; font-size:1.5rem; animation-duration:4.9s; animation-delay:0.6s; color:#f9a8d4">🩷</span>
    <!-- utensils -->
    <span class="bg-doodle" style="top:43%; right:14%;font-size:2.2rem; animation-duration:8s;   animation-delay:1s">🥄</span>
    <span class="bg-doodle" style="top:8%;  left:20%; font-size:2rem;   animation-duration:7.2s; animation-delay:1.9s">🧂</span>
</div>

<!-- ── Main page wrapper ──────────────────────────────────────── -->
<div class="page-wrap">

    <!-- ── Hero title ──────────────────────────────────────── -->
    <div class="hero-title-wrap">
        <div class="ribbon-banner">
            <h1 class="sys-title">Jezz Bakery</h1>
        </div>
        <div class="sys-subtitle">✦ Management System ✦</div>
        <!-- Cute deco icon row -->
        <div class="deco-row" aria-hidden="true">
            <span>🧁</span>
            <span style="color:#fcd34d">✦</span>
            <span>🥐</span>
            <span style="color:#f9a8d4">✦</span>
            <span>🍪</span>
        </div>
    </div>

    <!-- ── Login card ──────────────────────────────────────── -->
    <div class="login-card-outer">

        <!-- Decorative lace ribbon SVG above card -->
        <div class="lace-ribbon" aria-hidden="true">
            <svg viewBox="0 0 420 28" fill="none" xmlns="http://www.w3.org/2000/svg" style="height:28px">
                <path d="M0 14 Q10.5 2 21 14 Q31.5 26 42 14 Q52.5 2 63 14 Q73.5 26 84 14 Q94.5 2 105 14 Q115.5 26 126 14 Q136.5 2 147 14 Q157.5 26 168 14 Q178.5 2 189 14 Q199.5 26 210 14 Q220.5 2 231 14 Q241.5 26 252 14 Q262.5 2 273 14 Q283.5 26 294 14 Q304.5 2 315 14 Q325.5 26 336 14 Q346.5 2 357 14 Q367.5 26 378 14 Q388.5 2 399 14 Q409.5 26 420 14"
                      stroke="url(#laceGrad)" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <!-- Small circles along the lace -->
                <circle cx="21"  cy="14" r="2.5" fill="#f9a8d4" opacity=".7"/>
                <circle cx="63"  cy="14" r="2.5" fill="#fcd34d" opacity=".7"/>
                <circle cx="105" cy="14" r="2.5" fill="#fb923c" opacity=".7"/>
                <circle cx="147" cy="14" r="2.5" fill="#f9a8d4" opacity=".7"/>
                <circle cx="189" cy="14" r="2.5" fill="#fcd34d" opacity=".7"/>
                <circle cx="231" cy="14" r="2.5" fill="#fb923c" opacity=".7"/>
                <circle cx="273" cy="14" r="2.5" fill="#f9a8d4" opacity=".7"/>
                <circle cx="315" cy="14" r="2.5" fill="#fcd34d" opacity=".7"/>
                <circle cx="357" cy="14" r="2.5" fill="#fb923c" opacity=".7"/>
                <defs>
                    <linearGradient id="laceGrad" x1="0" y1="0" x2="420" y2="0" gradientUnits="userSpaceOnUse">
                        <stop offset="0%"   stop-color="#f9a8d4"/>
                        <stop offset="50%"  stop-color="#fcd34d"/>
                        <stop offset="100%" stop-color="#fb923c"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="card login-card">
            <!-- Card header -->
            <div class="card-header">
                <div class="card-header-title">
                    <div class="card-header-icon">
                        <i class="fas fa-cookie-bite"></i>
                    </div>
                    <div class="card-header-text">
                        <h5>Welcome Back! 🎀</h5>
                        <small>Choose your role and sign in to continue</small>
                    </div>
                </div>
            </div>

            <!-- Card body -->
            <div class="card-body">
                <!-- Role tabs -->
                <div class="role-tabs">
                    <div class="role-tab active" id="tab-cashier" onclick="switchRole('cashier')">
                        <span class="tab-icon"><i class="fas fa-cash-register"></i></span>
                        Cashier
                    </div>
                    <div class="role-tab" id="tab-administrator" onclick="switchRole('administrator')">
                        <span class="tab-icon"><i class="fas fa-user-shield"></i></span>
                        Administrator
                    </div>
                </div>

                <form action="" id="login-form">
                    <!-- Username -->
                    <div class="mb-2">
                        <label class="form-label-cute">
                            <i class="fas fa-user" style="color:#fb923c"></i> Username
                        </label>
                        <input type="text" id="username" autofocus name="username"
                               class="form-control form-control-cute w-100" required>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label-cute">
                            <i class="fas fa-lock" style="color:#fb923c"></i> Password
                        </label>
                        <div class="pw-wrapper">
                            <input type="password" id="password" name="password"
                                   class="form-control form-control-cute w-100" required
                                   style="padding-right:2.2rem">
                            <button type="button" class="pw-toggle" onclick="togglePassword()" title="Show / Hide">
                                <i class="fas fa-eye" id="pw-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Submit -->
                    <div class="d-flex justify-content-end">
                        <button class="btn-cute-login" type="submit">
                            <span class="btn-icon-badge"><i class="fas fa-hat-chef"></i></span>
                            Let's Go!
                        </button>
                    </div>
                </form>

                <!-- Cute footer note -->
                <div class="text-center mt-3" style="font-size:.7rem;color:#d4a37a;font-weight:600">
                    🍰 Fresh baked just for you, every day 🍰
                </div>
            </div>
        </div>
    </div><!-- /login-card-outer -->

</div><!-- /page-wrap -->

<script>
    var roles = {
        cashier:       { username: 'cblake', password: 'cblake' },
        administrator: { username: 'admin',  password: '' }
    };

    function switchRole(role) {
        document.getElementById('tab-cashier').classList.toggle('active', role === 'cashier');
        document.getElementById('tab-administrator').classList.toggle('active', role === 'administrator');
        $('#username').val(roles[role].username);
        $('#password').val(roles[role].password);
        $('#username').focus();
    }

    function togglePassword() {
        var pw  = document.getElementById('password');
        var eye = document.getElementById('pw-eye');
        if (pw.type === 'password') {
            pw.type = 'text';
            eye.className = 'fas fa-eye-slash';
        } else {
            pw.type = 'password';
            eye.className = 'fas fa-eye';
        }
    }

    $(function(){
        switchRole('cashier');

        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove();
            var _this = $(this);
            var _el   = $('<div>').addClass('pop_msg');
            _this.find('button[type="submit"]').attr('disabled', true)
                 .html('<i class="fas fa-spinner fa-spin me-1"></i> Baking...');
            $.ajax({
                url: './Actions.php?a=login',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                error: function(err){
                    console.log(err);
                    _el.addClass('alert alert-danger').text('An error occurred.');
                    _this.prepend(_el); _el.show('slow');
                    _this.find('button[type="submit"]').attr('disabled', false)
                         .html('<span class="btn-icon-badge"><i class="fas fa-hat-chef"></i></span> Let\'s Go!');
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success');
                        setTimeout(function(){ location.replace('./'); }, 1800);
                    } else {
                        _el.addClass('alert alert-danger');
                    }
                    _el.text(resp.msg).hide();
                    _this.prepend(_el); _el.show('slow');
                    _this.find('button[type="submit"]').attr('disabled', false)
                         .html('<span class="btn-icon-badge"><i class="fas fa-hat-chef"></i></span> Let\'s Go!');
                }
            });
        });
    });
</script>
</body>
</html>
