<?php
/**
 * Miemploya Consult — Services Page
 */
require_once __DIR__ . '/includes/db.php';
$page_title = 'Our Services';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="py-20 bg-gradient-to-br from-brand-500/5 via-purple-500/5 to-transparent mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 rounded-full text-xs font-bold text-brand-600 mb-6">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Consulting & Advisory
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-6">Consulting & Advisory <span class="gradient-text">Services</span></h1>
            <p class="text-lg text-slate-600 leading-relaxed">Strategic advisory and consulting services designed to optimize organizational performance and drive sustainable business growth.</p>
        </div>
    </div>
</section>

<!-- Services Detail -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        $services = [
            ['Human Resource Consulting', 'Comprehensive HR advisory covering recruitment strategies, talent management systems, employee relations frameworks, organizational restructuring, and workforce planning. We help organizations build robust HR departments that drive employee engagement and productivity.', 'users', 'from-blue-500 to-indigo-600', 'blue', ['Talent acquisition & recruitment strategy', 'Employee relations & engagement programs', 'Organizational development & restructuring', 'HR policy development & compliance', 'Workforce planning & succession management']],
            ['Payroll Management Advisory', 'Expert guidance on payroll processing, statutory compliance, tax optimization, and compensation structuring. We ensure your payroll operations are efficient, compliant, and employee-friendly.', 'banknote', 'from-emerald-500 to-teal-600', 'emerald', ['Payroll setup & configuration advisory', 'Statutory compliance (PAYE, Pension, NHF, NHIS)', 'Compensation & benefits structuring', 'Payroll audit & reconciliation', 'Tax optimization strategies']],
            ['Business Development & Strategic Advisory', 'Strategic planning, market analysis, partnership development, and revenue growth strategies. We work with you to identify opportunities and create actionable growth roadmaps.', 'trending-up', 'from-purple-500 to-violet-600', 'purple', ['Strategic business planning', 'Market research & competitive analysis', 'Partnership & investor relations', 'Revenue growth strategies', 'Business model innovation']],
            ['Audit & Compliance Support', 'Thorough audit support and regulatory compliance monitoring. We help businesses maintain transparency, reduce risk, and meet industry standards.', 'shield-check', 'from-amber-500 to-orange-600', 'amber', ['Internal audit & control assessment', 'Regulatory compliance monitoring', 'Risk assessment & mitigation', 'Financial reporting advisory', 'Compliance training & awareness']],
            ['Business Process Optimization', 'Streamline operations, eliminate inefficiencies, and improve workflows. Our optimization services cover everything from manual processes to digital transformation.', 'settings', 'from-cyan-500 to-blue-600', 'cyan', ['Process mapping & analysis', 'Workflow automation recommendations', 'Digital transformation advisory', 'Operational efficiency improvement', 'KPI development & monitoring']],
            ['Workforce Monitoring Systems', 'Implementing employee monitoring solutions, productivity tracking, attendance management systems, and performance analytics to help organizations manage their workforce effectively.', 'activity', 'from-rose-500 to-pink-600', 'rose', ['Employee attendance systems', 'Productivity tracking & reporting', 'Performance analytics dashboards', 'Workforce scheduling solutions', 'GPS & biometric monitoring setup']],
            ['Digital Business Solutions', 'We design, develop, and deploy custom software solutions engineered to address your organization\'s unique challenges. From enterprise-grade platforms and workflow automation tools to mobile-ready applications, we transform your vision into scalable digital products that drive efficiency, innovation, and measurable results.', 'code-2', 'from-violet-500 to-fuchsia-600', 'violet', ['Custom web & mobile application development', 'Enterprise software & ERP solutions', 'Workflow automation & system integration', 'SaaS product design & deployment', 'Legacy system modernization & migration']],
        ];
        foreach ($services as $i => $svc):
        ?>
        <div class="reveal mb-12 last:mb-0" style="transition-delay: <?= $i * 100 ?>ms">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start bg-white rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:border-<?= $svc[4] ?>-200 transition-all">
                <div class="lg:col-span-2">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?= $svc[3] ?> flex items-center justify-center shadow-lg mb-5">
                        <i data-lucide="<?= $svc[2] ?>" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 mb-3"><?= $svc[0] ?></h2>
                    <p class="text-sm text-slate-500 leading-relaxed"><?= $svc[1] ?></p>
                </div>
                <div class="lg:col-span-3">
                    <div class="bg-slate-50 rounded-2xl p-6">
                        <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <i data-lucide="list-checks" class="w-4 h-4 text-<?= $svc[4] ?>-500"></i>
                            Key Deliverables
                        </h4>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php foreach ($svc[5] as $feat): ?>
                            <li class="flex items-center gap-2 text-sm text-slate-600">
                                <div class="w-5 h-5 rounded-md bg-<?= $svc[4] ?>-100 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3 h-3 text-<?= $svc[4] ?>-600"></i>
                                </div>
                                <?= $feat ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Consulting Request Form -->
<section class="py-20 mesh-bg" id="request">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <h2 class="text-3xl font-black text-slate-900 mb-4">Request a <span class="gradient-text">Consultation</span></h2>
            <p class="text-slate-500">Fill in the form below and our team will get back to you within 48 hours.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden reveal" x-data="consultForm()">
            <div class="bg-gradient-to-r from-brand-500/10 via-purple-500/5 to-transparent px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Consulting Request Form</h3>
            </div>
            <div class="p-8">
                <div x-show="success" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2" x-cloak>
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>Your request has been submitted successfully. We'll contact you soon!</span>
                </div>
                <div x-show="error" class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-cloak x-text="error"></div>

                <form @submit.prevent="submit()" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Company Name *</label>
                            <input type="text" x-model="form.company_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Contact Person *</label>
                            <input type="text" x-model="form.contact_person" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Email *</label>
                            <input type="email" x-model="form.email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700 mb-2 block">Phone *</label>
                            <input type="tel" x-model="form.phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Industry Sector</label>
                        <select x-model="form.industry_sector" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all">
                            <option value="">Select sector...</option>
                            <option>Oil & Gas</option>
                            <option>Banking & Finance</option>
                            <option>Education</option>
                            <option>Healthcare</option>
                            <option>Manufacturing</option>
                            <option>Technology</option>
                            <option>Retail</option>
                            <option>Construction</option>
                            <option>Government</option>
                            <option>Non-Profit</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Description of Service Required *</label>
                        <textarea x-model="form.description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all resize-none" placeholder="Describe the consulting service you need..."></textarea>
                    </div>
                    <button type="submit" :disabled="loading" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50">
                        <span x-show="!loading">Submit Request</span>
                        <span x-show="loading" x-cloak>Submitting...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function consultForm() {
    return {
        form: { company_name:'', contact_person:'', email:'', phone:'', industry_sector:'', description:'' },
        loading: false, success: false, error: '',
        async submit() {
            this.loading = true; this.error = ''; this.success = false;
            try {
                const fd = new FormData();
                fd.append('action', 'submit_consulting');
                Object.keys(this.form).forEach(k => fd.append(k, this.form[k]));
                const res = await fetch('<?= SITE_URL ?>/api.php', { method:'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    this.success = true;
                    this.form = { company_name:'', contact_person:'', email:'', phone:'', industry_sector:'', description:'' };
                } else {
                    this.error = data.message || 'An error occurred.';
                }
            } catch(e) { this.error = 'Network error. Please try again.'; }
            this.loading = false;
        }
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
