<?php
/**
 * Miemploya Consult — Product Detail Page
 */
require_once __DIR__ . '/includes/db.php';
$slug = sanitize($_GET['slug'] ?? '');
if (!$slug) { redirect(SITE_URL . '/products.php'); }
$product = db_row("SELECT * FROM products WHERE slug = ? AND is_active = 1", [$slug]);
if (!$product) { redirect(SITE_URL . '/products.php'); }

$features = json_decode($product['features'], true) ?: [];
$page_title = $product['name'];
include __DIR__ . '/includes/header.php';
?>

<!-- Product Hero -->
<section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>08, <?= $product['color_to'] ?>05);">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 right-20 w-72 h-72 rounded-full blur-3xl opacity-20" style="background: <?= $product['color_from'] ?>"></div>
        <div class="absolute bottom-10 left-20 w-96 h-96 rounded-full blur-3xl opacity-10" style="background: <?= $product['color_to'] ?>"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="<?= SITE_URL ?>/products.php" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-brand-600 mb-8 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Products
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center shadow-2xl mb-6" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                    <i data-lucide="<?= $product['icon_class'] ?: 'box' ?>" class="w-10 h-10 text-white"></i>
                </div>
                <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4"><?= sanitize($product['name']) ?></h1>
                <p class="text-xl text-slate-500 font-medium mb-6"><?= sanitize($product['tagline']) ?></p>
                <p class="text-slate-600 leading-relaxed mb-8"><?= sanitize($product['description']) ?></p>
                
                <div class="flex flex-wrap gap-4">
                    <?php if ($product['external_link'] && $product['external_link'] !== '#'): ?>
                    <a href="<?= $product['external_link'] ?>" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 text-white font-bold rounded-2xl shadow-xl hover:scale-105 transition-all" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                        <i data-lucide="external-link" class="w-5 h-5"></i>
                        Visit Platform
                    </a>
                    <?php endif; ?>
                    <?php if ($product['demo_video_url']): ?>
                    <a href="<?= $product['demo_video_url'] ?>" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl shadow-lg border border-slate-200 hover:shadow-xl transition-all">
                        <i data-lucide="play-circle" class="w-5 h-5"></i>
                        Watch Demo
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Visual Placeholder -->
            <div class="hidden lg:block">
                <div class="bg-white/60 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                                <i data-lucide="<?= $product['icon_class'] ?: 'box' ?>" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="h-3 w-32 bg-slate-200 rounded-full"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <?php for ($j = 0; $j < 4; $j++): ?>
                            <div class="p-4 rounded-xl border border-slate-100" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>08, <?= $product['color_to'] ?>04);">
                                <div class="h-2 w-16 rounded-full mb-2" style="background: <?= $product['color_from'] ?>30;"></div>
                                <div class="h-6 w-full rounded-lg" style="background: <?= $product['color_from'] ?>10;"></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<?php if (!empty($features)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl font-black text-slate-900 mb-4">Key <span class="gradient-text">Features</span></h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($features as $i => $feat): ?>
            <div class="reveal flex items-start gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-lg transition-all" style="transition-delay: <?= $i * 60 ?>ms">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-md" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                    <i data-lucide="check" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900"><?= sanitize($feat) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
