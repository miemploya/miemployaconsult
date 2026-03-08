<?php
/**
 * Miemploya Consult — Careers / Job Vacancies
 */
require_once __DIR__ . '/includes/db.php';
$vacancies = db_query("SELECT * FROM job_vacancies WHERE is_active = 1 ORDER BY created_at DESC");
$page_title = 'Careers';
include __DIR__ . '/includes/header.php';
?>

<section class="py-20 mesh-bg min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-full text-xs font-bold text-emerald-600 mb-4">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Career Portal
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Job <span class="gradient-text">Vacancies</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Explore career opportunities and take the next step in your professional journey.</p>
        </div>

        <?php if (empty($vacancies)): ?>
        <div class="text-center py-16 reveal">
            <div class="w-20 h-20 rounded-3xl bg-slate-100 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">No Open Vacancies</h3>
            <p class="text-slate-500">Check back later for new opportunities, or submit your CV for future consideration.</p>
        </div>
        <?php else: ?>
        <div class="space-y-6" x-data="{ expanded: null }">
            <?php foreach ($vacancies as $i => $job): ?>
            <div class="reveal bg-white rounded-2xl border border-slate-100 hover:shadow-xl hover:border-brand-200 transition-all overflow-hidden" style="transition-delay: <?= $i * 80 ?>ms">
                <div class="p-6 cursor-pointer" @click="expanded = expanded === <?= $job['id'] ?> ? null : <?= $job['id'] ?>">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-lg shrink-0">
                                <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900"><?= sanitize($job['title']) ?></h3>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1"><i data-lucide="building" class="w-3 h-3"></i> <?= sanitize($job['company_name']) ?></span>
                                    <?php if ($job['location']): ?>
                                    <span class="inline-flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> <?= sanitize($job['location']) ?></span>
                                    <?php endif; ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full font-semibold"><?= ucwords(str_replace('_', ' ', $job['employment_type'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <?php if ($job['deadline']): ?>
                            <span class="text-xs text-slate-400"><i data-lucide="clock" class="w-3 h-3 inline"></i> Deadline: <?= format_date($job['deadline']) ?></span>
                            <?php endif; ?>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="expanded === <?= $job['id'] ?> ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                </div>

                <!-- Expanded Content -->
                <div x-show="expanded === <?= $job['id'] ?>" x-transition x-cloak class="border-t border-slate-100 px-6 pb-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-6">
                        <div>
                            <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2"><i data-lucide="file-text" class="w-4 h-4 text-brand-500"></i> Job Description</h4>
                            <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line"><?= sanitize($job['description']) ?></div>
                        </div>
                        <?php if ($job['requirements']): ?>
                        <div>
                            <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2"><i data-lucide="list-checks" class="w-4 h-4 text-emerald-500"></i> Requirements</h4>
                            <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line"><?= sanitize($job['requirements']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="<?= SITE_URL ?>/apply.php?vacancy=<?= $job['id'] ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-105 transition-all text-sm">
                            <i data-lucide="send" class="w-4 h-4"></i> Apply Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
