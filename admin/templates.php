<?php
/**
 * Admin — Templates Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$templates = db_query("SELECT * FROM business_templates ORDER BY category, title");
admin_page_start('Business Templates', 'Upload and manage templates');
?>
<div x-data="templateManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-black text-slate-900">Business Templates</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Upload Template</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Title</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Category</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Type</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Downloads</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-50">
            <?php if(empty($templates)):?><tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">No templates uploaded.</td></tr>
            <?php else: foreach($templates as $t):?>
            <tr class="hover:bg-slate-50"><td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($t['title']) ?></td><td class="px-6 py-4 text-slate-500"><?= ucfirst(str_replace('_',' ',$t['category'])) ?></td><td class="px-6 py-4"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-bold uppercase"><?= $t['file_type'] ?></span></td><td class="px-6 py-4 text-slate-600"><?= $t['download_count'] ?></td>
            <td class="px-6 py-4 text-right"><button @click="deleteItem(<?= $t['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button></td></tr>
            <?php endforeach; endif;?>
        </tbody></table></div>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-lg font-bold mb-6">Upload Template</h3>
            <form @submit.prevent="save()" class="space-y-4" enctype="multipart/form-data">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description</label><textarea x-model="form.description" rows="2" class="form-input resize-none"></textarea></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Category</label>
                    <select x-model="form.category" class="form-input"><option value="hr">HR</option><option value="business_development">Business Development</option><option value="finance">Finance</option><option value="audit">Audit</option><option value="legal">Legal</option><option value="proposal">Proposals</option></select></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">File *</label><input type="file" @change="form.file=$event.target.files[0]" accept=".pdf,.doc,.docx,.xls,.xlsx" required class="form-input text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-600 file:font-semibold"></div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg disabled:opacity-50">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function templateManager() {
    return {
        modal:false, loading:false, form:{title:'',description:'',category:'hr',file:null},
        openModal() { this.form={title:'',description:'',category:'hr',file:null}; this.modal=true; },
        async save() { this.loading=true; const fd=new FormData(); fd.append('action','upload_template'); fd.append('title',this.form.title); fd.append('description',this.form.description); fd.append('category',this.form.category); if(this.form.file)fd.append('file',this.form.file); const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd}); const d=await r.json(); if(d.success)location.reload(); this.loading=false; },
        async deleteItem(id) { if(!confirm('Delete?'))return; const fd=new FormData(); fd.append('action','delete_template'); fd.append('id',id); await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd}); location.reload(); }
    }
}
</script>
<?php admin_page_end(); ?>
