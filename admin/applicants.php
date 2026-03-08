<?php
/**
 * Admin — Applicants Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$applicants = db_query("SELECT a.*, v.title as vacancy_title FROM job_applications a LEFT JOIN job_vacancies v ON a.vacancy_id = v.id ORDER BY a.created_at DESC");
admin_page_start('Applicants', 'View and manage applications');
?>

<div x-data="{ detailModal: false, detail: null, filter: 'all' }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Job Applications</h2>
            <p class="text-sm text-slate-500"><?= count($applicants) ?> total applications</p>
        </div>
        <div class="flex gap-1.5 p-1 bg-white rounded-lg border border-slate-200">
            <?php foreach (['all'=>'All','new'=>'New','reviewed'=>'Reviewed','shortlisted'=>'Shortlisted','rejected'=>'Rejected'] as $k=>$l): ?>
            <button @click="filter = '<?= $k ?>'" :class="filter === '<?= $k ?>' ? 'bg-brand-500 text-white' : 'text-slate-500'" class="px-3 py-1.5 rounded-md text-xs font-bold transition-all"><?= $l ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Applicant</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Position</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Date</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($applicants)): ?>
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No applications received yet.</td></tr>
                    <?php else: foreach ($applicants as $a): ?>
                    <tr class="hover:bg-slate-50 transition-colors" x-show="filter === 'all' || filter === '<?= $a['status'] ?>'">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($a['full_name']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= sanitize($a['vacancy_title'] ?? '—') ?></td>
                        <td class="px-6 py-4 text-slate-500"><?= sanitize($a['email']) ?></td>
                        <td class="px-6 py-4">
                            <select onchange="updateStatus(<?= $a['id'] ?>, this.value, 'application')" class="text-xs font-bold px-2 py-1 rounded-lg border border-slate-200 bg-white cursor-pointer">
                                <?php foreach (['new','reviewed','shortlisted','rejected'] as $s): ?>
                                <option value="<?= $s ?>" <?= $a['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400"><?= format_date($a['created_at']) ?></td>
                        <td class="px-6 py-4 text-right flex gap-1 justify-end">
                            <button @click="detail = <?= htmlspecialchars(json_encode($a)) ?>; detailModal = true" class="p-2 text-brand-500 hover:bg-brand-50 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></button>
                            <?php if ($a['cv_path']): ?>
                            <a href="<?= SITE_URL ?>/uploads/cvs/<?= $a['cv_path'] ?>" target="_blank" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg"><i data-lucide="download" class="w-4 h-4"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="detailModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detailModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8">
            <button @click="detailModal = false" class="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-xl"><i data-lucide="x" class="w-5 h-5"></i></button>
            <template x-if="detail">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1" x-text="detail.full_name"></h3>
                    <p class="text-sm text-slate-500 mb-6" x-text="detail.vacancy_title || 'General Application'"></p>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Email</p><p class="text-sm font-semibold text-slate-800" x-text="detail.email"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Phone</p><p class="text-sm font-semibold text-slate-800" x-text="detail.phone"></p></div>
                    </div>
                    <div class="mb-4"><p class="text-xs font-bold text-slate-600 mb-2">Education</p><p class="text-sm text-slate-700 whitespace-pre-line" x-text="detail.education"></p></div>
                    <div class="mb-4"><p class="text-xs font-bold text-slate-600 mb-2">Experience</p><p class="text-sm text-slate-700 whitespace-pre-line" x-text="detail.experience"></p></div>
                    <div x-show="detail.cover_letter"><p class="text-xs font-bold text-slate-600 mb-2">Cover Letter</p><p class="text-sm text-slate-700 whitespace-pre-line" x-text="detail.cover_letter"></p></div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
async function updateStatus(id, status, type) {
    const fd = new FormData(); 
    fd.append('action', 'update_' + type + '_status');
    fd.append('id', id); fd.append('status', status);
    await fetch('<?= SITE_URL ?>/admin_api.php', {method:'POST', body:fd});
}
</script>

<?php admin_page_end(); ?>
