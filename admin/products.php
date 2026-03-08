<?php
/**
 * Admin — Products Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$products = db_query("SELECT * FROM products ORDER BY sort_order ASC");
admin_page_start('Products', 'Manage digital products');
?>
<div x-data="productManager()">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">Digital Products</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Add Product</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Name</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Tagline</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Link</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Status</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-50">
            <?php foreach($products as $p):?>
            <tr class="hover:bg-slate-50"><td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($p['name']) ?></td><td class="px-6 py-4 text-slate-500"><?= sanitize($p['tagline']) ?></td><td class="px-6 py-4"><?php if($p['external_link'] && $p['external_link']!=='#'):?><a href="<?= $p['external_link'] ?>" target="_blank" class="text-brand-600 text-xs font-bold">Visit →</a><?php else:?>—<?php endif;?></td><td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-xs font-bold <?= $p['is_active']?'bg-emerald-50 text-emerald-600':'bg-slate-100 text-slate-500' ?>"><?= $p['is_active']?'Active':'Inactive' ?></span></td>
            <td class="px-6 py-4 text-right"><button @click="editItem(<?= htmlspecialchars(json_encode($p)) ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg"><i data-lucide="edit" class="w-4 h-4"></i></button></td></tr>
            <?php endforeach;?>
        </tbody></table></div>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8">
            <h3 class="text-lg font-bold mb-6" x-text="editing?'Edit Product':'Add Product'"></h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div class="grid grid-cols-2 gap-4"><div><label class="text-sm font-semibold text-slate-700 mb-1 block">Name *</label><input type="text" x-model="form.name" required class="form-input"></div><div><label class="text-sm font-semibold text-slate-700 mb-1 block">Tagline</label><input type="text" x-model="form.tagline" class="form-input"></div></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description</label><textarea x-model="form.description" rows="3" class="form-input resize-none"></textarea></div>
                <div class="grid grid-cols-2 gap-4"><div><label class="text-sm font-semibold text-slate-700 mb-1 block">External Link</label><input type="url" x-model="form.external_link" class="form-input"></div><div><label class="text-sm font-semibold text-slate-700 mb-1 block">Icon</label><input type="text" x-model="form.icon_class" class="form-input" placeholder="e.g. box"></div></div>
                <div class="grid grid-cols-2 gap-4"><div><label class="text-sm font-semibold text-slate-700 mb-1 block">Color From</label><input type="color" x-model="form.color_from" class="form-input h-10"></div><div><label class="text-sm font-semibold text-slate-700 mb-1 block">Color To</label><input type="color" x-model="form.color_to" class="form-input h-10"></div></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Features <span class="text-xs text-slate-400">(one per line)</span></label><textarea x-model="form.features_text" rows="4" class="form-input resize-none" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea></div>
                <div class="flex items-center gap-2"><input type="checkbox" x-model="form.is_active" id="prod_active" class="rounded"><label for="prod_active" class="text-sm font-medium text-slate-700">Active</label></div>
                <div class="flex justify-end gap-3"><button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button><button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-violet-600 rounded-xl shadow-lg disabled:opacity-50"><span x-text="editing?'Update':'Create'"></span></button></div>
            </form>
        </div>
    </div>
</div>
<script>
function productManager() {
    return {
        modal:false,editing:false,loading:false,
        form:{name:'',tagline:'',description:'',external_link:'',icon_class:'box',color_from:'#6366f1',color_to:'#a855f7',features_text:'',is_active:true},
        openModal(){this.editing=false;this.form={name:'',tagline:'',description:'',external_link:'',icon_class:'box',color_from:'#6366f1',color_to:'#a855f7',features_text:'',is_active:true};this.modal=true},
        editItem(p){this.editing=p.id;let feats=[];try{feats=JSON.parse(p.features)||[];}catch(e){}this.form={name:p.name,tagline:p.tagline||'',description:p.description||'',external_link:p.external_link||'',icon_class:p.icon_class||'box',color_from:p.color_from||'#6366f1',color_to:p.color_to||'#a855f7',features_text:feats.join('\n'),is_active:p.is_active==1};this.modal=true},
        async save(){this.loading=true;const fd=new FormData();fd.append('action',this.editing?'update_product':'create_product');if(this.editing)fd.append('id',this.editing);Object.entries(this.form).forEach(([k,v])=>{if(k==='features_text'){fd.append('features',JSON.stringify(v.split('\n').filter(l=>l.trim())));}else{fd.append(k,v===true?1:(v===false?0:v));}});const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});const d=await r.json();if(d.success)location.reload();this.loading=false}
    }
}
</script>
<?php admin_page_end(); ?>
