<?php
/**
 * Miemploya Consult — Admin Dashboard
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);

// Dashboard Stats
$stats = [
    'vacancies'    => db_value("SELECT COUNT(*) FROM job_vacancies WHERE is_active = 1"),
    'applicants'   => db_value("SELECT COUNT(*) FROM job_applications"),
    'templates'    => db_value("SELECT COUNT(*) FROM business_templates WHERE is_active = 1"),
    'downloads'    => db_value("SELECT SUM(download_count) FROM business_templates") ?: 0,
    'training'     => db_value("SELECT COUNT(*) FROM training_programs WHERE is_active = 1"),
    'registrations'=> db_value("SELECT COUNT(*) FROM training_registrations"),
    'consulting'   => db_value("SELECT COUNT(*) FROM consulting_requests"),
    'new_consulting'=> db_value("SELECT COUNT(*) FROM consulting_requests WHERE status = 'new'"),
    'videos'       => db_value("SELECT COUNT(*) FROM videos"),
    'news'         => db_value("SELECT COUNT(*) FROM news_posts"),
    'users'        => db_value("SELECT COUNT(*) FROM users"),
];

$recent_applications = db_query("SELECT a.*, v.title as vacancy_title FROM job_applications a LEFT JOIN job_vacancies v ON a.vacancy_id = v.id ORDER BY a.created_at DESC LIMIT 5");
$recent_requests = db_query("SELECT * FROM consulting_requests ORDER BY created_at DESC LIMIT 5");

$page_title = 'Dashboard';
$page_subtitle = 'Platform Overview';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                colors: { brand: {50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81'} }
            }}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <style>[x-cloak]{display:none !important;}</style>
</head>
<body class="font-sans bg-slate-50 text-slate-900 antialiased">
<div class="flex h-screen w-full">
    <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col h-full overflow-hidden w-full relative">
        <?php include __DIR__ . '/../includes/admin_header.php'; ?>
        
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-brand-500 to-purple-600 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black mb-2">Welcome back, <?= sanitize($admin_user['name'] ?? 'Admin') ?> 👋</h2>
                        <p class="text-white/80 text-sm">Here's what's happening on your platform today.</p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                    <?php
                    $stat_cards = [
                        ['Job Vacancies', $stats['vacancies'], 'briefcase', 'from-emerald-50 to-green-50', 'emerald', 'vacancies.php'],
                        ['Applicants', $stats['applicants'], 'users', 'from-blue-50 to-indigo-50', 'blue', 'applicants.php'],
                        ['Templates', $stats['templates'], 'file-text', 'from-amber-50 to-orange-50', 'amber', 'templates.php'],
                        ['Training', $stats['training'], 'graduation-cap', 'from-violet-50 to-purple-50', 'violet', 'training.php'],
                        ['Consulting', $stats['new_consulting'], 'message-square', 'from-teal-50 to-emerald-50', 'teal', 'consulting.php'],
                    ];
                    foreach ($stat_cards as $sc):
                    ?>
                    <a href="<?= SITE_URL ?>/admin/<?= $sc[5] ?>" class="group bg-gradient-to-br <?= $sc[3] ?> rounded-2xl p-5 border border-<?= $sc[4] ?>-100 hover:shadow-lg hover:shadow-<?= $sc[4] ?>-500/10 hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-<?= $sc[4] ?>-500 to-<?= $sc[4] ?>-600 flex items-center justify-center shadow-md">
                                <i data-lucide="<?= $sc[2] ?>" class="w-5 h-5 text-white"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-slate-900"><?= $sc[1] ?></p>
                        <p class="text-xs text-slate-500 font-medium mt-1"><?= $sc[0] ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Secondary Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <?php
                    $sec_stats = [
                        ['Downloads', $stats['downloads'], 'download'],
                        ['Registrations', $stats['registrations'], 'clipboard-check'],
                        ['Videos', $stats['videos'], 'video'],
                        ['Users', $stats['users'], 'shield'],
                    ];
                    foreach ($sec_stats as $ss):
                    ?>
                    <div class="bg-white rounded-xl p-4 border border-slate-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                            <i data-lucide="<?= $ss[2] ?>" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-900"><?= $ss[1] ?></p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold"><?= $ss[0] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Applications -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="users" class="w-4 h-4 text-blue-500"></i> Recent Applications</h3>
                            <a href="<?= SITE_URL ?>/admin/applicants.php" class="text-xs font-bold text-brand-600 hover:text-brand-700">View All →</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <?php if (empty($recent_applications)): ?>
                            <div class="p-6 text-center text-sm text-slate-400">No applications yet.</div>
                            <?php else: foreach ($recent_applications as $app): ?>
                            <div class="px-6 py-3 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600">
                                    <?= strtoupper(substr($app['full_name'], 0, 2)) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate"><?= sanitize($app['full_name']) ?></p>
                                    <p class="text-[10px] text-slate-400 truncate"><?= sanitize($app['vacancy_title'] ?? 'Unknown Position') ?></p>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold <?= $app['status'] === 'new' ? 'bg-blue-50 text-blue-600' : ($app['status'] === 'shortlisted' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500') ?>">
                                    <?= ucfirst($app['status']) ?>
                                </span>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>

                    <!-- Recent Consulting Requests -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="message-square" class="w-4 h-4 text-teal-500"></i> Consulting Requests</h3>
                            <a href="<?= SITE_URL ?>/admin/consulting.php" class="text-xs font-bold text-brand-600 hover:text-brand-700">View All →</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <?php if (empty($recent_requests)): ?>
                            <div class="p-6 text-center text-sm text-slate-400">No requests yet.</div>
                            <?php else: foreach ($recent_requests as $req): ?>
                            <div class="px-6 py-3 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-xs font-bold text-teal-600">
                                    <?= strtoupper(substr($req['company_name'], 0, 2)) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate"><?= sanitize($req['company_name']) ?></p>
                                    <p class="text-[10px] text-slate-400 truncate"><?= sanitize($req['contact_person']) ?></p>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold <?= $req['status'] === 'new' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' ?>">
                                    <?= ucfirst($req['status']) ?>
                                </span>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../includes/admin_scripts.php'; ?>
</body>
</html>
