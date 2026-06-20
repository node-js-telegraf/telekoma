<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Restrict access
if (!isset($_SESSION['username']) || !in_array($_SESSION['plan'], ['admin', 'owner'])) {
    header('Location: ../login');
    exit;
}

// Load methods (3 fields: name|api|enabled)
function getMethods() {
    $methods = [];
    if (file_exists('../methods.txt')) {
        $lines = file('../methods.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            while (count($parts) < 3) $parts[] = '1';
            $methods[] = [
                'name' => trim($parts[0]),
                'api' => trim($parts[1]),
                'enabled' => trim($parts[2]) === '1'
            ];
        }
    }
    return $methods;
}

function saveMethods($methods) {
    $lines = array_map(function($m) {
        return $m['name'] . '|' . $m['api'] . '|' . ($m['enabled'] ? '1' : '0');
    }, $methods);
    file_put_contents('../methods.txt', implode("\n", $lines) . "\n");
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $methods = getMethods();

    switch ($action) {
        case 'add':
            $newName = trim($_POST['new_name'] ?? '');
            $newApi = trim($_POST['new_api'] ?? '');
            if (!empty($newName) && !empty($newApi)) {
                $methods[] = ['name' => $newName, 'api' => $newApi, 'enabled' => true];
                $_SESSION['admin_success'] = "Method added.";
            } else {
                $_SESSION['admin_error'] = "Name and API URL required.";
            }
            break;
        case 'edit':
            $oldName = $_POST['old_name'] ?? '';
            $newName = trim($_POST['new_name'] ?? '');
            $newApi = trim($_POST['new_api'] ?? '');
            if (!empty($oldName) && !empty($newName) && !empty($newApi)) {
                foreach ($methods as &$m) {
                    if ($m['name'] === $oldName) {
                        $m['name'] = $newName;
                        $m['api'] = $newApi;
                        break;
                    }
                }
                $_SESSION['admin_success'] = "Method updated.";
            } else {
                $_SESSION['admin_error'] = "All fields required.";
            }
            break;
        case 'delete':
            $name = $_POST['name'] ?? '';
            $methods = array_filter($methods, function($m) use ($name) {
                return $m['name'] !== $name;
            });
            $_SESSION['admin_success'] = "Method deleted.";
            break;
        case 'toggle':
            $name = $_POST['name'] ?? '';
            foreach ($methods as &$m) {
                if ($m['name'] === $name) {
                    $m['enabled'] = !$m['enabled'];
                    break;
                }
            }
            $_SESSION['admin_success'] = "Method toggled.";
            break;
        case 'move':
            $name = $_POST['name'] ?? '';
            $direction = $_POST['direction'] ?? 'up';
            $idx = array_search($name, array_column($methods, 'name'));
            if ($idx !== false) {
                if ($direction === 'up' && $idx > 0) {
                    $tmp = $methods[$idx];
                    $methods[$idx] = $methods[$idx-1];
                    $methods[$idx-1] = $tmp;
                } elseif ($direction === 'down' && $idx < count($methods)-1) {
                    $tmp = $methods[$idx];
                    $methods[$idx] = $methods[$idx+1];
                    $methods[$idx+1] = $tmp;
                }
            }
            $_SESSION['admin_success'] = "Method reordered.";
            break;
    }

    saveMethods($methods);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$methods = getMethods();

// Language
$lang = $_SESSION['lang'] ?? 'en';
$tr = [
    'en' => [
        'app_name' => 'KillByte Admin',
        'methods' => 'Methods',
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'attacks' => 'Attack Logs',
        'settings' => 'Settings',
        'methods_title' => 'Attack Methods',
        'add_method' => 'Add Method',
        'method_name' => 'Method Name',
        'api_url' => 'API URL',
        'add_btn' => 'Add',
        'list_title' => 'Current Methods',
        'name' => 'Name',
        'api' => 'API',
        'status' => 'Status',
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'toggle' => 'Toggle',
        'move_up' => 'Move Up',
        'move_down' => 'Move Down',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'back_to_panel' => 'Back to Panel'
    ],
    'zh' => [
        'app_name' => 'KillByte 管理',
        'methods' => '方法',
        'dashboard' => '仪表盘',
        'users' => '用户',
        'attacks' => '攻击日志',
        'settings' => '设置',
        'methods_title' => '攻击方法',
        'add_method' => '添加方法',
        'method_name' => '方法名称',
        'api_url' => 'API URL',
        'add_btn' => '添加',
        'list_title' => '当前方法',
        'name' => '名称',
        'api' => 'API',
        'status' => '状态',
        'enabled' => '启用',
        'disabled' => '禁用',
        'actions' => '操作',
        'edit' => '编辑',
        'delete' => '删除',
        'toggle' => '切换',
        'move_up' => '上移',
        'move_down' => '下移',
        'cancel' => '取消',
        'save' => '保存',
        'back_to_panel' => '返回面板'
    ]
];
$t = $tr[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo $t['app_name']; ?> – <?php echo $t['methods']; ?></title>
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

        /* ===== GRID BACKGROUND ===== */
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

        /* ===== SIDEBAR ===== */
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

        /* ===== GLASS PANEL ===== */
        .glass-panel { background:var(--bg-glass); backdrop-filter:blur(40px); border:1px solid var(--border-subtle); border-radius:16px; padding:1.5rem; margin-bottom:2rem; }
        .panel-title { font-family:var(--font-display); font-size:1.1rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.6rem; }
        .panel-title i { color:var(--accent-crimson); }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; }
        table { width:100%; border-collapse:collapse; font-size:0.8rem; min-width:600px; } /* ensure horizontal scroll on mobile */
        th { text-align:left; padding:0.6rem 0.8rem; color:var(--text-tertiary); font-weight:500; text-transform:uppercase; letter-spacing:0.08em; border-bottom:1px solid var(--border-subtle); }
        td { padding:0.6rem 0.8rem; border-bottom:1px solid var(--border-subtle); color:var(--text-secondary); }
        tr:hover td { background:var(--bg-glass-hover); }

        .btn { padding:0.4rem 0.8rem; border-radius:99px; border:1px solid var(--border-subtle); background:transparent; color:var(--text-secondary); font-size:0.7rem; cursor:pointer; transition:all 0.3s var(--ease-out-expo); }
        .btn:hover { border-color:var(--accent-crimson); color:var(--text-primary); }
        .btn-primary { background:var(--gradient-crimson); color:white; border:none; padding:0.4rem 1.2rem; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 40px rgba(204,17,17,0.12); }
        .btn-success { border-color:#22c55e; color:#22c55e; }
        .btn-success:hover { background:#22c55e; color:white; }
        .btn-danger { border-color:#cc1111; color:#cc1111; }
        .btn-danger:hover { background:#cc1111; color:white; }
        .btn-warning { border-color:#f59e0b; color:#f59e0b; }
        .btn-warning:hover { background:#f59e0b; color:white; }
        .status-badge { display:inline-block; padding:0.15rem 0.6rem; border-radius:99px; font-size:0.6rem; font-weight:600; text-transform:uppercase; }
        .status-enabled { background:rgba(34,197,94,0.1); color:#22c55e; }
        .status-disabled { background:rgba(204,17,17,0.1); color:#cc1111; }

        .action-group { display:flex; flex-wrap:wrap; gap:0.3rem; align-items:center; }
        .action-group form { display:inline; }

        /* ===== MODAL ===== */
        .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(20px); z-index:1000; align-items:center; justify-content:center; }
        .modal.active { display:flex; }
        .modal-content { background:var(--bg-elevated); border:1px solid var(--border-subtle); border-radius:16px; padding:2rem; max-width:500px; width:90%; position:relative; max-height:90vh; overflow-y:auto; }
        .modal-content .close { position:absolute; top:1rem; right:1.5rem; background:none; border:none; color:var(--text-tertiary); font-size:1.5rem; cursor:pointer; transition:all 0.3s; }
        .modal-content .close:hover { color:var(--accent-crimson); transform:rotate(90deg); }
        .modal-content h2 { font-family:var(--font-display); margin-bottom:1rem; }
        .modal-content .form-group { margin-bottom:1rem; }
        .modal-content .form-group label { display:block; font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); margin-bottom:0.3rem; }
        .modal-content .form-group input { width:100%; background:rgba(255,255,255,0.005); border:1px solid var(--border-subtle); border-radius:8px; padding:0.6rem 1rem; color:var(--text-primary); }

        /* ===== NOTIFICATION ===== */
        .notification { position:fixed; top:80px; right:20px; padding:0.8rem 1.5rem; background:var(--bg-elevated); border:1px solid var(--border-crimson); border-radius:12px; backdrop-filter:blur(40px); color:var(--text-primary); z-index:9999; animation:slideIn 0.3s var(--ease-out-expo); box-shadow:0 20px 60px rgba(0,0,0,0.2); max-width:90vw; word-wrap:break-word; }
        .notification.success { border-color:rgba(34,197,94,0.2); }
        .notification.error { border-color:rgba(204,17,17,0.2); }
        @keyframes slideIn { 0%{transform:translateX(100%);opacity:0;} 100%{transform:translateX(0);opacity:1;} }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px) {
            .btn { font-size:0.65rem; padding:0.3rem 0.6rem; }
            .btn-primary { padding:0.4rem 1rem; }
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
            <a href="methods" class="nav-item active"><i class="fas fa-code"></i> <?php echo $t['methods']; ?></a>
            <a href="settings" class="nav-item"><i class="fas fa-gear"></i> <?php echo $t['settings']; ?></a>
            <div class="bottom"><a href="../dashboard"><i class="fas fa-arrow-left"></i> <?php echo $t['back_to_panel']; ?></a></div>
        </aside>

        <div class="mobile-header">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <span></span><span></span><span></span>
            </button>
            <span style="font-weight:600;"><?php echo $t['methods']; ?></span>
        </div>

        <main class="main-content">
            <!-- Add Method -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-plus"></i> <?php echo $t['add_method']; ?></div>
                <form method="POST" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                    <input type="hidden" name="action" value="add">
                    <div style="flex:1 1 180px;">
                        <input type="text" name="new_name" placeholder="<?php echo $t['method_name']; ?>" style="width:100%;background:rgba(255,255,255,0.005);border:1px solid var(--border-subtle);border-radius:8px;padding:0.6rem 1rem;color:var(--text-primary);">
                    </div>
                    <div style="flex:2 1 250px;">
                        <input type="text" name="new_api" placeholder="<?php echo $t['api_url']; ?>" style="width:100%;background:rgba(255,255,255,0.005);border:1px solid var(--border-subtle);border-radius:8px;padding:0.6rem 1rem;color:var(--text-primary);">
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $t['add_btn']; ?></button>
                </form>
            </div>

            <!-- List Methods -->
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-list"></i> <?php echo $t['list_title']; ?></div>
                <div class="table-wrap">
                    <table>
                        <thead><tr>
                            <th>#</th>
                            <th><?php echo $t['name']; ?></th>
                            <th><?php echo $t['api']; ?></th>
                            <th><?php echo $t['status']; ?></th>
                            <th><?php echo $t['actions']; ?></th>
                        </tr></thead>
                        <tbody>
                            <?php if (empty($methods)): ?>
                            <tr><td colspan="5" style="text-align:center;color:var(--text-tertiary);">No methods defined.</td></tr>
                            <?php else: ?>
                            <?php foreach ($methods as $idx => $m): ?>
                            <tr>
                                <td><?php echo $idx+1; ?></td>
                                <td><strong><?php echo htmlspecialchars($m['name']); ?></strong></td>
                                <td><span style="font-family:var(--font-mono);font-size:0.7rem;"><?php echo htmlspecialchars($m['api']); ?></span></td>
                                <td>
                                    <span class="status-badge status-<?php echo $m['enabled'] ? 'enabled' : 'disabled'; ?>">
                                        <?php echo $m['enabled'] ? $t['enabled'] : $t['disabled']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="btn" onclick="openEditModal('<?php echo htmlspecialchars($m['name']); ?>', '<?php echo htmlspecialchars($m['api']); ?>')"><i class="fas fa-edit"></i> <?php echo $t['edit']; ?></button>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($m['name']); ?>">
                                            <button class="btn btn-warning" title="<?php echo $t['toggle']; ?>"><i class="fas fa-power-off"></i></button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($m['name']); ?>">
                                            <input type="hidden" name="direction" value="up">
                                            <button class="btn" title="<?php echo $t['move_up']; ?>" <?php echo $idx===0?'disabled':''; ?>><i class="fas fa-arrow-up"></i></button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($m['name']); ?>">
                                            <input type="hidden" name="direction" value="down">
                                            <button class="btn" title="<?php echo $t['move_down']; ?>" <?php echo $idx===count($methods)-1?'disabled':''; ?>><i class="fas fa-arrow-down"></i></button>
                                        </form>
                                        <form method="POST" onsubmit="return confirm('Delete this method?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($m['name']); ?>">
                                            <button class="btn btn-danger"><i class="fas fa-trash"></i> <?php echo $t['delete']; ?></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <button class="close" onclick="closeModal('editModal')">&times;</button>
            <h2><?php echo $t['edit']; ?> Method</h2>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="old_name" id="editOldName">
                <div class="form-group">
                    <label><?php echo $t['method_name']; ?></label>
                    <input type="text" name="new_name" id="editName" required>
                </div>
                <div class="form-group">
                    <label><?php echo $t['api_url']; ?></label>
                    <input type="text" name="new_api" id="editApi" required>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $t['save']; ?></button>
                <button type="button" class="btn" onclick="closeModal('editModal')"><?php echo $t['cancel']; ?></button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['admin_success'])): ?>
    <div class="notification success"><?php echo $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['admin_error'])): ?>
    <div class="notification error"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
    <?php endif; ?>

    <script>
        function openEditModal(name, api) {
            document.getElementById('editOldName').value = name;
            document.getElementById('editName').value = name;
            document.getElementById('editApi').value = api;
            document.getElementById('editModal').classList.add('active');
        }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        document.querySelectorAll('.modal').forEach(m => {
            m.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('active');
            });
        });

        const lenis = new Lenis({ duration: 1.2, smoothWheel: true, lerp: 0.08 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

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