<?php
/**
 * Admin — Videos Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$videos = db_query("SELECT * FROM videos ORDER BY created_at DESC");
admin_page_start('Videos', 'Manage video content');
?>
<div x-data="videoManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-black text-slate-900">Videos</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Add Video</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($videos)):?><div class="col-span-full text-center py-16 text-slate-400">No videos added yet.</div>
        <?php else: foreach($videos as $v):?>
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all">
            <div class="h-40 bg-slate-100 flex items-center justify-center">
                <?php if($v['thumbnail_path']):?><img src="<?= SITE_URL ?>/uploads/videos/<?= $v['thumbnail_path'] ?>" class="w-full h-full object-cover">
                <?php else:?><i data-lucide="video" class="w-10 h-10 text-slate-300"></i><?php endif;?>
            </div>
            <div class="p-5">
                <h4 class="font-bold text-slate-900 mb-1"><?= sanitize($v['title']) ?></h4>
                <p class="text-xs text-slate-400 mb-3"><?= $v['publish_date']?format_date($v['publish_date']):'—' ?> · <?= $v['is_featured']?'⭐ Featured':'' ?></p>
                <div class="flex gap-2">
                    <button @click="editItem(<?= htmlspecialchars(json_encode($v)) ?>)" class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg">Edit</button>
                    <button @click="deleteItem(<?= $v['id'] ?>)" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg">Delete</button>
                </div>
            </div>
        </div>
        <?php endforeach; endif;?>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-lg font-bold mb-6" x-text="editing?'Edit Video':'Add Video'"></h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Video URL * <span class="text-xs text-slate-400">(YouTube/Vimeo)</span></label><input type="url" x-model="form.video_url" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description</label><textarea x-model="form.description" rows="2" class="form-input resize-none"></textarea></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Publish Date</label><input type="date" x-model="form.publish_date" class="form-input"></div>
                <div class="flex items-center gap-2"><input type="checkbox" x-model="form.is_featured" id="vid_feat" class="rounded"><label for="vid_feat" class="text-sm font-medium text-slate-700">Featured</label></div>
                <div class="flex justify-end gap-3"><button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button><button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-red-500 to-rose-600 rounded-xl shadow-lg disabled:opacity-50"><span x-text="editing?'Update':'Add'"></span></button></div>
            </form>
        </div>
    </div>
</div>
<script>
function videoManager() {
    return {
        modal:false,editing:false,loading:false,
        form:{title:'',video_url:'',description:'',publish_date:'',is_featured:false},
        openModal(){this.editing=false;this.form={title:'',video_url:'',description:'',publish_date:'',is_featured:false};this.modal=true},
        editItem(v){this.editing=v.id;this.form={title:v.title,video_url:v.video_url,description:v.description||'',publish_date:v.publish_date||'',is_featured:v.is_featured==1};this.modal=true},
        async save(){this.loading=true;const fd=new FormData();fd.append('action',this.editing?'update_video':'create_video');if(this.editing)fd.append('id',this.editing);Object.entries(this.form).forEach(([k,v])=>fd.append(k,v===true?1:(v===false?0:v)));const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});const d=await r.json();if(d.success)location.reload();this.loading=false},
        async deleteItem(id){if(!confirm('Delete?'))return;const fd=new FormData();fd.append('action','delete_video');fd.append('id',id);await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});location.reload()}
    }
}
</script>
<?php admin_page_end(); ?>
