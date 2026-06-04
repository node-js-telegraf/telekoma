/* ============================================
   KILLBYTE ADMIN - DISCOUNTS MODULE
   Discount and promotion management
   ============================================ */

const adminDiscounts = {
    discounts: [],
    currentDiscount: null,
    plans: [],

    // Initialize
    init() {
        this.loadPlans();
        this.loadDiscounts();
        console.log('[AdminDiscounts] Initialized');
    },

    // Load plans for selection
    loadPlans() {
        this.plans = AdminCore.getStorage(AdminCore.storage.plans, [
            { id: 'starter', name: 'Starter Surge', price: 30 },
            { id: 'edge', name: 'Edge Strike', price: 65 },
            { id: 'dual', name: 'Dual Surge', price: 85 },
            { id: 'phantom', name: 'Phantom Force', price: 160 },
            { id: 'shadow', name: 'Shadow Blade', price: 280 },
            { id: 'dominion', name: 'Dominion Control', price: 380 },
            { id: 'burst', name: 'Burst Entry', price: 15 },
            { id: 'daggers', name: 'Dual Daggers', price: 22 },
            { id: 'crimson', name: 'Crimson Reaper', price: 40 },
            { id: 'overlord', name: 'Overlord Strike', price: 160 },
            { id: 'reactor', name: 'Enterprise Reactor', price: 1200 },
            { id: 'hellstorm', name: 'Hellstorm Protocol', price: 1450 }
        ]);
    },

    // Load discounts from storage
    loadDiscounts() {
        this.discounts = AdminCore.getStorage(AdminCore.storage.discounts, []);
        this.renderDiscounts();
        this.updateBadge();
        this.syncToMainSite();
    },

    // Save discounts to storage
    saveDiscounts() {
        AdminCore.setStorage(AdminCore.storage.discounts, this.discounts);
        this.updateBadge();
        this.syncToMainSite();
    },

    // Sync discounts to main site
    syncToMainSite() {
        const activeDiscounts = this.getActiveDiscounts();
        localStorage.setItem('kb_active_discounts_sync', JSON.stringify({
            discounts: activeDiscounts,
            timestamp: Date.now()
        }));
    },

    // Get active discounts
    getActiveDiscounts() {
        const now = new Date();
        return this.discounts.filter(discount => {
            if (!discount.active) return false;
            
            const startDate = discount.startDate ? new Date(discount.startDate) : null;
            const endDate = discount.endDate ? new Date(discount.endDate) : null;
            
            const isStarted = !startDate || now >= startDate;
            const isNotExpired = !endDate || now <= endDate;
            
            return isStarted && isNotExpired;
        });
    },

    // Get discount status
    getDiscountStatus(discount) {
        const now = new Date();
        const startDate = discount.startDate ? new Date(discount.startDate) : null;
        const endDate = discount.endDate ? new Date(discount.endDate) : null;

        if (!discount.active) return 'inactive';
        if (startDate && now < startDate) return 'scheduled';
        if (endDate && now > endDate) return 'expired';
        return 'active';
    },

    // Calculate discounted price
    calculateDiscountedPrice(originalPrice, discount) {
        if (discount.type === 'percentage') {
            return originalPrice * (1 - discount.value / 100);
        } else {
            return Math.max(0, originalPrice - discount.value);
        }
    },

    // Check if discount applies to plan
    discountAppliesToPlan(discount, planId) {
        if (discount.applyTo === 'all') return true;
        if (discount.applyTo === 'selected' && discount.selectedPlans) {
            return discount.selectedPlans.includes(planId);
        }
        return false;
    },

    // Get best discount for plan
    getBestDiscountForPlan(planId) {
        const activeDiscounts = this.getActiveDiscounts();
        const applicableDiscounts = activeDiscounts.filter(d => 
            this.discountAppliesToPlan(d, planId)
        );

        if (applicableDiscounts.length === 0) return null;

        // Return the highest discount
        return applicableDiscounts.sort((a, b) => {
            if (a.type === 'percentage' && b.type === 'percentage') {
                return b.value - a.value;
            }
            if (a.type === 'fixed' && b.type === 'fixed') {
                return b.value - a.value;
            }
            // Prefer percentage for comparison (approximate)
            const aVal = a.type === 'percentage' ? a.value : (a.value / 100 * 200); // assuming avg price $200
            const bVal = b.type === 'percentage' ? b.value : (b.value / 100 * 200);
            return bVal - aVal;
        })[0];
    },

    // Render discounts
    renderDiscounts() {
        const activeContainer = document.getElementById('activeDiscountsList');
        const scheduledContainer = document.getElementById('scheduledDiscountsList');
        const expiredContainer = document.getElementById('expiredDiscountsList');

        const active = [];
        const scheduled = [];
        const expired = [];

        this.discounts.forEach(discount => {
            const status = this.getDiscountStatus(discount);
            if (status === 'active') active.push(discount);
            else if (status === 'scheduled') scheduled.push(discount);
            else expired.push(discount);
        });

        // Update counts
        this.updateElement('activeDiscountCount', active.length);
        this.updateElement('scheduledDiscountCount', scheduled.length);
        this.updateElement('expiredDiscountCount', expired.length);

        // Render lists
        if (activeContainer) {
            activeContainer.innerHTML = active.length 
                ? active.map(d => this.renderDiscountItem(d, 'active')).join('')
                : '<div class="empty-state">No active discounts</div>';
        }

        if (scheduledContainer) {
            scheduledContainer.innerHTML = scheduled.length
                ? scheduled.map(d => this.renderDiscountItem(d, 'scheduled')).join('')
                : '<div class="empty-state">No scheduled discounts</div>';
        }

        if (expiredContainer) {
            expiredContainer.innerHTML = expired.length
                ? expired.map(d => this.renderDiscountItem(d, 'expired')).join('')
                : '<div class="empty-state">No expired discounts</div>';
        }
    },

    // Render single discount item
    renderDiscountItem(discount, status) {
        const valueText = discount.type === 'percentage' 
            ? `${discount.value}% OFF` 
            : `$${discount.value} OFF`;

        const applyToText = discount.applyTo === 'all' 
            ? 'All Plans' 
            : `${discount.selectedPlans?.length || 0} Selected Plans`;

        const timeText = status === 'active' 
            ? `Ends ${AdminCore.formatDate(discount.endDate, 'short')}`
            : status === 'scheduled'
                ? `Starts ${AdminCore.formatDate(discount.startDate, 'short')}`
                : `Expired ${AdminCore.formatDate(discount.endDate, 'short')}`;

        return `
            <div class="discount-item" data-id="${discount.id}">
                <div class="discount-item-header">
                    <span class="discount-item-name">${AdminCore.escapeHtml(discount.name)}</span>
                    <span class="discount-item-value">${valueText}</span>
                </div>
                <div class="discount-item-meta">
                    <span><i class="fas fa-layer-group"></i> ${applyToText}</span>
                    <span><i class="fas fa-clock"></i> ${timeText}</span>
                    ${discount.code ? `<span><i class="fas fa-tag"></i> Code: ${discount.code}</span>` : ''}
                </div>
                <div class="discount-item-actions">
                    <button onclick="adminDiscounts.editDiscount('${discount.id}')" class="popup-card-btn">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="adminDiscounts.toggleDiscount('${discount.id}')" class="popup-card-btn">
                        <i class="fas fa-${discount.active ? 'pause' : 'play'}"></i> ${discount.active ? 'Disable' : 'Enable'}
                    </button>
                    <button onclick="adminDiscounts.deleteDiscount('${discount.id}')" class="popup-card-btn danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;
    },

    // Update element text
    updateElement(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    },

    // Open modal
    openModal(discountId = null) {
        this.currentDiscount = discountId ? this.discounts.find(d => d.id === discountId) : null;
        
        const modal = document.getElementById('discountModal');
        const title = document.getElementById('discountModalTitle');
        const form = document.getElementById('discountForm');
        
        if (!modal || !form) return;

        form.reset();

        // Render plan checkboxes
        this.renderPlanCheckboxes();

        if (this.currentDiscount) {
            title.textContent = 'Edit Discount';
            this.fillForm(this.currentDiscount);
        } else {
            title.textContent = 'Create Discount';
            document.getElementById('discountId').value = '';
            
            // Set default dates
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('discountStartDate').value = now.toISOString().slice(0, 16);
            
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 7);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('discountEndDate').value = endDate.toISOString().slice(0, 16);
        }

        this.togglePlanSelection();
        modal.classList.add('active');
    },

    // Close modal
    closeModal() {
        const modal = document.getElementById('discountModal');
        if (modal) modal.classList.remove('active');
        this.currentDiscount = null;
    },

    // Render plan checkboxes
    renderPlanCheckboxes() {
        const container = document.getElementById('planCheckboxes');
        if (!container) return;

        container.innerHTML = this.plans.map(plan => `
            <label>
                <input type="checkbox" name="selectedPlans" value="${plan.id}" 
                    ${this.currentDiscount?.selectedPlans?.includes(plan.id) ? 'checked' : ''}>
                <span>${plan.name} ($${plan.price})</span>
            </label>
        `).join('');
    },

    // Toggle plan selection visibility
    togglePlanSelection() {
        const applyTo = document.getElementById('discountApplyTo')?.value;
        const planSelection = document.getElementById('planSelection');
        if (planSelection) {
            planSelection.classList.toggle('hidden', applyTo !== 'selected');
        }
    },

    // Fill form with discount data
    fillForm(discount) {
        document.getElementById('discountId').value = discount.id;
        document.getElementById('discountName').value = discount.name;
        document.getElementById('discountType').value = discount.type;
        document.getElementById('discountValue').value = discount.value;
        document.getElementById('discountApplyTo').value = discount.applyTo;
        document.getElementById('discountCode').value = discount.code || '';
        document.getElementById('discountActive').checked = discount.active !== false;

        if (discount.startDate) {
            const startDate = new Date(discount.startDate);
            startDate.setMinutes(startDate.getMinutes() - startDate.getTimezoneOffset());
            document.getElementById('discountStartDate').value = startDate.toISOString().slice(0, 16);
        }

        if (discount.endDate) {
            const endDate = new Date(discount.endDate);
            endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());
            document.getElementById('discountEndDate').value = endDate.toISOString().slice(0, 16);
        }

        // Check selected plans
        if (discount.selectedPlans) {
            document.querySelectorAll('input[name="selectedPlans"]').forEach(cb => {
                cb.checked = discount.selectedPlans.includes(cb.value);
            });
        }
    },

    // Save discount
    saveDiscount() {
        const form = document.getElementById('discountForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const discountId = document.getElementById('discountId').value;
        const isNew = !discountId;

        // Get selected plans
        const selectedPlans = [];
        document.querySelectorAll('input[name="selectedPlans"]:checked').forEach(cb => {
            selectedPlans.push(cb.value);
        });

        const applyTo = document.getElementById('discountApplyTo').value;
        if (applyTo === 'selected' && selectedPlans.length === 0) {
            AdminCore.showToast('Please select at least one plan', 'error');
            return;
        }

        const discountData = {
            id: discountId || AdminCore.generateId('discount'),
            name: document.getElementById('discountName').value,
            type: document.getElementById('discountType').value,
            value: parseFloat(document.getElementById('discountValue').value),
            applyTo: applyTo,
            selectedPlans: applyTo === 'selected' ? selectedPlans : null,
            startDate: document.getElementById('discountStartDate').value || null,
            endDate: document.getElementById('discountEndDate').value || null,
            code: document.getElementById('discountCode').value || null,
            active: document.getElementById('discountActive').checked,
            usageCount: this.currentDiscount?.usageCount || 0,
            updatedAt: new Date().toISOString(),
            createdAt: this.currentDiscount?.createdAt || new Date().toISOString()
        };

        if (isNew) {
            this.discounts.push(discountData);
            AdminCore.addLog('admin', 'Discount created', { name: discountData.name });
            AdminCore.showToast('Discount created successfully', 'success');
        } else {
            const index = this.discounts.findIndex(d => d.id === discountId);
            if (index !== -1) {
                this.discounts[index] = discountData;
                AdminCore.addLog('admin', 'Discount updated', { name: discountData.name });
                AdminCore.showToast('Discount updated successfully', 'success');
            }
        }

        this.saveDiscounts();
        this.renderDiscounts();
        this.closeModal();
        
        // Update dashboard
        if (typeof adminUI !== 'undefined') {
            adminUI.updateActiveDiscountsList();
            adminUI.loadDashboardData();
        }
    },

    // Edit discount
    editDiscount(id) {
        this.openModal(id);
    },

    // Toggle discount active state
    toggleDiscount(id) {
        const discount = this.discounts.find(d => d.id === id);
        if (discount) {
            discount.active = !discount.active;
            discount.updatedAt = new Date().toISOString();
            this.saveDiscounts();
            this.renderDiscounts();
            
            AdminCore.showToast(
                `Discount ${discount.active ? 'enabled' : 'disabled'}`,
                discount.active ? 'success' : 'info'
            );
            
            AdminCore.addLog('admin', `Discount ${discount.active ? 'enabled' : 'disabled'}`, { name: discount.name });
        }
    },

    // Delete discount
    deleteDiscount(id) {
        const discount = this.discounts.find(d => d.id === id);
        if (!discount) return;

        AdminCore.confirm(
            `Are you sure you want to delete "${discount.name}"?`,
            () => {
                this.discounts = this.discounts.filter(d => d.id !== id);
                this.saveDiscounts();
                this.renderDiscounts();
                
                AdminCore.showToast('Discount deleted', 'success');
                AdminCore.addLog('admin', 'Discount deleted', { name: discount.name });
            }
        );
    },

    // Update badge
    updateBadge() {
        const activeCount = this.getActiveDiscounts().length;
        if (typeof adminUI !== 'undefined') {
            adminUI.updateBadge('discountBadge', activeCount);
        }
    },

    // Get discount by ID
    getDiscount(id) {
        return this.discounts.find(d => d.id === id);
    },

    // Apply discount to price
    applyDiscount(price, discountId) {
        const discount = this.getDiscount(discountId);
        if (!discount) return price;
        return this.calculateDiscountedPrice(price, discount);
    },

    // Increment usage count
    incrementUsage(discountId) {
        const discount = this.discounts.find(d => d.id === discountId);
        if (discount) {
            discount.usageCount = (discount.usageCount || 0) + 1;
            this.saveDiscounts();
        }
    },

    // Validate promo code
    validatePromoCode(code) {
        const discount = this.discounts.find(d => 
            d.code?.toLowerCase() === code.toLowerCase() && 
            this.getDiscountStatus(d) === 'active'
        );
        return discount || null;
    },

    // Clean up expired discounts
    cleanupExpired() {
        const now = new Date();
        const expired = this.discounts.filter(d => {
            if (!d.endDate) return false;
            return new Date(d.endDate) < now;
        });

        if (expired.length > 0) {
            AdminCore.addLog('admin', 'Cleaned up expired discounts', { count: expired.length });
        }
    },

    // Export discounts
    exportDiscounts() {
        AdminCore.exportToJson(this.discounts, 'discounts.json');
        AdminCore.showToast('Discounts exported', 'success');
    },

    // Import discounts
    importDiscounts(file) {
        AdminCore.importFromJson(file, (err, data) => {
            if (err) {
                AdminCore.showToast('Failed to import discounts', 'error');
                return;
            }

            if (Array.isArray(data)) {
                this.discounts = [...this.discounts, ...data];
                this.saveDiscounts();
                this.renderDiscounts();
                AdminCore.showToast(`${data.length} discounts imported`, 'success');
                AdminCore.addLog('admin', 'Discounts imported', { count: data.length });
            }
        });
    },

    // Get discount stats
    getStats() {
        const active = this.getActiveDiscounts();
        return {
            total: this.discounts.length,
            active: active.length,
            scheduled: this.discounts.filter(d => this.getDiscountStatus(d) === 'scheduled').length,
            expired: this.discounts.filter(d => this.getDiscountStatus(d) === 'expired').length,
            totalUsage: this.discounts.reduce((sum, d) => sum + (d.usageCount || 0), 0)
        };
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminDiscounts.init();
});

// Export for global access
window.adminDiscounts = adminDiscounts;
