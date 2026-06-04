/* ============================================
   KILLBYTE ADMIN - SETTINGS MODULE
   Site settings management
   ============================================ */

const adminSettings = {
    settings: {},

    init() {
        this.loadSettings();
        console.log('[AdminSettings] Initialized');
    },

    loadSettings() {
        this.settings = AdminCore.loadSettings();
        this.fillForm();
    },

    saveSettings() {
        this.settings = {
            siteTitle: document.getElementById('siteTitle')?.value || 'KillByte Solutions',
            siteDescription: document.getElementById('siteDescription')?.value || '',
            adminContact: document.getElementById('adminContact')?.value || '@rankflood',
            primaryColor: document.getElementById('primaryColor')?.value || '#ff3333',
            themeMode: document.getElementById('themeMode')?.value || 'dark',
            enableVisitorTracking: document.getElementById('enableVisitorTracking')?.checked ?? true,
            enableNotifications: document.getElementById('enableNotifications')?.checked ?? true,
            sessionTimeout: parseInt(document.getElementById('sessionTimeout')?.value) || 30
        };

        AdminCore.saveSettings(this.settings);
        AdminCore.showToast('Settings saved successfully', 'success');
        AdminCore.addLog('admin', 'Settings updated');
        this.syncToMainSite();
    },

    fillForm() {
        const fields = ['siteTitle', 'siteDescription', 'adminContact', 'primaryColor', 'themeMode', 'sessionTimeout'];
        fields.forEach(field => {
            const el = document.getElementById(field);
            if (el && this.settings[field]) {
                el.value = this.settings[field];
            }
        });

        if (document.getElementById('enableVisitorTracking')) {
            document.getElementById('enableVisitorTracking').checked = this.settings.enableVisitorTracking;
        }
        if (document.getElementById('enableNotifications')) {
            document.getElementById('enableNotifications').checked = this.settings.enableNotifications;
        }
    },

    syncToMainSite() {
        localStorage.setItem('kb_settings_sync', JSON.stringify({
            settings: this.settings,
            timestamp: Date.now()
        }));
    },

    saveAll() {
        this.saveSettings();
    },

    clearAllData() {
        AdminCore.confirm(
            'WARNING: This will delete ALL data including visitors, settings, popups, discounts, and configurations. This action CANNOT be undone.\n\nAre you absolutely sure?',
            () => {
                AdminCore.confirm(
                    'Final confirmation: Type "DELETE" to confirm complete data removal.',
                    () => {
                        const keys = Object.keys(localStorage).filter(k => k.startsWith('kb_'));
                        keys.forEach(key => localStorage.removeItem(key));
                        
                        AdminCore.showToast('All data has been cleared', 'success');
                        AdminCore.addLog('admin', 'All data cleared');
                        
                        setTimeout(() => window.location.reload(), 1500);
                    }
                );
            }
        );
    },

    resetDefaults() {
        AdminCore.confirm(
            'Are you sure you want to reset all settings to default?',
            () => {
                AdminCore.removeStorage(AdminCore.storage.settings);
                this.loadSettings();
                AdminCore.showToast('Settings reset to default', 'success');
                AdminCore.addLog('admin', 'Settings reset to default');
            }
        );
    },

    exportData() {
        const data = {
            settings: this.settings,
            exportDate: new Date().toISOString()
        };
        AdminCore.exportToJson(data, 'settings.json');
        AdminCore.showToast('Settings exported', 'success');
    },

    importData(file) {
        AdminCore.importFromJson(file, (err, data) => {
            if (err) {
                AdminCore.showToast('Failed to import settings', 'error');
                return;
            }

            if (data.settings) {
                this.settings = data.settings;
                AdminCore.saveSettings(this.settings);
                this.fillForm();
                AdminCore.showToast('Settings imported successfully', 'success');
                AdminCore.addLog('admin', 'Settings imported');
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminSettings.init();
});

window.adminSettings = adminSettings;
