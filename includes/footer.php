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
<a href="<?= SITE_URL ?>/training.php" class="fixed bottom-4 left-4 z-50 group">
    <div class="flex items-center gap-1.5 pl-2 pr-3 py-1.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:scale-105 transition-all duration-300">
        <span class="relative flex items-center justify-center">
            <span class="absolute w-6 h-6 bg-white/20 rounded-full animate-ping"></span>
            <span class="relative w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </span>
        </span>
        <div class="leading-tight">
            <p class="text-[10px] font-black">Free Training!!!</p>
            <p class="text-[8px] font-medium opacity-80">Register Now</p>
        </div>
    </div>
</a>

<!-- WhatsApp Chat — Bottom Right -->
<a href="https://wa.me/2349063337173" target="_blank" rel="noopener" class="fixed bottom-4 right-4 z-50 group" title="Chat on WhatsApp">
    <div class="flex items-center gap-1.5 pl-2 pr-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-full shadow-lg shadow-green-500/30 hover:shadow-green-500/50 hover:scale-105 transition-all duration-300">
        <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </span>
        <div class="leading-tight">
            <p class="text-[10px] font-black">Chat with us</p>
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
