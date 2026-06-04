/* ============================================
   KILLBYTE PREMIUM - ADMIN PANEL
   Admin Dashboard & Management
   ============================================ */

// Admin State
const adminState = {
    isAuthenticated: false,
    currentTab: 'dashboard',
    chartInstance: null
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initAdminPanel();
});

function initAdminPanel() {
    // Check if admin was previously logged in
    const savedAuth = sessionStorage.getItem('kb_admin_auth');
    if (savedAuth === 'true') {
        adminState.isAuthenticated = true;
        document.getElementById('adminPanel').classList.add('active');
        loadAdminData();
    }
}

// ============================================
// ADMIN AUTHENTICATION
// ============================================
function openAdminModal() {
    document.getElementById('adminModal').classList.add('active');
    document.getElementById('adminPassword').focus();
}

function closeAdminModal() {
    document.getElementById('adminModal').classList.remove('active');
    document.getElementById('adminPassword').value = '';
}

function adminLogin() {
    const password = document.getElementById('adminPassword').value;
    const correctPassword = 'killbyte1337'; // Change this in production

    if (password === correctPassword) {
        adminState.isAuthenticated = true;
        sessionStorage.setItem('kb_admin_auth', 'true');
        
        closeAdminModal();
        document.getElementById('adminPanel').classList.add('active');
        
        loadAdminData();
        showNotification('Admin access granted', 'success');
    } else {
        showNotification('Invalid password', 'error');
        document.getElementById('adminPassword').value = '';
        
        // Shake animation
        const modal = document.querySelector('.modal-content');
        modal.style.animation = 'shake 0.5s ease';
        setTimeout(() => {
            modal.style.animation = '';
        }, 500);
    }
}

function adminLogout() {
    adminState.isAuthenticated = false;
    sessionStorage.removeItem('kb_admin_auth');
    
    document.getElementById('adminPanel').classList.remove('active');
    
    // Reset to dashboard tab
    switchAdminTab('dashboard', document.querySelector('.admin-nav-item'));
    
    showNotification('Logged out successfully', 'info');
}

// ============================================
// TAB SWITCHING
// ============================================
function switchAdminTab(tab, element) {
    if (!adminState.isAuthenticated) return;

    adminState.currentTab = tab;

    // Update nav items
    document.querySelectorAll('.admin-nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    if (element) {
        element.classList.add('active');
    }

    // Update tab content
    document.querySelectorAll('.admin-tab').forEach(tabContent => {
        tabContent.classList.remove('active');
    });
    
    const targetTab = document.getElementById(`tab-${tab}`);
    if (targetTab) {
        targetTab.classList.add('active');
    }

    // Load tab-specific data
    if (tab === 'visitors') {
        if (typeof updateAdminVisitorList === 'function') {
            updateAdminVisitorList();
        }
    } else if (tab === 'dashboard') {
        initVisitorChart();
    }
}

// ============================================
// LOAD ADMIN DATA
// ============================================
function loadAdminData() {
    // Update visitor stats
    updateVisitorStats();
    
    // Update Telegram status
    if (typeof updateTelegramStatus === 'function') {
        updateTelegramStatus();
    }
    
    // Initialize chart
    initVisitorChart();
}

function updateVisitorStats() {
    const totalVisitors = document.getElementById('totalVisitors');
    const todayVisitors = document.getElementById('todayVisitors');
    
    const savedTotal = localStorage.getItem('kb_total_visitors') || '0';
    const savedToday = localStorage.getItem('kb_today_visitors') || '0';
    
    if (totalVisitors) {
        totalVisitors.textContent = parseInt(savedTotal).toLocaleString();
    }
    
    if (todayVisitors) {
        todayVisitors.textContent = parseInt(savedToday).toLocaleString();
    }
}

// ============================================
// VISITOR CHART
// ============================================
function initVisitorChart() {
    const canvas = document.getElementById('visitorChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = canvas.parentElement.offsetWidth;
    canvas.height = 300;

    // Get visitor data
    const visitorData = getVisitorDataForChart();

    // Clear previous chart
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw chart
    drawLineChart(ctx, canvas, visitorData);
}

function getVisitorDataForChart() {
    const days = 7;
    const labels = [];
    const data = [];

    const visitors = JSON.parse(localStorage.getItem('kb_visitors') || '[]');

    for (let i = days - 1; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toDateString();

        // Count visitors for this day
        const count = visitors.filter(v => {
            const vDate = new Date(v.timestamp);
            return vDate.toDateString() === dateStr;
        }).length;

        data.push(count);
        labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
    }

    return { labels, data };
}

function drawLineChart(ctx, canvas, chartData) {
    const { labels, data } = chartData;
    const padding = 40;
    const chartWidth = canvas.width - padding * 2;
    const chartHeight = canvas.height - padding * 2;

    const maxValue = Math.max(...data, 1);
    const stepX = chartWidth / (data.length - 1);

    // Draw grid lines
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 5; i++) {
        const y = padding + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(canvas.width - padding, y);
        ctx.stroke();
    }

    // Draw labels
    ctx.fillStyle = '#666';
    ctx.font = '12px Inter';
    ctx.textAlign = 'center';

    labels.forEach((label, i) => {
        const x = padding + stepX * i;
        ctx.fillText(label, x, canvas.height - 10);
    });

    // Draw Y-axis labels
    ctx.textAlign = 'right';
    for (let i = 0; i <= 5; i++) {
        const value = Math.round((maxValue / 5) * (5 - i));
        const y = padding + (chartHeight / 5) * i + 4;
        ctx.fillText(value.toString(), padding - 10, y);
    }

    // Draw line
    ctx.strokeStyle = '#fff';
    ctx.lineWidth = 2;
    ctx.beginPath();

    data.forEach((value, i) => {
        const x = padding + stepX * i;
        const y = padding + chartHeight - (value / maxValue) * chartHeight;

        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            // Smooth curve
            const prevX = padding + stepX * (i - 1);
            const prevY = padding + chartHeight - (data[i - 1] / maxValue) * chartHeight;
            const cpX = (prevX + x) / 2;
            ctx.quadraticCurveTo(cpX, prevY, cpX, (prevY + y) / 2);
            ctx.quadraticCurveTo(cpX, y, x, y);
        }
    });

    ctx.stroke();

    // Draw gradient fill
    const gradient = ctx.createLinearGradient(0, padding, 0, canvas.height - padding);
    gradient.addColorStop(0, 'rgba(255, 255, 255, 0.1)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

    ctx.lineTo(padding + stepX * (data.length - 1), canvas.height - padding);
    ctx.lineTo(padding, canvas.height - padding);
    ctx.closePath();
    ctx.fillStyle = gradient;
    ctx.fill();

    // Draw points
    data.forEach((value, i) => {
        const x = padding + stepX * i;
        const y = padding + chartHeight - (value / maxValue) * chartHeight;

        ctx.beginPath();
        ctx.arc(x, y, 4, 0, Math.PI * 2);
        ctx.fillStyle = '#fff';
        ctx.fill();

        ctx.beginPath();
        ctx.arc(x, y, 6, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        ctx.stroke();
    });
}

// ============================================
// DATA MANAGEMENT
// ============================================
function clearAllData() {
    if (!confirm('⚠️ WARNING: This will delete ALL data including visitors, settings, and configurations. This action cannot be undone.\n\nAre you sure?')) {
        return;
    }

    if (!confirm('Are you absolutely sure? All data will be permanently lost.')) {
        return;
    }

    // Clear all localStorage
    localStorage.clear();

    // Clear session
    sessionStorage.clear();

    // Reset state
    adminState.isAuthenticated = false;

    // Reload page
    window.location.reload();
}

// ============================================
// NOTIFICATION
// ============================================
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `admin-notification notification-${type}`;
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };

    notification.innerHTML = `
        <i class="fas fa-${icons[type] || 'info-circle'}"></i>
        <span>${message}</span>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        background: #161616;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        transform: translateX(150%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
        font-size: 14px;
    `;

    // Type-specific colors
    const colors = {
        success: '#00ff88',
        error: '#ff4444',
        warning: '#ffaa00',
        info: '#79c0ff'
    };

    notification.querySelector('i').style.color = colors[type] || colors.info;

    document.body.appendChild(notification);

    // Show
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
    });

    // Hide after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(150%)';
        setTimeout(() => {
            notification.remove();
        }, 400);
    }, 3000);
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
document.addEventListener('keydown', (e) => {
    if (!adminState.isAuthenticated) return;

    // Escape to close admin panel
    if (e.key === 'Escape' && adminState.currentTab !== 'dashboard') {
        switchAdminTab('dashboard', document.querySelector('.admin-nav-item'));
    }

    // Number keys for tabs
    if (e.key >= '1' && e.key <= '4') {
        const tabs = ['dashboard', 'visitors', 'telegram', 'settings'];
        const tabIndex = parseInt(e.key) - 1;
        if (tabs[tabIndex]) {
            const navItems = document.querySelectorAll('.admin-nav-item');
            switchAdminTab(tabs[tabIndex], navItems[tabIndex]);
        }
    }
});

// ============================================
// AUTO REFRESH
// ============================================
setInterval(() => {
    if (adminState.isAuthenticated) {
        updateVisitorStats();
        
        if (adminState.currentTab === 'visitors') {
            if (typeof updateAdminVisitorList === 'function') {
                updateAdminVisitorList();
            }
        }
        
        if (adminState.currentTab === 'dashboard') {
            initVisitorChart();
        }
    }
}, 30000); // Refresh every 30 seconds

// Export functions
window.openAdminModal = openAdminModal;
window.closeAdminModal = closeAdminModal;
window.adminLogin = adminLogin;
window.adminLogout = adminLogout;
window.switchAdminTab = switchAdminTab;
window.loadAdminData = loadAdminData;
window.clearAllData = clearAllData;
window.showNotification = showNotification;
