/* ============================================
   KILLBYTE PREMIUM - VISITOR TRACKER
   Visitor Counting & IP Detection
   ============================================ */

// Visitor State
const visitorState = {
    visitors: [],
    currentVisitor: null,
    totalCount: 0,
    todayCount: 0,
    isNewVisitor: false
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initVisitorTracking();
});

async function initVisitorTracking() {
    // Load saved visitors from localStorage
    loadVisitors();

    // Check if this is a new visitor
    checkNewVisitor();

    // Get visitor info
    await getVisitorInfo();

    // Update display
    updateVisitorDisplay();

    // Send notification if new visitor
    if (visitorState.isNewVisitor) {
        await sendVisitorNotification();
        showVisitorToast();
    }

    // Save visitors
    saveVisitors();

    // Update admin panel if open
    if (typeof updateAdminVisitorList === 'function') {
        updateAdminVisitorList();
    }
}

// ============================================
// VISITOR INFO
// ============================================
async function getVisitorInfo() {
    try {
        // Get IP address from ipapi.co
        const response = await fetch('https://ipapi.co/json/');
        const data = await response.json();

        visitorState.currentVisitor = {
            ip: data.ip,
            city: data.city,
            region: data.region,
            country: data.country_name,
            countryCode: data.country_code,
            timezone: data.timezone,
            isp: data.org,
            timestamp: new Date().toISOString(),
            userAgent: navigator.userAgent,
            referrer: document.referrer || 'Direct',
            page: window.location.href
        };

    } catch (error) {
        console.log('Could not fetch IP info, using fallback');
        
        // Fallback - generate a visitor ID
        visitorState.currentVisitor = {
            ip: generateVisitorId(),
            city: 'Unknown',
            region: 'Unknown',
            country: 'Unknown',
            countryCode: 'XX',
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            isp: 'Unknown',
            timestamp: new Date().toISOString(),
            userAgent: navigator.userAgent,
            referrer: document.referrer || 'Direct',
            page: window.location.href
        };
    }
}

function generateVisitorId() {
    // Generate a unique visitor ID based on browser fingerprint
    const fingerprint = [
        navigator.userAgent,
        navigator.language,
        screen.width + 'x' + screen.height,
        new Date().getTimezoneOffset()
    ].join('|');
    
    // Simple hash
    let hash = 0;
    for (let i = 0; i < fingerprint.length; i++) {
        const char = fingerprint.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    
    return 'ID-' + Math.abs(hash).toString(16).toUpperCase().substring(0, 8);
}

// ============================================
// NEW VISITOR CHECK
// ============================================
function checkNewVisitor() {
    const visitorId = localStorage.getItem('kb_visitor_id');
    const lastVisit = localStorage.getItem('kb_last_visit');
    const today = new Date().toDateString();

    if (!visitorId) {
        // First time visitor
        visitorState.isNewVisitor = true;
        const newId = 'V-' + Date.now().toString(36).toUpperCase();
        localStorage.setItem('kb_visitor_id', newId);
        localStorage.setItem('kb_first_visit', new Date().toISOString());
    } else {
        // Returning visitor
        visitorState.isNewVisitor = lastVisit !== today;
    }

    localStorage.setItem('kb_last_visit', today);
}

// ============================================
// VISITOR STORAGE
// ============================================
function loadVisitors() {
    try {
        const saved = localStorage.getItem('kb_visitors');
        if (saved) {
            visitorState.visitors = JSON.parse(saved);
        }

        const savedTotal = localStorage.getItem('kb_total_visitors');
        visitorState.totalCount = savedTotal ? parseInt(savedTotal) : visitorState.visitors.length;

        const savedToday = localStorage.getItem('kb_today_visitors');
        const today = new Date().toDateString();
        const savedDate = localStorage.getItem('kb_visitor_date');

        if (savedDate === today && savedToday) {
            visitorState.todayCount = parseInt(savedToday);
        } else {
            visitorState.todayCount = 0;
            localStorage.setItem('kb_visitor_date', today);
        }

    } catch (error) {
        console.error('Error loading visitors:', error);
        visitorState.visitors = [];
    }
}

function saveVisitors() {
    try {
        // Add current visitor to list
        if (visitorState.currentVisitor) {
            visitorState.visitors.unshift({
                ...visitorState.currentVisitor,
                id: Date.now()
            });

            // Keep only last 100 visitors
            if (visitorState.visitors.length > 100) {
                visitorState.visitors = visitorState.visitors.slice(0, 100);
            }
        }

        // Update counts
        if (visitorState.isNewVisitor) {
            visitorState.totalCount++;
            visitorState.todayCount++;
        }

        // Save to localStorage
        localStorage.setItem('kb_visitors', JSON.stringify(visitorState.visitors));
        localStorage.setItem('kb_total_visitors', visitorState.totalCount.toString());
        localStorage.setItem('kb_today_visitors', visitorState.todayCount.toString());

    } catch (error) {
        console.error('Error saving visitors:', error);
    }
}

// ============================================
// DISPLAY UPDATE
// ============================================
function updateVisitorDisplay() {
    // Update admin panel counters
    const totalVisitors = document.getElementById('totalVisitors');
    const todayVisitors = document.getElementById('todayVisitors');

    if (totalVisitors) {
        totalVisitors.textContent = visitorState.totalCount.toLocaleString();
    }

    if (todayVisitors) {
        todayVisitors.textContent = visitorState.todayCount.toLocaleString();
    }
}

// ============================================
// VISITOR TOAST
// ============================================
function showVisitorToast() {
    const toast = document.getElementById('visitorToast');
    const visitorInfo = document.getElementById('visitorInfo');

    if (!toast || !visitorInfo || !visitorState.currentVisitor) return;

    const visitor = visitorState.currentVisitor;
    const location = visitor.city !== 'Unknown' 
        ? `${visitor.city}, ${visitor.countryCode}` 
        : visitor.country !== 'Unknown' 
            ? visitor.country 
            : 'Unknown Location';

    visitorInfo.innerHTML = `
        <span class="ip">${visitor.ip}</span>
        <span class="location">${location}</span>
    `;

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// ============================================
// TELEGRAM NOTIFICATION
// ============================================
async function sendVisitorNotification() {
    const botToken = localStorage.getItem('kb_bot_token');
    const chatId = localStorage.getItem('kb_chat_id');
    const notifyEnabled = localStorage.getItem('kb_notify_enabled') !== 'false';

    if (!botToken || !chatId || !notifyEnabled) return;

    const visitor = visitorState.currentVisitor;
    if (!visitor) return;

    const location = visitor.city !== 'Unknown' 
        ? `${visitor.city}, ${visitor.region}, ${visitor.country}` 
        : visitor.country !== 'Unknown' 
            ? visitor.country 
            : 'Unknown Location';

    const isNew = visitorState.isNewVisitor ? 'New' : 'Returning';
    const date = new Date().toLocaleString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    const message = `
🚨 <b>KillByte Visitor Alert</b>

📊 <b>Visitor Type:</b> ${isNew} Visitor
🌐 <b>IP Address:</b> <code>${visitor.ip}</code>
📍 <b>Location:</b> ${location}
🏳️ <b>Country:</b> ${visitor.country} (${visitor.countryCode})
🌐 <b>ISP:</b> ${visitor.isp}
🕐 <b>Time:</b> ${date}
🔗 <b>Page:</b> ${visitor.page}
📱 <b>Device:</b> ${getDeviceType()}

📈 <b>Total Visitors:</b> ${visitorState.totalCount}
📅 <b>Today's Visitors:</b> ${visitorState.todayCount}
    `.trim();

    try {
        const response = await fetch(`https://api.telegram.org/bot${botToken}/sendMessage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                chat_id: chatId,
                text: message,
                parse_mode: 'HTML'
            })
        });

        const data = await response.json();
        
        if (data.ok) {
            console.log('Visitor notification sent to Telegram');
        } else {
            console.error('Telegram API error:', data.description);
        }

    } catch (error) {
        console.error('Failed to send Telegram notification:', error);
    }
}

function getDeviceType() {
    const ua = navigator.userAgent;
    if (/mobile/i.test(ua)) return 'Mobile';
    if (/tablet/i.test(ua)) return 'Tablet';
    if (/ipad/i.test(ua)) return 'iPad';
    return 'Desktop';
}

// ============================================
// ADMIN PANEL FUNCTIONS
// ============================================
function updateAdminVisitorList() {
    const visitorList = document.getElementById('visitorList');
    if (!visitorList) return;

    if (visitorState.visitors.length === 0) {
        visitorList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No visitors yet</p>
            </div>
        `;
        return;
    }

    visitorList.innerHTML = visitorState.visitors.slice(0, 50).map(visitor => {
        const date = new Date(visitor.timestamp);
        const time = date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const location = visitor.city !== 'Unknown' 
            ? `${visitor.city}, ${visitor.countryCode}` 
            : visitor.country !== 'Unknown' 
                ? visitor.country 
                : 'Unknown';

        return `
            <div class="visitor-item">
                <div class="visitor-info">
                    <div class="visitor-ip">${visitor.ip}</div>
                    <div class="visitor-location">${location}</div>
                </div>
                <div class="visitor-time">${time}</div>
            </div>
        `;
    }).join('');
}

function clearVisitors() {
    if (!confirm('Are you sure you want to clear all visitor data?')) return;

    visitorState.visitors = [];
    visitorState.totalCount = 0;
    visitorState.todayCount = 0;

    localStorage.removeItem('kb_visitors');
    localStorage.removeItem('kb_total_visitors');
    localStorage.removeItem('kb_today_visitors');

    updateVisitorDisplay();
    updateAdminVisitorList();

    if (typeof showNotification === 'function') {
        showNotification('Visitor data cleared', 'success');
    }
}

// Export functions
window.clearVisitors = clearVisitors;
window.updateAdminVisitorList = updateAdminVisitorList;
