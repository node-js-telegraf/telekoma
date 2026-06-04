/* ============================================
   KILLBYTE PREMIUM - TERMINAL STYLES
   Clean Reddish macOS Terminal Design
   ============================================ */

/* Terminal Section */
.terminal-section {
    padding: 100px 40px;
    position: relative;
    z-index: 10;
}

.terminal-wrapper {
    max-width: 950px;
    margin: 0 auto;
}

/* macOS Terminal - Clean Reddish Design */
.mac-terminal {
    background: #1a1a1a;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 
        0 30px 80px -20px rgba(0, 0, 0, 0.9),
        0 0 0 1px rgba(255, 255, 255, 0.05),
        0 0 80px rgba(255, 51, 51, 0.1);
    font-family: 'JetBrains Mono', 'SF Mono', Monaco, 'Courier New', monospace;
    position: relative;
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.mac-terminal:hover {
    box-shadow: 
        0 40px 100px -20px rgba(0, 0, 0, 0.95),
        0 0 0 1px rgba(255, 51, 51, 0.2),
        0 0 100px rgba(255, 51, 51, 0.15);
}

.mac-terminal::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(255, 51, 51, 0.03) 0%,
        transparent 15%
    );
    pointer-events: none;
    z-index: 1;
}

/* Terminal Header */
.terminal-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: linear-gradient(180deg, #2d2d2d 0%, #252525 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.4);
    position: relative;
}

.terminal-buttons {
    display: flex;
    gap: 10px;
}

.terminal-btn {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.terminal-btn.close {
    background: #ff5f57;
    border: 1px solid #e0443e;
}

.terminal-btn.close:hover {
    background: #ff3b30;
    transform: scale(1.1);
}

.terminal-btn.minimize {
    background: #febc2e;
    border: 1px solid #dea123;
}

.terminal-btn.minimize:hover {
    background: #ff9500;
    transform: scale(1.1);
}


.terminal-btn.maximize {
    background: #28c840;
    border: 1px solid #1aab29;
}

.terminal-btn.maximize:hover {
    background: #28cd41;
    transform: scale(1.1);
}

/* Hover icons for buttons */
.terminal-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.2s ease;
    font-size: 10px;
    font-weight: bold;
}

.terminal-btn.close:hover::after {
    content: '×';
    color: #4d0000;
    opacity: 1;
}

.terminal-btn.minimize:hover::after {
    content: '−';
    color: #965100;
    opacity: 1;
}

.terminal-btn.maximize:hover::after {
    content: '+';
    color: #006500;
    opacity: 1;
}

.terminal-title {
    flex: 1;
    text-align: center;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.5);
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-family: 'Inter', -apple-system, sans-serif;
    letter-spacing: 0.5px;
}

.terminal-title i {
    font-size: 12px;
    opacity: 0.6;
}

.terminal-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}

.demo-badge {
    padding: 6px 14px;
    background: rgba(255, 51, 51, 0.15);
    color: #ff4444;
    border: 1px solid rgba(255, 68, 68, 0.3);
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    animation: demoPulse 2s ease-in-out infinite;
}

@keyframes demoPulse {
    0%, 100% { 
        background: rgba(255, 51, 51, 0.15);
        box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.2);
    }
    50% { 
        background: rgba(255, 51, 51, 0.25);
        box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
    }
}

/* Terminal Body */
.terminal-body {
    padding: 28px 32px;
    font-size: 15px;
    line-height: 1.8;
    min-height: 420px;
    max-height: 520px;
    background: #1a1a1a;
    position: relative;
    overflow-y: auto;
    color: #e0e0e0;
}

/* Custom Scrollbar */
.terminal-body::-webkit-scrollbar {
    width: 12px;
}

.terminal-body::-webkit-scrollbar-track {
    background: #1a1a1a;
}

.terminal-body::-webkit-scrollbar-thumb {
    background: #3a3a3a;
    border-radius: 6px;
    border: 3px solid #1a1a1a;
}

.terminal-body::-webkit-scrollbar-thumb:hover {
    background: #4a4a4a;
}

/* Terminal Content */
.terminal-content {
    margin-bottom: 20px;
}

/* ASCII Art Lines - Clean Design */
.ascii-line {
    font-family: 'JetBrains Mono', monospace;
    font-size: 14px;
    line-height: 1.6;
    white-space: pre;
    color: #e0e0e0;
    margin-bottom: 2px;
}

/* Output Lines */
.terminal-output-line {
    margin-bottom: 6px;
    animation: lineAppear 0.2s ease-out;
}

@keyframes lineAppear {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.command-line {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.output-text {
    white-space: pre-wrap;
    word-break: break-word;
    padding-left: 0;
    line-height: 1.9;
    color: #c0c0c0;
}

/* Input Line */
.terminal-input-line {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
    animation: inputAppear 0.3s ease-out;
}

@keyframes inputAppear {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Custom Prompt Styling - Like Image */
.terminal-prompt {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.prompt-user {
    background: #7c0a02;
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.5px;
}

.prompt-separator {
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
}

.prompt-host {
    color: #ffffff;
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 0.5px;
}

.prompt-arrow {
    color: #ff3333;
    font-weight: bold;
    font-size: 14px;
    margin-left: 6px;
    letter-spacing: -2px;
}

/* Input and Cursor */
.terminal-input {
    color: #ffffff;
    min-width: 10px;
    outline: none;
    word-break: break-all;
    font-weight: 500;
}

.terminal-cursor {
    display: inline-block;
    width: 10px;
    height: 20px;
    background: #ffffff;
    animation: cursor-blink 1.1s step-end infinite;
    vertical-align: middle;
    margin-left: 3px;
    border-radius: 1px;
}

@keyframes cursor-blink {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0;
    }
}

/* ANSI Color Classes */
.ansi-red {
    color: #ff4444 !important;
}

.ansi-red-dark {
    color: #cc0000 !important;
}

.ansi-green {
    color: #4ade80 !important;
}

.ansi-yellow {
    color: #fbbf24 !important;
}

.ansi-blue {
    color: #60a5fa !important;
}

.ansi-magenta {
    color: #c084fc !important;
}

.ansi-cyan {
    color: #22d3ee !important;
}

.ansi-white {
    color: #ffffff !important;
}

.ansi-gray {
    color: #9ca3af !important;
}

.ansi-bold {
    font-weight: 700 !important;
}

/* Command Text */
.command-text {
    color: #ffffff;
    font-weight: 500;
}

/* Terminal Hint */
.terminal-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin-top: 28px;
    padding: 16px 28px;
    background: rgba(255, 51, 51, 0.05);
    border: 1px solid rgba(255, 51, 51, 0.15);
    border-radius: 12px;
    font-size: 14px;
    color: #9ca3af;
    backdrop-filter: blur(10px);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.terminal-hint:hover {
    background: rgba(255, 51, 51, 0.08);
    border-color: rgba(255, 51, 51, 0.25);
}

.terminal-hint i {
    color: #ff4444;
    font-size: 16px;
}

.terminal-hint code {
    background: rgba(255, 255, 255, 0.08);
    padding: 4px 10px;
    border-radius: 6px;
    color: #ff4444;
    font-family: 'JetBrains Mono', monospace;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.terminal-hint code:hover {
    background: rgba(255, 68, 68, 0.15);
}

/* Selection */
.terminal-body ::selection {
    background: rgba(255, 68, 68, 0.3);
    color: #ffffff;
}

/* Animation for new lines */
@keyframes lineSlideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Focus State */
.mac-terminal:focus-within {
    box-shadow: 
        0 40px 100px -20px rgba(0, 0, 0, 0.95),
        0 0 0 1px rgba(255, 68, 68, 0.3),
        0 0 100px rgba(255, 68, 68, 0.15);
}

/* Responsive Terminal */
@media (max-width: 768px) {
    .terminal-section {
        padding: 60px 24px;
    }
    
    .terminal-body {
        padding: 20px 24px;
        font-size: 13px;
        min-height: 350px;
    }
    
    .terminal-header {
        padding: 14px 18px;
    }
    
    .terminal-title {
        font-size: 12px;
    }
    
    .ascii-line {
        font-size: 11px;
    }
    
    .prompt-user,
    .prompt-host,
    .prompt-arrow {
        font-size: 12px;
    }
    
    .prompt-user {
        padding: 3px 8px;
    }
    
    .terminal-cursor {
        height: 16px;
        width: 8px;
    }
    
    .demo-badge {
        font-size: 9px;
        padding: 4px 10px;
    }
    
    .terminal-hint {
        padding: 14px 20px;
        font-size: 12px;
        flex-wrap: wrap;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .terminal-body {
        font-size: 12px;
        min-height: 300px;
    }
    
    .ascii-line {
        font-size: 9px;
    }
    
    .terminal-prompt {
        gap: 6px;
    }
    
    .prompt-user {
        padding: 2px 6px;
        font-size: 11px;
    }
    
    .prompt-host {
        font-size: 11px;
    }
    
    .prompt-arrow {
        font-size: 11px;
        margin-left: 3px;
    }
}
