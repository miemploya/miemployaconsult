<?php
/**
 * Admin — Posters & Flyers
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$posters = db_query("SELECT * FROM posters ORDER BY created_at DESC");
admin_page_start('Posters & Flyers', 'Event posters & flyers');
?>
<div x-data="posterManager()">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">Posters & Flyers</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Upload Poster</button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($posters)):?><div class="col-span-full text-center py-16 text-slate-400">No posters uploaded.</div>
        <?php else: foreach($posters as $p):?>
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all">
            <div class="h-48 bg-slate-100"><img src="<?= SITE_URL ?>/uploads/posters/<?= $p['image_path'] ?>" class="w-full h-full object-cover"></div>
            <div class="p-5">
                <h4 class="font-bold text-slate-900 mb-1"><?= sanitize($p['title']) ?></h4>
                <p class="text-xs text-slate-400 mb-3"><?= $p['event_date']?format_date($p['event_date']):'—' ?></p>
                <button @click="deleteItem(<?= $p['id'] ?>)" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg">Delete</button>
            </div>
        </div>
        <?php endforeach; endif;?>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-lg font-bold mb-6">Upload Poster</h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Event Date</label><input type="date" x-model="form.event_date" class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Registration Link</label><input type="url" x-model="form.registration_link" class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Image *</label><input type="file" @change="form.image=$event.target.files[0]" accept="image/*" required class="form-input text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-pink-50 file:text-pink-600 file:font-semibold"></div>
                <div class="flex justify-end gap-3"><button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button><button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl shadow-lg disabled:opacity-50">Upload</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function posterManager() {
    return {
        modal:false,loading:false,form:{title:'',event_date:'',registration_link:'',image:null},
        openModal(){this.form={title:'',event_date:'',registration_link:'',image:null};this.modal=true},
        async save(){this.loading=true;const fd=new FormData();fd.append('action','upload_poster');fd.append('title',this.form.title);fd.append('event_date',this.form.event_date);fd.append('registration_link',this.form.registration_link);if(this.form.image)fd.append('image',this.form.image);const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});const d=await r.json();if(d.success)location.reload();this.loading=false},
        async deleteItem(id){if(!confirm('Delete?'))return;const fd=new FormData();fd.append('action','delete_poster');fd.append('id',id);await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});location.reload()}
    }
}
</script>
<?php admin_page_end(); ?>
