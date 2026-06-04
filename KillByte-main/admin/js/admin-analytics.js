/* ============================================
   KILLBYTE ADMIN - ANALYTICS MODULE
   Analytics and charts
   ============================================ */

const adminAnalytics = {
    charts: {},
    
    init() {
        console.log('[AdminAnalytics] Initialized');
    },

    loadAnalytics() {
        this.renderTrafficChart();
        this.renderCountriesList();
        this.renderDeviceChart();
        this.renderHoursChart();
        this.renderPageViews();
    },

    initVisitorChart() {
        const canvas = document.getElementById('visitorChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const visitors = AdminCore.getVisitors();
        
        // Get last 7 days data
        const days = 7;
        const labels = [];
        const data = [];

        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            const dateStr = date.toDateString();
            
            const count = visitors.filter(v => {
                const vDate = new Date(v.timestamp);
                return vDate.toDateString() === dateStr;
            }).length;
            
            data.push(count);
            labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
        }

        this.drawLineChart(ctx, canvas, labels, data, 'Visitors');
    },

    renderTrafficChart() {
        const canvas = document.getElementById('trafficChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const visitors = AdminCore.getVisitors();
        
        // Group by hour for today
        const hourlyData = new Array(24).fill(0);
        const today = new Date().toDateString();
        
        visitors.filter(v => new Date(v.timestamp).toDateString() === today)
            .forEach(v => {
                const hour = new Date(v.timestamp).getHours();
                hourlyData[hour]++;
            });

        const labels = hourlyData.map((_, i) => `${i}:00`);
        
        this.drawLineChart(ctx, canvas, labels, hourlyData, 'Visitors Today');
    },

    renderCountriesList() {
        const container = document.getElementById('countriesList');
        if (!container) return;

        const visitors = AdminCore.getVisitors();
        const countries = AdminCore.sortByValues(AdminCore.groupBy(visitors, 'country'));
        const total = visitors.length;

        const topCountries = Object.entries(countries).slice(0, 10);
        
        container.innerHTML = topCountries.map(([country, count]) => {
            const percentage = ((count / total) * 100).toFixed(1);
            return `
                <div class="country-item">
                    <span class="country-flag">${this.getCountryFlag(country)}</span>
                    <span class="country-name">${country}</span>
                    <div class="country-bar">
                        <div class="country-bar-fill" style="width: ${percentage}%"></div>
                    </div>
                    <span class="country-count">${count}</span>
                </div>
            `;
        }).join('');
    },

    renderDeviceChart() {
        const canvas = document.getElementById('deviceChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const visitors = AdminCore.getVisitors();
        const devices = AdminCore.groupBy(visitors, 'device');

        this.drawPieChart(ctx, canvas, devices);
    },

    renderHoursChart() {
        const canvas = document.getElementById('hoursChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const visitors = AdminCore.getVisitors();
        
        const hourlyData = new Array(24).fill(0);
        visitors.forEach(v => {
            const hour = new Date(v.timestamp).getHours();
            hourlyData[hour]++;
        });

        const labels = hourlyData.map((_, i) => `${i}:00`);
        this.drawBarChart(ctx, canvas, labels, hourlyData);
    },

    renderPageViews() {
        const container = document.getElementById('pageViewsList');
        if (!container) return;

        const visitors = AdminCore.getVisitors();
        const pages = AdminCore.sortByValues(AdminCore.groupBy(visitors, 'page'));
        const total = visitors.length;

        container.innerHTML = Object.entries(pages).slice(0, 10).map(([page, count]) => {
            const percentage = ((count / total) * 100).toFixed(1);
            const pageName = page.split('/').pop() || 'Home';
            return `
                <div class="page-view-item">
                    <span class="page-view-name">${pageName}</span>
                    <div class="page-view-bar">
                        <div class="page-view-bar-fill" style="width: ${percentage}%"></div>
                    </div>
                    <span class="page-view-count">${count}</span>
                </div>
            `;
        }).join('');
    },

    drawLineChart(ctx, canvas, labels, data, label) {
        const padding = 40;
        const chartWidth = canvas.width - padding * 2;
        const chartHeight = canvas.height - padding * 2;
        const maxValue = Math.max(...data, 1);
        const stepX = chartWidth / (data.length - 1 || 1);

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Grid
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
        ctx.lineWidth = 1;
        for (let i = 0; i <= 5; i++) {
            const y = padding + (chartHeight / 5) * i;
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(canvas.width - padding, y);
            ctx.stroke();
        }

        // Labels
        ctx.fillStyle = '#666';
        ctx.font = '12px Inter';
        ctx.textAlign = 'center';
        labels.forEach((label, i) => {
            const x = padding + stepX * i;
            ctx.fillText(label, x, canvas.height - 10);
        });

        // Line
        ctx.strokeStyle = '#ff3333';
        ctx.lineWidth = 2;
        ctx.beginPath();

        data.forEach((value, i) => {
            const x = padding + stepX * i;
            const y = padding + chartHeight - (value / maxValue) * chartHeight;
            if (i === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        });
        ctx.stroke();

        // Fill
        const gradient = ctx.createLinearGradient(0, padding, 0, canvas.height - padding);
        gradient.addColorStop(0, 'rgba(255, 51, 51, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 51, 51, 0)');
        ctx.fillStyle = gradient;
        ctx.fill();

        // Points
        data.forEach((value, i) => {
            const x = padding + stepX * i;
            const y = padding + chartHeight - (value / maxValue) * chartHeight;
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, Math.PI * 2);
            ctx.fillStyle = '#fff';
            ctx.fill();
        });
    },

    drawPieChart(ctx, canvas, data) {
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 40;

        const total = Object.values(data).reduce((a, b) => a + b, 0);
        let currentAngle = -Math.PI / 2;

        const colors = ['#ff3333', '#00c853', '#00b0ff', '#ffab00', '#aa00ff'];

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        Object.entries(data).forEach(([key, value], i) => {
            const sliceAngle = (value / total) * 2 * Math.PI;
            
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.closePath();
            ctx.fillStyle = colors[i % colors.length];
            ctx.fill();

            currentAngle += sliceAngle;
        });
    },

    drawBarChart(ctx, canvas, labels, data) {
        const padding = 40;
        const chartWidth = canvas.width - padding * 2;
        const chartHeight = canvas.height - padding * 2;
        const maxValue = Math.max(...data, 1);
        const barWidth = chartWidth / data.length * 0.8;
        const gap = chartWidth / data.length * 0.2;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        data.forEach((value, i) => {
            const barHeight = (value / maxValue) * chartHeight;
            const x = padding + i * (barWidth + gap);
            const y = padding + chartHeight - barHeight;

            ctx.fillStyle = '#ff3333';
            ctx.fillRect(x, y, barWidth, barHeight);
        });
    },

    getCountryFlag(country) {
        const flags = {
            'United States': '🇺🇸',
            'United Kingdom': '🇬🇧',
            'Germany': '🇩🇪',
            'France': '🇫🇷',
            'Russia': '🇷🇺',
            'China': '🇨🇳',
            'India': '🇮🇳',
            'Brazil': '🇧🇷',
            'Canada': '🇨🇦',
            'Australia': '🇦🇺'
        };
        return flags[country] || '🌍';
    },

    updateChart() {
        this.initVisitorChart();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminAnalytics.init();
});

window.adminAnalytics = adminAnalytics;
