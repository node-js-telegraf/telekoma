/* ============================================
   KILLBYTE ADMIN PANEL - STYLES
   Comprehensive Admin Dashboard Styles
   ============================================ */

/* CSS Variables */
:root {
    --primary: #ff3333;
    --primary-dark: #cc0000;
    --primary-light: #ff6666;
    --secondary: #1a1a1a;
    --bg-dark: #0a0a0a;
    --bg-card: #141414;
    --bg-hover: #1e1e1e;
    --bg-input: #1a1a1a;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #666666;
    --border-color: #2a2a2a;
    --success: #00c853;
    --warning: #ffab00;
    --danger: #ff1744;
    --info: #00b0ff;
    --sidebar-width: 280px;
    --sidebar-collapsed: 80px;
    --header-height: 70px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.5);
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
}

/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    font-size: 16px;
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: var(--bg-dark);
    color: var(--text-primary);
    line-height: 1.6;
    min-height: 100vh;
    overflow-x: hidden;
}

/* Utility Classes */
.hidden { display: none !important; }
.invisible { visibility: hidden; }
.flex { display: flex; }
.flex-col { flex-direction: column; }
.items-center { align-items: center; }
.justify-center { justify-content: center; }
.justify-between { justify-content: space-between; }
.gap-2 { gap: 0.5rem; }
.gap-4 { gap: 1rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 1rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-4 { margin-bottom: 1rem; }
.w-full { width: 100%; }
.text-center { text-align: center; }
.text-sm { font-size: 0.875rem; }
.text-lg { font-size: 1.125rem; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-card);
}

::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

/* ============================================
   LOGIN SCREEN
   ============================================ */
.login-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--bg-dark) 0%, #1a0a0a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.login-container {
    width: 100%;
    max-width: 420px;
    padding: 3rem;
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-logo {
    text-align: center;
    margin-bottom: 2.5rem;
}

.login-logo i {
    font-size: 4rem;
    color: var(--primary);
    margin-bottom: 1rem;
    display: block;
}

.login-logo h1 {
    font-size: 1.75rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--text-primary), var(--primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.input-group {
    position: relative;
}

.input-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1rem;
}

.input-group input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    font-size: 1rem;
    transition: var(--transition);
}

.input-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(255, 51, 51, 0.1);
}

.input-group input::placeholder {
    color: var(--text-muted);
}

.login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border: none;
    border-radius: var(--radius-md);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 51, 51, 0.3);
}

.login-btn:active {
    transform: translateY(0);
}

.login-error {
    color: var(--danger);
    font-size: 0.875rem;
    text-align: center;
    min-height: 1.25rem;
}

.login-footer {
    margin-top: 2rem;
    text-align: center;
}

.login-footer p {
    color: var(--text-muted);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* ============================================
   ADMIN DASHBOARD
   ============================================ */
.admin-dashboard {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: var(--sidebar-width);
    background: var(--bg-card);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 1000;
    transition: var(--transition);
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed);
}

.sidebar-header {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-color);
}

.brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
    text-decoration: none;
}

.brand i {
    font-size: 1.5rem;
    color: var(--primary);
}

.brand span {
    font-weight: 700;
    font-size: 1.125rem;
}

.sidebar.collapsed .brand span {
    display: none;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: var(--radius-sm);
    transition: var(--transition);
}

.sidebar-toggle:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

.sidebar.collapsed .sidebar-toggle {
    display: none;
}

/* Sidebar Navigation */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
}

.nav-section {
    margin-bottom: 1.5rem;
}

.nav-label {
    display: block;
    padding: 0 1.25rem;
    margin-bottom: 0.5rem;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
}

.sidebar.collapsed .nav-label {
    display: none;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1.25rem;
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    margin: 0 0.5rem;
    border-radius: var(--radius-md);
}

.nav-item:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

.nav-item.active {
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.15), rgba(255, 51, 51, 0.05));
    color: var(--primary);
}

.nav-item.active::before {
    content: '';
    position: absolute;
    left: -0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: var(--primary);
    border-radius: 0 3px 3px 0;
}

.nav-item i {
    font-size: 1.125rem;
    width: 24px;
    text-align: center;
}

.nav-item span {
    font-size: 0.9375rem;
    font-weight: 500;
}

.sidebar.collapsed .nav-item span,
.sidebar.collapsed .nav-item .badge {
    display: none;
}

.badge {
    margin-left: auto;
    padding: 0.125rem 0.5rem;
    background: var(--primary);
    color: white;
    font-size: 0.6875rem;
    font-weight: 600;
    border-radius: 10px;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
}

.admin-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.admin-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.admin-avatar i {
    font-size: 1.5rem;
    color: white;
}

.admin-details {
    display: flex;
    flex-direction: column;
}

.admin-name {
    font-weight: 600;
    font-size: 0.9375rem;
}

.admin-role {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.sidebar.collapsed .admin-info {
    justify-content: center;
}

.sidebar.collapsed .admin-details {
    display: none;
}

.logout-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: transparent;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
}

.logout-btn:hover {
    background: var(--danger);
    border-color: var(--danger);
    color: white;
}

.sidebar.collapsed .logout-btn span {
    display: none;
}

/* ============================================
   MAIN CONTENT
   ============================================ */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
}

.sidebar.collapsed + .main-content {
    margin-left: var(--sidebar-collapsed);
}

/* Top Header */
.top-header {
    height: var(--header-height);
    background: var(--bg-card);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left h2 {
    font-size: 1.5rem;
    font-weight: 700;
}

.header-left .subtitle {
    display: block;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.header-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.header-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--primary);
}

.notification-dot {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 8px;
    height: 8px;
    background: var(--primary);
    border-radius: 50%;
    display: none;
}

.notification-dot.active {
    display: block;
}

.header-time {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.125rem;
}

.header-time span:first-child {
    font-size: 1.125rem;
    font-weight: 600;
}

.header-time span:last-child {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Content Area */
.content-area {
    flex: 1;
    padding: 2rem;
    overflow-y: auto;
}

/* Tab Content */
.tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ============================================
   STATS GRID
   ============================================ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.stat-card.primary .stat-icon {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.stat-card.success .stat-icon {
    background: linear-gradient(135deg, var(--success), #00a344);
}

.stat-card.warning .stat-icon {
    background: linear-gradient(135deg, var(--warning), #cc8800);
}

.stat-card.danger .stat-icon {
    background: linear-gradient(135deg, var(--danger), #cc1233);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 1.5rem;
    color: white;
}

.stat-info {
    flex: 1;
}

.stat-value {
    display: block;
    font-size: 1.875rem;
    font-weight: 700;
    line-height: 1.2;
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.stat-change {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    margin-top: 0.5rem;
    color: var(--text-muted);
}

.stat-change.positive {
    color: var(--success);
}

.stat-change.negative {
    color: var(--danger);
}

/* ============================================
   DASHBOARD GRID
   ============================================ */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.dashboard-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-color);
}

.card-header h3 {
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header h3 i {
    color: var(--primary);
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.card-actions select {
    padding: 0.5rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.875rem;
    cursor: pointer;
}

.view-all {
    font-size: 0.875rem;
    color: var(--primary);
    text-decoration: none;
}

.view-all:hover {
    text-decoration: underline;
}

.card-body {
    padding: 1.25rem;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
}

.quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition);
}

.quick-action:hover {
    background: var(--bg-hover);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.quick-action i {
    font-size: 1.5rem;
    color: var(--primary);
}

.quick-action span {
    font-size: 0.875rem;
    font-weight: 500;
}

.quick-action.warning:hover {
    border-color: var(--warning);
}

.quick-action.warning i {
    color: var(--warning);
}

.quick-action.info:hover {
    border-color: var(--info);
}

.quick-action.info i {
    color: var(--info);
}

/* Mini List */
.mini-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.mini-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-input);
    border-radius: var(--radius-md);
}

.mini-item-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 51, 51, 0.1);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
}

.mini-item-icon i {
    color: var(--primary);
}

.mini-item-info {
    flex: 1;
}

.mini-item-title {
    font-weight: 500;
    font-size: 0.9375rem;
}

.mini-item-meta {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.mini-item-status {
    padding: 0.25rem 0.75rem;
    background: var(--success);
    color: white;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 10px;
}

.mini-item-status.scheduled {
    background: var(--warning);
}

.mini-item-status.expired {
    background: var(--text-muted);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

/* ============================================
   SECTION HEADER
   ============================================ */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
}

.section-title h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.section-title p {
    color: var(--text-secondary);
    font-size: 0.9375rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Buttons */
.btn-primary,
.btn-secondary,
.btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 51, 51, 0.3);
}

.btn-secondary {
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--bg-hover);
    border-color: var(--primary);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    background: #e01236;
    transform: translateY(-2px);
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1rem;
}

/* ============================================
   POPUPS TAB
   ============================================ */
.popup-filters,
.ads-filters,
.visitors-filters,
.logs-filters {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.filter-group select {
    padding: 0.5rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.875rem;
    cursor: pointer;
}

.search-group {
    position: relative;
    flex: 1;
    max-width: 300px;
    margin-left: auto;
}

.search-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.search-group input {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.875rem;
}

.search-group input:focus {
    outline: none;
    border-color: var(--primary);
}

/* Popups Grid */
.popups-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.popup-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: var(--transition);
}

.popup-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.popup-card-header {
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.popup-card-image {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-md);
    overflow: hidden;
    background: var(--bg-input);
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.popup-card-image i {
    font-size: 1.5rem;
    color: var(--text-muted);
}

.popup-card-info {
    flex: 1;
}

.popup-card-title {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.popup-card-type {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
}

.popup-card-status {
    padding: 0.25rem 0.75rem;
    border-radius: 10px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.popup-card-status.active {
    background: rgba(0, 200, 83, 0.15);
    color: var(--success);
}

.popup-card-status.scheduled {
    background: rgba(255, 171, 0, 0.15);
    color: var(--warning);
}

.popup-card-status.expired {
    background: rgba(102, 102, 102, 0.15);
    color: var(--text-muted);
}

.popup-card-body {
    padding: 1.25rem;
}

.popup-card-content {
    font-size: 0.875rem;
    color: var(--text-secondary);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.popup-card-meta {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.popup-card-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.popup-card-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 0.5rem;
}

.popup-card-btn {
    flex: 1;
    padding: 0.5rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
}

.popup-card-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--primary);
}

.popup-card-btn.danger:hover {
    background: var(--danger);
    border-color: var(--danger);
    color: white;
}

/* ============================================
   ADS TAB
   ============================================ */
.ads-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.ad-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    text-align: center;
}

.ad-stat-card i {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 0.75rem;
}

.ad-stat-card .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    display: block;
}

.ad-stat-card .stat-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    margin-top: 0.25rem;
}

.ads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.ad-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.ad-card-preview {
    height: 160px;
    background: var(--bg-input);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.ad-card-preview img,
.ad-card-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ad-card-info {
    padding: 1rem;
}

.ad-card-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.ad-card-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.ad-card-actions {
    display: flex;
    gap: 0.5rem;
    padding: 0 1rem 1rem;
}

/* ============================================
   CONTENT EDITOR
   ============================================ */
.content-editor {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    min-height: 600px;
}

.editor-sidebar {
    background: var(--bg-input);
    border-right: 1px solid var(--border-color);
    padding: 1rem;
}

.editor-sections {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.section-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    background: transparent;
    border: none;
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    font-size: 0.9375rem;
    cursor: pointer;
    transition: var(--transition);
    text-align: left;
}

.section-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

.section-btn.active {
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.15), rgba(255, 51, 51, 0.05));
    color: var(--primary);
}

.section-btn i {
    width: 20px;
    text-align: center;
}

.editor-main {
    padding: 1.5rem;
    overflow-y: auto;
}

.editor-fields {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.editor-field {
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1rem;
}

.editor-field label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.editor-field input,
.editor-field textarea {
    width: 100%;
    padding: 0.75rem;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.9375rem;
    transition: var(--transition);
}

.editor-field input:focus,
.editor-field textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.editor-field textarea {
    resize: vertical;
    min-height: 80px;
}

/* ============================================
   PLANS TAB
   ============================================ */
.plans-categories {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.category-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}

.category-card h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.category-card h3 i {
    color: var(--primary);
}

.plans-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.plan-item {
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.plan-item-info {
    flex: 1;
}

.plan-item-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.plan-item-price {
    font-size: 0.875rem;
    color: var(--primary);
    font-weight: 600;
}

.plan-item-actions {
    display: flex;
    gap: 0.5rem;
}

.plan-item-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
}

.plan-item-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* ============================================
   DISCOUNTS TAB
   ============================================ */
.discounts-info {
    margin-bottom: 2rem;
}

.info-card {
    background: rgba(0, 176, 255, 0.1);
    border: 1px solid rgba(0, 176, 255, 0.2);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.info-card i {
    font-size: 1.5rem;
    color: var(--info);
}

.info-content h4 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-content p {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.discounts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.discounts-list-section {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
}

.section-subheader {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.section-subheader h3 {
    font-size: 1rem;
    font-weight: 600;
}

.count-badge {
    padding: 0.125rem 0.5rem;
    background: var(--primary);
    color: white;
    font-size: 0.6875rem;
    font-weight: 600;
    border-radius: 10px;
}

.discount-item {
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-bottom: 0.75rem;
}

.discount-item:last-child {
    margin-bottom: 0;
}

.discount-item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.discount-item-name {
    font-weight: 600;
}

.discount-item-value {
    padding: 0.25rem 0.75rem;
    background: var(--success);
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: var(--radius-sm);
}

.discount-item-meta {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.discount-item-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

/* ============================================
   VISITORS TAB
   ============================================ */
.visitors-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.visitor-stat {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    text-align: center;
}

.visitor-stat .stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
}

.visitor-stat .stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.visitors-table-container {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    overflow-x: auto;
}

.visitors-table {
    width: 100%;
    border-collapse: collapse;
}

.visitors-table th,
.visitors-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.visitors-table th {
    background: var(--bg-input);
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.visitors-table tr:hover td {
    background: var(--bg-hover);
}

.visitors-table td {
    font-size: 0.875rem;
}

.visitor-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.visitor-location img {
    width: 20px;
    border-radius: 2px;
}

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.page-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition);
}

.page-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
}

/* ============================================
   SETTINGS TAB
   ============================================ */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.settings-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.settings-card.danger {
    border-color: rgba(255, 23, 68, 0.3);
}

.settings-card.danger .card-header h3 {
    color: var(--danger);
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.form-group.checkbox,
.form-group.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-group.checkbox label,
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.9375rem;
    color: var(--text-primary);
}

.form-group.checkbox input,
.checkbox-label input {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.9375rem;
    transition: var(--transition);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.help-text {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.color-picker {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.color-picker input[type="color"] {
    width: 50px;
    height: 40px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.danger-action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: rgba(255, 23, 68, 0.05);
    border: 1px solid rgba(255, 23, 68, 0.2);
    border-radius: var(--radius-md);
}

.danger-info h4 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.danger-info p {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.settings-actions {
    display: flex;
    justify-content: flex-end;
}

/* ============================================
   MODALS
   ============================================ */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

.modal-content.modal-large {
    max-width: 700px;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-color);
}

.modal-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
}

.modal-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-input);
    border: none;
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
}

.modal-close:hover {
    background: var(--danger);
    color: white;
}

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    max-height: calc(90vh - 140px);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--border-color);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Image Upload */
.image-upload {
    position: relative;
}

.image-upload input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.upload-area {
    padding: 2rem;
    background: var(--bg-input);
    border: 2px dashed var(--border-color);
    border-radius: var(--radius-md);
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
}

.upload-area:hover {
    border-color: var(--primary);
    background: rgba(255, 51, 51, 0.05);
}

.upload-area i {
    font-size: 2rem;
    color: var(--primary);
    margin-bottom: 0.75rem;
    display: block;
}

.upload-area span {
    display: block;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.upload-area small {
    color: var(--text-muted);
}

.image-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--bg-input);
    border-radius: var(--radius-md);
}

.image-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: var(--radius-sm);
}

/* Checkbox List */
.checkbox-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    max-height: 200px;
    overflow-y: auto;
    padding: 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
}

.checkbox-list label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

/* ============================================
   TOAST NOTIFICATIONS
   ============================================ */
.toast-container {
    position: fixed;
    top: 1.5rem;
    right: 1.5rem;
    z-index: 10001;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.toast {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    animation: toastSlideIn 0.3s ease;
    min-width: 300px;
}

@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.toast.success {
    border-left: 3px solid var(--success);
}

.toast.error {
    border-left: 3px solid var(--danger);
}

.toast.warning {
    border-left: 3px solid var(--warning);
}

.toast.info {
    border-left: 3px solid var(--info);
}

.toast-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.toast.success .toast-icon {
    background: rgba(0, 200, 83, 0.15);
    color: var(--success);
}

.toast.error .toast-icon {
    background: rgba(255, 23, 68, 0.15);
    color: var(--danger);
}

.toast.warning .toast-icon {
    background: rgba(255, 171, 0, 0.15);
    color: var(--warning);
}

.toast.info .toast-icon {
    background: rgba(0, 176, 255, 0.15);
    color: var(--info);
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 0.9375rem;
}

.toast-message {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-top: 0.125rem;
}

.toast-close {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.25rem;
    transition: var(--transition);
}

.toast-close:hover {
    color: var(--text-primary);
}

/* ============================================
   LOADING OVERLAY
   ============================================ */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10002;
}

.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.loading-spinner i {
    font-size: 3rem;
    color: var(--primary);
}

.loading-spinner span {
    font-size: 1rem;
    color: var(--text-secondary);
}

/* ============================================
   LOGS
   ============================================ */
.logs-container {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    max-height: 600px;
    overflow-y: auto;
}

.logs-list {
    display: flex;
    flex-direction: column;
}

.log-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.log-item:last-child {
    border-bottom: none;
}

.log-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    flex-shrink: 0;
}

.log-item.visitor .log-icon {
    background: rgba(0, 176, 255, 0.15);
    color: var(--info);
}

.log-item.order .log-icon {
    background: rgba(0, 200, 83, 0.15);
    color: var(--success);
}

.log-item.admin .log-icon {
    background: rgba(255, 171, 0, 0.15);
    color: var(--warning);
}

.log-item.error .log-icon {
    background: rgba(255, 23, 68, 0.15);
    color: var(--danger);
}

.log-content {
    flex: 1;
}

.log-message {
    font-size: 0.9375rem;
}

.log-meta {
    display: flex;
    gap: 1rem;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* ============================================
   ANALYTICS
   ============================================ */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.analytics-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.analytics-card.full {
    grid-column: 1 / -1;
}

.chart-filters {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
}

.filter-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

.filter-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.country-list,
.page-views-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.country-item,
.page-view-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: var(--bg-input);
    border-radius: var(--radius-sm);
}

.country-flag {
    font-size: 1.5rem;
}

.country-name,
.page-view-name {
    flex: 1;
    font-size: 0.9375rem;
}

.country-count,
.page-view-count {
    font-weight: 600;
    color: var(--primary);
}

.country-bar,
.page-view-bar {
    width: 100px;
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    overflow: hidden;
}

.country-bar-fill,
.page-view-bar-fill {
    height: 100%;
    background: var(--primary);
    border-radius: 3px;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1024px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .content-editor {
        grid-template-columns: 1fr;
    }
    
    .editor-sidebar {
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
    
    .editor-sections {
        flex-direction: row;
        overflow-x: auto;
    }
    
    .section-btn {
        white-space: nowrap;
    }
}

@media (max-width: 768px) {
    .top-header {
        padding: 0 1rem;
    }
    
    .header-left .subtitle {
        display: none;
    }
    
    .header-time {
        display: none;
    }
    
    .content-area {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .popup-filters,
    .ads-filters,
    .visitors-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-group {
        max-width: none;
        margin-left: 0;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .login-container {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .popups-grid,
    .ads-grid {
        grid-template-columns: 1fr;
    }
    
    .discounts-grid {
        grid-template-columns: 1fr;
    }
}

/* ============================================
   ANIMATIONS
   ============================================ */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Setup Steps */
.setup-steps {
    list-style: none;
    counter-reset: step;
}

.setup-steps li {
    position: relative;
    padding-left: 2.5rem;
    margin-bottom: 1rem;
    font-size: 0.9375rem;
    color: var(--text-secondary);
}

.setup-steps li::before {
    counter-increment: step;
    content: counter(step);
    position: absolute;
    left: 0;
    top: 0;
    width: 24px;
    height: 24px;
    background: var(--primary);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.setup-steps a {
    color: var(--primary);
    text-decoration: none;
}

.setup-steps a:hover {
    text-decoration: underline;
}

.setup-steps code {
    background: var(--bg-input);
    padding: 0.125rem 0.5rem;
    border-radius: var(--radius-sm);
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.875rem;
}
