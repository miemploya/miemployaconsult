<?php
/**
 * Miemploya Consult — About Page
 */
require_once __DIR__ . '/includes/db.php';
$page_title = 'About Us';
$page_description = 'Learn about Miemploya Consult, the consulting and technology solutions arm of Empleo System Limited.';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="relative py-20 bg-gradient-to-br from-brand-500/5 via-purple-500/5 to-transparent mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 rounded-full text-xs font-bold text-brand-600 mb-6">
                <i data-lucide="info" class="w-3.5 h-3.5"></i> About Us
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-6">About <span class="gradient-text">Miemploya Consult</span></h1>
            <p class="text-lg text-slate-600 leading-relaxed">The consulting and technology solutions arm of Empleo System Limited, designed to function as a business consulting, technology solutions, and professional development ecosystem.</p>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="reveal">
                <div class="bg-gradient-to-br from-brand-50 to-indigo-50 rounded-3xl p-8 border border-brand-100 h-full">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-brand-500/30 mb-6">
                        <i data-lucide="target" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">Our Mission</h2>
                    <p class="text-slate-600 leading-relaxed">To provide world-class consulting services, recruitment opportunities, digital resources, and technology platforms that improve organizational performance, empower professionals, and drive business growth across Africa and beyond.</p>
                </div>
            </div>
            <div class="reveal">
                <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-3xl p-8 border border-purple-100 h-full">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center shadow-lg shadow-purple-500/30 mb-6">
                        <i data-lucide="eye" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">Our Vision</h2>
                    <p class="text-slate-600 leading-relaxed">To be the leading integrated business consulting and technology solutions platform in Africa, serving as the central hub where businesses, professionals, and institutions access tools and services that transform organizational performance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Offer -->
<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl font-black text-slate-900 mb-4">What Makes Us <span class="gradient-text">Different</span></h2>
            <p class="text-slate-500 max-w-2xl mx-auto">We integrate consulting services, recruitment, digital resources, and technology platforms into a single unified ecosystem.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $values = [
                ['Consulting Excellence', 'Strategic advisory across HR, payroll, audit, and business development.', 'award', 'from-amber-500 to-orange-600'],
                ['Digital Innovation', 'Proprietary software platforms solving real business challenges.', 'cpu', 'from-blue-500 to-indigo-600'],
                ['Talent Development', 'Professional training programs, workshops, and conferences.', 'graduation-cap', 'from-emerald-500 to-teal-600'],
                ['Career Growth', 'Job opportunities and recruitment services for professionals.', 'rocket', 'from-purple-500 to-violet-600'],
            ];
            foreach ($values as $i => $val):
            ?>
            <div class="reveal text-center p-8 bg-white rounded-2xl border border-slate-100 hover:shadow-lg transition-all" style="transition-delay: <?= $i * 80 ?>ms">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?= $val[3] ?> flex items-center justify-center shadow-lg mx-auto mb-5">
                    <i data-lucide="<?= $val[2] ?>" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2"><?= $val[0] ?></h3>
                <p class="text-sm text-slate-500"><?= $val[1] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ecosystem -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center reveal">
            <h2 class="text-3xl font-black text-slate-900 mb-6">Part of the <span class="gradient-text">Empleo System</span></h2>
            <p class="text-slate-600 leading-relaxed mb-8">Miemploya Consult operates as the consulting and technology solutions arm of <strong>Empleo System Limited</strong>. Our ecosystem includes multiple digital products designed to serve businesses across various sectors — from payroll management and auditing to digital learning and workforce monitoring.</p>
            <a href="<?= SITE_URL ?>/products.php" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-105 transition-all">
                <i data-lucide="box" class="w-5 h-5"></i>
                Explore Our Products
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
