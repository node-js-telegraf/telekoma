/* ============================================
   KILLBYTE ADMIN - ADS MODULE
   Advertisement management
   ============================================ */

const adminAds = {
    ads: [],
    currentAd: null,
    filters: {
        position: 'all',
        type: 'all'
    },

    // Initialize
    init() {
        this.loadAds();
        console.log('[AdminAds] Initialized');
    },

    // Load ads from storage
    loadAds() {
        this.ads = AdminCore.getStorage(AdminCore.storage.ads, []);
        this.renderAds();
        this.updateStats();
        this.updateBadge();
        this.syncToMainSite();
    },

    // Save ads to storage
    saveAds() {
        AdminCore.setStorage(AdminCore.storage.ads, this.ads);
        this.updateStats();
        this.updateBadge();
        this.syncToMainSite();
    },

    // Sync ads to main site
    syncToMainSite() {
        const activeAds = this.getActiveAds();
        localStorage.setItem('kb_active_ads_sync', JSON.stringify({
            ads: activeAds,
            timestamp: Date.now()
        }));
    },

    // Get active ads
    getActiveAds() {
        const now = new Date();
        return this.ads.filter(ad => {
            if (!ad.active) return false;
            
            const startDate = ad.startDate ? new Date(ad.startDate) : null;
            const endDate = ad.endDate ? new Date(ad.endDate) : null;
            
            const isStarted = !startDate || now >= startDate;
            const isNotExpired = !endDate || now <= endDate;
            
            return isStarted && isNotExpired;
        });
    },

    // Get ads by position
    getAdsByPosition(position) {
        return this.getActiveAds().filter(ad => ad.position === position);
    },

    // Get ad status
    getAdStatus(ad) {
        const now = new Date();
        const startDate = ad.startDate ? new Date(ad.startDate) : null;
        const endDate = ad.endDate ? new Date(ad.endDate) : null;

        if (!ad.active) return 'inactive';
        if (startDate && now < startDate) return 'scheduled';
        if (endDate && now > endDate) return 'expired';
        return 'active';
    },

    // Filter ads
    filterAds() {
        this.filters.position = document.getElementById('adPositionFilter')?.value || 'all';
        this.filters.type = document.getElementById('adTypeFilter')?.value || 'all';
        this.renderAds();
    },

    // Get filtered ads
    getFilteredAds() {
        return this.ads.filter(ad => {
            // Position filter
            if (this.filters.position !== 'all' && ad.position !== this.filters.position) {
                return false;
            }

            // Type filter
            if (this.filters.type !== 'all') {
                const adType = this.getAdType(ad.file);
                if (adType !== this.filters.type) return false;
            }

            return true;
        }).sort((a, b) => b.priority - a.priority);
    },

    // Get ad type from file
    getAdType(file) {
        if (!file) return 'image';
        if (file.includes('data:video') || file.endsWith('.mp4')) return 'video';
        if (file.includes('data:image/gif') || file.endsWith('.gif')) return 'gif';
        return 'image';
    },

    // Render ads
    renderAds() {
        const container = document.getElementById('adsList');
        if (!container) return;

        const filtered = this.getFilteredAds();

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1; padding: 3rem;">
                    <i class="fas fa-ad" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3>No ads found</h3>
                    <p>Upload your first advertisement</p>
                    <button onclick="adminAds.openUploadModal()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-upload"></i> Upload Ad
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(ad => {
            const status = this.getAdStatus(ad);
            const adType = this.getAdType(ad.file);
            
            return `
                <div class="ad-card" data-id="${ad.id}">
                    <div class="ad-card-preview">
                        ${this.renderAdMedia(ad, adType)}
                    </div>
                    <div class="ad-card-info">
                        <div class="ad-card-name">${AdminCore.escapeHtml(ad.name)}</div>
                        <div class="ad-card-meta">
                            <span><i class="fas fa-map-marker-alt"></i> ${ad.position}</span>
                            <span><i class="fas fa-eye"></i> ${ad.views || 0}</span>
                            <span><i class="fas fa-mouse-pointer"></i> ${ad.clicks || 0}</span>
                        </div>
                    </div>
                    <div class="ad-card-actions">
                        <button onclick="adminAds.editAd('${ad.id}')" class="popup-card-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="adminAds.toggleAd('${ad.id}')" class="popup-card-btn">
                            <i class="fas fa-${ad.active ? 'pause' : 'play'}"></i>
                        </button>
                        <button onclick="adminAds.deleteAd('${ad.id}')" class="popup-card-btn danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    },

    // Render ad media
    renderAdMedia(ad, type) {
        if (!ad.file) return '<i class="fas fa-image"></i>';
        
        if (type === 'video') {
            return `<video src="${ad.file}" muted loop></video>`;
        }
        return `<img src="${ad.file}" alt="${AdminCore.escapeHtml(ad.name)}">`;
    },

    // Update stats
    updateStats() {
        const totalViews = this.ads.reduce((sum, ad) => sum + (ad.views || 0), 0);
        const totalClicks = this.ads.reduce((sum, ad) => sum + (ad.clicks || 0), 0);
        const avgCTR = totalViews > 0 ? ((totalClicks / totalViews) * 100).toFixed(2) : '0.00';

        this.updateElement('totalAds', this.ads.length);
        this.updateElement('totalAdViews', AdminCore.formatNumber(totalViews));
        this.updateElement('totalAdClicks', AdminCore.formatNumber(totalClicks));
        this.updateElement('avgCTR', avgCTR + '%');
    },

    // Update element text
    updateElement(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    },

    // Open upload modal
    openUploadModal(adId = null) {
        this.currentAd = adId ? this.ads.find(a => a.id === adId) : null;
        
        const modal = document.getElementById('adUploadModal');
        const form = document.getElementById('adUploadForm');
        
        if (!modal || !form) return;

        form.reset();
        document.getElementById('adFilePreview').classList.add('hidden');

        if (this.currentAd) {
            this.fillUploadForm(this.currentAd);
        } else {
            document.getElementById('adName').value = '';
            document.getElementById('adPriority').value = 1;
            document.getElementById('adActive').checked = true;
        }

        modal.classList.add('active');
    },

    // Close upload modal
    closeUploadModal() {
        const modal = document.getElementById('adUploadModal');
        if (modal) modal.classList.remove('active');
        this.currentAd = null;
    },

    // Fill upload form
    fillUploadForm(ad) {
        document.getElementById('adName').value = ad.name;
        document.getElementById('adPosition').value = ad.position;
        document.getElementById('adPriority').value = ad.priority;
        document.getElementById('adLink').value = ad.link || '';
        document.getElementById('adActive').checked = ad.active !== false;

        if (ad.startDate) {
            const startDate = new Date(ad.startDate);
            startDate.setMinutes(startDate.getMinutes() - startDate.getTimezoneOffset());
            document.getElementById('adStartDate').value = startDate.toISOString().slice(0, 16);
        }

        if (ad.endDate) {
            const endDate = new Date(ad.endDate);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('adEndDate').value = endDate.toISOString().slice(0, 16);
        }

        if (ad.file) {
            const preview = document.getElementById('adFilePreview');
            const type = this.getAdType(ad.file);
            preview.innerHTML = this.renderAdMedia({ file: ad.file, name: ad.name }, type);
            preview.classList.remove('hidden');
        }
    },

    // Preview ad file
    previewAd(input) {
        const preview = document.getElementById('adFilePreview');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            if (file.size > 10 * 1024 * 1024) {
                AdminCore.showToast('File size must be less than 10MB', 'error');
                input.value = '';
                return;
            }

            AdminCore.fileToBase64(file).then(base64 => {
                const isVideo = file.type.startsWith('video/');
                const isGif = file.type === 'image/gif';
                
                if (isVideo) {
                    preview.innerHTML = `<video src="${base64}" muted loop controls></video>`;
                } else if (isGif) {
                    preview.innerHTML = `<img src="${base64}" alt="Preview">`;
                } else {
                    // Compress images
                    AdminCore.compressImage(base64, 800, 600, 0.8).then(compressed => {
                        preview.innerHTML = `<img src="${compressed}" alt="Preview">`;
                    });
                    return;
                }
                preview.classList.remove('hidden');
            });
        }
    },

    // Upload ad
    async uploadAd() {
        const form = document.getElementById('adUploadForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const adId = this.currentAd?.id;
        const isNew = !adId;

        const fileInput = document.getElementById('adFile');
        let fileBase64 = this.currentAd?.file || null;

        if (fileInput.files && fileInput.files[0]) {
            try {
                fileBase64 = await AdminCore.fileToBase64(fileInput.files[0]);
                
                // Compress if it's an image (not GIF or video)
                const file = fileInput.files[0];
                if (file.type.startsWith('image/') && file.type !== 'image/gif') {
                    fileBase64 = await AdminCore.compressImage(fileBase64, 800, 600, 0.8);
                }
            } catch (err) {
                AdminCore.showToast('Failed to process file', 'error');
                return;
            }
        }

        if (!fileBase64) {
            AdminCore.showToast('Please select a file', 'error');
            return;
        }

        const adData = {
            id: adId || AdminCore.generateId('ad'),
            name: document.getElementById('adName').value,
            file: fileBase64,
            position: document.getElementById('adPosition').value,
            priority: parseInt(document.getElementById('adPriority').value) || 1,
            link: document.getElementById('adLink').value || null,
            startDate: document.getElementById('adStartDate').value || null,
            endDate: document.getElementById('adEndDate').value || null,
            active: document.getElementById('adActive').checked,
            views: this.currentAd?.views || 0,
            clicks: this.currentAd?.clicks || 0,
            updatedAt: new Date().toISOString(),
            createdAt: this.currentAd?.createdAt || new Date().toISOString()
        };

        if (isNew) {
            this.ads.push(adData);
            AdminCore.addLog('admin', 'Ad uploaded', { name: adData.name });
            AdminCore.showToast('Ad uploaded successfully', 'success');
        } else {
            const index = this.ads.findIndex(a => a.id === adId);
            if (index !== -1) {
                this.ads[index] = adData;
                AdminCore.addLog('admin', 'Ad updated', { name: adData.name });
                AdminCore.showToast('Ad updated successfully', 'success');
            }
        }

        this.saveAds();
        this.renderAds();
        this.closeUploadModal();
    },

    // Edit ad
    editAd(id) {
        this.openUploadModal(id);
    },

    // Toggle ad active state
    toggleAd(id) {
        const ad = this.ads.find(a => a.id === id);
        if (ad) {
            ad.active = !ad.active;
            ad.updatedAt = new Date().toISOString();
            this.saveAds();
            this.renderAds();
            
            AdminCore.showToast(
                `Ad ${ad.active ? 'enabled' : 'disabled'}`,
                ad.active ? 'success' : 'info'
            );
            
            AdminCore.addLog('admin', `Ad ${ad.active ? 'enabled' : 'disabled'}`, { name: ad.name });
        }
    },

    // Delete ad
    deleteAd(id) {
        const ad = this.ads.find(a => a.id === id);
        if (!ad) return;

        AdminCore.confirm(
            `Are you sure you want to delete "${ad.name}"?`,
            () => {
                this.ads = this.ads.filter(a => a.id !== id);
                this.saveAds();
                this.renderAds();
                
                AdminCore.showToast('Ad deleted', 'success');
                AdminCore.addLog('admin', 'Ad deleted', { name: ad.name });
            }
        );
    },

    // Update badge
    updateBadge() {
        const activeCount = this.getActiveAds().length;
        if (typeof adminUI !== 'undefined') {
            adminUI.updateBadge('adsBadge', activeCount);
        }
    },

    // Get ad by ID
    getAd(id) {
        return this.ads.find(a => a.id === id);
    },

    // Increment view
    incrementView(id) {
        const ad = this.ads.find(a => a.id === id);
        if (ad) {
            ad.views = (ad.views || 0) + 1;
            this.saveAds();
        }
    },

    // Increment click
    incrementClick(id) {
        const ad = this.ads.find(a => a.id === id);
        if (ad) {
            ad.clicks = (ad.clicks || 0) + 1;
            this.saveAds();
        }
    },

    // Get random ad for position
    getRandomAdForPosition(position) {
        const ads = this.getAdsByPosition(position);
        if (ads.length === 0) return null;
        
        // Weight by priority
        const totalPriority = ads.reduce((sum, ad) => sum + (ad.priority || 1), 0);
        let random = Math.random() * totalPriority;
        
        for (const ad of ads) {
            random -= (ad.priority || 1);
            if (random <= 0) return ad;
        }
        
        return ads[0];
    },

    // Clean up expired ads
    cleanupExpired() {
        const now = new Date();
        const expired = this.ads.filter(a => {
            if (!a.endDate) return false;
            return new Date(a.endDate) < now;
        });

        if (expired.length > 0) {
            AdminCore.addLog('admin', 'Cleaned up expired ads', { count: expired.length });
        }
    },

    // Export ads
    exportAds() {
        AdminCore.exportToJson(this.ads, 'ads.json');
        AdminCore.showToast('Ads exported', 'success');
    },

    // Import ads
    importAds(file) {
        AdminCore.importFromJson(file, (err, data) => {
            if (err) {
                AdminCore.showToast('Failed to import ads', 'error');
                return;
            }

            if (Array.isArray(data)) {
                this.ads = [...this.ads, ...data];
                this.saveAds();
                this.renderAds();
                AdminCore.showToast(`${data.length} ads imported`, 'success');
                AdminCore.addLog('admin', 'Ads imported', { count: data.length });
            }
        });
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminAds.init();
});

// Export for global access
window.adminAds = adminAds;
