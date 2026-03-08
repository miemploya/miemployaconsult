<?php
/**
 * Admin — User Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin']);
require_once __DIR__ . '/../includes/admin_page.php';
$users = db_query("SELECT id, name, email, phone, role, is_active, created_at FROM users ORDER BY created_at DESC");
admin_page_start('User Management', 'Manage platform users');
?>
<div x-data="userManager()">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">Users</h2>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-slate-600 to-slate-800 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm"><i data-lucide="plus" class="w-4 h-4"></i> Add User</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-100"><tr><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Name</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Email</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Role</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Status</th><th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Joined</th><th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-50">
            <?php foreach($users as $u):?>
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($u['name']) ?></td>
                <td class="px-6 py-4 text-slate-500"><?= sanitize($u['email']) ?></td>
                <td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-xs font-bold <?= $u['role']==='super_admin'?'bg-purple-50 text-purple-600':($u['role']==='staff_admin'?'bg-blue-50 text-blue-600':'bg-slate-100 text-slate-500') ?>"><?= ucwords(str_replace('_',' ',$u['role'])) ?></span></td>
                <td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-xs font-bold <?= $u['is_active']?'bg-emerald-50 text-emerald-600':'bg-red-50 text-red-600' ?>"><?= $u['is_active']?'Active':'Inactive' ?></span></td>
                <td class="px-6 py-4 text-xs text-slate-400"><?= format_date($u['created_at']) ?></td>
                <td class="px-6 py-4 text-right">
                    <button @click="editUser(<?= htmlspecialchars(json_encode($u)) ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg"><i data-lucide="edit" class="w-4 h-4"></i></button>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody></table></div>
    </div>
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal=false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-lg font-bold mb-6" x-text="editing?'Edit User':'Add User'"></h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Name *</label><input type="text" x-model="form.name" required class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Email *</label><input type="email" x-model="form.email" required class="form-input"></div>
                <div x-show="!editing"><label class="text-sm font-semibold text-slate-700 mb-1 block">Password *</label><input type="password" x-model="form.password" :required="!editing" class="form-input"></div>
                <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Role</label>
                    <select x-model="form.role" class="form-input"><option value="user">User</option><option value="staff_admin">Staff Admin</option><option value="super_admin">Super Admin</option></select></div>
                <div class="flex items-center gap-2"><input type="checkbox" x-model="form.is_active" id="user_active" class="rounded"><label for="user_active" class="text-sm font-medium text-slate-700">Active</label></div>
                <div class="flex justify-end gap-3"><button type="button" @click="modal=false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button><button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-slate-600 to-slate-800 rounded-xl shadow-lg disabled:opacity-50"><span x-text="editing?'Update':'Create'"></span></button></div>
            </form>
        </div>
    </div>
</div>
<script>
function userManager() {
    return {
        modal:false,editing:false,loading:false,
        form:{name:'',email:'',password:'',role:'user',is_active:true},
        openModal(){this.editing=false;this.form={name:'',email:'',password:'',role:'user',is_active:true};this.modal=true},
        editUser(u){this.editing=u.id;this.form={name:u.name,email:u.email,password:'',role:u.role,is_active:u.is_active==1};this.modal=true},
        async save(){this.loading=true;const fd=new FormData();fd.append('action',this.editing?'update_user':'create_user');if(this.editing)fd.append('id',this.editing);Object.entries(this.form).forEach(([k,v])=>fd.append(k,v===true?1:(v===false?0:v)));const r=await fetch('<?= SITE_URL ?>/admin_api.php',{method:'POST',body:fd});const d=await r.json();if(d.success)location.reload();this.loading=false}
    }
}
</script>
<?php admin_page_end(); ?>
