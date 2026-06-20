<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Set default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login');
    exit;
}

$username = $_SESSION['username'];

// Load latest user data from bus.txt (9 fields)
$userData = null;
if (file_exists('bus.txt')) {
    $lines = file('bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if ($parts[0] === $username) {
            $userData = $parts;
            break;
        }
    }
}

// Update session with fresh data
if ($userData) {
    $_SESSION['plan']       = $userData[4] ?? 'free';
    $_SESSION['concurrent'] = (int)($userData[2] ?? 0);
    $_SESSION['running']    = (int)($userData[3] ?? 0);
    $_SESSION['expiry']     = $userData[5] ?? '30-12-2030';
    $_SESSION['user_status']= $userData[6] ?? 'active';
    $_SESSION['warnings']   = (int)($userData[7] ?? 0);
    $_SESSION['warning_message'] = $userData[8] ?? '';
}

// If frozen, force logout (security)
if (($_SESSION['user_status'] ?? 'active') === 'frozen') {
    session_destroy();
    header('Location: login?error=frozen');
    exit;
}

// Get total attacks from ln.txt
$total_attacks = 0;
if (file_exists('ln.txt')) {
    $total_attacks = (int)file_get_contents('ln.txt');
}

// Count active attacks for this user
$active_attacks = 0;
if (file_exists('ng.txt')) {
    $lines = file('ng.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $now = time();
    foreach ($lines as $line) {
        $attack = json_decode($line, true);
        if ($attack['username'] === $username && $now <= $attack['end_time']) {
            $active_attacks++;
        }
    }
}

// Get total users count (for stats)
$total_users = 0;
if (file_exists('bus.txt')) {
    $total_users = count(file('bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}

// Load announcements (from an.txt)
$announcements = [];
if (file_exists('an.txt')) {
    $raw = file('an.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($raw as $line) {
        $parts = explode('|', $line, 2);
        if (count($parts) === 2) {
            $announcements[] = ['title' => trim($parts[0]), 'message' => trim($parts[1])];
        }
    }
}
// Show newest first
$announcements = array_reverse($announcements);

// Translation strings
$lang = $_SESSION['lang'];
$t = [
    'en' => [
        'app_name' => 'KillByte Solutions',
        'dashboard' => 'Dashboard',
        'attack_hub' => 'Attack Hub',
        'pricing' => 'Pricing',
        'profile' => 'Profile',
        'admin' => 'Admin',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'plan' => 'Plan',
        'restricted' => 'Restricted – attack abilities disabled',
        'max_concurrent' => 'Max Concurrent',
        'max_duration' => 'Max Duration',
        'active_attacks' => 'Active Attacks',
        'total_launched' => 'Total Launched',
        'warnings_received' => 'Warnings Received',
        'plan_expiry' => 'Plan Expiry',
        'account_details' => 'Account Details',
        'username' => 'Username',
        'status' => 'Status',
        'warnings' => 'Warnings',
        'expiry' => 'Expiry',
        'concurrent_limit' => 'Concurrent Limit',
        'launch_attack' => 'Launch Attack',
        'profile_link' => 'Profile',
        'admin_panel' => 'Admin Panel',
        'announcements' => 'Announcements',
        'no_announcements' => 'No Announcements',
        'check_back' => 'Check back later for updates.',
        'warning_message' => '⚠️',
    ],
    'zh' => [
        'app_name' => 'KillByte 解决方案',
        'dashboard' => '仪表盘',
        'attack_hub' => '攻击中心',
        'pricing' => '定价',
        'profile' => '个人资料',
        'admin' => '管理',
        'logout' => '登出',
        'welcome' => '欢迎',
        'plan' => '套餐',
        'restricted' => '受限 – 攻击功能已禁用',
        'max_concurrent' => '最大并发数',
        'max_duration' => '最大持续时间',
        'active_attacks' => '进行中的攻击',
        'total_launched' => '总发起攻击数',
        'warnings_received' => '收到警告数',
        'plan_expiry' => '套餐到期日',
        'account_details' => '账户详情',
        'username' => '用户名',
        'status' => '状态',
        'warnings' => '警告数',
        'expiry' => '到期日',
        'concurrent_limit' => '并发限制',
        'launch_attack' => '发起攻击',
        'profile_link' => '个人资料',
        'admin_panel' => '管理面板',
        'announcements' => '公告',
        'no_announcements' => '暂无公告',
        'check_back' => '稍后回来查看更新。',
        'warning_message' => '⚠️',
    ]
];
$tr = $t[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo $tr['app_name']; ?> – <?php echo $tr['dashboard']; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ============================================================
           LUXURY KILLBYTE DESIGN – FULLY FIXED & RESPONSIVE
           ============================================================ */
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

        /* ---- Background layers ---- */
        .grid-container {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            overflow: hidden; perspective: 1200px; perspective-origin: 50% 50%;
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
            background:
                radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.02) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.015) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(204,17,17,0.008) 0%, transparent 70%);
        }
        .vignette-luxury {
            position: fixed; inset: 0; z-index: 2; pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.5) 100%);
        }
        .glass-sweep {
            position: fixed; inset: 0; z-index: 3; pointer-events: none;
            background: linear-gradient(105deg,
                transparent 40%,
                rgba(255,255,255,0.008) 45%,
                rgba(255,255,255,0.015) 50%,
                rgba(255,255,255,0.008) 55%,
                transparent 60%
            );
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

        /* ---- Custom cursor ---- */
        .cursor-dot {
            position: fixed; width: 6px; height: 6px;
            background: var(--accent-crimson);
            border-radius: 50%; pointer-events: none; z-index: 99999;
            mix-blend-mode: difference;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 30px rgba(204,17,17,0.3);
            transition: transform 0.15s var(--ease-out-expo), width 0.3s, height 0.3s;
        }
        .cursor-dot.expanded {
            width: 40px; height: 40px;
            background: rgba(204,17,17,0.06);
            border: 1px solid rgba(204,17,17,0.15);
            mix-blend-mode: normal;
        }
        @media (pointer: coarse) { .cursor-dot { display: none; } }

        /* ---- Navbar ---- */
        .navbar {
            position: fixed; top: 0; left: 0; width: 100%; height: 68px;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(60px) saturate(180%);
            -webkit-backdrop-filter: blur(60px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.015);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            transition: all 0.6s var(--ease-out-expo);
        }
        .navbar.scrolled { height: 56px; background: rgba(0,0,0,0.6); box-shadow: 0 20px 60px rgba(0,0,0,0.2); }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            height: 100%;
        }
        .nav-brand-icon {
            width: 32px; height: 32px;
            background: var(--gradient-crimson);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 0 30px rgba(204,17,17,0.08);
        }
        .nav-brand-text {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.03em;
        }
        .nav-brand-text span { font-weight: 300; color: var(--text-secondary); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.8rem;
            height: 100%;
        }
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: all 0.4s var(--ease-out-expo);
            position: relative;
            padding: 0.4rem 0;
            display: inline-flex;
            align-items: center;
            height: 100%;
            line-height: normal;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent-crimson);
            transition: width 0.4s var(--ease-out-expo);
            box-shadow: 0 0 20px rgba(204,17,17,0.3);
        }
        .nav-link:hover { color: var(--text-primary); }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active { color: var(--text-primary); }
        .nav-link.active::after { width: 100%; }

        .nav-cta {
            background: var(--gradient-crimson);
            color: white;
            padding: 0.5rem 1.4rem;
            border-radius: 99px;
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: all 0.4s var(--ease-out-expo);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 30px rgba(204,17,17,0.06);
            display: inline-flex;
            align-items: center;
        }
        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(204,17,17,0.12);
        }

        /* ---- Language switcher ---- */
        .lang-switch {
            display: flex;
            gap: 3px;
            background: rgba(255,255,255,0.01);
            padding: 3px;
            border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.02);
            align-items: center;
        }
        .lang-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.25rem 0.6rem;
            border-radius: 99px;
            font-size: 0.55rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .lang-btn.active {
            background: var(--accent-crimson);
            color: white;
            box-shadow: 0 0 20px rgba(204,17,17,0.1);
        }
        .lang-btn:hover:not(.active) { color: var(--text-primary); }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: 0.6rem;
            display: inline-flex;
            align-items: center;
        }
        .theme-toggle:hover { color: var(--text-primary); }

        /* ---- Mobile menu ---- */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }
        .hamburger span {
            display: block;
            height: 3px;
            width: 100%;
            background: var(--text-primary);
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        .hamburger.active span:nth-child(1) { transform: translateY(9px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: translateY(-9px) rotate(-45deg); }

        .nav-mobile {
            display: none;
            position: fixed;
            top: 68px;
            left: 0;
            width: 100%;
            background: rgba(0,0,0,0.95);
            backdrop-filter: blur(20px);
            flex-direction: column;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            z-index: 999;
        }
        .nav-mobile.active { display: flex; }
        .nav-mobile a {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .nav-mobile a:hover { color: var(--text-primary); }
        .nav-mobile a:last-child { border-bottom: none; }

        @media (max-width: 768px) {
            .navbar { padding: 0 1.2rem; }
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        /* ---- Main content ---- */
        .main-content {
            position: relative;
            z-index: 10;
            padding: 120px 2rem 80px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ---- Glass panel ---- */
        .glass-panel {
            background: var(--bg-glass);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            transition: all 0.6s var(--ease-out-expo);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .glass-panel:hover {
            border-color: var(--border-crimson);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }
        .glass-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: var(--gradient-crimson);
            opacity: 0.2;
        }

        .panel-title {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .panel-title i { color: var(--accent-crimson); font-size: 1.2rem; }

        /* ---- Warning banner ---- */
        .warning-banner {
            background: rgba(204,17,17,0.08);
            border: 1px solid rgba(204,17,17,0.15);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(20px);
            animation: slideDown 0.5s var(--ease-out-expo);
        }
        .warning-banner i {
            color: var(--accent-crimson);
            font-size: 1.4rem;
        }
        .warning-banner .message {
            flex: 1;
            color: var(--text-primary);
            font-size: 0.95rem;
        }
        .warning-banner .close-btn {
            background: none; border: none;
            color: var(--text-tertiary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.3s;
        }
        .warning-banner .close-btn:hover { color: var(--accent-crimson); }
        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* ---- Stats grid ---- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.4s var(--ease-out-expo);
        }
        .stat-card:hover {
            border-color: var(--border-crimson);
            transform: translateY(-4px);
            background: var(--bg-glass-hover);
        }
        .stat-card .number {
            font-family: var(--font-mono);
            font-size: 2rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        .stat-card .label {
            font-size: 0.65rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.2rem;
        }

        /* ---- User info grid ---- */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .info-item {
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
        }
        .info-item .label {
            font-size: 0.6rem;
            text-transform: uppercase;
            color: var(--text-tertiary);
            letter-spacing: 0.08em;
        }
        .info-item .value {
            font-family: var(--font-mono);
            font-size: 1rem;
            color: var(--text-primary);
            margin-top: 0.2rem;
        }

        /* ---- Announcements ---- */
        .announcements-container {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        .announcements-container::-webkit-scrollbar { width: 4px; }
        .announcements-container::-webkit-scrollbar-thumb { background: var(--accent-crimson); border-radius: 4px; }
        .announcement-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-bottom: 0.8rem;
        }
        .announcement-item:last-child { margin-bottom: 0; }
        .announcement-item .title {
            color: var(--accent-crimson);
            font-weight: 600;
            font-size: 0.9rem;
        }
        .announcement-item .message {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }

        /* ---- Quick actions ---- */
        .action-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .action-btn {
            background: var(--gradient-crimson);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.4s var(--ease-out-expo);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 4px 30px rgba(204,17,17,0.06);
        }
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(204,17,17,0.12);
        }
        .action-btn-secondary {
            background: rgba(255,255,255,0.01);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(20px);
        }
        .action-btn-secondary:hover {
            border-color: var(--border-crimson);
            background: var(--accent-crimson-dim);
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            .main-content { padding: 100px 1rem 60px; }
            .glass-panel { padding: 1.5rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr; }
            .warning-banner { flex-wrap: wrap; }
            .action-grid { justify-content: center; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Background layers -->
    <div class="grid-container">
        <div class="grid-surface">
            <div class="grid-squares">
                <?php for ($i = 0; $i < 144; $i++): ?>
                <div class="grid-square <?php echo (rand(0, 20) === 0) ? 'highlight' : ''; ?>"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <div class="crimson-reflection"></div>
    <div class="vignette-luxury"></div>
    <div class="glass-sweep"></div>
    <div class="cursor-dot" id="cursor"></div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-brand">
            <div class="nav-brand-icon">◈</div>
            <div class="nav-brand-text">KillByte<span>Solutions</span></div>
        </a>
        <div class="nav-links">
            <a href="dashboard" class="nav-link active"><?php echo $tr['dashboard']; ?></a>
            <a href="hub" class="nav-link"><?php echo $tr['attack_hub']; ?></a>
            <a href="pricing" class="nav-link"><?php echo $tr['pricing']; ?></a>
            <a href="profile" class="nav-link"><?php echo $tr['profile']; ?></a>
            <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
            <a href="admin/dashboard" class="nav-link"><?php echo $tr['admin']; ?></a>
            <?php endif; ?>
            <a href="logout" class="nav-link"><?php echo $tr['logout']; ?></a>
        </div>
        <div style="display:flex;align-items:center;gap:0.8rem;">
            <!-- Language Switcher -->
            <div class="lang-switch">
                <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="switchLanguage('en')">EN</button>
                <button class="lang-btn <?php echo $lang === 'zh' ? 'active' : ''; ?>" onclick="switchLanguage('zh')">中</button>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
    <div class="nav-mobile" id="navMobile">
        <a href="dashboard"><?php echo $tr['dashboard']; ?></a>
        <a href="hub"><?php echo $tr['attack_hub']; ?></a>
        <a href="pricing"><?php echo $tr['pricing']; ?></a>
        <a href="profile"><?php echo $tr['profile']; ?></a>
        <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
        <a href="admin/dashboard"><?php echo $tr['admin']; ?></a>
        <?php endif; ?>
        <a href="logout"><?php echo $tr['logout']; ?></a>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- ===== WARNING BANNER ===== -->
        <?php if (!empty($_SESSION['warning_message'])): ?>
        <div class="warning-banner" id="warningBanner">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="message"><?php echo $tr['warning_message']; ?> <?php echo htmlspecialchars($_SESSION['warning_message']); ?></span>
            <button class="close-btn" onclick="document.getElementById('warningBanner').style.display='none';">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endif; ?>

        <!-- Welcome -->
        <div class="glass-panel">
            <div class="panel-title">
                <i class="fas fa-user-astronaut"></i>
                <?php echo $tr['welcome']; ?>, <span style="color:var(--accent-crimson);"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <p style="color:var(--text-secondary);">
                <?php echo $tr['plan']; ?>: <strong style="color:var(--text-primary);"><?php echo htmlspecialchars($_SESSION['plan']); ?></strong>
                <?php if (($_SESSION['user_status'] ?? 'active') === 'restricted'): ?>
                <span style="color:#f59e0b;font-size:0.8rem;margin-left:1rem;">
                    <i class="fas fa-ban"></i> <?php echo $tr['restricted']; ?>
                </span>
                <?php endif; ?>
            </p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?php echo htmlspecialchars($_SESSION['concurrent'] ?? 0); ?></div>
                <div class="label"><?php echo $tr['max_concurrent']; ?></div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo htmlspecialchars($_SESSION['running'] ?? 0); ?>s</div>
                <div class="label"><?php echo $tr['max_duration']; ?></div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $active_attacks; ?></div>
                <div class="label"><?php echo $tr['active_attacks']; ?></div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $total_attacks; ?></div>
                <div class="label"><?php echo $tr['total_launched']; ?></div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $_SESSION['warnings'] ?? 0; ?></div>
                <div class="label"><?php echo $tr['warnings_received']; ?></div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo htmlspecialchars($_SESSION['expiry'] ?? 'N/A'); ?></div>
                <div class="label"><?php echo $tr['plan_expiry']; ?></div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="glass-panel">
            <div class="panel-title"><i class="fas fa-id-card"></i> <?php echo $tr['account_details']; ?></div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label"><?php echo $tr['username']; ?></div>
                    <div class="value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                </div>
                <div class="info-item">
                    <div class="label"><?php echo $tr['plan']; ?></div>
                    <div class="value"><?php echo htmlspecialchars($_SESSION['plan']); ?></div>
                </div>
                <div class="info-item">
                    <div class="label"><?php echo $tr['status']; ?></div>
                    <div class="value">
                        <?php
                        $status = $_SESSION['user_status'] ?? 'active';
                        $color = $status === 'active' ? '#22c55e' : ($status === 'frozen' ? '#f59e0b' : '#cc1111');
                        ?>
                        <span style="color:<?php echo $color; ?>;text-transform:uppercase;font-weight:600;">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label"><?php echo $tr['warnings']; ?></div>
                    <div class="value"><?php echo (int)($_SESSION['warnings'] ?? 0); ?></div>
                </div>
                <div class="info-item">
                    <div class="label"><?php echo $tr['expiry']; ?></div>
                    <div class="value"><?php echo htmlspecialchars($_SESSION['expiry'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="label"><?php echo $tr['concurrent_limit']; ?></div>
                    <div class="value"><?php echo htmlspecialchars($_SESSION['concurrent'] ?? 0); ?></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="action-grid">
                <a href="hub" class="action-btn"><i class="fas fa-rocket"></i> <?php echo $tr['launch_attack']; ?></a>
                <a href="profile" class="action-btn action-btn-secondary"><i class="fas fa-user-cog"></i> <?php echo $tr['profile_link']; ?></a>
                <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
                <a href="admin/dashboard" class="action-btn action-btn-secondary"><i class="fas fa-shield-alt"></i> <?php echo $tr['admin_panel']; ?></a>
                <?php endif; ?>
                <a href="logout" class="action-btn action-btn-secondary" style="border-color:#cc1111;color:#cc1111;">
                    <i class="fas fa-sign-out-alt"></i> <?php echo $tr['logout']; ?>
                </a>
            </div>
        </div>

        <!-- Announcements -->
        <div class="glass-panel">
            <div class="panel-title"><i class="fas fa-bullhorn"></i> <?php echo $tr['announcements']; ?></div>
            <div class="announcements-container">
                <?php if (empty($announcements)): ?>
                    <div class="announcement-item">
                        <div class="title"><?php echo $tr['no_announcements']; ?></div>
                        <div class="message"><?php echo $tr['check_back']; ?></div>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $ann): ?>
                    <div class="announcement-item">
                        <div class="title"><?php echo htmlspecialchars($ann['title']); ?></div>
                        <div class="message"><?php echo htmlspecialchars($ann['message']); ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- ===== SCRIPTS ===== -->
    <script>
        // Lenis smooth scroll
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // Custom cursor
        const cursor = document.getElementById('cursor');
        let cursorX = 0, cursorY = 0, targetX = 0, targetY = 0;
        if (window.matchMedia('(pointer: fine)').matches) {
            document.addEventListener('mousemove', (e) => { targetX = e.clientX; targetY = e.clientY; });
            function animateCursor() {
                cursorX += (targetX - cursorX) * 0.15;
                cursorY += (targetY - cursorY) * 0.15;
                cursor.style.left = cursorX + 'px';
                cursor.style.top = cursorY + 'px';
                requestAnimationFrame(animateCursor);
            }
            animateCursor();
            document.querySelectorAll('a, button, .stat-card, .glass-panel').forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('expanded'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('expanded'));
            });
        }

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            if (html.getAttribute('data-theme') === 'dark') {
                html.setAttribute('data-theme', 'light');
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        }
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            document.getElementById('themeIcon').className = 'fas fa-sun';
        }

        // Language switch
        function switchLanguage(lang) {
            fetch('set_lang.php?lang=' + lang)
                .then(() => {
                    window.location.reload();
                })
                .catch(() => {
                    window.location.reload();
                });
        }

        // Mobile menu toggle
        const hamburger = document.getElementById('hamburger');
        const navMobile = document.getElementById('navMobile');
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navMobile.classList.toggle('active');
        });
        // Close menu when a link is clicked
        document.querySelectorAll('.nav-mobile a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMobile.classList.remove('active');
            });
        });
    </script>
</body>
</html>