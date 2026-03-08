<?php
/**
 * Miemploya Consult — Products Listing
 */
require_once __DIR__ . '/includes/db.php';
$products = db_query("SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC");
$page_title = 'Our Products';
include __DIR__ . '/includes/header.php';
?>

<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-full text-xs font-bold text-purple-600 mb-4">
                <i data-lucide="box" class="w-3.5 h-3.5"></i> Digital Solutions
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Our Digital <span class="gradient-text">Products</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Cutting-edge software platforms developed by Miemploya Consult to streamline business operations across industries.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($products as $i => $product):
                $features = json_decode($product['features'], true) ?: [];
            ?>
            <div class="reveal group" style="transition-delay: <?= $i * 100 ?>ms">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:border-brand-200 transition-all duration-500 h-full flex flex-col">
                    <!-- Header -->
                    <div class="p-8 bg-gradient-to-br relative overflow-hidden" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>12, <?= $product['color_to'] ?>08);">
                        <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl opacity-20" style="background: <?= $product['color_from'] ?>"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-xl mb-5" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                                <i data-lucide="<?= $product['icon_class'] ?: 'box' ?>" class="w-8 h-8 text-white"></i>
                            </div>
                            <h2 class="text-2xl font-black text-slate-900"><?= sanitize($product['name']) ?></h2>
                            <p class="text-sm text-slate-500 mt-1 font-medium"><?= sanitize($product['tagline']) ?></p>
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="p-8 flex-1 flex flex-col">
                        <p class="text-sm text-slate-600 leading-relaxed mb-6"><?= sanitize($product['description']) ?></p>
                        <ul class="space-y-2 mb-8 flex-1">
                            <?php foreach ($features as $feat): ?>
                            <li class="flex items-center gap-2 text-sm text-slate-600">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                <?= sanitize($feat) ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="flex gap-3">
                            <a href="<?= SITE_URL ?>/product.php?slug=<?= urlencode($product['slug']) ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                                <i data-lucide="eye" class="w-4 h-4"></i> Learn More
                            </a>
                            <?php if ($product['external_link'] && $product['external_link'] !== '#'): ?>
                            <a href="<?= $product['external_link'] ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm">
                                <i data-lucide="external-link" class="w-4 h-4"></i> Visit
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
