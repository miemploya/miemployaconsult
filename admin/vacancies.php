<?php
/**
 * Admin — Job Vacancies Management
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$vacancies = db_query("SELECT * FROM job_vacancies ORDER BY created_at DESC");
admin_page_start('Job Vacancies', 'Manage job postings');
?>

<div x-data="vacancyManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Job Vacancies</h2>
            <p class="text-sm text-slate-500"><?= count($vacancies) ?> total vacancies</p>
        </div>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Vacancy
        </button>
    </div>

    <!-- Vacancies Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Title</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Company</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Type</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Deadline</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($vacancies)): ?>
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No vacancies yet. Click "Add Vacancy" to create one.</td></tr>
                    <?php else: foreach ($vacancies as $v): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($v['title']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= sanitize($v['company_name']) ?></td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded text-xs font-bold"><?= ucwords(str_replace('_',' ',$v['employment_type'])) ?></span></td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-xs font-bold <?= $v['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' ?>"><?= $v['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td class="px-6 py-4 text-slate-500"><?= $v['deadline'] ? format_date($v['deadline']) : '—' ?></td>
                        <td class="px-6 py-4 text-right">
                            <button @click="editVacancy(<?= htmlspecialchars(json_encode($v)) ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"><i data-lucide="edit" class="w-4 h-4"></i></button>
                            <button @click="deleteItem(<?= $v['id'] ?>, 'vacancy')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="modal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-8 py-5 border-b border-slate-100 flex justify-between items-center rounded-t-3xl z-10">
                <h3 class="text-lg font-bold text-slate-900" x-text="editing ? 'Edit Vacancy' : 'Add Vacancy'"></h3>
                <button @click="modal = false" class="p-2 hover:bg-slate-100 rounded-xl"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-8">
                <div x-show="formError" class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-text="formError" x-cloak></div>
                <form @submit.prevent="save()" class="space-y-4">
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Job Title *</label><input type="text" x-model="form.title" required class="form-input"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Company</label><input type="text" x-model="form.company_name" class="form-input"></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Location</label><input type="text" x-model="form.location" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Type</label>
                            <select x-model="form.employment_type" class="form-input"><option value="full_time">Full Time</option><option value="part_time">Part Time</option><option value="contract">Contract</option><option value="internship">Internship</option></select></div>
                        <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Deadline</label><input type="date" x-model="form.deadline" class="form-input"></div>
                    </div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Description *</label><textarea x-model="form.description" rows="4" required class="form-input resize-none"></textarea></div>
                    <div><label class="text-sm font-semibold text-slate-700 mb-1 block">Requirements</label><textarea x-model="form.requirements" rows="3" class="form-input resize-none"></textarea></div>
                    <div class="flex items-center gap-2"><input type="checkbox" x-model="form.is_active" id="active" class="rounded"><label for="active" class="text-sm font-medium text-slate-700">Active</label></div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="modal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Cancel</button>
                        <button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-purple-600 rounded-xl shadow-lg hover:scale-105 transition-all disabled:opacity-50">
                            <span x-show="!loading" x-text="editing ? 'Update' : 'Create'"></span><span x-show="loading" x-cloak>Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function vacancyManager() {
    return {
        modal: false, editing: false, loading: false, formError: '',
        form: { title:'', company_name:'<?= COMPANY_NAME ?>', location:'', employment_type:'full_time', deadline:'', description:'', requirements:'', is_active:true },
        openModal() { this.editing = false; this.formError = ''; this.form = { title:'', company_name:'<?= COMPANY_NAME ?>', location:'', employment_type:'full_time', deadline:'', description:'', requirements:'', is_active:true }; this.modal = true; },
        editVacancy(v) { this.editing = v.id; this.formError = ''; this.form = { title:v.title, company_name:v.company_name, location:v.location||'', employment_type:v.employment_type, deadline:v.deadline||'', description:v.description, requirements:v.requirements||'', is_active: v.is_active == 1 }; this.modal = true; },
        async save() {
            this.loading = true; this.formError = '';
            const fd = new FormData();
            fd.append('action', this.editing ? 'update_vacancy' : 'create_vacancy');
            if (this.editing) fd.append('id', this.editing);
            Object.entries(this.form).forEach(([k,v]) => fd.append(k, v === true ? 1 : (v === false ? 0 : v)));
            const r = await fetch('<?= SITE_URL ?>/admin_api.php', {method:'POST',body:fd});
            const d = await r.json();
            if (d.success) location.reload(); else this.formError = d.message || 'Error';
            this.loading = false;
        },
        async deleteItem(id, type) {
            if (!confirm('Are you sure?')) return;
            const fd = new FormData(); fd.append('action','delete_'+type); fd.append('id',id);
            await fetch('<?= SITE_URL ?>/admin_api.php', {method:'POST',body:fd});
            location.reload();
        }
    }
}
</script>

<?php admin_page_end(); ?>
