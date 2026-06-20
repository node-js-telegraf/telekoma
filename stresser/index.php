<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('OXAPAY_API_KEY', '6KL5W3-K89C60-NHWKAV-7PHHPM');

$cryptocurrencies = [
    'SOL' => 'Solana',
    'TRX' => 'TRON',
    'LTC' => 'Litecoin',
    'BTC' => 'Bitcoin',
    'USDT_TRC20' => 'USDT (TRC20)',
    'USDT_ERC20' => 'USDT (ERC20)',
    'USDT_BEP20' => 'USDT (BEP20)',
    'BNB' => 'Binance Coin',
    'XRP' => 'Ripple',
    'ETH' => 'Ethereum',
    'POL' => 'Polygon'
];

$plans = [
    // ─── Daily Blasts ───
    'burst_entry' => [
        'name' => 'Burst Entry',
        'price' => 15,
        'days' => 1,
        'max_conc' => 1,
        'max_time' => 120,
        'vip' => false,
        'features' => ['1 Day', '1 Concurrent', '120s Attack Time', 'Non-VIP']
    ],
    'dual_daggers' => [
        'name' => 'Dual Daggers',
        'price' => 22,
        'days' => 1,
        'max_conc' => 2,
        'max_time' => 120,
        'vip' => false,
        'features' => ['1 Day', '2 Concurrents', '120s Attack Time', 'Non-VIP']
    ],
    'crimson_reaper' => [
        'name' => 'Crimson Reaper',
        'price' => 40,
        'days' => 1,
        'max_conc' => 2,
        'max_time' => 250,
        'vip' => true,
        'features' => ['1 Day', '2 Concurrents', '250s Attack Time', 'VIP']
    ],
    'overlord_strike' => [
        'name' => 'Overlord Strike',
        'price' => 160,
        'days' => 1,
        'max_conc' => 2,
        'max_time' => 250,
        'vip' => true,
        'limited_access' => true,
        'features' => ['1 Day', '2 Concurrents', '250s Attack Time', 'VIP + Limited Access']
    ],

    // ─── Starter Surge etc. ───
    'starter_surge' => [
        'name' => 'Starter Surge',
        'price' => 30,
        'days' => 7,
        'max_conc' => 1,
        'max_time' => 60,
        'vip' => false,
        'features' => ['7 Days', '1 Concurrent', '60s Attack Time', 'Non-VIP']
    ],
    'edge_strike' => [
        'name' => 'Edge Strike',
        'price' => 65,
        'days' => 30,
        'max_conc' => 1,
        'max_time' => 120,
        'vip' => false,
        'features' => ['30 Days', '1 Concurrent', '120s Attack Time', 'Non-VIP']
    ],
    'dual_surge' => [
        'name' => 'Dual Surge',
        'price' => 85,
        'days' => 30,
        'max_conc' => 2,
        'max_time' => 120,
        'vip' => false,
        'features' => ['30 Days', '2 Concurrents', '120s Attack Time', 'Non-VIP']
    ],
    'phantom_force' => [
        'name' => 'Phantom Force',
        'price' => 160,
        'days' => 30,
        'max_conc' => 3,
        'max_time' => 200,
        'vip' => true,
        'features' => ['30 Days', '3 Concurrents', '200s Attack Time', 'VIP']
    ],
    'shadow_blade' => [
        'name' => 'Shadow Blade',
        'price' => 280,
        'days' => 30,
        'max_conc' => 4,
        'max_time' => 250,
        'vip' => true,
        'features' => ['30 Days', '4 Concurrents', '250s Attack Time', 'VIP']
    ],
    'dominion_control' => [
        'name' => 'Dominion Control',
        'price' => 380,
        'days' => 30,
        'max_conc' => 4,
        'max_time' => 250,
        'vip' => true,
        'limited_access' => true,
        'features' => ['30 Days', '4 Concurrents', '250s Attack Time', 'VIP + Limited Access']
    ],
    'infinity_override' => [
        'name' => 'Infinity Override',
        'price' => 750,
        'days' => 3650,
        'max_conc' => 6,
        'max_time' => 1200,
        'vip' => true,
        'features' => ['Lifetime', '6 Concurrents', '1200s Attack Time', 'VIP']
    ],

    // ─── Reseller ───
    'dark_market' => [
        'name' => 'Dark Market License',
        'price' => 1300,
        'days' => 3650,
        'max_conc' => 10,
        'max_time' => 1000,
        'vip' => true,
        'reseller' => true,
        'features' => ['Reseller', '10 Concurrents', '1000s Attack Time', 'No Limits']
    ],

    // ─── Elite Monthly ───
    'enterprise_reactor' => [
        'name' => 'Enterprise Reactor',
        'price' => 1200,
        'days' => 30,
        'max_conc' => 10,
        'max_time' => 3600,
        'vip' => true,
        'features' => [
            '35M+ Rq/s L7 Power',
            '150+ Gbps L4',
            'Multi-Target',
            'API-Optimized',
            'No Rate Limits',
            'Priority Execution'
        ]
    ],
    'hellstorm_protocol' => [
        'name' => 'Hellstorm Protocol',
        'price' => 1450,
        'days' => 30,
        'max_conc' => 15,
        'max_time' => 5400,
        'vip' => true,
        'features' => [
            '45M+ Rq/s L7',
            '200+ Gbps L4',
            'VIP Private Nodes',
            'Undetected Obfuscation',
            'Exclusive L7-KILLER Payloads',
            'Zero-Lag Launch'
        ]
    ],
];

// Handle callback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_OXAPAY_SIGNATURE'])) {
    $payload = file_get_contents('php://input');
    $data = json_decode($payload, true);
    
    if ($data['status'] === 'COMPLETED' && isset($data['orderId'])) {
        $orderParts = explode('-', $data['orderId']);
        $planType = strtolower($orderParts[0]);
        
        if (isset($plans[$planType])) {
            $username = 'user_' . bin2hex(random_bytes(5));
            $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 12);
            
            $apiData = [
                'username' => $username,
                'password' => $password,
                'rank' => 'user',
                'max_time' => $plans[$planType]['max_time'],
                'max_conc' => $plans[$planType]['max_conc'],
                'vip' => $plans[$planType]['vip'] ? 'true' : 'false',
                'days' => 30
            ];

            $ch = curl_init('http://0.0.0.0:5000/api/user/add');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($response, true);
            
            if (isset($result['message']) && $result['message'] === 'User created successfully') {
                $_SESSION['credentials'] = [
                    'username' => $username,
                    'password' => $password,
                    'plan' => $plans[$planType]['name']
                ];
                
                header('Location: ' . $_SERVER['PHP_SELF'] . '?status=success');
                exit;
            } else {
                die('Error creating account: ' . $response);
            }
        }
    }
    exit;
}

// Handle payment initiation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crypto']) && isset($_POST['plan'])) {
    $selectedCrypto = $_POST['crypto'];
    $selectedPlan = $_POST['plan'];
    
    if (!isset($plans[$selectedPlan])) {
        die('Invalid plan selected');
    }
    
    $orderId = strtoupper($selectedPlan) . '-' . time() . '-' . rand(1000, 9999);
    
    $data = [
        'merchant' => OXAPAY_API_KEY,
        'amount' => $plans[$selectedPlan]['price'],
        'currency' => 'USD',
        'orderCurrency' => 'USD',
        'lifeTime' => 30,
        'feePaidByPayer' => 0,
        'underPaidCover' => 2.5,
        'callbackUrl' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
        'returnUrl' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?status=pending',
        'description' => $plans[$selectedPlan]['name'] . ' Purchase',
        'orderId' => $orderId,
        'email' => isset($_POST['email']) ? $_POST['email'] : null
    ];

    $ch = curl_init('https://api.oxapay.com/merchants/request');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) die('Curl error: ' . curl_error($ch));
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['payLink'])) {
        header('Location: ' . $result['payLink']);
        exit;
    } else {
        die('Error creating payment: ' . json_encode($result));
    }
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#000000">
    <title>KillByte Solutions | Enterprise L7 & L4 Stress Testing</title>
    <meta name="description" content="The most powerful Layer 7 & Layer 4 stress testing platform. 550M+ req/s, 1.2TB L4 performance.">
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
        .nav-auth .register-btn {
            background: var(--gradient-crimson);
            border: none;
            color: white;
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

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.1rem;
            cursor: pointer;
        }

        /* ===== MOBILE MENU OVERLAY ===== */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(30px);
            z-index: 2000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }
        .mobile-nav-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .mobile-nav-overlay a, .mobile-nav-overlay button {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: 500;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: color 0.3s;
            cursor: pointer;
        }
        .mobile-nav-overlay a:hover, .mobile-nav-overlay button:hover {
            color: var(--accent-crimson);
        }
        .mobile-nav-auth {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        .mobile-nav-auth a {
            padding: 0.6rem 1.8rem;
            border-radius: 99px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .mobile-nav-auth .login-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .mobile-nav-auth .register-btn {
            background: var(--gradient-crimson);
            color: white;
        }
        .close-mobile-menu {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.8rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 140px 2rem 100px;
            z-index: 10;
            overflow: hidden;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1200px;
            width: 100%;
        }
        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; gap: 2rem; }
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.01);
            border: 1px solid rgba(255,255,255,0.02);
            padding: 0.5rem 1.2rem;
            border-radius: 99px;
            font-size: 0.6rem;
            color: var(--text-tertiary);
            margin-bottom: 2.5rem;
            backdrop-filter: blur(20px);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            animation: fadeInUp 1s var(--ease-out-expo) 0.2s both;
        }
        .hero-badge .pulse-dot {
            width: 5px;
            height: 5px;
            background: var(--accent-crimson);
            border-radius: 50%;
            animation: pulse-glow 3s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(204,17,17,0.3);
        }
        @keyframes pulse-glow {
            0%,100% { opacity:1; transform:scale(1); box-shadow:0 0 20px rgba(204,17,17,0.3); }
            50% { opacity:0.3; transform:scale(1.6); box-shadow:0 0 40px rgba(204,17,17,0.05); }
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(3rem, 7vw, 6rem);
            font-weight: 300;
            line-height: 1.05;
            letter-spacing: -0.04em;
            margin-bottom: 1.5rem;
            max-width: 1000px;
            animation: fadeInUp 1.2s var(--ease-out-expo) 0.4s both;
        }
        .hero-title .thin { font-weight: 300; }
        .hero-title .bold { font-weight: 700; }
        .hero-title .crimson {
            font-weight: 600;
            background: linear-gradient(135deg, #ffffff 0%, #ff3333 25%, #cc1111 50%, #880a0a 75%, #ffffff 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradientShift 10s ease infinite;
            filter: drop-shadow(0 0 60px rgba(204,17,17,0.08));
        }
        @keyframes gradientShift { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .hero-title .outline {
            color: transparent;
            -webkit-text-stroke: 1px rgba(255,255,255,0.04);
            font-weight: 300;
            transition: all 0.6s var(--ease-out-expo);
        }
        .hero-title .outline:hover {
            -webkit-text-stroke: 1px var(--accent-crimson);
            text-shadow: 0 0 100px rgba(204,17,17,0.05);
        }

        .hero-subtitle {
            font-size: clamp(0.9rem,1.4vw,1.05rem);
            color: var(--text-secondary);
            max-width: 540px;
            margin-bottom: 3rem;
            line-height: 1.8;
            font-weight: 300;
            animation: fadeInUp 1s var(--ease-out-expo) 0.6s both;
        }

        .hero-stats {
            display: flex;
            gap: 4rem;
            margin-bottom: 3.5rem;
            animation: fadeInUp 1s var(--ease-out-expo) 0.8s both;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hero-stat {
            text-align: center;
            position: relative;
        }
        .hero-stat::after {
            content: '';
            position: absolute;
            right: -2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 30px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.03), transparent);
        }
        .hero-stat:last-child::after { display: none; }
        .hero-stat-value {
            font-family: var(--font-mono);
            font-size: 1.6rem;
            font-weight: 500;
            color: var(--text-primary);
            display: block;
            letter-spacing: -0.02em;
        }
        .hero-stat-value .counter {
            background: linear-gradient(135deg, #fff 0%, var(--accent-crimson) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-stat-label {
            font-size: 0.55rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-top: 0.3rem;
            font-weight: 500;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            animation: fadeInUp 1s var(--ease-out-expo) 1s both;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn-primary {
            background: var(--gradient-crimson);
            color: white;
            padding: 0.9rem 2.2rem;
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
            box-shadow: 0 4px 40px rgba(204,17,17,0.06);
            letter-spacing: 0.02em;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 50px rgba(204,17,17,0.12);
        }
        .btn-secondary {
            background: rgba(255,255,255,0.01);
            color: var(--text-primary);
            padding: 0.9rem 2.2rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.4s var(--ease-out-expo);
            border: 1px solid rgba(255,255,255,0.03);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            backdrop-filter: blur(20px);
            letter-spacing: 0.02em;
        }
        .btn-secondary:hover {
            border-color: var(--border-crimson);
            background: var(--accent-crimson-dim);
            transform: translateY(-3px);
        }

        /* ===== EARTH GLOBE ===== */
        .globe-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            aspect-ratio: 1/1;
        }
        .map-rotate {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: relative;
            background: #000;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .globe-dots {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            overflow: hidden;
        }
        .globe-scroll {
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background-image: url('earth.png');
            background-size: 50% 100%;
            background-repeat: repeat-x;
            animation: globeScrollLeft 25s linear infinite;
            filter: brightness(1.2) contrast(1.1);
            mask-image: radial-gradient(circle, #000 1.2px, transparent 1.2px);
            -webkit-mask-image: radial-gradient(circle, #000 1.2px, transparent 1.2px);
            mask-size: 4px 4px;
        }
        @keyframes globeScrollLeft {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .map-rotate::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            z-index: 15;
            background: radial-gradient(circle at 35% 35%, rgba(255,255,255,0.15) 0%, transparent 45%),
                        radial-gradient(circle at center, transparent 30%, rgba(0,0,0,0.8) 100%);
            box-shadow: inset -40px -40px 60px rgba(0,0,0,1), inset 40px 40px 60px rgba(0,0,0,1);
            pointer-events: none;
        }
        .globe-overlay {
            position: absolute;
            inset: 0;
            z-index: 14;
            border-radius: 50%;
            background: linear-gradient(to right, rgba(0,0,0,0.5), transparent 20%, transparent 80%, rgba(0,0,0,0.5)),
                        linear-gradient(to bottom, rgba(0,0,0,0.4), transparent 30%, transparent 70%, rgba(0,0,0,0.4));
            pointer-events: none;
        }
        .radar-ring {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(204,17,17,0.2);
            border-radius: 50%;
            animation: radarPulse 4s ease-out infinite;
        }
        .radar-ring:nth-child(1) { width: 110%; height: 110%; animation-delay: 0s; }
        .radar-ring:nth-child(2) { width: 130%; height: 130%; animation-delay: 1s; }
        .radar-ring:nth-child(3) { width: 150%; height: 150%; animation-delay: 2s; }
        @keyframes radarPulse {
            0% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(1.3); }
        }

        /* ===== MARQUEE ===== */
        .marquee-banner {
            width: 100%;
            overflow: hidden;
            background: rgba(255,255,255,0.005);
            border-top: 1px solid rgba(255,255,255,0.01);
            border-bottom: 1px solid rgba(255,255,255,0.01);
            padding: 0.6rem 0;
            position: relative;
            z-index: 10;
        }
        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 45s linear infinite;
        }
        .marquee-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0 2.5rem;
            white-space: nowrap;
            font-family: var(--font-mono);
            font-size: 0.6rem;
            color: var(--text-tertiary);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .marquee-item i {
            color: var(--accent-crimson);
            font-size: 0.4rem;
            opacity: 0.3;
        }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        /* ===== GLASS PANELS ===== */
        .glass-panel {
            background: rgba(255,255,255,0.01);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.02);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            transition: all 0.6s var(--ease-out-expo);
        }
        .glass-panel:hover {
            border-color: rgba(255,255,255,0.03);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }

        /* ===== SUITE SECTION ===== */
        .suite-section {
            position: relative;
            padding: 80px 2rem;
            background: var(--bg-deep);
            overflow: hidden;
        }
        .suite-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-crimson), transparent);
            opacity: 0.3;
        }
        .suite-carousel {
            display: flex;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            perspective: 1200px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .suite-card {
            flex: 1 1 280px;
            max-width: 340px;
            background: var(--bg-glass);
            backdrop-filter: blur(30px);
            border-radius: 28px;
            padding: 2.5rem 2rem;
            border: 1px solid var(--border-subtle);
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .suite-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            padding: 1px;
            background: conic-gradient(from var(--angle, 0deg), transparent 60%, rgba(225,29,29,0.3), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.6s;
        }
        .suite-card:hover::before {
            opacity: 1;
            animation: rotate-conic 4s linear infinite;
        }
        @keyframes rotate-conic { to { --angle: 360deg; } }
        .suite-card:hover {
            transform: translateY(-20px) rotateY(4deg) scale(1.02);
            border-color: var(--border-crimson);
            box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 60px rgba(225,29,29,0.05);
            background: var(--bg-glass-hover);
        }
        .suite-card .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: var(--gradient-crimson);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 30px rgba(225,29,29,0.3);
            transition: all 0.4s;
        }
        .suite-card:hover .icon-wrap {
            transform: scale(1.1) rotate(-5deg);
            box-shadow: 0 12px 40px rgba(225,29,29,0.4);
        }
        .suite-card h4 {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .suite-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        .suite-card .glow-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-crimson);
            transform: scaleX(0);
            transition: transform 0.6s var(--ease-out-expo);
            transform-origin: left;
        }
        .suite-card:hover .glow-line { transform: scaleX(1); }
        .suite-card .float-icon {
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 6rem;
            opacity: 0.03;
            pointer-events: none;
            transform: rotate(10deg);
        }

        /* ===== SECTIONS ===== */
        .section {
            position: relative;
            z-index: 10;
            padding: 120px 2rem;
        }
        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }
        .section-label {
            font-family: var(--font-mono);
            font-size: 0.55rem;
            color: var(--accent-crimson);
            text-transform: uppercase;
            letter-spacing: 0.3em;
            margin-bottom: 1rem;
            display: inline-block;
            padding: 0.3rem 1rem;
            border: 1px solid rgba(204,17,17,0.06);
            border-radius: 99px;
            background: rgba(204,17,17,0.02);
            backdrop-filter: blur(10px);
        }
        .section-title {
            font-family: var(--font-display);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            margin-bottom: 1rem;
            line-height: 1.15;
            letter-spacing: -0.03em;
        }
        .section-title .accent {
            font-weight: 600;
            color: var(--accent-crimson);
        }
        .section-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.8;
            font-weight: 300;
        }

        /* ===== TYPEWRITER ===== */
        .typewriter-wrapper {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
            font-size: 1.2rem;
            font-family: var(--font-display);
            font-weight: 300;
            min-height: 70px;
        }
        .typewriter-cursor {
            display: inline-block;
            width: 2px;
            height: 1em;
            background: var(--accent-crimson);
            margin-left: 4px;
            vertical-align: text-bottom;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink { from,to { opacity:1; } 50% { opacity:0; } }

        /* ===== CRYPTO ===== */
        .crypto-grid-showcase {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
        }
        .crypto-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .crypto-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 0.9rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 12px;
            font-size: 0.75rem;
            color: var(--text-secondary);
            transition: all 0.4s var(--ease-out-expo);
            backdrop-filter: blur(10px);
            font-weight: 300;
        }
        .crypto-item:hover {
            border-color: rgba(204,17,17,0.06);
            background: rgba(204,17,17,0.01);
            transform: translateX(4px);
        }

        /* ===== PLANS ===== */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .plan-card {
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.5s var(--ease-out-expo);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            backdrop-filter: blur(20px);
        }
        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gradient-crimson);
            transform: scaleX(0);
            transition: transform 0.5s var(--ease-out-expo);
        }
        .plan-card:hover::before { transform: scaleX(1); }
        .plan-card:hover {
            transform: translateY(-6px);
            border-color: rgba(204,17,17,0.04);
            box-shadow: 0 30px 80px rgba(0,0,0,0.08);
        }
        .plan-card.featured {
            border-color: rgba(204,17,17,0.04);
            background: linear-gradient(180deg, rgba(204,17,17,0.01), rgba(255,255,255,0.005));
        }
        .plan-card.featured::before { transform: scaleX(1); }
        .plan-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--gradient-crimson);
            color: white;
            padding: 0.2rem 0.7rem;
            border-radius: 99px;
            font-size: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            box-shadow: 0 4px 20px rgba(204,17,17,0.06);
        }
        .plan-name {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 600;
        }
        .plan-price {
            font-family: var(--font-mono);
            font-size: 1.8rem;
            font-weight: 500;
        }
        .plan-price .currency {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            font-weight: 400;
        }
        .plan-price .period {
            font-size: 0.65rem;
            color: var(--text-tertiary);
            font-weight: 400;
        }
        .plan-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.02), transparent);
            margin: 1rem 0;
        }
        .plan-features {
            list-style: none;
            margin-bottom: 1.5rem;
        }
        .plan-features li {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.3rem 0;
            font-size: 0.78rem;
            color: var(--text-secondary);
            font-weight: 300;
        }
        .plan-features li i {
            color: var(--accent-crimson);
            font-size: 0.5rem;
            width: 14px;
            text-align: center;
            opacity: 0.4;
        }
        .plan-btn {
            width: 100%;
            padding: 0.8rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.02);
            background: rgba(255,255,255,0.005);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.4s var(--ease-out-expo);
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.02em;
        }
        .plan-btn:hover {
            background: var(--gradient-crimson);
            border-color: var(--accent-crimson);
            color: white;
            box-shadow: 0 4px 30px rgba(204,17,17,0.06);
        }
        .plan-card.featured .plan-btn {
            background: var(--gradient-crimson);
            border-color: var(--accent-crimson);
            color: white;
        }

        /* ===== INLINE PAYMENT SECTION ===== */
        .crypto-payment-section {
            margin-top: 3rem;
            padding: 2.5rem;
            background: rgba(255,255,255,0.01);
            border: 1px solid rgba(255,255,255,0.02);
            border-radius: 24px;
            backdrop-filter: blur(40px);
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.6s var(--ease-out-expo);
        }
        .crypto-payment-section:hover {
            border-color: rgba(255,255,255,0.03);
        }
        .payment-form-inline {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .email-input {
            width: 100%;
            padding: 0.8rem 1.2rem;
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.8rem;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .email-input:focus {
            border-color: rgba(204,17,17,0.06);
            box-shadow: 0 0 30px rgba(204,17,17,0.01);
        }
        .crypto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.8rem;
        }
        .crypto-option {
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 10px;
            padding: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            font-size: 0.72rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            text-align: center;
            font-family: 'Inter', sans-serif;
        }
        .crypto-option:hover {
            border-color: rgba(204,17,17,0.06);
            background: rgba(204,17,17,0.01);
            color: var(--text-primary);
        }

        /* ===== COMPARE ===== */
        .compare-table-wrap {
            max-width: 1100px;
            margin: 0 auto;
            overflow-x: auto;
        }
        .compare-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 650px;
        }
        .compare-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.55rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--text-tertiary);
            border-bottom: 1px solid rgba(255,255,255,0.015);
            font-family: var(--font-mono);
        }
        .compare-table td {
            padding: 0.9rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.01);
            font-size: 0.78rem;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            font-weight: 300;
        }
        .compare-table tr:hover td { background: rgba(255,255,255,0.005); }
        .compare-table .highlight-col {
            background: rgba(204,17,17,0.01);
            border-left: 1px solid rgba(204,17,17,0.02);
            border-right: 1px solid rgba(204,17,17,0.02);
        }
        .compare-table th.highlight-col {
            background: rgba(204,17,17,0.015);
            color: var(--accent-crimson);
            font-weight: 600;
        }
        .compare-check { color: var(--accent-crimson); font-size: 0.75rem; }
        .compare-x { color: var(--text-muted); font-size: 0.75rem; }

        /* ===== METHODS SECTION (NEW) ===== */
        .methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .method-card {
            background: var(--bg-glass);
            backdrop-filter: blur(30px);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s var(--ease-out-expo);
        }
        .method-card:hover {
            border-color: var(--border-crimson);
            transform: translateY(-4px);
        }
        .method-card .method-name {
            font-family: var(--font-mono);
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-crimson);
            margin-bottom: 0.5rem;
        }
        .method-card .method-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .method-category {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 99px;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            background: rgba(204,17,17,0.1);
            color: var(--accent-crimson);
        }

        /* ===== FAQ ===== */
        .faq-container { max-width: 760px; margin: 0 auto; }
        .faq-item {
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 16px;
            margin-bottom: 0.6rem;
            overflow: hidden;
            transition: all 0.4s var(--ease-out-expo);
            backdrop-filter: blur(20px);
        }
        .faq-item:hover { border-color: rgba(204,17,17,0.02); }
        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-primary);
        }
        .faq-question:hover { color: var(--accent-crimson); }
        .faq-question i {
            transition: transform 0.4s var(--ease-out-expo);
            color: var(--text-tertiary);
            font-size: 0.65rem;
        }
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
            color: var(--accent-crimson);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s var(--ease-out-expo), padding 0.4s ease;
        }
        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 1.5rem 1.5rem;
        }
        .faq-answer p {
            color: var(--text-secondary);
            line-height: 1.9;
            font-size: 0.82rem;
            font-weight: 300;
        }

        /* ===== ORBIT ===== */
        .orbit-section {
            position: relative;
            padding: 120px 2rem;
            text-align: center;
            overflow: hidden;
            z-index: 10;
        }
        .orbit-circle {
            position: absolute;
            width: 500px;
            height: 500px;
            border: 1px dashed rgba(204,17,17,0.03);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            pointer-events: none;
            z-index: 0;
            animation: spinOrbit 50s linear infinite;
        }
        .orbit-square {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(204,17,17,0.02);
            transform: translate(-50%,-50%);
        }
        .orbit-square:nth-child(1) { top:0; left:50%; }
        .orbit-square:nth-child(2) { top:50%; right:0; }
        .orbit-square:nth-child(3) { bottom:0; left:50%; }
        .orbit-square:nth-child(4) { top:50%; left:0; }
        .orbit-square:nth-child(5) { top:20%; right:20%; }
        .orbit-square:nth-child(6) { bottom:20%; left:20%; }
        @keyframes spinOrbit {
            from { transform: translate(-50%,-50%) rotate(0deg); }
            to { transform: translate(-50%,-50%) rotate(360deg); }
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--bg-deep);
            border-top: 1px solid rgba(255,255,255,0.01);
            padding: 80px 2rem 40px;
            position: relative;
            z-index: 10;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto 4rem;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }
        .footer-brand-icon {
            width: 32px;
            height: 32px;
            background: var(--gradient-crimson);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 0 20px rgba(204,17,17,0.04);
        }
        .footer-brand-text {
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 600;
        }
        .footer-desc {
            color: var(--text-tertiary);
            font-size: 0.75rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            max-width: 300px;
            font-weight: 300;
        }
        .footer-social {
            display: flex;
            gap: 0.5rem;
        }
        .footer-social a {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-tertiary);
            text-decoration: none;
            transition: all 0.4s var(--ease-out-expo);
            font-size: 0.75rem;
        }
        .footer-social a:hover {
            border-color: rgba(204,17,17,0.06);
            color: var(--accent-crimson);
            transform: translateY(-2px);
        }
        .footer-col h4 {
            font-size: 0.55rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-family: var(--font-mono);
        }
        .footer-col a {
            display: block;
            color: var(--text-tertiary);
            text-decoration: none;
            font-size: 0.75rem;
            margin-bottom: 0.7rem;
            transition: all 0.3s var(--ease-out-expo);
            font-weight: 300;
        }
        .footer-col a:hover {
            color: var(--accent-crimson);
            padding-left: 4px;
        }
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.01);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-copyright {
            font-size: 0.6rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            letter-spacing: 0.08em;
        }
        .footer-links {
            display: flex;
            gap: 2rem;
        }
        .footer-links a {
            font-size: 0.6rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
            font-family: var(--font-mono);
            letter-spacing: 0.08em;
        }
        .footer-links a:hover { color: var(--accent-crimson); }

        /* ===== STATUS ===== */
        .status-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(60px);
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .status-container {
            max-width: 500px;
            width: 100%;
            text-align: center;
            padding: 3rem 2.5rem;
            background: rgba(6,6,6,0.95);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s var(--ease-out-expo);
            backdrop-filter: blur(60px);
        }
        .status-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gradient-crimson);
            box-shadow: 0 0 30px rgba(204,17,17,0.1);
        }
        .status-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.6rem;
        }
        .status-icon.success {
            color: #22c55e;
            border-color: rgba(34,197,94,0.05);
            box-shadow: 0 0 30px rgba(34,197,94,0.02);
        }
        .status-icon.pending {
            color: #f59e0b;
            border-color: rgba(245,158,11,0.05);
            box-shadow: 0 0 30px rgba(245,158,11,0.02);
            animation: spin 2s linear infinite;
        }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .status-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
        }
        .status-desc {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 0.82rem;
            line-height: 1.7;
            font-weight: 300;
        }
        .credentials-box {
            background: rgba(255,255,255,0.005);
            border: 1px solid rgba(255,255,255,0.01);
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.01);
        }
        .credential-row:last-child { border-bottom: none; }
        .credential-label {
            font-size: 0.65rem;
            color: var(--text-tertiary);
            font-family: var(--font-mono);
            letter-spacing: 0.08em;
        }
        .credential-value {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-primary);
            background: rgba(255,255,255,0.005);
            padding: 0.2rem 0.7rem;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.01);
        }

        /* ===== REVEAL ===== */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            filter: blur(4px);
            transition: all 1s var(--ease-out-expo);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }

        /* ===== BACK TO TOP ===== */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 9999;
            width: 40px;
            height: 40px;
            background: var(--gradient-crimson);
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 4px 30px rgba(204,17,17,0.04);
            transition: all 0.4s var(--ease-out-expo);
            opacity: 0;
            transform: scale(0.8);
        }
        .back-to-top.visible {
            opacity: 1;
            transform: scale(1);
        }
        .back-to-top:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 40px rgba(204,17,17,0.08);
        }

        /* ===== SCROLL PROGRESS ===== */
        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 1px;
            background: var(--gradient-crimson);
            z-index: 9999;
            transition: width 0.1s linear;
            box-shadow: 0 0 30px rgba(204,17,17,0.15);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); filter: blur(4px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .crypto-grid-showcase { grid-template-columns: 1fr; gap: 3rem; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
            .navbar { padding: 0 1.5rem; }
            .nav-links, .nav-auth, .lang-switch { display: none; }
            .mobile-menu-btn { display: block; }
            .suite-carousel { flex-direction: column; align-items: center; }
            .suite-card { max-width: 100%; }
            .grid-squares { grid-template-columns: repeat(8, 1fr); grid-template-rows: repeat(8, 1fr); }
            .hero-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .hero-stats { gap: 2rem; }
            .hero-stat::after { display: none; }
            .hero-actions { flex-direction: column; width: 100%; }
            .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
            .plans-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .crypto-grid { grid-template-columns: 1fr; }
            .crypto-list { grid-template-columns: 1fr; }
            .section { padding: 80px 1.2rem; }
            .hero { padding: 120px 1.2rem 80px; }
            .suite-card { padding: 2rem 1.5rem; }
            .compare-table { min-width: 450px; }
            .grid-squares { grid-template-columns: repeat(6, 1fr); grid-template-rows: repeat(6, 1fr); }
            .mobile-nav-overlay a, .mobile-nav-overlay button { font-size: 1.1rem; }
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
    <div id="scroll-progress"></div>

    <!-- ===== CURSOR ===== -->
    <div class="cursor-dot" id="cursor"></div>

    <!-- ===== BACK TO TOP ===== -->
    <button class="back-to-top" id="backToTop" onclick="window.lenis.scrollTo(0, {duration:1.5})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ===== NAVBAR ===== -->
    <!-- Navbar -->
<nav class="navbar" id="navbar">
    <a href="dashboard.php" class="nav-brand">
        <img src="image.jpg" alt="KillByte Solutions" style="height:32px; width:auto; border-radius:8px;">
        <div class="nav-brand-text">KillByte Solutions</div>
    </a>
        <div class="nav-links">
            <a href="#features" class="nav-link" data-i18n="features">Features</a>
            <a href="#crypto" class="nav-link" data-i18n="payments">Payments</a>
            <a href="#methods" class="nav-link" data-i18n="methods">Methods</a>
            <a href="#plans" class="nav-link" data-i18n="plans">Plans</a>
            <a href="#compare" class="nav-link" data-i18n="compare">Compare</a>
            <a href="#faq" class="nav-link" data-i18n="faq">FAQ</a>
        </div>
        <div style="display:flex;align-items:center;gap:0.8rem;">
            <div class="nav-auth">
                <a href="/login" data-i18n="login">Login</a>
                <a href="/register" class="register-btn" data-i18n="register">Register</a>
            </div>
            <div class="lang-switch">
                <button class="lang-btn active" data-lang="en" onclick="switchLanguage('en')">EN</button>
                <button class="lang-btn" data-lang="zh" onclick="switchLanguage('zh')">中</button>
            </div>
            <button onclick="toggleTheme()" style="background:none;border:none;color:var(--text-secondary);font-size:0.9rem;cursor:pointer;">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <!-- ===== MOBILE NAV OVERLAY ===== -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay">
        <span class="close-mobile-menu" onclick="toggleMobileMenu()">&times;</span>
        <a href="#features" onclick="closeMobileMenu()" data-i18n="features">Features</a>
        <a href="#crypto" onclick="closeMobileMenu()" data-i18n="payments">Payments</a>
        <a href="#methods" onclick="closeMobileMenu()" data-i18n="methods">Methods</a>
        <a href="#plans" onclick="closeMobileMenu()" data-i18n="plans">Plans</a>
        <a href="#compare" onclick="closeMobileMenu()" data-i18n="compare">Compare</a>
        <a href="#faq" onclick="closeMobileMenu()" data-i18n="faq">FAQ</a>
        <a href="https://killbyte.cc/" target="_blank" data-i18n="panel">Panel</a>
        <div class="mobile-nav-auth">
            <a href="/login" class="login-btn" data-i18n="login">Login</a>
            <a href="/register" class="register-btn" data-i18n="register">Register</a>
        </div>
        <div class="lang-switch" style="margin-top:1rem;">
            <button class="lang-btn active" data-lang="en" onclick="switchLanguage('en')">EN</button>
            <button class="lang-btn" data-lang="zh" onclick="switchLanguage('zh')">中</button>
        </div>
        <button onclick="toggleTheme()" style="background:none;border:none;color:var(--text-secondary);font-size:1.2rem;cursor:pointer;margin-top:1rem;">
            <i class="fas fa-moon" id="themeIconMobile"></i>
        </button>
    </div>

    <!-- ===== HERO ===== -->
    <section class="hero" id="hero">
        <div class="hero-grid">
            <div>
                <div class="hero-badge">
                    <span class="pulse-dot"></span>
                    <span data-i18n="hero_badge">Enterprise L7 / L4 Testing Platform</span>
                </div>
                <h1 class="hero-title">
                    <span class="thin" data-i18n="hero_unleash">Unleash</span>
                    <span class="crimson" data-i18n="hero_550m">550M+</span><br>
                    <span class="outline" data-i18n="hero_reqs">Requests Per Second</span><br>
                    <span class="thin" data-i18n="hero_with">with</span>
                    <span class="bold" data-i18n="hero_killbyte">KillByte</span>
                </h1>
                <p class="hero-subtitle" data-i18n="hero_sub">The most powerful Layer 7 & Layer 4 stress testing platform on the market. Enterprise-grade infrastructure testing with unmatched performance, bypass methods, and absolute anonymity.</p>
                <div class="hero-stats">
                    <div class="hero-stat"><span class="hero-stat-value"><span class="counter" data-target="550">0</span>M+</span><div class="hero-stat-label" data-i18n="stat_reqs">Requests / Sec</div></div>
                    <div class="hero-stat"><span class="hero-stat-value"><span class="counter" data-target="1.2">0</span>TB</span><div class="hero-stat-label" data-i18n="stat_l4">Layer 4 Power</div></div>
                    <div class="hero-stat"><span class="hero-stat-value"><span class="counter" data-target="99.9">0</span>%</span><div class="hero-stat-label" data-i18n="stat_uptime">Uptime</div></div>
                    <div class="hero-stat"><span class="hero-stat-value"><span class="counter" data-target="15000">0</span>+</span><div class="hero-stat-label" data-i18n="stat_users">Active Users</div></div>
                </div>
                <div class="hero-actions">
                    <a href="#plans" class="btn-primary" data-i18n="btn_getstarted"><i class="fas fa-bolt"></i> Get Started</a>
                    <a href="#features" class="btn-secondary" data-i18n="btn_learnmore"><i class="fas fa-info-circle"></i> Learn More</a>
                </div>
            </div>
            <div class="globe-container">
                <div class="map-rotate">
                    <div class="globe-dots">
                        <div class="globe-scroll"></div>
                    </div>
                    <div class="globe-overlay"></div>
                </div>
                <div class="radar-ring"></div>
                <div class="radar-ring"></div>
                <div class="radar-ring"></div>
            </div>
        </div>
    </section>

    <!-- ===== MARQUEE ===== -->
    <div class="marquee-banner">
        <div class="marquee-track">
            <div class="marquee-item"><i class="fas fa-bolt"></i> 550M+ Requests Per Second</div>
            <div class="marquee-item"><i class="fas fa-shield-halved"></i> HTTP-IOS Bypass</div>
            <div class="marquee-item"><i class="fas fa-globe"></i> GEO-Bypass Enabled</div>
            <div class="marquee-item"><i class="fas fa-lock"></i> Zero Logs Policy</div>
            <div class="marquee-item"><i class="fas fa-server"></i> 1.2TB Layer 4</div>
            <div class="marquee-item"><i class="fas fa-bolt"></i> Instant Account Creation</div>
            <div class="marquee-item"><i class="fas fa-coins"></i> 11 Crypto Payments</div>
            <div class="marquee-item"><i class="fas fa-chart-line"></i> Real-time Analytics</div>
            <!-- duplicate for seamless loop -->
            <div class="marquee-item"><i class="fas fa-bolt"></i> 550M+ Requests Per Second</div>
            <div class="marquee-item"><i class="fas fa-shield-halved"></i> HTTP-IOS Bypass</div>
            <div class="marquee-item"><i class="fas fa-globe"></i> GEO-Bypass Enabled</div>
            <div class="marquee-item"><i class="fas fa-lock"></i> Zero Logs Policy</div>
            <div class="marquee-item"><i class="fas fa-server"></i> 1.2TB Layer 4</div>
            <div class="marquee-item"><i class="fas fa-bolt"></i> Instant Account Creation</div>
            <div class="marquee-item"><i class="fas fa-coins"></i> 11 Crypto Payments</div>
            <div class="marquee-item"><i class="fas fa-chart-line"></i> Real-time Analytics</div>
        </div>
    </div>

    <!-- ===== FEATURES ===== -->
    <section class="section" id="features">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="features_label">Features</div>
            <h2 class="section-title" data-i18n="features_title">Built for <span class="accent">Domination</span></h2>
            <p class="section-desc" data-i18n="features_desc">Enterprise-grade power with zero compromise.</p>
        </div>
        <div class="typewriter-wrapper" id="typewriterTarget">
            <span data-i18n="typewriter_text">550M+ requests per second · 1.2TB L4 · GEO-Bypass · HTTP-IOS</span>
            <span class="typewriter-cursor"></span>
        </div>
    </section>

    <!-- ===== REDESIGNED ULTIMATE TESTING SUITE ===== -->
    <section class="suite-section" id="suite">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="suite_label">The Suite</div>
            <h2 class="section-title" data-i18n="suite_title">Ultimate <span class="accent">Testing Suite</span></h2>
            <p class="section-desc" data-i18n="suite_desc">Everything you need to evaluate infrastructure resilience.</p>
        </div>
        <div class="suite-carousel">
            <div class="suite-card" data-delay="0">
                <div class="float-icon"><i class="fas fa-rocket"></i></div>
                <div class="icon-wrap"><i class="fas fa-bolt"></i></div>
                <h4 data-i18n="suite_card1_title">Instant Setup</h4>
                <p data-i18n="suite_card1_desc">Get started in under 60 seconds with automated account creation and instant API key generation.</p>
                <div class="glow-line"></div>
            </div>
            <div class="suite-card" data-delay="100">
                <div class="float-icon"><i class="fas fa-shield-halved"></i></div>
                <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
                <h4 data-i18n="suite_card2_title">Advanced Bypass</h4>
                <p data-i18n="suite_card2_desc">HTTP-IOS & GEO-Bypass methods that defeat captcha, WAF, and geo-restrictions with ease.</p>
                <div class="glow-line"></div>
            </div>
            <div class="suite-card" data-delay="200">
                <div class="float-icon"><i class="fas fa-chart-line"></i></div>
                <div class="icon-wrap"><i class="fas fa-chart-pie"></i></div>
                <h4 data-i18n="suite_card3_title">Live Analytics</h4>
                <p data-i18n="suite_card3_desc">Real-time performance metrics, request logs, and traffic visualization for deep insights.</p>
                <div class="glow-line"></div>
            </div>
            <div class="suite-card" data-delay="300">
                <div class="float-icon"><i class="fas fa-globe"></i></div>
                <div class="icon-wrap"><i class="fas fa-network-wired"></i></div>
                <h4 data-i18n="suite_card4_title">Global Nodes</h4>
                <p data-i18n="suite_card4_desc">150+ nodes worldwide delivering unmatched speed, low latency, and distributed testing power.</p>
                <div class="glow-line"></div>
            </div>
        </div>
    </section>

    <!-- ===== METHODS SECTION (NEW) ===== -->
    <section class="section" id="methods">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="methods_label">Attack Vectors</div>
            <h2 class="section-title" data-i18n="methods_title">Full <span class="accent">Arsenal</span></h2>
            <p class="section-desc" data-i18n="methods_desc">Every method tuned for maximum impact. Access tiered by plan.</p>
        </div>
        <div class="methods-grid">
            <?php
            $methods = [
                'Private' => [
                    ['!c-flood', 'Massive flood attack – 67M RPS, full backend/CDN bypass.'],
                    ['!c-browser', 'Browser traffic sim – JS exec + TLS fp random + behav emul.'],
                    ['!c-bypass', 'High RPS method with advanced CDN bypass capabilities.']
                ],
                'VIP' => [
                    ['!overload', 'HTTP flood attack – 12M RPS instantly overwhelms backend & CDN.'],
                    ['!rapidreset', 'Session resetter – drops HTTP sessions via TCP RST.'],
                    ['!http-exploit', 'Protocol abuse engine – malformed requests crash parsers.'],
                    ['!spectre', 'JS bypass – simulates browser logic to evade anti-bot defenses.'],
                    ['!h-flood', 'Hybrid HTTP flood – mixed GET/POST/HEAD + HTTP/2 streams.'],
                    ['!ovh', 'OVH bypass – breaks OVH game & VAC firewall stacks.'],
                    ['!udp', 'Raw UDP flood – 1.5M+ PPS for bandwidth saturation.'],
                    ['!tcp', 'SYN/RST flooder – rapidly crashes port handlers.']
                ],
                'Non-VIP' => [
                    ['!dns', 'DNS spammer – floods resolvers with random queries.'],
                    ['!browser', 'Basic browser flood – GET/HEAD spoofing with rotating headers.'],
                    ['!floodcore', 'Standard HTTP flood – reliable POST/GET for weak endpoints.'],
                    ['!game', 'Game disruptor – injects latency and crashes real-time sessions.']
                ]
            ];
            foreach ($methods as $category => $items):
            ?>
            <div class="method-card reveal">
                <div class="method-category"><?php echo $category; ?> Access</div>
                <?php foreach ($items as $item): ?>
                <div class="method-name"><?php echo $item[0]; ?></div>
                <div class="method-desc"><?php echo $item[1]; ?></div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

  <!-- ===== CRYPTO ===== -->
<section class="section" id="crypto">
    <div class="section-header reveal">
        <div class="section-label" data-i18n="crypto_label">Payments</div>
        <h2 class="section-title" data-i18n="crypto_title">We Accept <span class="accent">All Crypto</span></h2>
        <p class="section-desc" data-i18n="crypto_desc">Secure, anonymous payments with instant activation. 11+ cryptocurrencies supported.</p>
    </div>
    <div class="crypto-grid-showcase">
        <div class="crypto-visual reveal">
            <div class="glass-panel" style="border-radius:24px;overflow:hidden;padding:0;">
                <video autoplay loop muted playsinline style="width:100%;display:block;object-fit:cover;">
                    <source src="currency.mp4" type="video/mp4">
                </video>
            </div>
        </div>
        <div class="crypto-text reveal">
            <h3 data-i18n="crypto_heading">Anonymous <strong>Crypto Payments.</strong></h3>
            <p data-i18n="crypto_p1">Pay securely with <strong>Bitcoin, Ethereum, Solana, TRON, Litecoin, Binance Coin, Ripple, Polygon</strong>, and USDT on TRC20, ERC20, and BEP20 networks. All transactions are processed through our secure Oxapay gateway with instant confirmation and automatic account creation.</p>
            <p data-i18n="crypto_p2">No KYC required. No personal information stored. Just pure anonymity with military-grade encryption.</p>
            <div class="crypto-list">
                <?php foreach ($cryptocurrencies as $code => $name): ?>
                <div class="crypto-item"><i class="fas fa-coin"></i> <?php echo htmlspecialchars($name); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
    <!-- ===== PLANS ===== -->
    <section class="section" id="plans">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="plans_label">Pricing</div>
            <h2 class="section-title" data-i18n="plans_title">Choose Your <span class="accent">Power Level</span></h2>
            <p class="section-desc" data-i18n="plans_desc">From entry-level testing to enterprise-grade destruction. Select the plan that matches your needs.</p>
        </div>
        <div class="plans-grid">
            <?php foreach ($plans as $planId => $plan): ?>
            <div class="plan-card <?php echo $plan['vip'] ? 'featured' : ''; ?> reveal">
                <?php if ($plan['vip']): ?>
                <div class="plan-badge" data-i18n="plan_badge_vip">VIP</div>
                <?php endif; ?>
                <div class="plan-name"><?php echo htmlspecialchars($plan['name']); ?></div>
                <div class="plan-price">
                    <span class="currency">$</span><?php echo number_format($plan['price'], 0); ?>
                    <span class="period" data-i18n="plan_period">/month</span>
                </div>
                <div class="plan-divider"></div>
                <ul class="plan-features">
                    <?php foreach ($plan['features'] as $feature): ?>
                    <li>
                        <?php if (strpos($feature, 'Non-VIP') !== false): ?>
                        <i class="fas fa-xmark"></i>
                        <?php else: ?>
                        <i class="fas fa-check"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars(str_replace(['VIP Access', 'Non-VIP'], ['VIP', 'Non-VIP'], $feature)); ?>
                    </li>
                    <?php endforeach; ?>
                    <li><i class="fas fa-check"></i> <?php echo $plan['max_conc']; ?> <?php echo $plan['max_conc'] > 1 ? 'slots' : 'slot'; ?></li>
                    <li><i class="fas fa-check"></i> <?php echo number_format($plan['max_time']); ?>s max</li>
                    <li><i class="fas fa-check"></i> 30 Days access</li>
                </ul>
                <button class="plan-btn" onclick="selectPlan('<?php echo $planId; ?>')">
                    <span data-i18n="plan_select">Select Plan</span>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- INLINE CRYPTO PAYMENT SECTION -->
        <div id="cryptoPaymentSection" class="crypto-payment-section" style="display:none;">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="payment-form-inline">
                <input type="hidden" name="plan" id="selectedPlanInput">
                <input type="email" name="email" placeholder="Enter your email (optional)" class="email-input">
                <div class="crypto-grid">
                    <?php foreach ($cryptocurrencies as $code => $name): ?>
                    <button type="submit" name="crypto" value="<?php echo htmlspecialchars($code); ?>" class="crypto-option">
                        <?php echo htmlspecialchars($name); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </section>

    <!-- ===== COMPARE ===== -->
    <section class="section" id="compare">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="compare_label">Comparison</div>
            <h2 class="section-title" data-i18n="compare_title">KillByte vs <span class="accent">The Competition</span></h2>
            <p class="section-desc" data-i18n="compare_desc">See why KillByte dominates the stress testing market with superior performance and value.</p>
        </div>
        <div class="compare-table-wrap reveal">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th data-i18n="compare_feature">Feature</th>
                        <th class="highlight-col" data-i18n="compare_killbyte">KillByte Solutions</th>
                        <th data-i18n="compare_compA">Competitor A</th>
                        <th data-i18n="compare_compB">Competitor B</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td data-i18n="compare_row1">Layer 7 Requests/sec</td><td class="highlight-col"><strong style="color:var(--accent-crimson);">550M+</strong></td><td>~50M</td><td>~120M</td></tr>
                    <tr><td data-i18n="compare_row2">Layer 4 Throughput</td><td class="highlight-col"><strong style="color:var(--accent-crimson);">1.2 TB/s</strong></td><td>~200 GB/s</td><td>~500 GB/s</td></tr>
                    <tr><td data-i18n="compare_row3">HTTP-IOS Bypass</td><td class="highlight-col"><i class="fas fa-check compare-check"></i></td><td><i class="fas fa-xmark compare-x"></i></td><td><i class="fas fa-xmark compare-x"></i></td></tr>
                    <tr><td data-i18n="compare_row4">GEO-Bypass</td><td class="highlight-col"><i class="fas fa-check compare-check"></i></td><td><i class="fas fa-xmark compare-x"></i></td><td><i class="fas fa-check compare-check" style="color:var(--text-tertiary);"></i></td></tr>
                    <tr><td data-i18n="compare_row5">Zero Logs Policy</td><td class="highlight-col"><i class="fas fa-check compare-check"></i></td><td><i class="fas fa-xmark compare-x"></i></td><td><i class="fas fa-xmark compare-x"></i></td></tr>
                    <tr><td data-i18n="compare_row6">Crypto Payments</td><td class="highlight-col"><i class="fas fa-check compare-check"></i> 11 Coins</td><td><i class="fas fa-check compare-check" style="color:var(--text-tertiary);"></i> 3 Coins</td><td><i class="fas fa-check compare-check" style="color:var(--text-tertiary);"></i> 5 Coins</td></tr>
                    <tr><td data-i18n="compare_row7">Auto Account Creation</td><td class="highlight-col"><i class="fas fa-check compare-check"></i></td><td><i class="fas fa-xmark compare-x"></i></td><td><i class="fas fa-xmark compare-x"></i></td></tr>
                    <tr><td data-i18n="compare_row8">Max Concurrent Slots</td><td class="highlight-col"><strong style="color:var(--accent-crimson);">500</strong></td><td>50</td><td>100</td></tr>
                    <tr><td data-i18n="compare_row9">Max Attack Duration</td><td class="highlight-col"><strong style="color:var(--accent-crimson);">280,000s</strong></td><td>3,600s</td><td>10,800s</td></tr>
                    <tr><td data-i18n="compare_row10">Starting Price</td><td class="highlight-col"><strong style="color:var(--accent-crimson);">$10</strong></td><td>$15</td><td>$20</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ===== ORBIT ===== -->
    <section class="orbit-section reveal">
        <div class="orbit-circle">
            <div class="orbit-square"></div><div class="orbit-square"></div><div class="orbit-square"></div>
            <div class="orbit-square"></div><div class="orbit-square"></div><div class="orbit-square"></div>
        </div>
        <div style="position:relative;z-index:1;max-width:600px;margin:0 auto;">
            <h2 class="section-title" data-i18n="orbit_title">The <span class="accent">Future</span> of Testing</h2>
            <p class="section-desc" data-i18n="orbit_desc">Join the elite network of security professionals who trust KillByte for their infrastructure validation.</p>
        </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section class="section" id="faq">
        <div class="section-header reveal">
            <div class="section-label" data-i18n="faq_label">FAQ</div>
            <h2 class="section-title" data-i18n="faq_title">Frequently Asked <span class="accent">Questions</span></h2>
            <p class="section-desc" data-i18n="faq_desc">Everything you need to know about KillByte Solutions before getting started.</p>
        </div>
        <div class="faq-container">
            <?php
            $faqs = [
                ['q' => 'What is KillByte Solutions and how powerful is it?', 'a' => 'KillByte Solutions is the most powerful Layer 7 and Layer 4 stress testing platform available. Our infrastructure delivers <strong>550+ million requests per second</strong> on Layer 7 and <strong>1.2 terabytes per second</strong> on Layer 4. With 150+ global nodes, advanced bypass methods like HTTP-IOS and GEO-BYPASS, and zero logs policy, KillByte represents the absolute pinnacle of network testing technology.'],
                ['q' => 'How does the payment and account creation work?', 'a' => 'Simply select your desired plan, choose a cryptocurrency payment method, and complete the transaction via our secure Oxapay gateway. Once payment is confirmed (usually within minutes), your account is <strong>automatically created</strong> with randomized credentials. You\'ll receive your username and password instantly — no manual setup required.'],
                ['q' => 'What bypass methods does KillByte support?', 'a' => 'KillByte supports industry-leading bypass methods including <strong>HTTP-IOS</strong> (bypasses iOS verification and mobile challenges) and <strong>GEO-BYPASS</strong> (routes through 150+ global nodes to bypass geo-restrictions). Our methods can pass through captcha-protected websites, geo-blocked services, and WAF-protected targets with amazing strength and speed.'],
                ['q' => 'Is my usage anonymous and secure?', 'a' => '<strong>Absolutely.</strong> KillByte operates a strict zero-logs policy. We do not store, monitor, or log any of your testing activity. The only data we retain is your username and password for account access. All payments are processed through cryptocurrency, ensuring complete anonymity. Your attacks are 100% anonymous and safe.'],
                ['q' => 'What cryptocurrencies are accepted for payment?', 'a' => 'We accept <strong>11 cryptocurrencies</strong>: Bitcoin (BTC), Ethereum (ETH), Solana (SOL), TRON (TRX), Litecoin (LTC), Binance Coin (BNB), Ripple (XRP), Polygon (POL), and USDT on TRC20, ERC20, and BEP20 networks. All payments are processed securely through Oxapay with instant confirmation.'],
                ['q' => 'How long does it take to activate my account?', 'a' => 'Account activation is <strong>fully automated</strong>. Once your cryptocurrency payment is confirmed on the blockchain (typically 1-3 confirmations depending on the coin), your account is created instantly with randomized credentials. The entire process from payment to access usually takes under 5 minutes.'],
                ['q' => 'Can I upgrade my plan later?', 'a' => 'Yes, you can upgrade your plan at any time. Simply contact our support team or purchase a new plan and the difference will be prorated. Your account credentials remain the same.'],
                ['q' => 'Is there a free trial available?', 'a' => 'We do not offer a free trial, but we provide a <strong>money-back guarantee</strong> within 24 hours of purchase if you are not satisfied with the service. Please contact support for refund requests.'],
                ['q' => 'What is the maximum attack duration?', 'a' => 'The maximum attack duration depends on your plan. Our <strong>KillByte Plan</strong> allows up to <strong>280,000 seconds</strong> (about 3.2 days) of continuous testing. Lower plans have shorter durations.'],
                ['q' => 'Do you provide API access?', 'a' => 'Yes, all VIP plans include API access. You can integrate our testing capabilities into your own applications using our RESTful API. Documentation is available upon request.'],
                ['q' => 'How many concurrent attacks can I run?', 'a' => 'Concurrent slots range from 1 (Basic) up to 500 (KillByte Plan). You can run multiple attacks simultaneously based on your plan\'s slot limit.'],
                ['q' => 'Is there a limit on the number of targets?', 'a' => 'You can test any number of targets, but you can only attack one target per slot at a time. With more slots, you can test multiple targets concurrently.']
            ];
            foreach ($faqs as $faq):
            ?>
            <div class="faq-item reveal">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span><?php echo htmlspecialchars($faq['q']); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p><?php echo $faq['a']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="section" id="cta" style="text-align:center;">
        <div class="cta-content reveal" style="max-width:600px;margin:0 auto;">
            <h2 class="section-title" data-i18n="cta_title">Ready to Experience <span class="accent">True Power?</span></h2>
            <p class="section-desc" data-i18n="cta_desc">Join 15,000+ users who trust KillByte for their infrastructure testing needs. Get started in under 60 seconds with instant crypto payments and automated account creation.</p>
            <div class="hero-actions" style="justify-content:center;margin-top:2rem;">
                <a href="#plans" class="btn-primary" data-i18n="cta_btn"><i class="fas fa-bolt"></i> Get Started Now</a>
                <a href="https://t.me/KillByte_Support_Bot" target="_blank" class="btn-secondary" data-i18n="cta_support"><i class="fab fa-telegram"></i> Contact Support</a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <div class="footer-brand-icon">◈</div>
                    <div class="footer-brand-text">KillByteSolutions</div>
                </div>
                <p class="footer-desc" data-i18n="footer_desc">Enterprise-grade Layer 7 & Layer 4 stress testing platform. 550M+ requests/sec, 1.2TB Layer 4 power, zero logs policy.</p>
                <div class="footer-social">
                    <a href="https://t.me/KillByte_Support_Bot" target="_blank"><i class="fab fa-telegram"></i></a>
                    <a href="https://t.me/+eF2cU5fVUxU3ZDcx" target="_blank"><i class="fab fa-telegram-plane"></i></a>
                    <a href="https://killbyte.cc/" target="_blank"><i class="fas fa-globe"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4 data-i18n="footer_platform">Platform</h4>
                <a href="#features" data-i18n="footer_about">About</a>
                <a href="#plans" data-i18n="footer_pricing">Pricing</a>
                <a href="#compare" data-i18n="footer_compare">Compare</a>
            </div>
            <div class="footer-col">
                <h4 data-i18n="footer_resources">Resources</h4>
                <a href="#faq" data-i18n="footer_faq">FAQ</a>
                <a href="https://killbyte.cc/" target="_blank" data-i18n="footer_panel">Panel</a>
                <a href="https://killbyte.cc/tos" target="_blank" data-i18n="footer_tos">Terms of Service</a>
            </div>
            <div class="footer-col">
                <h4 data-i18n="footer_support">Support</h4>
                <a href="https://t.me/KillByte_Support_Bot" target="_blank" data-i18n="footer_telegram">Telegram Support</a>
                <a href="https://t.me/+eF2cU5fVUxU3ZDcx" target="_blank" data-i18n="footer_channel">Channel</a>
                <a href="https://t.me/KillBytePowerShow" target="_blank" data-i18n="footer_proofs">Power Proofs</a>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copyright">© 2026 KillByte Solutions. All rights reserved.</div>
            <div class="footer-links">
                <a href="https://killbyte.cc/tos" target="_blank" data-i18n="footer_tos_link">Terms of Service</a>
                <a href="#" data-i18n="footer_privacy">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <!-- ===== STATUS OVERLAYS ===== -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success' && isset($_SESSION['credentials'])): ?>
    <div class="status-overlay">
        <div class="status-container">
            <div class="status-icon success"><i class="fas fa-check"></i></div>
            <h2 class="status-title" data-i18n="status_success">Payment Successful!</h2>
            <p class="status-desc" data-i18n="status_desc">Your account has been created automatically. Save these credentials securely.</p>
            <div class="credentials-box">
                <div class="credential-row"><span class="credential-label" data-i18n="status_plan">Plan</span><span class="credential-value"><?php echo htmlspecialchars($_SESSION['credentials']['plan']); ?></span></div>
                <div class="credential-row"><span class="credential-label" data-i18n="status_username">Username</span><span class="credential-value"><?php echo htmlspecialchars($_SESSION['credentials']['username']); ?></span></div>
                <div class="credential-row"><span class="credential-label" data-i18n="status_password">Password</span><span class="credential-value"><?php echo htmlspecialchars($_SESSION['credentials']['password']); ?></span></div>
            </div>
            <a href="/login" class="btn-primary" style="display:inline-flex;"><i class="fas fa-sign-in-alt"></i> Login to Panel</a>
            <?php unset($_SESSION['credentials']); ?>
        </div>
    </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'pending'): ?>
    <div class="status-overlay">
        <div class="status-container">
            <div class="status-icon pending"><i class="fas fa-spinner"></i></div>
            <h2 class="status-title" data-i18n="status_pending">Payment Pending</h2>
            <p class="status-desc" data-i18n="status_pending_desc">Please complete your cryptocurrency payment. Your account will be created automatically once confirmed.</p>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-primary" style="display:inline-flex;"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>
    <?php endif; ?>

    <script>
     const translations = {
    en: {
        features: 'Features', payments: 'Payments', methods: 'Methods', plans: 'Plans', compare: 'Compare',
        faq: 'FAQ', panel: 'Panel', login: 'Login', register: 'Register',
        hero_badge: 'Enterprise L7 / L4 Testing Platform',
        hero_unleash: 'Unleash', hero_550m: '550M+', hero_reqs: 'Requests Per Second',
        hero_with: 'with', hero_killbyte: 'KillByte',
        hero_sub: 'The most powerful Layer 7 & Layer 4 stress testing platform on the market. Enterprise-grade infrastructure testing with unmatched performance, bypass methods, and absolute anonymity.',
        stat_reqs: 'Requests / Sec', stat_l4: 'Layer 4 Power', stat_uptime: 'Uptime',
        stat_users: 'Active Users',
        btn_getstarted: 'Get Started', btn_learnmore: 'Learn More',
        features_label: 'Features', features_title: 'Built for Domination',
        features_desc: 'Enterprise-grade power with zero compromise.',
        typewriter_text: '550M+ requests per second · 1.2TB L4 · !c-flood · !overload',
        suite_label: 'The Suite', suite_title: 'Ultimate Testing Suite',
        suite_desc: 'Everything you need to evaluate infrastructure resilience.',
        suite_card1_title: 'Instant Setup', suite_card1_desc: 'Get started in under 60 seconds with automated account creation and instant API key generation.',
        suite_card2_title: 'Advanced Bypass', suite_card2_desc: '!c-bypass & !spectre methods that defeat captcha, WAF, and geo-restrictions with ease.',
        suite_card3_title: 'Live Analytics', suite_card3_desc: 'Real-time performance metrics, request logs, and traffic visualization for deep insights.',
        suite_card4_title: 'Global Nodes', suite_card4_desc: '150+ nodes worldwide delivering unmatched speed, low latency, and distributed testing power.',
        methods_label: 'Attack Vectors', methods_title: 'Full Arsenal', methods_desc: 'Every method tuned for maximum impact. Access tiered by plan.',
        crypto_label: 'Payments', crypto_title: 'We Accept All Crypto',
        crypto_desc: 'Secure, anonymous payments with instant activation. 11+ cryptocurrencies supported.',
        crypto_heading: 'Anonymous Crypto Payments.',
        crypto_p1: 'Pay securely with Bitcoin, Ethereum, Solana, TRON, Litecoin, Binance Coin, Ripple, Polygon, and USDT on TRC20, ERC20, and BEP20 networks. All transactions are processed through our secure Oxapay gateway with instant confirmation and automatic account creation.',
        crypto_p2: 'No KYC required. No personal information stored. Just pure anonymity with military-grade encryption.',
        plans_label: 'Pricing', plans_title: 'Choose Your Power Level',
        plans_desc: 'From entry-level testing to enterprise-grade destruction. Select the plan that matches your needs.',
        plan_badge_vip: 'VIP', plan_period: '/month', plan_select: 'Select Plan',
        compare_label: 'Comparison', compare_title: 'KillByte vs The Competition',
        compare_desc: 'See why KillByte dominates the stress testing market with superior performance and value.',
        compare_feature: 'Feature', compare_killbyte: 'KillByte Solutions',
        compare_compA: 'Competitor A', compare_compB: 'Competitor B',
        compare_row1: 'Layer 7 Requests/sec', compare_row2: 'Layer 4 Throughput',
        compare_row3: '!c-flood Bypass', compare_row4: 'GEO-Bypass',
        compare_row5: 'Zero Logs Policy', compare_row6: 'Crypto Payments',
        compare_row7: 'Auto Account Creation', compare_row8: 'Max Concurrent Slots',
        compare_row9: 'Max Attack Duration', compare_row10: 'Starting Price',
        orbit_title: 'The Future of Testing',
        orbit_desc: 'Join the elite network of security professionals who trust KillByte for their infrastructure validation.',
        cta_title: 'Ready to Experience True Power?',
        cta_desc: 'Join 15,000+ users who trust KillByte for their infrastructure testing needs. Get started in under 60 seconds with instant crypto payments and automated account creation.',
        cta_btn: 'Get Started Now', cta_support: 'Contact Support',
        footer_desc: 'Enterprise-grade Layer 7 & Layer 4 stress testing platform. 550M+ requests/sec, 1.2TB Layer 4 power, zero logs policy.',
        footer_platform: 'Platform', footer_about: 'About', footer_pricing: 'Pricing',
        footer_compare: 'Compare', footer_resources: 'Resources', footer_faq: 'FAQ',
        footer_panel: 'Panel', footer_tos: 'Terms of Service', footer_support: 'Support',
        footer_telegram: 'Telegram Support', footer_channel: 'Channel',
        footer_proofs: 'Power Proofs', footer_tos_link: 'Terms of Service',
        footer_privacy: 'Privacy Policy',
        modal_title: 'Complete Your Purchase', modal_subtitle: 'Select your cryptocurrency payment method',
        modal_email: 'Enter your email (optional)', modal_pay: 'Pay Securely',
        status_success: 'Payment Successful!',
        status_desc: 'Your account has been created automatically. Save these credentials securely.',
        status_plan: 'Plan', status_username: 'Username', status_password: 'Password',
        status_pending: 'Payment Pending',
        status_pending_desc: 'Please complete your cryptocurrency payment. Your account will be created automatically once confirmed.'
    },
    zh: {
        features: '功能', payments: '支付', methods: '方法', plans: '方案', compare: '对比',
        faq: '常见问题', panel: '面板', login: '登录', register: '注册',
        hero_badge: '企业级 L7 / L4 测试平台',
        hero_unleash: '释放', hero_550m: '5.5亿+', hero_reqs: '每秒请求',
        hero_with: '通过', hero_killbyte: 'KillByte',
        hero_sub: '市场上最强大的第7层和第4层压力测试平台。企业级基础设施测试，拥有无与伦比的性能、绕过方法和绝对匿名性。',
        stat_reqs: '请求/秒', stat_l4: '第4层能力', stat_uptime: '正常运行时间',
        stat_users: '活跃用户',
        btn_getstarted: '开始使用', btn_learnmore: '了解更多',
        features_label: '功能', features_title: '为统治而生',
        features_desc: '企业级力量，零妥协。',
        typewriter_text: '5.5亿+ 请求/秒 · 1.2TB 第4层 · !c-flood · !overload',
        suite_label: '套件', suite_title: '终极测试套件',
        suite_desc: '评估基础设施弹性所需的一切。',
        suite_card1_title: '即时设置', suite_card1_desc: '在60秒内开始，自动创建账户和即时API密钥生成。',
        suite_card2_title: '高级绕过', suite_card2_desc: '!c-bypass 和 !spectre 方法，轻松击败验证码、WAF 和地理限制。',
        suite_card3_title: '实时分析', suite_card3_desc: '实时性能指标、请求日志和流量可视化，深度洞察。',
        suite_card4_title: '全球节点', suite_card4_desc: '全球150+节点提供无与伦比的速度、低延迟和分布式测试能力。',
        methods_label: '攻击向量', methods_title: '完整武器库', methods_desc: '每种方法都针对最大影响进行了调整。按计划分层访问。',
        crypto_label: '支付', crypto_title: '我们接受所有加密货币',
        crypto_desc: '安全、匿名的支付，即时激活。支持11+种加密货币。',
        crypto_heading: '匿名加密货币支付。',
        crypto_p1: '通过比特币、以太坊、Solana、TRON、莱特币、币安币、瑞波币、Polygon以及TRC20、ERC20和BEP20网络的USDT安全支付。所有交易通过我们的安全Oxapay网关处理，即时确认并自动创建账户。',
        crypto_p2: '无需KYC。不存储个人信息。纯匿名，军用级加密。',
        plans_label: '定价', plans_title: '选择您的力量等级',
        plans_desc: '从入门级测试到企业级破坏。选择适合您需求的方案。',
        plan_badge_vip: 'VIP', plan_period: '/月', plan_select: '选择方案',
        compare_label: '对比', compare_title: 'KillByte vs 竞争对手',
        compare_desc: '了解KillByte如何凭借卓越的性能和价值主导压力测试市场。',
        compare_feature: '功能', compare_killbyte: 'KillByte解决方案',
        compare_compA: '竞争对手A', compare_compB: '竞争对手B',
        compare_row1: '第7层请求/秒', compare_row2: '第4层吞吐量',
        compare_row3: '!c-flood 绕过', compare_row4: 'GEO绕过',
        compare_row5: '零日志政策', compare_row6: '加密货币支付',
        compare_row7: '自动账户创建', compare_row8: '最大并发插槽',
        compare_row9: '最大攻击持续时间', compare_row10: '起始价格',
        orbit_title: '测试的未来',
        orbit_desc: '加入信任KillByte进行基础设施验证的精英安全专业人员网络。',
        cta_title: '准备体验真正的力量？',
        cta_desc: '加入15,000+信任KillByte进行基础设施测试需求的用户。在60秒内通过即时加密货币支付和自动账户创建开始。',
        cta_btn: '立即开始', cta_support: '联系支持',
        footer_desc: '企业级第7层和第4层压力测试平台。5.5亿+请求/秒，1.2TB第4层能力，零日志政策。',
        footer_platform: '平台', footer_about: '关于', footer_pricing: '定价',
        footer_compare: '对比', footer_resources: '资源', footer_faq: '常见问题',
        footer_panel: '面板', footer_tos: '服务条款', footer_support: '支持',
        footer_telegram: 'Telegram支持', footer_channel: '频道',
        footer_proofs: '能力证明', footer_tos_link: '服务条款',
        footer_privacy: '隐私政策',
        modal_title: '完成购买', modal_subtitle: '选择您的加密货币支付方式',
        modal_email: '输入您的电子邮件（可选）', modal_pay: '安全支付',
        status_success: '支付成功！',
        status_desc: '您的账户已自动创建。请安全保存这些凭证。',
        status_plan: '方案', status_username: '用户名', status_password: '密码',
        status_pending: '支付待处理',
        status_pending_desc: '请完成您的加密货币支付。确认后您的账户将自动创建。'
    }
};
        let currentLang = 'en';

        function switchLanguage(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll(`.lang-btn[data-lang="${lang}"]`).forEach(b => b.classList.add('active'));
            document.documentElement.setAttribute('data-lang', lang);
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[lang] && translations[lang][key]) {
                    if (el.tagName === 'INPUT' && el.hasAttribute('data-i18n-placeholder')) {
                        el.placeholder = translations[lang][key];
                    } else {
                        el.textContent = translations[lang][key];
                    }
                }
            });
            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (translations[lang] && translations[lang][key]) {
                    el.placeholder = translations[lang][key];
                }
            });
        }

        // ===== LENIS SMOOTH SCROLL =====
        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // ===== GSAP =====
        gsap.registerPlugin(ScrollTrigger);

        // ===== REVEAL =====
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -60px 0px' });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // ===== SUITE CARDS ANIMATION (GSAP) =====
        gsap.utils.toArray('.suite-card').forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 0,
                y: 50,
                rotationY: 8,
                duration: 0.9,
                delay: i * 0.12,
                ease: 'power3.out'
            });
        });

        // ===== NAVBAR =====
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
            const btn = document.getElementById('backToTop');
            if (window.scrollY > 400) btn.classList.add('visible');
            else btn.classList.remove('visible');
            const progress = document.getElementById('scroll-progress');
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const value = (window.scrollY / max) * 100;
            progress.style.width = value + '%';
        });

        // ===== COUNTERS =====
        const counters = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseFloat(counter.dataset.target);
                    const duration = 2500;
                    const start = performance.now();
                    function updateCounter(currentTime) {
                        const elapsed = currentTime - start;
                        const progress = Math.min(elapsed / duration, 1);
                        const easeOut = 1 - Math.pow(1 - progress, 4);
                        const current = target * easeOut;
                        if (target >= 1000) counter.textContent = Math.floor(current).toLocaleString();
                        else if (target < 10 && target % 1 !== 0) counter.textContent = current.toFixed(1);
                        else counter.textContent = Math.floor(current);
                        if (progress < 1) requestAnimationFrame(updateCounter);
                        else {
                            if (target >= 1000) counter.textContent = Math.floor(target).toLocaleString();
                            else if (target < 10 && target % 1 !== 0) counter.textContent = target.toFixed(1);
                            else counter.textContent = Math.floor(target);
                        }
                    }
                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(counter => counterObserver.observe(counter));

        // ===== TYPEWRITER =====
        const typewriterTarget = document.getElementById('typewriterTarget');
        if (typewriterTarget) {
            const texts = [
                '550M+ requests per second · 1.2TB L4 · GEO-Bypass · HTTP-IOS',
                'Zero logs · Auto account creation · 11 Crypto payments',
                '500 concurrent slots · 280,000s attack duration · 150+ nodes'
            ];
            let idx = 0, charIdx = 0, isDeleting = false;
            let currentText = '';
            function typeLoop() {
                const fullText = texts[idx];
                if (!isDeleting) {
                    currentText = fullText.substring(0, charIdx + 1);
                    charIdx++;
                    if (charIdx === fullText.length) {
                        isDeleting = true;
                        setTimeout(typeLoop, 3000);
                        return;
                    }
                } else {
                    currentText = fullText.substring(0, charIdx - 1);
                    charIdx--;
                    if (charIdx === 0) {
                        isDeleting = false;
                        idx = (idx + 1) % texts.length;
                        setTimeout(typeLoop, 500);
                        return;
                    }
                }
                typewriterTarget.innerHTML = currentText + '<span class="typewriter-cursor"></span>';
                setTimeout(typeLoop, isDeleting ? 40 : 80);
            }
            typeLoop();
        }

        // ===== FAQ =====
        function toggleFaq(el) {
            const item = el.parentElement;
            const isActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(f => f.classList.remove('active'));
            if (!isActive) item.classList.add('active');
        }

        // ===== INLINE PLAN SELECTION =====
        function selectPlan(planId) {
            document.getElementById('selectedPlanInput').value = planId;
            const section = document.getElementById('cryptoPaymentSection');
            section.style.display = 'block';
            section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // ===== MOBILE MENU =====
        function toggleMobileMenu() {
            document.getElementById('mobileNavOverlay').classList.toggle('active');
        }
        function closeMobileMenu() {
            document.getElementById('mobileNavOverlay').classList.remove('active');
        }

        // ===== THEME =====
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
            document.querySelectorAll('a, button, .plan-card, .crypto-option, .faq-question, .suite-card').forEach(el => {
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

        // ===== INIT =====
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
                const icon = document.getElementById('themeIcon');
                if (icon) icon.className = 'fas fa-sun';
                const iconMobile = document.getElementById('themeIconMobile');
                if (iconMobile) iconMobile.className = 'fas fa-sun';
            }
            switchLanguage('en');
        });
    </script>
</body>
</html>