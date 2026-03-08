<?php
/**
 * Admin — Site Settings (Super Admin only)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

admin_page_start('Settings', 'Platform configuration');
?>

<div class="max-w-4xl" x-data="settingsPage()">
    <h2 class="text-2xl font-black text-slate-900 mb-8">Platform Settings</h2>

    <!-- Site Info Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="globe" class="w-4 h-4 text-brand-500"></i> Site Information</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Site Name</p><p class="text-sm font-semibold text-slate-800"><?= SITE_NAME ?></p></div>
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Company</p><p class="text-sm font-semibold text-slate-800"><?= COMPANY_NAME ?></p></div>
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Site URL</p><p class="text-sm font-semibold text-slate-800"><?= SITE_URL ?></p></div>
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Email</p><p class="text-sm font-semibold text-slate-800"><?= SITE_EMAIL ?></p></div>
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Address</p><p class="text-sm font-semibold text-slate-800"><?= SITE_ADDRESS ?></p></div>
                <div class="p-4 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Phone</p><p class="text-sm font-semibold text-slate-800"><?= SITE_PHONE ?></p></div>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-xs text-amber-700 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4"></i>
                Site information is configured in <code class="bg-amber-100 px-1 rounded">includes/config.php</code>. Contact your developer to modify these values.
            </div>
        </div>
    </div>

    <!-- Database Stats -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="database" class="w-4 h-4 text-emerald-500"></i> Database Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <?php
                $tables = [
                    ['users', 'Users', 'shield'],
                    ['job_vacancies', 'Vacancies', 'briefcase'],
                    ['job_applications', 'Applications', 'users'],
                    ['consulting_requests', 'Consulting', 'message-square'],
                    ['training_programs', 'Programs', 'graduation-cap'],
                    ['training_registrations', 'Registrations', 'clipboard-check'],
                    ['business_templates', 'Templates', 'file-text'],
                    ['videos', 'Videos', 'video'],
                    ['news_posts', 'News', 'newspaper'],
                    ['posters', 'Posters', 'image'],
                    ['products', 'Products', 'box'],
                    ['quarterly_training_requests', 'Q. Requests', 'calendar'],
                ];
                foreach ($tables as $t):
                    $count = db_value("SELECT COUNT(*) FROM {$t[0]}");
                ?>
                <div class="p-3 bg-slate-50 rounded-xl text-center">
                    <i data-lucide="<?= $t[2] ?>" class="w-4 h-4 text-slate-400 mx-auto mb-1"></i>
                    <p class="text-lg font-bold text-slate-900"><?= $count ?></p>
                    <p class="text-[10px] text-slate-400 uppercase font-semibold"><?= $t[1] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="lock" class="w-4 h-4 text-amber-500"></i> Change Password</h3>
        </div>
        <div class="p-6">
            <div x-show="pwSuccess" x-cloak class="p-3 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">Password updated successfully!</div>
            <div x-show="pwError" x-cloak class="p-3 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-text="pwError"></div>
            <form @submit.prevent="changePassword()" class="space-y-4 max-w-md">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Current Password</label><input type="password" x-model="pw.current" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">New Password</label><input type="password" x-model="pw.new_password" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Confirm New Password</label><input type="password" x-model="pw.confirm" required class="form-input"></div>
                <button type="submit" :disabled="pwLoading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg disabled:opacity-50">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="link" class="w-4 h-4 text-blue-500"></i> Quick Actions</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="<?= SITE_URL ?>/admin/pins.php" class="flex items-center gap-3 p-4 rounded-xl bg-brand-50 border border-brand-100 hover:bg-brand-100 transition-colors">
                <i data-lucide="pin" class="w-5 h-5 text-brand-600"></i>
                <div><p class="text-sm font-bold text-brand-800">Homepage Pins</p><p class="text-[10px] text-brand-500">Manage featured content</p></div>
            </a>
            <a href="<?= SITE_URL ?>/admin/registrations.php" class="flex items-center gap-3 p-4 rounded-xl bg-violet-50 border border-violet-100 hover:bg-violet-100 transition-colors">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-violet-600"></i>
                <div><p class="text-sm font-bold text-violet-800">All Registrations</p><p class="text-[10px] text-violet-500">View training registrations</p></div>
            </a>
            <a href="<?= SITE_URL ?>/admin/quarterly_requests.php" class="flex items-center gap-3 p-4 rounded-xl bg-teal-50 border border-teal-100 hover:bg-teal-100 transition-colors">
                <i data-lucide="calendar" class="w-5 h-5 text-teal-600"></i>
                <div><p class="text-sm font-bold text-teal-800">Quarterly Requests</p><p class="text-[10px] text-teal-500">Corporate training needs</p></div>
            </a>
            <a href="<?= SITE_URL ?>" target="_blank" class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition-colors">
                <i data-lucide="external-link" class="w-5 h-5 text-emerald-600"></i>
                <div><p class="text-sm font-bold text-emerald-800">View Public Site</p><p class="text-[10px] text-emerald-500">Open in new tab</p></div>
            </a>
        </div>
    </div>
</div>

<script>
function settingsPage() {
    return {
        pw: { current: '', new_password: '', confirm: '' },
        pwLoading: false, pwSuccess: false, pwError: '',
        async changePassword() {
            if (this.pw.new_password !== this.pw.confirm) { this.pwError = 'Passwords do not match.'; return; }
            if (this.pw.new_password.length < 6) { this.pwError = 'Password must be at least 6 characters.'; return; }
            this.pwLoading = true; this.pwError = ''; this.pwSuccess = false;
            const fd = new FormData();
            fd.append('action', 'change_password');
            fd.append('current_password', this.pw.current);
            fd.append('new_password', this.pw.new_password);
            const r = await fetch('<?= SITE_URL ?>/admin_api.php', { method: 'POST', body: fd });
            const d = await r.json();
            if (d.success) { this.pwSuccess = true; this.pw = { current: '', new_password: '', confirm: '' }; }
            else { this.pwError = d.message || 'Failed to change password.'; }
            this.pwLoading = false;
        }
    }
}
</script>

<?php admin_page_end(); ?>
