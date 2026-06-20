<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Restrict access
if (!isset($_SESSION['username']) || !in_array($_SESSION['plan'], ['admin', 'owner'])) {
    header('Location: ../login');
    exit;
}

// ---- Helper functions ----
function getUsers() {
    $users = [];
    if (file_exists('../bus.txt')) {
        $lines = file('../bus.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            while (count($parts) < 9) $parts[] = '';
            $users[] = $parts;
        }
    }
    return $users;
}

function saveUsers($users) {
    $lines = array_map(function($u) {
        while (count($u) < 9) $u[] = '';
        return implode('|', $u);
    }, $users);
    file_put_contents('../bus.txt', implode("\n", $lines) . "\n");
}

// ---- Handle POST actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = $_POST['username'] ?? '';
    $users = getUsers();
    $found = false;

    foreach ($users as $idx => &$u) {
        if ($u[0] === $username) {
            $found = true;
            switch ($action) {
                case 'delete':
                    unset($users[$idx]);
                    $_SESSION['admin_success'] = "User deleted.";
                    break;
                case 'warn':
                    $u[7] = (int)($u[7] ?? 0) + 1;
                    $u[8] = trim($_POST['warning_message'] ?? '');
                    $_SESSION['admin_success'] = "User warned (total: {$u[7]}). Message sent.";
                    break;
                case 'freeze':
                    $u[6] = 'frozen';
                    $_SESSION['admin_success'] = "User frozen.";
                    break;
                case 'unfreeze':
                    $u[6] = 'active';
                    $_SESSION['admin_success'] = "User unfrozen.";
                    break;
                case 'restrict':
                    $u[6] = 'restricted';
                    $_SESSION['admin_success'] = "User restricted.";
                    break;
                case 'resetpass':
                    $newPass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
                    $u[1] = $newPass;
                    $_SESSION['admin_success'] = "Password reset to: $newPass";
                    break;
                case 'update':
                    $u[2] = $_POST['concurrent'] ?? 0;
                    $u[3] = $_POST['duration'] ?? 0;
                    $u[4] = $_POST['plan'] ?? 'free';
                    $u[5] = $_POST['expiry'] ?? '30-12-2030';
                    $_SESSION['admin_success'] = "User updated.";
                    break;
                case 'clear_warning':
                    $u[7] = 0;
                    $u[8] = '';
                    $_SESSION['admin_success'] = "Warning cleared.";
                    break;
            }
            break;
        }
    }

    if ($action === 'create') {
        $newUser = [
            $_POST['username'] ?? '',
            $_POST['password'] ?? '',
            $_POST['concurrent'] ?? 0,
            $_POST['duration'] ?? 0,
            $_POST['plan'] ?? 'free',
            $_POST['expiry'] ?? '30-12-2030',
            'active',
            0,
            ''
        ];
        if (!empty($newUser[0]) && !empty($newUser[1])) {
            $users[] = $newUser;
            $_SESSION['admin_success'] = "User created.";
        } else {
            $_SESSION['admin_error'] = "Username and password required.";
        }
    }

    saveUsers($users);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ---- Load data ----
$users = getUsers();
$total = count($users);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>User Management – KillByte Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        body { overflow-y: auto; }  /* FIX: allow vertical scrolling */
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

        /* ===== SIDEBAR & LAYOUT ===== */
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
        .table-wrap { overflow-x: auto; } /* Always scrollable */
        table { width:100%; border-collapse:collapse; font-size:0.8rem; min-width:700px; } /* ensure table has min-width for mobile */
        th { text-align:left; padding:0.6rem 0.8rem; color:var(--text-tertiary); font-weight:500; text-transform:uppercase; letter-spacing:0.08em; border-bottom:1px solid var(--border-subtle); }
        td { padding:0.6rem 0.8rem; border-bottom:1px solid var(--border-subtle); color:var(--text-secondary); }
        tr:hover td { background:var(--bg-glass-hover); }
        .status-badge { display:inline-block; padding:0.15rem 0.6rem; border-radius:99px; font-size:0.6rem; font-weight:600; text-transform:uppercase; }
        .status-active { background:rgba(34,197,94,0.1); color:#22c55e; }
        .status-frozen { background:rgba(255,165,0,0.1); color:#f59e0b; }
        .status-restricted { background:rgba(204,17,17,0.1); color:#cc1111; }

        /* ===== BUTTONS ===== */
        .action-group { display:flex; flex-wrap:wrap; gap:0.3rem; }
        .action-group button, .action-group form { margin:0; }
        .btn { background:none; border:1px solid var(--border-subtle); border-radius:6px; padding:0.2rem 0.6rem; color:var(--text-secondary); font-size:0.65rem; cursor:pointer; transition:all 0.3s; }
        .btn:hover { border-color:var(--accent-crimson); color:var(--text-primary); }
        .btn-danger { border-color:#cc1111; color:#cc1111; }
        .btn-danger:hover { background:#cc1111; color:white; }
        .btn-success { border-color:#22c55e; color:#22c55e; }
        .btn-success:hover { background:#22c55e; color:white; }
        .btn-warning { border-color:#f59e0b; color:#f59e0b; }
        .btn-warning:hover { background:#f59e0b; color:white; }
        .btn-primary { border-color:var(--accent-crimson); color:var(--accent-crimson); }
        .btn-primary:hover { background:var(--accent-crimson); color:white; }

        /* ===== SEARCH & PAGINATION ===== */
        .search-box { display:flex; gap:1rem; margin-bottom:1rem; flex-wrap:wrap; }
        .search-box input { flex:1; background:rgba(255,255,255,0.005); border:1px solid var(--border-subtle); border-radius:8px; padding:0.6rem 1rem; color:var(--text-primary); font-size:0.8rem; }
        .pagination-controls { display:flex; justify-content:center; gap:0.5rem; margin-top:1.5rem; flex-wrap:wrap; }
        .pagination-controls button { background:none; border:1px solid var(--border-subtle); border-radius:6px; padding:0.3rem 0.8rem; color:var(--text-secondary); cursor:pointer; transition:all 0.3s; }
        .pagination-controls button:hover { border-color:var(--accent-crimson); color:var(--text-primary); }
        .pagination-controls button:disabled { opacity:0.4; cursor:not-allowed; }
        .pagination-controls .page-info { padding:0.3rem 0.8rem; color:var(--text-tertiary); }

        /* ===== MODALS ===== */
        .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(20px); z-index:1000; align-items:center; justify-content:center; }
        .modal.active { display:flex; }
        .modal-content { background:var(--bg-elevated); border:1px solid var(--border-subtle); border-radius:16px; padding:2rem; max-width:500px; width:90%; max-height:80vh; overflow-y:auto; position:relative; }
        .modal-content .close { position:absolute; top:1rem; right:1.5rem; background:none; border:none; color:var(--text-tertiary); font-size:1.5rem; cursor:pointer; transition:all 0.3s; }
        .modal-content .close:hover { color:var(--accent-crimson); transform:rotate(90deg); }
        .modal-content h2 { font-family:var(--font-display); margin-bottom:1rem; }
        .modal-content .form-group { margin-bottom:1rem; }
        .modal-content .form-group label { display:block; font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); margin-bottom:0.3rem; }
        .modal-content .form-group input, .modal-content .form-group textarea { width:100%; background:rgba(255,255,255,0.005); border:1px solid var(--border-subtle); border-radius:8px; padding:0.6rem 1rem; color:var(--text-primary); font-family:'Inter',sans-serif; resize:vertical; }
        .modal-content .form-group textarea { min-height:80px; }
        .modal-content .btn { padding:0.5rem 1.5rem; margin-top:0.5rem; }

        /* ===== NOTIFICATION ===== */
        .notification { position:fixed; top:80px; right:20px; padding:0.8rem 1.5rem; background:var(--bg-elevated); border:1px solid var(--border-crimson); border-radius:12px; backdrop-filter:blur(40px); color:var(--text-primary); z-index:9999; animation:slideIn 0.3s var(--ease-out-expo); box-shadow:0 20px 60px rgba(0,0,0,0.2); max-width:90vw; word-wrap: break-word; }
        .notification.success { border-color:rgba(34,197,94,0.2); }
        .notification.error { border-color:rgba(204,17,17,0.2); }
        @keyframes slideIn { 0%{transform:translateX(100%);opacity:0;} 100%{transform:translateX(0);opacity:1;} }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px) {
            .action-group .btn { font-size:0.6rem; padding:0.1rem 0.4rem; }
            .glass-panel { padding:1rem; }
            .pagination-controls button { font-size:0.65rem; padding:0.2rem 0.5rem; }
        }
    </style>
</head>
<body>
    <!-- Background layers -->
    <div class="grid-container"><div class="grid-surface"><div class="grid-squares">
        <?php for ($i=0;$i<144;$i++): ?><div class="grid-square"></div><?php endfor; ?>
    </div></div></div>
    <div class="crimson-reflection"></div><div class="vignette"></div><div class="glass-sweep"></div>

    <div class="app">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand"><i class="fas fa-skull"></i> KillByte<br><span style="font-size:0.7rem;color:var(--text-tertiary);">Admin Panel</span></div>
            <a href="dashboard" class="nav-item"><i class="fas fa-gauge-high"></i> Dashboard</a>
            <a href="users" class="nav-item active"><i class="fas fa-users"></i> Users</a>
            <a href="attacks" class="nav-item"><i class="fas fa-bolt"></i> Attack Logs</a>
            <a href="methods" class="nav-item"><i class="fas fa-code"></i> Methods</a>
            <a href="settings" class="nav-item"><i class="fas fa-gear"></i> Settings</a>
            <div class="bottom"><a href="../dashboard"><i class="fas fa-arrow-left"></i> Back to Panel</a></div>
        </aside>

        <!-- Mobile header -->
        <div class="mobile-header">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <span></span><span></span><span></span>
            </button>
            <span style="font-weight:600; font-size:1rem;">User Management</span>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <div class="glass-panel">
                <div class="panel-title"><i class="fas fa-users"></i> Manage Users <span style="font-size:0.7rem;color:var(--text-tertiary);font-weight:400;">(<?php echo $total; ?> total)</span></div>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <button class="btn btn-success" onclick="openCreateModal()"><i class="fas fa-plus"></i> Create User</button>
                </div>

                <!-- Search -->
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by username or plan..." oninput="applyFilters()">
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead><tr>
                            <th>User</th><th>Plan</th><th>Conc</th><th>Dur</th><th>Expiry</th><th>Status</th><th>Warnings</th><th>Actions</th>
                        </tr></thead>
                        <tbody id="userTableBody">
                            <!-- populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-controls" id="paginationControls">
                    <button id="firstPage" onclick="goToPage(1)">First</button>
                    <button id="prevPage" onclick="goToPrevPage()">Previous</button>
                    <span class="page-info" id="pageInfo">Page 1 of 1</span>
                    <button id="nextPage" onclick="goToNextPage()">Next</button>
                    <button id="lastPage" onclick="goToLastPage()">Last</button>
                </div>
            </div>
        </main>
    </div>

    <!-- ===== CREATE MODAL ===== -->
    <div class="modal" id="createModal">
        <div class="modal-content">
            <button class="close" onclick="closeModal('createModal')">&times;</button>
            <h2>Create User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="text" name="password" required></div>
                <div class="form-group"><label>Plan</label><input type="text" name="plan" value="free"></div>
                <div class="form-group"><label>Concurrent</label><input type="number" name="concurrent" value="1"></div>
                <div class="form-group"><label>Duration (s)</label><input type="number" name="duration" value="60"></div>
                <div class="form-group"><label>Expiry (dd-mm-yyyy)</label><input type="text" name="expiry" value="30-12-2030"></div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>

    <!-- ===== EDIT MODAL ===== -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <button class="close" onclick="closeModal('editModal')">&times;</button>
            <h2>Edit User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="username" id="editUsername">
                <div class="form-group"><label>Plan</label><input type="text" name="plan" id="editPlan"></div>
                <div class="form-group"><label>Concurrent</label><input type="number" name="concurrent" id="editConcurrent"></div>
                <div class="form-group"><label>Duration (s)</label><input type="number" name="duration" id="editDuration"></div>
                <div class="form-group"><label>Expiry (dd-mm-yyyy)</label><input type="text" name="expiry" id="editExpiry"></div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>

    <!-- ===== WARN MODAL ===== -->
    <div class="modal" id="warnModal">
        <div class="modal-content">
            <button class="close" onclick="closeModal('warnModal')">&times;</button>
            <h2>Warn User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="warn">
                <input type="hidden" name="username" id="warnUsername">
                <div class="form-group">
                    <label>Warning Message</label>
                    <textarea name="warning_message" id="warningMessage" placeholder="e.g., You are spamming. Please stop." required></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Send Warning</button>
            </form>
        </div>
    </div>

    <!-- ===== NOTIFICATIONS ===== -->
    <?php if (isset($_SESSION['admin_success'])): ?>
    <div class="notification success"><?php echo $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['admin_error'])): ?>
    <div class="notification error"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
    <?php endif; ?>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        // ---- Data ----
        const allUsers = <?php echo json_encode($users); ?>;
        let filteredUsers = [...allUsers];
        let currentPage = 1;
        const perPage = 100;

        // ---- Render ----
        function renderUsers() {
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const pageUsers = filteredUsers.slice(start, end);

            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            if (pageUsers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--text-tertiary);">No users found.</td></tr>';
                updatePagination();
                return;
            }

            pageUsers.forEach(u => {
                const row = document.createElement('tr');
                const status = u[6] || 'active';
                const warnings = parseInt(u[7] || 0);
                const warningMsg = u[8] || '';
                row.innerHTML = `
                    <td><strong>${escapeHtml(u[0])}</strong></td>
                    <td>${escapeHtml(u[4])}</td>
                    <td>${escapeHtml(u[2])}</td>
                    <td>${escapeHtml(u[3])}</td>
                    <td>${escapeHtml(u[5])}</td>
                    <td><span class="status-badge status-${status}">${status}</span></td>
                    <td>
                        ${warnings > 0 ? `<span title="${escapeHtml(warningMsg)}">${warnings} ⚠️</span>` : '0'}
                    </td>
                    <td>
                        <div class="action-group">
                            <button class="btn" onclick="openEditModal('${escapeHtml(u[0])}')"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-warning" onclick="openWarnModal('${escapeHtml(u[0])}')"><i class="fas fa-exclamation-triangle"></i></button>
                            ${status !== 'frozen' 
                                ? `<form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="freeze">
                                    <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                    <button class="btn btn-warning" title="Freeze"><i class="fas fa-snowflake"></i></button>
                                   </form>`
                                : `<form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="unfreeze">
                                    <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                    <button class="btn btn-success" title="Unfreeze"><i class="fas fa-play"></i></button>
                                   </form>`
                            }
                            ${status !== 'restricted' 
                                ? `<form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="restrict">
                                    <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                    <button class="btn btn-warning" title="Restrict"><i class="fas fa-ban"></i></button>
                                   </form>`
                                : ''
                            }
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="resetpass">
                                <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                <button class="btn" title="Reset Password"><i class="fas fa-key"></i></button>
                            </form>
                            ${warnings > 0 ? `<form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="clear_warning">
                                <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                <button class="btn btn-success" title="Clear Warning"><i class="fas fa-check"></i></button>
                            </form>` : ''}
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="username" value="${escapeHtml(u[0])}">
                                <button class="btn btn-danger" onclick="return confirm('Delete user?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            updatePagination();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredUsers.length / perPage) || 1;
            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('firstPage').disabled = currentPage === 1;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
            document.getElementById('lastPage').disabled = currentPage >= totalPages;
        }

        // ---- Navigation ----
        function goToPage(page) {
            const totalPages = Math.ceil(filteredUsers.length / perPage) || 1;
            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;
            renderUsers();
        }
        function goToPrevPage() { if (currentPage > 1) goToPage(currentPage - 1); }
        function goToNextPage() { const total = Math.ceil(filteredUsers.length / perPage); if (currentPage < total) goToPage(currentPage + 1); }
        function goToLastPage() { const total = Math.ceil(filteredUsers.length / perPage); goToPage(total); }

        // ---- Filter ----
        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            if (q === '') {
                filteredUsers = [...allUsers];
            } else {
                filteredUsers = allUsers.filter(u => 
                    u[0].toLowerCase().includes(q) || 
                    u[4].toLowerCase().includes(q)
                );
            }
            currentPage = 1;
            renderUsers();
        }

        // ---- Modals ----
        function openCreateModal() { document.getElementById('createModal').classList.add('active'); }
        function openEditModal(username) {
            const user = allUsers.find(u => u[0] === username);
            if (!user) return;
            document.getElementById('editUsername').value = username;
            document.getElementById('editPlan').value = user[4];
            document.getElementById('editConcurrent').value = user[2];
            document.getElementById('editDuration').value = user[3];
            document.getElementById('editExpiry').value = user[5];
            document.getElementById('editModal').classList.add('active');
        }
        function openWarnModal(username) {
            document.getElementById('warnUsername').value = username;
            document.getElementById('warningMessage').value = '';
            document.getElementById('warnModal').classList.add('active');
        }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        // Close modal on overlay click
        document.querySelectorAll('.modal').forEach(m => {
            m.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('active');
            });
        });

        // ---- Helpers ----
        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                      .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        // ---- Sidebar toggle (mobile) ----
        document.querySelectorAll('.sidebar .nav-item').forEach(link => {
            link.addEventListener('click', () => document.getElementById('sidebar').classList.remove('open'));
        });

        // ---- Init ----
        document.addEventListener('DOMContentLoaded', function() {
            renderUsers();
            // Auto-hide notifications after 5s
            document.querySelectorAll('.notification').forEach(n => {
                setTimeout(() => {
                    n.style.opacity = '0';
                    n.style.transform = 'translateX(40px)';
                    setTimeout(() => n.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>