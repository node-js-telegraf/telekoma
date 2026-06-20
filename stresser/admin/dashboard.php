<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Restrict access
if (!isset($_SESSION['username']) || !in_array($_SESSION['plan'], ['admin', 'owner'])) {
    header('Location: ../login');
    exit;
}

// ---- Stats ----
$total_users = 0;
if (file_exists('../bus.txt')) {
    $users = file('../bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total_users = count($users);
}

$total_attacks = 0;
$logFile = __DIR__ . '/attacks.log';
if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total_attacks = count($lines);
}

$active_attacks = 0;
if (file_exists('../ng.txt')) {
    $ng_lines = file('../ng.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $now = time();
    foreach ($ng_lines as $line) {
        $attack = json_decode($line, true);
        if ($now <= $attack['end_time']) $active_attacks++;
    }
}

$methods_count = 0;
if (file_exists('../methods.txt')) {
    $methods = file('../methods.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $methods_count = count($methods);
}

// Success rate (simulated: completed attacks vs total)
$success_rate = $total_attacks > 0 ? round(($active_attacks / $total_attacks) * 100, 1) : 0;

// Recent attacks (last 5)
$recent_attacks = [];
if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines);
    foreach (array_slice($lines, 0, 5) as $line) {
        $recent_attacks[] = json_decode($line, true);
    }
}

// ---- Announcements ----
$announcements = [];
if (file_exists('../an.txt')) {
    $raw = file('../an.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($raw as $line) {
        $parts = explode('|', $line, 2);
        if (count($parts) === 2) {
            $announcements[] = ['title' => trim($parts[0]), 'message' => trim($parts[1])];
        }
    }
}

// Handle announcement actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['announce_action'])) {
    if ($_POST['announce_action'] === 'add') {
        $title = $_POST['ann_title'] ?? '';
        $msg = $_POST['ann_msg'] ?? '';
        if (!empty($title) && !empty($msg)) {
            file_put_contents('../an.txt', $title . '|' . $msg . "\n", FILE_APPEND);
            $_SESSION['admin_success'] = 'Announcement added!';
        }
    } elseif ($_POST['announce_action'] === 'remove') {
        $index = (int)($_POST['index'] ?? -1);
        if ($index >= 0 && file_exists('../an.txt')) {
            $lines = file('../an.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (isset($lines[$index])) {
                unset($lines[$index]);
                file_put_contents('../an.txt', implode("\n", $lines) . "\n");
                $_SESSION['admin_success'] = 'Announcement removed.';
            }
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Admin Dashboard – KillByte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== GLOBAL RESET & VARIABLES ===== */
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
        body { overflow-y: auto; }  /* FIX: allow vertical scroll on mobile */
        ::-webkit-scrollbar { width:2px; }
        ::-webkit-scrollbar-thumb { background:rgba(204,17,17,0.15); border-radius:1px; }

        /* ===== GRID BACKGROUND ===== */
        .grid-container {
            position:fixed; inset:0; z-index:0; pointer-events:none; perspective:1200px;
        }
        .grid-surface {
            position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotateX(60deg) scale(1.4);
            width:200%; height:200%; transform-style:preserve-3d;
        }
        .grid-squares {
            position:absolute; inset:0; display:grid; grid-template-columns:repeat(12,1fr); grid-template-rows:repeat(12,1fr);
            width:100%; height:100%; animation:gridPulse 8s ease-in-out infinite;
        }
        .grid-square {
            border:1px solid rgba(255,255,255,0.012); background:rgba(255,255,255,0.002);
            transition:all 0.8s ease; position:relative;
        }
        .grid-square::after {
            content:''; position:absolute; inset:0;
            background:radial-gradient(ellipse at center, rgba(204,17,17,0.02), transparent 70%);
            opacity:0; transition:opacity 0.8s ease;
        }
        .grid-square:hover::after { opacity:1; }
        @keyframes gridPulse { 0%,100%{transform:scale(1) rotateX(0deg);} 50%{transform:scale(1.01) rotateX(1deg);} }

        .crimson-reflection {
            position:fixed; inset:0; z-index:1; pointer-events:none;
            background:radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.02) 0%, transparent 50%),
                       radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.015) 0%, transparent 50%),
                       radial-gradient(ellipse at 50% 50%, rgba(204,17,17,0.008) 0%, transparent 70%);
        }
        .vignette {
            position:fixed; inset:0; z-index:2; pointer-events:none;
            background:radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.5) 100%);
        }
        .glass-sweep {
            position:fixed; inset:0; z-index:3; pointer-events:none;
            background:linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.008) 45%, rgba(255,255,255,0.015) 50%, rgba(255,255,255,0.008) 55%, transparent 60%);
            transform:translateX(-100%); animation:sweepGloss 12s ease-in-out infinite;
        }
        @keyframes sweepGloss { 0%{transform:translateX(-100%);opacity:0;} 6%{opacity:1;} 25%{transform:translateX(100%);opacity:1;} 30%{opacity:0;} 100%{transform:translateX(100%);opacity:0;} }

        /* ===== SIDEBAR LAYOUT ===== */
        .app {
            display:flex; min-height:100vh; position:relative; z-index:10;
        }
        .sidebar {
            position:fixed; top:0; left:0; width:var(--sidebar-width); height:100vh;
            background:rgba(0,0,0,0.4); backdrop-filter:blur(60px) saturate(180%);
            border-right:1px solid var(--border-subtle);
            padding:2rem 0; display:flex; flex-direction:column; z-index:100;
            transition:transform 0.4s var(--ease-out-expo);
        }
        .sidebar .brand {
            font-family:var(--font-display); font-size:1.2rem; font-weight:600;
            padding:0 1.5rem 2rem; letter-spacing:-0.02em;
            color:var(--text-primary); border-bottom:1px solid var(--border-subtle);
            margin-bottom:1.5rem;
        }
        .sidebar .brand i { color:var(--accent-crimson); margin-right:0.5rem; }
        .sidebar .nav-item {
            display:flex; align-items:center; gap:0.8rem;
            padding:0.7rem 1.5rem; color:var(--text-secondary);
            text-decoration:none; font-size:0.8rem; font-weight:500;
            transition:all 0.3s var(--ease-out-expo);
            border-left:2px solid transparent;
        }
        .sidebar .nav-item:hover, .sidebar .nav-item.active {
            color:var(--text-primary);
            background:var(--bg-glass-hover);
            border-left-color:var(--accent-crimson);
        }
        .sidebar .nav-item i { width:20px; font-size:0.9rem; }
        .sidebar .nav-item.active { color:var(--accent-crimson); }
        .sidebar .bottom {
            margin-top:auto; padding:1.5rem;
            border-top:1px solid var(--border-subtle);
        }
        .sidebar .bottom a {
            color:var(--text-tertiary); text-decoration:none; font-size:0.7rem;
            display:flex; align-items:center; gap:0.6rem;
            transition:color 0.3s;
        }
        .sidebar .bottom a:hover { color:var(--accent-crimson); }

        .main-content {
            margin-left:var(--sidebar-width); flex:1; padding:2rem;
            width:calc(100% - var(--sidebar-width));
        }

        /* ===== CARDS & STATS ===== */
        .stats-grid {
            display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:1rem;
            margin-bottom:2rem;
        }
        .stat-card {
            background:var(--bg-glass); backdrop-filter:blur(40px);
            border:1px solid var(--border-subtle); border-radius:16px;
            padding:1.2rem 1.5rem; transition:all 0.4s var(--ease-out-expo);
        }
        .stat-card:hover { border-color:var(--border-crimson); transform:translateY(-2px); }
        .stat-card .number {
            font-family:var(--font-mono); font-size:1.8rem; font-weight:500;
            color:var(--text-primary);
        }
        .stat-card .label { font-size:0.65rem; color:var(--text-tertiary); text-transform:uppercase; letter-spacing:0.08em; margin-top:0.2rem; }

        .glass-panel {
            background:var(--bg-glass); backdrop-filter:blur(40px);
            border:1px solid var(--border-subtle); border-radius:16px;
            padding:1.5rem; margin-bottom:2rem;
        }
        .panel-title {
            font-family:var(--font-display); font-size:1.1rem; font-weight:600;
            margin-bottom:1rem; display:flex; align-items:center; gap:0.6rem;
        }
        .panel-title i { color:var(--accent-crimson); }

        /* ===== ANNOUNCEMENTS CAROUSEL ===== */
        .announcement-carousel {
            position:relative; overflow:hidden; min-height:80px;
            border-radius:12px; background:var(--accent-crimson-dim);
            border:1px solid var(--border-crimson);
            padding:0.8rem 1.5rem;
        }
        .announcement-slide {
            display:none; animation:fadeSlide 0.6s var(--ease-out-expo);
        }
        .announcement-slide.active { display:block; }
        @keyframes fadeSlide {
            0%{opacity:0;transform:translateY(10px);}
            100%{opacity:1;transform:translateY(0);}
        }
        .announcement-slide .title { font-weight:600; font-size:0.95rem; color:var(--accent-crimson); }
        .announcement-slide .msg { color:var(--text-secondary); font-size:0.85rem; margin-top:0.2rem; }

        .ann-form { display:flex; gap:0.8rem; flex-wrap:wrap; margin-top:1rem; }
        .ann-form input, .ann-form textarea {
            background:rgba(255,255,255,0.005); border:1px solid var(--border-subtle);
            border-radius:8px; padding:0.6rem 1rem; color:var(--text-primary);
            font-family:'Inter',sans-serif; font-size:0.8rem; flex:1;
        }
        .ann-form textarea { min-width:200px; resize:vertical; }
        .ann-form button {
            background:var(--gradient-crimson); border:none; border-radius:8px;
            padding:0.6rem 1.5rem; color:white; font-weight:600; cursor:pointer;
            transition:all 0.3s; font-size:0.8rem;
        }
        .ann-form button:hover { transform:scale(1.02); box-shadow:0 0 30px rgba(204,17,17,0.1); }

        .ann-list { margin-top:1rem; }
        .ann-item {
            display:flex; justify-content:space-between; align-items:center;
            padding:0.6rem 0; border-bottom:1px solid var(--border-subtle);
        }
        .ann-item .content { flex:1; }
        .ann-item .title { font-weight:500; color:var(--text-primary); }
        .ann-item .msg { font-size:0.8rem; color:var(--text-secondary); }
        .ann-item .remove-btn {
            background:none; border:none; color:var(--text-tertiary);
            cursor:pointer; transition:color 0.3s;
        }
        .ann-item .remove-btn:hover { color:var(--accent-crimson); }

        /* ===== RECENT ATTACKS ===== */
        .attack-feed .item {
            display:flex; justify-content:space-between; padding:0.5rem 0;
            border-bottom:1px solid var(--border-subtle);
            font-size:0.8rem;
        }
        .attack-feed .item:last-child { border-bottom:none; }
        .attack-feed .item .user { font-weight:500; color:var(--text-primary); }
        .attack-feed .item .target { color:var(--text-secondary); }
        .attack-feed .item .time { color:var(--text-tertiary); font-size:0.7rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-left:0; width:100%; padding:1rem; }
            .stats-grid { grid-template-columns:1fr 1fr; }
        }
        .hamburger {
            display:none; flex-direction:column; gap:4px; cursor:pointer;
            background:none; border:none; padding:0.5rem;
        }
        .hamburger span { width:24px; height:2px; background:var(--text-primary); border-radius:2px; }
        .mobile-header {
            display:none; align-items:center; gap:1rem; margin-bottom:1.5rem;
        }
        @media (max-width:768px) {
            .hamburger, .mobile-header { display:flex; }
        }

        /* ===== NOTIFICATION ===== */
        .notification {
            position:fixed; top:80px; right:20px; padding:0.8rem 1.5rem;
            background:var(--bg-elevated); border:1px solid var(--border-crimson);
            border-radius:12px; backdrop-filter:blur(40px); color:var(--text-primary);
            z-index:9999; animation:slideIn 0.3s var(--ease-out-expo);
            box-shadow:0 20px 60px rgba(0,0,0,0.2);
            max-width:90vw; word-wrap:break-word;
        }
        .notification.success { border-color:rgba(34,197,94,0.2); }
        @keyframes slideIn {
            0%{transform:translateX(100%);opacity:0;} 100%{transform:translateX(0);opacity:1;}
        }
    </style>
</head>
<body>
    <div class="grid-container"><div class="grid-surface"><div class="grid-squares">
        <?php for ($i=0;$i<144;$i++): ?><div class="grid-square"></div><?php endfor; ?>
    </div></div></div>
    <div class="crimson-reflection"></div><div class="vignette"></div><div class="glass-sweep"></div>

    <div class="app">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand"><i class="fas fa-skull"></i> KillByte<br><span style="font-size:0.7rem;color:var(--text-tertiary);">Admin Panel</span></div>
            <a href="dashboard" class="nav-item active"><i class="fas fa-gauge-high"></i> Dashboard</a>
            <a href="users" class="nav-item"><i class="fas fa-users"></i> Users</a>
            <a href="attacks" class="nav-item"><i class="fas fa-bolt"></i> Attack Logs</a>
            <a href="methods" class="nav-item"><i class="fas fa-code"></i> Methods</a>
            <a href="settings" class="nav-item"><i class="fas fa-gear"></i> Settings</a>
            <div class="bottom">
                <a href="../dashboard"><i class="fas fa-arrow-left"></i> Back to Panel</a>
            </div>
        </aside>

        <!-- Mobile header -->
        <div class="mobile-header">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <span></span><span></span><span></span>
            </button>
            <span style="font-weight:600;">KillByte Admin</span>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card"><div class="number"><?php echo $total_users; ?></div><div class="label">Total Users</div></div>
                <div class="stat-card"><div class="number"><?php echo $total_attacks; ?></div><div class="label">Total Attacks</div></div>
                <div class="stat-card"><div class="number"><?php echo $active_attacks; ?></div><div class="label">Active Attacks</div></div>
                <div class="stat-card"><div class="number"><?php echo $methods_count; ?></div><div class="label">Methods</div></div>
                <div class="stat-card"><div class="number"><?php echo $success_rate; ?>%</div><div class="label">Success Rate</div></div>
            </div>

            <!-- Announcements -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-bullhorn"></i> Announcements</div>
                <div class="announcement-carousel" id="annCarousel">
                    <?php if (empty($announcements)): ?>
                        <div class="announcement-slide active">
                            <div class="msg" style="color:var(--text-tertiary);">No announcements yet.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($announcements as $idx => $ann): ?>
                        <div class="announcement-slide <?php echo $idx===0?'active':''; ?>" data-index="<?php echo $idx; ?>">
                            <div class="title"><?php echo htmlspecialchars($ann['title']); ?></div>
                            <div class="msg"><?php echo htmlspecialchars($ann['message']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div style="text-align:center;margin-top:0.5rem;">
                    <button onclick="prevAnnounce()" style="background:none;border:none;color:var(--text-tertiary);cursor:pointer;padding:0 0.5rem;">‹</button>
                    <span id="annIndicator" style="font-size:0.7rem;color:var(--text-tertiary);"></span>
                    <button onclick="nextAnnounce()" style="background:none;border:none;color:var(--text-tertiary);cursor:pointer;padding:0 0.5rem;">›</button>
                </div>

                <form method="POST" class="ann-form">
                    <input type="hidden" name="announce_action" value="add">
                    <input type="text" name="ann_title" placeholder="Title" required>
                    <textarea name="ann_msg" placeholder="Message" required></textarea>
                    <button type="submit"><i class="fas fa-plus"></i> Add</button>
                </form>

                <div class="ann-list">
                    <?php foreach ($announcements as $idx => $ann): ?>
                    <div class="ann-item">
                        <div class="content">
                            <div class="title"><?php echo htmlspecialchars($ann['title']); ?></div>
                            <div class="msg"><?php echo htmlspecialchars($ann['message']); ?></div>
                        </div>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="announce_action" value="remove">
                            <input type="hidden" name="index" value="<?php echo $idx; ?>">
                            <button type="submit" class="remove-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Attacks Feed -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-clock-rotate-left"></i> Recent Attacks</div>
                <div class="attack-feed">
                    <?php if (empty($recent_attacks)): ?>
                        <div style="color:var(--text-tertiary);font-size:0.8rem;">No attacks yet.</div>
                    <?php else: ?>
                        <?php foreach ($recent_attacks as $att): ?>
                        <div class="item">
                            <span><span class="user"><?php echo htmlspecialchars($att['username'] ?? '?'); ?></span>
                                <span class="target">→ <?php echo htmlspecialchars($att['target'] ?? ''); ?></span></span>
                            <span class="time"><?php echo date('H:i:s', $att['start_time'] ?? time()); ?></span>
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

    <script>
        // Announcement carousel
        const slides = document.querySelectorAll('.announcement-slide');
        let current = 0;
        function showSlide(index) {
            slides.forEach((s,i) => {
                s.classList.toggle('active', i===index);
            });
            document.getElementById('annIndicator').textContent = (slides.length>0) ? (index+1)+'/'+slides.length : '';
        }
        function nextAnnounce() {
            if (slides.length===0) return;
            current = (current+1)%slides.length;
            showSlide(current);
        }
        function prevAnnounce() {
            if (slides.length===0) return;
            current = (current-1+slides.length)%slides.length;
            showSlide(current);
        }
        // Auto-rotate every 8 seconds
        if (slides.length>1) setInterval(nextAnnounce, 8000);
        // Init indicator
        showSlide(0);
        // Close sidebar on link click (mobile)
        document.querySelectorAll('.sidebar .nav-item').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('sidebar').classList.remove('open');
            });
        });

        // Auto-dismiss notifications after 5 seconds
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