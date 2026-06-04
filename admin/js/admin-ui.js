/* ============================================
   KILLBYTE ADMIN - UI MODULE
   UI interactions and tab switching
   ============================================ */

const adminUI = {
    currentTab: 'dashboard',
    sidebarCollapsed: false,
    notifications: [],

    // Initialize UI
    init() {
        this.setupEventListeners();
        this.loadNotifications();
        console.log('[AdminUI] Initialized');
    },

    // Setup event listeners
    setupEventListeners() {
        // Handle window resize
        window.addEventListener('resize', AdminCore.debounce(() => {
            this.handleResize();
        }, 250));

        // Handle keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (!adminAuth.isAuthenticated) return;
            
            // Ctrl/Cmd + number for tabs
            if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '9') {
                e.preventDefault();
                const tabs = ['dashboard', 'analytics', 'popups', 'ads', 'content', 'plans', 'discounts', 'visitors', 'users', 'telegram', 'settings', 'logs'];
                const tabIndex = parseInt(e.key) - 1;
                if (tabs[tabIndex]) {
                    this.switchTab(tabs[tabIndex]);
                }
            }

            // Escape to close modals
            if (e.key === 'Escape') {
                this.closeAllModals();
            }

            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.handleSaveShortcut();
            }
        });

        // Handle before unload
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    },

    // Switch tab
    switchTab(tabName) {
        if (!adminAuth.isAuthenticated) {
            adminAuth.showLogin();
            return;
        }

        // Update current tab
        this.currentTab = tabName;

        // Update nav items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.tab === tabName) {
                item.classList.add('active');
            }
        });

        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        const tabContent = document.getElementById(`tab-${tabName}`);
        if (tabContent) {
            tabContent.classList.add('active');
        }

        // Update page title
        const pageTitles = {
            dashboard: { title: 'Dashboard', subtitle: 'Welcome back, Admin' },
            analytics: { title: 'Analytics', subtitle: 'Detailed traffic analysis' },
            popups: { title: 'Popups', subtitle: 'Manage popup announcements' },
            ads: { title: 'Advertisements', subtitle: 'Manage ads and banners' },
            content: { title: 'Page Content', subtitle: 'Edit website content' },
            plans: { title: 'Plans', subtitle: 'Manage service plans' },
            discounts: { title: 'Discounts', subtitle: 'Manage discounts and promotions' },
            visitors: { title: 'Visitors', subtitle: 'Track visitor activity' },
            users: { title: 'Users', subtitle: 'Manage admin users' },
            telegram: { title: 'Telegram', subtitle: 'Configure Telegram bot' },
            settings: { title: 'Settings', subtitle: 'Configure site settings' },
            logs: { title: 'System Logs', subtitle: 'View system activity' }
        };

        const pageInfo = pageTitles[tabName];
        if (pageInfo) {
            const titleEl = document.getElementById('pageTitle');
            const subtitleEl = document.getElementById('pageSubtitle');
            if (titleEl) titleEl.textContent = pageInfo.title;
            if (subtitleEl) subtitleEl.textContent = pageInfo.subtitle;
        }

        // Load tab-specific data
        this.loadTabData(tabName);

        // Close sidebar on mobile
        if (window.innerWidth <= 1024) {
            document.querySelector('.sidebar')?.classList.remove('open');
        }

        // Log tab switch
        AdminCore.addLog('admin', `Switched to ${tabName} tab`);
    },

    // Load tab-specific data
    loadTabData(tabName) {
        switch (tabName) {
            case 'dashboard':
                this.loadDashboardData();
                break;
            case 'popups':
                if (typeof adminPopup !== 'undefined') adminPopup.loadPopups();
                break;
            case 'ads':
                if (typeof adminAds !== 'undefined') adminAds.loadAds();
                break;
            case 'content':
                if (typeof adminContent !== 'undefined') adminContent.loadContent();
                break;
            case 'plans':
                if (typeof adminPlans !== 'undefined') adminPlans.loadPlans();
                break;
            case 'discounts':
                if (typeof adminDiscounts !== 'undefined') adminDiscounts.loadDiscounts();
                break;
            case 'visitors':
                if (typeof adminVisitors !== 'undefined') adminVisitors.loadVisitors();
                break;
            case 'users':
                if (typeof adminUsers !== 'undefined') adminUsers.loadUsers();
                break;
            case 'telegram':
                if (typeof adminTelegram !== 'undefined') adminTelegram.loadConfig();
                break;
            case 'settings':
                if (typeof adminSettings !== 'undefined') adminSettings.loadSettings();
                break;
            case 'logs':
                if (typeof adminLogs !== 'undefined') adminLogs.loadLogs();
                break;
            case 'analytics':
                if (typeof adminAnalytics !== 'undefined') adminAnalytics.loadAnalytics();
                break;
        }
    },

    // Load dashboard data
    loadDashboardData() {
        // Update visitor stats
        const visitors = AdminCore.getVisitors();
        const today = new Date().toDateString();
        const todayVisitors = visitors.filter(v => new Date(v.timestamp).toDateString() === today);

        this.updateElement('dashTotalVisitors', AdminCore.formatNumber(visitors.length));
        this.updateElement('dashTodayVisitors', AdminCore.formatNumber(todayVisitors.length));

        // Calculate visitor change
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayVisitors = visitors.filter(v => 
            new Date(v.timestamp).toDateString() === yesterday.toDateString()
        );
        const change = yesterdayVisitors.length > 0 
            ? Math.round(((todayVisitors.length - yesterdayVisitors.length) / yesterdayVisitors.length) * 100)
            : 0;
        this.updateElement('visitorChange', (change >= 0 ? '+' : '') + change + '%');

        // Update discount stats
        const discounts = AdminCore.getStorage(AdminCore.storage.discounts, []);
        const activeDiscounts = discounts.filter(d => {
            if (!d.active) return false;
            const now = new Date();
            const start = d.startDate ? new Date(d.startDate) : null;
            const end = d.endDate ? new Date(d.endDate) : null;
            return (!start || now >= start) && (!end || now <= end);
        });
        this.updateElement('dashActiveDiscounts', activeDiscounts.length);
        this.updateElement('discountStatus', activeDiscounts.length > 0 ? 'Active' : 'No active');

        // Update active popups list
        this.updateActivePopupsList();

        // Update active discounts list
        this.updateActiveDiscountsList();

        // Update chart
        if (typeof adminAnalytics !== 'undefined') {
            adminAnalytics.initVisitorChart();
        }

        // Update badges
        this.updateBadges();
    },

    // Update element text content
    updateElement(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    },

    // Update active popups list in dashboard
    updateActivePopupsList() {
        const popups = AdminCore.getStorage(AdminCore.storage.popups, []);
        const activePopups = popups.filter(p => {
            if (!p.active) return false;
            const now = new Date();
            const start = p.startDate ? new Date(p.startDate) : null;
            const end = p.endDate ? new Date(p.endDate) : null;
            return (!start || now >= start) && (!end || now <= end);
        }).slice(0, 5);

        const container = document.getElementById('dashActivePopups');
        if (!container) return;

        if (activePopups.length === 0) {
            container.innerHTML = '<div class="empty-state">No active popups</div>';
            return;
        }

        container.innerHTML = activePopups.map(popup => `
            <div class="mini-item">
                <div class="mini-item-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="mini-item-info">
                    <div class="mini-item-title">${AdminCore.escapeHtml(popup.title)}</div>
                    <div class="mini-item-meta">${popup.type} • Ends ${AdminCore.formatDate(popup.endDate, 'short')}</div>
                </div>
                <span class="mini-item-status">Active</span>
            </div>
        `).join('');
    },

    // Update active discounts list in dashboard
    updateActiveDiscountsList() {
        const discounts = AdminCore.getStorage(AdminCore.storage.discounts, []);
        const activeDiscounts = discounts.filter(d => {
            if (!d.active) return false;
            const now = new Date();
            const start = d.startDate ? new Date(d.startDate) : null;
            const end = d.endDate ? new Date(d.endDate) : null;
            return (!start || now >= start) && (!end || now <= end);
        }).slice(0, 5);

        const container = document.getElementById('dashActiveDiscountsList');
        if (!container) return;

        if (activeDiscounts.length === 0) {
            container.innerHTML = '<div class="empty-state">No active discounts</div>';
            return;
        }

        container.innerHTML = activeDiscounts.map(discount => `
            <div class="mini-item">
                <div class="mini-item-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <div class="mini-item-info">
                    <div class="mini-item-title">${AdminCore.escapeHtml(discount.name)}</div>
                    <div class="mini-item-meta">${discount.value}${discount.type === 'percentage' ? '%' : '$'} off • ${discount.applyTo === 'all' ? 'All plans' : 'Selected plans'}</div>
                </div>
                <span class="mini-item-status">Active</span>
            </div>
        `).join('');
    },

    // Update badges
    updateBadges() {
        const popups = AdminCore.getStorage(AdminCore.storage.popups, []);
        const ads = AdminCore.getStorage(AdminCore.storage.ads, []);
        const discounts = AdminCore.getStorage(AdminCore.storage.discounts, []);

        const activePopups = popups.filter(p => p.active).length;
        const activeAds = ads.filter(a => a.active).length;
        const activeDiscounts = discounts.filter(d => d.active).length;

        this.updateBadge('popupBadge', activePopups);
        this.updateBadge('adsBadge', activeAds);
        this.updateBadge('discountBadge', activeDiscounts);
    },

    // Update badge
    updateBadge(id, count) {
        const badge = document.getElementById(id);
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    },

    // Toggle sidebar
    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('collapsed');
            this.sidebarCollapsed = sidebar.classList.contains('collapsed');
        }
    },

    // Handle window resize
    handleResize() {
        const sidebar = document.querySelector('.sidebar');
        if (window.innerWidth <= 1024) {
            sidebar?.classList.remove('collapsed');
        }
    },

    // Toggle fullscreen
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                AdminCore.showToast('Fullscreen not supported', 'error');
            });
        } else {
            document.exitFullscreen();
        }
    },

    // Refresh data
    refreshData() {
        AdminCore.showLoading('Refreshing data...');
        
        setTimeout(() => {
            this.loadTabData(this.currentTab);
            AdminCore.hideLoading();
            AdminCore.showToast('Data refreshed', 'success');
        }, 500);
    },

    // Open notifications
    openNotifications() {
        if (this.notifications.length === 0) {
            AdminCore.showToast('No new notifications', 'info');
            return;
        }

        // Show notifications modal or dropdown
        AdminCore.showToast(`${this.notifications.length} notifications`, 'info');
        
        // Clear notification badge
        const badge = document.getElementById('notificationBadge');
        if (badge) badge.classList.remove('active');
    },

    // Load notifications
    loadNotifications() {
        this.notifications = AdminCore.getStorage('kb_notifications', []);
        this.updateNotificationBadge();
    },

    // Add notification
    addNotification(title, message, type = 'info') {
        this.notifications.unshift({
            id: AdminCore.generateId('notif'),
            title,
            message,
            type,
            timestamp: new Date().toISOString(),
            read: false
        });

        // Keep only last 50
        if (this.notifications.length > 50) {
            this.notifications = this.notifications.slice(0, 50);
        }

        AdminCore.setStorage('kb_notifications', this.notifications);
        this.updateNotificationBadge();
    },

    // Update notification badge
    updateNotificationBadge() {
        const unreadCount = this.notifications.filter(n => !n.read).length;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.classList.toggle('active', unreadCount > 0);
        }
    },

    // Close all modals
    closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('active');
        });
    },

    // Handle save shortcut
    handleSaveShortcut() {
        switch (this.currentTab) {
            case 'content':
                if (typeof adminContent !== 'undefined') adminContent.saveAllChanges();
                break;
            case 'settings':
                if (typeof adminSettings !== 'undefined') adminSettings.saveAll();
                break;
            case 'telegram':
                if (typeof adminTelegram !== 'undefined') adminTelegram.saveConfig();
                break;
            default:
                AdminCore.showToast('Nothing to save in this tab', 'info');
        }
    },

    // Check for unsaved changes
    hasUnsavedChanges() {
        // This would track unsaved changes across different modules
        return false;
    },

    // Show confirmation dialog
    confirm(message, onConfirm, onCancel = null) {
        AdminCore.confirm(message, onConfirm, onCancel);
    },

    // Export current view data
    exportCurrentView() {
        let data, filename;
        
        switch (this.currentTab) {
            case 'visitors':
                data = AdminCore.getVisitors();
                filename = 'visitors.json';
                break;
            case 'logs':
                data = AdminCore.getLogs();
                filename = 'logs.json';
                break;
            case 'discounts':
                data = AdminCore.getStorage(AdminCore.storage.discounts, []);
                filename = 'discounts.json';
                break;
            default:
                AdminCore.showToast('Export not available for this view', 'warning');
                return;
        }

        AdminCore.exportToJson(data, filename);
        AdminCore.showToast('Data exported successfully', 'success');
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminUI.init();
});

// Export for global access
window.adminUI = adminUI;
