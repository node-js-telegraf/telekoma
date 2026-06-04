/* ============================================
   KILLBYTE ADMIN - LOGS MODULE
   System logs management
   ============================================ */

const adminLogs = {
    logs: [],
    filters: {
        type: 'all',
        date: ''
    },

    init() {
        console.log('[AdminLogs] Initialized');
    },

    loadLogs() {
        this.logs = AdminCore.getLogs(this.filters);
        this.renderLogs();
    },

    filterLogs() {
        this.filters.type = document.getElementById('logTypeFilter')?.value || 'all';
        this.filters.date = document.getElementById('logDateFilter')?.value || '';
        this.loadLogs();
    },

    renderLogs() {
        const container = document.getElementById('logsList');
        if (!container) return;

        if (this.logs.length === 0) {
            container.innerHTML = '<div class="empty-state">No logs found</div>';
            return;
        }

        container.innerHTML = this.logs.slice(0, 100).map(log => {
            const icons = {
                visitor: 'user',
                order: 'shopping-cart',
                admin: 'cog',
                error: 'exclamation-triangle',
                info: 'info-circle'
            };

            return `
                <div class="log-item ${log.type}">
                    <div class="log-icon">
                        <i class="fas fa-${icons[log.type] || 'info-circle'}"></i>
                    </div>
                    <div class="log-content">
                        <div class="log-message">${AdminCore.escapeHtml(log.message)}</div>
                        <div class="log-meta">
                            <span><i class="fas fa-clock"></i> ${AdminCore.formatDate(log.timestamp, 'short')}</span>
                            ${log.user ? `<span><i class="fas fa-user"></i> ${log.user}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    },

    clearLogs() {
        AdminCore.confirm('Are you sure you want to clear all logs?', () => {
            AdminCore.clearLogs();
            this.loadLogs();
            AdminCore.showToast('Logs cleared', 'success');
        });
    },

    exportLogs() {
        AdminCore.exportToJson(this.logs, 'logs.json');
        AdminCore.showToast('Logs exported', 'success');
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminLogs.init();
});

window.adminLogs = adminLogs;
