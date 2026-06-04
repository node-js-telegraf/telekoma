/* ============================================
   KILLBYTE - POPUP MANAGER
   Handles popup announcements on main site
   ============================================ */

const PopupManager = {
    popups: [],
    shownPopups: [],
    currentPopupIndex: 0,

    init() {
        this.loadPopups();
        this.setupSyncListener();
        
        // Check for popups after a short delay
        setTimeout(() => this.checkAndShowPopups(), 3000);
        
        console.log('[PopupManager] Initialized');
    },

    loadPopups() {
        // Load from admin sync
        const sync = localStorage.getItem('kb_active_popups_sync');
        if (sync) {
            try {
                const data = JSON.parse(sync);
                this.popups = data.popups || [];
            } catch (e) {
                console.error('Failed to load popups:', e);
            }
        }

        // Load shown popups from session
        const shown = sessionStorage.getItem('kb_shown_popups');
        if (shown) {
            this.shownPopups = JSON.parse(shown);
        }
    },

    setupSyncListener() {
        // Listen for storage changes (from admin panel)
        window.addEventListener('storage', (e) => {
            if (e.key === 'kb_active_popups_sync') {
                this.loadPopups();
            }
        });
    },

    checkAndShowPopups() {
        const activePopups = this.getActivePopups();
        
        if (activePopups.length === 0) return;

        // Show first popup that hasn't been shown (if showOnce is enabled)
        for (const popup of activePopups) {
            if (popup.showOnce && this.shownPopups.includes(popup.id)) {
                continue;
            }
            
            setTimeout(() => {
                this.showPopup(popup);
            }, (popup.delay || 3) * 1000);
            
            break; // Show only one popup at a time
        }
    },

    getActivePopups() {
        const now = new Date();
        return this.popups.filter(popup => {
            if (!popup.active) return false;
            
            const startDate = popup.startDate ? new Date(popup.startDate) : null;
            const endDate = popup.endDate ? new Date(popup.endDate) : null;
            
            const isStarted = !startDate || now >= startDate;
            const isNotExpired = !endDate || now <= endDate;
            
            return isStarted && isNotExpired;
        }).sort((a, b) => (b.priority || 0) - (a.priority || 0));
    },

    showPopup(popup) {
        // Remove existing popups
        this.closeAllPopups();

        // Create popup element
        const popupEl = document.createElement('div');
        popupEl.id = `popup-${popup.id}`;
        popupEl.className = `kb-popup kb-popup-${popup.position || 'center'} kb-popup-${popup.design || 'default'}`;
        
        if (popup.backdrop !== false) {
            popupEl.classList.add('with-backdrop');
        }

        // Build popup HTML
        let popupHTML = '';
        
        if (popup.backdrop !== false) {
            popupHTML += `<div class="kb-popup-backdrop" onclick="${popup.closeable !== false ? `PopupManager.closePopup('${popup.id}')` : ''}"></div>`;
        }

        popupHTML += `
            <div class="kb-popup-content">
                ${popup.closeable !== false ? `
                    <button class="kb-popup-close" onclick="PopupManager.closePopup('${popup.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                ` : ''}
                
                ${popup.image ? `
                    <div class="kb-popup-image">
                        <img src="${popup.image}" alt="${popup.title}">
                    </div>
                ` : ''}
                
                <div class="kb-popup-body">
                    <h3 class="kb-popup-title">${this.escapeHtml(popup.title)}</h3>
                    <div class="kb-popup-text">${this.formatContent(popup.content)}</div>
                    
                    ${popup.buttonText ? `
                        <a href="${popup.buttonLink || '#'}" 
                           class="kb-popup-btn" 
                           onclick="PopupManager.handleButtonClick('${popup.id}')"
                           ${popup.buttonLink?.startsWith('http') ? 'target="_blank"' : ''}>
                            ${this.escapeHtml(popup.buttonText)}
                        </a>
                    ` : ''}
                </div>
            </div>
        `;

        popupEl.innerHTML = popupHTML;
        document.body.appendChild(popupEl);

        // Add to shown popups
        if (popup.showOnce && !this.shownPopups.includes(popup.id)) {
            this.shownPopups.push(popup.id);
            sessionStorage.setItem('kb_shown_popups', JSON.stringify(this.shownPopups));
        }

        // Track view
        this.trackView(popup.id);

        // Auto-close after duration
        if (popup.duration) {
            const durationMs = popup.duration * 24 * 60 * 60 * 1000;
            // Don't auto-close, just let it expire naturally
        }

        // Animate in
        requestAnimationFrame(() => {
            popupEl.classList.add('active');
        });
    },

    closePopup(id) {
        const popup = document.getElementById(`popup-${id}`);
        if (popup) {
            popup.classList.remove('active');
            setTimeout(() => popup.remove(), 300);
        }
    },

    closeAllPopups() {
        document.querySelectorAll('.kb-popup').forEach(popup => {
            popup.classList.remove('active');
            setTimeout(() => popup.remove(), 300);
        });
    },

    handleButtonClick(id) {
        this.trackClick(id);
    },

    trackView(id) {
        // Send to admin panel via storage
        const views = JSON.parse(localStorage.getItem('kb_popup_views') || '[]');
        views.push({ id, timestamp: Date.now() });
        localStorage.setItem('kb_popup_views', JSON.stringify(views));
    },

    trackClick(id) {
        const clicks = JSON.parse(localStorage.getItem('kb_popup_clicks') || '[]');
        clicks.push({ id, timestamp: Date.now() });
        localStorage.setItem('kb_popup_clicks', JSON.stringify(clicks));
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    formatContent(content) {
        // Convert URLs to links
        return content
            .replace(/\n/g, '<br>')
            .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
    }
};

// Add popup styles
const popupStyles = document.createElement('style');
popupStyles.textContent = `
    .kb-popup {
        position: fixed;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .kb-popup.active {
        opacity: 1;
        visibility: visible;
    }
    
    .kb-popup-center {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .kb-popup-top { top: 20px; left: 50%; transform: translateX(-50%); }
    .kb-popup-bottom { bottom: 20px; left: 50%; transform: translateX(-50%); }
    .kb-popup-top-left { top: 20px; left: 20px; }
    .kb-popup-top-right { top: 20px; right: 20px; }
    .kb-popup-bottom-left { bottom: 20px; left: 20px; }
    .kb-popup-bottom-right { bottom: 20px; right: 20px; }
    
    .kb-popup-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(4px);
    }
    
    .kb-popup-content {
        position: relative;
        max-width: 500px;
        width: 90%;
        background: #141414;
        border: 1px solid #2a2a2a;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        animation: popupSlideIn 0.3s ease;
    }
    
    @keyframes popupSlideIn {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .kb-popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
    }
    
    .kb-popup-close:hover {
        background: #ff3333;
    }
    
    .kb-popup-image {
        width: 100%;
        max-height: 200px;
        overflow: hidden;
    }
    
    .kb-popup-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .kb-popup-body {
        padding: 24px;
    }
    
    .kb-popup-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #fff;
    }
    
    .kb-popup-text {
        color: #a0a0a0;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .kb-popup-btn {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(135deg, #ff3333, #cc0000);
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .kb-popup-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 51, 51, 0.3);
    }
    
    /* Design variants */
    .kb-popup-modern .kb-popup-content {
        border-radius: 24px;
        background: linear-gradient(135deg, #1a1a1a, #0a0a0a);
    }
    
    .kb-popup-minimal .kb-popup-content {
        border: none;
        background: rgba(20, 20, 20, 0.95);
    }
    
    .kb-popup-gradient .kb-popup-content {
        background: linear-gradient(135deg, #1a0a0a, #0a0a0a);
        border-color: #ff3333;
    }
`;
document.head.appendChild(popupStyles);

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    PopupManager.init();
});

window.PopupManager = PopupManager;
