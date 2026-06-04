/* ============================================
   KILLBYTE PREMIUM - MAIN JAVASCRIPT
   Premium Animations & Counter Effects
   ============================================ */

// Language Translations
const translations = {
    en: {
        nav: {
            home: 'Home',
            terminal: 'Terminal',
            tiers: 'Tiers',
            plans: 'Plans',
            enterprise: 'Enterprise',
            support: 'Support'
        },
        hero: {
            badge: 'Top #1 Market Services',
            title1: 'KillByte Solutions',
            title2: 'Power Infrastructure',
            description: 'The most powerful Layer 7 & Layer 4 DDoS infrastructure. Engineered for precision, built for dominance.',
            highlight: '@rankflood Best L7 & L4 DDoS',
            viewPlans: 'View Plans',
            getSupport: 'Get Support',
            stat1: 'L4 Capacity',
            stat2: 'L7 Requests/sec',
            stat3: 'Uptime',
            stat4: 'Active servers'
        },
        terminal: {
            label: 'System Status',
            title: 'Live Terminal',
            subtitle: 'Real-time infrastructure monitoring',
            hint: 'Type help for available commands • methods for attack vectors • clear to reset'
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
            standard: 'Standard',
            vip: 'VIP',
            private: 'Private',
            standardDesc: 'Entry-level power for testing environments',
            vipDesc: 'Professional grade with enhanced capabilities',
            privateDesc: 'Exclusive access to restricted infrastructure',
            getStarted: 'Get Started',
            getVIP: 'Get VIP',
            getPrivate: 'Get Private',
            mostPopular: 'MOST POPULAR'
        },
        plans: {
            label: 'Pricing',
            title: 'Service Plans',
            subtitle: 'Flexible options for every requirement',
            monthly: 'Monthly Plans',
            daily: 'Daily Blasts',
            addons: 'Add-ons',
            selectPlan: 'Select Plan'
        },
        enterprise: {
            label: 'Enterprise',
            title: 'Elite Solutions',
            subtitle: 'Maximum power for maximum impact',
            configure: 'Configure'
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
            online: 'Online Now',
            vipSupport: 'VIP Support',
            channels: 'Official Channels'
        },
        footer: {
            text: 'Premium infrastructure for elite operators.',
            terms: 'Terms',
            privacy: 'Privacy',
            admin: 'Admin',
            copyright: '© 2026 KillByte Solutions. All rights reserved. | Admin:'
        },
        admin: {
            title: 'Admin Panel',
            login: 'Admin Login',
            password: 'Enter Password',
            access: 'Access Panel',
            close: 'Close',
            success: 'Admin access granted',
            error: 'Invalid password',
            logout: 'Logged out',
            dashboard: 'Dashboard',
            settings: 'Settings',
            users: 'Users',
            stats: 'Stats',
            totalVisitors: 'Total Visitors',
            activeUsers: 'Active Users',
            serverLoad: 'Server Load',
            bandwidth: 'Bandwidth',
            visitorStats: 'Visitor Statistics',
            quickActions: 'Quick Actions',
            clearCache: 'Clear Cache',
            restartService: 'Restart Service',
            backupData: 'Backup Data',
            systemLogs: 'System Logs'
        },
        notifications: {
            redirecting: 'Redirecting to Telegram...',
            botOpened: 'Telegram bot opened',
            adminContact: 'Contacting admin...',
            processing: 'Processing...',
            success: 'Success!',
            error: 'Error!'
        }
    },
    cn: {
        nav: {
            home: '首页',
            terminal: '终端',
            tiers: '等级',
            plans: '计划',
            enterprise: '企业',
            support: '支持'
        },
        hero: {
            badge: '市场第一服务',
            title1: 'KillByte 解决方案',
            title2: '电力基础设施',
            description: '最强大的第7层和第4层DDoS基础设施。为精准而设计，为统治而构建。',
            highlight: '@rankflood 最佳L7和L4 DDoS',
            viewPlans: '查看计划',
            getSupport: '获取支持',
            stat1: 'L4容量',
            stat2: 'L7请求/秒',
            stat3: '正常运行时间',
            stat4: '活动服务器'
        },
        terminal: {
            label: '系统状态',
            title: '实时终端',
            subtitle: '实时基础设施监控',
            hint: '输入help查看可用命令 • methods查看攻击向量 • clear重置'
        },
        about: {
            label: '关于我们',
            title: '我们是',
            text1: '在KillByte，我们专注于提供高性能DDoS服务，特别是第7层（L7），能够处理最复杂和有针对性的攻击。我们可以达到630 Gbps，提供巨大的力量来压倒和破坏任何目标。',
            text2: '我们最先进的基础设施使我们能够以每秒7200万次请求的精度执行打击，确保以无与伦比的可靠性产生最大影响。',
            feature1: '即时部署',
            feature2: '隐形方法',
            feature3: '全球服务器',
            feature4: '24/7支持'
        },
        tiers: {
            label: '性能',
            title: '功率等级',
            subtitle: '选择您的操作级别',
            standard: '标准',
            vip: 'VIP',
            private: '私人',
            standardDesc: '用于测试环境的入门级电源',
            vipDesc: '具有增强功能的专业级',
            privateDesc: '独家访问受限基础设施',
            getStarted: '开始使用',
            getVIP: '获取VIP',
            getPrivate: '获取私人',
            mostPopular: '最受欢迎'
        },
        plans: {
            label: '定价',
            title: '服务计划',
            subtitle: '满足各种需求的灵活选择',
            monthly: '月度计划',
            daily: '每日爆破',
            addons: '附加组件',
            selectPlan: '选择计划'
        },
        enterprise: {
            label: '企业',
            title: '精英解决方案',
            subtitle: '最大功率实现最大影响',
            configure: '配置'
        },
        support: {
            label: '协助',
            title: '获取支持',
            subtitle: '24/7专业协助',
            telegramBot: '电报机器人',
            telegramBotDesc: '自动订购和即时交付',
            directAdmin: '直接管理员',
            directAdminDesc: '联系@rankflood获取销售和支持',
            supportCenter: '支持中心',
            supportCenterDesc: '从我们的支持团队获取帮助',
            online: '在线',
            vipSupport: 'VIP支持',
            channels: '官方频道'
        },
        footer: {
            text: '为精英操作员提供优质基础设施。',
            terms: '条款',
            privacy: '隐私',
            admin: '管理员',
            copyright: '© 2026 KillByte解决方案。保留所有权利。| 管理员:'
        },
        admin: {
            title: '管理面板',
            login: '管理员登录',
            password: '输入密码',
            access: '访问面板',
            close: '关闭',
            success: '管理员访问已授予',
            error: '密码错误',
            logout: '已登出',
            dashboard: '仪表板',
            settings: '设置',
            users: '用户',
            stats: '统计',
            totalVisitors: '总访客',
            activeUsers: '在线用户',
            serverLoad: '服务器负载',
            bandwidth: '带宽',
            visitorStats: '访客统计',
            quickActions: '快速操作',
            clearCache: '清除缓存',
            restartService: '重启服务',
            backupData: '备份数据',
            systemLogs: '系统日志'
        },
        notifications: {
            redirecting: '正在重定向到Telegram...',
            botOpened: 'Telegram机器人已打开',
            adminContact: '正在联系管理员...',
            processing: '处理中...',
            success: '成功！',
            error: '错误！'
        }
    },
    ru: {
        nav: {
            home: 'Главная',
            terminal: 'Терминал',
            tiers: 'Уровни',
            plans: 'Планы',
            enterprise: 'Enterprise',
            support: 'Поддержка'
        },
        hero: {
            badge: 'Топ #1 на рынке',
            title1: 'KillByte Solutions',
            title2: 'Мощная инфраструктура',
            description: 'Самая мощная инфраструктура DDoS уровня 7 и 4. Создана для точности, построена для доминирования.',
            highlight: '@rankflood Лучший L7 и L4 DDoS',
            viewPlans: 'Смотреть планы',
            getSupport: 'Получить поддержку',
            stat1: 'L4 Емкость',
            stat2: 'L7 Запросов/сек',
            stat3: 'Аптайм',
            stat4: 'Активных серверов'
        },
        terminal: {
            label: 'Статус системы',
            title: 'Live Терминал',
            subtitle: 'Мониторинг инфраструктуры в реальном времени',
            hint: 'Введите help для команд • methods для атак • clear для сброса'
        },
        about: {
            label: 'О нас',
            title: 'Мы',
            text1: 'В KillByte мы специализируемся на предоставлении мощных DDoS-услуг, особенно уровня 7 (L7), с возможностью обработки самых сложных и целенаправленных атак. Мы можем достичь 630 Гбит/с, обеспечивая огромную мощность для подавления любой цели.',
            text2: 'Наша современная инфраструктура позволяет нам выполнять точечные удары со скоростью до 72 миллионов запросов в секунду, обеспечивая максимальное воздействие с беспрецедентной надежностью.',
            feature1: 'Мгновенное развертывание',
            feature2: 'Скрытые методы',
            feature3: 'Глобальные серверы',
            feature4: 'Поддержка 24/7'
        },
        tiers: {
            label: 'Производительность',
            title: 'Уровни мощности',
            subtitle: 'Выберите ваш уровень работы',
            standard: 'Стандарт',
            vip: 'VIP',
            private: 'Приват',
            standardDesc: 'Начальный уровень для тестовых сред',
            vipDesc: 'Профессиональный уровень с расширенными возможностями',
            privateDesc: 'Эксклюзивный доступ к ограниченной инфраструктуре',
            getStarted: 'Начать',
            getVIP: 'Получить VIP',
            getPrivate: 'Получить Приват',
            mostPopular: 'ПОПУЛЯРНЫЙ'
        },
        plans: {
            label: 'Цены',
            title: 'Тарифные планы',
            subtitle: 'Гибкие варианты для любых требований',
            monthly: 'Ежемесячные планы',
            daily: 'Ежедневные атаки',
            addons: 'Дополнения',
            selectPlan: 'Выбрать план'
        },
        enterprise: {
            label: 'Enterprise',
            title: 'Элитные решения',
            subtitle: 'Максимальная мощность для максимального эффекта',
            configure: 'Настроить'
        },
        support: {
            label: 'Помощь',
            title: 'Получить поддержку',
            subtitle: 'Профессиональная помощь 24/7',
            telegramBot: 'Telegram Бот',
            telegramBotDesc: 'Автоматический заказ и мгновенная доставка',
            directAdmin: 'Прямой админ',
            directAdminDesc: 'Свяжитесь с @rankflood для продаж и поддержки',
            supportCenter: 'Центр поддержки',
            supportCenterDesc: 'Получите помощь от нашей команды поддержки',
            online: 'В сети',
            vipSupport: 'VIP поддержка',
            channels: 'Официальные каналы'
        },
        footer: {
            text: 'Премиум инфраструктура для элитных операторов.',
            terms: 'Условия',
            privacy: 'Конфиденциальность',
            admin: 'Админ',
            copyright: '© 2026 KillByte Solutions. Все права защищены. | Админ:'
        },
        admin: {
            title: 'Панель админа',
            login: 'Вход админа',
            password: 'Введите пароль',
            access: 'Войти',
            close: 'Закрыть',
            success: 'Доступ разрешен',
            error: 'Неверный пароль',
            logout: 'Вышел',
            dashboard: 'Панель',
            settings: 'Настройки',
            users: 'Пользователи',
            stats: 'Статистика',
            totalVisitors: 'Всего посетителей',
            activeUsers: 'Активных',
            serverLoad: 'Нагрузка',
            bandwidth: 'Трафик',
            visitorStats: 'Статистика',
            quickActions: 'Быстрые действия',
            clearCache: 'Очистить кэш',
            restartService: 'Перезапуск',
            backupData: 'Бэкап',
            systemLogs: 'Логи'
        },
        notifications: {
            redirecting: 'Перенаправление в Telegram...',
            botOpened: 'Бот открыт',
            adminContact: 'Связь с админом...',
            processing: 'Обработка...',
            success: 'Успешно!',
            error: 'Ошибка!'
        }
    }
};

// Current Language
let currentLang = localStorage.getItem('kb_language') || 'en';

// Global State
const state = {
    isAdmin: false,
    currentLang: currentLang,
    visitorCount: 0,
    menuOpen: false,
    countersAnimated: false
};

// DOM Elements
const elements = {
    preloader: document.getElementById('preloader'),
    navbar: document.getElementById('navbar'),
    menuToggle: document.getElementById('menuToggle'),
    mobileMenu: document.getElementById('mobileMenu'),
    redirectOverlay: document.getElementById('redirectOverlay'),
    adminModal: document.getElementById('adminModal'),
    adminPanel: document.getElementById('adminPanel')
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initPreloader();
    initNavbar();
    initLanguageSelector();
    initMobileMenu();
    initTierTabs();
    initScrollAnimations();
    initHeroStatsCounter();
    initParallaxEffect();
    initBackgroundCanvas();
    updateLoginTime();
    applyLanguage(currentLang);
});

// ============================================
// PRELOADER - PREMIUM
// ============================================
function initPreloader() {
    const preloader = elements.preloader;
    if (!preloader) return;

    // Update status text with translations
    const statusEl = preloader.querySelector('.preloader-status');
    const statuses = {
        en: ['Initializing systems...', 'Loading modules...', 'Connecting to nodes...', 'Ready'],
        cn: ['系统初始化中...', '加载模块中...', '连接节点中...', '准备就绪'],
        ru: ['Инициализация...', 'Загрузка модулей...', 'Подключение к узлам...', 'Готово']
    };

    let statusIndex = 0;
    const statusInterval = setInterval(() => {
        statusIndex++;
        if (statusEl && statusIndex < statuses[currentLang].length) {
            statusEl.textContent = statuses[currentLang][statusIndex];
        }
    }, 400);

    // Hide preloader after animation
    setTimeout(() => {
        clearInterval(statusInterval);
        preloader.classList.add('hidden');
        
        // Remove from DOM after transition
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 600);
    }, 2200);
}

// ============================================
// NAVBAR - PREMIUM
// ============================================
function initNavbar() {
    const navbar = elements.navbar;
    if (!navbar) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // Active link highlighting
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollY >= sectionTop - 300) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

    // Smooth scroll for nav links
    document.querySelectorAll('.nav-link, .mobile-link').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 80;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
}

// ============================================
// LANGUAGE SELECTOR - PREMIUM
// ============================================
function initLanguageSelector() {
    const langBtn = document.getElementById('langBtn');
    const langDropdown = document.getElementById('langDropdown');
    const langOptions = document.querySelectorAll('.lang-option');

    if (!langBtn || !langDropdown) return;

    // Toggle dropdown
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langBtn.parentElement.classList.toggle('active');
    });

    // Close on outside click
    document.addEventListener('click', () => {
        langBtn.parentElement.classList.remove('active');
    });

    // Language selection
    langOptions.forEach(option => {
        option.addEventListener('click', () => {
            const lang = option.dataset.lang;
            setLanguage(lang);
            
            // Update active state
            langOptions.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            
            // Update button
            const flag = option.querySelector('img').src;
            langBtn.querySelector('img').src = flag;
            langBtn.querySelector('span').textContent = lang.toUpperCase();
        });
    });

    // Set initial active state
    const activeOption = document.querySelector(`.lang-option[data-lang="${currentLang}"]`);
    if (activeOption) {
        activeOption.classList.add('active');
        const flag = activeOption.querySelector('img').src;
        langBtn.querySelector('img').src = flag;
        langBtn.querySelector('span').textContent = currentLang.toUpperCase();
    }
}

function setLanguage(lang) {
    currentLang = lang;
    state.currentLang = lang;
    localStorage.setItem('kb_language', lang);
    applyLanguage(lang);
    
    // Update admin panel if open
    if (elements.adminPanel && elements.adminPanel.classList.contains('active')) {
        applyAdminLanguage(lang);
    }
}

function applyLanguage(lang) {
    const t = translations[lang];
    if (!t) return;

    // Update navigation
    document.querySelectorAll('.nav-link').forEach((link, index) => {
        const keys = ['home', 'terminal', 'tiers', 'plans', 'enterprise', 'support'];
        if (t.nav[keys[index]]) {
            link.querySelector('.nav-link-text').textContent = t.nav[keys[index]];
        }
    });

    // Update hero section
    const heroBadge = document.querySelector('.hero-badge span');
    if (heroBadge) heroBadge.textContent = t.hero.badge;

    const heroTitle1 = document.querySelector('.hero-title .title-line:first-child');
    if (heroTitle1) heroTitle1.textContent = t.hero.title1;

    const heroTitle2 = document.querySelector('.hero-title .title-line.gradient');
    if (heroTitle2) heroTitle2.textContent = t.hero.title2;

    const heroDesc = document.querySelector('.hero-description');
    if (heroDesc) {
        heroDesc.childNodes[0].textContent = t.hero.description + ' ';
    }

    const heroHighlight = document.querySelector('.hero-description .highlight');
    if (heroHighlight) heroHighlight.textContent = t.hero.highlight;

    const btnPrimary = document.querySelector('.hero-actions .btn-primary span');
    if (btnPrimary) btnPrimary.textContent = t.hero.viewPlans;

    const btnSecondary = document.querySelector('.hero-actions .btn-secondary span');
    if (btnSecondary) btnSecondary.textContent = t.hero.getSupport;

    // Update hero stats
    const statLabels = document.querySelectorAll('.hero-stat .stat-label');
    if (statLabels[0]) statLabels[0].textContent = t.hero.stat1;
    if (statLabels[1]) statLabels[1].textContent = t.hero.stat2;
    if (statLabels[2]) statLabels[2].textContent = t.hero.stat3;
    if (statLabels[3]) statLabels[3].textContent = t.hero.stat4;

    // Update terminal section
    const terminalLabel = document.querySelector('#terminal .section-label');
    if (terminalLabel) terminalLabel.textContent = t.terminal.label;

    const terminalTitle = document.querySelector('#terminal .section-title');
    if (terminalTitle) terminalTitle.textContent = t.terminal.title;

    const terminalSubtitle = document.querySelector('#terminal .section-subtitle');
    if (terminalSubtitle) terminalSubtitle.textContent = t.terminal.subtitle;

    const terminalHint = document.querySelector('.terminal-hint span');
    if (terminalHint) {
        terminalHint.innerHTML = t.terminal.hint.replace(/help/g, '<code>help</code>').replace(/methods/g, '<code>methods</code>').replace(/clear/g, '<code>clear</code>');
    }

    // Update about section
    const aboutLabel = document.querySelector('#about .section-label');
    if (aboutLabel) aboutLabel.textContent = t.about.label;

    const aboutTitle = document.querySelector('#about .section-title');
    if (aboutTitle) aboutTitle.innerHTML = `${t.about.title} <span class="gradient">KillByte</span>`;

    const aboutTexts = document.querySelectorAll('.about-text');
    if (aboutTexts[0]) aboutTexts[0].innerHTML = t.about.text1;
    if (aboutTexts[1]) aboutTexts[1].innerHTML = t.about.text2;

    const aboutFeatures = document.querySelectorAll('.about-feature span');
    if (aboutFeatures[0]) aboutFeatures[0].textContent = t.about.feature1;
    if (aboutFeatures[1]) aboutFeatures[1].textContent = t.about.feature2;
    if (aboutFeatures[2]) aboutFeatures[2].textContent = t.about.feature3;
    if (aboutFeatures[3]) aboutFeatures[3].textContent = t.about.feature4;

    // Update tiers section
    const tiersLabel = document.querySelector('#tiers .section-label');
    if (tiersLabel) tiersLabel.textContent = t.tiers.label;

    const tiersTitle = document.querySelector('#tiers .section-title');
    if (tiersTitle) tiersTitle.textContent = t.tiers.title;

    const tiersSubtitle = document.querySelector('#tiers .section-subtitle');
    if (tiersSubtitle) tiersSubtitle.textContent = t.tiers.subtitle;

    const tierTabs = document.querySelectorAll('.tier-tab');
    if (tierTabs[0]) tierTabs[0].textContent = t.tiers.standard;
    if (tierTabs[1]) tierTabs[1].textContent = t.tiers.vip;
    if (tierTabs[2]) tierTabs[2].textContent = t.tiers.private;

    // Update tier content buttons
    const tierButtons = document.querySelectorAll('.tier-btn');
    if (tierButtons[0]) {
        const btnSpan = tierButtons[0].querySelector('span');
        if (btnSpan) btnSpan.textContent = t.tiers.getStarted;
    }
    if (tierButtons[1]) {
        const btnSpan = tierButtons[1].querySelector('span');
        if (btnSpan) btnSpan.textContent = t.tiers.getVIP;
    }
    if (tierButtons[2]) {
        const btnSpan = tierButtons[2].querySelector('span');
        if (btnSpan) btnSpan.textContent = t.tiers.getPrivate;
    }

    // Update plans section
    const plansLabel = document.querySelector('#plans .section-label');
    if (plansLabel) plansLabel.textContent = t.plans.label;

    const plansTitle = document.querySelector('#plans .section-title');
    if (plansTitle) plansTitle.textContent = t.plans.title;

    const plansSubtitle = document.querySelector('#plans .section-subtitle');
    if (plansSubtitle) plansSubtitle.textContent = t.plans.subtitle;

    const plansCategories = document.querySelectorAll('.plans-category');
    if (plansCategories[0]) plansCategories[0].textContent = t.plans.monthly;
    if (plansCategories[1]) plansCategories[1].textContent = t.plans.daily;

    const addonsTitle = document.querySelector('.addons-title');
    if (addonsTitle) addonsTitle.textContent = t.plans.addons;

    // Update plan buttons
    document.querySelectorAll('.plan-btn').forEach(btn => {
        const btnSpan = btn.querySelector('span');
        if (btnSpan) btnSpan.textContent = t.plans.selectPlan;
    });

    // Update enterprise section
    const enterpriseLabel = document.querySelector('#enterprise .section-label');
    if (enterpriseLabel) enterpriseLabel.textContent = t.enterprise.label;

    const enterpriseTitle = document.querySelector('#enterprise .section-title');
    if (enterpriseTitle) enterpriseTitle.textContent = t.enterprise.title;

    const enterpriseSubtitle = document.querySelector('#enterprise .section-subtitle');
    if (enterpriseSubtitle) enterpriseSubtitle.textContent = t.enterprise.subtitle;

    const enterpriseBtn = document.querySelector('.enterprise-btn span');
    if (enterpriseBtn) enterpriseBtn.textContent = t.enterprise.configure;

    // Update support section
    const supportLabel = document.querySelector('#support .section-label');
    if (supportLabel) supportLabel.textContent = t.support.label;

    const supportTitle = document.querySelector('#support .section-title');
    if (supportTitle) supportTitle.textContent = t.support.title;

    const supportSubtitle = document.querySelector('#support .section-subtitle');
    if (supportSubtitle) supportSubtitle.textContent = t.support.subtitle;

    const supportCardTitles = document.querySelectorAll('.support-card h3');
    if (supportCardTitles[0]) supportCardTitles[0].textContent = t.support.telegramBot;
    if (supportCardTitles[1]) supportCardTitles[1].textContent = t.support.directAdmin;
    if (supportCardTitles[2]) supportCardTitles[2].textContent = t.support.supportCenter;

    const supportCardDescs = document.querySelectorAll('.support-card p');
    if (supportCardDescs[0]) supportCardDescs[0].textContent = t.support.telegramBotDesc;
    if (supportCardDescs[1]) supportCardDescs[1].textContent = t.support.directAdminDesc;
    if (supportCardDescs[2]) supportCardDescs[2].textContent = t.support.supportCenterDesc;

    const onlineBadges = document.querySelectorAll('.online-badge');
    onlineBadges.forEach(badge => {
        badge.innerHTML = `<span class="online-dot"></span> ${t.support.online}`;
    });

    const channelsTitle = document.querySelector('.channels-title');
    if (channelsTitle) channelsTitle.textContent = t.support.channels;

    // Update footer
    const footerText = document.querySelector('.footer-text');
    if (footerText) footerText.textContent = t.footer.text;

    const footerLinks = document.querySelectorAll('.footer-links button');
    if (footerLinks[0]) footerLinks[0].textContent = t.footer.terms;
    if (footerLinks[1]) footerLinks[1].textContent = t.footer.privacy;
    if (footerLinks[2]) footerLinks[2].textContent = t.footer.admin;

    const copyrightText = document.querySelector('.copyright');
    if (copyrightText) {
        const adminSpan = copyrightText.querySelector('span:last-child');
        if (adminSpan) {
            copyrightText.innerHTML = `${t.footer.copyright} <span>@rankflood</span>`;
        }
    }
}

function applyAdminLanguage(lang) {
    const t = translations[lang].admin;
    if (!t) return;

    // Update admin modal
    const modalTitle = document.querySelector('.admin-modal h2');
    if (modalTitle) modalTitle.textContent = t.login;

    const modalInput = document.querySelector('.admin-modal input');
    if (modalInput) modalInput.placeholder = t.password;

    const modalButton = document.querySelector('.admin-modal .admin-btn');
    if (modalButton) {
        const btnSpan = modalButton.querySelector('span');
        if (btnSpan) btnSpan.textContent = t.access;
    }

    const modalClose = document.querySelector('.modal-close span');
    if (modalClose) modalClose.textContent = t.close;

    // Update admin panel
    const panelTitle = document.querySelector('.admin-panel h2');
    if (panelTitle) panelTitle.textContent = t.title;

    const navItems = document.querySelectorAll('.admin-nav-item span');
    const navKeys = ['dashboard', 'settings', 'users', 'stats'];
    navItems.forEach((item, index) => {
        if (navKeys[index] && t[navKeys[index]]) {
            item.textContent = t[navKeys[index]];
        }
    });

    // Update stats cards
    const statTitles = document.querySelectorAll('.admin-stat h3');
    const statKeys = ['totalVisitors', 'activeUsers', 'serverLoad', 'bandwidth'];
    statTitles.forEach((title, index) => {
        if (statKeys[index] && t[statKeys[index]]) {
            title.textContent = t[statKeys[index]];
        }
    });

    // Update chart title
    const chartTitle = document.querySelector('.admin-chart h3');
    if (chartTitle) chartTitle.textContent = t.visitorStats;

    // Update quick actions
    const actionsTitle = document.querySelector('.admin-actions h3');
    if (actionsTitle) actionsTitle.textContent = t.quickActions;

    const actionButtons = document.querySelectorAll('.admin-action-btn span');
    const actionKeys = ['clearCache', 'restartService', 'backupData', 'systemLogs'];
    actionButtons.forEach((btn, index) => {
        if (actionKeys[index] && t[actionKeys[index]]) {
            btn.textContent = t[actionKeys[index]];
        }
    });

    const logoutBtn = document.querySelector('.admin-logout span');
    if (logoutBtn) logoutBtn.textContent = t.logout;
}

// ============================================
// MOBILE MENU
// ============================================
function initMobileMenu() {
    const menuToggle = elements.menuToggle;
    const mobileMenu = elements.mobileMenu;

    if (!menuToggle || !mobileMenu) return;

    menuToggle.addEventListener('click', () => {
        state.menuOpen = !state.menuOpen;
        mobileMenu.classList.toggle('active', state.menuOpen);
        
        // Animate hamburger
        const spans = menuToggle.querySelectorAll('span');
        if (state.menuOpen) {
            spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
        } else {
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });

    // Update mobile menu links with language
    updateMobileMenuLinks();
}

function updateMobileMenuLinks() {
    const t = translations[currentLang].nav;
    const mobileLinks = document.querySelectorAll('.mobile-link .nav-link-text');
    
    const keys = ['home', 'terminal', 'tiers', 'plans', 'enterprise', 'support'];
    mobileLinks.forEach((link, index) => {
        if (keys[index] && t[keys[index]]) {
            link.textContent = t[keys[index]];
        }
    });
}

function closeMobileMenu() {
    state.menuOpen = false;
    if (elements.mobileMenu) {
        elements.mobileMenu.classList.remove('active');
    }
    
    if (elements.menuToggle) {
        const spans = elements.menuToggle.querySelectorAll('span');
        spans[0].style.transform = 'none';
        spans[1].style.opacity = '1';
        spans[2].style.transform = 'none';
    }
}

// ============================================
// TIER TABS - PREMIUM
// ============================================
function initTierTabs() {
    const tabs = document.querySelectorAll('.tier-tab');
    const contents = document.querySelectorAll('.tier-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tier = tab.dataset.tier;

            // Update tabs with animation
            tabs.forEach(t => {
                t.classList.remove('active');
                t.style.transform = 'scale(0.95)';
            });
            
            tab.classList.add('active');
            setTimeout(() => {
                tab.style.transform = 'scale(1)';
            }, 50);

            // Update contents
            contents.forEach(c => c.classList.remove('active'));
            const content = document.getElementById(`tier-${tier}`);
            if (content) {
                content.classList.add('active');
            }
        });
    });
}

// ============================================
// SCROLL ANIMATIONS - PREMIUM
// ============================================
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                
                // Add stagger animation for children
                const children = entry.target.querySelectorAll('.stagger-item');
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('revealed');
                    }, index * 100);
                });
            }
        });
    }, observerOptions);

    // Observe all major elements
    document.querySelectorAll('.tier-card, .plan-card, .support-card, .enterprise-card, .about-feature, .addon-chip').forEach(el => {
        el.classList.add('scroll-reveal');
        observer.observe(el);
    });
}

// ============================================
// HERO STATS COUNTER - PREMIUM ANIMATION
// ============================================
function initHeroStatsCounter() {
    const statsSection = document.querySelector('.hero-stats');
    if (!statsSection) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !state.countersAnimated) {
                state.countersAnimated = true;
                animateAllCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    observer.observe(statsSection);
}

function animateAllCounters() {
    // Stat 1: 630 Gbps
    animateCounter(document.querySelector('.hero-stat:nth-child(1) .stat-number'), 630, 0, 2500, false);
    
    // Stat 2: 72 M+
    animateCounter(document.querySelector('.hero-stat:nth-child(2) .stat-number'), 72, 0, 2500, false);
    
    // Stat 3: 99.9%
    animateCounter(document.querySelector('.hero-stat:nth-child(3) .stat-number'), 99.9, 1, 2500, true);
    
    // Stat 4: 240+
    animateCounter(document.querySelector('.hero-stat:nth-child(4) .stat-number'), 240, 0, 2500, false);
}

function animateCounter(element, target, start, duration, isDecimal) {
    if (!element) return;
    
    const startTime = performance.now();
    const startValue = start;
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function - easeOutExpo for premium feel
        const easeOutExpo = 1 - Math.pow(2, -10 * progress);
        
        const current = startValue + (target - startValue) * easeOutExpo;
        
        if (isDecimal) {
            element.textContent = current.toFixed(1);
        } else {
            element.textContent = Math.floor(current);
        }
        
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            // Final value
            if (isDecimal) {
                element.textContent = target.toFixed(1);
            } else {
                element.textContent = target;
            }
            
            // Add glow effect after animation
            element.style.textShadow = '0 0 30px rgba(255, 255, 255, 0.3)';
            setTimeout(() => {
                element.style.textShadow = 'none';
            }, 500);
        }
    }
    
    requestAnimationFrame(update);
}

// ============================================
// PARALLAX EFFECT - PREMIUM
// ============================================
function initParallaxEffect() {
    const glows = document.querySelectorAll('.ambient-glow');
    
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        
        glows.forEach((glow, index) => {
            const speed = 0.1 + (index * 0.05);
            const yPos = scrollY * speed;
            glow.style.transform = `translateY(${yPos}px)`;
        });
    });
}

// ============================================
// BACKGROUND CANVAS
// ============================================
function initBackgroundCanvas() {
    const canvas = document.getElementById('bgCanvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let particles = [];
    let animationId;

    // Resize canvas
    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    // Particle class
    class Particle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 0.5;
            this.speedX = (Math.random() - 0.5) * 0.5;
            this.speedY = (Math.random() - 0.5) * 0.5;
            this.opacity = Math.random() * 0.5 + 0.1;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x < 0 || this.x > canvas.width || 
                this.y < 0 || this.y > canvas.height) {
                this.reset();
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.fill();
        }
    }

    // Initialize particles
    function initParticles() {
        particles = [];
        const particleCount = Math.min(80, Math.floor((canvas.width * canvas.height) / 15000));
        
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    // Draw connections
    function drawConnections() {
        const maxDistance = 100;
        const maxConnections = 3;

        for (let i = 0; i < particles.length; i++) {
            let connections = 0;
            
            for (let j = i + 1; j < particles.length; j++) {
                if (connections >= maxConnections) break;

                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < maxDistance) {
                    const opacity = (1 - distance / maxDistance) * 0.15;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(255, 255, 255, ${opacity})`;
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                    connections++;
                }
            }
        }
    }

    // Animation loop
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        drawConnections();
        animationId = requestAnimationFrame(animate);
    }

    // Initialize
    resize();
    initParticles();
    animate();

    // Handle resize
    window.addEventListener('resize', () => {
        resize();
        initParticles();
    });

    // Pause when tab is hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            cancelAnimationFrame(animationId);
        } else {
            animate();
        }
    });
}

// ============================================
// SUPPORT FUNCTIONS - PREMIUM
// ============================================
function openSupport() {
    const overlay = elements.redirectOverlay;
    if (!overlay) return;

    const overlayText = overlay.querySelector('.overlay-content p');
    const t = translations[currentLang].notifications;
    
    if (overlayText) {
        overlayText.textContent = t.redirecting;
    }

    overlay.classList.add('active');

    setTimeout(() => {
        window.open('https://t.me/KillByte_Support_Bot', '_blank');
        overlay.classList.remove('active');
        
        // Show success notification
        showNotification(t.botOpened, 'success');
    }, 2000);
}

function buyPlan(plan) {
    const t = translations[currentLang].notifications;
    
    // Format plan name for URL
    const planName = plan.replace(/_/g, ' ');
    window.open(`https://t.me/rankflood?text=I'm interested in the ${planName} plan`, '_blank');
    
    showNotification(t.adminContact, 'info');
}

function buyAddon(addon) {
    const t = translations[currentLang].notifications;
    
    window.open(`https://t.me/rankflood?text=I'm interested in the ${addon} addon`, '_blank');
    
    showNotification(t.adminContact, 'info');
}

// ============================================
// ADMIN FUNCTIONS
// ============================================
function openAdminModal() {
    elements.adminModal.classList.add('active');
    document.getElementById('adminPassword').focus();
    
    // Update modal language
    applyAdminLanguage(currentLang);
}

function closeAdminModal() {
    elements.adminModal.classList.remove('active');
    document.getElementById('adminPassword').value = '';
}

function adminLogin() {
    const password = document.getElementById('adminPassword').value;
    const correctPassword = 'killbyte1337';
    const t = translations[currentLang].admin;
    const notT = translations[currentLang].notifications;

    if (password === correctPassword) {
        state.isAdmin = true;
        closeAdminModal();
        elements.adminPanel.classList.add('active');
        
        // Load admin data
        loadAdminData();
        
        // Apply admin language
        applyAdminLanguage(currentLang);
        
        showNotification(t.success, 'success');
    } else {
        showNotification(t.error, 'error');
        document.getElementById('adminPassword').value = '';
    }
}

function adminLogout() {
    state.isAdmin = false;
    elements.adminPanel.classList.remove('active');
    
    const t = translations[currentLang].admin;
    showNotification(t.logout, 'info');
}

function switchAdminTab(tab, element) {
    // Update nav items
    document.querySelectorAll('.admin-nav-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');

    // Update tab content
    document.querySelectorAll('.admin-tab').forEach(tabContent => {
        tabContent.classList.remove('active');
    });
    document.getElementById(`tab-${tab}`).classList.add('active');
}

function loadAdminData() {
    // Simulate loading admin data
    const totalVisitors = document.getElementById('totalVisitors');
    const activeUsers = document.getElementById('activeUsers');
    const serverLoad = document.getElementById('serverLoad');
    const bandwidth = document.getElementById('bandwidth');
    
    if (totalVisitors) totalVisitors.textContent = '1,247';
    if (activeUsers) activeUsers.textContent = '42';
    if (serverLoad) serverLoad.textContent = '23%';
    if (bandwidth) bandwidth.textContent = '1.4 Gbps';
    
    // Update visitor chart
    updateVisitorChart();
}

function updateVisitorChart() {
    const chartBars = document.querySelectorAll('.chart-bar');
    const heights = [45, 65, 35, 75, 55, 85, 70];
    
    chartBars.forEach((bar, index) => {
        if (heights[index]) {
            bar.style.height = `${heights[index]}%`;
        }
    });
}

// ============================================
// NOTIFICATIONS
// ============================================
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    let icon = 'info-circle';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error') icon = 'exclamation-circle';
    
    notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas fa-${icon}"></i>
        </div>
        <div class="notification-text">${message}</div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 24px;
        background: #161616;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        z-index: 9999;
        transform: translateX(150%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
    `;

    document.body.appendChild(notification);

    // Show notification
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
    });

    // Hide after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(150%)';
        setTimeout(() => {
            notification.remove();
        }, 400);
    }, 3000);
}

// ============================================
// LEGAL MODALS
// ============================================
function showTerms() {
    const t = translations[currentLang];
    
    alert(`${t.footer.terms}:\n\n1. Authorized testing only\n2. Compliance with local laws required\n3. No refunds after delivery\n4. Misuse results in termination\n\nContact @rankflood for full terms.`);
}

function showPrivacy() {
    const t = translations[currentLang];
    
    alert(`${t.footer.privacy}:\n\nWe collect minimal data necessary for service operation.\nNo attack target logs are kept.\nPayment data is encrypted.\n\nContact @rankflood for full policy.`);
}

// ============================================
// UTILITY FUNCTIONS
// ============================================
function updateLoginTime() {
    const loginTimeEl = document.getElementById('loginTime');
    if (loginTimeEl) {
        const now = new Date();
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        const day = days[now.getDay()];
        const month = months[now.getMonth()];
        const date = now.getDate();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        loginTimeEl.textContent = `${day} ${month} ${date} ${hours}:${minutes}:${seconds}`;
    }
}

// Update login time every second
setInterval(updateLoginTime, 1000);

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
document.addEventListener('keydown', (e) => {
    // Escape to close modals
    if (e.key === 'Escape') {
        closeAdminModal();
        closeMobileMenu();
        
        if (elements.redirectOverlay && elements.redirectOverlay.classList.contains('active')) {
            elements.redirectOverlay.classList.remove('active');
        }
    }

    // Admin shortcut (Ctrl + Shift + A)
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openAdminModal();
    }
});

// ============================================
// CONSOLE EASTER EGG
// ============================================
console.log('%c KillByte Infrastructure ', 'background: linear-gradient(135deg, #fff, #888); color: #000; font-size: 24px; font-weight: bold; padding: 10px 20px; border-radius: 8px;');
console.log('%c Welcome to the console! ', 'color: #888; font-size: 14px;');
console.log('%c Admin: @rankflood ', 'color: #fff; font-size: 12px;');

// Export functions
window.closeMobileMenu = closeMobileMenu;
window.openSupport = openSupport;
window.buyPlan = buyPlan;
window.buyAddon = buyAddon;
window.showTerms = showTerms;
window.showPrivacy = showPrivacy;
window.setLanguage = setLanguage;
window.openAdminModal = openAdminModal;
window.closeAdminModal = closeAdminModal;
window.adminLogin = adminLogin;
window.adminLogout = adminLogout;
window.switchAdminTab = switchAdminTab;