<?php
/**
 * Miemploya Consult — Admin Dashboard Header
 * Included in all admin pages
 */
$admin_user = currentUser();
?>
<!-- Admin Header Bar -->
<header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/50 px-6 py-3 flex items-center justify-between shrink-0 z-30 shadow-sm">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition-colors">
            <i data-lucide="menu" class="w-5 h-5 text-slate-600"></i>
        </button>
        
        <!-- Page Title -->
        <div>
            <h1 class="text-lg font-bold text-slate-900"><?= $page_title ?? 'Dashboard' ?></h1>
            <p class="text-xs text-slate-400"><?= $page_subtitle ?? 'Miemploya Consult Admin' ?></p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Visit Site -->
        <a href="<?= SITE_URL ?>" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>View Site</span>
        </a>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-all">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white text-xs font-bold"><?= strtoupper(substr($admin_user['name'] ?? 'A', 0, 2)) ?></span>
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-800"><?= $admin_user['name'] ?? 'Admin' ?></p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider"><?= str_replace('_', ' ', $admin_user['role'] ?? 'admin') ?></p>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden sm:block"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50" x-cloak>
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-800"><?= $admin_user['name'] ?? '' ?></p>
                    <p class="text-xs text-slate-400"><?= $admin_user['email'] ?? '' ?></p>
                </div>
                <a href="<?= SITE_URL ?>/admin/index.php" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600 transition-colors">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>
                <div class="border-t border-slate-100 mt-1 pt-1">
                    <a href="<?= SITE_URL ?>/auth/logout.php" onclick="return confirm('Are you sure you want to log out?')" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Collapsed Toolbar (shows when sidebar is hidden) -->
<div id="collapsed-toolbar" class="toolbar-hidden w-full bg-white border-b border-slate-200 flex items-center px-6 shrink-0 shadow-sm z-20">
    <button id="sidebar-expand-btn" class="flex items-center gap-2 py-2 text-slate-500 hover:text-brand-600 transition-colors">
        <i data-lucide="menu" class="w-5 h-5"></i>
        <span class="text-sm font-medium">Show Menu</span>
    </button>
</div>
