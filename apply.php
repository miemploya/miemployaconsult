<?php
/**
 * Miemploya Consult — Job Application Form
 */
require_once __DIR__ . '/includes/db.php';
$vacancy_id = intval($_GET['vacancy'] ?? 0);
$vacancy = $vacancy_id ? db_row("SELECT * FROM job_vacancies WHERE id = ? AND is_active = 1", [$vacancy_id]) : null;

$page_title = 'Apply for Job';
include __DIR__ . '/includes/header.php';
?>

<section class="py-20 mesh-bg min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="<?= SITE_URL ?>/careers.php" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-brand-600 mb-8 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Vacancies
        </a>

        <?php if ($vacancy): ?>
        <div class="mb-8 p-6 bg-brand-50 rounded-2xl border border-brand-100 reveal">
            <h2 class="text-xl font-bold text-slate-900 mb-1">Applying for: <?= sanitize($vacancy['title']) ?></h2>
            <p class="text-sm text-slate-500"><?= sanitize($vacancy['company_name']) ?> · <?= sanitize($vacancy['location'] ?: 'Remote') ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden reveal" x-data="applicationForm()">
            <div class="bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-transparent px-8 py-6 border-b border-slate-100">
                <h1 class="text-xl font-bold text-slate-900">Job Application Form</h1>
                <p class="text-sm text-slate-500 mt-1">Complete all required fields</p>
            </div>
            <div class="p-8">
                <div x-show="success" x-cloak class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-800 mb-1">Application Submitted!</h3>
                    <p class="text-sm text-emerald-600">Thank you for applying. We'll review your application and get back to you.</p>
                </div>

                <form x-show="!success" @submit.prevent="submit()" class="space-y-5" enctype="multipart/form-data">
                    <input type="hidden" name="vacancy_id" value="<?= $vacancy_id ?>">

                    <div x-show="error" x-cloak class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-text="error"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Full Name *</label>
                            <input type="text" x-model="form.full_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Email Address *</label>
                            <input type="email" x-model="form.email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Phone Number *</label>
                            <input type="tel" x-model="form.phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Address</label>
                            <input type="text" x-model="form.address" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Education Background *</label>
                        <textarea x-model="form.education" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all resize-none" placeholder="e.g. B.Sc. Computer Science, University of Lagos, 2020"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Work Experience *</label>
                        <textarea x-model="form.experience" rows="4" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all resize-none" placeholder="Describe your relevant work experience..."></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Upload CV * <span class="text-xs text-slate-400 font-normal">(PDF, DOC, max 5MB)</span></label>
                        <input type="file" @change="form.cv = $event.target.files[0]" accept=".pdf,.doc,.docx" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-600 file:font-semibold file:text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Cover Letter <span class="text-xs text-slate-400 font-normal">(Optional)</span></label>
                        <textarea x-model="form.cover_letter" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all resize-none" placeholder="Optional cover letter..."></textarea>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50">
                        <span x-show="!loading">Submit Application</span>
                        <span x-show="loading" x-cloak>Uploading...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function applicationForm() {
    return {
        form: { full_name:'', email:'', phone:'', address:'', education:'', experience:'', cv:null, cover_letter:'' },
        loading: false, success: false, error: '',
        async submit() {
            this.loading = true; this.error = '';
            try {
                const fd = new FormData();
                fd.append('action', 'apply_job');
                fd.append('vacancy_id', '<?= $vacancy_id ?>');
                Object.keys(this.form).forEach(k => { if (this.form[k]) fd.append(k, this.form[k]); });
                const res = await fetch('<?= SITE_URL ?>/api.php', { method:'POST', body: fd });
                const data = await res.json();
                if (data.success) { this.success = true; } else { this.error = data.message || 'Error submitting application.'; }
            } catch(e) { this.error = 'Network error. Please try again.'; }
            this.loading = false;
        }
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
