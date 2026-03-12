<?php
/**
 * Miemploya Consult — Homepage
 */
require_once __DIR__ . '/includes/db.php';

// Fetch dynamic content
$featured_products = db_query("SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC");
$latest_news = db_query("SELECT * FROM news_posts ORDER BY publish_date DESC, created_at DESC LIMIT 3");
$featured_videos = db_query("SELECT * FROM videos ORDER BY publish_date DESC, created_at DESC LIMIT 3");
$upcoming_training = db_query("SELECT * FROM training_programs WHERE is_active = 1 AND program_date >= CURDATE() ORDER BY program_date ASC LIMIT 4");
$open_vacancies_count = db_value("SELECT COUNT(*) FROM job_vacancies WHERE is_active = 1");

$page_title = 'Home';
$page_description = 'Miemploya Consult — Business Consulting, Digital Solutions & Professional Development by Empleo System Limited.';
include __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO SECTION
     ═══════════════════════════════════════════════════════════ -->
<section class="relative overflow-hidden hero-color-cycle">
    <style>
        @keyframes heroBgCycle {
            0%, 10%   { background-color: rgba(99,102,241,0.04); }
            33%, 43%  { background-color: rgba(168,85,247,0.05); }
            66%, 76%  { background-color: rgba(59,130,246,0.04); }
            100%      { background-color: rgba(99,102,241,0.04); }
        }
        .dark .hero-color-cycle {
            animation-name: heroBgCycleDark !important;
        }
        @keyframes heroBgCycleDark {
            0%, 10%   { background-color: rgba(99,102,241,0.08); }
            33%, 43%  { background-color: rgba(168,85,247,0.08); }
            66%, 76%  { background-color: rgba(59,130,246,0.07); }
            100%      { background-color: rgba(99,102,241,0.08); }
        }
        .hero-color-cycle { animation: heroBgCycle 30s ease-in-out infinite; }
        @keyframes watermarkDrift {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }
    </style>

    <!-- Watermark Rain — "Miemploya Consult" repeated -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden select-none" aria-hidden="true">
        <div class="absolute inset-0 w-full" style="animation: watermarkDrift 40s linear infinite;">
            <?php
            $watermarkText = 'Miemploya Consult';
            $positions = [
                ['top'=>'-5%','left'=>'5%','rotate'=>'-15','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'5%','left'=>'55%','rotate'=>'10','size'=>'2xl','opacity'=>'0.025'],
                ['top'=>'15%','left'=>'20%','rotate'=>'-8','size'=>'4xl','opacity'=>'0.035'],
                ['top'=>'12%','left'=>'75%','rotate'=>'12','size'=>'xl','opacity'=>'0.02'],
                ['top'=>'25%','left'=>'-5%','rotate'=>'5','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'30%','left'=>'40%','rotate'=>'-12','size'=>'5xl','opacity'=>'0.04'],
                ['top'=>'35%','left'=>'80%','rotate'=>'8','size'=>'2xl','opacity'=>'0.025'],
                ['top'=>'45%','left'=>'10%','rotate'=>'-5','size'=>'4xl','opacity'=>'0.035'],
                ['top'=>'48%','left'=>'60%','rotate'=>'15','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'55%','left'=>'30%','rotate'=>'-10','size'=>'2xl','opacity'=>'0.02'],
                ['top'=>'60%','left'=>'70%','rotate'=>'6','size'=>'4xl','opacity'=>'0.035'],
                ['top'=>'65%','left'=>'0%','rotate'=>'-8','size'=>'3xl','opacity'=>'0.025'],
                ['top'=>'70%','left'=>'50%','rotate'=>'12','size'=>'xl','opacity'=>'0.02'],
                ['top'=>'78%','left'=>'15%','rotate'=>'-15','size'=>'5xl','opacity'=>'0.04'],
                ['top'=>'80%','left'=>'65%','rotate'=>'5','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'88%','left'=>'35%','rotate'=>'-6','size'=>'2xl','opacity'=>'0.025'],
                ['top'=>'92%','left'=>'85%','rotate'=>'10','size'=>'4xl','opacity'=>'0.035'],
                ['top'=>'100%','left'=>'5%','rotate'=>'-12','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'108%','left'=>'50%','rotate'=>'8','size'=>'5xl','opacity'=>'0.04'],
                ['top'=>'115%','left'=>'25%','rotate'=>'-5','size'=>'2xl','opacity'=>'0.025'],
                ['top'=>'120%','left'=>'70%','rotate'=>'12','size'=>'4xl','opacity'=>'0.035'],
                ['top'=>'130%','left'=>'10%','rotate'=>'-10','size'=>'3xl','opacity'=>'0.03'],
                ['top'=>'140%','left'=>'55%','rotate'=>'6','size'=>'xl','opacity'=>'0.02'],
                ['top'=>'150%','left'=>'30%','rotate'=>'-8','size'=>'5xl','opacity'=>'0.04'],
            ];
            foreach ($positions as $p):
            ?>
            <span class="absolute font-black text-<?= $p['size'] ?> text-slate-900 dark:text-white whitespace-nowrap" 
                  style="top:<?= $p['top'] ?>;left:<?= $p['left'] ?>;transform:rotate(<?= $p['rotate'] ?>deg);opacity:<?= $p['opacity'] ?>;">
                <?= $watermarkText ?>
            </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Floating Decorations -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-brand-400/10 rounded-full blur-3xl float-animate"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-400/8 rounded-full blur-3xl float-animate" style="animation-delay:2s"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-blue-400/6 rounded-full blur-3xl float-animate" style="animation-delay:4s"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 dark:bg-brand-500/10 border border-brand-200 dark:border-brand-500/20 rounded-full text-xs font-bold text-brand-600 dark:text-brand-400 mb-6">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    Empleo System Limited
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight mb-6">
                    Business Consulting & 
                    <span class="gradient-text">Digital Solutions</span>
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed mb-8 max-w-lg">
                    Your central hub for consulting services, recruitment opportunities, professional training, business resources, and cutting-edge technology products.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?= SITE_URL ?>/services.php" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-105 transition-all">
                        <i data-lucide="briefcase" class="w-5 h-5"></i>
                        Our Services
                    </a>
                    <a href="<?= SITE_URL ?>/products.php" class="inline-flex items-center gap-2 px-8 py-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 hover:border-brand-300 dark:hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 hover:shadow-xl transition-all">
                        <i data-lucide="box" class="w-5 h-5"></i>
                        Explore Products
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12">
                    <div>
                        <p class="text-3xl font-black gradient-text"><?= count($featured_products) ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Digital Products</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black gradient-text"><?= $open_vacancies_count ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Open Vacancies</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black gradient-text">6+</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Core Services</p>
                    </div>
                </div>
            </div>

            <!-- Right — Product Infographic Slideshow -->
            <div class="hidden lg:block relative" x-data="heroSlider()" x-init="start()">
                <div class="relative bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 dark:border-slate-700/50 overflow-hidden transform rotate-1 hover:rotate-0 transition-transform duration-500">
                    <?php
                    $heroSlideDir = __DIR__ . '/assets/images/hero/';
                    $heroSlides = [];
                    if (is_dir($heroSlideDir)) {
                        foreach (glob($heroSlideDir . '*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) as $f) {
                            $heroSlides[] = basename($f);
                        }
                        sort($heroSlides);
                        $heroSlides = array_slice($heroSlides, 0, 10);
                    }
                    ?>
                    <?php if (!empty($heroSlides)): ?>
                    <div class="relative aspect-[4/3] w-full">
                        <?php foreach ($heroSlides as $si => $hs): ?>
                        <img src="<?= SITE_URL ?>/assets/images/hero/<?= $hs ?>" 
                             alt="Product Preview <?= $si + 1 ?>"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700"
                             :class="current === <?= $si ?> ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                             loading="<?= $si === 0 ? 'eager' : 'lazy' ?>">
                        <?php endforeach; ?>
                        <!-- Slide dots -->
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
                            <?php foreach ($heroSlides as $si => $hs): ?>
                            <button @click="goTo(<?= $si ?>)" class="w-2 h-2 rounded-full transition-all duration-300" :class="current === <?= $si ?> ? 'bg-white w-6 shadow' : 'bg-white/40 hover:bg-white/70'"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Fallback placeholder when no slides uploaded -->
                    <div class="aspect-[4/3] w-full bg-gradient-to-br from-brand-50 via-purple-50 to-indigo-50 flex items-center justify-center">
                        <div class="text-center p-8">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i data-lucide="monitor" class="w-8 h-8 text-white"></i>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Product Showcase</p>
                            <p class="text-slate-300 text-xs mt-1">Upload slides via Admin Panel</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- Floating Badge -->
                <div class="absolute -bottom-4 -left-4 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 px-5 py-3 flex items-center gap-3 float-animate" style="animation-delay:1s">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">Trusted Platform</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Enterprise Grade Security</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
function heroSlider() {
    return {
        current: 0, total: <?= !empty($heroSlides) ? count($heroSlides) : 1 ?>, timer: null,
        start() { this.timer = setInterval(() => { this.current = (this.current + 1) % this.total; }, 4000); },
        goTo(i) { this.current = i; clearInterval(this.timer); this.start(); }
    }
}
</script>

<!-- ═══════════════════════════════════════════════════════════
     SERVICES OVERVIEW
     ═══════════════════════════════════════════════════════════ -->
<section class="py-20 bg-white dark:bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 dark:bg-brand-500/10 rounded-full text-xs font-bold text-brand-600 dark:text-brand-400 mb-4">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> What We Do
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mb-4">Consulting & Advisory <span class="gradient-text">Services</span></h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">We help organizations optimize operations, manage workforce, and drive business growth through strategic advisory and technology solutions.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php
            $services = [
                ['Human Resource Consulting', 'Strategic HR advisory for recruitment, talent management, employee relations, and organizational development.', 'users', 'from-blue-500 to-indigo-600', 'blue'],
                ['Payroll Management Advisory', 'Expert guidance on payroll processing, statutory compliance, tax optimization, and compensation structuring.', 'banknote', 'from-emerald-500 to-teal-600', 'emerald'],
                ['Business Development', 'Strategic planning, market analysis, partnership development, and revenue growth strategies for your organization.', 'trending-up', 'from-purple-500 to-violet-600', 'purple'],
                ['Audit & Compliance', 'Comprehensive audit support, regulatory compliance monitoring, and risk assessment for business operations.', 'shield-check', 'from-amber-500 to-orange-600', 'amber'],
                ['Process Optimization', 'Business process re-engineering, workflow automation, and operational efficiency improvement.', 'settings', 'from-cyan-500 to-blue-600', 'cyan'],
                ['Workforce Monitoring', 'Employee monitoring systems, productivity tracking, attendance management, and performance analytics.', 'activity', 'from-rose-500 to-pink-600', 'rose'],
                ['Digital Business Solutions', 'Bespoke software design, development, and deployment tailored to your unique business requirements — from enterprise platforms to mobile-ready tools.', 'code-2', 'from-violet-500 to-fuchsia-600', 'violet'],
            ];
            foreach ($services as $i => $svc):
            ?>
            <style>
                @keyframes svcCardBg {
                    0%, 48%  { background-color: rgba(99,102,241,0.04); }
                    50%, 98% { background-color: rgba(168,85,247,0.05); }
                    100%     { background-color: rgba(99,102,241,0.04); }
                }
                .dark .svc-card-cycle { animation-name: svcCardBgDark !important; }
                @keyframes svcCardBgDark {
                    0%, 48%  { background-color: rgba(99,102,241,0.08); }
                    50%, 98% { background-color: rgba(168,85,247,0.08); }
                    100%     { background-color: rgba(99,102,241,0.08); }
                }
                .svc-card-cycle { animation: svcCardBg 30s ease-in-out infinite; }
                .svc-card:hover .svc-icon { transform: scale(1.15) rotate(5deg); }
                .svc-card::after {
                    content: '';
                    position: absolute; inset: 0; border-radius: 1rem;
                    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.15) 45%, rgba(255,255,255,0.25) 50%, rgba(255,255,255,0.15) 55%, transparent 60%);
                    background-size: 250% 100%;
                    background-position: 100% 0;
                    transition: background-position 0.6s ease;
                    pointer-events: none;
                }
                .svc-card:hover::after { background-position: -50% 0; }
                .svc-card:hover { transform: translateY(-6px); }
            </style>
            <div class="svc-card svc-card-cycle reveal group relative rounded-2xl p-6 border border-slate-100 dark:border-slate-700 hover:border-<?= $svc[4] ?>-300 dark:hover:border-<?= $svc[4] ?>-500/40 hover:shadow-2xl hover:shadow-<?= $svc[4] ?>-500/15 transition-all duration-500 cursor-default overflow-hidden" style="transition-delay: <?= $i * 80 ?>ms; animation-delay: <?= $i * 2 ?>s">
                <div class="svc-icon w-14 h-14 rounded-2xl bg-gradient-to-br <?= $svc[3] ?> flex items-center justify-center shadow-lg shadow-<?= $svc[4] ?>-500/30 mb-5 transition-all duration-500">
                    <i data-lucide="<?= $svc[2] ?>" class="w-7 h-7 text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-<?= $svc[4] ?>-600 dark:group-hover:text-<?= $svc[4] ?>-400 transition-colors"><?= $svc[0] ?></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed"><?= $svc[1] ?></p>
                <!-- Bottom accent bar on hover -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r <?= $svc[3] ?> scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left rounded-b-2xl"></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-10 reveal">
            <a href="<?= SITE_URL ?>/services.php" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors shadow-lg">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                Request a Consultation
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     FEATURED PRODUCTS
     ═══════════════════════════════════════════════════════════ -->
<?php if (!empty($featured_products)): ?>
<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 dark:bg-purple-500/10 rounded-full text-xs font-bold text-purple-600 dark:text-purple-400 mb-4">
                <i data-lucide="box" class="w-3.5 h-3.5"></i> Our Products
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mb-4">Digital <span class="gradient-text">Solutions</span></h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">Cutting-edge software platforms designed to streamline operations and drive organizational efficiency.</p>
        </div>

        <style>
            .flip-container { perspective: 1200px; }
            .flip-card { position: relative; transition: transform 0.8s cubic-bezier(0.4,0,0.2,1); transform-style: preserve-3d; }
            .flip-card.flipped { transform: rotateY(180deg); }
            .flip-front, .flip-back { position: absolute; inset: 0; backface-visibility: hidden; -webkit-backface-visibility: hidden; border-radius: 1rem; overflow: hidden; }
            .flip-back { transform: rotateY(180deg); }
        </style>
        <?php
        // Logo finder
        $productLogosDir = __DIR__ . '/uploads/products/logos/';
        function _findProdLogo($dir, $slug) {
            foreach (['png','jpg','jpeg','webp','gif','svg'] as $ext) {
                if (file_exists($dir . $slug . '.' . $ext)) return $slug . '.' . $ext;
            }
            return null;
        }
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featured_products as $i => $product): 
                $features = json_decode($product['features'], true) ?: [];
                $pLogo = _findProdLogo($productLogosDir, $product['slug']);
            ?>
            <div class="reveal flip-container" style="transition-delay: <?= $i * 100 ?>ms"
                 x-data="{ flipped: false, timer: null }"
                 x-init="timer = setInterval(() => { flipped = !flipped }, 10000 + <?= $i * 500 ?>)">
                <div class="flip-card w-full" :class="flipped ? 'flipped' : ''" style="min-height: 340px;">
                    <!-- FRONT FACE -->
                    <div class="flip-front bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-100 dark:border-slate-700 hover:shadow-2xl hover:shadow-brand-500/10 transition-all">
                        <div class="p-6 bg-gradient-to-br" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>15, <?= $product['color_to'] ?>10);">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg mb-4" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                                <i data-lucide="<?= $product['icon_class'] ?: 'box' ?>" class="w-7 h-7 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white"><?= sanitize($product['name']) ?></h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium"><?= sanitize($product['tagline']) ?></p>
                        </div>
                        <div class="p-6 pt-4">
                            <ul class="space-y-2 mb-6">
                                <?php foreach (array_slice($features, 0, 4) as $feat): ?>
                                <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                    <?= sanitize($feat) ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?= SITE_URL ?>/product.php?slug=<?= urlencode($product['slug']) ?>" 
                               class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700 transition-all">
                                Learn More <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                    <!-- BACK FACE (Logo) -->
                    <div class="flip-back flex flex-col items-center justify-center text-center border" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>12, <?= $product['color_to'] ?>08); border-color: <?= $product['color_from'] ?>30;">
                        <?php if ($pLogo): ?>
                        <img src="<?= SITE_URL ?>/uploads/products/logos/<?= $pLogo ?>?v=<?= filemtime($productLogosDir . $pLogo) ?>" alt="<?= sanitize($product['name']) ?>" class="max-w-[70%] max-h-40 object-contain mb-5 drop-shadow-lg">
                        <?php else: ?>
                        <div class="w-24 h-24 rounded-3xl flex items-center justify-center shadow-xl mb-5" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                            <i data-lucide="<?= $product['icon_class'] ?: 'box' ?>" class="w-12 h-12 text-white"></i>
                        </div>
                        <?php endif; ?>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-1"><?= sanitize($product['name']) ?></h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= sanitize($product['tagline']) ?></p>
                        <a href="<?= SITE_URL ?>/product.php?slug=<?= urlencode($product['slug']) ?>" 
                           class="mt-5 inline-flex items-center gap-2 px-5 py-2 text-xs font-bold text-white rounded-xl shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, <?= $product['color_from'] ?>, <?= $product['color_to'] ?>);">
                            View Product <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     PRODUCT INFOGRAPHIC SLIDESHOW
     ═══════════════════════════════════════════════════════════ -->
<?php
$slideDir = __DIR__ . '/assets/images/slides/';
$slideConfigFile = $slideDir . 'config.json';
$slideConfig = ['duration' => 5, 'aspect' => '16:9'];
if (file_exists($slideConfigFile)) {
    $sc = json_decode(file_get_contents($slideConfigFile), true);
    if ($sc) $slideConfig = array_merge($slideConfig, $sc);
}
$aspectMap = ['16:9'=>'aspect-video','4:3'=>'aspect-[4/3]','21:9'=>'aspect-[21/9]','3:2'=>'aspect-[3/2]','1:1'=>'aspect-square'];
$slideAspect = $aspectMap[$slideConfig['aspect']] ?? 'aspect-video';
$slideDuration = intval($slideConfig['duration']) * 1000;
$slideFiles = [];
if (is_dir($slideDir)) {
    foreach (glob($slideDir . '*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) as $f) {
        $slideFiles[] = basename($f);
    }
    sort($slideFiles);
    $slideFiles = array_slice($slideFiles, 0, 10);
}
?>
<?php if (!empty($slideFiles)): ?>
<section class="py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur rounded-full text-xs font-bold text-white/80 mb-4">
                <i data-lucide="monitor" class="w-3.5 h-3.5"></i> Product Showcase
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Our <span class="bg-gradient-to-r from-brand-400 to-purple-400 bg-clip-text text-transparent">Digital Products</span> in Action</h2>
            <p class="text-slate-400 max-w-2xl mx-auto">See how our enterprise solutions streamline operations across payroll, audit, workforce management, and more.</p>
        </div>
    </div>

    <!-- Slideshow Container -->
    <div class="relative max-w-6xl mx-auto px-4" x-data="productSlider(<?= count($slideFiles) ?>, <?= $slideDuration ?>)" @mouseenter="pause()" @mouseleave="resume()" @keydown.left.window="prev()" @keydown.right.window="next()">
        <!-- Slides Viewport -->
        <div class="relative overflow-hidden rounded-2xl shadow-2xl shadow-black/40 border border-white/10">
            <div class="flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (current * 100) + '%)'">
                <?php foreach ($slideFiles as $i => $slide): ?>
                <div class="w-full flex-shrink-0">
                    <div class="<?= $slideAspect ?> w-full">
                        <img src="<?= SITE_URL ?>/assets/images/slides/<?= $slide ?>" alt="Product Screenshot <?= $i + 1 ?>" class="w-full h-full object-contain bg-slate-900" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Prev / Next Arrows -->
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center text-white hover:bg-white/25 transition-all opacity-0 group-hover:opacity-100 hover:opacity-100 focus:opacity-100 z-10" style="opacity:0.7;">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center text-white hover:bg-white/25 transition-all opacity-0 group-hover:opacity-100 hover:opacity-100 focus:opacity-100 z-10" style="opacity:0.7;">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Dot Navigation -->
        <div class="flex justify-center gap-2 mt-6">
            <?php foreach ($slideFiles as $i => $slide): ?>
            <button @click="goTo(<?= $i ?>)" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="current === <?= $i ?> ? 'bg-brand-400 w-8' : 'bg-white/30 hover:bg-white/50'"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slide Counter -->
        <div class="text-center mt-3">
            <span class="text-xs text-slate-500" x-text="(current + 1) + ' / <?= count($slideFiles) ?>'"></span>
        </div>
    </div>
</section>

<script>
function productSlider(total, duration) {
    return {
        current: 0,
        total: total,
        duration: duration || 5000,
        timer: null,
        init() { this.startAutoPlay(); },
        startAutoPlay() { this.timer = setInterval(() => this.next(), this.duration); },
        stopAutoPlay() { clearInterval(this.timer); },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        goTo(i) { this.current = i; this.stopAutoPlay(); this.startAutoPlay(); },
        pause() { this.stopAutoPlay(); },
        resume() { this.startAutoPlay(); }
    }
}
</script>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     UPCOMING TRAINING
     ═══════════════════════════════════════════════════════════ -->
<?php if (!empty($upcoming_training)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-12 reveal">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 rounded-full text-xs font-bold text-violet-600 mb-4">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Upcoming Events
                </div>
                <h2 class="text-3xl font-black text-slate-900">Training & <span class="gradient-text">Conferences</span></h2>
            </div>
            <a href="<?= SITE_URL ?>/training.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-50 text-violet-600 font-bold rounded-xl hover:bg-violet-100 transition-colors text-sm">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($upcoming_training as $i => $program): ?>
            <div class="reveal group bg-white rounded-2xl border border-slate-100 hover:border-violet-200 hover:shadow-xl transition-all duration-300 overflow-hidden" style="transition-delay: <?= $i * 80 ?>ms">
                <div class="p-1.5">
                    <div class="bg-gradient-to-br from-violet-500/10 to-purple-500/5 rounded-xl p-5">
                        <div class="flex items-center gap-2 text-xs text-violet-600 font-bold mb-3">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <?= $program['program_date'] ? format_date($program['program_date']) : 'TBA' ?>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2"><?= sanitize($program['title']) ?></h3>
                        <p class="text-sm text-slate-500 line-clamp-2"><?= sanitize($program['venue'] ?: 'Venue TBA') ?></p>
                    </div>
                </div>
                <div class="px-5 pb-5 flex items-center justify-between">
                    <span class="text-sm font-bold <?= $program['fee'] > 0 ? 'text-amber-600' : 'text-emerald-600' ?>">
                        <?= $program['fee'] > 0 ? '₦' . number_format($program['fee']) : 'Free' ?>
                    </span>
                    <a href="<?= SITE_URL ?>/training.php" class="text-xs font-bold text-brand-600 hover:text-brand-700">Register →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     LATEST NEWS
     ═══════════════════════════════════════════════════════════ -->
<?php if (!empty($latest_news)): ?>
<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-12 reveal">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-sky-50 rounded-full text-xs font-bold text-sky-600 mb-4">
                    <i data-lucide="newspaper" class="w-3.5 h-3.5"></i> Latest Updates
                </div>
                <h2 class="text-3xl font-black text-slate-900">News & <span class="gradient-text">Announcements</span></h2>
            </div>
            <a href="<?= SITE_URL ?>/news.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 font-bold rounded-xl hover:bg-sky-100 transition-colors text-sm">
                All News <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($latest_news as $i => $news): ?>
            <div class="reveal group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300" style="transition-delay: <?= $i * 100 ?>ms">
                <?php if ($news['image_path']): ?>
                <div class="h-48 bg-gradient-to-br from-slate-100 to-slate-50 overflow-hidden">
                    <img src="<?= SITE_URL ?>/uploads/news/<?= $news['image_path'] ?>" alt="<?= sanitize($news['title']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <?php else: ?>
                <div class="h-48 bg-gradient-to-br from-brand-50 to-purple-50 flex items-center justify-center">
                    <i data-lucide="newspaper" class="w-12 h-12 text-brand-300"></i>
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-3">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <?= $news['publish_date'] ? format_date($news['publish_date']) : format_date($news['created_at']) ?>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2"><?= sanitize($news['title']) ?></h3>
                    <p class="text-sm text-slate-500 line-clamp-3"><?= sanitize($news['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     CAREERS CTA
     ═══════════════════════════════════════════════════════════ -->
<section class="py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-brand-900 relative overflow-hidden">
    <!-- Decorations -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-20 w-40 h-40 bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 reveal">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-xs font-bold text-brand-300 mb-6 border border-white/10">
            <i data-lucide="rocket" class="w-3.5 h-3.5"></i> Join Our Team
        </div>
        <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Ready to Build Your <span class="text-brand-400">Career?</span></h2>
        <p class="text-lg text-slate-400 mb-8 max-w-2xl mx-auto">Explore exciting job opportunities across various roles and industries. Submit your application and take the next step in your professional journey.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= SITE_URL ?>/careers.php" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-105 transition-all">
                <i data-lucide="search" class="w-5 h-5"></i>
                Browse Vacancies
            </a>
            <a href="<?= SITE_URL ?>/templates.php" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-2xl border border-white/20 hover:bg-white/20 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Download Templates
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     LATEST VIDEOS
     ═══════════════════════════════════════════════════════════ -->
<?php if (!empty($featured_videos)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-12 reveal">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 rounded-full text-xs font-bold text-red-600 mb-4">
                    <i data-lucide="video" class="w-3.5 h-3.5"></i> Media
                </div>
                <h2 class="text-3xl font-black text-slate-900">Latest <span class="gradient-text">Videos</span></h2>
            </div>
            <a href="<?= SITE_URL ?>/videos.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-colors text-sm">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($featured_videos as $i => $video): 
                // Extract embed URL
                $embed_url = '';
                $vurl = $video['video_url'];
                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $vurl, $m)) {
                    $embed_url = 'https://www.youtube.com/embed/' . $m[1];
                } elseif (preg_match('/vimeo\.com\/(\d+)/', $vurl, $m)) {
                    $embed_url = 'https://player.vimeo.com/video/' . $m[1];
                }
            ?>
            <div class="reveal group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:border-red-200 transition-all duration-500" style="transition-delay: <?= $i * 120 ?>ms">
                <div class="relative aspect-video bg-slate-100 overflow-hidden">
                    <?php if ($embed_url): ?>
                    <iframe src="<?= $embed_url ?>" title="<?= sanitize($video['title']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                    <?php elseif ($video['thumbnail_path']): ?>
                    <a href="<?= sanitize($vurl) ?>" target="_blank" class="block w-full h-full relative">
                        <img src="<?= SITE_URL ?>/uploads/videos/<?= $video['thumbnail_path'] ?>" alt="<?= sanitize($video['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-slate-900/30 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                                <i data-lucide="play" class="w-7 h-7 text-red-600 ml-1"></i>
                            </div>
                        </div>
                    </a>
                    <?php else: ?>
                    <a href="<?= sanitize($vurl) ?>" target="_blank" class="w-full h-full bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-white/80 flex items-center justify-center shadow-lg mx-auto mb-3">
                                <i data-lucide="play" class="w-7 h-7 text-red-500 ml-1"></i>
                            </div>
                            <p class="text-xs text-red-400 font-medium">Watch Video</p>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <h4 class="font-bold text-slate-900 line-clamp-2 mb-1"><?= sanitize($video['title']) ?></h4>
                    <p class="text-xs text-slate-400"><?= $video['publish_date'] ? format_date($video['publish_date']) : '' ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
