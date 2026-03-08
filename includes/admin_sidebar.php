<?php
/**
 * Miemploya Consult — Admin Dashboard Sidebar
 * Centralized navigation for all admin pages
 */
$current_admin_page = basename($_SERVER['PHP_SELF'], '.php');
$admin_user = currentUser();

$nav_sections = [
    'Main' => [
        ['Dashboard', 'index', 'layout-dashboard', 'from-blue-500 to-indigo-600', 'shadow-blue-500/30'],
    ],
    'Content Management' => [
        ['Job Vacancies', 'vacancies', 'briefcase', 'from-emerald-500 to-teal-600', 'shadow-emerald-500/30'],
        ['Applicants', 'applicants', 'users', 'from-cyan-500 to-blue-600', 'shadow-cyan-500/30'],
        ['Training', 'training', 'graduation-cap', 'from-violet-500 to-purple-600', 'shadow-violet-500/30'],
        ['Registrations', 'registrations', 'clipboard-check', 'from-fuchsia-500 to-pink-600', 'shadow-fuchsia-500/30'],
        ['Q. Requests', 'quarterly_requests', 'calendar', 'from-lime-500 to-green-600', 'shadow-lime-500/30'],
        ['Templates', 'templates', 'file-text', 'from-amber-500 to-orange-600', 'shadow-amber-500/30'],
    ],
    'Media & News' => [
        ['Videos', 'videos', 'video', 'from-red-500 to-rose-600', 'shadow-red-500/30'],
        ['News Posts', 'news', 'newspaper', 'from-sky-500 to-blue-600', 'shadow-sky-500/30'],
        ['Posters & Flyers', 'posters', 'image', 'from-pink-500 to-rose-600', 'shadow-pink-500/30'],
        ['Media Bucket', 'media', 'folder-open', 'from-amber-500 to-yellow-600', 'shadow-amber-500/30'],
    ],
    'Products & Services' => [
        ['Products', 'products', 'box', 'from-indigo-500 to-violet-600', 'shadow-indigo-500/30'],
        ['Consulting', 'consulting', 'message-square', 'from-teal-500 to-emerald-600', 'shadow-teal-500/30'],
        ['Homepage Pins', 'pins', 'pin', 'from-orange-500 to-red-600', 'shadow-orange-500/30'],
    ],
    'Administration' => [
        ['Users', 'users', 'shield', 'from-slate-500 to-slate-700', 'shadow-slate-500/30'],
        ['Settings', 'settings', 'settings', 'from-gray-500 to-gray-700', 'shadow-gray-500/30'],
    ],
];
?>

<!-- Admin Sidebar -->
<aside id="sidebar" class="w-64 bg-white border-r border-slate-200/50 flex flex-col h-full shrink-0 sidebar-transition overflow-hidden z-40
                           lg:relative fixed inset-y-0 left-0 lg:translate-x-0 -translate-x-full">
    
    <!-- Brand Card -->
    <div class="p-4 border-b border-slate-100/50">
        <a href="<?= SITE_URL ?>/admin/index.php" class="relative group w-full flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100/50 border border-slate-200/50 hover:border-brand-300 transition-all shadow-sm">
            <img src="<?= SITE_URL ?>/assets/images/logo.png" alt="<?= SITE_NAME ?>" class="w-10 h-10 rounded-lg object-contain">
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Admin Panel</p>
                <p class="text-sm font-bold text-slate-800 truncate"><?= SITE_NAME ?></p>
            </div>
            <!-- Shimmer Overlay -->
            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6" style="scrollbar-width:thin; scrollbar-color: rgba(148,163,184,0.3) transparent;">
        <?php foreach ($nav_sections as $section => $items): ?>
        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400"><?= $section ?></p>
            <div class="space-y-1">
                <?php foreach ($items as $item):
                    $is_active = $current_admin_page === $item[1];
                ?>
                <a href="<?= SITE_URL ?>/admin/<?= $item[1] ?>.php" 
                   class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 hover:translate-x-0.5
                          <?= $is_active 
                              ? 'text-brand-700 bg-gradient-to-r from-brand-50 to-brand-100/50 border-l-2 border-brand-500 shadow-sm' 
                              : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50 border-l-2 border-transparent' ?>">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br <?= $item[3] ?> flex items-center justify-center shadow-md <?= $item[4] ?> group-hover:shadow-lg transition-shadow">
                        <i data-lucide="<?= $item[2] ?>" class="w-4 h-4 text-white"></i>
                    </span>
                    <span class="font-semibold"><?= $item[0] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </nav>

    <!-- Sidebar Footer -->
    <div class="py-3 px-4 border-t border-slate-200/50 bg-gradient-to-r from-slate-50 to-transparent">
        <!-- Logout -->
        <a href="<?= SITE_URL ?>/auth/logout.php" onclick="return confirm('Are you sure you want to log out?')"
           class="group flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all">
            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-md shadow-red-500/30">
                <i data-lucide="log-out" class="w-4 h-4 text-white"></i>
            </span>
            <span class="font-semibold">Logout</span>
        </a>
        <div class="flex justify-between items-center mt-3 px-3">
            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">v1.0</span>
            <button id="sidebar-collapse-btn" class="hidden lg:block p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-all hover:scale-110">
                <i data-lucide="chevrons-left" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden lg:hidden"></div>
