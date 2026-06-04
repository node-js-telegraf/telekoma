/* ============================================
   KILLBYTE ADMIN - CORE MODULE
   Core functionality and utilities
   ============================================ */

const AdminCore = {
    version: '2.0.0',
    apiEndpoint: '/api/admin',
    
    // Storage keys
    storage: {
        auth: 'kb_admin_auth',
        user: 'kb_admin_user',
        settings: 'kb_admin_settings',
        popups: 'kb_popups',
        ads: 'kb_ads',
        discounts: 'kb_discounts',
        content: 'kb_content',
        logs: 'kb_logs',
        visitors: 'kb_visitors',
        plans: 'kb_plans'
    },

    // Default admin credentials (change in production)
    defaultCredentials: {
        username: 'admin',
        password: 'killbyte1337'
    },

    // Initialize core
    init() {
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
        this.loadSettings();
        console.log('[AdminCore] Initialized v' + this.version);
    },

    // Update time display
    updateTime() {
        const now = new Date();
        const timeEl = document.getElementById('currentTime');
        const dateEl = document.getElementById('currentDate');
        
        if (timeEl) {
            timeEl.textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        }
        
        if (dateEl) {
            dateEl.textContent = now.toLocaleDateString('en-US', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
            });
        }
    },

    // Get item from localStorage with error handling
    getStorage(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.error('[AdminCore] Storage get error:', e);
            return defaultValue;
        }
    },

    // Set item to localStorage with error handling
    setStorage(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.error('[AdminCore] Storage set error:', e);
            return false;
        }
    },

    // Remove item from localStorage
    removeStorage(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.error('[AdminCore] Storage remove error:', e);
            return false;
        }
    },

    // Load admin settings
    loadSettings() {
        return this.getStorage(this.storage.settings, {
            siteTitle: 'KillByte Solutions',
            siteDescription: 'Premium L7 & L4 Infrastructure',
            adminContact: '@rankflood',
            primaryColor: '#ff3333',
            themeMode: 'dark',
            enableVisitorTracking: true,
            enableNotifications: true,
            sessionTimeout: 30
        });
    },

    // Save admin settings
    saveSettings(settings) {
        return this.setStorage(this.storage.settings, settings);
    },

    // Generate unique ID
    generateId(prefix = 'id') {
        return `${prefix}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    },

    // Format date
    formatDate(date, format = 'short') {
        const d = new Date(date);
        const options = {
            short: { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' },
            long: { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' },
            date: { year: 'numeric', month: '2-digit', day: '2-digit' },
            time: { hour: '2-digit', minute: '2-digit', second: '2-digit' }
        };
        return d.toLocaleString('en-US', options[format] || options.short);
    },

    // Format number with commas
    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },

    // Format currency
    formatCurrency(amount, currency = '$') {
        return `${currency}${parseFloat(amount).toFixed(2)}`;
    },

    // Calculate percentage
    calculatePercentage(value, total) {
        if (total === 0) return 0;
        return Math.round((value / total) * 100);
    },

    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Deep clone object
    deepClone(obj) {
        return JSON.parse(JSON.stringify(obj));
    },

    // Merge objects deeply
    deepMerge(target, source) {
        const output = Object.assign({}, target);
        if (this.isObject(target) && this.isObject(source)) {
            Object.keys(source).forEach(key => {
                if (this.isObject(source[key])) {
                    if (!(key in target)) {
                        Object.assign(output, { [key]: source[key] });
                    } else {
                        output[key] = this.deepMerge(target[key], source[key]);
                    }
                } else {
                    Object.assign(output, { [key]: source[key] });
                }
            });
        }
        return output;
    },

    // Check if value is object
    isObject(item) {
        return item && typeof item === 'object' && !Array.isArray(item);
    },

    // Escape HTML
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    // Show loading overlay
    showLoading(message = 'Loading...') {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.querySelector('span').textContent = message;
            overlay.classList.remove('hidden');
        }
    },

    // Hide loading overlay
    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
        }
    },

    // Show toast notification
    showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.appendChild(toast);

        // Auto remove
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    // Confirm dialog
    confirm(message, onConfirm, onCancel = null) {
        if (confirm(message)) {
            onConfirm();
        } else if (onCancel) {
            onCancel();
        }
    },

    // Export data to JSON file
    exportToJson(data, filename) {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    },

    // Import data from JSON file
    importFromJson(file, callback) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = JSON.parse(e.target.result);
                callback(null, data);
            } catch (err) {
                callback(err, null);
            }
        };
        reader.readAsText(file);
    },

    // Get visitor data
    getVisitors() {
        return this.getStorage(this.storage.visitors, []);
    },

    // Get visitor stats
    getVisitorStats() {
        const visitors = this.getVisitors();
        const today = new Date().toDateString();
        
        return {
            total: visitors.length,
            today: visitors.filter(v => new Date(v.timestamp).toDateString() === today).length,
            unique: new Set(visitors.map(v => v.ip)).size,
            countries: this.groupBy(visitors, 'country'),
            devices: this.groupBy(visitors, 'device')
        };
    },

    // Group array by key
    groupBy(array, key) {
        return array.reduce((result, item) => {
            const value = item[key] || 'Unknown';
            result[value] = (result[value] || 0) + 1;
            return result;
        }, {});
    },

    // Sort object by values
    sortByValues(obj, descending = true) {
        return Object.entries(obj)
            .sort(([,a], [,b]) => descending ? b - a : a - b)
            .reduce((r, [k, v]) => ({ ...r, [k]: v }), {});
    },

    // Add log entry
    addLog(type, message, details = {}) {
        const logs = this.getStorage(this.storage.logs, []);
        logs.unshift({
            id: this.generateId('log'),
            type,
            message,
            details,
            timestamp: new Date().toISOString(),
            user: this.getStorage(this.storage.user, {}).username || 'system'
        });
        
        // Keep only last 1000 logs
        if (logs.length > 1000) {
            logs.length = 1000;
        }
        
        this.setStorage(this.storage.logs, logs);
    },

    // Get logs
    getLogs(filter = {}) {
        let logs = this.getStorage(this.storage.logs, []);
        
        if (filter.type) {
            logs = logs.filter(l => l.type === filter.type);
        }
        
        if (filter.date) {
            const filterDate = new Date(filter.date).toDateString();
            logs = logs.filter(l => new Date(l.timestamp).toDateString() === filterDate);
        }
        
        return logs;
    },

    // Clear all logs
    clearLogs() {
        this.removeStorage(this.storage.logs);
        this.addLog('admin', 'Logs cleared');
    },

    // Validate email
    validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    // Validate URL
    validateUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    },

    // Copy to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showToast('Copied to clipboard!', 'success');
            return true;
        } catch (err) {
            this.showToast('Failed to copy', 'error');
            return false;
        }
    },

    // Download file
    downloadFile(content, filename, type = 'text/plain') {
        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    },

    // Image to base64
    fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    },

    // Compress image
    compressImage(base64, maxWidth = 800, maxHeight = 600, quality = 0.8) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height *= maxWidth / width;
                    width = maxWidth;
                }
                if (height > maxHeight) {
                    width *= maxHeight / height;
                    height = maxHeight;
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                resolve(canvas.toDataURL('image/jpeg', quality));
            };
            img.src = base64;
        });
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    AdminCore.init();
});

// Export for global access
window.AdminCore = AdminCore;
