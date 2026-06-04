/* ============================================
   KILLBYTE PREMIUM - TERMINAL JAVASCRIPT
   Interactive macOS Terminal with Commands
   ============================================ */

// Terminal State
const Terminal = {
    input: '',
    history: [],
    historyIndex: -1,
    isTyping: false,
    content: null,
    inputEl: null,
    cursorEl: null,
    bodyEl: null,
    initialized: false,
    username: 'Guest',
    hostname: 'KillByte',
    version: '2.5'
};

// ASCII Art Logo
const ASCII_LOGO = [
    { text: '<span class="ansi-red">      __  ___ </span>', delay: 50 },
    { text: '<span class="ansi-red">     |  |/  / </span>  <span class="ansi-bold">KillByte Solutions</span> (v2.5)', delay: 50 },
    { text: '<span class="ansi-red">     |  \'  /  </span>  Developed by <span class="ansi-red">@RankFlood</span>', delay: 50 },
    { text: '<span class="ansi-red">     |    <   </span>  Join our Telegram channel: <span class="ansi-red">https://t.me/KillByteSolution</span>', delay: 50 },
    { text: '<span class="ansi-red">     |  .  \\  </span>  Type <span class="ansi-red">"help"</span> to view the command list', delay: 50 },
    { text: '<span class="ansi-red">     |__|\\__\\ </span>', delay: 50 },
    { text: '', delay: 50 }
];

// Command Definitions
const COMMANDS = {
    help: {
        description: 'Display available commands',
        execute: () => {
            return `
<span class="ansi-red ansi-bold">COMMANDS OVERVIEW:</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">clear</span><span class="ansi-gray">: Resets the terminal and clears the workspace.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">help</span><span class="ansi-gray">: Displays this help menu for navigation and guidance.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">methods</span><span class="ansi-gray">: Lists all available attack methods with detailed descriptions.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">myinfo</span><span class="ansi-gray">: View your account details, statistics, and subscription info.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">server</span><span class="ansi-gray">: Check the live botnet server status and uptime.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">passwd</span><span class="ansi-gray">: Securely update or reset your account password.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">themes</span><span class="ansi-gray">: Customize the terminal appearance to fit your style.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">ongoing</span><span class="ansi-gray">: Monitor the live progress of ongoing operations.</span>

<span class="ansi-red ansi-bold">HOW TO EXECUTE ATTACKS:</span>
<span class="ansi-gray"> 1. Select an attack method and enter the command (e.g.,</span> <span class="ansi-red">http-exploit</span><span class="ansi-gray">).</span>
<span class="ansi-gray"> 2. Provide the following required parameters:</span>
<span class="ansi-gray">    </span><span class="ansi-white">- host</span><span class="ansi-gray">: Target URL or IP.</span>
<span class="ansi-gray">    </span><span class="ansi-white">- port</span><span class="ansi-gray">: Target port (e.g., 80, 443).</span>
<span class="ansi-gray">    </span><span class="ansi-white">- time</span><span class="ansi-gray">: Duration of the attack (in seconds).</span>
<span class="ansi-gray">    </span><span class="ansi-white">- rate</span><span class="ansi-gray">: Rate of attack (e.g., 1000 RPS).</span>
<span class="ansi-gray">    </span><span class="ansi-white">- cache</span><span class="ansi-gray">: Use cache (true/false) - Optional.</span>
<span class="ansi-gray"> 3. Example:</span> <span class="ansi-red">http-exploit</span> <span class="ansi-white">host=example.com port=80 time=60 rate=1000 cache=true</span><span class="ansi-gray">.</span>
<span class="ansi-gray"> 4. Press enter to execute.</span>

<span class="ansi-red ansi-bold">TIPS & SHORTCUTS:</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">TAB Autocomplete</span><span class="ansi-gray">: Press TAB to autocomplete commands.</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">Ctrl + C</span><span class="ansi-gray">: Interrupt and exit the current operation.</span>
<span class="ansi-gray"> - Use</span> <span class="ansi-red">methods</span><span class="ansi-gray"> to explore all available options.</span>

<span class="ansi-red ansi-bold">SUPPORT:</span>
<span class="ansi-gray"> - For more help, contact our support team or refer to the user manual.</span>
<span class="ansi-gray"> - Ensure you are using valid credentials to avoid errors.</span>
            `.trim();
        }
    },
    methods: {
        description: 'List all attack methods',
        execute: () => {
            return `
<span class="ansi-gray"> ── Attack Vectors (Private Access) ──</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">c-flood</span>       <span class="ansi-gray">Massive flood attack – 48M RPS, full backend/CDN bypass.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">c-bypass</span>      <span class="ansi-gray">High RPS method with advanced CDN bypass capabilities.</span>

<span class="ansi-gray"> ── Attack Vectors (VIP Access) ──</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">overload</span>      <span class="ansi-gray">HTTP flood attack – 12M RPS, instantly overwhelms backend & CDN.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">rapidreset</span>    <span class="ansi-gray">Session resetter – drops HTTP sessions via TCP RST.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">http-exploit</span>  <span class="ansi-gray">Protocol abuse engine – malformed requests crash parsers.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">spectre</span>       <span class="ansi-gray">JS bypass – simulates browser logic to evade anti-bot defenses.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">h-flood</span>       <span class="ansi-gray">Hybrid HTTP flood – mixed GET/POST/HEAD + HTTP/2 streams.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">ovh</span>           <span class="ansi-gray">OVH bypass – breaks OVH game & VAC firewall stacks.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">udp</span>           <span class="ansi-gray">Raw UDP flood – 1.5M+ PPS for bandwidth saturation.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">tcp</span>           <span class="ansi-gray">SYN/RST flooder – rapidly crashes port handlers.</span>

<span class="ansi-gray"> ── Attack Vectors (Non-VIP Access) ──</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">dns</span>           <span class="ansi-gray">DNS spammer – floods resolvers with random queries.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">browser</span>       <span class="ansi-gray">Basic browser flood – GET/HEAD spoofing with rotating headers.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">floodcore</span>     <span class="ansi-gray">Standard HTTP flood – reliable POST/GET for weak endpoints.</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">!</span><span class="ansi-red">game</span>          <span class="ansi-gray">Game disruptor – injects latency and crashes real-time sessions.</span>

<span class="ansi-gray"> ── Quick Tips ──</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">TAB key</span>     <span class="ansi-gray">= Auto-complete any command</span>
<span class="ansi-gray"> -</span> <span class="ansi-red">Ctrl + C</span> <span class="ansi-gray">= Exit session immediately</span>
<span class="ansi-gray"> - Syntax:</span> <span class="ansi-white">!method host port time rate=32</span>
            `.trim();
        }
    },
    clear: {
        description: 'Clear terminal screen',
        execute: () => {
            return '__CLEAR__';
        }
    },
    myinfo: {
        description: 'Show user account info',
        execute: () => {
            return `
<span class="ansi-red ansi-bold">ACCOUNT INFORMATION:</span>
<span class="ansi-gray"> Username: </span><span class="ansi-white">${Terminal.username}</span>
<span class="ansi-gray"> Status: </span><span class="ansi-green">● Online</span>
<span class="ansi-gray"> Plan: </span><span class="ansi-white">Demo Access</span>
<span class="ansi-gray"> Expiry: </span><span class="ansi-white">5/11/2028</span>
<span class="ansi-gray"> Max Time: </span><span class="ansi-white">60 seconds</span>
<span class="ansi-gray"> Concurrents: </span><span class="ansi-white">1</span>
<span class="ansi-gray"> Cooldown: </span><span class="ansi-white">60 seconds</span>
            `.trim();
        }
    },
    server: {
        description: 'Check server status',
        execute: () => {
            return `
<span class="ansi-red ansi-bold">SERVER STATUS:</span>
<span class="ansi-gray"> L7 Nodes: </span><span class="ansi-green">● Online</span> <span class="ansi-gray">(240 active)</span>
<span class="ansi-gray"> L4 Nodes: </span><span class="ansi-green">● Online</span> <span class="ansi-gray">(180 active)</span>
<span class="ansi-gray"> API Status: </span><span class="ansi-green">● Operational</span>
<span class="ansi-gray"> Uptime: </span><span class="ansi-white">99.98%</span>
<span class="ansi-gray"> Load Average: </span><span class="ansi-white">23%</span>
<span class="ansi-gray"> Last Update: </span><span class="ansi-white">${new Date().toLocaleString()}</span>
            `.trim();
        }
    },
    passwd: {
        description: 'Change password',
        execute: () => {
            return '<span class="ansi-yellow">⚠ This is a demo terminal. Password change unavailable.</span>';
        }
    },
    themes: {
        description: 'Change terminal theme',
        execute: () => {
            return `
<span class="ansi-red ansi-bold">AVAILABLE THEMES:</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">dark</span> <span class="ansi-gray">(current)</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">light</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">matrix</span>
<span class="ansi-gray"> -</span> <span class="ansi-white">ocean</span>
<span class="ansi-gray">Use</span> <span class="ansi-red">theme [name]</span> <span class="ansi-gray">to switch themes.</span>
            `.trim();
        }
    },
    ongoing: {
        description: 'Show ongoing attacks',
        execute: () => {
            return '<span class="ansi-gray">No ongoing operations. Type</span> <span class="ansi-red">methods</span> <span class="ansi-gray">to start.</span>';
        }
    },
    whoami: {
        description: 'Show current user',
        execute: () => {
            return Terminal.username;
        }
    },
    date: {
        description: 'Show current date',
        execute: () => {
            return new Date().toString();
        }
    },
    ls: {
        description: 'List files',
        execute: () => {
            return '<span class="ansi-blue">README.md</span>  <span class="ansi-blue">config.json</span>  <span class="ansi-green">src/</span>  <span class="ansi-green">assets/</span>';
        }
    }
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initTerminal();
});

function initTerminal() {
    Terminal.content = document.getElementById('terminalContent');
    Terminal.inputEl = document.getElementById('terminalInput');
    Terminal.cursorEl = document.getElementById('terminalCursor');
    Terminal.bodyEl = document.getElementById('terminalBody');

    if (!Terminal.content || !Terminal.inputEl || !Terminal.cursorEl) return;

    // Clear and show initial content
    clearTerminal();
    
    // Focus terminal on click
    Terminal.bodyEl.addEventListener('click', () => {
        document.body.focus();
    });

    // Keyboard event listener
    document.addEventListener('keydown', handleKeyInput);

    Terminal.initialized = true;
}

// ============================================
// KEYBOARD HANDLING
// ============================================
function handleKeyInput(e) {
    // Only handle if terminal is visible
    const terminalSection = document.getElementById('terminal');
    if (!terminalSection) return;
    
    const rect = terminalSection.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
    
    if (!isVisible) return;

    // Ignore if user is typing in an input field elsewhere
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

    // Handle special keys
    if (e.key === 'Enter') {
        e.preventDefault();
        executeCommand(Terminal.input);
        Terminal.input = '';
        Terminal.inputEl.textContent = '';
        return;
    }

    if (e.key === 'Backspace') {
        e.preventDefault();
        Terminal.input = Terminal.input.slice(0, -1);
        updateInput();
        return;
    }

    if (e.key === 'ArrowUp') {
        e.preventDefault();
        navigateHistory(-1);
        return;
    }

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        navigateHistory(1);
        return;
    }

    if (e.key === 'Tab') {
        e.preventDefault();
        autocomplete();
        return;
    }

    if (e.key === 'Escape') {
        Terminal.input = '';
        updateInput();
        return;
    }

    // Handle regular character input
    if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
        e.preventDefault();
        Terminal.input += e.key;
        updateInput();
    }
}

function updateInput() {
    Terminal.inputEl.textContent = Terminal.input;
    scrollToBottom();
}

function navigateHistory(direction) {
    if (Terminal.history.length === 0) return;

    Terminal.historyIndex += direction;

    if (Terminal.historyIndex < 0) {
        Terminal.historyIndex = -1;
        Terminal.input = '';
    } else if (Terminal.historyIndex >= Terminal.history.length) {
        Terminal.historyIndex = Terminal.history.length - 1;
    } else {
        Terminal.input = Terminal.history[Terminal.history.length - 1 - Terminal.historyIndex];
    }

    updateInput();
}

function autocomplete() {
    const input = Terminal.input.toLowerCase();
    if (!input) return;

    const matches = Object.keys(COMMANDS).filter(cmd => cmd.startsWith(input));
    
    if (matches.length === 1) {
        Terminal.input = matches[0];
        updateInput();
    } else if (matches.length > 1) {
        // Show possible completions
        addOutput(`<span class="ansi-gray">${matches.join('  ')}</span>`, '');
    }
}

// ============================================
// COMMAND EXECUTION
// ============================================
function executeCommand(input) {
    const trimmed = input.trim();
    
    if (!trimmed) {
        addOutput('', '');
        return;
    }

    // Add to history
    Terminal.history.push(trimmed);
    if (Terminal.history.length > 50) {
        Terminal.history.shift();
    }
    Terminal.historyIndex = -1;

    // Parse command
    const parts = trimmed.split(' ');
    const cmd = parts[0].toLowerCase();
    const args = parts.slice(1);

    // Show the command that was entered
    addOutput('', trimmed);

    // Execute
    if (COMMANDS[cmd]) {
        const result = COMMANDS[cmd].execute();
        
        if (result === '__CLEAR__') {
            clearTerminal();
        } else {
            addOutput(result, null);
        }
    } else {
        addOutput(`<span class="ansi-red">Command not found: ${cmd}</span>. <span class="ansi-gray">Type 'help' for available commands.</span>`, null);
    }
}

// ============================================
// OUTPUT HANDLING
// ============================================
function addOutput(html, command) {
    const line = document.createElement('div');
    line.className = 'terminal-output-line';

    if (command !== null) {
        // This is a command line
        line.innerHTML = `
            <div class="command-line">
                <span class="terminal-prompt">
                    <span class="prompt-user">${Terminal.username}</span>
                    <span class="prompt-separator">●</span>
                    <span class="prompt-host">${Terminal.hostname}</span>
                    <span class="prompt-arrow">►►</span>
                </span>
                <span class="command-text">${escapeHtml(command || '')}</span>
            </div>
        `;
    } else {
        // This is output
        line.innerHTML = `<div class="output-text">${html}</div>`;
    }

    Terminal.content.appendChild(line);
    scrollToBottom();
}

function clearTerminal() {
    Terminal.content.innerHTML = '';
    showAsciiArt();
}

function showAsciiArt() {
    let delay = 0;
    ASCII_LOGO.forEach((line, index) => {
        setTimeout(() => {
            const div = document.createElement('div');
            div.className = 'ascii-line';
            div.innerHTML = line.text;
            Terminal.content.appendChild(div);
            scrollToBottom();
        }, delay);
        delay += line.delay;
    });
}

function scrollToBottom() {
    if (Terminal.bodyEl) {
        Terminal.bodyEl.scrollTop = Terminal.bodyEl.scrollHeight;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// UTILITY FUNCTIONS
// ============================================
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Export for global access
window.Terminal = Terminal;
window.executeCommand = executeCommand;
window.clearTerminal = clearTerminal;
