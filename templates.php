<?php
/**
 * Miemploya Consult — Business Templates Library
 */
require_once __DIR__ . '/includes/db.php';
$templates = db_query("SELECT * FROM business_templates WHERE is_active = 1 ORDER BY category, title");
$categories = ['hr' => 'HR Templates', 'business_development' => 'Business Development', 'finance' => 'Finance', 'audit' => 'Audit', 'legal' => 'Legal', 'proposal' => 'Proposals'];
$page_title = 'Business Templates';
include __DIR__ . '/includes/header.php';
?>

<section class="py-20 mesh-bg min-h-screen" x-data="{ activeTab: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 rounded-full text-xs font-bold text-amber-600 mb-4">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Resource Library
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Business <span class="gradient-text">Templates</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Download professional business templates in Word, PDF, and Excel formats.</p>
        </div>

        <!-- Category Tabs -->
        <div class="mb-10 reveal">
            <div class="flex flex-wrap gap-1.5 p-1.5 bg-white rounded-xl border border-slate-200 shadow-sm justify-center">
                <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-brand-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">All</button>
                <?php foreach ($categories as $key => $label): ?>
                <button @click="activeTab = '<?= $key ?>'" :class="activeTab === '<?= $key ?>' ? 'bg-brand-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all"><?= $label ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($templates)): ?>
        <div class="text-center py-16 reveal">
            <div class="w-20 h-20 rounded-3xl bg-amber-50 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="folder-open" class="w-10 h-10 text-amber-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">No Templates Available</h3>
            <p class="text-slate-500">Templates will be added soon. Check back later!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($templates as $i => $tpl):
                $type_icons = ['pdf' => 'file-text', 'doc' => 'file-type', 'docx' => 'file-type', 'xls' => 'table', 'xlsx' => 'table'];
                $type_colors = ['pdf' => 'red', 'doc' => 'blue', 'docx' => 'blue', 'xls' => 'emerald', 'xlsx' => 'emerald'];
                $icon = $type_icons[$tpl['file_type']] ?? 'file';
                $color = $type_colors[$tpl['file_type']] ?? 'slate';
            ?>
            <div class="reveal bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg transition-all"
                 x-show="activeTab === 'all' || activeTab === '<?= $tpl['category'] ?>'"
                 style="transition-delay: <?= $i * 60 ?>ms">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-<?= $color ?>-50 flex items-center justify-center shrink-0">
                        <i data-lucide="<?= $icon ?>" class="w-6 h-6 text-<?= $color ?>-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-900 mb-1"><?= sanitize($tpl['title']) ?></h3>
                        <p class="text-xs text-slate-500 mb-3 line-clamp-2"><?= sanitize($tpl['description']) ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-<?= $color ?>-500 bg-<?= $color ?>-50 px-2 py-0.5 rounded"><?= strtoupper($tpl['file_type']) ?></span>
                            <a href="<?= SITE_URL ?>/api.php?action=download_template&id=<?= $tpl['id'] ?>" 
                               class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                <span class="text-slate-400">(<?= $tpl['download_count'] ?>)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
