<?php
/**
 * Miemploya Consult — Training & Conferences
 */
require_once __DIR__ . '/includes/db.php';
$programs = db_query("SELECT * FROM training_programs WHERE is_active = 1 ORDER BY program_date ASC");
$page_title = 'Training & Conferences';
include __DIR__ . '/includes/header.php';
?>

<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 rounded-full text-xs font-bold text-violet-600 mb-4">
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Professional Development
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Training & <span class="gradient-text">Conferences</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Professional training programs, seminars, and conferences to advance your career and organization.</p>
        </div>

        <?php if (empty($programs)): ?>
        <div class="text-center py-16 reveal">
            <div class="w-20 h-20 rounded-3xl bg-violet-50 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="calendar" class="w-10 h-10 text-violet-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">No Upcoming Programs</h3>
            <p class="text-slate-500">Check back later or submit a quarterly training request below.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($programs as $i => $prog): 
                $spots_left = $prog['registration_limit'] ? max(0, $prog['registration_limit'] - $prog['registrations_count']) : null;
            ?>
            <div class="reveal bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all" style="transition-delay: <?= $i * 80 ?>ms"
                 x-data="{ showReg: false }">
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs font-bold text-violet-600 mb-3">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <?= $prog['program_date'] ? format_date($prog['program_date']) : 'Date TBA' ?>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2"><?= sanitize($prog['title']) ?></h3>
                    <p class="text-sm text-slate-500 mb-4 line-clamp-3"><?= sanitize($prog['description']) ?></p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php if ($prog['venue']): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-50 text-xs text-slate-600 rounded-lg"><i data-lucide="map-pin" class="w-3 h-3"></i> <?= sanitize($prog['venue']) ?></span>
                        <?php endif; ?>
                        <span class="inline-flex items-center gap-1 px-2 py-1 <?= $prog['fee'] > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' ?> text-xs font-bold rounded-lg">
                            <?= $prog['fee'] > 0 ? '₦' . number_format($prog['fee']) : 'Free' ?>
                        </span>
                        <?php if ($spots_left !== null): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-600 text-xs rounded-lg"><?= $spots_left ?> spots left</span>
                        <?php endif; ?>
                    </div>

                    <button @click="showReg = !showReg" class="w-full py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-bold rounded-xl text-sm hover:scale-[1.02] transition-all shadow-md">
                        Register Now
                    </button>
                </div>

                <!-- Registration Form -->
                <div x-show="showReg" x-transition x-cloak class="border-t border-slate-100 p-6 bg-slate-50" x-data="regForm(<?= $prog['id'] ?>)">
                    <div x-show="regSuccess" x-cloak class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm text-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mx-auto mb-1"></i> Registration successful!
                    </div>
                    <form x-show="!regSuccess" @submit.prevent="submitReg()" class="space-y-3">
                        <input type="text" x-model="reg.user_name" required placeholder="Full Name *" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30">
                        <input type="email" x-model="reg.email" required placeholder="Email *" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30">
                        <input type="tel" x-model="reg.phone" placeholder="Phone" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30">
                        <input type="text" x-model="reg.organization" placeholder="Organization" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30">
                        <button type="submit" :disabled="regLoading" class="w-full py-2.5 bg-violet-600 text-white font-bold rounded-lg text-sm hover:bg-violet-700 transition-colors disabled:opacity-50">
                            <span x-show="!regLoading">Confirm Registration</span>
                            <span x-show="regLoading" x-cloak>Registering...</span>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quarterly Training Request -->
<section class="py-20 bg-white" id="quarterly">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <h2 class="text-3xl font-black text-slate-900 mb-4">Quarterly Training <span class="gradient-text">Request</span></h2>
            <p class="text-slate-500">Organizations can submit training needs for customized corporate programs.</p>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden reveal" x-data="quarterlyForm()">
            <div class="bg-gradient-to-r from-violet-500/10 via-purple-500/5 to-transparent px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Quarterly Training Needs Form</h3>
            </div>
            <div class="p-8">
                <div x-show="qSuccess" x-cloak class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm text-center">Request submitted successfully!</div>
                <form x-show="!qSuccess" @submit.prevent="submitQ()" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Company Name *</label><input type="text" x-model="q.company_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all"></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Contact Person *</label><input type="text" x-model="q.contact_person" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Department</label><input type="text" x-model="q.department" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all"></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Number of Staff</label><input type="number" x-model="q.staff_count" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all"></div>
                    </div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Training Category</label>
                        <select x-model="q.training_category" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all">
                            <option value="">Select...</option>
                            <option>HR Training</option><option>Business Development</option><option>School Leadership</option><option>Education Technology</option><option>Corporate Training</option><option>Other</option>
                        </select>
                    </div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-2 block">Preferred Training Period</label><input type="text" x-model="q.preferred_period" placeholder="e.g. Q2 2026, April-June" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 transition-all"></div>
                    <button type="submit" :disabled="qLoading" class="px-8 py-3.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-[1.02] transition-all disabled:opacity-50">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function regForm(pid) {
    return {
        reg: { user_name:'', email:'', phone:'', organization:'' }, regLoading:false, regSuccess:false,
        async submitReg() {
            this.regLoading = true;
            const fd = new FormData(); fd.append('action','register_training'); fd.append('program_id', pid);
            Object.keys(this.reg).forEach(k => fd.append(k, this.reg[k]));
            const r = await fetch('<?= SITE_URL ?>/api.php', {method:'POST', body:fd});
            const d = await r.json();
            if (d.success) this.regSuccess = true;
            this.regLoading = false;
        }
    }
}
function quarterlyForm() {
    return {
        q: { company_name:'', contact_person:'', department:'', staff_count:'', training_category:'', preferred_period:'' }, qLoading:false, qSuccess:false,
        async submitQ() {
            this.qLoading = true;
            const fd = new FormData(); fd.append('action','submit_quarterly');
            Object.keys(this.q).forEach(k => fd.append(k, this.q[k]));
            const r = await fetch('<?= SITE_URL ?>/api.php', {method:'POST', body:fd});
            const d = await r.json();
            if (d.success) this.qSuccess = true;
            this.qLoading = false;
        }
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
