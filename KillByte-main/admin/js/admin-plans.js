/* ============================================
   KILLBYTE ADMIN - PLANS MODULE
   Service plans management
   ============================================ */

const adminPlans = {
    plans: [],
    currentPlan: null,

    defaultPlans: {
        monthly: [
            { id: 'starter', name: 'Starter Surge', duration: '7 Days', price: 30, slots: 1, time: 60 },
            { id: 'edge', name: 'Edge Strike', duration: '30 Days', price: 65, slots: 1, time: 120 },
            { id: 'dual', name: 'Dual Surge', duration: '30 Days', price: 85, slots: 2, time: 120 },
            { id: 'phantom', name: 'Phantom Force', duration: 'VIP', price: 160, slots: 3, time: 200, featured: true },
            { id: 'shadow', name: 'Shadow Blade', duration: 'VIP', price: 280, slots: 4, time: 250 },
            { id: 'dominion', name: 'Dominion Control', duration: 'VIP+', price: 380, slots: 4, time: 250 }
        ],
        daily: [
            { id: 'burst', name: 'Burst Entry', duration: 'Daily', price: 15, slots: 1, time: 120 },
            { id: 'daggers', name: 'Dual Daggers', duration: 'Daily', price: 22, slots: 2, time: 120 },
            { id: 'crimson', name: 'Crimson Reaper', duration: 'VIP', price: 40, slots: 2, time: 250 },
            { id: 'overlord', name: 'Overlord Strike', duration: 'VIP+', price: 160, slots: 2, time: 250 }
        ],
        addons: [
            { id: 'concurrent', name: '+1 Concurrent', price: 40, icon: 'plus' },
            { id: 'duration', name: '+60s Duration', price: 10, icon: 'clock' },
            { id: 'api', name: 'API Gateway', price: 50, icon: 'code' },
            { id: 'cooldown', name: 'Zero Cooldown', price: 15, icon: 'ban' },
            { id: 'private', name: 'Private Access', price: 80, icon: 'lock' },
            { id: 'vip', name: 'VIP Upgrade', price: 25, icon: 'star' }
        ],
        enterprise: [
            { id: 'reactor', name: 'Enterprise Reactor', price: 1200, period: 'month', 
              features: ['35M+ Rq/s Layer 7 Power', '150+ Gbps L4 Flood Capability', 'Multi-Target Parallel Handling', 'API-Optimized Deployment', 'Priority Slot Execution', 'Zero Packet Loss'] },
            { id: 'hellstorm', name: 'Hellstorm Protocol', price: 1450, period: 'month',
              features: ['45M+ Rq/s L7 Floods', '200+ Gbps Raw L4 Output', 'VIP-Only Private servers', 'Obfuscation Methods', 'Exclusive L7-KILLER Payloads', 'Zero-Lag Launch Time'] }
        ]
    },

    init() {
        this.loadPlans();
        console.log('[AdminPlans] Initialized');
    },

    loadPlans() {
        this.plans = AdminCore.getStorage(AdminCore.storage.plans, this.defaultPlans);
        // Ensure all categories exist
        this.plans = { ...this.defaultPlans, ...this.plans };
        this.renderPlans();
        this.syncToMainSite();
    },

    savePlans() {
        AdminCore.setStorage(AdminCore.storage.plans, this.plans);
        this.syncToMainSite();
    },

    syncToMainSite() {
        localStorage.setItem('kb_plans_sync', JSON.stringify({
            plans: this.plans,
            timestamp: Date.now()
        }));
    },

    renderPlans() {
        this.renderCategory('monthlyPlansList', this.plans.monthly);
        this.renderCategory('dailyPlansList', this.plans.daily);
        this.renderAddons();
    },

    renderCategory(containerId, plans) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = plans.map(plan => {
            const discountedPrice = this.getDiscountedPrice(plan);
            const hasDiscount = discountedPrice < plan.price;
            
            return `
                <div class="plan-item" data-id="${plan.id}">
                    <div class="plan-item-info">
                        <div class="plan-item-name">${AdminCore.escapeHtml(plan.name)}</div>
                        <div class="plan-item-price">
                            ${hasDiscount 
                                ? `<span style="text-decoration: line-through; opacity: 0.6;">$${plan.price}</span> <span style="color: var(--success);">$${discountedPrice}</span>`
                                : `$${plan.price}`
                            }
                            ${plan.duration ? `• ${plan.duration}` : ''}
                        </div>
                    </div>
                    <div class="plan-item-actions">
                        <button onclick="adminPlans.editPlan('${plan.id}', 'monthly')" class="plan-item-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="adminPlans.deletePlan('${plan.id}', 'monthly')" class="plan-item-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    },

    renderAddons() {
        const container = document.getElementById('addonsList');
        if (!container) return;

        container.innerHTML = this.plans.addons.map(addon => `
            <div class="plan-item" data-id="${addon.id}">
                <div class="plan-item-info">
                    <div class="plan-item-name">
                        <i class="fas fa-${addon.icon}"></i> ${AdminCore.escapeHtml(addon.name)}
                    </div>
                    <div class="plan-item-price">$${addon.price}</div>
                </div>
                <div class="plan-item-actions">
                    <button onclick="adminPlans.editAddon('${addon.id}')" class="plan-item-btn">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="adminPlans.deleteAddon('${addon.id}')" class="plan-item-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    },

    getDiscountedPrice(plan) {
        if (typeof adminDiscounts !== 'undefined') {
            const discount = adminDiscounts.getBestDiscountForPlan(plan.id);
            if (discount) {
                return adminDiscounts.calculateDiscountedPrice(plan.price, discount);
            }
        }
        return plan.price;
    },

    openModal(category = 'monthly', planId = null) {
        this.currentPlan = planId ? this.findPlan(planId, category) : null;
        // Implementation for plan modal
        AdminCore.showToast('Plan editor coming soon', 'info');
    },

    findPlan(id, category) {
        return this.plans[category]?.find(p => p.id === id);
    },

    editPlan(id, category) {
        this.openModal(category, id);
    },

    deletePlan(id, category) {
        AdminCore.confirm('Are you sure you want to delete this plan?', () => {
            this.plans[category] = this.plans[category].filter(p => p.id !== id);
            this.savePlans();
            this.renderPlans();
            AdminCore.showToast('Plan deleted', 'success');
        });
    },

    editAddon(id) {
        // Implementation for addon editing
    },

    deleteAddon(id) {
        AdminCore.confirm('Are you sure you want to delete this addon?', () => {
            this.plans.addons = this.plans.addons.filter(a => a.id !== id);
            this.savePlans();
            this.renderPlans();
            AdminCore.showToast('Addon deleted', 'success');
        });
    },

    getPlanById(id) {
        for (const category of ['monthly', 'daily', 'enterprise']) {
            const plan = this.plans[category]?.find(p => p.id === id);
            if (plan) return plan;
        }
        return null;
    },

    getAllPlans() {
        return [
            ...(this.plans.monthly || []),
            ...(this.plans.daily || []),
            ...(this.plans.enterprise || [])
        ];
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminPlans.init();
});

window.adminPlans = adminPlans;
