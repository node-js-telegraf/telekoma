/* ============================================
   KILLBYTE ADMIN - AUTHENTICATION MODULE
   Admin login/logout and session management
   ============================================ */

const adminAuth = {
    isAuthenticated: false,
    currentUser: null,
    sessionTimeout: null,
    sessionDuration: 30 * 60 * 1000, // 30 minutes

    // Initialize auth
    init() {
        this.checkSession();
        this.setupEventListeners();
        console.log('[AdminAuth] Initialized');
    },

    // Setup event listeners
    setupEventListeners() {
        // Login form enter key
        const passwordInput = document.getElementById('loginPassword');
        if (passwordInput) {
            passwordInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.login();
                }
            });
        }

        // Reset session timeout on activity
        ['click', 'keydown', 'mousemove', 'scroll'].forEach(event => {
            document.addEventListener(event, () => this.resetSessionTimeout());
        });
    },

    // Check existing session
    checkSession() {
        const auth = AdminCore.getStorage(AdminCore.storage.auth, null);
        const user = AdminCore.getStorage(AdminCore.storage.user, null);
        
        if (auth && user) {
            const now = Date.now();
            if (now < auth.expiresAt) {
                this.isAuthenticated = true;
                this.currentUser = user;
                this.showDashboard();
                this.startSessionTimeout();
                AdminCore.addLog('admin', 'Session restored', { username: user.username });
            } else {
                this.clearSession();
            }
        }
    },

    // Login function
    login() {
        const usernameInput = document.getElementById('loginUsername');
        const passwordInput = document.getElementById('loginPassword');
        const errorEl = document.getElementById('loginError');
        
        const username = usernameInput?.value.trim();
        const password = passwordInput?.value;

        if (!username || !password) {
            errorEl.textContent = 'Please enter both username and password';
            return;
        }

        // Get stored users or use default
        const users = AdminCore.getStorage('kb_admin_users', [
            { username: 'admin', password: 'killbyte1337', role: 'superadmin', name: 'Administrator' }
        ]);

        const user = users.find(u => u.username === username && u.password === password);

        if (user) {
            this.isAuthenticated = true;
            this.currentUser = {
                username: user.username,
                role: user.role,
                name: user.name,
                loginTime: new Date().toISOString()
            };

            // Save session
            AdminCore.setStorage(AdminCore.storage.auth, {
                token: AdminCore.generateId('token'),
                expiresAt: Date.now() + this.sessionDuration
            });
            AdminCore.setStorage(AdminCore.storage.user, this.currentUser);

            // Update admin name in UI
            const adminNameEl = document.getElementById('adminName');
            if (adminNameEl) {
                adminNameEl.textContent = this.currentUser.name || this.currentUser.username;
            }

            // Log login
            AdminCore.addLog('admin', 'Admin logged in', { username: user.username });

            // Show dashboard
            this.showDashboard();
            this.startSessionTimeout();

            // Clear inputs
            usernameInput.value = '';
            passwordInput.value = '';
            errorEl.textContent = '';

            AdminCore.showToast('Welcome back, ' + this.currentUser.name + '!', 'success');
        } else {
            errorEl.textContent = 'Invalid username or password';
            AdminCore.addLog('admin', 'Failed login attempt', { username });
            
            // Shake animation
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                loginContainer.style.animation = '';
            }, 500);
        }
    },

    // Logout function
    logout() {
        AdminCore.confirm('Are you sure you want to logout?', () => {
            AdminCore.addLog('admin', 'Admin logged out', { 
                username: this.currentUser?.username 
            });
            
            this.clearSession();
            this.showLogin();
            AdminCore.showToast('Logged out successfully', 'info');
        });
    },

    // Clear session data
    clearSession() {
        this.isAuthenticated = false;
        this.currentUser = null;
        AdminCore.removeStorage(AdminCore.storage.auth);
        AdminCore.removeStorage(AdminCore.storage.user);
        this.clearSessionTimeout();
    },

    // Start session timeout
    startSessionTimeout() {
        this.clearSessionTimeout();
        this.sessionTimeout = setTimeout(() => {
            AdminCore.showToast('Session expired. Please login again.', 'warning');
            this.clearSession();
            this.showLogin();
        }, this.sessionDuration);
    },

    // Reset session timeout on activity
    resetSessionTimeout() {
        if (this.isAuthenticated) {
            this.startSessionTimeout();
            
            // Update expiresAt
            const auth = AdminCore.getStorage(AdminCore.storage.auth, null);
            if (auth) {
                auth.expiresAt = Date.now() + this.sessionDuration;
                AdminCore.setStorage(AdminCore.storage.auth, auth);
            }
        }
    },

    // Clear session timeout
    clearSessionTimeout() {
        if (this.sessionTimeout) {
            clearTimeout(this.sessionTimeout);
            this.sessionTimeout = null;
        }
    },

    // Show login screen
    showLogin() {
        const loginScreen = document.getElementById('loginScreen');
        const dashboard = document.getElementById('adminDashboard');
        
        if (loginScreen) loginScreen.classList.remove('hidden');
        if (dashboard) dashboard.classList.add('hidden');
        
        document.getElementById('loginUsername')?.focus();
    },

    // Show dashboard
    showDashboard() {
        const loginScreen = document.getElementById('loginScreen');
        const dashboard = document.getElementById('adminDashboard');
        
        if (loginScreen) loginScreen.classList.add('hidden');
        if (dashboard) dashboard.classList.remove('hidden');
        
        // Initialize dashboard data
        if (typeof adminUI !== 'undefined') {
            adminUI.loadDashboardData();
        }
    },

    // Change password
    changePassword(currentPassword, newPassword) {
        const users = AdminCore.getStorage('kb_admin_users', []);
        const userIndex = users.findIndex(u => 
            u.username === this.currentUser?.username && 
            u.password === currentPassword
        );

        if (userIndex === -1) {
            return { success: false, message: 'Current password is incorrect' };
        }

        if (newPassword.length < 6) {
            return { success: false, message: 'Password must be at least 6 characters' };
        }

        users[userIndex].password = newPassword;
        AdminCore.setStorage('kb_admin_users', users);
        
        AdminCore.addLog('admin', 'Password changed', { username: this.currentUser.username });
        
        return { success: true, message: 'Password changed successfully' };
    },

    // Add new admin user
    addUser(username, password, name, role = 'admin') {
        const users = AdminCore.getStorage('kb_admin_users', []);
        
        if (users.find(u => u.username === username)) {
            return { success: false, message: 'Username already exists' };
        }

        users.push({ username, password, name, role });
        AdminCore.setStorage('kb_admin_users', users);
        
        AdminCore.addLog('admin', 'New user created', { username, role, by: this.currentUser?.username });
        
        return { success: true, message: 'User created successfully' };
    },

    // Remove user
    removeUser(username) {
        if (username === 'admin') {
            return { success: false, message: 'Cannot remove default admin' };
        }

        let users = AdminCore.getStorage('kb_admin_users', []);
        users = users.filter(u => u.username !== username);
        AdminCore.setStorage('kb_admin_users', users);
        
        AdminCore.addLog('admin', 'User removed', { username, by: this.currentUser?.username });
        
        return { success: true, message: 'User removed successfully' };
    },

    // Get all users
    getUsers() {
        return AdminCore.getStorage('kb_admin_users', []);
    },

    // Check if user has permission
    hasPermission(permission) {
        if (!this.currentUser) return false;
        if (this.currentUser.role === 'superadmin') return true;
        
        const permissions = {
            admin: ['view', 'edit_content', 'manage_popups', 'manage_discounts'],
            moderator: ['view', 'edit_content']
        };
        
        return permissions[this.currentUser.role]?.includes(permission) || false;
    },

    // Require auth for function
    requireAuth(callback) {
        if (this.isAuthenticated) {
            callback();
        } else {
            this.showLogin();
            AdminCore.showToast('Please login to continue', 'warning');
        }
    }
};

// Add shake animation
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100%); }
    }
`;
document.head.appendChild(style);

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminAuth.init();
});

// Export for global access
window.adminAuth = adminAuth;
