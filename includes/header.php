<?php
/**
 * Miemploya Consult — Public Site Header
 * Included in all public-facing pages
 */
require_once __DIR__ . '/auth_guard.php';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<script>
// Apply dark mode before paint to prevent flash
(function(){
    const t = localStorage.getItem('theme');
    if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
})();
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Home' ?> — <?= SITE_NAME ?></title>
    <meta name="description" content="<?= $page_description ?? 'Miemploya Consult - Business Consulting & Digital Solutions by Empleo System Limited' ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Glassmorphism Navbar */
        .nav-glass { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .nav-glass.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,0.08); border-bottom: 1px solid rgba(226,232,240,0.5); }
        .dark .nav-glass { background: rgba(15,23,42,0.9); }
        .dark .nav-glass.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,0.3); border-bottom-color: rgba(51,65,85,0.5); }
        .dark .glass-card { background: linear-gradient(135deg, rgba(30,41,59,0.95), rgba(15,23,42,0.9)); }
        .dark .gradient-text { background: linear-gradient(135deg, #818cf8, #a78bfa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .dark .mesh-bg { background: radial-gradient(ellipse 80% 50% at 20% 40%, rgba(99,102,241,0.1), transparent), radial-gradient(ellipse 60% 80% at 80% 20%, rgba(168,85,247,0.08), transparent), radial-gradient(ellipse 50% 60% at 50% 80%, rgba(59,130,246,0.06), transparent); }
        
        /* Mesh Background */
        .mesh-bg {
            background: 
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(99,102,241,0.06), transparent),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(168,85,247,0.05), transparent),
                radial-gradient(ellipse 50% 60% at 50% 80%, rgba(59,130,246,0.04), transparent);
        }
        
        /* Scroll Reveal Animation */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.revealed { opacity: 1; transform: translateY(0); }
        
        /* Glass Card */
        .glass-card { background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(249,250,251,0.9) 100%); backdrop-filter: blur(12px); }
        
        /* Float Animation */
        @keyframes float { 0%,100%{transform:translateY(0px)} 50%{transform:translateY(-20px)} }
        .float-animate { animation: float 6s ease-in-out infinite; }
        
        /* Gradient Text */
        .gradient-text { background: linear-gradient(135deg, #3b82f6, #6366f1, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    </style>
</head>
<body class="font-sans bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-300" x-data="{ mobileMenu: false, darkMode: document.documentElement.classList.contains('dark') }">

<!-- Navigation Bar -->
<nav class="nav-glass fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="mainNav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <!-- Logo -->
            <a href="<?= SITE_URL ?>/index.php" class="flex items-center gap-3 group shrink-0">
                <?php $hasDarkLogo = file_exists(__DIR__ . '/../assets/images/logo_dark.png'); ?>
                <img src="<?= SITE_URL ?>/assets/images/logo.png" alt="<?= SITE_NAME ?>" class="h-10 lg:h-12 w-auto group-hover:scale-105 transition-transform <?= $hasDarkLogo ? 'dark:hidden' : '' ?>">
                <?php if ($hasDarkLogo): ?>
                <img src="<?= SITE_URL ?>/assets/images/logo_dark.png" alt="<?= SITE_NAME ?>" class="h-10 lg:h-12 w-auto group-hover:scale-105 transition-transform hidden dark:block">
                <?php endif; ?>
                <div class="hidden sm:block">
                    <p class="text-lg font-black text-slate-900 dark:text-white tracking-tight leading-tight"><?= SITE_NAME ?></p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium uppercase tracking-widest"><?= COMPANY_TAGLINE ?></p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-1">
                <?php
                $nav_items = [
                    ['Home', 'index', 'home'],
                    ['About', 'about', 'info'],
                    ['Services', 'services', 'briefcase'],
                    ['Products', 'products', 'box'],
                ];
                $dropdown_items = [
                    ['Careers', 'careers', 'briefcase', 'Browse job openings & apply'],
                    ['Training', 'training', 'graduation-cap', 'Programs & conferences'],
                    ['Templates', 'templates', 'file-text', 'Download business templates'],
                    ['News', 'news', 'newspaper', 'Latest updates & media'],
                    ['Videos', 'videos', 'video', 'Watch our video content'],
                    ['Books', 'books', 'book-open', 'Download books & guides'],
                ];
                $dropdown_pages = array_column($dropdown_items, 1);
                foreach ($nav_items as $item):
                    $is_active = $current_page === $item[1];
                ?>
                <a href="<?= SITE_URL ?>/<?= $item[1] ?>.php" 
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?= $is_active ? 'text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                    <?= $item[0] ?>
                </a>
                <?php endforeach; ?>

                <!-- Resources Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button @click="open = !open"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                                   <?= in_array($current_page, $dropdown_pages) ? 'text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                        Resources
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         x-cloak
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-64 bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50">
                        <div class="p-2">
                            <?php foreach ($dropdown_items as $dd):
                                $is_dd_active = $current_page === $dd[1];
                            ?>
                            <a href="<?= SITE_URL ?>/<?= $dd[1] ?>.php"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-200 group
                                      <?= $is_dd_active ? 'text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                                <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand-50 to-purple-50 dark:from-brand-500/10 dark:to-purple-500/10 border border-slate-100 dark:border-slate-600 flex items-center justify-center shrink-0 group-hover:border-brand-200 dark:group-hover:border-brand-500 transition-colors">
                                    <i data-lucide="<?= $dd[2] ?>" class="w-4 h-4 text-brand-500"></i>
                                </span>
                                <div>
                                    <p class="font-semibold leading-tight"><?= $dd[0] ?></p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5"><?= $dd[3] ?></p>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <a href="<?= SITE_URL ?>/contact.php" 
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?= $current_page === 'contact' ? 'text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                    Contact
                </a>
            </div>

            <!-- Theme Toggle + Auth Buttons -->
            <div class="hidden lg:flex items-center gap-3">
                <!-- Dark/Light Toggle -->
                <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle theme">
                    <i data-lucide="sun" class="w-5 h-5 text-amber-500" x-show="darkMode" x-cloak></i>
                    <i data-lucide="moon" class="w-5 h-5 text-slate-500" x-show="!darkMode"></i>
                </button>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                    <a href="<?= SITE_URL ?>/admin/index.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10 hover:bg-brand-100 dark:hover:bg-brand-500/20 rounded-xl transition-all">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/auth/logout.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-red-600 rounded-xl transition-all">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/auth/login.php" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Sign In</a>
                    <a href="<?= SITE_URL ?>/auth/register.php" class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transition-all">Get Started</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <!-- Mobile Theme Toggle -->
            <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                    class="lg:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle theme">
                <i data-lucide="sun" class="w-5 h-5 text-amber-500" x-show="darkMode" x-cloak></i>
                <i data-lucide="moon" class="w-5 h-5 text-slate-500" x-show="!darkMode"></i>
            </button>
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i data-lucide="menu" class="w-6 h-6 text-slate-700 dark:text-slate-300" x-show="!mobileMenu"></i>
                <i data-lucide="x" class="w-6 h-6 text-slate-700 dark:text-slate-300" x-show="mobileMenu" x-cloak></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak
         class="lg:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-slate-100 dark:border-slate-800 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <?php 
            $all_mobile_items = [
                ['Home', 'index', 'home'],
                ['About', 'about', 'info'],
                ['Services', 'services', 'briefcase'],
                ['Products', 'products', 'box'],
                ['Careers', 'careers', 'briefcase'],
                ['Training', 'training', 'graduation-cap'],
                ['Templates', 'templates', 'file-text'],
                ['News', 'news', 'newspaper'],
                ['Videos', 'videos', 'video'],
                ['Books', 'books', 'book-open'],
                ['Contact', 'contact', 'phone'],
            ];
            foreach ($all_mobile_items as $item):
                $is_active = $current_page === $item[1];
            ?>
            <a href="<?= SITE_URL ?>/<?= $item[1] ?>.php" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?= $is_active ? 'text-brand-600 bg-brand-50 dark:text-brand-400 dark:bg-brand-500/10' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                <i data-lucide="<?= $item[2] ?>" class="w-4 h-4"></i>
                <?= $item[0] ?>
            </a>
            <?php endforeach; ?>
            
            <div class="pt-3 border-t border-slate-100 flex gap-2">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= SITE_URL ?>/auth/logout.php" class="flex-1 text-center py-3 rounded-xl text-sm font-semibold text-red-600 bg-red-50">Logout</a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/auth/login.php" class="flex-1 text-center py-3 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100">Sign In</a>
                    <a href="<?= SITE_URL ?>/auth/register.php" class="flex-1 text-center py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-purple-600">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Navbar Scroll Effect -->
<script>
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>

<!-- Top Spacer -->
<div class="h-16 lg:h-20"></div>
