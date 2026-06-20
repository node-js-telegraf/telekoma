<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Restrict access to admins/owners only
if (!isset($_SESSION['username']) || !in_array($_SESSION['plan'], ['admin', 'owner'])) {
    header('Location: ../login');
    exit;
}

// Load logs
$logFile = __DIR__ . '/attacks.log';
$logs = [];
if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // Reverse to show newest first
    $lines = array_reverse($lines);
    foreach ($lines as $line) {
        $logs[] = json_decode($line, true);
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Attack Logs – Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            color: #f0f0f0;
            padding: 2rem;
            min-height: 100vh;
            background: radial-gradient(ellipse at center, rgba(204,17,17,0.02) 0%, transparent 70%);
        }
        ::-webkit-scrollbar { width: 2px; }
        ::-webkit-scrollbar-thumb { background: rgba(204,17,17,0.2); border-radius: 1px; }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.02);
            padding-bottom: 1rem;
        }
        .header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 500;
            font-size: 1.6rem;
            letter-spacing: -0.02em;
        }
        .header h1 i { color: #cc1111; margin-right: 0.6rem; }
        .header .stats {
            font-size: 0.8rem;
            color: #8a8a8a;
        }
        .header .stats span { color: #cc1111; font-weight: 600; }

        .glass-panel {
            background: rgba(255,255,255,0.01);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.02);
            border-radius: 16px;
            padding: 1.5rem;
            overflow-x: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            min-width: 700px;
        }
        th {
            text-align: left;
            padding: 0.8rem 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #555;
            border-bottom: 1px solid rgba(255,255,255,0.02);
            font-size: 0.65rem;
        }
        td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.01);
            color: #aaa;
            vertical-align: middle;
        }
        tr:hover td { background: rgba(255,255,255,0.005); }
        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 99px;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .status-launched { background: rgba(204,17,17,0.1); color: #cc1111; }
        .status-stopped { background: rgba(255,165,0,0.1); color: #f59e0b; }
        .status-expired { background: rgba(34,197,94,0.1); color: #22c55e; }
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #555;
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.3s;
        }
        .back-link:hover { color: #cc1111; }
        .back-link i { margin-right: 0.4rem; }

        .no-logs {
            text-align: center;
            padding: 3rem 0;
            color: #555;
        }
        .no-logs i { font-size: 2rem; margin-bottom: 1rem; display: block; }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-skull"></i> Attack Logs</h1>
            <div class="stats">
                Total Attacks: <span><?php echo count($logs); ?></span>
            </div>
        </div>

        <div class="glass-panel">
            <?php if (empty($logs)): ?>
                <div class="no-logs">
                    <i class="fas fa-inbox"></i>
                    No attack logs found.
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Target</th>
                            <th>Method</th>
                            <th>Duration</th>
                            <th>Concurrents</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($log['username'] ?? 'unknown'); ?></strong></td>
                            <td><?php echo htmlspecialchars($log['target'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($log['method'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($log['duration'] ?? 0); ?>s</td>
                            <td><?php echo htmlspecialchars($log['concurrents'] ?? 0); ?></td>
                            <td class="mono"><?php echo date('H:i:s', $log['start_time'] ?? time()); ?></td>
                            <td class="mono"><?php echo date('H:i:s', $log['end_time'] ?? time()); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $log['status'] ?? 'launched'; ?>">
                                    <?php echo htmlspecialchars($log['status'] ?? 'launched'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <a href="../dashboard" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>