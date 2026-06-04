/* ============================================
   KILLBYTE ADMIN - TELEGRAM MODULE
   Telegram bot configuration
   ============================================ */

const adminTelegram = {
    config: {},

    init() {
        this.loadConfig();
        console.log('[AdminTelegram] Initialized');
    },

    loadConfig() {
        this.config = AdminCore.getStorage('kb_telegram_config', {
            botToken: '',
            chatId: '',
            notifyVisitors: true,
            notifyOrders: true,
            notifyErrors: true
        });

        this.fillForm();
    },

    saveConfig() {
        this.config = {
            botToken: document.getElementById('telegramBotToken')?.value || '',
            chatId: document.getElementById('telegramChatId')?.value || '',
            notifyVisitors: document.getElementById('telegramNotifyVisitors')?.checked ?? true,
            notifyOrders: document.getElementById('telegramNotifyOrders')?.checked ?? true,
            notifyErrors: document.getElementById('telegramNotifyErrors')?.checked ?? true
        };

        AdminCore.setStorage('kb_telegram_config', this.config);
        AdminCore.showToast('Telegram configuration saved', 'success');
        AdminCore.addLog('admin', 'Telegram config updated');
    },

    fillForm() {
        if (document.getElementById('telegramBotToken')) {
            document.getElementById('telegramBotToken').value = this.config.botToken;
        }
        if (document.getElementById('telegramChatId')) {
            document.getElementById('telegramChatId').value = this.config.chatId;
        }
        if (document.getElementById('telegramNotifyVisitors')) {
            document.getElementById('telegramNotifyVisitors').checked = this.config.notifyVisitors;
        }
        if (document.getElementById('telegramNotifyOrders')) {
            document.getElementById('telegramNotifyOrders').checked = this.config.notifyOrders;
        }
    },

    async testConnection() {
        if (!this.config.botToken) {
            AdminCore.showToast('Please enter bot token first', 'error');
            return;
        }

        AdminCore.showLoading('Testing connection...');

        try {
            const response = await fetch(`https://api.telegram.org/bot${this.config.botToken}/getMe`);
            const data = await response.json();

            AdminCore.hideLoading();

            if (data.ok) {
                AdminCore.showToast(`Connected to @${data.result.username}`, 'success');
                
                // Send test message if chatId is set
                if (this.config.chatId) {
                    await this.sendTestMessage();
                }
            } else {
                AdminCore.showToast('Connection failed: ' + data.description, 'error');
            }
        } catch (err) {
            AdminCore.hideLoading();
            AdminCore.showToast('Connection error: ' + err.message, 'error');
        }
    },

    async sendTestMessage() {
        const message = `
<b>KillByte Bot Test</b>

Your Telegram bot is now connected!
Time: ${new Date().toLocaleString()}
        `.trim();

        try {
            await fetch(`https://api.telegram.org/bot${this.config.botToken}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chat_id: this.config.chatId,
                    text: message,
                    parse_mode: 'HTML'
                })
            });
        } catch (err) {
            console.error('Failed to send test message:', err);
        }
    },

    async sendNotification(type, data) {
        if (!this.config.botToken || !this.config.chatId) return;

        const shouldNotify = {
            visitor: this.config.notifyVisitors,
            order: this.config.notifyOrders,
            error: this.config.notifyErrors
        };

        if (!shouldNotify[type]) return;

        const messages = {
            visitor: () => `
<b>New Visitor</b>

IP: <code>${data.ip}</code>
Location: ${data.city}, ${data.country}
Time: ${new Date().toLocaleString()}
            `,
            order: () => `
<b>New Order</b>

Plan: ${data.plan}
Price: $${data.price}
Time: ${new Date().toLocaleString()}
            `,
            error: () => `
<b>System Error</b>

Error: ${data.message}
Time: ${new Date().toLocaleString()}
            `
        };

        try {
            await fetch(`https://api.telegram.org/bot${this.config.botToken}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chat_id: this.config.chatId,
                    text: messages[type]().trim(),
                    parse_mode: 'HTML'
                })
            });
        } catch (err) {
            console.error('Failed to send Telegram notification:', err);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminTelegram.init();
});

window.adminTelegram = adminTelegram;
