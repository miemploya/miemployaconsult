<?php
/**
 * Admin — Training Registrations Viewer
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$program_id = intval($_GET['program_id'] ?? 0);
$program = $program_id ? db_row("SELECT * FROM training_programs WHERE id = ?", [$program_id]) : null;

if ($program_id && $program) {
    $registrations = db_query("SELECT * FROM training_registrations WHERE program_id = ? ORDER BY created_at DESC", [$program_id]);
} else {
    $registrations = db_query("SELECT r.*, p.title as program_title FROM training_registrations r LEFT JOIN training_programs p ON r.program_id = p.id ORDER BY r.created_at DESC");
}

admin_page_start('Training Registrations', 'View program registrations');
?>

<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= SITE_URL ?>/admin/training.php" class="text-sm text-slate-400 hover:text-brand-600 flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Training Programs</a>
            </div>
            <h2 class="text-2xl font-black text-slate-900">
                <?= $program ? 'Registrations for: ' . sanitize($program['title']) : 'All Registrations' ?>
            </h2>
            <p class="text-sm text-slate-500"><?= count($registrations) ?> total registrations</p>
        </div>
        <?php if (!empty($registrations)): ?>
        <a href="<?= SITE_URL ?>/admin_api.php?action=export_registrations<?= $program_id ? '&program_id='.$program_id : '' ?>" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-700 font-bold rounded-xl hover:bg-emerald-100 transition-all text-sm">
            <i data-lucide="download" class="w-4 h-4"></i> Export CSV
        </a>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Name</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Phone</th>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Organization</th>
                        <?php if (!$program): ?>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Program</th>
                        <?php endif; ?>
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($registrations)): ?>
                    <tr><td colspan="<?= $program ? 5 : 6 ?>" class="px-6 py-10 text-center text-slate-400">No registrations yet.</td></tr>
                    <?php else: foreach ($registrations as $r): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?= sanitize($r['user_name']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= sanitize($r['email']) ?></td>
                        <td class="px-6 py-4 text-slate-500"><?= sanitize($r['phone'] ?: '—') ?></td>
                        <td class="px-6 py-4 text-slate-500"><?= sanitize($r['organization'] ?: '—') ?></td>
                        <?php if (!$program): ?>
                        <td class="px-6 py-4 text-slate-600"><?= sanitize($r['program_title'] ?? '—') ?></td>
                        <?php endif; ?>
                        <td class="px-6 py-4 text-xs text-slate-400"><?= format_date($r['created_at']) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php admin_page_end(); ?>
