<?php
/**
 * Admin — News Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$news = db_query("SELECT * FROM news_posts ORDER BY created_at DESC");
admin_page_start('News Posts', 'Manage news & announcements');
?>
<div x-data="newsManager()">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">News Posts</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Add News</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Title</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Category</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Date</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-50">
            <?php if(empty($news)):?><tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">No news posts.</td></tr>
            <?php else: foreach($news as $n):?>
            <tr class="hover:bg-slate-50"><td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($n['title']) ?></td><td class="px-6 py-4 text-slate-500"><?= sanitize($n['category']?:'—') ?></td><td class="px-6 py-4 text-slate-500"><?= $n['publish_date']?format_date($n['publish_date']):format_date($n['created_at']) ?></td>
            <td class="px-6 py-4 text-right"><button @click="editItem(<?= htmlspecialchars(json_encode($n)) ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg"><i data-lucide="edit" class="w-4 h-4"></i></button><button @click="deleteItem(<?= $n['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button></td></tr>
            <?php endforeach; endif;?>
        </tbody></table></div>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-8">
            <h3 class="text-lg font-bold mb-6" x-text="editing?'Edit News':'Add News'"></h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description *</label><textarea x-model="form.description" rows="4" required class="form-input resize-none"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Category</label><input type="text" x-model="form.category" class="form-input" placeholder="e.g. Update, Event"></div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Publish Date</label><input type="date" x-model="form.publish_date" class="form-input"></div>
                </div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Link URL</label><input type="url" x-model="form.link_url" class="form-input"></div>
                <div class="flex justify-end gap-3"><button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button><button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-blue-600 rounded-xl shadow-lg disabled:opacity-50"><span x-text="editing?'Update':'Create'"></span></button></div>
            </form>
        </div>
    </div>
</div>
<script>
function newsManager() {
    return {
        modal:false,editing:false,loading:false,
        form:{title:'',description:'',category:'',publish_date:'',link_url:''},
        openModal(){this.editing=false;this.form={title:'',description:'',category:'',publish_date:'',link_url:''};this.modal=true},
        editItem(n){this.editing=n.id;this.form={title:n.title,description:n.description||'',category:n.category||'',publish_date:n.publish_date||'',link_url:n.link_url||''};this.modal=true},
        async save(){this.loading=true;const fd=new FormData();fd.append('action',this.editing?'update_news':'create_news');if(this.editing)fd.append('id',this.editing);Object.entries(this.form).forEach(([k,v])=>fd.append(k,v));const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});const d=await r.json();if(d.success)location.reload();this.loading=false},
        async deleteItem(id){if(!confirm('Delete?'))return;const fd=new FormData();fd.append('action','delete_news');fd.append('id',id);await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});location.reload()}
    }
}
</script>
<?php admin_page_end(); ?>
