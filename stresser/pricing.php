<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Language persistence
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'zh'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
$lang = $_SESSION['lang'] ?? 'en';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

define('OXAPAY_API_KEY', '6KL5W3-K89C60-NHWKAV-7PHHPM');

$plans = [
    'basic' => [
        'name' => ['en' => 'Basic Plan', 'zh' => '基础套餐'],
        'price' => 10,
        'features' => [
            'en' => ['1 Concurrent', '300s Attack Time', '❌ Non-VIP'],
            'zh' => ['1 并发', '300秒攻击时间', '❌ 非VIP']
        ],
        'max_conc' => 1,
        'max_time' => 300,
        'vip' => false
    ],
    'simple' => [
        'name' => ['en' => 'Simple Plan', 'zh' => '简单套餐'],
        'price' => 25,
        'features' => [
            'en' => ['2 Concurrents', '600s Attack Time', '❌ Non-VIP'],
            'zh' => ['2 并发', '600秒攻击时间', '❌ 非VIP']
        ],
        'max_conc' => 2,
        'max_time' => 600,
        'vip' => false
    ],
    'hobbit' => [
        'name' => ['en' => 'Hobbit Plan', 'zh' => '霍比特套餐'],
        'price' => 55,
        'features' => [
            'en' => ['3 Concurrents', '2000s Attack Time', '✨ VIP'],
            'zh' => ['3 并发', '2000秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 3,
        'max_time' => 2000,
        'vip' => true
    ],
    'advanced' => [
        'name' => ['en' => 'Advanced Plan', 'zh' => '高级套餐'],
        'price' => 65,
        'features' => [
            'en' => ['4 Concurrents', '2800s Attack Time', '✨ VIP'],
            'zh' => ['4 并发', '2800秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 4,
        'max_time' => 2800,
        'vip' => true
    ],
    'terror' => [
        'name' => ['en' => 'Terror Plan', 'zh' => '恐怖套餐'],
        'price' => 75,
        'features' => [
            'en' => ['5 Concurrents', '3300s Attack Time', '✨ VIP'],
            'zh' => ['5 并发', '3300秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 5,
        'max_time' => 3300,
        'vip' => true
    ],
    'fresh' => [
        'name' => ['en' => 'Fresh Plan', 'zh' => '清新套餐'],
        'price' => 110,
        'features' => [
            'en' => ['8 Concurrents', '6400s Attack Time', '✨ VIP'],
            'zh' => ['8 并发', '6400秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 8,
        'max_time' => 6400,
        'vip' => true
    ],
    'emerald' => [
        'name' => ['en' => 'Emerald Plan', 'zh' => '翡翠套餐'],
        'price' => 150,
        'features' => [
            'en' => ['12 Concurrents', '8800s Attack Time', '✨ VIP'],
            'zh' => ['12 并发', '8800秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 12,
        'max_time' => 8800,
        'vip' => true
    ],
    'meteor' => [
        'name' => ['en' => 'Meteor Plan', 'zh' => '流星套餐'],
        'price' => 180,
        'features' => [
            'en' => ['15 Concurrents', '10400s Attack Time', '✨ VIP'],
            'zh' => ['15 并发', '10400秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 15,
        'max_time' => 10400,
        'vip' => true
    ],
    'burial' => [
        'name' => ['en' => 'Burial Plan', 'zh' => '埋葬套餐'],
        'price' => 220,
        'features' => [
            'en' => ['20 Concurrents', '14400s Attack Time', '✨ VIP'],
            'zh' => ['20 并发', '14400秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 20,
        'max_time' => 14400,
        'vip' => true
    ],
    'rush' => [
        'name' => ['en' => 'Rush Plan', 'zh' => '冲刺套餐'],
        'price' => 500,
        'features' => [
            'en' => ['50 Concurrents', '28800s Attack Time', '✨ VIP'],
            'zh' => ['50 并发', '28800秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 50,
        'max_time' => 28800,
        'vip' => true
    ],
    'blast' => [
        'name' => ['en' => 'Blast Plan', 'zh' => '爆炸套餐'],
        'price' => 1000,
        'features' => [
            'en' => ['100 Concurrents', '36600s Attack Time', '✨ VIP'],
            'zh' => ['100 并发', '36600秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 100,
        'max_time' => 36600,
        'vip' => true
    ],
    'zomb' => [
        'name' => ['en' => 'Zomb Plan', 'zh' => '丧尸套餐'],
        'price' => 1750,
        'features' => [
            'en' => ['200 Concurrents', '43200s Attack Time', '✨ VIP'],
            'zh' => ['200 并发', '43200秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 200,
        'max_time' => 43200,
        'vip' => true
    ],
    'titan' => [
        'name' => ['en' => 'Titan Plan', 'zh' => '泰坦套餐'],
        'price' => 2500,
        'features' => [
            'en' => ['300 Concurrents', '86000s Attack Time', '✨ VIP'],
            'zh' => ['300 并发', '86000秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 300,
        'max_time' => 86000,
        'vip' => true
    ],
    'decay' => [
        'name' => ['en' => 'Decay Plan', 'zh' => '衰退套餐'],
        'price' => 4000,
        'features' => [
            'en' => ['500 Concurrents', '280000s Attack Time', '✨ VIP'],
            'zh' => ['500 并发', '280000秒攻击时间', '✨ VIP']
        ],
        'max_conc' => 500,
        'max_time' => 280000,
        'vip' => true
    ]
];

// Handle payment submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'])) {
    $plan = $_POST['plan'] ?? '';

    if (empty($plan) || !isset($plans[$plan])) {
        $errors[] = "Please select a valid plan";
    } else {
        $orderId = strtoupper($plan) . '-' . time() . '-' . rand(1000, 9999);

        $data = [
            'merchant' => OXAPAY_API_KEY,
            'amount' => $plans[$plan]['price'],
            'currency' => isset($_POST['crypto']) ? $_POST['crypto'] : 'USD',
            'lifeTime' => 30,
            'feePaidByPayer' => 0,
            'underPaidCover' => 2.5,
            'callbackUrl' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            'returnUrl' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?status=pending',
            'description' => $plans[$plan]['name'][$lang] . ' Purchase',
            'orderId' => $orderId,
            'email' => isset($_POST['email']) ? $_POST['email'] : null
        ];

        $ch = curl_init('https://api.oxapay.com/merchants/request');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $errors[] = 'Payment error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['payLink'])) {
                $_SESSION['pending_plan'] = [
                    'plan' => $plan,
                    'specs' => $plans[$plan]
                ];
                header('Location: ' . $result['payLink']);
                exit;
            } else {
                $errors[] = 'Error creating payment: ' . json_encode($result);
            }
        }
        curl_close($ch);
    }
}

// Handle successful payment return
if (isset($_GET['success']) && isset($_SESSION['pending_plan'])) {
    $plan = $_SESSION['pending_plan']['plan'];
    $specs = $_SESSION['pending_plan']['specs'];

    $users = file('bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $newUsers = [];
    foreach ($users as $user) {
        $userData = explode('|', $user);
        if ($userData[0] === $_SESSION['username']) {
            $expiryDate = date('d-m-Y', strtotime('+30 days'));
            $userData[2] = $specs['max_conc'];
            $userData[3] = $specs['max_time'];
            $userData[4] = $plan;
            $userData[5] = $expiryDate;
        }
        $newUsers[] = implode('|', $userData);
    }
    file_put_contents('bus.txt', implode("\n", $newUsers));
    unset($_SESSION['pending_plan']);
    $_SESSION['success'] = "Plan upgraded successfully!";
    header('Location: dashboard.php');
    exit;
}

// Handle failed payment
if (isset($_GET['fail'])) {
    unset($_SESSION['pending_plan']);
    $errors[] = "Payment failed. Please try again.";
}

// Translation strings (simplified for this page)
$t = [
    'en' => [
        'page_title' => 'Pricing Plans',
        'subtitle' => 'Choose the plan that fits your testing needs',
        'purchase' => 'Purchase Now',
        'current_plan' => 'Your current plan:',
    ],
    'zh' => [
        'page_title' => '套餐价格',
        'subtitle' => '选择适合您测试需求的套餐',
        'purchase' => '立即购买',
        'current_plan' => '您当前的套餐：',
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
    <title>Pricing | KillByte Solutions</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ===== KILLBYTE LUXURY CSS (same as dashboard/hub) ===== */
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
        .nav-link.active { color: var(--text-primary); }
        .nav-link.active::after { width: 100%; }

        .lang-switch {
            display: flex; gap: 3px; margin-left: 0.6rem;
            background: rgba(255,255,255,0.01); padding: 3px; border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.02);
        }
        .lang-btn {
            background: none; border: none; color: var(--text-secondary);
            padding: 0.25rem 0.6rem; border-radius: 99px; font-size: 0.55rem;
            font-weight: 600; cursor: pointer; transition: all 0.3s ease;
            font-family: var(--font-mono); text-transform: uppercase;
        }
        .lang-btn.active { background: var(--accent-crimson); color: white; }
        .lang-btn:hover:not(.active) { color: var(--text-primary); }

        .theme-toggle {
            background: none; border: none; color: var(--text-secondary);
            font-size: 0.9rem; cursor: pointer; transition: all 0.3s; margin-left: 0.6rem;
        }
        .theme-toggle:hover { color: var(--text-primary); }

        .mobile-menu-btn { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.1rem; cursor: pointer; }

        /* Main Content */
        .main-content {
            position: relative; z-index: 10;
            max-width: 1400px; margin: 120px auto 60px; padding: 0 2rem;
        }

        .page-header {
            text-align: center; margin-bottom: 3rem;
        }
        .page-header h1 {
            font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 300; letter-spacing: -0.02em;
        }
        .page-header h1 .accent { font-weight: 600; color: var(--accent-crimson); }
        .page-header p {
            color: var(--text-secondary); font-size: 1.1rem; margin-top: 0.5rem;
        }

        /* Pricing Grid */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .pricing-card {
            background: var(--bg-glass);
            backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.6s var(--ease-out-expo);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .pricing-card:hover {
            border-color: var(--border-crimson);
            transform: translateY(-8px);
            box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        }
        .pricing-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 1px; background: var(--gradient-crimson); opacity: 0.2;
        }

        .pricing-card .plan-name {
            font-family: var(--font-display);
            font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .pricing-card .plan-price {
            font-family: var(--font-mono);
            font-size: 2rem; font-weight: 500; color: var(--accent-crimson);
            margin-bottom: 1.5rem;
        }
        .pricing-card .plan-features {
            list-style: none; margin-bottom: 2rem; flex: 1;
        }
        .pricing-card .plan-features li {
            padding: 0.4rem 0; color: var(--text-secondary);
            font-size: 0.9rem; border-bottom: 1px solid var(--border-subtle);
        }
        .pricing-card .plan-features li:last-child { border-bottom: none; }
        .pricing-card .vip-badge {
            display: inline-block; font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 0.1em; padding: 0.2rem 0.8rem; border-radius: 99px;
            background: var(--accent-crimson-dim); color: var(--accent-crimson);
            border: 1px solid var(--border-crimson); margin-bottom: 1rem;
        }

        .purchase-btn {
            width: 100%; padding: 0.9rem; background: var(--gradient-crimson);
            color: white; border: none; border-radius: 99px; font-weight: 600;
            font-size: 0.9rem; cursor: pointer; transition: all 0.4s var(--ease-out-expo);
            box-shadow: 0 4px 30px rgba(204,17,17,0.06);
            display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem;
        }
        .purchase-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 50px rgba(204,17,17,0.12);
        }

        /* Notification */
        .notification {
            position: fixed; top: 20px; right: 20px; padding: 15px 25px;
            border-radius: 12px; background: rgba(6,6,6,0.95);
            border: 1px solid rgba(204,17,17,0.15); color: var(--text-primary);
            z-index: 10000; animation: slideIn 0.4s ease;
            backdrop-filter: blur(20px); font-size: 0.85rem;
            max-width: 400px; display: flex; align-items: center; gap: 0.5rem;
        }
        .notification.error { border-color: var(--accent-crimson); }
        .notification.success { border-color: #22c55e; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 0 1.2rem; }
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
            .pricing-grid { grid-template-columns: 1fr; }
            .main-content { padding: 0 1rem; }
        }
    </style>
</head>
<body>
    <!-- Background layers -->
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

    <!-- Navbar -->
<nav class="navbar" id="navbar">
    <a href="dashboard.php" class="nav-brand">
        <img src="image.jpg" alt="KillByte Solutions" style="height:32px; width:auto; border-radius:8px;">
        <div class="nav-brand-text">KillByte Solutions</div>
    </a>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="hub.php" class="nav-link">Attack Hub</a>
            <a href="pricing.php" class="nav-link active">Pricing</a>
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
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1><?php echo $tr['page_title']; ?></h1>
            <p><?php echo $tr['subtitle']; ?></p>
            <p style="font-size:0.9rem;color:var(--text-tertiary);margin-top:0.5rem;">
                <?php echo $tr['current_plan']; ?> <strong style="color:var(--accent-crimson);"><?php echo htmlspecialchars($_SESSION['plan']); ?></strong>
            </p>
        </div>

        <div class="pricing-grid">
            <?php foreach ($plans as $planId => $plan): ?>
            <div class="pricing-card">
                <?php if ($plan['vip']): ?>
                <div class="vip-badge"><i class="fas fa-crown"></i> VIP</div>
                <?php endif; ?>
                <div class="plan-name"><?php echo htmlspecialchars($plan['name'][$lang]); ?></div>
                <div class="plan-price">$<?php echo number_format($plan['price'], 2); ?></div>
                <ul class="plan-features">
                    <?php foreach ($plan['features'][$lang] as $feature): ?>
                    <li><?php echo htmlspecialchars($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <form method="POST">
                    <input type="hidden" name="plan" value="<?php echo htmlspecialchars($planId); ?>">
                    <button type="submit" class="purchase-btn">
                        <i class="fas fa-cart-plus"></i> <?php echo $tr['purchase']; ?>
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Notifications -->
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="notification error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <script>
        // ===== LANGUAGE SWITCH =====
        function switchLanguage(lang) {
            window.location.href = '?lang=' + lang;
        }

        // ===== THEME TOGGLE =====
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

        // ===== LENIS SMOOTH SCROLL =====
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // ===== CURSOR =====
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
            document.querySelectorAll('a, button, .pricing-card').forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('expanded'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('expanded'));
            });
        }

        // ===== NAVBAR SCROLL EFFECT =====
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // ===== MOBILE MENU =====
        function toggleMobileMenu() {
            const nav = document.querySelector('.nav-links');
            if (nav.style.display === 'flex') {
                nav.style.display = 'none';
            } else {
                nav.style.display = 'flex';
                nav.style.flexDirection = 'column';
                nav.style.position = 'fixed';
                nav.style.top = '68px';
                nav.style.left = '0';
                nav.style.width = '100%';
                nav.style.background = 'rgba(0,0,0,0.95)';
                nav.style.backdropFilter = 'blur(20px)';
                nav.style.padding = '1.5rem 2rem';
                nav.style.gap = '1rem';
                nav.style.borderBottom = '1px solid rgba(255,255,255,0.05)';
                nav.style.zIndex = '999';
            }
        }
        // Close menu when a link is clicked
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const nav = document.querySelector('.nav-links');
                if (nav.style.display === 'flex') nav.style.display = 'none';
            });
        });

        // ===== AUTO-DISMISS NOTIFICATIONS =====
        document.querySelectorAll('.notification').forEach(n => {
            setTimeout(() => {
                n.style.transition = 'all 0.3s ease';
                n.style.opacity = '0';
                n.style.transform = 'translateX(100%)';
                setTimeout(() => n.remove(), 300);
            }, 5000);
        });

        // ===== INIT THEME =====
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            document.getElementById('themeIcon').className = 'fas fa-sun';
        }
    </script>
</body>
</html>