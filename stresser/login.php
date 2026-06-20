<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];
$captcha_question = '';
$captcha_answer = '';

// Generate math captcha question
if (!isset($_SESSION['captcha'])) {
    $num1 = rand(1, 15);
    $num2 = rand(1, 15);
    $_SESSION['captcha'] = ['q' => "$num1 + $num2", 'a' => $num1 + $num2];
}
$captcha_question = $_SESSION['captcha']['q'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $user_captcha = trim($_POST['captcha'] ?? '');

    // Validation
    if (empty($username) || empty($password) || empty($user_captcha)) {
        $errors[] = "All fields are required.";
    } else {
        // Check captcha
        if ((int)$user_captcha !== $_SESSION['captcha']['a']) {
            $errors[] = "Incorrect security answer. Please try again.";
            // Regenerate captcha after wrong attempt
            $num1 = rand(1, 15);
            $num2 = rand(1, 15);
            $_SESSION['captcha'] = ['q' => "$num1 + $num2", 'a' => $num1 + $num2];
            $captcha_question = $_SESSION['captcha']['q'];
        } else {
            // Authenticate against bus.txt
            if (file_exists('bus.txt')) {
                $users = file('bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $authenticated = false;

                foreach ($users as $key => $user) {
                    $userData = explode('|', $user);
                    // Ensure we have at least 6 fields
                    while (count($userData) < 8) $userData[] = '';
                    if (count($userData) < 6) continue;

                    if ($userData[0] === $username && $userData[1] === $password) {
                        $authenticated = true;

                        // Check account status
                        $status = $userData[6] ?? 'active';
                        if ($status === 'frozen') {
                            $errors[] = "Your account has been frozen. Please contact the administrator.";
                            $num1 = rand(1, 15);
                            $num2 = rand(1, 15);
                            $_SESSION['captcha'] = ['q' => "$num1 + $num2", 'a' => $num1 + $num2];
                            $captcha_question = $_SESSION['captcha']['q'];
                            break;
                        }

                        // Check expiry
                        $expiryDate = DateTime::createFromFormat('d-m-Y', $userData[5]);
                        $today = new DateTime();
                        if ($expiryDate && $expiryDate < $today) {
                            // Reset to free plan
                            $userData[4] = "free";
                            $userData[2] = "0";
                            $userData[3] = "0";
                            $userData[5] = "30-12-2030";
                            $users[$key] = implode('|', $userData);
                            file_put_contents('bus.txt', implode("\n", $users) . "\n");
                        }

                        // Login success
                        $_SESSION['username'] = $username;
                        $_SESSION['plan'] = $userData[4];
                        $_SESSION['expiry'] = $userData[5];
                        $_SESSION['concurrent'] = $userData[2];
                        $_SESSION['running'] = $userData[3];
                        $_SESSION['user_status'] = $status;
                        $_SESSION['warnings'] = (int)($userData[7] ?? 0);
                        $_SESSION['success'] = "Welcome back, $username! Redirecting to dashboard...";
                        break;
                    }
                }

                if (!$authenticated && empty($errors)) {
                    $errors[] = "Invalid username or password.";
                    $num1 = rand(1, 15);
                    $num2 = rand(1, 15);
                    $_SESSION['captcha'] = ['q' => "$num1 + $num2", 'a' => $num1 + $num2];
                    $captcha_question = $_SESSION['captcha']['q'];
                }
            } else {
                $errors[] = "System error: User database not found.";
            }

            // Regenerate captcha after successful validation
            if (empty($errors) && !isset($_SESSION['success'])) {
                $num1 = rand(1, 15);
                $num2 = rand(1, 15);
                $_SESSION['captcha'] = ['q' => "$num1 + $num2", 'a' => $num1 + $num2];
                $captcha_question = $_SESSION['captcha']['q'];
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
    <title>Secure Login | KillByte Solutions</title>
    <meta name="description" content="Enterprise-grade Layer 7 & Layer 4 stress testing platform. Secure access panel.">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.36/dist/lenis.min.js"></script>
    <style>
        /* ========== BASE & RESET ========== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: auto; }
        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            color: #f0f0f0;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Background Grid */
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

        /* Crimson Reflection */
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

        /* Custom Cursor */
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

        /* Main Layout - responsive luxury */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            justify-content: center;
            padding: 1.5rem;
            min-height: 100vh;
            gap: 2rem;
        }
        .auth-brand-col {
            flex: 1 1 350px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 28px;
            position: relative;
            overflow: hidden;
        }
        .auth-brand-col::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at 30% 50%, rgba(204,17,17,0.03) 0%, transparent 70%);
            z-index: 0;
        }
        .auth-form-col {
            flex: 1 1 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 28px;
        }
        @media (max-width: 900px) {
            .auth-wrapper { max-width: 500px; flex-direction: column; gap: 1rem; }
            .auth-brand-col, .auth-form-col { flex: none; width: 100%; border-radius: 24px; }
            .auth-brand-col { padding: 1.5rem; }
            .auth-form-col { padding: 1.5rem; }
        }

        /* Branding content */
        .brand-logo {
            width: 60px; height: 60px;
            background: var(--gradient-crimson);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            box-shadow: 0 15px 40px rgba(204,17,17,0.15);
            margin-bottom: 1.5rem;
            position: relative; z-index: 1;
        }
        .brand-name {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 5vw, 2.2rem);
            font-weight: 300;
            letter-spacing: -0.03em;
            line-height: 1.1;
            position: relative; z-index: 1;
        }
        .brand-name .accent {
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, #ff3333 25%, #cc1111 50%, #880a0a 75%, #ffffff 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradientShift 10s ease infinite;
        }
        @keyframes gradientShift { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .brand-subtitle {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0.5rem 0 1.5rem;
            font-weight: 300;
            position: relative; z-index: 1;
            line-height: 1.5;
        }
        .security-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1.5rem;
            position: relative; z-index: 1;
        }
        .security-badge {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 10px;
            padding: 0.8rem;
            text-align: center;
            flex: 1 1 100px;
            transition: all 0.4s ease;
        }
        .security-badge i {
            color: var(--accent-crimson);
            font-size: 1.1rem;
            margin-bottom: 0.4rem;
            display: block;
        }
        .security-badge span {
            font-size: 0.65rem;
            color: var(--text-secondary);
            font-weight: 500;
            letter-spacing: 0.03em;
        }
        .security-badge:hover {
            border-color: var(--border-crimson);
            background: rgba(204,17,17,0.02);
        }

        /* Form styles */
        .form-title {
            font-family: var(--font-display);
            font-size: clamp(1.5rem, 4vw, 1.8rem);
            font-weight: 300;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }
        .form-title .accent {
            font-weight: 600;
            color: var(--accent-crimson);
        }
        .form-group {
            margin-bottom: 1.2rem;
            position: relative;
        }
        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.03);
            color: var(--text-primary);
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.4s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--accent-crimson);
            box-shadow: 0 0 25px rgba(204,17,17,0.08);
            background: rgba(255,255,255,0.03);
        }
        .form-input::placeholder { color: var(--text-tertiary); font-weight: 300; }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.3s;
            z-index: 2;
        }
        .password-toggle:hover { color: var(--accent-crimson); }

        .captcha-group {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .captcha-label {
            background: rgba(204,17,17,0.05);
            border: 1px solid rgba(204,17,17,0.1);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-family: var(--font-mono);
            font-weight: 600;
            color: var(--accent-crimson);
            white-space: nowrap;
            font-size: 0.8rem;
        }
        .captcha-input {
            flex: 1;
            min-width: 80px;
        }

        .btn-primary {
            width: 100%;
            background: var(--gradient-crimson);
            color: white;
            padding: 0.8rem 1.8rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.4s var(--ease-out-expo);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 35px rgba(204,17,17,0.06);
            letter-spacing: 0.03em;
            font-family: 'Inter', sans-serif;
            margin-top: 0.5rem;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(204,17,17,0.12);
        }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
        .auth-footer a {
            color: var(--accent-crimson);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .auth-footer a:hover { text-shadow: 0 0 20px rgba(204,17,17,0.3); }

        /* Toast notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 22px;
            border-radius: 10px;
            background: rgba(6,6,6,0.95);
            border: 1px solid rgba(204,17,17,0.15);
            color: var(--text-primary);
            z-index: 10000;
            animation: slideIn 0.4s var(--ease-out-expo);
            backdrop-filter: blur(20px);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            max-width: 350px;
        }
        .notification.error { border-color: var(--accent-crimson); }
        .notification.success { border-color: #22c55e; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Additional luxury touches */
        .glow-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-crimson);
            transform: scaleX(0);
            transition: transform 0.6s var(--ease-out-expo);
            z-index: 1;
        }
        .auth-brand-col:hover .glow-line { transform: scaleX(1); }
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

    <!-- Auth Container -->
    <div class="auth-wrapper">
        <!-- Branding Side -->
        <div class="auth-brand-col">
            <div class="brand-logo">◈</div>
            <h1 class="brand-name">
                KillByte<br><span class="accent">Solutions</span>
            </h1>
            <p class="brand-subtitle">
                Enterprise L7 & L4 Stress Testing<br>
                <span style="font-weight:400; color:var(--accent-crimson);">550M+ req/s</span> · Zero‑Logs · Full Anonymity
            </p>
            <div class="security-badges">
                <div class="security-badge">
                    <i class="fas fa-lock"></i>
                    <span>256‑bit AES</span>
                </div>
                <div class="security-badge">
                    <i class="fas fa-shield-halved"></i>
                    <span>Quantum‑Safe</span>
                </div>
                <div class="security-badge">
                    <i class="fas fa-fingerprint"></i>
                    <span>Biometric‑Ready</span>
                </div>
                <div class="security-badge">
                    <i class="fas fa-server"></i>
                    <span>99.99% Uptime</span>
                </div>
            </div>
            <div class="glow-line"></div>
        </div>

        <!-- Login Form Side -->
        <div class="auth-form-col">
            <h2 class="form-title">
                <span class="accent">Secure</span> Login
            </h2>
            <form method="POST" id="loginForm">
                <div class="form-group">
                    <input type="text" name="username" class="form-input" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="passwordField" class="form-input" placeholder="Password" required>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <!-- Math Captcha -->
                <div class="form-group">
                    <label style="font-size:0.75rem;color:var(--text-secondary);margin-bottom:0.5rem;display:block;">
                        Security Verification — Solve: <strong><?php echo htmlspecialchars($captcha_question); ?></strong>
                    </label>
                    <div class="captcha-group">
                        <div class="captcha-label"><?php echo htmlspecialchars($captcha_question); ?></div>
                        <input type="number" name="captcha" class="form-input captcha-input" placeholder="Answer" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Authenticate Securely
                </button>
            </form>
            <div class="auth-footer">
                Don't have an account? <a href="register.php">Create one</a><br>
                <a href="index.php" style="font-size:0.75rem;">← Return to Home</a>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="notification error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <script>
            setTimeout(() => { window.location.href = 'dashboard.php'; }, 3000);
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

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

            document.querySelectorAll('a, button, input').forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('expanded'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('expanded'));
            });
        }

        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('passwordField');
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Auto dismiss error toasts
        document.querySelectorAll('.notification.error').forEach(n => {
            setTimeout(() => {
                n.style.transition = 'all 0.3s ease';
                n.style.opacity = '0';
                n.style.transform = 'translateX(100%)';
                setTimeout(() => n.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>