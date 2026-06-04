/* ============================================
   KILLBYTE ADMIN - CONTENT MODULE
   Page content and text management
   ============================================ */

const adminContent = {
    currentSection: 'hero',
    content: {},
    defaultContent: {},
    unsavedChanges: false,

    // Initialize
    init() {
        this.loadDefaultContent();
        this.loadContent();
        console.log('[AdminContent] Initialized');
    },

    // Load default content
    loadDefaultContent() {
        this.defaultContent = {
            hero: {
                badge: 'Top #1 Market Services',
                title1: 'KillByte Solutions',
                title2: 'Power Infrastructure',
                description: 'The most powerful Layer 7 & Layer 4 DDoS infrastructure. Engineered for precision, built for dominance.',
                highlight: '@rankflood Best L7 & L4 DDoS',
                viewPlans: 'View Plans',
                getSupport: 'Get Support',
                stat1Label: 'L4 Capacity',
                stat1Value: '630',
                stat1Suffix: 'Gbps',
                stat2Label: 'L7 Requests/sec',
                stat2Value: '72',
                stat2Suffix: 'M+',
                stat3Label: 'Uptime',
                stat3Value: '99.9',
                stat3Suffix: '%',
                stat4Label: 'Active servers',
                stat4Value: '240',
                stat4Suffix: '+'
            },
            about: {
                label: 'About Us',
                title: 'We Are',
                text1: 'At KillByte, we specialize in providing high-powered DDoS services, particularly Layer 7 (L7), with the ability to handle the most complex and targeted attacks. We can reach up to 630 Gbps, delivering immense force to overwhelm and disrupt any target.',
                text2: 'Our state-of-the-art infrastructure allows us to execute precision strikes with up to 72 million requests per second, ensuring maximum impact with unmatched reliability.',
                feature1: 'Instant Deployment',
                feature2: 'Stealth Methods',
                feature3: 'Global servers',
                feature4: '24/7 Support'
            },
            tiers: {
                label: 'Performance',
                title: 'Power Tiers',
                subtitle: 'Choose your operational level',
                standardName: 'Standard Tier',
                standardDesc: 'Entry-level power for testing environments',
                standardPrice: '$30',
                vipName: 'VIP Tier',
                vipDesc: 'Professional grade with enhanced capabilities',
                vipPrice: '$65',
                privateName: 'Private Tier',
                privateDesc: 'Exclusive access to restricted infrastructure',
                privatePrice: '$160'
            },
            plans: {
                label: 'Pricing',
                title: 'Service Plans',
                subtitle: 'Flexible options for every requirement',
                monthlyTitle: 'Monthly Plans',
                dailyTitle: 'Daily Blasts',
                addonsTitle: 'Add-ons',
                selectPlan: 'Select Plan'
            },
            enterprise: {
                label: 'Enterprise',
                title: 'Elite Solutions',
                subtitle: 'Maximum power for maximum impact',
                reactorName: 'Enterprise Reactor',
                reactorDesc: 'Full-Scale DDoS Domination',
                reactorPrice: '$1200',
                hellstormName: 'Hellstorm Protocol',
                hellstormDesc: 'The Final Evolution',
                hellstormPrice: '$1450'
            },
            support: {
                label: 'Assistance',
                title: 'Get Support',
                subtitle: '24/7 Professional Assistance',
                telegramBot: 'Telegram Bot',
                telegramBotDesc: 'Automated ordering & instant delivery',
                directAdmin: 'Direct Admin',
                directAdminDesc: 'Contact @rankflood for sales & support',
                supportCenter: 'Support Center',
                supportCenterDesc: 'Get help from our support team',
                channelsTitle: 'Official Channels'
            },
            footer: {
                text: 'Premium infrastructure for elite operators.',
                terms: 'Terms',
                privacy: 'Privacy',
                admin: 'Admin',
                copyright: '© 2026 KillByte Solutions. All rights reserved. | Admin: @rankflood'
            }
        };
    },

    // Load content from storage
    loadContent() {
        this.content = AdminCore.getStorage(AdminCore.storage.content, {});
        
        // Merge with defaults to ensure all fields exist
        this.content = AdminCore.deepMerge(this.defaultContent, this.content);
        
        this.renderFields();
        this.syncToMainSite();
    },

    // Save content to storage
    saveContent() {
        AdminCore.setStorage(AdminCore.storage.content, this.content);
        this.unsavedChanges = false;
        this.syncToMainSite();
    },

    // Sync content to main site
    syncToMainSite() {
        localStorage.setItem('kb_content_sync', JSON.stringify({
            content: this.content,
            timestamp: Date.now()
        }));
    },

    // Switch section
    switchSection(section) {
        this.currentSection = section;
        
        // Update active button
        document.querySelectorAll('.section-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.section === section) {
                btn.classList.add('active');
            }
        });
        
        this.renderFields();
    },

    // Render fields for current section
    renderFields() {
        const container = document.getElementById('contentEditorFields');
        if (!container) return;

        const sectionContent = this.content[this.currentSection] || {};
        const fields = this.getSectionFields(this.currentSection);

        container.innerHTML = fields.map(field => {
            const value = sectionContent[field.key] || '';
            const isTextarea = field.type === 'textarea';
            
            return `
                <div class="editor-field">
                    <label>${field.label}</label>
                    ${isTextarea 
                        ? `<textarea 
                            data-key="${field.key}" 
                            rows="${field.rows || 3}"
                            onchange="adminContent.updateField('${field.key}', this.value)"
                            oninput="adminContent.markUnsaved()"
                        >${AdminCore.escapeHtml(value)}</textarea>`
                        : `<input 
                            type="text" 
                            data-key="${field.key}" 
                            value="${AdminCore.escapeHtml(value)}"
                            onchange="adminContent.updateField('${field.key}', this.value)"
                            oninput="adminContent.markUnsaved()"
                        >`
                    }
                </div>
            `;
        }).join('');
    },

    // Get fields for section
    getSectionFields(section) {
        const fields = {
            hero: [
                { key: 'badge', label: 'Badge Text' },
                { key: 'title1', label: 'Title Line 1' },
                { key: 'title2', label: 'Title Line 2' },
                { key: 'description', label: 'Description', type: 'textarea', rows: 3 },
                { key: 'highlight', label: 'Highlighted Text' },
                { key: 'viewPlans', label: 'View Plans Button' },
                { key: 'getSupport', label: 'Get Support Button' },
                { key: 'stat1Label', label: 'Stat 1 Label' },
                { key: 'stat1Value', label: 'Stat 1 Value' },
                { key: 'stat1Suffix', label: 'Stat 1 Suffix' },
                { key: 'stat2Label', label: 'Stat 2 Label' },
                { key: 'stat2Value', label: 'Stat 2 Value' },
                { key: 'stat2Suffix', label: 'Stat 2 Suffix' },
                { key: 'stat3Label', label: 'Stat 3 Label' },
                { key: 'stat3Value', label: 'Stat 3 Value' },
                { key: 'stat3Suffix', label: 'Stat 3 Suffix' },
                { key: 'stat4Label', label: 'Stat 4 Label' },
                { key: 'stat4Value', label: 'Stat 4 Value' },
                { key: 'stat4Suffix', label: 'Stat 4 Suffix' }
            ],
            about: [
                { key: 'label', label: 'Section Label' },
                { key: 'title', label: 'Title' },
                { key: 'text1', label: 'Paragraph 1', type: 'textarea', rows: 4 },
                { key: 'text2', label: 'Paragraph 2', type: 'textarea', rows: 4 },
                { key: 'feature1', label: 'Feature 1' },
                { key: 'feature2', label: 'Feature 2' },
                { key: 'feature3', label: 'Feature 3' },
                { key: 'feature4', label: 'Feature 4' }
            ],
            tiers: [
                { key: 'label', label: 'Section Label' },
                { key: 'title', label: 'Section Title' },
                { key: 'subtitle', label: 'Section Subtitle' },
                { key: 'standardName', label: 'Standard Tier Name' },
                { key: 'standardDesc', label: 'Standard Tier Description', type: 'textarea' },
                { key: 'standardPrice', label: 'Standard Tier Price' },
                { key: 'vipName', label: 'VIP Tier Name' },
                { key: 'vipDesc', label: 'VIP Tier Description', type: 'textarea' },
                { key: 'vipPrice', label: 'VIP Tier Price' },
                { key: 'privateName', label: 'Private Tier Name' },
                { key: 'privateDesc', label: 'Private Tier Description', type: 'textarea' },
                { key: 'privatePrice', label: 'Private Tier Price' }
            ],
            plans: [
                { key: 'label', label: 'Section Label' },
                { key: 'title', label: 'Section Title' },
                { key: 'subtitle', label: 'Section Subtitle' },
                { key: 'monthlyTitle', label: 'Monthly Plans Title' },
                { key: 'dailyTitle', label: 'Daily Plans Title' },
                { key: 'addonsTitle', label: 'Add-ons Title' },
                { key: 'selectPlan', label: 'Select Plan Button' }
            ],
            enterprise: [
                { key: 'label', label: 'Section Label' },
                { key: 'title', label: 'Section Title' },
                { key: 'subtitle', label: 'Section Subtitle' },
                { key: 'reactorName', label: 'Reactor Plan Name' },
                { key: 'reactorDesc', label: 'Reactor Plan Description' },
                { key: 'reactorPrice', label: 'Reactor Plan Price' },
                { key: 'hellstormName', label: 'Hellstorm Plan Name' },
                { key: 'hellstormDesc', label: 'Hellstorm Plan Description' },
                { key: 'hellstormPrice', label: 'Hellstorm Plan Price' }
            ],
            support: [
                { key: 'label', label: 'Section Label' },
                { key: 'title', label: 'Section Title' },
                { key: 'subtitle', label: 'Section Subtitle' },
                { key: 'telegramBot', label: 'Telegram Bot Title' },
                { key: 'telegramBotDesc', label: 'Telegram Bot Description' },
                { key: 'directAdmin', label: 'Direct Admin Title' },
                { key: 'directAdminDesc', label: 'Direct Admin Description' },
                { key: 'supportCenter', label: 'Support Center Title' },
                { key: 'supportCenterDesc', label: 'Support Center Description' },
                { key: 'channelsTitle', label: 'Channels Title' }
            ],
            footer: [
                { key: 'text', label: 'Footer Text' },
                { key: 'terms', label: 'Terms Link' },
                { key: 'privacy', label: 'Privacy Link' },
                { key: 'admin', label: 'Admin Link' },
                { key: 'copyright', label: 'Copyright Text' }
            ]
        };

        return fields[section] || [];
    },

    // Update field value
    updateField(key, value) {
        if (!this.content[this.currentSection]) {
            this.content[this.currentSection] = {};
        }
        this.content[this.currentSection][key] = value;
        this.unsavedChanges = true;
    },

    // Mark as unsaved
    markUnsaved() {
        this.unsavedChanges = true;
    },

    // Save all changes
    saveAllChanges() {
        this.saveContent();
        AdminCore.showToast('All changes saved successfully', 'success');
        AdminCore.addLog('admin', 'Content updated', { section: this.currentSection });
    },

    // Reset to default
    resetToDefault() {
        AdminCore.confirm(
            'Are you sure you want to reset all content to default? This cannot be undone.',
            () => {
                this.content = AdminCore.deepClone(this.defaultContent);
                this.saveContent();
                this.renderFields();
                AdminCore.showToast('Content reset to default', 'success');
                AdminCore.addLog('admin', 'Content reset to default');
            }
        );
    },

    // Reset section to default
    resetSection(section) {
        if (this.defaultContent[section]) {
            this.content[section] = AdminCore.deepClone(this.defaultContent[section]);
            this.saveContent();
            this.renderFields();
            AdminCore.showToast(`${section} section reset to default`, 'success');
        }
    },

    // Get content for section
    getContent(section) {
        return this.content[section] || this.defaultContent[section] || {};
    },

    // Get specific field value
    getFieldValue(section, key) {
        const sectionContent = this.getContent(section);
        return sectionContent[key] || this.defaultContent[section]?.[key] || '';
    },

    // Export content
    exportContent() {
        AdminCore.exportToJson(this.content, 'content.json');
        AdminCore.showToast('Content exported', 'success');
    },

    // Import content
    importContent(file) {
        AdminCore.importFromJson(file, (err, data) => {
            if (err) {
                AdminCore.showToast('Failed to import content', 'error');
                return;
            }

            this.content = AdminCore.deepMerge(this.defaultContent, data);
            this.saveContent();
            this.renderFields();
            AdminCore.showToast('Content imported successfully', 'success');
            AdminCore.addLog('admin', 'Content imported');
        });
    },

    // Clear cache
    clearCache() {
        localStorage.removeItem('kb_content_sync');
        this.syncToMainSite();
        AdminCore.showToast('Cache cleared', 'success');
    },

    // Preview changes
    previewChanges() {
        // Open main site in new tab with preview parameter
        window.open('../index.html?preview=1', '_blank');
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    adminContent.init();
});

// Export for global access
window.adminContent = adminContent;
