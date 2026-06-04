<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KillByte Admin Panel</title>
    <link rel="icon" type="image/png" href="../logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <!-- Login Screen -->
    <div id="loginScreen" class="login-screen">
        <div class="login-container">
            <div class="login-logo">
                <i class="fas fa-shield-alt"></i>
                <h1>KillByte Admin</h1>
            </div>
            <div class="login-form">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="loginUsername" placeholder="Username" autocomplete="off">
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="loginPassword" placeholder="Password">
                </div>
                <button onclick="adminAuth.login()" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Access Panel</span>
                </button>
                <div id="loginError" class="login-error"></div>
            </div>
            <div class="login-footer">
                <p>Protected Area - Authorized Access Only</p>
            </div>
        </div>
    </div>

    <!-- Admin Dashboard -->
    <div id="adminDashboard" class="admin-dashboard hidden">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="brand">
                    <i class="fas fa-bolt"></i>
                    <span>KillByte Admin</span>
                </div>
                <button class="sidebar-toggle" onclick="adminUI.toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-label">Main</span>
                    <a href="#" class="nav-item active" data-tab="dashboard" onclick="adminUI.switchTab('dashboard')">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="analytics" onclick="adminUI.switchTab('analytics')">
                        <i class="fas fa-chart-pie"></i>
                        <span>Analytics</span>
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-label">Content</span>
                    <a href="#" class="nav-item" data-tab="popups" onclick="adminUI.switchTab('popups')">
                        <i class="fas fa-bullhorn"></i>
                        <span>Popups</span>
                        <span id="popupBadge" class="badge">0</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="ads" onclick="adminUI.switchTab('ads')">
                        <i class="fas fa-ad"></i>
                        <span>Advertisements</span>
                        <span id="adsBadge" class="badge">0</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="content" onclick="adminUI.switchTab('content')">
                        <i class="fas fa-edit"></i>
                        <span>Page Content</span>
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-label">Sales</span>
                    <a href="#" class="nav-item" data-tab="plans" onclick="adminUI.switchTab('plans')">
                        <i class="fas fa-layer-group"></i>
                        <span>Plans</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="discounts" onclick="adminUI.switchTab('discounts')">
                        <i class="fas fa-percent"></i>
                        <span>Discounts</span>
                        <span id="discountBadge" class="badge hidden">0</span>
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-label">Management</span>
                    <a href="#" class="nav-item" data-tab="visitors" onclick="adminUI.switchTab('visitors')">
                        <i class="fas fa-users"></i>
                        <span>Visitors</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="users" onclick="adminUI.switchTab('users')">
                        <i class="fas fa-user-shield"></i>
                        <span>Users</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="telegram" onclick="adminUI.switchTab('telegram')">
                        <i class="fab fa-telegram"></i>
                        <span>Telegram</span>
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-label">System</span>
                    <a href="#" class="nav-item" data-tab="settings" onclick="adminUI.switchTab('settings')">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="#" class="nav-item" data-tab="logs" onclick="adminUI.switchTab('logs')">
                        <i class="fas fa-file-alt"></i>
                        <span>Logs</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="admin-details">
                        <span class="admin-name" id="adminName">Admin</span>
                        <span class="admin-role">Super Admin</span>
                    </div>
                </div>
                <button onclick="adminAuth.logout()" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <h2 id="pageTitle">Dashboard</h2>
                    <span id="pageSubtitle" class="subtitle">Welcome back, Admin</span>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="header-btn" onclick="adminUI.toggleFullscreen()" title="Fullscreen">
                            <i class="fas fa-expand"></i>
                        </button>
                        <button class="header-btn" onclick="adminUI.refreshData()" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="header-btn" onclick="adminUI.openNotifications()" title="Notifications">
                            <i class="fas fa-bell"></i>
                            <span id="notificationBadge" class="notification-dot"></span>
                        </button>
                    </div>
                    <div class="header-time">
                        <span id="currentTime">--:--</span>
                        <span id="currentDate">--/--/----</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Dashboard Tab -->
                <div id="tab-dashboard" class="tab-content active">
                    <div class="stats-grid">
                        <div class="stat-card primary">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-value" id="dashTotalVisitors">0</span>
                                <span class="stat-label">Total Visitors</span>
                                <span class="stat-change positive">
                                    <i class="fas fa-arrow-up"></i> <span id="visitorChange">0%</span>
                                </span>
                            </div>
                        </div>
                        <div class="stat-card success">
                            <div class="stat-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-value" id="dashTodayVisitors">0</span>
                                <span class="stat-label">Today's Visitors</span>
                                <span class="stat-change positive">Live</span>
                            </div>
                        </div>
                        <div class="stat-card warning">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-value" id="dashTotalOrders">0</span>
                                <span class="stat-label">Total Orders</span>
                                <span class="stat-change">
                                    <span id="orderChange">$0</span> revenue
                                </span>
                            </div>
                        </div>
                        <div class="stat-card danger">
                            <div class="stat-icon">
                                <i class="fas fa-percent"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-value" id="dashActiveDiscounts">0</span>
                                <span class="stat-label">Active Discounts</span>
                                <span class="stat-change" id="discountStatus">No active</span>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-chart-area"></i> Visitor Analytics</h3>
                                <div class="card-actions">
                                    <select id="chartPeriod" onchange="adminAnalytics.updateChart()">
                                        <option value="7">Last 7 Days</option>
                                        <option value="30">Last 30 Days</option>
                                        <option value="90">Last 3 Months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="visitorChart"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="quick-actions">
                                    <button onclick="adminUI.switchTab('popups')" class="quick-action">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Add Popup</span>
                                    </button>
                                    <button onclick="adminUI.switchTab('discounts')" class="quick-action">
                                        <i class="fas fa-tag"></i>
                                        <span>Add Discount</span>
                                    </button>
                                    <button onclick="adminUI.switchTab('ads')" class="quick-action">
                                        <i class="fas fa-upload"></i>
                                        <span>Upload Ad</span>
                                    </button>
                                    <button onclick="adminUI.switchTab('content')" class="quick-action">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit Content</span>
                                    </button>
                                    <button onclick="adminContent.clearCache()" class="quick-action warning">
                                        <i class="fas fa-broom"></i>
                                        <span>Clear Cache</span>
                                    </button>
                                    <button onclick="adminSettings.exportData()" class="quick-action info">
                                        <i class="fas fa-download"></i>
                                        <span>Export Data</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-bullhorn"></i> Active Popups</h3>
                                <a href="#" onclick="adminUI.switchTab('popups')" class="view-all">View All</a>
                            </div>
                            <div class="card-body">
                                <div id="dashActivePopups" class="mini-list">
                                    <div class="empty-state">No active popups</div>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-percent"></i> Active Discounts</h3>
                                <a href="#" onclick="adminUI.switchTab('discounts')" class="view-all">View All</a>
                            </div>
                            <div class="card-body">
                                <div id="dashActiveDiscountsList" class="mini-list">
                                    <div class="empty-state">No active discounts</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Tab -->
                <div id="tab-analytics" class="tab-content">
                    <div class="analytics-grid">
                        <div class="analytics-card full">
                            <div class="card-header">
                                <h3><i class="fas fa-chart-line"></i> Traffic Overview</h3>
                                <div class="chart-filters">
                                    <button class="filter-btn active" data-period="day">Day</button>
                                    <button class="filter-btn" data-period="week">Week</button>
                                    <button class="filter-btn" data-period="month">Month</button>
                                    <button class="filter-btn" data-period="year">Year</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="trafficChart"></canvas>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="card-header">
                                <h3><i class="fas fa-globe"></i> Top Countries</h3>
                            </div>
                            <div class="card-body">
                                <div id="countriesList" class="country-list"></div>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="card-header">
                                <h3><i class="fas fa-desktop"></i> Device Stats</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="deviceChart"></canvas>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="card-header">
                                <h3><i class="fas fa-clock"></i> Peak Hours</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="hoursChart"></canvas>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="card-header">
                                <h3><i class="fas fa-mouse-pointer"></i> Page Views</h3>
                            </div>
                            <div class="card-body">
                                <div id="pageViewsList" class="page-views-list"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popups Tab -->
                <div id="tab-popups" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Popup Announcements</h2>
                            <p>Create and manage popup announcements for your visitors</p>
                        </div>
                        <button onclick="adminPopup.openModal()" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Create Popup</span>
                        </button>
                    </div>

                    <div class="popup-filters">
                        <div class="filter-group">
                            <label>Status:</label>
                            <select id="popupStatusFilter" onchange="adminPopup.filterPopups()">
                                <option value="all">All</option>
                                <option value="active">Active</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Type:</label>
                            <select id="popupTypeFilter" onchange="adminPopup.filterPopups()">
                                <option value="all">All Types</option>
                                <option value="announcement">Announcement</option>
                                <option value="promotion">Promotion</option>
                                <option value="warning">Warning</option>
                                <option value="ad">Advertisement</option>
                            </select>
                        </div>
                        <div class="search-group">
                            <i class="fas fa-search"></i>
                            <input type="text" id="popupSearch" placeholder="Search popups..." onkeyup="adminPopup.filterPopups()">
                        </div>
                    </div>

                    <div id="popupsList" class="popups-grid">
                        <!-- Popups will be rendered here -->
                    </div>
                </div>

                <!-- Ads Tab -->
                <div id="tab-ads" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Advertisements</h2>
                            <p>Upload and manage advertisement banners and GIFs</p>
                        </div>
                        <button onclick="adminAds.openUploadModal()" class="btn-primary">
                            <i class="fas fa-upload"></i>
                            <span>Upload Ad</span>
                        </button>
                    </div>

                    <div class="ads-stats">
                        <div class="ad-stat-card">
                            <i class="fas fa-image"></i>
                            <span class="stat-value" id="totalAds">0</span>
                            <span class="stat-label">Total Ads</span>
                        </div>
                        <div class="ad-stat-card">
                            <i class="fas fa-eye"></i>
                            <span class="stat-value" id="totalAdViews">0</span>
                            <span class="stat-label">Total Views</span>
                        </div>
                        <div class="ad-stat-card">
                            <i class="fas fa-mouse-pointer"></i>
                            <span class="stat-value" id="totalAdClicks">0</span>
                            <span class="stat-label">Total Clicks</span>
                        </div>
                        <div class="ad-stat-card">
                            <i class="fas fa-chart-line"></i>
                            <span class="stat-value" id="avgCTR">0%</span>
                            <span class="stat-label">Avg CTR</span>
                        </div>
                    </div>

                    <div class="ads-filters">
                        <div class="filter-group">
                            <label>Position:</label>
                            <select id="adPositionFilter" onchange="adminAds.filterAds()">
                                <option value="all">All Positions</option>
                                <option value="popup">Popup</option>
                                <option value="banner">Banner</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Type:</label>
                            <select id="adTypeFilter" onchange="adminAds.filterAds()">
                                <option value="all">All Types</option>
                                <option value="gif">GIF</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                    </div>

                    <div id="adsList" class="ads-grid">
                        <!-- Ads will be rendered here -->
                    </div>
                </div>

                <!-- Content Tab -->
                <div id="tab-content" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Page Content Editor</h2>
                            <p>Edit all text content on your website</p>
                        </div>
                        <div class="header-actions">
                            <button onclick="adminContent.resetToDefault()" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                <span>Reset Default</span>
                            </button>
                            <button onclick="adminContent.saveAllChanges()" class="btn-primary">
                                <i class="fas fa-save"></i>
                                <span>Save All Changes</span>
                            </button>
                        </div>
                    </div>

                    <div class="content-editor">
                        <div class="editor-sidebar">
                            <div class="editor-sections">
                                <button class="section-btn active" data-section="hero" onclick="adminContent.switchSection('hero')">
                                    <i class="fas fa-home"></i>
                                    <span>Hero Section</span>
                                </button>
                                <button class="section-btn" data-section="about" onclick="adminContent.switchSection('about')">
                                    <i class="fas fa-info-circle"></i>
                                    <span>About Section</span>
                                </button>
                                <button class="section-btn" data-section="tiers" onclick="adminContent.switchSection('tiers')">
                                    <i class="fas fa-layer-group"></i>
                                    <span>Tiers Section</span>
                                </button>
                                <button class="section-btn" data-section="plans" onclick="adminContent.switchSection('plans')">
                                    <i class="fas fa-tags"></i>
                                    <span>Plans Section</span>
                                </button>
                                <button class="section-btn" data-section="enterprise" onclick="adminContent.switchSection('enterprise')">
                                    <i class="fas fa-building"></i>
                                    <span>Enterprise Section</span>
                                </button>
                                <button class="section-btn" data-section="support" onclick="adminContent.switchSection('support')">
                                    <i class="fas fa-headset"></i>
                                    <span>Support Section</span>
                                </button>
                                <button class="section-btn" data-section="footer" onclick="adminContent.switchSection('footer')">
                                    <i class="fas fa-shoe-prints"></i>
                                    <span>Footer</span>
                                </button>
                            </div>
                        </div>

                        <div class="editor-main">
                            <div id="contentEditorFields" class="editor-fields">
                                <!-- Dynamic fields will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plans Tab -->
                <div id="tab-plans" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Plans Management</h2>
                            <p>Manage all service plans and pricing</p>
                        </div>
                        <button onclick="adminPlans.openModal()" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Add New Plan</span>
                        </button>
                    </div>

                    <div class="plans-categories">
                        <div class="category-card">
                            <h3><i class="fas fa-calendar-alt"></i> Monthly Plans</h3>
                            <div id="monthlyPlansList" class="plans-list"></div>
                        </div>
                        <div class="category-card">
                            <h3><i class="fas fa-sun"></i> Daily Plans</h3>
                            <div id="dailyPlansList" class="plans-list"></div>
                        </div>
                        <div class="category-card">
                            <h3><i class="fas fa-puzzle-piece"></i> Add-ons</h3>
                            <div id="addonsList" class="plans-list"></div>
                        </div>
                    </div>
                </div>

                <!-- Discounts Tab -->
                <div id="tab-discounts" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Discount Management</h2>
                            <p>Create and manage discounts for your plans</p>
                        </div>
                        <button onclick="adminDiscounts.openModal()" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Create Discount</span>
                        </button>
                    </div>

                    <div class="discounts-info">
                        <div class="info-card">
                            <i class="fas fa-info-circle"></i>
                            <div class="info-content">
                                <h4>How Discounts Work</h4>
                                <p>Discounts can be applied to all plans or specific plans only. Set start and end dates for automatic expiration.</p>
                            </div>
                        </div>
                    </div>

                    <div class="discounts-grid">
                        <div class="discounts-list-section">
                            <div class="section-subheader">
                                <h3>Active Discounts</h3>
                                <span id="activeDiscountCount" class="count-badge">0</span>
                            </div>
                            <div id="activeDiscountsList" class="discounts-list"></div>
                        </div>

                        <div class="discounts-list-section">
                            <div class="section-subheader">
                                <h3>Scheduled Discounts</h3>
                                <span id="scheduledDiscountCount" class="count-badge">0</span>
                            </div>
                            <div id="scheduledDiscountsList" class="discounts-list"></div>
                        </div>

                        <div class="discounts-list-section">
                            <div class="section-subheader">
                                <h3>Expired Discounts</h3>
                                <span id="expiredDiscountCount" class="count-badge">0</span>
                            </div>
                            <div id="expiredDiscountsList" class="discounts-list"></div>
                        </div>
                    </div>
                </div>

                <!-- Visitors Tab -->
                <div id="tab-visitors" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Visitor Tracking</h2>
                            <p>Monitor and analyze visitor activity</p>
                        </div>
                        <button onclick="adminVisitors.exportData()" class="btn-secondary">
                            <i class="fas fa-download"></i>
                            <span>Export Data</span>
                        </button>
                    </div>

                    <div class="visitors-stats">
                        <div class="visitor-stat">
                            <span class="stat-value" id="visitorsTotal">0</span>
                            <span class="stat-label">Total Visitors</span>
                        </div>
                        <div class="visitor-stat">
                            <span class="stat-value" id="visitorsToday">0</span>
                            <span class="stat-label">Today</span>
                        </div>
                        <div class="visitor-stat">
                            <span class="stat-value" id="visitorsOnline">0</span>
                            <span class="stat-label">Online Now</span>
                        </div>
                        <div class="visitor-stat">
                            <span class="stat-value" id="visitorsUnique">0</span>
                            <span class="stat-label">Unique</span>
                        </div>
                    </div>

                    <div class="visitors-filters">
                        <div class="search-group">
                            <i class="fas fa-search"></i>
                            <input type="text" id="visitorSearch" placeholder="Search by IP, country, city..." onkeyup="adminVisitors.filterVisitors()">
                        </div>
                        <div class="filter-group">
                            <label>Country:</label>
                            <select id="visitorCountryFilter" onchange="adminVisitors.filterVisitors()">
                                <option value="all">All Countries</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Date Range:</label>
                            <select id="visitorDateFilter" onchange="adminVisitors.filterVisitors()">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>

                    <div class="visitors-table-container">
                        <table class="visitors-table">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Device</th>
                                    <th>Page</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="visitorsTableBody">
                                <!-- Visitors will be rendered here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination">
                        <button onclick="adminVisitors.prevPage()" class="page-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="pageInfo">Page 1 of 1</span>
                        <button onclick="adminVisitors.nextPage()" class="page-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Users Tab -->
                <div id="tab-users" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>User Management</h2>
                            <p>Manage admin users and permissions</p>
                        </div>
                        <button onclick="adminUsers.openAddModal()" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Add User</span>
                        </button>
                    </div>

                    <div class="users-list" id="usersList">
                        <!-- Users will be rendered here -->
                    </div>
                </div>

                <!-- Telegram Tab -->
                <div id="tab-telegram" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Telegram Configuration</h2>
                            <p>Configure Telegram bot for notifications</p>
                        </div>
                    </div>

                    <div class="telegram-config">
                        <div class="config-card">
                            <div class="card-header">
                                <h3><i class="fab fa-telegram"></i> Bot Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Bot Token</label>
                                    <input type="text" id="telegramBotToken" class="form-input" placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                                    <span class="help-text">Get from @BotFather</span>
                                </div>
                                <div class="form-group">
                                    <label>Chat ID</label>
                                    <input type="text" id="telegramChatId" class="form-input" placeholder="-1001234567890">
                                    <span class="help-text">Use @userinfobot to get your Chat ID</span>
                                </div>
                                <div class="form-group checkbox">
                                    <label>
                                        <input type="checkbox" id="telegramNotifyVisitors" checked>
                                        <span>Notify on new visitors</span>
                                    </label>
                                </div>
                                <div class="form-group checkbox">
                                    <label>
                                        <input type="checkbox" id="telegramNotifyOrders" checked>
                                        <span>Notify on new orders</span>
                                    </label>
                                </div>
                                <div class="form-actions">
                                    <button onclick="adminTelegram.saveConfig()" class="btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>Save Configuration</span>
                                    </button>
                                    <button onclick="adminTelegram.testConnection()" class="btn-secondary">
                                        <i class="fas fa-plug"></i>
                                        <span>Test Connection</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="config-card">
                            <div class="card-header">
                                <h3><i class="fas fa-book"></i> Setup Guide</h3>
                            </div>
                            <div class="card-body">
                                <ol class="setup-steps">
                                    <li>Message <a href="https://t.me/BotFather" target="_blank">@BotFather</a> on Telegram</li>
                                    <li>Create new bot with <code>/newbot</code> command</li>
                                    <li>Copy the bot token provided</li>
                                    <li>Start a chat with your bot</li>
                                    <li>Use <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a> to get your Chat ID</li>
                                    <li>Paste both values above and save</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="tab-settings" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Site Settings</h2>
                            <p>Configure global site settings</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="settings-card">
                            <div class="card-header">
                                <h3><i class="fas fa-globe"></i> General Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Site Title</label>
                                    <input type="text" id="siteTitle" class="form-input" value="KillByte Solutions">
                                </div>
                                <div class="form-group">
                                    <label>Site Description</label>
                                    <textarea id="siteDescription" class="form-textarea" rows="3">Premium L7 & L4 Infrastructure</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Admin Contact</label>
                                    <input type="text" id="adminContact" class="form-input" value="@rankflood">
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="card-header">
                                <h3><i class="fas fa-palette"></i> Appearance</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Primary Color</label>
                                    <div class="color-picker">
                                        <input type="color" id="primaryColor" value="#ff3333">
                                        <span>#ff3333</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Theme Mode</label>
                                    <select id="themeMode" class="form-select">
                                        <option value="dark">Dark</option>
                                        <option value="light">Light</option>
                                        <option value="auto">Auto</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="card-header">
                                <h3><i class="fas fa-shield-alt"></i> Security</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group checkbox">
                                    <label>
                                        <input type="checkbox" id="enableVisitorTracking" checked>
                                        <span>Enable Visitor Tracking</span>
                                    </label>
                                </div>
                                <div class="form-group checkbox">
                                    <label>
                                        <input type="checkbox" id="enableNotifications" checked>
                                        <span>Enable Notifications</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Session Timeout (minutes)</label>
                                    <input type="number" id="sessionTimeout" class="form-input" value="30" min="5" max="120">
                                </div>
                            </div>
                        </div>

                        <div class="settings-card danger">
                            <div class="card-header">
                                <h3><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
                            </div>
                            <div class="card-body">
                                <div class="danger-actions">
                                    <div class="danger-action">
                                        <div class="danger-info">
                                            <h4>Clear All Data</h4>
                                            <p>Delete all visitors, settings, and configurations</p>
                                        </div>
                                        <button onclick="adminSettings.clearAllData()" class="btn-danger">Clear Data</button>
                                    </div>
                                    <div class="danger-action">
                                        <div class="danger-info">
                                            <h4>Reset to Default</h4>
                                            <p>Reset all settings to factory defaults</p>
                                        </div>
                                        <button onclick="adminSettings.resetDefaults()" class="btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-actions">
                        <button onclick="adminSettings.saveAll()" class="btn-primary btn-large">
                            <i class="fas fa-save"></i>
                            <span>Save All Settings</span>
                        </button>
                    </div>
                </div>

                <!-- Logs Tab -->
                <div id="tab-logs" class="tab-content">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>System Logs</h2>
                            <p>View system activity logs</p>
                        </div>
                        <button onclick="adminLogs.clearLogs()" class="btn-danger">
                            <i class="fas fa-trash"></i>
                            <span>Clear Logs</span>
                        </button>
                    </div>

                    <div class="logs-filters">
                        <div class="filter-group">
                            <label>Log Type:</label>
                            <select id="logTypeFilter" onchange="adminLogs.filterLogs()">
                                <option value="all">All Types</option>
                                <option value="visitor">Visitors</option>
                                <option value="order">Orders</option>
                                <option value="admin">Admin Actions</option>
                                <option value="error">Errors</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Date:</label>
                            <input type="date" id="logDateFilter" onchange="adminLogs.filterLogs()">
                        </div>
                    </div>

                    <div class="logs-container">
                        <div id="logsList" class="logs-list"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Popup Modal -->
    <div id="popupModal" class="modal">
        <div class="modal-overlay" onclick="adminPopup.closeModal()"></div>
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3 id="popupModalTitle">Create Popup</h3>
                <button onclick="adminPopup.closeModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="popupForm" class="popup-form">
                    <input type="hidden" id="popupId">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Popup Title *</label>
                            <input type="text" id="popupTitle" class="form-input" placeholder="Enter popup title" required>
                        </div>
                        <div class="form-group">
                            <label>Popup Type</label>
                            <select id="popupType" class="form-select">
                                <option value="announcement">Announcement</option>
                                <option value="promotion">Promotion</option>
                                <option value="warning">Warning</option>
                                <option value="ad">Advertisement</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Popup Content *</label>
                        <textarea id="popupContent" class="form-textarea" rows="4" placeholder="Enter popup message content..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Design Style</label>
                            <select id="popupDesign" class="form-select">
                                <option value="default">Default</option>
                                <option value="modern">Modern</option>
                                <option value="minimal">Minimal</option>
                                <option value="gradient">Gradient</option>
                                <option value="dark">Dark</option>
                                <option value="colorful">Colorful</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <select id="popupPosition" class="form-select">
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                                <option value="top-left">Top Left</option>
                                <option value="top-right">Top Right</option>
                                <option value="bottom-left">Bottom Left</option>
                                <option value="bottom-right">Bottom Right</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Popup Image (Optional)</label>
                        <div class="image-upload">
                            <input type="file" id="popupImage" accept="image/*,.gif" onchange="adminPopup.previewImage(this)">
                            <div class="upload-area" onclick="document.getElementById('popupImage').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload image or GIF</span>
                                <small>Max size: 5MB</small>
                            </div>
                            <div id="popupImagePreview" class="image-preview hidden"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="datetime-local" id="popupStartDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label>End Date (Leave empty for no expiry)</label>
                            <input type="datetime-local" id="popupEndDate" class="form-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Display Duration (days)</label>
                            <input type="number" id="popupDuration" class="form-input" value="7" min="1" max="365">
                        </div>
                        <div class="form-group">
                            <label>Delay Before Show (seconds)</label>
                            <input type="number" id="popupDelay" class="form-input" value="3" min="0" max="60">
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="popupShowOnce" checked>
                            <span>Show only once per visitor</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="popupCloseable" checked>
                            <span>Allow closing</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="popupBackdrop" checked>
                            <span>Show backdrop</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="popupActive" checked>
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Button Text (Optional)</label>
                        <input type="text" id="popupButtonText" class="form-input" placeholder="e.g., Learn More, Get Started">
                    </div>

                    <div class="form-group">
                        <label>Button Link (Optional)</label>
                        <input type="url" id="popupButtonLink" class="form-input" placeholder="https://...">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="adminPopup.closeModal()" class="btn-secondary">Cancel</button>
                <button onclick="adminPopup.savePopup()" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Save Popup</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Discount Modal -->
    <div id="discountModal" class="modal">
        <div class="modal-overlay" onclick="adminDiscounts.closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="discountModalTitle">Create Discount</h3>
                <button onclick="adminDiscounts.closeModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="discountForm">
                    <input type="hidden" id="discountId">
                    
                    <div class="form-group">
                        <label>Discount Name *</label>
                        <input type="text" id="discountName" class="form-input" placeholder="e.g., Summer Sale" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Discount Type</label>
                            <select id="discountType" class="form-select">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Discount Value *</label>
                            <input type="number" id="discountValue" class="form-input" placeholder="10" min="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Apply To</label>
                        <select id="discountApplyTo" class="form-select" onchange="adminDiscounts.togglePlanSelection()">
                            <option value="all">All Plans</option>
                            <option value="selected">Selected Plans Only</option>
                        </select>
                    </div>

                    <div id="planSelection" class="form-group hidden">
                        <label>Select Plans</label>
                        <div id="planCheckboxes" class="checkbox-list"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="datetime-local" id="discountStartDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label>End Date *</label>
                            <input type="datetime-local" id="discountEndDate" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Promo Code (Optional)</label>
                        <input type="text" id="discountCode" class="form-input" placeholder="e.g., SUMMER2024">
                        <span class="help-text">Leave empty for automatic discount</span>
                    </div>

                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" id="discountActive" checked>
                            <span>Active</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="adminDiscounts.closeModal()" class="btn-secondary">Cancel</button>
                <button onclick="adminDiscounts.saveDiscount()" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Save Discount</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ad Upload Modal -->
    <div id="adUploadModal" class="modal">
        <div class="modal-overlay" onclick="adminAds.closeUploadModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Upload Advertisement</h3>
                <button onclick="adminAds.closeUploadModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="adUploadForm">
                    <div class="form-group">
                        <label>Ad Name *</label>
                        <input type="text" id="adName" class="form-input" placeholder="Enter ad name" required>
                    </div>

                    <div class="form-group">
                        <label>Ad File *</label>
                        <div class="image-upload">
                            <input type="file" id="adFile" accept="image/*,.gif,video/*" onchange="adminAds.previewAd(this)" required>
                            <div class="upload-area" onclick="document.getElementById('adFile').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload ad file</span>
                                <small>Supported: JPG, PNG, GIF, MP4 | Max: 10MB</small>
                            </div>
                            <div id="adFilePreview" class="image-preview hidden"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Position</label>
                            <select id="adPosition" class="form-select">
                                <option value="popup">Popup</option>
                                <option value="banner">Banner</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="inline">Inline</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Priority</label>
                            <input type="number" id="adPriority" class="form-input" value="1" min="1" max="10">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Link URL (Optional)</label>
                        <input type="url" id="adLink" class="form-input" placeholder="https://...">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="datetime-local" id="adStartDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="datetime-local" id="adEndDate" class="form-input">
                        </div>
                    </div>

                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" id="adActive" checked>
                            <span>Active</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="adminAds.closeUploadModal()" class="btn-secondary">Cancel</button>
                <button onclick="adminAds.uploadAd()" class="btn-primary">
                    <i class="fas fa-upload"></i>
                    <span>Upload Ad</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay hidden">
        <div class="loading-spinner">
            <i class="fas fa-circle-notch fa-spin"></i>
            <span>Loading...</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/admin-core.js"></script>
    <script src="js/admin-auth.js"></script>
    <script src="js/admin-ui.js"></script>
    <script src="js/admin-popup.js"></script>
    <script src="js/admin-ads.js"></script>
    <script src="js/admin-content.js"></script>
    <script src="js/admin-plans.js"></script>
    <script src="js/admin-discounts.js"></script>
    <script src="js/admin-analytics.js"></script>
    <script src="js/admin-visitors.js"></script>
    <script src="js/admin-users.js"></script>
    <script src="js/admin-telegram.js"></script>
    <script src="js/admin-settings.js"></script>
    <script src="js/admin-logs.js"></script>
</body>
</html>
