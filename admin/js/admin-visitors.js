/* ============================================
   KILLBYTE ADMIN - VISITORS MODULE
   Visitor tracking and management
   ============================================ */

const adminVisitors = {
    visitors: [],
    filteredVisitors: [],
    currentPage: 1,
    perPage: 20,
    filters: {
        search: '',
        country: 'all',
        dateRange: 'all'
    },

    init() {
        this.loadVisitors();
        console.log('[AdminVisitors] Initialized');
    },

    loadVisitors() {
        this.visitors = AdminCore.getStorage(AdminCore.storage.visitors, []);
        this.applyFilters();
        this.updateStats();
        this.populateCountryFilter();
        this.renderTable();
    },

    applyFilters() {
        this.filteredVisitors = this.visitors.filter(v => {
            // Search filter
            if (this.filters.search) {
                const searchStr = [
                    v.ip, v.city, v.country, v.isp, v.userAgent
                ].join(' ').toLowerCase();
                if (!searchStr.includes(this.filters.search.toLowerCase())) return false;
            }

            // Country filter
            if (this.filters.country !== 'all' && v.country !== this.filters.country) {
                return false;
            }

            // Date filter
            if (this.filters.dateRange !== 'all') {
                const visitDate = new Date(v.timestamp);
                const now = new Date();
                
                switch (this.filters.dateRange) {
                    case 'today':
                        if (visitDate.toDateString() !== now.toDateString()) return false;
                        break;
                    case 'week':
                        const weekAgo = new Date(now - 7 * 24 * 60 * 60 * 1000);
                        if (visitDate < weekAgo) return false;
                        break;
                    case 'month':
                        const monthAgo = new Date(now - 30 * 24 * 60 * 60 * 1000);
                        if (visitDate < monthAgo) return false;
                        break;
                }
            }

            return true;
        }).sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

        this.currentPage = 1;
    },

    filterVisitors() {
        this.filters.search = document.getElementById('visitorSearch')?.value || '';
        this.filters.country = document.getElementById('visitorCountryFilter')?.value || 'all';
        this.filters.dateRange = document.getElementById('visitorDateFilter')?.value || 'all';
        
        this.applyFilters();
        this.renderTable();
    },

    populateCountryFilter() {
        const select = document.getElementById('visitorCountryFilter');
        if (!select) return;

        const countries = [...new Set(this.visitors.map(v => v.country).filter(Boolean))].sort();
        
        select.innerHTML = '<option value="all">All Countries</option>' + 
            countries.map(c => `<option value="${c}">${c}</option>`).join('');
    },

    updateStats() {
        const today = new Date().toDateString();
        const todayVisitors = this.visitors.filter(v => 
            new Date(v.timestamp).toDateString() === today
        );

        // Simulate online users (last 5 minutes)
        const fiveMinutesAgo = new Date(Date.now() - 5 * 60 * 1000);
        const onlineVisitors = this.visitors.filter(v => 
            new Date(v.timestamp) > fiveMinutesAgo
        );

        const uniqueIPs = new Set(this.visitors.map(v => v.ip));

        this.updateElement('visitorsTotal', AdminCore.formatNumber(this.visitors.length));
        this.updateElement('visitorsToday', AdminCore.formatNumber(todayVisitors.length));
        this.updateElement('visitorsOnline', AdminCore.formatNumber(onlineVisitors.length));
        this.updateElement('visitorsUnique', AdminCore.formatNumber(uniqueIPs.size));
    },

    updateElement(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    },

    renderTable() {
        const tbody = document.getElementById('visitorsTableBody');
        if (!tbody) return;

        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        const pageVisitors = this.filteredVisitors.slice(start, end);

        if (pageVisitors.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="empty-state">No visitors found</td></tr>';
            this.updatePagination();
            return;
        }

        tbody.innerHTML = pageVisitors.map(v => {
            const device = this.getDeviceType(v.userAgent);
            const location = v.city && v.country 
                ? `${v.city}, ${v.country}` 
                : v.country || 'Unknown';
            
            return `
                <tr>
                    <td><code>${v.ip}</code></td>
                    <td>
                        <div class="visitor-location">
                            ${v.countryCode ? `<img src="https://flagcdn.com/w20/${v.countryCode.toLowerCase()}.png" alt="">` : ''}
                            ${location}
                        </div>
                    </td>
                    <td><i class="fas fa-${device.icon}"></i> ${device.name}</td>
                    <td>${v.page?.split('/').pop() || 'Home'}</td>
                    <td>${AdminCore.formatDate(v.timestamp, 'short')}</td>
                    <td>
                        <button onclick="adminVisitors.viewDetails('${v.id}')" class="popup-card-btn">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="adminVisitors.banIP('${v.ip}')" class="popup-card-btn danger">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

        this.updatePagination();
    },

    updatePagination() {
        const totalPages = Math.ceil(this.filteredVisitors.length / this.perPage) || 1;
        const pageInfo = document.getElementById('pageInfo');
        if (pageInfo) {
            pageInfo.textContent = `Page ${this.currentPage} of ${totalPages}`;
        }
    },

    getDeviceType(userAgent) {
        if (!userAgent) return { name: 'Unknown', icon: 'question' };
        if (/mobile/i.test(userAgent)) return { name: 'Mobile', icon: 'mobile-alt' };
        if (/tablet|ipad/i.test(userAgent)) return { name: 'Tablet', icon: 'tablet-alt' };
        return { name: 'Desktop', icon: 'desktop' };
    },

    prevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.renderTable();
        }
    },

    nextPage() {
        const totalPages = Math.ceil(this.filteredVisitors.length / this.perPage);
        if (this.currentPage < totalPages) {
            this.currentPage++;
            this.renderTable();
        }
    },

    viewDetails(id) {
        const visitor = this.visitors.find(v => v.id === id);
        if (!visitor) return;

        // Show visitor details modal
        alert(`
IP: ${visitor.ip}
Location: ${visitor.city}, ${visitor.region}, ${visitor.country}
ISP: ${visitor.isp}
Device: ${visitor.userAgent}
Time: ${AdminCore.formatDate(visitor.timestamp, 'long')}
Page: ${visitor.page}
Referrer: ${visitor.referrer}
        `.trim());
    },

    banIP(ip) {
        AdminCore.confirm(`Are you sure you want to ban IP ${ip}?`, () => {
            const banned = AdminCore.getStorage('kb_banned_ips', []);
            if (!banned.includes(ip)) {
                banned.push(ip);
                AdminCore.setStorage('kb_banned_ips', banned);
                AdminCore.showToast(`IP ${ip} has been banned`, 'success');
                AdminCore.addLog('admin', 'IP banned', { ip });
            }
        });
    },

    exportData() {
        AdminCore.exportToJson(this.filteredVisitors, 'visitors.json');
        AdminCore.showToast('Visitor data exported', 'success');
    },

    clearAll() {
        AdminCore.confirm('Are you sure you want to clear all visitor data?', () => {
            AdminCore.removeStorage(AdminCore.storage.visitors);
            this.loadVisitors();
            AdminCore.showToast('All visitor data cleared', 'success');
            AdminCore.addLog('admin', 'All visitor data cleared');
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminVisitors.init();
});

window.adminVisitors = adminVisitors;
