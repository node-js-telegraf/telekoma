/* ============================================
   KILLBYTE ADMIN - POPUP MODULE
   Popup announcement management
   ============================================ */

const adminPopup = {
    popups: [],
    currentPopup: null,
    filters: {
        status: 'all',
        type: 'all',
        search: ''
    },

    // Initialize
    init() {
        this.loadPopups();
        console.log('[AdminPopup] Initialized');
    },

    // Load popups from storage
    loadPopups() {
        this.popups = AdminCore.getStorage(AdminCore.storage.popups, []);
        this.renderPopups();
        this.updateBadge();
    },

    // Save popups to storage
    savePopups() {
        AdminCore.setStorage(AdminCore.storage.popups, this.popups);
        this.updateBadge();
        
        // Sync to main site
        this.syncToMainSite();
    },

    // Sync popups to main site
    syncToMainSite() {
        // Create a storage event to notify main site
        const activePopups = this.getActivePopups();
        localStorage.setItem('kb_active_popups_sync', JSON.stringify({
            popups: activePopups,
            timestamp: Date.now()
        }));
    },

    // Get active popups
    getActivePopups() {
        const now = new Date();
        return this.popups.filter(popup => {
            if (!popup.active) return false;
            
            const startDate = popup.startDate ? new Date(popup.startDate) : null;
            const endDate = popup.endDate ? new Date(popup.endDate) : null;
            
            const isStarted = !startDate || now >= startDate;
            const isNotExpired = !endDate || now <= endDate;
            
            return isStarted && isNotExpired;
        });
    },

    // Get popup status
    getPopupStatus(popup) {
        const now = new Date();
        const startDate = popup.startDate ? new Date(popup.startDate) : null;
        const endDate = popup.endDate ? new Date(popup.endDate) : null;

        if (!popup.active) return 'inactive';
        if (startDate && now < startDate) return 'scheduled';
        if (endDate && now > endDate) return 'expired';
        return 'active';
    },

    // Filter popups
    filterPopups() {
        this.filters.status = document.getElementById('popupStatusFilter')?.value || 'all';
        this.filters.type = document.getElementById('popupTypeFilter')?.value || 'all';
        this.filters.search = document.getElementById('popupSearch')?.value.toLowerCase() || '';
        
        this.renderPopups();
    },

    // Get filtered popups
    getFilteredPopups() {
        return this.popups.filter(popup => {
            // Status filter
            if (this.filters.status !== 'all') {
                const status = this.getPopupStatus(popup);
                if (status !== this.filters.status) return false;
            }

            // Type filter
            if (this.filters.type !== 'all' && popup.type !== this.filters.type) {
                return false;
            }

            // Search filter
            if (this.filters.search) {
                const searchFields = [
                    popup.title,
                    popup.content,
                    popup.type
                ].join(' ').toLowerCase();
                if (!searchFields.includes(this.filters.search)) return false;
            }

            return true;
        }).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
    },

    // Render popups
    renderPopups() {
        const container = document.getElementById('popupsList');
        if (!container) return;

        const filtered = this.getFilteredPopups();

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1; padding: 3rem;">
                    <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3>No popups found</h3>
                    <p>Create your first popup announcement</p>
                    <button onclick="adminPopup.openModal()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Create Popup
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(popup => {
            const status = this.getPopupStatus(popup);
            const statusClass = status;
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            
            const hasImage = popup.image ? true : false;
            
            return `
                <div class="popup-card" data-id="${popup.id}">
                    <div class="popup-card-header">
                        <div class="popup-card-image">
                            ${hasImage 
                                ? `<img src="${popup.image}" alt="${AdminCore.escapeHtml(popup.title)}">`
                                : `<i class="fas fa-bullhorn"></i>`
                            }
                        </div>
                        <div class="popup-card-info">
                            <div class="popup-card-title">${AdminCore.escapeHtml(popup.title)}</div>
                            <div class="popup-card-type">${popup.type}</div>
                        </div>
                        <span class="popup-card-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="popup-card-body">
                        <div class="popup-card-content">${AdminCore.escapeHtml(popup.content)}</div>
                        <div class="popup-card-meta">
                            <span><i class="fas fa-calendar"></i> ${popup.startDate ? AdminCore.formatDate(popup.startDate, 'short') : 'Now'}</span>
                            <span><i class="fas fa-clock"></i> ${popup.endDate ? AdminCore.formatDate(popup.endDate, 'short') : 'No expiry'}</span>
                            <span><i class="fas fa-eye"></i> ${popup.views || 0} views</span>
                        </div>
                    </div>
                    <div class="popup-card-footer">
                        <button onclick="adminPopup.editPopup('${popup.id}')" class="popup-card-btn">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="adminPopup.togglePopup('${popup.id}')" class="popup-card-btn">
                            <i class="fas fa-${popup.active ? 'pause' : 'play'}"></i> ${popup.active ? 'Disable' : 'Enable'}
                        </button>
                        <button onclick="adminPopup.duplicatePopup('${popup.id}')" class="popup-card-btn">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <button onclick="adminPopup.deletePopup('${popup.id}')" class="popup-card-btn danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    },

    // Open modal for creating/editing popup
    openModal(popupId = null) {
        this.currentPopup = popupId ? this.popups.find(p => p.id === popupId) : null;
        
        const modal = document.getElementById('popupModal');
        const title = document.getElementById('popupModalTitle');
        const form = document.getElementById('popupForm');
        
        if (!modal || !form) return;

        // Reset form
        form.reset();
        document.getElementById('popupImagePreview').classList.add('hidden');

        if (this.currentPopup) {
            title.textContent = 'Edit Popup';
            this.fillForm(this.currentPopup);
        } else {
            title.textContent = 'Create Popup';
            document.getElementById('popupId').value = '';
            // Set default dates
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('popupStartDate').value = now.toISOString().slice(0, 16);
            
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 7);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('popupEndDate').value = endDate.toISOString().slice(0, 16);
        }

        modal.classList.add('active');
    },

    // Close modal
    closeModal() {
        const modal = document.getElementById('popupModal');
        if (modal) modal.classList.remove('active');
        this.currentPopup = null;
    },

    // Fill form with popup data
    fillForm(popup) {
        document.getElementById('popupId').value = popup.id;
        document.getElementById('popupTitle').value = popup.title;
        document.getElementById('popupContent').value = popup.content;
        document.getElementById('popupType').value = popup.type || 'announcement';
        document.getElementById('popupDesign').value = popup.design || 'default';
        document.getElementById('popupPosition').value = popup.position || 'center';
        document.getElementById('popupDuration').value = popup.duration || 7;
        document.getElementById('popupDelay').value = popup.delay || 3;
        document.getElementById('popupShowOnce').checked = popup.showOnce !== false;
        document.getElementById('popupCloseable').checked = popup.closeable !== false;
        document.getElementById('popupBackdrop').checked = popup.backdrop !== false;
        document.getElementById('popupActive').checked = popup.active !== false;
        document.getElementById('popupButtonText').value = popup.buttonText || '';
        document.getElementById('popupButtonLink').value = popup.buttonLink || '';

        if (popup.startDate) {
            const startDate = new Date(popup.startDate);
            startDate.setMinutes(startDate.getMinutes() - startDate.getTimezoneOffset());
            document.getElementById('popupStartDate').value = startDate.toISOString().slice(0, 16);
        }

        if (popup.endDate) {
            const endDate = new Date(popup.endDate);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('popupEndDate').value = endDate.toISOString().slice(0, 16);
        }

        if (popup.image) {
            const preview = document.getElementById('popupImagePreview');
            preview.innerHTML = `<img src="${popup.image}" alt="Preview">`;
            preview.classList.remove('hidden');
        }
    },

    // Preview image
    previewImage(input) {
        const preview = document.getElementById('popupImagePreview');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            if (file.size > 5 * 1024 * 1024) {
                AdminCore.showToast('Image size must be less than 5MB', 'error');
                input.value = '';
                return;
            }

            AdminCore.fileToBase64(file).then(base64 => {
                preview.innerHTML = `<img src="${base64}" alt="Preview">`;
                preview.classList.remove('hidden');
            });
        }
    },

    // Save popup
    async savePopup() {
        const form = document.getElementById('popupForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const popupId = document.getElementById('popupId').value;
        const isNew = !popupId;

        const imageFile = document.getElementById('popupImage').files[0];
        let imageBase64 = null;

        if (imageFile) {
            try {
                imageBase64 = await AdminCore.fileToBase64(imageFile);
                // Compress if it's an image (not GIF)
                if (!imageFile.name.endsWith('.gif')) {
                    imageBase64 = await AdminCore.compressImage(imageBase64, 800, 600, 0.8);
                }
            } catch (err) {
                AdminCore.showToast('Failed to process image', 'error');
                return;
            }
        }

        const popupData = {
            id: popupId || AdminCore.generateId('popup'),
            title: document.getElementById('popupTitle').value,
            content: document.getElementById('popupContent').value,
            type: document.getElementById('popupType').value,
            design: document.getElementById('popupDesign').value,
            position: document.getElementById('popupPosition').value,
            startDate: document.getElementById('popupStartDate').value || null,
            endDate: document.getElementById('popupEndDate').value || null,
            duration: parseInt(document.getElementById('popupDuration').value) || 7,
            delay: parseInt(document.getElementById('popupDelay').value) || 3,
            showOnce: document.getElementById('popupShowOnce').checked,
            closeable: document.getElementById('popupCloseable').checked,
            backdrop: document.getElementById('popupBackdrop').checked,
            active: document.getElementById('popupActive').checked,
            buttonText: document.getElementById('popupButtonText').value || null,
            buttonLink: document.getElementById('popupButtonLink').value || null,
            image: imageBase64 || (this.currentPopup?.image || null),
            views: this.currentPopup?.views || 0,
            clicks: this.currentPopup?.clicks || 0,
            updatedAt: new Date().toISOString(),
            createdAt: this.currentPopup?.createdAt || new Date().toISOString()
        };

        if (isNew) {
            this.popups.push(popupData);
            AdminCore.addLog('admin', 'Popup created', { title: popupData.title });
            AdminCore.showToast('Popup created successfully', 'success');
        } else {
            const index = this.popups.findIndex(p => p.id === popupId);
            if (index !== -1) {
                this.popups[index] = popupData;
                AdminCore.addLog('admin', 'Popup updated', { title: popupData.title });
                AdminCore.showToast('Popup updated successfully', 'success');
            }
        }

        this.savePopups();
        this.renderPopups();
        this.closeModal();
        
        // Update dashboard if visible
        if (typeof adminUI !== 'undefined') {
            adminUI.updateActivePopupsList();
        }
    },

    // Edit popup
    editPopup(id) {
        this.openModal(id);
    },

    // Toggle popup active state
    togglePopup(id) {
        const popup = this.popups.find(p => p.id === id);
        if (popup) {
            popup.active = !popup.active;
            popup.updatedAt = new Date().toISOString();
            this.savePopups();
            this.renderPopups();
            
            AdminCore.showToast(
                `Popup ${popup.active ? 'enabled' : 'disabled'}`,
                popup.active ? 'success' : 'info'
            );
            
            AdminCore.addLog('admin', `Popup ${popup.active ? 'enabled' : 'disabled'}`, { title: popup.title });
        }
    },

    // Duplicate popup
    duplicatePopup(id) {
        const popup = this.popups.find(p => p.id === id);
        if (popup) {
            const newPopup = {
                ...AdminCore.deepClone(popup),
                id: AdminCore.generateId('popup'),
                title: popup.title + ' (Copy)',
                views: 0,
                clicks: 0,
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };
            
            this.popups.push(newPopup);
            this.savePopups();
            this.renderPopups();
            
            AdminCore.showToast('Popup duplicated', 'success');
            AdminCore.addLog('admin', 'Popup duplicated', { original: popup.title });
        }
    },

    // Delete popup
    deletePopup(id) {
        const popup = this.popups.find(p => p.id === id);
        if (!popup) return;

        AdminCore.confirm(
            `Are you sure you want to delete "${popup.title}"?`,
            () => {
                this.popups = this.popups.filter(p => p.id !== id);
                this.savePopups();
                this.renderPopups();
                
                AdminCore.showToast('Popup deleted', 'success');
                AdminCore.addLog('admin', 'Popup deleted', { title: popup.title });
            }
        );
    },

    // Update badge
    updateBadge() {
        const activeCount = this.getActivePopups().length;
        if (typeof adminUI !== 'undefined') {
            adminUI.updateBadge('popupBadge', activeCount);
        }
    },

    // Get popup by ID
    getPopup(id) {
        return this.popups.find(p => p.id === id);
    },

    // Increment popup view
    incrementView(id) {
        const popup = this.popups.find(p => p.id === id);
        if (popup) {
            popup.views = (popup.views || 0) + 1;
            this.savePopups();
        }
    },

    // Increment popup click
    incrementClick(id) {
        const popup = this.popups.find(p => p.id === id);
        if (popup) {
            popup.clicks = (popup.clicks || 0) + 1;
            this.savePopups();
        }
    },

    // Clean up expired popups
    cleanupExpired() {
        const now = new Date();
        const expired = this.popups.filter(p => {
            if (!p.endDate) return false;
            return new Date(p.endDate) < now;
        });

        if (expired.length > 0) {
            AdminCore.addLog('admin', 'Cleaned up expired popups', { count: expired.length });
        }
    },

    // Export popups
    exportPopups() {
        AdminCore.exportToJson(this.popups, 'popups.json');
        AdminCore.showToast('Popups exported', 'success');
    },

    // Import popups
    importPopups(file) {
        AdminCore.importFromJson(file, (err, data) => {
            if (err) {
                AdminCore.showToast('Failed to import popups', 'error');
                return;
            }

            if (Array.isArray(data)) {
                this.popups = [...this.popups, ...data];
                this.savePopups();
                this.renderPopups();
                AdminCore.showToast(`${data.length} popups imported`, 'success');
                AdminCore.addLog('admin', 'Popups imported', { count: data.length });
            }
        });
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminPopup.init();
});

// Export for global access
window.adminPopup = adminPopup;
