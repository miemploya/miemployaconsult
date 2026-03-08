<?php
/**
 * Admin — Quarterly Training Requests
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$requests = db_query("SELECT * FROM quarterly_training_requests ORDER BY created_at DESC");
admin_page_start('Quarterly Training Requests', 'Corporate training needs');
?>

<div x-data="{ detail: null, showDetail: false }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= SITE_URL ?>/admin/training.php" class="text-sm text-slate-400 hover:text-brand-600 flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Training Programs</a>
            </div>
            <h2 class="text-2xl font-black text-slate-900">Quarterly Training Requests</h2>
            <p class="text-sm text-slate-500"><?= count($requests) ?> requests submitted</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Company</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Contact</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Category</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Staff Count</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Period</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Date</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($requests)): ?>
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">No quarterly requests submitted yet.</td></tr>
                    <?php else: foreach ($requests as $r): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($r['company_name']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= sanitize($r['contact_person']) ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 bg-violet-50 text-violet-600 rounded text-xs font-bold"><?= sanitize($r['training_category'] ?: '—') ?></span></td>
                        <td class="px-6 py-4 text-slate-600"><?= $r['staff_count'] ?: '—' ?></td>
                        <td class="px-6 py-4 text-slate-500"><?= sanitize($r['preferred_period'] ?: '—') ?></td>
                        <td class="px-6 py-4 text-xs text-slate-400"><?= format_date($r['created_at']) ?></td>
                        <td class="px-6 py-4 text-right">
                            <button @click="detail = <?= htmlspecialchars(json_encode($r)) ?>; showDetail = true" class="p-2 text-brand-500 hover:bg-brand-50 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></button>
                            <button onclick="if(confirm('Delete this request?')){deleteQR(<?= $r['id'] ?>)}" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="showDetail" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showDetail = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <button @click="showDetail = false" class="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-xl"><i data-lucide="x" class="w-5 h-5"></i></button>
            <template x-if="detail">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1" x-text="detail.company_name"></h3>
                    <p class="text-sm text-slate-500 mb-6" x-text="'Contact: ' + detail.contact_person"></p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Department</p><p class="text-sm font-semibold" x-text="detail.department || 'Not specified'"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Staff Count</p><p class="text-sm font-semibold" x-text="detail.staff_count || 'N/A'"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Category</p><p class="text-sm font-semibold" x-text="detail.training_category || 'Not specified'"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Preferred Period</p><p class="text-sm font-semibold" x-text="detail.preferred_period || 'Not specified'"></p></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
async function deleteQR(id) {
    const fd = new FormData();
    fd.append('action', 'delete_quarterly_request');
    fd.append('id', id);
    await fetch('<?= SITE_URL ?>/admin_api.php', { method:'POST', body:fd });
    location.reload();
}
</script>

<?php admin_page_end(); ?>
