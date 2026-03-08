<?php
/**
 * Admin — Training Programs
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$programs = db_query("SELECT * FROM training_programs ORDER BY program_date DESC, created_at DESC");
admin_page_start('Training Programs', 'Manage training & conferences');
?>
<div x-data="trainingManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-black text-slate-900">Training Programs</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Add Program</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Title</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Date</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Fee</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Registrations</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Status</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($programs)): ?><tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No programs yet.</td></tr>
                    <?php else: foreach ($programs as $p): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($p['title']) ?></td>
                        <td class="px-6 py-4 text-slate-500"><?= $p['program_date'] ? format_date($p['program_date']) : 'TBA' ?></td>
                        <td class="px-6 py-4"><?= $p['fee'] > 0 ? '₦'.number_format($p['fee']) : '<span class="text-emerald-600 font-bold">Free</span>' ?></td>
                        <td class="px-6 py-4"><?= $p['registrations_count'] ?><?= $p['registration_limit'] ? '/'.$p['registration_limit'] : '' ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-xs font-bold <?= $p['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' ?>"><?= $p['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td class="px-6 py-4 text-right">
                            <button @click="editItem(<?= htmlspecialchars(json_encode($p)) ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg"><i data-lucide="edit" class="w-4 h-4"></i></button>
                            <button @click="deleteItem(<?= $p['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-8 py-5 border-b border-slate-100 flex justify-between items-center rounded-t-3xl z-10">
                <h3 class="text-lg font-bold" x-text="editing?'Edit Program':'Add Program'"></h3>
                <button @click="modal=false" class="p-2 hover:bg-slate-100 rounded-xl"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-8">
                <form @submit.prevent="save()" class="space-y-4">
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description</label><textarea x-model="form.description" rows="3" class="form-input resize-none"></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Date</label><input type="date" x-model="form.program_date" class="form-input"></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Venue</label><input type="text" x-model="form.venue" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Fee (₦)</label><input type="number" x-model="form.fee" class="form-input" step="0.01"></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Registration Limit</label><input type="number" x-model="form.registration_limit" class="form-input" placeholder="0 = unlimited"></div>
                    </div>
                    <div class="flex items-center gap-2"><input type="checkbox" x-model="form.is_active" id="prog_active" class="rounded"><label for="prog_active" class="text-sm font-medium text-slate-700">Active</label></div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="modal=false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl">Cancel</button>
                        <button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl shadow-lg disabled:opacity-50"><span x-show="!loading" x-text="editing?'Update':'Create'"></span><span x-show="loading" x-cloak>Saving...</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function trainingManager() {
    return {
        modal:false, editing:false, loading:false,
        form: {title:'',description:'',program_date:'',venue:'',fee:0,registration_limit:0,is_active:true},
        openModal() { this.editing=false; this.form={title:'',description:'',program_date:'',venue:'',fee:0,registration_limit:0,is_active:true}; this.modal=true; },
        editItem(p) { this.editing=p.id; this.form={title:p.title,description:p.description||'',program_date:p.program_date||'',venue:p.venue||'',fee:p.fee||0,registration_limit:p.registration_limit||0,is_active:p.is_active==1}; this.modal=true; },
        async save() { this.loading=true; const fd=new FormData(); fd.append('action',this.editing?'update_training':'create_training'); if(this.editing)fd.append('id',this.editing); Object.entries(this.form).forEach(([k,v])=>fd.append(k,v===true?1:(v===false?0:v))); const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd}); const d=await r.json(); if(d.success)location.reload(); this.loading=false; },
        async deleteItem(id) { if(!confirm('Delete?'))return; const fd=new FormData(); fd.append('action','delete_training'); fd.append('id',id); await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd}); location.reload(); }
    }
}
</script>
<?php admin_page_end(); ?>
