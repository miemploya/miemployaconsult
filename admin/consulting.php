<?php
/**
 * Admin — Consulting Requests
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$requests = db_query("SELECT * FROM consulting_requests ORDER BY created_at DESC");
admin_page_start('Consulting Requests', 'View inbound consulting requests');
?>
<div x-data="{ detail:null, showDetail:false }">
    <h2 class="text-2xl font-black text-slate-900 mb-8">Consulting Requests</h2>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Company</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Contact</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Sector</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Status</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Date</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-50">
            <?php if(empty($requests)):?><tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No requests yet.</td></tr>
            <?php else: foreach($requests as $r):?>
            <tr class="hover:bg-slate-50"><td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($r['company_name']) ?></td><td class="px-6 py-4 text-slate-600"><?= sanitize($r['contact_person']) ?></td><td class="px-6 py-4 text-slate-500"><?= sanitize($r['industry_sector']?:'—') ?></td>
            <td class="px-6 py-4"><select onchange="updateStatus(<?= $r['id'] ?>,this.value,'consulting')" class="text-xs font-bold px-2 py-1 rounded-lg border border-slate-200"><option value="new" <?= $r['status']==='new'?'selected':'' ?>>New</option><option value="contacted" <?= $r['status']==='contacted'?'selected':'' ?>>Contacted</option><option value="in_progress" <?= $r['status']==='in_progress'?'selected':'' ?>>In Progress</option><option value="completed" <?= $r['status']==='completed'?'selected':'' ?>>Completed</option></select></td>
            <td class="px-6 py-4 text-xs text-slate-400"><?= format_date($r['created_at']) ?></td>
            <td class="px-6 py-4 text-right"><button @click="detail=<?= htmlspecialchars(json_encode($r)) ?>;showDetail=true" class="p-2 text-brand-500 hover:bg-brand-50 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></button></td></tr>
            <?php endforeach; endif;?>
        </tbody></table></div>
    </div>
    <div x-show="showDetail" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showDetail=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <button @click="showDetail=false" class="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-xl"><i data-lucide="x" class="w-5 h-5"></i></button>
            <template x-if="detail">
                <div>
                    <h3 class="text-xl font-bold mb-1" x-text="detail.company_name"></h3>
                    <p class="text-sm text-slate-500 mb-6" x-text="detail.contact_person+' · '+detail.email"></p>
                    <div class="space-y-3">
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Phone</p><p class="text-sm font-semibold" x-text="detail.phone"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Industry Sector</p><p class="text-sm font-semibold" x-text="detail.industry_sector||'Not specified'"></p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-[10px] text-slate-400 uppercase mb-1">Description</p><p class="text-sm whitespace-pre-line" x-text="detail.description"></p></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<script>
async function updateStatus(id,status,type){const fd=new FormData();fd.append('action','update_'+type+'_status');fd.append('id',id);fd.append('status',status);await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd})}
</script>
<?php admin_page_end(); ?>
