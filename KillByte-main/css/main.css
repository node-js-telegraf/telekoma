/* ============================================
   KILLBYTE PREMIUM - MAIN STYLES
   Ultra Premium Monochrome Design
   ============================================ */

:root {
    --bg-primary: #0a0a0a;
    --bg-secondary: #111111;
    --bg-tertiary: #161616;
    --bg-card: #1a1a1a;
    --bg-elevated: #1e1e1e;
    
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-tertiary: #666666;
    --text-muted: #444444;
    
    --accent-red: #ff3333;
    --accent-red-dark: #cc0000;
    --accent-red-glow: rgba(255, 51, 51, 0.4);
    
    --border: #222222;
    --border-light: #2a2a2a;
    --border-hover: #333333;
    
    --gradient-primary: linear-gradient(135deg, #ffffff 0%, #c0c0c0 50%, #ffffff 100%);
    --gradient-text: linear-gradient(135deg, #ffffff 0%, #888888 100%);
    --gradient-red: linear-gradient(135deg, #ff3333 0%, #cc0000 100%);
    
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.5);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.6);
    --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.7);
    --shadow-xl: 0 24px 64px rgba(0, 0, 0, 0.8);
    --shadow-glow: 0 0 40px rgba(255, 51, 51, 0.2);
    --shadow-red: 0 0 60px rgba(255, 51, 51, 0.3);
    
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-mono: 'JetBrains Mono', 'SF Mono', Monaco, monospace;
    
    --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
    --ease-out-back: cubic-bezier(0.34, 1.56, 0.64, 1);
    --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
}

*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
    font-size: 16px;
}

body {
    font-family: var(--font-primary);
    background: var(--bg-primary);
    color: var(--text-primary);
    line-height: 1.6;
    overflow-x: hidden;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

::selection {
    background: rgba(255, 51, 51, 0.3);
    color: var(--text-primary);
}

::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-secondary);
}

::-webkit-scrollbar-thumb {
    background: var(--text-muted);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--text-tertiary);
}

/* ============================================
   PRELOADER - PREMIUM
   ============================================ */
.preloader {
    position: fixed;
    inset: 0;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    transition: opacity 0.8s var(--ease-out-expo), visibility 0.8s;
}

.preloader.hidden {
    opacity: 0;
    visibility: hidden;
}

.preloader-content {
    text-align: center;
    transform: scale(1);
    transition: transform 0.5s var(--ease-out-expo);
}

.preloader.hidden .preloader-content {
    transform: scale(0.9);
}

.preloader-logo {
    width: 100px;
    height: 100px;
    background: var(--gradient-red);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    position: relative;
    animation: logoPulse 2s ease-in-out infinite;
    box-shadow: var(--shadow-red);
}

.preloader-logo::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 28px;
    background: linear-gradient(135deg, var(--accent-red), transparent, var(--accent-red));
    z-index: -1;
    animation: rotate 4s linear infinite;
    opacity: 0.5;
}

.preloader-logo i {
    font-size: 44px;
    color: white;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes logoPulse {
    0%, 100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 51, 51, 0.4), 0 0 60px rgba(255, 51, 51, 0.3);
    }
    50% { 
        transform: scale(1.05);
        box-shadow: 0 0 0 20px rgba(255, 51, 51, 0), 0 0 80px rgba(255, 51, 51, 0.5);
    }
}

.preloader-text {
    font-size: 36px;
    font-weight: 900;
    letter-spacing: 8px;
    margin-bottom: 40px;
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: textGlow 2s ease-in-out infinite;
}

@keyframes textGlow {
    0%, 100% { filter: brightness(1); }
    50% { filter: brightness(1.2); }
}

.preloader-bar {
    width: 280px;
    height: 3px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
    margin: 0 auto 20px;
    position: relative;
}

.preloader-progress {
    height: 100%;
    background: var(--gradient-red);
    width: 0;
    animation: loadProgress 2.5s var(--ease-out-expo) forwards;
    position: relative;
}

.preloader-progress::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 40px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: progressShine 1s ease-in-out infinite;
}

@keyframes loadProgress {
    0% { width: 0; }
    100% { width: 100%; }
}

@keyframes progressShine {
    0% { transform: translateX(-40px); opacity: 0; }
    50% { opacity: 1; }
    100% { transform: translateX(40px); opacity: 0; }
}

.preloader-status {
    font-size: 12px;
    color: var(--text-tertiary);
    letter-spacing: 3px;
    text-transform: uppercase;
    animation: statusBlink 1.5s ease-in-out infinite;
}

@keyframes statusBlink {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

/* ============================================
   BACKGROUND EFFECTS - PREMIUM
   ============================================ */
.bg-canvas {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
}

.ambient-glow {
    position: fixed;
    border-radius: 50%;
    filter: blur(200px);
    pointer-events: none;
    z-index: 1;
    opacity: 0.12;
}

.glow-1 {
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, var(--accent-red), transparent 70%);
    top: -300px;
    right: -300px;
    animation: floatGlow 25s ease-in-out infinite;
}

.glow-2 {
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, #444, transparent 70%);
    bottom: -200px;
    left: -200px;
    animation: floatGlow 30s ease-in-out infinite reverse;
}

.glow-3 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, #333, transparent 70%);
    top: 50%;
    left: 30%;
    animation: floatGlow 20s ease-in-out infinite;
    animation-delay: -8s;
}

@keyframes floatGlow {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.12; }
    25% { transform: translate(50px, -30px) scale(1.1); opacity: 0.15; }
    50% { transform: translate(-30px, 40px) scale(0.95); opacity: 0.1; }
    75% { transform: translate(40px, 50px) scale(1.05); opacity: 0.14; }
}

.grid-overlay {
    position: fixed;
    inset: 0;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
    background-size: 80px 80px;
    pointer-events: none;
    z-index: 1;
}

.noise-overlay {
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    opacity: 0.015;
    pointer-events: none;
    z-index: 2;
}

/* ============================================
   NAVBAR - PREMIUM (FIXED)
   ============================================ */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 24px 40px;
    transition: all 0.5s var(--ease-out-expo);
}

.navbar.scrolled {
    background: rgba(10, 10, 10, 0.9);
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border-bottom: 1px solid var(--border);
    padding: 16px 40px;
}

.nav-container {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.brand {
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: var(--text-primary);
    transition: transform 0.3s var(--ease-out-expo);
    flex-shrink: 0;
}

.brand:hover {
    transform: scale(1.02);
}

.brand-icon {
    width: auto;
    height: auto;
    background: none;
    background-color: transparent;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s var(--ease-out-expo);
    box-shadow: none;
}

.brand:hover .brand-icon {
    transform: scale(1.1);
    box-shadow: none;
}

.brand-icon img {
    display: block;
    width: auto;
    height: 60px;
    max-width: 240px;
    object-fit: contain;
}

.brand-text {
    display: flex;
    flex-direction: column;
}

.brand-name {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.5px;
    line-height: 1.1;
}

.brand-tag {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 3px;
    color: var(--text-tertiary);
    text-transform: uppercase;
}

/* Nav Links - Premium */
.nav-links {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.02);
    padding: 6px;
    border-radius: 100px;
    border: 1px solid var(--border);
    backdrop-filter: blur(10px);
}

/* PC and Large Laptop - Center Fix */
@media screen and (min-width: 1200px) {
    .nav-links {
        flex: 1;
        justify-content: center;
        margin: 0 40px;
    }
}

@media screen and (min-width: 1024px) and (max-width: 1199px) {
    .nav-links {
        flex: 1;
        justify-content: center;
        margin: 0 30px;
    }
}

@media screen and (min-width: 769px) and (max-width: 1023px) {
    .nav-links {
        flex: 1;
        justify-content: center;
        margin: 0 20px;
    }
}

.nav-link {
    position: relative;
    padding: 14px 24px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    border-radius: 100px;
    transition: all 0.4s var(--ease-out-expo);
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-primary);
    opacity: 0;
    transition: opacity 0.4s var(--ease-out-expo);
    border-radius: 100px;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 50%;
    width: 0;
    height: 2px;
    background: var(--accent-red);
    transition: all 0.4s var(--ease-out-expo);
    transform: translateX(-50%);
}

.nav-link-text {
    position: relative;
    z-index: 1;
    transition: color 0.4s var(--ease-out-expo);
}

.nav-link:hover,
.nav-link.active {
    color: var(--bg-primary);
}

.nav-link:hover::before,
.nav-link.active::before {
    opacity: 1;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 60%;
}

/* Language Selector - Premium */
.language-selector {
    position: relative;
}

.lang-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--border);
    border-radius: 100px;
    color: var(--text-secondary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.4s var(--ease-out-expo);
}

.lang-btn:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-red);
    color: var(--text-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(255, 51, 51, 0.15);
}

.lang-flag {
    width: 20px;
    height: 15px;
    border-radius: 3px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.lang-btn i {
    font-size: 10px;
    transition: transform 0.4s var(--ease-out-expo);
}

.language-selector.active .lang-btn i {
    transform: rotate(180deg);
}

.lang-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 10px;
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-15px) scale(0.95);
    transition: all 0.4s var(--ease-out-expo);
    box-shadow: var(--shadow-lg);
    z-index: 100;
    backdrop-filter: blur(20px);
}

.language-selector.active .lang-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.lang-option {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 14px;
    background: none;
    border: none;
    border-radius: 10px;
    color: var(--text-secondary);
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s var(--ease-out-expo);
    text-align: left;
}

.lang-option:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    transform: translateX(5px);
}

.lang-option.active {
    background: rgba(255, 51, 51, 0.15);
    color: var(--accent-red);
}

.lang-option img {
    width: 20px;
    height: 15px;
    border-radius: 3px;
    object-fit: cover;
}

/* Nav Actions */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-shrink: 0;
}

/* Premium Button - Support */
.nav-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: transparent;
    color: var(--text-primary);
    border: 2px solid var(--accent-red);
    border-radius: 100px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.nav-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-red);
    transform: translateX(-100%);
    transition: transform 0.5s var(--ease-out-expo);
}

.nav-btn:hover::before {
    transform: translateX(0);
}

.nav-btn span,
.nav-btn i {
    position: relative;
    z-index: 1;
    transition: all 0.3s var(--ease-out-expo);
}

.nav-btn:hover {
    border-color: transparent;
    box-shadow: 0 8px 30px rgba(255, 51, 51, 0.4);
    transform: translateY(-3px);
}

.nav-btn:hover i {
    transform: rotate(15deg) scale(1.2);
}

/* Menu Toggle */
.menu-toggle {
    display: none;
    flex-direction: column;
    gap: 6px;
    padding: 10px;
    background: none;
    border: none;
    cursor: pointer;
}

.menu-toggle span {
    width: 28px;
    height: 2px;
    background: var(--text-primary);
    transition: all 0.4s var(--ease-out-expo);
    transform-origin: center;
}

.menu-toggle:hover span:nth-child(1) {
    transform: translateY(-2px);
}

.menu-toggle:hover span:nth-child(3) {
    transform: translateY(2px);
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    inset: 0;
    background: rgba(10, 10, 10, 0.98);
    backdrop-filter: blur(40px);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.6s var(--ease-out-expo);
}

.mobile-menu.active {
    opacity: 1;
    visibility: visible;
}

.mobile-menu-links {
    display: flex;
    flex-direction: column;
    gap: 32px;
    text-align: center;
}

.mobile-link {
    font-size: 42px;
    font-weight: 700;
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.4s var(--ease-out-expo);
    position: relative;
    display: inline-block;
}

.mobile-link::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    width: 0;
    height: 3px;
    background: var(--accent-red);
    transition: all 0.4s var(--ease-out-expo);
    transform: translateX(-50%);
}

.mobile-link:hover {
    color: var(--accent-red);
    transform: scale(1.05);
}

.mobile-link:hover::after {
    width: 100%;
}

/* ============================================
   RESPONSIVE FIXES - MOBILE LANGUAGE SELECTOR
   ============================================ */
@media (max-width: 1200px) {
    .nav-links {
        display: none;
    }
    
    .menu-toggle {
        display: flex;
    }
    
    /* Mobile e language selector show korbe */
    .language-selector {
        display: block !important;
    }
}

@media (max-width: 768px) {
    .navbar {
        padding: 16px 24px;
    }
    
    .brand-icon img {
        height: 45px;
    }
    
    .brand-name {
        font-size: 20px;
    }
    
    .brand-tag {
        font-size: 8px;
        letter-spacing: 2px;
    }
    
    /* Language selector mobile e thik thakbe */
    .language-selector {
        display: block !important;
    }
    
    .lang-btn {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .lang-btn span {
        display: none; /* Shudhu icon show korbe */
    }
    
    .lang-btn i {
        font-size: 12px;
    }
    
    .nav-btn {
        padding: 8px 16px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .brand-icon img {
        height: 40px;
    }
    
    .brand-name {
        font-size: 18px;
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .brand-tag {
        display: none;
    }
    
    .lang-btn {
        padding: 6px 10px;
    }
    
    .lang-btn span {
        display: none;
    }
    
    .nav-btn span {
        display: none; /* Support button e shudhu icon */
    }
    
    .nav-btn i {
        font-size: 14px;
    }
}
/* ============================================
   HERO SECTION - PREMIUM WITH STATS BOXES (FIXED)
   ============================================ */

.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 160px 40px 100px;
    position: relative;
    z-index: 10;
}

.hero-content {
    text-align: center;
    max-width: 1100px;
    margin: 0 auto;
}

/* Badge */

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 12px 24px;
    background: rgba(255, 51, 51, 0.1);
    border: 1px solid rgba(255, 51, 51, 0.3);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 600;
    color: var(--accent-red);
    margin-bottom: 48px;
    animation: badgeFloat 4s ease-in-out infinite;
    backdrop-filter: blur(10px);
}

.hero-badge i {
    color: var(--accent-red);
    animation: crownPulse 2s ease-in-out infinite;
}

@keyframes crownPulse {
    0%,100%{transform:scale(1);}
    50%{transform:scale(1.2);}
}

@keyframes badgeFloat {
    0%,100%{transform:translateY(0);}
    50%{transform:translateY(-8px);}
}

/* Title */

.hero-title{
    font-size: clamp(56px, 10vw, 96px);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: -1px;   /* FIXED */
    margin-bottom: 32px;
}

.title-line{
    display:block;
    transition:all .6s var(--ease-out-expo);
}

.title-line:first-child{
    animation:titleSlideIn 1s var(--ease-out-expo) .3s both;
}

.title-line.gradient{
    background:var(--gradient-text);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
    animation:titleSlideIn 1s var(--ease-out-expo) .5s both;
}

@keyframes titleSlideIn{
    from{
        opacity:0;
        transform:translateY(40px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* Description */

.hero-description{
    font-size:20px;
    color:var(--text-secondary);
    max-width:650px;
    margin:0 auto 48px;
    line-height:1.8;
    animation:fadeInUp 1s var(--ease-out-expo) .7s both;
}

.hero-description .highlight{
    color:var(--accent-red);
    font-weight:600;
}

/* Buttons */

.hero-actions{
    display:flex;
    gap:24px;
    justify-content:center;
    margin-bottom:80px;
    flex-wrap:wrap;
    animation:fadeInUp 1s var(--ease-out-expo) .9s both;
}

.btn{
    display:inline-flex;
    align-items:center;
    gap:12px;
    padding:18px 40px;
    font-size:16px;
    font-weight:600;
    border-radius:100px;
    cursor:pointer;
    transition:all .5s var(--ease-out-expo);
    text-decoration:none;
    border:none;
    position:relative;
    overflow:hidden;
}

/* Primary */

.btn-primary{
    background:var(--gradient-red);
    color:white;
    box-shadow:0 8px 30px rgba(255,51,51,.4);
}

.btn-primary::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,#ff5555,#ff3333,#cc0000);
    opacity:0;
    transition:opacity .5s var(--ease-out-expo);
}

.btn-primary::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),transparent);
    transition:left .8s var(--ease-out-expo);
}

.btn-primary:hover{
    transform:translateY(-5px) scale(1.02);
    box-shadow:0 16px 50px rgba(255,51,51,.6);
}

.btn-primary:hover::before{
    opacity:1;
}

.btn-primary:hover::after{
    left:100%;
}

.btn-primary span,
.btn-primary i{
    position:relative;
    z-index:1;
}

.btn-primary:hover i{
    transform:translateX(8px);
}

/* Secondary */

.btn-secondary{
    background:transparent;
    color:var(--text-primary);
    border:2px solid var(--border-light);
}

.btn-secondary::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(255,255,255,.05);
    transform:scaleX(0);
    transform-origin:left;
    transition:transform .5s var(--ease-out-expo);
}

.btn-secondary::after{
    content:"";
    position:absolute;
    inset:-2px;
    border-radius:100px;
    background:linear-gradient(135deg,var(--accent-red),transparent,var(--accent-red));
    opacity:0;
    z-index:-1;
    transition:opacity .5s var(--ease-out-expo);
}

.btn-secondary:hover{
    border-color:var(--accent-red);
    transform:translateY(-5px) scale(1.02);
    box-shadow:0 8px 30px rgba(255,51,51,.2);
}

.btn-secondary:hover::before{
    transform:scaleX(1);
}

.btn-secondary:hover::after{
    opacity:1;
}

.btn-secondary i{
    transition:all .4s var(--ease-out-expo);
}

.btn-secondary:hover i{
    transform:rotate(15deg) scale(1.2);
    color:var(--accent-red);
}

/* ============================================
   HERO STATS - PREMIUM BOXES WITH COUNTER
   ============================================ */
.hero-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    max-width: 1000px;
    margin: 0 auto;
    animation: fadeInUp 1s var(--ease-out-expo) 1.1s both;
}

.hero-stat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px 24px;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.hero-stat::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.1), transparent);
    opacity: 0;
    transition: opacity 0.5s var(--ease-out-expo);
}

.hero-stat::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-red);
    transform: scaleX(0);
    transition: transform 0.5s var(--ease-out-expo);
}

.hero-stat:hover {
    transform: translateY(-10px) scale(1.02);
    border-color: rgba(255, 51, 51, 0.3);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 40px rgba(255, 51, 51, 0.1);
}

.hero-stat:hover::before {
    opacity: 1;
}

.hero-stat:hover::after {
    transform: scaleX(1);
}

.stat-number {
    font-size: 52px;
    font-weight: 800;
    line-height: 1;
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
    font-family: var(--font-mono);
    position: relative;
    z-index: 1;
}

.stat-suffix {
    font-size: 28px;
    font-weight: 700;
    color: var(--accent-red);
}

.stat-label {
    font-size: 13px;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-top: 12px;
    position: relative;
    z-index: 1;
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    color: var(--text-muted);
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    animation: fadeInUp 1s var(--ease-out-expo) 1.3s both;
}

.scroll-mouse {
    width: 28px;
    height: 44px;
    border: 2px solid var(--text-muted);
    border-radius: 14px;
    display: flex;
    justify-content: center;
    padding-top: 10px;
    transition: all 0.4s var(--ease-out-expo);
}

.scroll-indicator:hover .scroll-mouse {
    border-color: var(--accent-red);
    transform: scale(1.1);
}

.scroll-wheel {
    width: 4px;
    height: 10px;
    background: var(--accent-red);
    border-radius: 2px;
    animation: scrollWheel 2s ease-in-out infinite;
}

@keyframes scrollWheel {
    0%, 100% { transform: translateY(0); opacity: 1; }
    50% { transform: translateY(12px); opacity: 0.3; }
}

/* ============================================
   SECTION COMMON STYLES
   ============================================ */
section {
    padding: 140px 40px;
    position: relative;
    z-index: 10;
}

.section-header {
    text-align: center;
    margin-bottom: 80px;
}

.section-label {
    display: inline-block;
    padding: 10px 24px;
    background: rgba(255, 51, 51, 0.1);
    border: 1px solid rgba(255, 51, 51, 0.2);
    border-radius: 100px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: var(--accent-red);
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
}

.section-title {
    font-size: clamp(42px, 6vw, 64px);
    font-weight: 800;
    letter-spacing: -2px;
    margin-bottom: 20px;
}

.section-title .gradient {
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 20px;
    color: var(--text-tertiary);
    max-width: 550px;
    margin: 0 auto;
}

/* ============================================
   ABOUT SECTION
   ============================================ */
.about-section {
    background: var(--bg-secondary);
}

.about-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 100px;
    align-items: center;
}

.about-content .section-label,
.about-content .section-title {
    text-align: left;
}

.about-text {
    font-size: 18px;
    color: var(--text-secondary);
    line-height: 2;
    margin-bottom: 24px;
}

.about-text strong,
.about-text .highlight {
    color: var(--accent-red);
    font-weight: 600;
}

.about-features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 40px;
}

.about-feature {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 22px;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border);
    border-radius: 16px;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.5s var(--ease-out-expo);
}

.about-feature:hover {
    background: rgba(255, 51, 51, 0.05);
    border-color: rgba(255, 51, 51, 0.3);
    transform: translateX(10px);
}

.about-feature i {
    color: var(--accent-red);
    font-size: 18px;
    transition: transform 0.4s var(--ease-out-expo);
}

.about-feature:hover i {
    transform: scale(1.2) rotate(10deg);
}

/* Visual Card */
.visual-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 32px;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.visual-card:hover {
    border-color: rgba(255, 51, 51, 0.2);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    transform: translateY(-10px);
}

.visual-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 24px 28px;
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid var(--border);
    font-size: 15px;
    font-weight: 600;
}

.visual-header i {
    color: var(--accent-red);
    font-size: 18px;
}

.visual-content {
    padding: 28px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.status-item:last-child {
    margin-bottom: 0;
}

.status-name {
    width: 120px;
    font-size: 14px;
    color: var(--text-secondary);
}

.status-bar {
    flex: 1;
    height: 8px;
    background: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.status-fill {
    height: 100%;
    background: var(--gradient-red);
    border-radius: 4px;
    transition: width 1.5s var(--ease-out-expo);
    position: relative;
    overflow: hidden;
}

.status-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: barShine 2s ease-in-out infinite;
}

@keyframes barShine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.status-fill.low {
    background: linear-gradient(90deg, #00ff88, #00cc6a);
}

.status-value {
    width: 60px;
    text-align: right;
    font-size: 14px;
    font-weight: 700;
    font-family: var(--font-mono);
    color: var(--text-primary);
}

/* ============================================
   TIERS SECTION - PREMIUM
   ============================================ */
.tiers-section {
    background: var(--bg-primary);
}

.tier-tabs {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 60px;
}

.tier-tab {
    padding: 16px 40px;
    background: rgba(255, 255, 255, 0.02);
    border: 2px solid var(--border);
    border-radius: 100px;
    color: var(--text-secondary);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.5s var(--ease-out-expo);
    position: relative;
    overflow: hidden;
}

.tier-tab::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-red);
    opacity: 0;
    transition: opacity 0.5s var(--ease-out-expo);
}

.tier-tab span {
    position: relative;
    z-index: 1;
}

.tier-tab:hover {
    border-color: var(--accent-red);
    color: var(--text-primary);
    transform: translateY(-3px);
}

.tier-tab.active {
    background: var(--gradient-red);
    color: white;
    border-color: transparent;
    box-shadow: 0 10px 40px rgba(255, 51, 51, 0.4);
}

.tier-contents {
    max-width: 500px;
    margin: 0 auto;
}

.tier-content {
    display: none;
}

.tier-content.active {
    display: block;
    animation: tierFadeIn 0.6s var(--ease-out-expo);
}

@keyframes tierFadeIn {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Premium Tier Card */
.tier-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 40px;
    padding: 60px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.6s var(--ease-out-expo);
}

.tier-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.05), transparent 50%);
    opacity: 0;
    transition: opacity 0.6s var(--ease-out-expo);
}

.tier-card:hover {
    border-color: rgba(255, 51, 51, 0.3);
    transform: translateY(-15px) scale(1.02);
    box-shadow: 
        0 40px 80px rgba(0, 0, 0, 0.5),
        0 0 60px rgba(255, 51, 51, 0.1);
}

.tier-card:hover::before {
    opacity: 1;
}

.tier-card.featured {
    border-color: rgba(255, 51, 51, 0.3);
    background: linear-gradient(135deg, var(--bg-card), rgba(255, 51, 51, 0.03));
}

.tier-badge {
    position: absolute;
    top: 24px;
    right: 24px;
    padding: 10px 20px;
    background: var(--gradient-red);
    color: white;
    font-size: 11px;
    font-weight: 800;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 4px 20px rgba(255, 51, 51, 0.4);
    animation: badgePulse 2s ease-in-out infinite;
}

@keyframes badgePulse {
    0%, 100% { box-shadow: 0 4px 20px rgba(255, 51, 51, 0.4); }
    50% { box-shadow: 0 4px 30px rgba(255, 51, 51, 0.6); }
}

.tier-icon {
    width: 100px;
    height: 100px;
    background: var(--gradient-red);
    border-radius: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: white;
    margin: 0 auto 32px;
    position: relative;
    transition: all 0.5s var(--ease-out-expo);
    box-shadow: 0 10px 40px rgba(255, 51, 51, 0.3);
}

.tier-card:hover .tier-icon {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 15px 50px rgba(255, 51, 51, 0.5);
}

.tier-name {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
}

.tier-desc {
    color: var(--text-tertiary);
    margin-bottom: 40px;
    font-size: 16px;
    position: relative;
    z-index: 1;
}

.tier-specs {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 40px;
    position: relative;
    z-index: 1;
}

.tier-spec {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 14px;
    font-size: 16px;
    color: var(--text-secondary);
    transition: all 0.4s var(--ease-out-expo);
}

.tier-spec:hover {
    background: rgba(255, 51, 51, 0.05);
    transform: translateX(10px);
}

.tier-spec i {
    color: #00ff88;
    font-size: 16px;
    transition: transform 0.4s var(--ease-out-expo);
}

.tier-spec:hover i {
    transform: scale(1.3);
}

.tier-spec strong {
    color: var(--text-primary);
}

.tier-price {
    font-size: 64px;
    font-weight: 900;
    margin-bottom: 32px;
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    z-index: 1;
}

/* ============================================
   PLANS SECTION - PREMIUM WITH REFLECTION
   ============================================ */
.plans-section {
    background: var(--bg-secondary);
}

.plans-category {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 32px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 32px;
    margin-bottom: 64px;
}

.plans-grid.daily {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

/* Premium Plan Card with Reflection */
.plan-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 32px;
    padding: 36px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.6s var(--ease-out-expo);
    transform-style: preserve-3d;
}

.plan-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.08), transparent 60%);
    opacity: 0;
    transition: opacity 0.6s var(--ease-out-expo);
}

.plan-card::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent 40%,
        rgba(255, 255, 255, 0.03) 50%,
        transparent 60%
    );
    transform: rotate(45deg) translateY(-100%);
    transition: transform 0.8s var(--ease-out-expo);
    pointer-events: none;
}

.plan-card:hover {
    border-color: rgba(255, 51, 51, 0.4);
    transform: translateY(-15px) scale(1.02);
    box-shadow: 
        0 40px 80px rgba(0, 0, 0, 0.5),
        0 0 80px rgba(255, 51, 51, 0.15);
}

.plan-card:hover::before {
    opacity: 1;
}

.plan-card:hover::after {
    transform: rotate(45deg) translateY(100%);
}

.plan-card.featured {
    border-color: rgba(255, 51, 51, 0.3);
    position: relative;
}

.plan-card.featured::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-red);
    opacity: 1;
}

.plan-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
}

.plan-name {
    font-size: 24px;
    font-weight: 800;
    transition: color 0.4s var(--ease-out-expo);
}

.plan-card:hover .plan-name {
    color: var(--accent-red);
}

.plan-badge {
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--text-secondary);
    transition: all 0.4s var(--ease-out-expo);
}

.plan-card:hover .plan-badge {
    background: rgba(255, 51, 51, 0.1);
    border-color: rgba(255, 51, 51, 0.3);
    color: var(--accent-red);
}

.plan-badge.vip {
    background: rgba(255, 51, 51, 0.15);
    color: var(--accent-red);
    border-color: rgba(255, 51, 51, 0.3);
}

.plan-price {
    font-size: 52px;
    font-weight: 900;
    margin-bottom: 28px;
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    z-index: 1;
    font-family: var(--font-mono);
}

.plan-specs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 20px;
    background: var(--bg-tertiary);
    border-radius: 20px;
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
    transition: all 0.4s var(--ease-out-expo);
}

.plan-card:hover .plan-specs {
    background: rgba(255, 51, 51, 0.05);
}

.spec-label {
    font-size: 11px;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 6px;
}

.spec-value {
    font-size: 17px;
    font-weight: 700;
    transition: color 0.4s var(--ease-out-expo);
}

.plan-card:hover .spec-value {
    color: var(--accent-red);
}

.plan-btn {
    width: 100%;
    padding: 18px;
    background: transparent;
    border: 2px solid var(--border-light);
    border-radius: 16px;
    color: var(--text-primary);
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
    z-index: 1;
}

.plan-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-red);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s var(--ease-out-expo);
    z-index: -1;
}

.plan-card:hover .plan-btn {
    border-color: transparent;
    color: white;
}

.plan-card:hover .plan-btn::before {
    transform: scaleX(1);
}

/* Addons */
.addons-section {
    margin-top: 64px;
}

.addons-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 28px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.addons-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.addon-chip {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 24px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.addon-chip::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-red);
    opacity: 0;
    transition: opacity 0.5s var(--ease-out-expo);
}

.addon-chip span,
.addon-chip i {
    position: relative;
    z-index: 1;
    transition: all 0.4s var(--ease-out-expo);
}

.addon-chip:hover {
    border-color: var(--accent-red);
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 40px rgba(255, 51, 51, 0.3);
}

.addon-chip:hover::before {
    opacity: 1;
}

.addon-chip:hover span,
.addon-chip:hover i {
    color: white;
}

.addon-chip i {
    color: var(--text-tertiary);
    transition: transform 0.4s var(--ease-out-expo);
}

.addon-chip:hover i {
    transform: rotate(15deg) scale(1.2);
}

.addon-price {
    margin-left: 8px;
    padding-left: 16px;
    border-left: 1px solid var(--border);
    font-weight: 700;
    color: var(--accent-red);
    transition: all 0.4s var(--ease-out-expo);
}

.addon-chip:hover .addon-price {
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}

/* ============================================
   ENTERPRISE SECTION - PREMIUM
   ============================================ */
.enterprise-section {
    background: var(--bg-primary);
}

.enterprise-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
}

.enterprise-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 40px;
    padding: 48px;
    position: relative;
    overflow: hidden;
    transition: all 0.6s var(--ease-out-expo);
}

.enterprise-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.08), transparent 60%);
    opacity: 0;
    transition: opacity 0.6s var(--ease-out-expo);
}

.enterprise-card:hover {
    border-color: rgba(255, 51, 51, 0.4);
    transform: translateY(-15px) scale(1.02);
    box-shadow: 
        0 50px 100px rgba(0, 0, 0, 0.5),
        0 0 80px rgba(255, 51, 51, 0.15);
}

.enterprise-card:hover::before {
    opacity: 1;
}

.enterprise-card.ultimate {
    background: linear-gradient(135deg, var(--bg-card), rgba(255, 51, 51, 0.05));
    border-color: rgba(255, 51, 51, 0.3);
}

.enterprise-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.enterprise-icon {
    width: 64px;
    height: 64px;
    background: var(--gradient-red);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    transition: all 0.5s var(--ease-out-expo);
    box-shadow: 0 8px 30px rgba(255, 51, 51, 0.3);
}

.enterprise-card:hover .enterprise-icon {
    transform: scale(1.1) rotate(-10deg);
    box-shadow: 0 12px 40px rgba(255, 51, 51, 0.5);
}

.enterprise-info {
    flex: 1;
}

.enterprise-name {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 6px;
}

.enterprise-badge {
    display: inline-block;
    padding: 8px 16px;
    background: var(--gradient-red);
    color: white;
    font-size: 11px;
    font-weight: 800;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 4px 15px rgba(255, 51, 51, 0.3);
}

.enterprise-badge.dark {
    background: var(--text-tertiary);
    color: var(--text-primary);
}

.enterprise-desc {
    color: var(--text-tertiary);
    margin-bottom: 28px;
    font-size: 16px;
    position: relative;
    z-index: 1;
}

.enterprise-price {
    font-size: 56px;
    font-weight: 900;
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
}

.enterprise-price span {
    font-size: 20px;
    color: var(--text-tertiary);
    font-weight: 400;
}

.enterprise-features {
    list-style: none;
    margin-bottom: 36px;
    position: relative;
    z-index: 1;
}

.enterprise-features li {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
    font-size: 16px;
    color: var(--text-secondary);
    transition: all 0.4s var(--ease-out-expo);
}

.enterprise-features li:hover {
    color: var(--text-primary);
    transform: translateX(10px);
}

.enterprise-features li:last-child {
    border-bottom: none;
}

.enterprise-features i {
    color: #00ff88;
    font-size: 16px;
    transition: transform 0.4s var(--ease-out-expo);
}

.enterprise-features li:hover i {
    transform: scale(1.3);
}

/* ============================================
   SUPPORT SECTION - PREMIUM
   ============================================ */
.support-section {
    background: var(--bg-secondary);
}

.support-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    margin-bottom: 80px;
}

.support-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 32px;
    padding: 48px 40px;
    text-align: center;
    text-decoration: none;
    color: var(--text-primary);
    position: relative;
    overflow: hidden;
    transition: all 0.6s var(--ease-out-expo);
}

.support-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 51, 51, 0.08), transparent 60%);
    opacity: 0;
    transition: opacity 0.6s var(--ease-out-expo);
}

.support-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-red);
    transform: scaleX(0);
    transition: transform 0.5s var(--ease-out-expo);
}

.support-card:hover {
    border-color: rgba(255, 51, 51, 0.4);
    transform: translateY(-15px) scale(1.03);
    box-shadow: 
        0 40px 80px rgba(0, 0, 0, 0.5),
        0 0 60px rgba(255, 51, 51, 0.15);
}

.support-card:hover::before {
    opacity: 1;
}

.support-card:hover::after {
    transform: scaleX(1);
}

.support-icon {
    width: 88px;
    height: 88px;
    background: var(--gradient-red);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: white;
    margin: 0 auto 28px;
    position: relative;
    z-index: 1;
    transition: all 0.5s var(--ease-out-expo);
    box-shadow: 0 10px 40px rgba(255, 51, 51, 0.3);
}

.support-card:hover .support-icon {
    transform: scale(1.15) rotate(10deg);
    box-shadow: 0 15px 50px rgba(255, 51, 51, 0.5);
}

.support-title {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
}

.support-desc {
    color: var(--text-tertiary);
    margin-bottom: 24px;
    font-size: 16px;
    position: relative;
    z-index: 1;
}

.support-status {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 700;
    position: relative;
    z-index: 1;
    transition: all 0.4s var(--ease-out-expo);
}

.support-card:hover .support-status {
    background: rgba(255, 51, 51, 0.1);
    border-color: rgba(255, 51, 51, 0.3);
}

.support-status.online .status-dot {
    width: 10px;
    height: 10px;
    background: #00ff88;
    border-radius: 50%;
    animation: pulse 2s infinite;
    box-shadow: 0 0 10px #00ff88;
}

.support-status.vip {
    background: rgba(255, 51, 51, 0.1);
    color: var(--accent-red);
    border-color: rgba(255, 51, 51, 0.3);
}

/* Channels */
.channels-section {
    max-width: 800px;
    margin: 0 auto;
}

.channels-title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 32px;
    text-transform: uppercase;
    letter-spacing: 3px;
}

.channels-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.channel-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 22px 28px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    text-decoration: none;
    color: var(--text-primary);
    position: relative;
    overflow: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.channel-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(255, 51, 51, 0.05), transparent);
    opacity: 0;
    transition: opacity 0.5s var(--ease-out-expo);
}

.channel-item:hover {
    background: rgba(255, 255, 255, 0.03);
    border-color: rgba(255, 51, 51, 0.3);
    transform: translateX(15px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.channel-item:hover::before {
    opacity: 1;
}

.channel-icon {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--text-secondary);
    transition: all 0.4s var(--ease-out-expo);
}

.channel-item:hover .channel-icon {
    background: rgba(255, 51, 51, 0.15);
    color: var(--accent-red);
    transform: scale(1.1);
}

.channel-info {
    flex: 1;
}

.channel-name {
    font-weight: 700;
    font-size: 17px;
    margin-bottom: 4px;
    transition: color 0.4s var(--ease-out-expo);
}

.channel-item:hover .channel-name {
    color: var(--accent-red);
}

.channel-desc {
    font-size: 14px;
    color: var(--text-tertiary);
}

.channel-arrow {
    color: var(--text-tertiary);
    font-size: 14px;
    transition: all 0.4s var(--ease-out-expo);
}

.channel-item:hover .channel-arrow {
    transform: translateX(10px);
    color: var(--accent-red);
}

/* ============================================
   FOOTER - PREMIUM
   ============================================ */
.footer {
    background: var(--bg-primary);
    border-top: 1px solid var(--border);
    padding: 80px 40px;
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    text-align: center;
}

.footer-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 24px;
}

.footer-icon {
    width: 52px;
    height: 52px;
    background: var(--gradient-red);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    transition: all 0.5s var(--ease-out-expo);
    box-shadow: 0 8px 30px rgba(255, 51, 51, 0.3);
}

.footer-icon:hover {
    transform: rotate(-10deg) scale(1.1);
    box-shadow: 0 12px 40px rgba(255, 51, 51, 0.5);
}

.footer-name {
    font-size: 32px;
    font-weight: 800;
}

.footer-text {
    color: var(--text-tertiary);
    margin-bottom: 32px;
    font-size: 17px;
}

.footer-links {
    display: flex;
    gap: 32px;
    justify-content: center;
    margin-bottom: 32px;
}

.footer-links button {
    color: var(--text-secondary);
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 100px;
    transition: all 0.4s var(--ease-out-expo);
    position: relative;
}

.footer-links button::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: var(--accent-red);
    transition: all 0.4s var(--ease-out-expo);
    transform: translateX(-50%);
}

.footer-links button:hover {
    color: var(--accent-red);
}

.footer-links button:hover::after {
    width: 60%;
}

.footer-copy {
    font-size: 14px;
    color: var(--text-muted);
}

.footer-copy .highlight {
    color: var(--accent-red);
    font-weight: 700;
}

/* ============================================
   MODAL & OVERLAYS - PREMIUM
   ============================================ */
.redirect-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(40px);
    z-index: 5000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.6s var(--ease-out-expo);
}

.redirect-overlay.active {
    opacity: 1;
    visibility: visible;
}

.redirect-content {
    text-align: center;
    padding: 60px;
    animation: scaleIn 0.6s var(--ease-out-expo);
}

.redirect-icon {
    width: 120px;
    height: 120px;
    background: var(--gradient-red);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 56px;
    color: white;
    margin: 0 auto 40px;
    animation: redirectPulse 1.5s ease-in-out infinite;
    box-shadow: 0 0 60px rgba(255, 51, 51, 0.4);
}

@keyframes redirectPulse {
    0%, 100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 51, 51, 0.4), 0 0 60px rgba(255, 51, 51, 0.3);
    }
    50% { 
        transform: scale(1.08);
        box-shadow: 0 0 0 40px rgba(255, 51, 51, 0), 0 0 80px rgba(255, 51, 51, 0.5);
    }
}

.redirect-title {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 16px;
}

.redirect-text {
    color: var(--text-secondary);
    margin-bottom: 40px;
    font-size: 18px;
}

.redirect-loader {
    width: 300px;
    height: 4px;
    background: var(--border);
    border-radius: 4px;
    margin: 0 auto;
    overflow: hidden;
}

.redirect-progress {
    height: 100%;
    background: var(--gradient-red);
    width: 0;
    animation: redirectProgress 2s var(--ease-out-expo) forwards;
    position: relative;
}

.redirect-progress::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: progressShine 1s ease-in-out infinite;
}

@keyframes redirectProgress {
    from { width: 0; }
    to { width: 100%; }
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(30px);
    z-index: 6000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.5s var(--ease-out-expo);
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 40px;
    padding: 60px;
    max-width: 480px;
    width: 90%;
    text-align: center;
    transform: scale(0.9) translateY(20px);
    transition: all 0.5s var(--ease-out-expo);
}

.modal-overlay.active .modal-content {
    transform: scale(1) translateY(0);
}

.modal-icon {
    width: 88px;
    height: 88px;
    background: var(--gradient-red);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: white;
    margin: 0 auto 32px;
    box-shadow: 0 10px 40px rgba(255, 51, 51, 0.3);
    animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.modal-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 12px;
}

.modal-subtitle {
    color: var(--text-secondary);
    margin-bottom: 36px;
    font-size: 16px;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.modal-input {
    width: 100%;
    padding: 20px 24px;
    background: var(--bg-tertiary);
    border: 2px solid var(--border);
    border-radius: 16px;
    color: var(--text-primary);
    font-size: 16px;
    outline: none;
    transition: all 0.4s var(--ease-out-expo);
}

.modal-input:focus {
    border-color: var(--accent-red);
    box-shadow: 0 0 30px rgba(255, 51, 51, 0.2);
}

.modal-btn {
    width: 100%;
    padding: 20px;
    background: var(--gradient-red);
    color: white;
    border: none;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.5s var(--ease-out-expo);
    position: relative;
    overflow: hidden;
}

.modal-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #ff5555, #ff3333);
    opacity: 0;
    transition: opacity 0.5s var(--ease-out-expo);
}

.modal-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(255, 51, 51, 0.4);
}

.modal-btn:hover::before {
    opacity: 1;
}

.modal-btn span,
.modal-btn i {
    position: relative;
    z-index: 1;
}

.modal-cancel {
    margin-top: 20px;
    color: var(--text-secondary);
    background: none;
    border: none;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.4s var(--ease-out-expo);
    padding: 10px 20px;
    border-radius: 100px;
}

.modal-cancel:hover {
    color: var(--accent-red);
    background: rgba(255, 51, 51, 0.1);
}

/* ============================================
   ADMIN PANEL - PREMIUM
   ============================================ */
.admin-panel {
    position: fixed;
    inset: 0;
    background: var(--bg-primary);
    z-index: 7000;
    display: none;
    overflow: hidden;
}

.admin-panel.active {
    display: flex;
    animation: fadeIn 0.5s var(--ease-out-expo);
}

.admin-sidebar {
    width: 300px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    padding: 40px 28px;
    display: flex;
    flex-direction: column;
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 56px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--border);
}

.admin-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-red);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 6px 20px rgba(255, 51, 51, 0.3);
}

.admin-brand span {
    font-size: 18px;
    font-weight: 800;
}

.admin-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.admin-nav-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.4s var(--ease-out-expo);
}

.admin-nav-item i {
    font-size: 20px;
    width: 28px;
    text-align: center;
    transition: transform 0.4s var(--ease-out-expo);
}

.admin-nav-item:hover {
    background: rgba(255, 255, 255, 0.03);
    color: var(--text-primary);
    transform: translateX(8px);
}

.admin-nav-item:hover i {
    transform: scale(1.2);
}

.admin-nav-item.active {
    background: rgba(255, 51, 51, 0.1);
    color: var(--accent-red);
}

.admin-logout {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    color: var(--text-secondary);
    background: none;
    border: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.4s var(--ease-out-expo);
    margin-top: auto;
}

.admin-logout:hover {
    background: rgba(255, 0, 0, 0.1);
    color: #ff4444;
    transform: translateX(8px);
}

.admin-main {
    flex: 1;
    padding: 40px 48px;
    overflow-y: auto;
}

.admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 40px;
}

.admin-header h1 {
    font-size: 36px;
    font-weight: 800;
}

.admin-status {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    color: var(--text-secondary);
}

.admin-status .status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #00ff88;
    animation: pulse 2s infinite;
    box-shadow: 0 0 10px #00ff88;
}

.admin-tab {
    display: none;
}

.admin-tab.active {
    display: block;
    animation: fadeInUp 0.5s var(--ease-out-expo);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.5s var(--ease-out-expo);
}

.stat-card:hover {
    border-color: rgba(255, 51, 51, 0.3);
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.stat-card .stat-icon {
    width: 56px;
    height: 56px;
    background: rgba(255, 51, 51, 0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--accent-red);
    transition: all 0.4s var(--ease-out-expo);
}

.stat-card:hover .stat-icon {
    background: var(--gradient-red);
    color: white;
    transform: scale(1.1);
}

.stat-info {
    flex: 1;
}

.stat-info .stat-value {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 6px;
    font-family: var(--font-mono);
}

.stat-info .stat-label {
    font-size: 13px;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.admin-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 28px;
}

.admin-section h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--text-secondary);
}

.chart-container {
    height: 320px;
}

/* Visitor List */
.visitor-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.visitor-item {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.4s var(--ease-out-expo);
}

.visitor-item:hover {
    border-color: rgba(255, 51, 51, 0.2);
    transform: translateX(10px);
}

.visitor-ip {
    font-family: var(--font-mono);
    font-size: 15px;
    font-weight: 700;
}

.visitor-time {
    margin-left: auto;
    font-size: 14px;
    color: var(--text-tertiary);
}

.btn-clear {
    padding: 12px 20px;
    background: rgba(255, 0, 0, 0.1);
    color: #ff4444;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.4s var(--ease-out-expo);
}

.btn-clear:hover {
    background: rgba(255, 0, 0, 0.2);
    transform: translateY(-3px);
}

/* Config Card */
.config-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    margin-bottom: 24px;
}

.config-card h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--text-secondary);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 13px;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
}

.form-input {
    width: 100%;
    padding: 16px 20px;
    background: var(--bg-tertiary);
    border: 2px solid var(--border);
    border-radius: 14px;
    color: var(--text-primary);
    font-size: 15px;
    outline: none;
    transition: all 0.4s var(--ease-out-expo);
}

.form-input:focus {
    border-color: var(--accent-red);
    box-shadow: 0 0 20px rgba(255, 51, 51, 0.15);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-size: 15px;
}

.checkbox-label input {
    width: 22px;
    height: 22px;
    accent-color: var(--accent-red);
}

.btn-save {
    padding: 16px 28px;
    background: var(--gradient-red);
    color: white;
    border: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    transition: all 0.5s var(--ease-out-expo);
}

.btn-save:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(255, 51, 51, 0.4);
}

.btn-danger {
    padding: 16px 28px;
    background: rgba(255, 0, 0, 0.1);
    color: #ff4444;
    border: 2px solid rgba(255, 0, 0, 0.3);
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    transition: all 0.5s var(--ease-out-expo);
}

.btn-danger:hover {
    background: rgba(255, 0, 0, 0.2);
    transform: translateY(-3px);
}

.setup-steps {
    color: var(--text-secondary);
    line-height: 2.2;
    padding-left: 24px;
}

.setup-steps a {
    color: var(--accent-red);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s var(--ease-out-expo);
}

.setup-steps a:hover {
    text-decoration: underline;
}

.setup-steps code {
    background: var(--bg-tertiary);
    padding: 4px 12px;
    border-radius: 8px;
    font-family: var(--font-mono);
    font-size: 14px;
    color: var(--accent-red);
}

/* ============================================
   VISITOR TOAST - PREMIUM
   ============================================ */
.visitor-toast {
    position: fixed;
    top: 120px;
    right: 32px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 20px 28px;
    display: flex;
    align-items: center;
    gap: 18px;
    z-index: 9999;
    transform: translateX(150%);
    transition: all 0.6s var(--ease-out-expo);
    box-shadow: var(--shadow-xl);
    backdrop-filter: blur(20px);
}

.visitor-toast.show {
    transform: translateX(0);
}

.toast-icon {
    width: 56px;
    height: 56px;
    background: var(--gradient-red);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    animation: toastPulse 2s ease-in-out infinite;
    box-shadow: 0 6px 20px rgba(255, 51, 51, 0.3);
}

@keyframes toastPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.toast-content {
    min-width: 200px;
}

.toast-title {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 6px;
}

.toast-info {
    font-size: 13px;
    color: var(--text-secondary);
    font-family: var(--font-mono);
}

.toast-info .ip {
    color: var(--accent-red);
    font-weight: 600;
}

/* ============================================
   RESPONSIVE DESIGN
   ============================================ */
@media (max-width: 1200px) {
    .nav-links {
        display: none;
    }
    
    .menu-toggle {
        display: flex;
    }
    
    .about-grid {
        grid-template-columns: 1fr;
        gap: 60px;
    }
    
    .enterprise-grid {
        grid-template-columns: 1fr;
    }
    
    .support-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 1024px) {
    .admin-panel {
        flex-direction: column;
    }
    
    .admin-sidebar {
        width: 100%;
        flex-direction: row;
        padding: 20px;
        overflow-x: auto;
    }
    
    .admin-brand {
        display: none;
    }
    
    .admin-nav {
        flex-direction: row;
        gap: 10px;
    }
    
    .admin-nav-item {
        white-space: nowrap;
    }
    
    .admin-logout {
        margin-top: 0;
        margin-left: auto;
    }
    
    .admin-main {
        padding: 28px;
    }
}

@media (max-width: 768px) {
    :root {
        --section-padding: 100px;
    }
    
    .navbar {
        padding: 16px 24px;
    }
    
    .language-selector {
        display: none;
    }
    
    .hero {
        padding: 140px 24px 80px;
    }
    
    .hero-title {
        font-size: 42px;
    }
    
    .hero-stats {
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    .hero-stat {
        padding: 24px 16px;
    }
    
    .stat-number {
        font-size: 36px;
    }
    
    section {
        padding: 100px 24px;
    }
    
    .tier-tabs {
        flex-wrap: wrap;
    }
    
    .tier-tab {
        padding: 14px 28px;
        font-size: 14px;
    }
    
    .plans-grid {
        grid-template-columns: 1fr;
    }
    
    .about-features {
        grid-template-columns: 1fr;
    }
    
    .addons-grid {
        justify-content: center;
    }
    
    .addon-chip {
        width: 100%;
        justify-content: space-between;
    }
}

@media (max-width: 480px) {
    .hero-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .hero-stats {
        grid-template-columns: 1fr;
    }
    
    .tier-card {
        padding: 40px 28px;
    }
    
    .enterprise-card {
        padding: 32px 24px;
    }
    
    .support-card {
        padding: 36px 28px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        padding: 40px 28px;
    }
}
