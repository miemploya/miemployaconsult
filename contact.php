<?php
/**
 * Miemploya Consult — Contact Page
 */
require_once __DIR__ . '/includes/db.php';
$page_title = 'Contact Us';
include __DIR__ . '/includes/header.php';

$success = flash('contact_success');
?>

<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 rounded-full text-xs font-bold text-brand-600 mb-4">
                <i data-lucide="phone" class="w-3.5 h-3.5"></i> Get in Touch
            </div>
            <h1 class="text-4xl font-black text-slate-900 mb-4">Contact <span class="gradient-text">Us</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Have a question or need our consulting services? Reach out and we'll get back to you shortly.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Info Cards -->
            <div class="space-y-6 reveal">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-brand-500/25 mb-4">
                        <i data-lucide="map-pin" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-1">Office Address</h3>
                    <p class="text-sm text-slate-500"><?= SITE_ADDRESS ?></p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/25 mb-4">
                        <i data-lucide="phone" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-1">Phone</h3>
                    <p class="text-sm text-slate-500"><?= SITE_PHONE ?></p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center shadow-lg shadow-purple-500/25 mb-4">
                        <i data-lucide="mail" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-1">Email</h3>
                    <p class="text-sm text-slate-500"><?= SITE_EMAIL ?></p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 reveal">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-brand-500/10 via-purple-500/5 to-transparent px-8 py-6 border-b border-slate-100">
                        <h2 class="text-xl font-bold text-slate-900">Send Us a Message</h2>
                        <p class="text-sm text-slate-500 mt-1">We'll respond within 24 hours</p>
                    </div>
                    <div class="p-8">
                        <?php if ($success): ?>
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <?= $success ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= SITE_URL ?>/api.php" class="space-y-5" x-data="{ sending: false }">
                            <input type="hidden" name="action" value="contact_message">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Full Name *</label>
                                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all" placeholder="John Doe">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Email *</label>
                                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all" placeholder="you@company.com">
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700 mb-2 block">Subject *</label>
                                <input type="text" name="subject" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all" placeholder="How can we help?">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700 mb-2 block">Message *</label>
                                <textarea name="message" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all resize-none" placeholder="Tell us more about your needs..."></textarea>
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
