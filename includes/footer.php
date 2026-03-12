<!-- Footer -->
<footer class="bg-slate-900 text-slate-300 mt-auto">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <?php $footerDarkLogo = file_exists(__DIR__ . '/../assets/images/logo_dark.png'); ?>
                    <?php if ($footerDarkLogo): ?>
                    <img src="<?= SITE_URL ?>/assets/images/logo_dark.png" alt="<?= SITE_NAME ?>" class="h-12 w-auto">
                    <?php else: ?>
                    <img src="<?= SITE_URL ?>/assets/images/logo.png" alt="<?= SITE_NAME ?>" class="h-12 w-auto">
                    <?php endif; ?>
                    <div>
                        <p class="text-lg font-black text-white tracking-tight"><?= SITE_NAME ?></p>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest"><?= COMPANY_TAGLINE ?></p>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed mb-6">
                    The consulting and technology solutions arm of <?= COMPANY_NAME ?>. We provide business consulting, recruitment, professional training, and cutting-edge digital products.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                        <i data-lucide="linkedin" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="<?= SITE_URL ?>/about.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> About Us</a></li>
                    <li><a href="<?= SITE_URL ?>/services.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> Our Services</a></li>
                    <li><a href="<?= SITE_URL ?>/products.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> Digital Products</a></li>
                    <li><a href="<?= SITE_URL ?>/careers.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> Career Portal</a></li>
                    <li><a href="<?= SITE_URL ?>/training.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> Training Programs</a></li>
                    <li><a href="<?= SITE_URL ?>/templates.php" class="text-sm text-slate-400 hover:text-brand-400 transition-colors flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3"></i> Business Templates</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Services</h4>
                <ul class="space-y-3">
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> HR Consulting</span></li>
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> Payroll Advisory</span></li>
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> Business Development</span></li>
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> Audit & Compliance</span></li>
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> Process Optimization</span></li>
                    <li><span class="text-sm text-slate-400 flex items-center gap-2"><i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i> Workforce Monitoring</span></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Contact Us</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-brand-400"></i>
                        </div>
                        <span class="text-sm text-slate-400"><?= SITE_ADDRESS ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-4 h-4 text-brand-400"></i>
                        </div>
                        <span class="text-sm text-slate-400"><?= SITE_PHONE ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="w-4 h-4 text-brand-400"></i>
                        </div>
                        <span class="text-sm text-slate-400"><?= SITE_EMAIL ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-500">&copy; <?= date('Y') ?> <?= COMPANY_NAME ?>. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="text-xs text-slate-500 hover:text-brand-400 transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-slate-500 hover:text-brand-400 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════════════════════
     FLOATING ACTION BUTTONS
     ═══════════════════════════════════════════════════════ -->

<!-- Training CTA — Bottom Left -->
<a href="<?= SITE_URL ?>/training.php" class="fixed bottom-6 left-6 z-50 group">
    <div class="flex items-center gap-2 pl-3 pr-5 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full shadow-2xl shadow-emerald-500/40 hover:shadow-emerald-500/60 hover:scale-105 transition-all duration-300">
        <span class="relative flex items-center justify-center">
            <span class="absolute w-10 h-10 bg-white/20 rounded-full animate-ping"></span>
            <span class="relative w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </span>
        </span>
        <div class="leading-tight">
            <p class="text-sm font-black">Free Training!!!</p>
            <p class="text-[10px] font-medium opacity-80">Register Now</p>
        </div>
    </div>
</a>

<!-- WhatsApp Chat — Bottom Right -->
<a href="https://wa.me/2348000000000" target="_blank" rel="noopener" class="fixed bottom-6 right-6 z-50 group" title="Chat with us on WhatsApp">
    <div class="flex items-center gap-2 pl-3 pr-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-full shadow-2xl shadow-blue-600/40 hover:shadow-blue-600/60 hover:scale-105 transition-all duration-300">
        <span class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
        </span>
        <div class="leading-tight">
            <p class="text-sm font-black">Chat with us</p>
        </div>
    </div>
</a>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>

<!-- Scroll Reveal Observer -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('revealed');
                    }, index * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>

</body>
</html>
