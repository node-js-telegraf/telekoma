<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Restrict access
if (!isset($_SESSION['username']) || !in_array($_SESSION['plan'], ['admin', 'owner'])) {
    header('Location: ../login');
    exit;
}

// Settings file
$settingsFile = __DIR__ . '/settings.json';

// Default settings
$defaults = [
    'site_title' => 'KillByte Solutions',
    'meta_description' => 'Enterprise-grade L7 & L4 stress testing platform. 550M+ req/s, 1.2TB L4 performance.',
    'meta_keywords' => 'stress testing, layer 7, layer 4, ddos, bypass, killbyte',
    'blacklist' => ['gov', 'edu', '.gov', '.edu', 'l7syria', '127.0.0.1'],
    'default_concurrent' => 1,
    'default_duration' => 60,
    'maintenance' => false,
    'api_base' => 'http://0.0.0.0:5000/api'
];

// Load settings
$settings = $defaults;
if (file_exists($settingsFile)) {
    $data = json_decode(file_get_contents($settingsFile), true);
    if (is_array($data)) {
        $settings = array_merge($defaults, $data);
    }
}

// Handle GET reset (before any output)
if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    $settings = $defaults;
    file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
    $_SESSION['admin_success'] = "Settings reset to defaults.";
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'save_general':
            $settings['site_title'] = trim($_POST['site_title'] ?? 'KillByte Solutions');
            $settings['meta_description'] = trim($_POST['meta_description'] ?? '');
            $settings['meta_keywords'] = trim($_POST['meta_keywords'] ?? '');
            $settings['default_concurrent'] = (int)($_POST['default_concurrent'] ?? 1);
            $settings['default_duration'] = (int)($_POST['default_duration'] ?? 60);
            $settings['maintenance'] = isset($_POST['maintenance']);
            $settings['api_base'] = trim($_POST['api_base'] ?? '');
            $_SESSION['admin_success'] = "General settings saved.";
            break;

        case 'add_blacklist':
            $newItem = trim($_POST['blacklist_item'] ?? '');
            if (!empty($newItem) && !in_array($newItem, $settings['blacklist'])) {
                $settings['blacklist'][] = $newItem;
                $_SESSION['admin_success'] = "Blacklist item added.";
            } else {
                $_SESSION['admin_error'] = "Item already exists or empty.";
            }
            break;

        case 'remove_blacklist':
            $index = (int)($_POST['index'] ?? -1);
            if ($index >= 0 && isset($settings['blacklist'][$index])) {
                unset($settings['blacklist'][$index]);
                $settings['blacklist'] = array_values($settings['blacklist']);
                $_SESSION['admin_success'] = "Blacklist item removed.";
            } else {
                $_SESSION['admin_error'] = "Invalid item.";
            }
            break;

        case 'reset_defaults':
            $settings = $defaults;
            $_SESSION['admin_success'] = "Settings reset to defaults.";
            break;
    }

    // Save to file
    file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Language
$lang = $_SESSION['lang'] ?? 'en';
$tr = [
    'en' => [
        'app_name' => 'KillByte Admin',
        'settings' => 'Settings',
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'attacks' => 'Attack Logs',
        'methods' => 'Methods',
        'settings_title' => 'System Settings',
        'general' => 'General',
        'site_title_label' => 'Site Title (SEO)',
        'meta_desc_label' => 'Meta Description (SEO)',
        'meta_keywords_label' => 'Meta Keywords (SEO)',
        'default_concurrent_label' => 'Default Concurrent Limit',
        'default_duration_label' => 'Default Duration (seconds)',
        'maintenance_label' => 'Maintenance Mode',
        'api_base_label' => 'API Base URL',
        'blacklist' => 'Blacklist',
        'blacklist_desc' => 'Blocked domains/words (one per line)',
        'add_blacklist_label' => 'Add to Blacklist',
        'add_btn' => 'Add',
        'remove' => 'Remove',
        'reset_defaults' => 'Reset to Defaults',
        'save_btn' => 'Save Settings',
        'back_to_panel' => 'Back to Panel',
        'no_items' => 'No items in blacklist.',
        'confirm_reset' => 'Are you sure you want to reset all settings to defaults?'
    ],
    'zh' => [
        'app_name' => 'KillByte 管理',
        'settings' => '设置',
        'dashboard' => '仪表盘',
        'users' => '用户',
        'attacks' => '攻击日志',
        'methods' => '方法',
        'settings_title' => '系统设置',
        'general' => '常规',
        'site_title_label' => '网站标题 (SEO)',
        'meta_desc_label' => 'Meta 描述 (SEO)',
        'meta_keywords_label' => 'Meta 关键词 (SEO)',
        'default_concurrent_label' => '默认并发限制',
        'default_duration_label' => '默认持续时间 (秒)',
        'maintenance_label' => '维护模式',
        'api_base_label' => 'API 基础 URL',
        'blacklist' => '黑名单',
        'blacklist_desc' => '阻止的域名/词语 (每行一个)',
        'add_blacklist_label' => '添加到黑名单',
        'add_btn' => '添加',
        'remove' => '移除',
        'reset_defaults' => '重置为默认',
        'save_btn' => '保存设置',
        'back_to_panel' => '返回面板',
        'no_items' => '黑名单为空。',
        'confirm_reset' => '确定要重置所有设置为默认值吗？'
    ]
];
$t = $tr[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo $t['app_name']; ?> – <?php echo $t['settings']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ===== RESET & VARIABLES ===== */
        * { margin:0; padding:0; box-sizing:border-box; }
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
            --accent-crimson-dim: rgba(204,17,17,0.04);
            --border-subtle: rgba(255,255,255,0.02);
            --border-crimson: rgba(204,17,17,0.08);
            --gradient-crimson: linear-gradient(135deg, #cc1111 0%, #aa0e0e 50%, #880a0a 100%);
            --font-display: 'Space Grotesk', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
            --sidebar-width: 220px;
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
        html, body { height:100%; font-family:'Inter',sans-serif; background:var(--bg-void); color:var(--text-primary); overflow-x:hidden; }
        body { overflow-y: auto; }  /* FIX: mobile scroll */
        ::-webkit-scrollbar { width:2px; }
        ::-webkit-scrollbar-thumb { background:rgba(204,17,17,0.15); border-radius:1px; }

        /* Background layers */
        .grid-container { position:fixed; inset:0; z-index:0; pointer-events:none; perspective:1200px; }
        .grid-surface { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotateX(60deg) scale(1.4); width:200%; height:200%; transform-style:preserve-3d; }
        .grid-squares { position:absolute; inset:0; display:grid; grid-template-columns:repeat(12,1fr); grid-template-rows:repeat(12,1fr); width:100%; height:100%; animation:gridPulse 8s ease-in-out infinite; }
        .grid-square { border:1px solid rgba(255,255,255,0.012); background:rgba(255,255,255,0.002); transition:all 0.8s ease; position:relative; }
        .grid-square::after { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at center, rgba(204,17,17,0.02), transparent 70%); opacity:0; transition:opacity 0.8s ease; }
        .grid-square:hover::after { opacity:1; }
        @keyframes gridPulse { 0%,100%{transform:scale(1) rotateX(0deg);} 50%{transform:scale(1.01) rotateX(1deg);} }
        .crimson-reflection { position:fixed; inset:0; z-index:1; pointer-events:none; background:radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.02) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.015) 0%, transparent 50%), radial-gradient(ellipse at 50% 50%, rgba(204,17,17,0.008) 0%, transparent 70%); }
        .vignette { position:fixed; inset:0; z-index:2; pointer-events:none; background:radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.5) 100%); }
        .glass-sweep { position:fixed; inset:0; z-index:3; pointer-events:none; background:linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.008) 45%, rgba(255,255,255,0.015) 50%, rgba(255,255,255,0.008) 55%, transparent 60%); transform:translateX(-100%); animation:sweepGloss 12s ease-in-out infinite; }
        @keyframes sweepGloss { 0%{transform:translateX(-100%);opacity:0;} 6%{opacity:1;} 25%{transform:translateX(100%);opacity:1;} 30%{opacity:0;} 100%{transform:translateX(100%);opacity:0;} }

        .app { display:flex; min-height:100vh; position:relative; z-index:10; }
        .sidebar { position:fixed; top:0; left:0; width:var(--sidebar-width); height:100vh; background:rgba(0,0,0,0.4); backdrop-filter:blur(60px) saturate(180%); border-right:1px solid var(--border-subtle); padding:2rem 0; display:flex; flex-direction:column; z-index:100; transition:transform 0.4s var(--ease-out-expo); }
        .sidebar .brand { font-family:var(--font-display); font-size:1.2rem; font-weight:600; padding:0 1.5rem 2rem; letter-spacing:-0.02em; color:var(--text-primary); border-bottom:1px solid var(--border-subtle); margin-bottom:1.5rem; }
        .sidebar .brand i { color:var(--accent-crimson); margin-right:0.5rem; }
        .sidebar .nav-item { display:flex; align-items:center; gap:0.8rem; padding:0.7rem 1.5rem; color:var(--text-secondary); text-decoration:none; font-size:0.8rem; font-weight:500; transition:all 0.3s var(--ease-out-expo); border-left:2px solid transparent; }
        .sidebar .nav-item:hover, .sidebar .nav-item.active { color:var(--text-primary); background:var(--bg-glass-hover); border-left-color:var(--accent-crimson); }
        .sidebar .nav-item i { width:20px; font-size:0.9rem; }
        .sidebar .nav-item.active { color:var(--accent-crimson); }
        .sidebar .bottom { margin-top:auto; padding:1.5rem; border-top:1px solid var(--border-subtle); }
        .sidebar .bottom a { color:var(--text-tertiary); text-decoration:none; font-size:0.7rem; display:flex; align-items:center; gap:0.6rem; transition:color 0.3s; }
        .sidebar .bottom a:hover { color:var(--accent-crimson); }

        .main-content { margin-left:var(--sidebar-width); flex:1; padding:2rem; width:calc(100% - var(--sidebar-width)); }
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-left:0; width:100%; padding:1rem; }
            .hamburger { display:flex; }
        }
        .hamburger { display:none; flex-direction:column; gap:4px; cursor:pointer; background:none; border:none; padding:0.5rem; }
        .hamburger span { width:24px; height:2px; background:var(--text-primary); border-radius:2px; }
        .mobile-header { display:none; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        @media (max-width:768px) { .hamburger, .mobile-header { display:flex; } }

        .glass-panel { background:var(--bg-glass); backdrop-filter:blur(40px); border:1px solid var(--border-subtle); border-radius:16px; padding:1.5rem; margin-bottom:2rem; }
        .panel-title { font-family:var(--font-display); font-size:1.1rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.6rem; }
        .panel-title i { color:var(--accent-crimson); }

        .form-group { margin-bottom:1.2rem; }
        .form-group label { display:block; font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); letter-spacing:0.08em; margin-bottom:0.3rem; }
        .form-group input, .form-group textarea { width:100%; background:rgba(255,255,255,0.005); border:1px solid var(--border-subtle); border-radius:8px; padding:0.6rem 1rem; color:var(--text-primary); font-family:'Inter',sans-serif; }
        .form-group textarea { min-height:80px; resize:vertical; }
        .form-group input:focus, .form-group textarea:focus { border-color:var(--accent-crimson); outline:none; box-shadow:0 0 30px rgba(204,17,17,0.02); }
        .form-group .checkbox { display:flex; align-items:center; gap:0.5rem; }
        .form-group .checkbox input { width:auto; }

        .btn { padding:0.4rem 0.8rem; border-radius:99px; border:1px solid var(--border-subtle); background:transparent; color:var(--text-secondary); font-size:0.7rem; cursor:pointer; transition:all 0.3s var(--ease-out-expo); }
        .btn:hover { border-color:var(--accent-crimson); color:var(--text-primary); }
        .btn-primary { background:var(--gradient-crimson); color:white; border:none; padding:0.6rem 2rem; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 40px rgba(204,17,17,0.12); }
        .btn-danger { border-color:#cc1111; color:#cc1111; }
        .btn-danger:hover { background:#cc1111; color:white; }
        .btn-warning { border-color:#f59e0b; color:#f59e0b; }
        .btn-warning:hover { background:#f59e0b; color:white; }

        .blacklist-item { display:flex; justify-content:space-between; align-items:center; padding:0.4rem 0.8rem; background:rgba(255,255,255,0.02); border-radius:8px; margin-bottom:0.4rem; border:1px solid var(--border-subtle); }
        .blacklist-item .text { font-family:var(--font-mono); font-size:0.8rem; color:var(--text-secondary); }
        .blacklist-item .remove-btn { background:none; border:none; color:var(--text-tertiary); cursor:pointer; transition:color 0.3s; }
        .blacklist-item .remove-btn:hover { color:var(--accent-crimson); }

        .notification { position:fixed; top:80px; right:20px; padding:0.8rem 1.5rem; background:var(--bg-elevated); border:1px solid var(--border-crimson); border-radius:12px; backdrop-filter:blur(40px); color:var(--text-primary); z-index:9999; animation:slideIn 0.3s var(--ease-out-expo); box-shadow:0 20px 60px rgba(0,0,0,0.2); max-width:90vw; word-wrap:break-word; }
        .notification.success { border-color:rgba(34,197,94,0.2); }
        .notification.error { border-color:rgba(204,17,17,0.2); }
        @keyframes slideIn { 0%{transform:translateX(100%);opacity:0;} 100%{transform:translateX(0);opacity:1;} }

        /* Additional mobile adjustments */
        @media (max-width:768px) {
            .btn { font-size:0.65rem; padding:0.3rem 0.6rem; }
            .btn-primary { padding:0.5rem 1.5rem; }
            .glass-panel { padding:1rem; }
        }
    </style>
</head>
<body>
    <div class="grid-container"><div class="grid-surface"><div class="grid-squares">
        <?php for ($i=0;$i<144;$i++): ?><div class="grid-square"></div><?php endfor; ?>
    </div></div></div>
    <div class="crimson-reflection"></div><div class="vignette"></div><div class="glass-sweep"></div>

    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="brand"><i class="fas fa-skull"></i> KillByte<br><span style="font-size:0.7rem;color:var(--text-tertiary);">Admin Panel</span></div>
            <a href="dashboard" class="nav-item"><i class="fas fa-gauge-high"></i> <?php echo $t['dashboard']; ?></a>
            <a href="users" class="nav-item"><i class="fas fa-users"></i> <?php echo $t['users']; ?></a>
            <a href="attacks" class="nav-item"><i class="fas fa-bolt"></i> <?php echo $t['attacks']; ?></a>
            <a href="methods" class="nav-item"><i class="fas fa-code"></i> <?php echo $t['methods']; ?></a>
            <a href="settings" class="nav-item active"><i class="fas fa-gear"></i> <?php echo $t['settings']; ?></a>
            <div class="bottom"><a href="../dashboard"><i class="fas fa-arrow-left"></i> <?php echo $t['back_to_panel']; ?></a></div>
        </aside>

        <div class="mobile-header">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <span></span><span></span><span></span>
            </button>
            <span style="font-weight:600;"><?php echo $t['settings']; ?></span>
        </div>

        <main class="main-content">
            <!-- General Settings -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-sliders-h"></i> <?php echo $t['general']; ?></div>
                <form method="POST">
                    <input type="hidden" name="action" value="save_general">
                    <div class="form-group">
                        <label><?php echo $t['site_title_label']; ?></label>
                        <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $t['meta_desc_label']; ?></label>
                        <textarea name="meta_description" rows="2"><?php echo htmlspecialchars($settings['meta_description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo $t['meta_keywords_label']; ?></label>
                        <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($settings['meta_keywords']); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $t['default_concurrent_label']; ?></label>
                        <input type="number" name="default_concurrent" value="<?php echo $settings['default_concurrent']; ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label><?php echo $t['default_duration_label']; ?></label>
                        <input type="number" name="default_duration" value="<?php echo $settings['default_duration']; ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label><?php echo $t['api_base_label']; ?></label>
                        <input type="text" name="api_base" value="<?php echo htmlspecialchars($settings['api_base']); ?>">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" id="maintenance" name="maintenance" <?php echo $settings['maintenance'] ? 'checked' : ''; ?>>
                            <label for="maintenance" style="font-size:0.8rem;color:var(--text-secondary);"><?php echo $t['maintenance_label']; ?></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $t['save_btn']; ?></button>
                    <a href="?reset=1" class="btn btn-warning" style="margin-left:0.5rem;" onclick="return confirm('<?php echo $t['confirm_reset']; ?>')"><i class="fas fa-undo"></i> <?php echo $t['reset_defaults']; ?></a>
                </form>
            </div>

            <!-- Blacklist Management -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-ban"></i> <?php echo $t['blacklist']; ?></div>
                <p style="color:var(--text-secondary);font-size:0.8rem;margin-bottom:1rem;"><?php echo $t['blacklist_desc']; ?></p>
                <div style="margin-bottom:1rem;">
                    <form method="POST" style="display:flex;gap:0.5rem;">
                        <input type="hidden" name="action" value="add_blacklist">
                        <input type="text" name="blacklist_item" placeholder="e.g. example.com" style="flex:1;background:rgba(255,255,255,0.005);border:1px solid var(--border-subtle);border-radius:8px;padding:0.6rem 1rem;color:var(--text-primary);">
                        <button type="submit" class="btn btn-primary"><?php echo $t['add_btn']; ?></button>
                    </form>
                </div>
                <div>
                    <?php if (empty($settings['blacklist'])): ?>
                        <p style="color:var(--text-tertiary);font-size:0.85rem;"><?php echo $t['no_items']; ?></p>
                    <?php else: ?>
                        <?php foreach ($settings['blacklist'] as $index => $item): ?>
                        <div class="blacklist-item">
                            <span class="text"><?php echo htmlspecialchars($item); ?></span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="remove_blacklist">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" class="remove-btn" title="<?php echo $t['remove']; ?>"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <?php if (isset($_SESSION['admin_success'])): ?>
    <div class="notification success"><?php echo $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['admin_error'])): ?>
    <div class="notification error"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
    <?php endif; ?>

    <script>
        // Lenis smooth scroll
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // Sidebar toggle on mobile
        document.querySelectorAll('.sidebar .nav-item').forEach(link => {
            link.addEventListener('click', () => document.getElementById('sidebar').classList.remove('open'));
        });

        // Auto-hide notifications
        document.querySelectorAll('.notification').forEach(n => {
            setTimeout(() => {
                n.style.opacity = '0';
                n.style.transform = 'translateX(40px)';
                setTimeout(() => n.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>