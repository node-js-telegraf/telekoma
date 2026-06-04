/* ============================================
   KILLBYTE - DISCOUNT MANAGER
   Handles discounts on main site
   ============================================ */

const DiscountManager = {
    discounts: [],
    appliedDiscounts: {},

    init() {
        this.loadDiscounts();
        this.setupSyncListener();
        this.applyDiscountsToPage();
        console.log('[DiscountManager] Initialized');
    },

    loadDiscounts() {
        const sync = localStorage.getItem('kb_active_discounts_sync');
        if (sync) {
            try {
                const data = JSON.parse(sync);
                this.discounts = data.discounts || [];
            } catch (e) {
                console.error('Failed to load discounts:', e);
            }
        }
    },

    setupSyncListener() {
        window.addEventListener('storage', (e) => {
            if (e.key === 'kb_active_discounts_sync') {
                this.loadDiscounts();
                this.applyDiscountsToPage();
            }
        });
    },

    getActiveDiscounts() {
        const now = new Date();
        return this.discounts.filter(d => {
            if (!d.active) return false;
            
            const start = d.startDate ? new Date(d.startDate) : null;
            const end = d.endDate ? new Date(d.endDate) : null;
            
            return (!start || now >= start) && (!end || now <= end);
        });
    },

    getBestDiscountForPlan(planId) {
        const active = this.getActiveDiscounts();
        const applicable = active.filter(d => {
            if (d.applyTo === 'all') return true;
            return d.selectedPlans?.includes(planId);
        });

        if (applicable.length === 0) return null;

        // Sort by discount value
        return applicable.sort((a, b) => {
            if (a.type === 'percentage' && b.type === 'percentage') {
                return b.value - a.value;
            }
            if (a.type === 'fixed' && b.type === 'fixed') {
                return b.value - a.value;
            }
            return a.type === 'percentage' ? -1 : 1;
        })[0];
    },

    calculateDiscountedPrice(originalPrice, discount) {
        if (!discount) return originalPrice;
        
        if (discount.type === 'percentage') {
            return Math.round(originalPrice * (1 - discount.value / 100));
        } else {
            return Math.max(0, originalPrice - discount.value);
        }
    },

    applyDiscountsToPage() {
        // Find all price elements and apply discounts
        document.querySelectorAll('[data-plan-id]').forEach(el => {
            const planId = el.dataset.planId;
            const originalPrice = parseFloat(el.dataset.originalPrice);
            
            if (planId && originalPrice) {
                const discount = this.getBestDiscountForPlan(planId);
                
                if (discount) {
                    const discountedPrice = this.calculateDiscountedPrice(originalPrice, discount);
                    this.showDiscountedPrice(el, originalPrice, discountedPrice, discount);
                }
            }
        });

        // Update tier prices
        this.updateTierPrices();
        
        // Update plan prices
        this.updatePlanPrices();
    },

    showDiscountedPrice(el, original, discounted, discount) {
        const discountText = discount.type === 'percentage' 
            ? `-${discount.value}%` 
            : `-$${discount.value}`;

        el.innerHTML = `
            <span class="original-price" style="text-decoration: line-through; opacity: 0.6;">$${original}</span>
            <span class="discounted-price" style="color: #00c853; font-weight: 700;">$${discounted}</span>
            <span class="discount-badge" style="background: #00c853; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">${discountText}</span>
        `;
    },

    updateTierPrices() {
        const tierPrices = {
            'tier-standard': { id: 'standard', price: 30 },
            'tier-vip': { id: 'vip', price: 65 },
            'tier-private': { id: 'private', price: 160 }
        };

        Object.entries(tierPrices).forEach(([tierId, data]) => {
            const discount = this.getBestDiscountForPlan(data.id);
            if (discount) {
                const discounted = this.calculateDiscountedPrice(data.price, discount);
                const priceEl = document.querySelector(`#${tierId} .tier-price`);
                if (priceEl) {
                    priceEl.innerHTML = `
                        <span style="text-decoration: line-through; opacity: 0.6; font-size: 0.8em;">$${data.price}</span>
                        <span style="color: #00c853;">$${discounted}</span>
                    `;
                }
            }
        });
    },

    updatePlanPrices() {
        const planCards = document.querySelectorAll('.plan-card');
        
        planCards.forEach(card => {
            const planName = card.querySelector('.plan-name')?.textContent;
            const priceEl = card.querySelector('.plan-price');
            
            if (planName && priceEl) {
                const planId = this.getPlanIdByName(planName);
                if (planId) {
                    const originalPrice = this.extractPrice(priceEl.textContent);
                    const discount = this.getBestDiscountForPlan(planId);
                    
                    if (discount && originalPrice) {
                        const discounted = this.calculateDiscountedPrice(originalPrice, discount);
                        const discountText = discount.type === 'percentage' 
                            ? `-${discount.value}%` 
                            : `-$${discount.value}`;
                        
                        priceEl.innerHTML = `
                            <span style="text-decoration: line-through; opacity: 0.6; font-size: 0.7em; display: block;">$${originalPrice}</span>
                            <span style="color: #00c853;">$${discounted}</span>
                            <span style="background: #00c853; color: #fff; padding: 2px 6px; border-radius: 8px; font-size: 0.6em; margin-left: 5px;">${discountText}</span>
                        `;
                    }
                }
            }
        });
    },

    getPlanIdByName(name) {
        const planMap = {
            'Starter Surge': 'starter',
            'Edge Strike': 'edge',
            'Dual Surge': 'dual',
            'Phantom Force': 'phantom',
            'Shadow Blade': 'shadow',
            'Dominion Control': 'dominion',
            'Burst Entry': 'burst',
            'Dual Daggers': 'daggers',
            'Crimson Reaper': 'crimson',
            'Overlord Strike': 'overlord',
            'Enterprise Reactor': 'reactor',
            'Hellstorm Protocol': 'hellstorm'
        };
        
        return planMap[name] || null;
    },

    extractPrice(text) {
        const match = text.match(/\$(\d+)/);
        return match ? parseInt(match[1]) : null;
    },

    validatePromoCode(code) {
        const discount = this.discounts.find(d => 
            d.code?.toLowerCase() === code.toLowerCase() &&
            d.active &&
            this.isDiscountActive(d)
        );
        return discount || null;
    },

    isDiscountActive(discount) {
        const now = new Date();
        const start = discount.startDate ? new Date(discount.startDate) : null;
        const end = discount.endDate ? new Date(discount.endDate) : null;
        return (!start || now >= start) && (!end || now <= end);
    },

    applyPromoCode(code, planId) {
        const discount = this.validatePromoCode(code);
        
        if (!discount) {
            return { success: false, message: 'Invalid promo code' };
        }

        if (discount.applyTo === 'selected' && !discount.selectedPlans?.includes(planId)) {
            return { success: false, message: 'Code not valid for this plan' };
        }

        this.appliedDiscounts[planId] = discount;
        
        return { 
            success: true, 
            discount,
            message: `Promo code applied! ${discount.value}${discount.type === 'percentage' ? '%' : '$'} off`
        };
    },

    getDiscountedPriceForPlan(planId, originalPrice) {
        const discount = this.appliedDiscounts[planId] || this.getBestDiscountForPlan(planId);
        return this.calculateDiscountedPrice(originalPrice, discount);
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    DiscountManager.init();
});

window.DiscountManager = DiscountManager;
