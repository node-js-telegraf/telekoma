/* ============================================
   KILLBYTE PREMIUM - TELEGRAM INTEGRATION
   Bot Configuration & Messaging
   ============================================ */

// Telegram State
const telegramState = {
    botToken: '',
    chatId: '',
    notifyEnabled: true,
    isConnected: false
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    loadTelegramConfig();
});

// ============================================
// CONFIGURATION
// ============================================
function loadTelegramConfig() {
    telegramState.botToken = localStorage.getItem('kb_bot_token') || '';
    telegramState.chatId = localStorage.getItem('kb_chat_id') || '';
    telegramState.notifyEnabled = localStorage.getItem('kb_notify_enabled') !== 'false';

    // Update form fields if they exist
    const tokenInput = document.getElementById('botToken');
    const chatIdInput = document.getElementById('chatId');
    const notifyCheckbox = document.getElementById('notifyEnabled');

    if (tokenInput) tokenInput.value = telegramState.botToken;
    if (chatIdInput) chatIdInput.value = telegramState.chatId;
    if (notifyCheckbox) notifyCheckbox.checked = telegramState.notifyEnabled;

    // Update status
    updateTelegramStatus();
}

function saveTelegramConfig() {
    const tokenInput = document.getElementById('botToken');
    const chatIdInput = document.getElementById('chatId');
    const notifyCheckbox = document.getElementById('notifyEnabled');

    telegramState.botToken = tokenInput ? tokenInput.value.trim() : '';
    telegramState.chatId = chatIdInput ? chatIdInput.value.trim() : '';
    telegramState.notifyEnabled = notifyCheckbox ? notifyCheckbox.checked : true;

    // Save to localStorage
    localStorage.setItem('kb_bot_token', telegramState.botToken);
    localStorage.setItem('kb_chat_id', telegramState.chatId);
    localStorage.setItem('kb_notify_enabled', telegramState.notifyEnabled.toString());

    // Update status
    updateTelegramStatus();

    // Test connection
    testTelegramConnection();

    if (typeof showNotification === 'function') {
        showNotification('Telegram configuration saved', 'success');
    }
}

function updateTelegramStatus() {
    const statusEl = document.getElementById('telegramStatus');
    
    if (statusEl) {
        if (telegramState.botToken && telegramState.chatId) {
            statusEl.textContent = 'ON';
            statusEl.style.color = '#00ff88';
            telegramState.isConnected = true;
        } else {
            statusEl.textContent = 'OFF';
            statusEl.style.color = '#ff4444';
            telegramState.isConnected = false;
        }
    }
}

// ============================================
// CONNECTION TEST
// ============================================
async function testTelegramConnection() {
    if (!telegramState.botToken || !telegramState.chatId) return;

    try {
        const response = await fetch(`https://api.telegram.org/bot${telegramState.botToken}/getMe`);
        const data = await response.json();

        if (data.ok) {
            console.log('Telegram bot connected:', data.result.username);
            telegramState.isConnected = true;
            
            // Send test message
            await sendTelegramMessage('🔔 <b>KillByte Bot Connected</b>\n\nVisitor notifications are now active!', false);
        } else {
            console.error('Telegram bot connection failed:', data.description);
            telegramState.isConnected = false;
            if (typeof showNotification === 'function') {
                showNotification('Bot connection failed. Check your token.', 'error');
            }
        }
    } catch (error) {
        console.error('Telegram connection error:', error);
        telegramState.isConnected = false;
    }
}

// ============================================
// SEND MESSAGE
// ============================================
async function sendTelegramMessage(message, silent = false) {
    if (!telegramState.botToken || !telegramState.chatId) {
        console.log('Telegram not configured');
        return false;
    }

    try {
        const response = await fetch(`https://api.telegram.org/bot${telegramState.botToken}/sendMessage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                chat_id: telegramState.chatId,
                text: message,
                parse_mode: 'HTML',
                disable_notification: silent
            })
        });

        const data = await response.json();

        if (data.ok) {
            console.log('Message sent to Telegram');
            return true;
        } else {
            console.error('Telegram API error:', data.description);
            return false;
        }
    } catch (error) {
        console.error('Failed to send Telegram message:', error);
        return false;
    }
}

// ============================================
// SEND ORDER NOTIFICATION
// ============================================
async function sendOrderNotification(orderDetails) {
    if (!telegramState.botToken || !telegramState.chatId) return;

    const date = new Date().toLocaleString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    const message = `
💰 <b>New Order on KillByte</b>

📦 <b>Order Details</b>
├ Plan: ${orderDetails.plan}
├ Price: $${orderDetails.price}
├ Type: ${orderDetails.type}
└ Time: ${date}

👤 <b>Customer</b>
├ IP: ${orderDetails.ip || 'Unknown'}
└ Contact: @${orderDetails.username || 'Unknown'}

⚡ Action required! Contact customer ASAP.
    `.trim();

    return await sendTelegramMessage(message);
}

// ============================================
// SEND SYSTEM ALERT
// ============================================
async function sendSystemAlert(alertType, details) {
    if (!telegramState.botToken || !telegramState.chatId) return;

    const icons = {
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️',
        success: '✅'
    };

    const message = `
${icons[alertType] || 'ℹ️'} <b>System Alert</b>

<b>Type:</b> ${alertType.toUpperCase()}
<b>Details:</b> ${details}
<b>Time:</b> ${new Date().toLocaleString()}
    `.trim();

    return await sendTelegramMessage(message, alertType === 'info');
}

// Export functions
window.saveTelegramConfig = saveTelegramConfig;
window.testTelegramConnection = testTelegramConnection;
window.sendTelegramMessage = sendTelegramMessage;
window.sendOrderNotification = sendOrderNotification;
window.sendSystemAlert = sendSystemAlert;
window.updateTelegramStatus = updateTelegramStatus;
