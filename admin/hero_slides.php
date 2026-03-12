<?php
/**
 * Admin — Hero Slides Manager
 * Upload and manage hero section slideshow images (separate from product infographic slides)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$heroDir = __DIR__ . '/../assets/images/hero/';
if (!is_dir($heroDir)) mkdir($heroDir, 0777, true);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_hero' && !empty($_FILES['hero']['name'][0])) {
        $count = 0;
        foreach ($_FILES['hero']['tmp_name'] as $i => $tmp) {
            if ($_FILES['hero']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['hero']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    $fname = 'hero_' . time() . '_' . $i . '.' . $ext;
                    move_uploaded_file($tmp, $heroDir . $fname);
                    $count++;
                }
            }
        }
        $msg = "$count hero image(s) uploaded!";
    }
    if ($action === 'delete_hero') {
        $file = basename($_POST['file'] ?? '');
        if ($file && file_exists($heroDir . $file)) {
            unlink($heroDir . $file);
            $msg = 'Hero image deleted.';
        }
    }
}

$heroImages = [];
foreach (glob($heroDir . '*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) as $f) {
    $heroImages[] = basename($f);
}
sort($heroImages);

admin_page_start('Hero Slides', 'Manage the homepage hero section slideshow');
?>

<div class="max-w-5xl">
    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Info -->
    <div class="p-4 mb-6 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-sm flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold">Hero Section Slideshow</p>
            <p class="text-xs mt-1">These images appear in the top-right area of the homepage hero section. They auto-rotate every 4 seconds. Upload up to 10 images. This is <strong>separate</strong> from the Product Infographic slideshow further down the page.</p>
        </div>
    </div>

    <!-- Upload -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-cyan-50 to-blue-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="upload" class="w-5 h-5 text-cyan-500"></i> Upload Hero Images
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-4">
                <input type="hidden" name="action" value="upload_hero">
                <div class="flex-1 w-full">
                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Select Images (multiple)</label>
                    <input type="file" name="hero[]" accept="image/*" multiple required class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-600 hover:file:bg-cyan-100">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all whitespace-nowrap">
                    Upload
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="images" class="w-5 h-5 text-blue-500"></i> Current Hero Images
                <span class="text-xs text-slate-400 font-normal">(<?= count($heroImages) ?> uploaded)</span>
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($heroImages)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="image-off" class="w-8 h-8 text-slate-300"></i>
                </div>
                <p class="text-slate-400 text-sm">No hero images yet.</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($heroImages as $idx => $img): ?>
                <div class="group relative bg-slate-50 rounded-xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="<?= SITE_URL ?>/assets/images/hero/<?= $img ?>?v=<?= time() ?>" alt="Hero <?= $idx + 1 ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="px-3 py-2 border-t border-slate-100 bg-white flex justify-between items-center">
                        <p class="text-[11px] font-semibold text-slate-700">Hero <?= $idx + 1 ?></p>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="delete_hero">
                            <input type="hidden" name="file" value="<?= htmlspecialchars($img) ?>">
                            <button type="submit" onclick="return confirm('Delete?')" class="w-6 h-6 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php admin_page_end(); ?>
