<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ---------- Login check ----------
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['username'];

// ---------- Load user info ----------
$busFile = __DIR__ . '/../bus.txt';
if (!file_exists($busFile)) die("System error.");
$users = file($busFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$userData = null;
foreach ($users as $line) {
    $parts = explode('|', $line);
    if (count($parts) >= 6 && $parts[0] === $username) {
        $userData = $parts;
        break;
    }
}
if (!$userData) die("User not found.");

$plan = strtolower($userData[4]);

// ---------- VIP list ----------
$vipPlans = ['hobbit', 'advanced', 'terror', 'fresh', 'emerald', 'meteor', 'burial', 'rush', 'blast', 'zomb', 'titan', 'decay', 'owner', 'admin'];
if (!in_array($plan, $vipPlans)) {
    die("API token management is only for VIP plans. Please upgrade.");
}

// ---------- Token file ----------
$tokenFile = __DIR__ . '/tokens.txt';

// ---------- Load user tokens ----------
$userTokens = [];
$tokenCount = 0;
if (file_exists($tokenFile)) {
    foreach (file($tokenFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $t = json_decode($line, true);
        if ($t && $t['username'] === $username) {
            $userTokens[] = $t;
            $tokenCount++;
        }
    }
}

// ---------- Handle actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate new token (with limit)
    if (isset($_POST['generate'])) {
        if ($tokenCount >= 3) {
            $_SESSION['token_msg'] = "You can only create up to 3 tokens.";
        } else {
            $newToken = bin2hex(random_bytes(16));
            $entry = json_encode([
                'token'    => $newToken,
                'username' => $username,
                'created'  => date('Y-m-d H:i:s'),
                'requests' => 0
            ]) . "\n";
            file_put_contents($tokenFile, $entry, FILE_APPEND);
            $_SESSION['token_msg'] = "Token generated: <code>$newToken</code>";
        }
    }

    // Revoke token
    if (isset($_POST['revoke'])) {
        $revokeToken = $_POST['token'];
        if (file_exists($tokenFile)) {
            $lines = file($tokenFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $newContent = '';
            $found = false;
            foreach ($lines as $line) {
                $t = json_decode($line, true);
                if ($t && $t['token'] === $revokeToken && $t['username'] === $username) {
                    $found = true;
                    continue;
                }
                $newContent .= $line . "\n";
            }
            file_put_contents($tokenFile, $newContent);
            $_SESSION['token_msg'] = $found ? "Token revoked." : "Token not found.";
        }
    }
    header("Location: index.php");
    exit;
}

$msg = $_SESSION['token_msg'] ?? '';
unset($_SESSION['token_msg']);

// ---------- Detect host ----------
$host = $_SERVER['HTTP_HOST'];
$baseUrl = "https://$host/api/attack.php";
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tokens | KillByte Solutions</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>◈</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #000; --text: #f0f0f0; --text2: #8a8a8a;
            --accent: #cc1111; --gradient: linear-gradient(135deg, #cc1111 0%, #aa0e0e 50%, #880a0a 100%);
            --font-display: 'Space Grotesk', sans-serif; --font-mono: 'JetBrains Mono', monospace;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #000; color: var(--text); line-height: 1.6; min-height: 100vh; }
        .grid-container { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; perspective: 1200px; }
        .grid-surface { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%) rotateX(60deg) scale(1.4); width: 200%; height: 200%; transform-style: preserve-3d; }
        .grid-squares { position: absolute; inset: 0; display: grid; grid-template-columns: repeat(12,1fr); grid-template-rows: repeat(12,1fr); animation: gridPulse 8s ease-in-out infinite; }
        .grid-square { border: 1px solid rgba(255,255,255,0.012); background: rgba(255,255,255,0.002); }
        .grid-square.highlight { border-color: rgba(204,17,17,0.04); background: rgba(204,17,17,0.01); }
        @keyframes gridPulse { 0%,100% { transform: scale(1) rotateX(0); } 50% { transform: scale(1.01) rotateX(1deg); } }
        .main-content { position: relative; z-index: 10; max-width: 900px; margin: 80px auto 60px; padding: 0 2rem; }
        .glass-panel { background: rgba(255,255,255,0.01); backdrop-filter: blur(40px); border: 1px solid rgba(255,255,255,0.02); border-radius: 24px; padding: 2.5rem; margin-bottom: 2rem; }
        .section-label { font-family: var(--font-mono); font-size: 0.55rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 1rem; display: inline-block; padding: 0.3rem 1rem; border: 1px solid rgba(204,17,17,0.06); border-radius: 99px; background: rgba(204,17,17,0.02); }
        .section-title { font-family: var(--font-display); font-size: 2rem; font-weight: 300; margin-bottom: 1.5rem; }
        .accent { color: var(--accent); font-weight: 600; }
        .btn { padding: 0.7rem 1.5rem; border-radius: 99px; border: 1px solid var(--accent); background: transparent; color: var(--accent); font-weight: 600; cursor: pointer; transition: all .3s; font-size: 0.85rem; }
        .btn:hover { background: var(--accent); color: #fff; }
        .btn-generate { width: 100%; margin-top: 1.5rem; }
        .token-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.03); border-radius: 14px; padding: 1.5rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .token-value { font-family: var(--font-mono); font-size: 0.9rem; word-break: break-all; }
        .token-meta { font-size: 0.7rem; color: var(--text2); }
        .notification { position: fixed; top: 20px; right: 20px; padding: 15px 25px; border-radius: 12px; background: rgba(6,6,6,0.95); border: 1px solid rgba(204,17,17,0.15); z-index: 10000; animation: slideIn 0.4s ease; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        .back-link { color: var(--text2); text-decoration: none; font-size: 0.8rem; margin-bottom: 2rem; display: inline-block; }
        pre { background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; overflow-x: auto; font-size: 0.85rem; color: var(--text); }
        code { font-family: var(--font-mono); color: var(--accent); }
    </style>
</head>
<body>
<div class="grid-container"><div class="grid-surface"><div class="grid-squares"><?php for($i=0;$i<144;$i++): ?><div class="grid-square <?= rand(0,20)===0?'highlight':'' ?>"></div><?php endfor; ?></div></div></div>

<div class="main-content">
    <a href="../dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

    <div class="glass-panel">
        <div class="section-label">API</div>
        <h1 class="section-title">API <span class="accent">Tokens</span></h1>
        <p style="color: var(--text2); margin-bottom: 2rem;">Generate tokens to authenticate your API requests. Maximum <strong>3 tokens</strong> per user.</p>

        <?php if ($msg): ?>
        <div class="notification"><?= $msg ?></div>
        <?php endif; ?>

        <?php if ($tokenCount >= 3): ?>
            <p style="color: var(--accent); text-align: center; padding: 1rem; border: 1px solid var(--accent); border-radius: 12px;">
                ⚠️ You have reached the maximum of 3 tokens. Revoke an existing token to create a new one.
            </p>
        <?php else: ?>
            <form method="POST">
                <button type="submit" name="generate" class="btn btn-generate"><i class="fas fa-key"></i> Generate New Token</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="glass-panel">
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 1.5rem;">Your Tokens</h2>
        <?php if (empty($userTokens)): ?>
        <p style="color: var(--text2);">No tokens generated yet.</p>
        <?php else: ?>
        <?php foreach ($userTokens as $tok): ?>
        <div class="token-card">
            <div>
                <div class="token-value"><?= htmlspecialchars($tok['token']) ?></div>
                <div class="token-meta">Created: <?= htmlspecialchars($tok['created']) ?> | Requests: <?= (int)($tok['requests']??0) ?></div>
            </div>
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($tok['token']) ?>">
                <button type="submit" name="revoke" class="btn" onclick="return confirm('Revoke this token?')"><i class="fas fa-trash"></i> Revoke</button>
            </form>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="glass-panel">
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 1.5rem;">How to Use the API</h2>
        <p style="color: var(--text2); margin-bottom: 1rem;">Send a GET request to <code><?= $baseUrl ?></code> with:</p>
        <table style="width:100%; border-collapse: collapse; margin-bottom: 1.5rem;">
            <tr><td style="padding:0.5rem; color:var(--accent);">token</td><td style="padding:0.5rem;">Your API token</td></tr>
            <tr><td style="padding:0.5rem; color:var(--accent);">target</td><td style="padding:0.5rem;">Target URL / IP</td></tr>
            <tr><td style="padding:0.5rem; color:var(--accent);">time</td><td style="padding:0.5rem;">Duration in seconds</td></tr>
            <tr><td style="padding:0.5rem; color:var(--accent);">method</td><td style="padding:0.5rem;">Attack method (e.g., HTTP-IOS)</td></tr>
            <tr><td style="padding:0.5rem; color:var(--accent);">port</td><td style="padding:0.5rem;">(Optional) Port, default 80</td></tr>
            <tr><td style="padding:0.5rem; color:var(--accent);">concurrents</td><td style="padding:0.5rem;">(Optional) Concurrents, default 1</td></tr>
        </table>
        <?php if (!empty($userTokens)): ?>
        <p style="color: var(--text2);">Example with your first token:</p>
        <pre><code>curl "<?= $baseUrl ?>?token=<?= htmlspecialchars($userTokens[0]['token']) ?>&target=https://example.com&time=60&method=HTTP-IOS&port=443&concurrents=2"</code></pre>
        <?php endif; ?>
        <p style="color: var(--text2); margin-top: 1rem;">Response is JSON with <code>status</code>, <code>message</code>, and attack details.</p>
    </div>
</div>

<script>
    setTimeout(() => {
        const n = document.querySelector('.notification');
        if (n) { n.style.opacity='0'; n.style.transform='translateX(100%)'; setTimeout(() => n.remove(), 300); }
    }, 5000);
</script>
</body>
</html>