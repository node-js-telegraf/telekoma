<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Language persistence
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'zh'])) {
    $_SESSION['lang'] = $_GET['lang'];
    // Redirect to clean URL (no query string) to avoid accidental resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
$lang = $_SESSION['lang'] ?? 'en';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle password change
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "Please fill in all fields";
    }
    if ($newPassword !== $confirmPassword) {
        $errors[] = "New passwords do not match";
    }
    if (strlen($newPassword) < 6 || strlen($newPassword) > 30) {
        $errors[] = "New password must be between 6 and 30 characters";
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $newPassword)) {
        $errors[] = "Password can only contain letters and numbers";
    }
    if (strpos($newPassword, '|') !== false) {
        $errors[] = "Password cannot contain the | character";
    }

    if (empty($errors)) {
        if (file_exists('bus.txt')) {
            $users = file('bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $userFound = false;
            foreach ($users as $i => $user) {
                $userData = explode('|', $user);
                if ($userData[0] === $_SESSION['username'] && $userData[1] === $currentPassword) {
                    $userFound = true;
                    $userData[1] = $newPassword;
                    $users[$i] = implode('|', $userData);
                    if (file_put_contents('bus.txt', implode("\n", $users))) {
                        $_SESSION['success'] = "Password updated successfully!";
                    } else {
                        $errors[] = "Error updating password";
                    }
                    break;
                }
            }
            if (!$userFound) {
                $errors[] = "Current password is incorrect";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#000000">
    <title>Profile | KillByte Solutions</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ========== KILLBYTE LUXURY CSS ========== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: auto; }
        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            color: #f0f0f0;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            transition: background 0.4s, color 0.4s;
        }
        ::-webkit-scrollbar { width: 2px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(204,17,17,0.15); border-radius: 1px; }

        :root {
            --bg-void: #000000;
            --bg-deep: #010101;
            --bg-surface: #030303;
            --bg-elevated: #060606;
            --bg-glass: rgba(255,255,255,0.015);
            --bg-glass-hover: rgba(255,255,255,0.025);
            --text-primary: #f0f0f0;
            --text-secondary: #8a8a8a;
            --text-tertiary: #555555;
            --accent-crimson: #cc1111;
            --accent-crimson-bright: #ee2222;
            --accent-crimson-dim: rgba(204,17,17,0.04);
            --border-subtle: rgba(255,255,255,0.02);
            --border-crimson: rgba(204,17,17,0.08);
            --gradient-crimson: linear-gradient(135deg, #cc1111 0%, #aa0e0e 50%, #880a0a 100%);
            --font-display: 'Space Grotesk', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
        }
        [data-theme="light"] {
            --bg-void: #f0f0f0;
            --bg-deep: #e8e8e8;
            --bg-surface: #e0e0e0;
            --bg-elevated: #d8d8d8;
            --bg-glass: rgba(0,0,0,0.02);
            --bg-glass-hover: rgba(0,0,0,0.04);
            --text-primary: #1a1a1a;
            --text-secondary: #4a4a4a;
            --text-tertiary: #7a7a7a;
            --border-subtle: rgba(0,0,0,0.04);
            --border-crimson: rgba(204,17,17,0.1);
        }

        /* Background layers */
        .grid-container {
            position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
            perspective: 1200px; perspective-origin: 50% 50%;
        }
        .grid-surface {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotateX(60deg) scale(1.4);
            width: 200%; height: 200%; transform-style: preserve-3d;
        }
        .grid-squares {
            position: absolute; inset: 0;
            display: grid; grid-template-columns: repeat(12, 1fr); grid-template-rows: repeat(12, 1fr);
            animation: gridPulse 8s ease-in-out infinite;
        }
        .grid-square {
            border: 1px solid rgba(255,255,255,0.012);
            background: rgba(255,255,255,0.002);
            transition: all 0.8s ease;
        }
        .grid-square.highlight {
            border-color: rgba(204,17,17,0.04);
            background: rgba(204,17,17,0.01);
        }
        @keyframes gridPulse {
            0%, 100% { transform: scale(1) rotateX(0deg); }
            50% { transform: scale(1.01) rotateX(1deg); }
        }

        .crimson-reflection {
            position: fixed; inset: 0; z-index: 1; pointer-events: none;
            background: radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.02) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.015) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(204,17,17,0.008) 0%, transparent 70%);
        }
        .vignette-luxury {
            position: fixed; inset: 0; z-index: 2; pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.5) 100%);
        }
        .glass-sweep {
            position: fixed; inset: 0; z-index: 3; pointer-events: none;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.008) 45%, rgba(255,255,255,0.015) 50%, rgba(255,255,255,0.008) 55%, transparent 60%);
            transform: translateX(-100%);
            animation: sweepGloss 12s ease-in-out infinite;
        }
        @keyframes sweepGloss {
            0% { transform: translateX(-100%); opacity: 0; }
            6% { opacity: 1; }
            25% { transform: translateX(100%); opacity: 1; }
            30% { opacity: 0; }
            100% { transform: translateX(100%); opacity: 0; }
        }

        .cursor-dot {
            position: fixed; width: 6px; height: 6px; background: var(--accent-crimson);
            border-radius: 50%; pointer-events: none; z-index: 99999; mix-blend-mode: difference;
            transform: translate(-50%, -50%); box-shadow: 0 0 30px rgba(204,17,17,0.3);
            transition: transform 0.15s var(--ease-out-expo), width 0.3s, height 0.3s;
        }
        .cursor-dot.expanded {
            width: 40px; height: 40px; background: rgba(204,17,17,0.06);
            border: 1px solid rgba(204,17,17,0.15); mix-blend-mode: normal;
        }
        @media (pointer: coarse) { .cursor-dot { display: none; } }

        /* Navbar */
        .navbar {
            position: fixed; top: 0; left: 0; width: 100%; height: 68px;
            background: rgba(0,0,0,0.25); backdrop-filter: blur(60px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.015);
            z-index: 1000; display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem; transition: all 0.6s var(--ease-out-expo);
        }
        [data-theme="light"] .navbar {
            background: rgba(255,255,255,0.4);
            border-bottom-color: rgba(0,0,0,0.04);
        }
        .navbar.scrolled { height: 56px; background: rgba(0,0,0,0.6); }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-brand-icon {
            width: 32px; height: 32px; background: var(--gradient-crimson);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            font-size: 16px; box-shadow: 0 0 30px rgba(204,17,17,0.08);
        }
        .nav-brand-text {
            font-family: var(--font-display); font-size: 1.1rem; font-weight: 600;
            color: var(--text-primary); letter-spacing: -0.03em;
        }
        .nav-brand-text span { font-weight: 300; color: var(--text-secondary); }
        .nav-links { display: flex; align-items: center; gap: 1.8rem; }
        .nav-link {
            color: var(--text-secondary); text-decoration: none; font-size: 0.7rem;
            font-weight: 500; letter-spacing: 0.06em; transition: all 0.4s var(--ease-out-expo);
            position: relative; padding: 0.4rem 0; text-transform: uppercase;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -1px; left: 0; width: 0; height: 1px;
            background: var(--accent-crimson); transition: width 0.4s var(--ease-out-expo);
        }
        .nav-link:hover { color: var(--text-primary); }
        .nav-link:hover::after { width: 100%; }
        .nav-cta {
            background: var(--gradient-crimson); color: white; padding: 0.5rem 1.4rem;
            border-radius: 99px; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; transition: all 0.4s; border: none;
        }
        .lang-switch {
            display: flex; gap: 3px; margin-left: 0.6rem; background: rgba(255,255,255,0.01);
            padding: 3px; border-radius: 99px; border: 1px solid rgba(255,255,255,0.02);
        }
        .lang-btn {
            background: none; border: none; color: var(--text-secondary); padding: 0.25rem 0.6rem;
            border-radius: 99px; font-size: 0.55rem; font-weight: 600; cursor: pointer;
            transition: all 0.3s ease; font-family: var(--font-mono); text-transform: uppercase;
        }
        .lang-btn.active { background: var(--accent-crimson); color: white; }
        .lang-btn:hover:not(.active) { color: var(--text-primary); }

        .mobile-menu-btn { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.1rem; cursor: pointer; }

        /* Mobile Overlay */
        .mobile-nav-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85); backdrop-filter: blur(30px);
            z-index: 2000; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 2rem;
            opacity: 0; pointer-events: none; transition: opacity 0.4s ease;
        }
        .mobile-nav-overlay.active { opacity: 1; pointer-events: all; }
        .mobile-nav-overlay a, .mobile-nav-overlay button {
            background: none; border: none; color: var(--text-primary);
            font-size: 1.2rem; font-weight: 500; text-decoration: none;
            text-transform: uppercase; letter-spacing: 0.1em; transition: color 0.3s; cursor: pointer;
        }
        .mobile-nav-overlay a:hover, .mobile-nav-overlay button:hover { color: var(--accent-crimson); }
        .close-mobile-menu {
            position: absolute; top: 1.5rem; right: 1.5rem;
            font-size: 1.8rem; color: var(--text-primary); cursor: pointer;
        }

        /* Main content */
        .main-content {
            position: relative; z-index: 10; max-width: 900px; margin: 120px auto 60px;
            padding: 0 2rem;
        }
        .glass-panel {
            background: rgba(255,255,255,0.01); backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.02); border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            padding: 2.5rem;
            transition: all 0.6s;
        }
        .glass-panel:hover { border-color: rgba(255,255,255,0.03); }

        .section-label {
            font-family: var(--font-mono); font-size: 0.55rem; color: var(--accent-crimson);
            text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 1rem;
            display: inline-block; padding: 0.3rem 1rem; border: 1px solid rgba(204,17,17,0.06);
            border-radius: 99px; background: rgba(204,17,17,0.02);
        }
        .section-title {
            font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300; margin-bottom: 2rem; line-height: 1.15;
        }
        .section-title .accent { font-weight: 600; color: var(--accent-crimson); }

        .detail-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .detail-item {
            background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.02);
            border-radius: 14px; padding: 1.5rem;
        }
        .detail-label { font-size: 0.7rem; color: var(--accent-crimson); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
        .detail-value { font-size: 1rem; color: var(--text-secondary); font-family: var(--font-mono); }

        .form-group { margin-bottom: 1.5rem; }
        .form-input {
            width: 100%; padding: 0.9rem 1.2rem; background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.03); color: var(--text-primary);
            border-radius: 12px; font-size: 0.9rem; transition: all 0.4s; outline: none;
        }
        .form-input:focus {
            border-color: var(--accent-crimson); box-shadow: 0 0 30px rgba(204,17,17,0.08);
        }
        .btn-primary {
            width: 100%; background: var(--gradient-crimson); color: white; padding: 0.9rem 2rem;
            border-radius: 99px; border: none; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; transition: all 0.4s; box-shadow: 0 4px 40px rgba(204,17,17,0.06);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 50px rgba(204,17,17,0.12); }

        /* Notification toast */
        .notification {
            position: fixed; top: 20px; right: 20px; padding: 15px 25px;
            border-radius: 12px; background: rgba(6,6,6,0.95); border: 1px solid rgba(204,17,17,0.15);
            color: var(--text-primary); z-index: 10000; animation: slideIn 0.4s ease;
            backdrop-filter: blur(20px); font-size: 0.85rem; max-width: 90vw; word-wrap: break-word;
        }
        .notification.error { border-color: var(--accent-crimson); }
        .notification.success { border-color: #22c55e; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
            .navbar { padding: 0 1.5rem; }
            .main-content { padding: 0 1rem; margin-top: 100px; }
        }
    </style>
</head>
<body>
    <div class="grid-container">
        <div class="grid-surface">
            <div class="grid-squares">
                <?php for ($i = 0; $i < 144; $i++): ?>
                <div class="grid-square <?php echo (rand(0,20)===0) ? 'highlight' : ''; ?>"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <div class="crimson-reflection"></div>
    <div class="vignette-luxury"></div>
    <div class="glass-sweep"></div>
    <div class="cursor-dot" id="cursor"></div>

    <nav class="navbar" id="navbar">
        <a href="dashboard.php" class="nav-brand">
            <div class="nav-brand-icon">◈</div>
            <div class="nav-brand-text">KillByte<span>Solutions</span></div>
        </a>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="hub.php" class="nav-link">Attack Hub</a>
            <a href="pricing.php" class="nav-link">Pricing</a>
            <a href="api.php" class="nav-link">API</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
            <a href="admin/dashboard.php" class="nav-link">Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
        <div style="display:flex;align-items:center;gap:0.8rem;">
            <div class="lang-switch">
                <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="switchLanguage('en')">EN</button>
                <button class="lang-btn <?php echo $lang === 'zh' ? 'active' : ''; ?>" onclick="switchLanguage('zh')">中</button>
            </div>
            <button onclick="toggleTheme()" style="background:none;border:none;color:var(--text-secondary);font-size:0.9rem;cursor:pointer;">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay">
        <span class="close-mobile-menu" onclick="closeMobileMenu()">&times;</span>
        <a href="dashboard.php" onclick="closeMobileMenu()">Dashboard</a>
        <a href="hub.php" onclick="closeMobileMenu()">Attack Hub</a>
        <a href="pricing.php" onclick="closeMobileMenu()">Pricing</a>
        <a href="api.php" onclick="closeMobileMenu()">API</a>
        <a href="profile.php" onclick="closeMobileMenu()">Profile</a>
        <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
        <a href="admin/dashboard.php" onclick="closeMobileMenu()">Admin</a>
        <?php endif; ?>
        <a href="logout.php" onclick="closeMobileMenu()">Logout</a>
        <div class="lang-switch" style="margin-top:1rem;">
            <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="switchLanguage('en')">EN</button>
            <button class="lang-btn <?php echo $lang === 'zh' ? 'active' : ''; ?>" onclick="switchLanguage('zh')">中</button>
        </div>
        <button onclick="toggleTheme()" style="background:none;border:none;color:var(--text-secondary);font-size:1.2rem;cursor:pointer;margin-top:1rem;">
            <i class="fas fa-moon" id="themeIconMobile"></i>
        </button>
    </div>

    <main class="main-content">
        <!-- Account Details -->
        <div class="glass-panel" style="margin-bottom: 2rem;">
            <div class="section-label" data-i18n="account_label">Account</div>
            <h2 class="section-title" data-i18n="details_title">Your <span class="accent">Details</span></h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label" data-i18n="username">Username</div>
                    <div class="detail-value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" data-i18n="plan">Plan</div>
                    <div class="detail-value"><?php echo htmlspecialchars($_SESSION['plan']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" data-i18n="max_attack">Max Attack Duration</div>
                    <div class="detail-value"><?php echo htmlspecialchars($_SESSION['running']); ?>s</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" data-i18n="concurrent">Concurrent Attacks</div>
                    <div class="detail-value"><?php echo htmlspecialchars($_SESSION['concurrent']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" data-i18n="expiry">Plan Expiry</div>
                    <div class="detail-value"><?php echo htmlspecialchars($_SESSION['expiry']); ?></div>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="glass-panel">
            <div class="section-label" data-i18n="security_label">Security</div>
            <h2 class="section-title" data-i18n="password_title">Change <span class="accent">Password</span></h2>
            <form method="POST">
                <div class="form-group">
                    <input type="password" name="current_password" class="form-input" placeholder="Current Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="new_password" class="form-input" placeholder="New Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-input" placeholder="Confirm New Password" required>
                </div>
                <button type="submit" class="btn-primary" data-i18n="update_btn">Update Password</button>
            </form>
        </div>
    </main>

    <!-- Notifications -->
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="notification error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <script>
        // ===== TRANSLATIONS =====
        const translations = {
            en: {
                account_label: 'Account',
                details_title: 'Your Details',
                username: 'Username',
                plan: 'Plan',
                max_attack: 'Max Attack Duration',
                concurrent: 'Concurrent Attacks',
                expiry: 'Plan Expiry',
                security_label: 'Security',
                password_title: 'Change Password',
                current_password: 'Current Password',
                new_password: 'New Password',
                confirm_password: 'Confirm New Password',
                update_btn: 'Update Password'
            },
            zh: {
                account_label: '账户',
                details_title: '您的详情',
                username: '用户名',
                plan: '套餐',
                max_attack: '最大攻击时长',
                concurrent: '并发攻击',
                expiry: '到期时间',
                security_label: '安全',
                password_title: '修改密码',
                current_password: '当前密码',
                new_password: '新密码',
                confirm_password: '确认新密码',
                update_btn: '更新密码'
            }
        };

        function switchLanguage(lang) {
            window.location.href = '?lang=' + lang;
        }

        // Apply initial translations
        (function() {
            const lang = '<?php echo $lang; ?>';
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[lang] && translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });
        })();

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            const iconMobile = document.getElementById('themeIconMobile');
            if (html.getAttribute('data-theme') === 'dark') {
                html.setAttribute('data-theme', 'light');
                if (icon) icon.className = 'fas fa-sun';
                if (iconMobile) iconMobile.className = 'fas fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                if (icon) icon.className = 'fas fa-moon';
                if (iconMobile) iconMobile.className = 'fas fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Mobile menu
        function toggleMobileMenu() {
            document.getElementById('mobileNavOverlay').classList.toggle('active');
        }
        function closeMobileMenu() {
            document.getElementById('mobileNavOverlay').classList.remove('active');
        }

        // Lenis smooth scroll
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // Cursor
        const cursor = document.getElementById('cursor');
        let cx = 0, cy = 0, tx = 0, ty = 0;
        if (window.matchMedia('(pointer: fine)').matches) {
            document.addEventListener('mousemove', (e) => { tx = e.clientX; ty = e.clientY; });
            function animateCursor() {
                cx += (tx - cx) * 0.15;
                cy += (ty - cy) * 0.15;
                cursor.style.left = cx + 'px';
                cursor.style.top = cy + 'px';
                requestAnimationFrame(animateCursor);
            }
            animateCursor();
            document.querySelectorAll('a, button, input').forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('expanded'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('expanded'));
            });
        }

        // Auto-dismiss notifications
        document.querySelectorAll('.notification.error').forEach(n => {
            setTimeout(() => {
                n.style.transition = 'all 0.3s ease';
                n.style.opacity = '0';
                n.style.transform = 'translateX(100%)';
                setTimeout(() => n.remove(), 300);
            }, 5000);
        });

        // Init theme
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            document.getElementById('themeIcon').className = 'fas fa-sun';
            const mobileIcon = document.getElementById('themeIconMobile');
            if (mobileIcon) mobileIcon.className = 'fas fa-sun';
        }
    </script>
</body>
</html>