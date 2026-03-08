<?php
/**
 * Admin — Homepage Pins Management
 * Control what appears in homepage featured sections
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$pins = db_query("SELECT * FROM homepage_pins ORDER BY sort_order ASC");
$products = db_query("SELECT id, name FROM products WHERE is_active = 1 ORDER BY name");
$programs = db_query("SELECT id, title FROM training_programs WHERE is_active = 1 ORDER BY title");
$videos = db_query("SELECT id, title FROM videos ORDER BY title");
$news_posts = db_query("SELECT id, title FROM news_posts ORDER BY title");

admin_page_start('Homepage Pins', 'Manage featured homepage content');
?>

<div x-data="pinsManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Homepage Pins</h2>
            <p class="text-sm text-slate-500">Control which content appears in featured sections on the homepage.</p>
        </div>
        <button @click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Pin Content
        </button>
    </div>

    <!-- How it Works -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-8">
        <h3 class="text-sm font-bold text-blue-800 flex items-center gap-2 mb-3"><i data-lucide="info" class="w-4 h-4"></i> How Homepage Pins Work</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs text-blue-700">
            <div class="flex items-start gap-2"><span class="font-bold text-blue-500">Video:</span> Featured in the video carousel section</div>
            <div class="flex items-start gap-2"><span class="font-bold text-blue-500">News:</span> Shown in the latest news section</div>
            <div class="flex items-start gap-2"><span class="font-bold text-blue-500">Product:</span> Highlighted in the products grid</div>
            <div class="flex items-start gap-2"><span class="font-bold text-blue-500">Training:</span> Shown in the upcoming events section</div>
        </div>
    </div>

    <!-- Pins Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Order</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Type</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Content ID</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($pins)): ?>
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">No pinned content. The homepage will use default queries.</td></tr>
                    <?php else: foreach ($pins as $p): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-slate-900">#<?= $p['sort_order'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-xs font-bold
                                <?= $p['content_type'] === 'video' ? 'bg-red-50 text-red-600' :
                                   ($p['content_type'] === 'news' ? 'bg-sky-50 text-sky-600' :
                                   ($p['content_type'] === 'product' ? 'bg-purple-50 text-purple-600' : 'bg-violet-50 text-violet-600')) ?>">
                                <?= ucfirst($p['content_type']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= $p['content_id'] ?></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="deletePin(<?= $p['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
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
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
            <h3 class="text-lg font-bold mb-6">Pin Content to Homepage</h3>
            <form @submit.prevent="save()" class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-slate-700 mb-1 block">Content Type</label>
                    <select x-model="form.content_type" class="form-input">
                        <option value="video">Video</option>
                        <option value="news">News Post</option>
                        <option value="product">Product</option>
                        <option value="training">Training Program</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 mb-1 block">Select Content</label>
                    <select x-model="form.content_id" class="form-input" x-show="form.content_type === 'video'">
                        <option value="">Select video...</option>
                        <?php foreach ($videos as $v): ?><option value="<?= $v['id'] ?>"><?= sanitize($v['title']) ?></option><?php endforeach; ?>
                    </select>
                    <select x-model="form.content_id" class="form-input" x-show="form.content_type === 'news'">
                        <option value="">Select news post...</option>
                        <?php foreach ($news_posts as $n): ?><option value="<?= $n['id'] ?>"><?= sanitize($n['title']) ?></option><?php endforeach; ?>
                    </select>
                    <select x-model="form.content_id" class="form-input" x-show="form.content_type === 'product'">
                        <option value="">Select product...</option>
                        <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?></option><?php endforeach; ?>
                    </select>
                    <select x-model="form.content_id" class="form-input" x-show="form.content_type === 'training'">
                        <option value="">Select program...</option>
                        <?php foreach ($programs as $p): ?><option value="<?= $p['id'] ?>"><?= sanitize($p['title']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 mb-1 block">Sort Order</label>
                    <input type="number" x-model="form.sort_order" class="form-input" value="1" min="1">
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="modal = false" class="px-5 py-2.5 text-sm text-slate-600 bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-purple-600 rounded-xl shadow-lg">Pin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function pinsManager() {
    return {
        modal: false,
        form: { content_type: 'video', content_id: '', sort_order: 1 },
        openModal() { this.form = { content_type: 'video', content_id: '', sort_order: 1 }; this.modal = true; },
        async save() {
            const fd = new FormData();
            fd.append('action', 'create_pin');
            Object.entries(this.form).forEach(([k, v]) => fd.append(k, v));
            const r = await fetch('<?= SITE_URL ?>/admin_api.php', { method: 'POST', body: fd });
            const d = await r.json();
            if (d.success) location.reload();
        }
    }
}
async function deletePin(id) {
    if (!confirm('Remove this pin?')) return;
    const fd = new FormData();
    fd.append('action', 'delete_pin');
    fd.append('id', id);
    await fetch('<?= SITE_URL ?>/admin_api.php', { method: 'POST', body: fd });
    location.reload();
}
</script>

<?php admin_page_end(); ?>
