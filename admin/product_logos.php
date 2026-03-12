<?php
/**
 * Admin — Product Logos
 * Upload logos for each product card (shown on card flip back face)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$logosDir = __DIR__ . '/../uploads/products/logos/';
if (!is_dir($logosDir)) mkdir($logosDir, 0777, true);

$products = db_query("SELECT id, name, slug, color_from, color_to FROM products WHERE is_active = 1 ORDER BY sort_order ASC");

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'upload_logo' && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $slug = basename($_POST['slug']);
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'])) {
            // Remove old logos for this slug
            foreach (glob($logosDir . $slug . '.*') as $old) { unlink($old); }
            $fname = $slug . '.' . $ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], $logosDir . $fname);
            $msg = 'Logo for "' . sanitize($_POST['product_name']) . '" uploaded successfully!';
        } else {
            $msg = 'Invalid file type. Use PNG, JPG, WEBP, GIF, or SVG.';
        }
    }
    if ($_POST['action'] === 'delete_logo') {
        $slug = basename($_POST['slug']);
        foreach (glob($logosDir . $slug . '.*') as $old) { unlink($old); }
        $msg = 'Logo removed.';
    }
}

// Helper: find logo for a slug
function findProductLogo($logosDir, $slug) {
    foreach (['png','jpg','jpeg','webp','gif','svg'] as $ext) {
        if (file_exists($logosDir . $slug . '.' . $ext)) return $slug . '.' . $ext;
    }
    return null;
}

admin_page_start('Product Logos', 'Upload logos for product card back-face display');
?>

<div class="max-w-5xl">
    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-indigo-500"></i> Product Card Logos
            </h3>
            <p class="text-xs text-slate-500 mt-1">These logos appear on the <strong>back face</strong> of product cards when they flip every 10 seconds on the homepage.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($products as $p):
            $logo = findProductLogo($logosDir, $p['slug']);
        ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3" style="background: linear-gradient(135deg, <?= $p['color_from'] ?>10, <?= $p['color_to'] ?>08);">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow" style="background: linear-gradient(135deg, <?= $p['color_from'] ?>, <?= $p['color_to'] ?>);">
                    <i data-lucide="box" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm"><?= sanitize($p['name']) ?></h4>
                    <p class="text-[10px] text-slate-400">Slug: <?= sanitize($p['slug']) ?></p>
                </div>
            </div>
            <div class="p-5">
                <!-- Current Logo Preview -->
                <div class="mb-4 flex items-center gap-4">
                    <div class="w-24 h-24 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50 overflow-hidden shrink-0">
                        <?php if ($logo): ?>
                        <img src="<?= SITE_URL ?>/uploads/products/logos/<?= $logo ?>?v=<?= time() ?>" alt="Logo" class="max-w-full max-h-full object-contain p-1">
                        <?php else: ?>
                        <div class="text-center">
                            <i data-lucide="image-off" class="w-6 h-6 text-slate-300 mx-auto"></i>
                            <p class="text-[9px] text-slate-400 mt-1">No logo</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($logo): ?>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="delete_logo">
                        <input type="hidden" name="slug" value="<?= $p['slug'] ?>">
                        <button type="submit" onclick="return confirm('Remove this logo?')" class="px-3 py-1.5 text-xs font-semibold text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <i data-lucide="trash-2" class="w-3 h-3 inline"></i> Remove
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <!-- Upload -->
                <form method="POST" enctype="multipart/form-data" class="flex items-end gap-3">
                    <input type="hidden" name="action" value="upload_logo">
                    <input type="hidden" name="slug" value="<?= $p['slug'] ?>">
                    <input type="hidden" name="product_name" value="<?= sanitize($p['name']) ?>">
                    <div class="flex-1">
                        <input type="file" name="logo" accept="image/*" required class="w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    </div>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white rounded-xl shadow" style="background: linear-gradient(135deg, <?= $p['color_from'] ?>, <?= $p['color_to'] ?>);">
                        Upload
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php admin_page_end(); ?>
