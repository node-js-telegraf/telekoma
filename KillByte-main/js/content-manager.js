/* ============================================
   KILLBYTE - CONTENT MANAGER
   Handles dynamic content on main site
   ============================================ */

const ContentManager = {
    content: {},
    defaultContent: {},

    init() {
        this.loadDefaultContent();
        this.loadContent();
        this.setupSyncListener();
        this.applyContent();
        console.log('[ContentManager] Initialized');
    },

    loadDefaultContent() {
        this.defaultContent = {
            hero: {
                badge: 'Top #1 Market Services',
                title1: 'KillByte Solutions',
                title2: 'Power Infrastructure',
                description: 'The most powerful Layer 7 & Layer 4 DDoS infrastructure. Engineered for precision, built for dominance.',
                highlight: '@rankflood Best L7 & L4 DDoS',
                viewPlans: 'View Plans',
                getSupport: 'Get Support'
            },
            about: {
                label: 'About Us',
                title: 'We Are',
                text1: 'At KillByte, we specialize in providing high-powered DDoS services, particularly Layer 7 (L7), with the ability to handle the most complex and targeted attacks.',
                text2: 'Our state-of-the-art infrastructure allows us to execute precision strikes with up to 72 million requests per second.',
                feature1: 'Instant Deployment',
                feature2: 'Stealth Methods',
                feature3: 'Global servers',
                feature4: '24/7 Support'
            },
            tiers: {
                label: 'Performance',
                title: 'Power Tiers',
                subtitle: 'Choose your operational level'
            },
            plans: {
                label: 'Pricing',
                title: 'Service Plans',
                subtitle: 'Flexible options for every requirement'
            },
            enterprise: {
                label: 'Enterprise',
                title: 'Elite Solutions',
                subtitle: 'Maximum power for maximum impact'
            },
            support: {
                label: 'Assistance',
                title: 'Get Support',
                subtitle: '24/7 Professional Assistance'
            },
            footer: {
                text: 'Premium infrastructure for elite operators.',
                copyright: '© 2026 KillByte Solutions. All rights reserved. | Admin: @rankflood'
            }
        };
    },

    loadContent() {
        const sync = localStorage.getItem('kb_content_sync');
        if (sync) {
            try {
                const data = JSON.parse(sync);
                this.content = data.content || {};
            } catch (e) {
                console.error('Failed to load content:', e);
            }
        }
    },

    setupSyncListener() {
        window.addEventListener('storage', (e) => {
            if (e.key === 'kb_content_sync') {
                this.loadContent();
                this.applyContent();
            }
        });
    },

    getContent(section, key) {
        return this.content[section]?.[key] || this.defaultContent[section]?.[key] || '';
    },

    applyContent() {
        // Hero section
        this.updateText('.hero-badge span', this.getContent('hero', 'badge'));
        this.updateText('.hero-title .title-line:first-child', this.getContent('hero', 'title1'));
        this.updateText('.hero-title .title-line.gradient', this.getContent('hero', 'title2'));
        this.updateText('.hero-description', this.getContent('hero', 'description'));
        this.updateText('.hero-actions .btn-primary span', this.getContent('hero', 'viewPlans'));
        this.updateText('.hero-actions .btn-secondary span', this.getContent('hero', 'getSupport'));

        // About section
        this.updateText('#about .section-label', this.getContent('about', 'label'));
        this.updateText('#about .section-title', this.getContent('about', 'title') + ' KillByte');
        
        const aboutTexts = document.querySelectorAll('.about-text');
        if (aboutTexts[0]) aboutTexts[0].innerHTML = this.getContent('about', 'text1');
        if (aboutTexts[1]) aboutTexts[1].innerHTML = this.getContent('about', 'text2');

        const aboutFeatures = document.querySelectorAll('.about-feature span');
        if (aboutFeatures[0]) aboutFeatures[0].textContent = this.getContent('about', 'feature1');
        if (aboutFeatures[1]) aboutFeatures[1].textContent = this.getContent('about', 'feature2');
        if (aboutFeatures[2]) aboutFeatures[2].textContent = this.getContent('about', 'feature3');
        if (aboutFeatures[3]) aboutFeatures[3].textContent = this.getContent('about', 'feature4');

        // Tiers section
        this.updateText('#tiers .section-label', this.getContent('tiers', 'label'));
        this.updateText('#tiers .section-title', this.getContent('tiers', 'title'));
        this.updateText('#tiers .section-subtitle', this.getContent('tiers', 'subtitle'));

        // Plans section
        this.updateText('#plans .section-label', this.getContent('plans', 'label'));
        this.updateText('#plans .section-title', this.getContent('plans', 'title'));
        this.updateText('#plans .section-subtitle', this.getContent('plans', 'subtitle'));

        // Enterprise section
        this.updateText('#enterprise .section-label', this.getContent('enterprise', 'label'));
        this.updateText('#enterprise .section-title', this.getContent('enterprise', 'title'));
        this.updateText('#enterprise .section-subtitle', this.getContent('enterprise', 'subtitle'));

        // Support section
        this.updateText('#support .section-label', this.getContent('support', 'label'));
        this.updateText('#support .section-title', this.getContent('support', 'title'));
        this.updateText('#support .section-subtitle', this.getContent('support', 'subtitle'));

        // Footer
        this.updateText('.footer-text', this.getContent('footer', 'text'));
        this.updateText('.footer-copy', this.getContent('footer', 'copyright'));
    },

    updateText(selector, text) {
        if (!text) return;
        const el = document.querySelector(selector);
        if (el) el.textContent = text;
    },

    // Update specific section
    updateSection(section) {
        const sectionContent = this.content[section];
        if (!sectionContent) return;

        Object.entries(sectionContent).forEach(([key, value]) => {
            const el = document.querySelector(`[data-content="${section}.${key}"]`);
            if (el) el.textContent = value;
        });
    },

    // Get all content
    getAllContent() {
        return { ...this.defaultContent, ...this.content };
    },

    // Reset to default
    resetToDefault() {
        this.content = {};
        localStorage.removeItem('kb_content_sync');
        this.applyContent();
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    ContentManager.init();
});

window.ContentManager = ContentManager;
