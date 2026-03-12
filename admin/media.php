<?php
/**
 * Admin — Media Bucket (Image Manager)
 * Upload and manage site images including the company logo
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

// Handle uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $targetDir = __DIR__ . '/../assets/images/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    if ($action === 'upload_logo' && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        copy($_FILES['logo']['tmp_name'], $targetDir . 'logo.png');
        $msg = 'Light theme logo uploaded successfully!';
    }
    if ($action === 'upload_logo_dark' && isset($_FILES['logo_dark']) && $_FILES['logo_dark']['error'] === UPLOAD_ERR_OK) {
        copy($_FILES['logo_dark']['tmp_name'], $targetDir . 'logo_dark.png');
        $msg = 'Dark theme logo uploaded successfully!';
    }
    if ($action === 'upload_image' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $name = preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        copy($_FILES['image']['tmp_name'], $targetDir . $name);
        $msg = 'Image "' . htmlspecialchars($name) . '" uploaded!';
    }
    if ($action === 'delete_image') {
        $file = basename($_POST['file']);
        if ($file !== 'logo.png' && $file !== 'logo_dark.png' && file_exists($targetDir . $file)) {
            unlink($targetDir . $file);
            $msg = 'Image deleted.';
        }
    }
}

// List existing images
$imagesDir = __DIR__ . '/../assets/images/';
$images = [];
if (is_dir($imagesDir)) {
    foreach (scandir($imagesDir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico'])) {
            $images[] = $f;
        }
    }
}

admin_page_start('Media Bucket', 'Upload & manage site images');
?>

<div class="max-w-5xl">
    <?php if (!empty($msg)): ?>
    <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Site Logo Upload -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-brand-50 to-purple-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-brand-500"></i> Site Logo (Light Theme)
            </h3>
            <p class="text-xs text-slate-500 mt-1">This logo appears on the <strong>light</strong> theme. Saved as <code class="bg-slate-100 px-1 rounded">logo.png</code>.</p>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <!-- Current Logo Preview -->
                <div class="w-48 h-32 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50 overflow-hidden">
                    <?php if (file_exists($imagesDir . 'logo.png')): ?>
                    <img src="<?= SITE_URL ?>/assets/images/logo.png?v=<?= time() ?>" alt="Current Logo" class="max-w-full max-h-full object-contain p-2">
                    <?php else: ?>
                    <div class="text-center">
                        <i data-lucide="image-off" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                        <p class="text-xs text-slate-400">No logo uploaded</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Upload Form -->
                <form method="POST" enctype="multipart/form-data" class="flex-1">
                    <input type="hidden" name="action" value="upload_logo">
                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Upload New Logo</label>
                    <p class="text-xs text-slate-400 mb-3">Accepts PNG, JPG, GIF, WEBP. Will be saved as <code class="bg-slate-100 px-1 rounded">logo.png</code>.</p>
                    <div class="flex items-center gap-3">
                        <input type="file" name="logo" accept="image/*" required class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all">
                            Upload Logo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dark Theme Logo Upload -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-800 to-slate-900">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i data-lucide="moon" class="w-5 h-5 text-amber-400"></i> Site Logo (Dark Theme)
            </h3>
            <p class="text-xs text-slate-400 mt-1">This logo appears on the <strong class="text-slate-300">dark</strong> theme. Use a version with light/white text or a transparent background. Saved as <code class="bg-slate-700 px-1 rounded text-slate-300">logo_dark.png</code>.</p>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <!-- Preview on dark background -->
                <div class="w-48 h-32 rounded-xl border-2 border-dashed border-slate-600 flex items-center justify-center bg-slate-800 overflow-hidden">
                    <?php if (file_exists($imagesDir . 'logo_dark.png')): ?>
                    <img src="<?= SITE_URL ?>/assets/images/logo_dark.png?v=<?= time() ?>" alt="Dark Logo" class="max-w-full max-h-full object-contain p-2">
                    <?php else: ?>
                    <div class="text-center">
                        <i data-lucide="image-off" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                        <p class="text-xs text-slate-500">No dark logo</p>
                        <p class="text-[10px] text-slate-600 mt-1">Will use light logo</p>
                    </div>
                    <?php endif; ?>
                </div>
                <form method="POST" enctype="multipart/form-data" class="flex-1">
                    <input type="hidden" name="action" value="upload_logo_dark">
                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Upload Dark Theme Logo</label>
                    <p class="text-xs text-slate-400 mb-3">Use a logo that looks good on dark backgrounds (white text, transparent bg, etc).</p>
                    <div class="flex items-center gap-3">
                        <input type="file" name="logo_dark" accept="image/*" required class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-600 hover:file:bg-slate-200">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-slate-700 to-slate-900 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all">
                            Upload Dark Logo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- General Image Upload -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="upload" class="w-5 h-5 text-emerald-500"></i> Upload Image
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
                <input type="hidden" name="action" value="upload_image">
                <div class="flex-1">
                    <label class="text-sm font-semibold text-slate-700 mb-2 block">Select Image</label>
                    <input type="file" name="image" accept="image/*" required class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-sm shadow-lg hover:bg-emerald-700 transition-all">
                    Upload
                </button>
            </form>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="folder-open" class="w-5 h-5 text-amber-500"></i> Image Bucket
                <span class="text-xs text-slate-400 font-normal">(<?= count($images) ?> files)</span>
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($images)): ?>
            <p class="text-center text-slate-400 py-8">No images uploaded yet.</p>
            <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach ($images as $img): ?>
                <div class="group relative bg-slate-50 rounded-xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all">
                    <div class="aspect-square flex items-center justify-center p-3">
                        <img src="<?= SITE_URL ?>/assets/images/<?= $img ?>?v=<?= time() ?>" alt="<?= htmlspecialchars($img) ?>" class="max-w-full max-h-full object-contain">
                    </div>
                    <div class="px-3 py-2 border-t border-slate-100 bg-white">
                        <p class="text-[11px] font-semibold text-slate-700 truncate"><?= htmlspecialchars($img) ?></p>
                        <p class="text-[10px] text-slate-400"><?= round(filesize($imagesDir . $img) / 1024) ?> KB</p>
                    </div>
                    <?php if ($img !== 'logo.png' && $img !== 'logo_dark.png'): ?>
                    <form method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <input type="hidden" name="action" value="delete_image">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($img) ?>">
                        <button type="submit" onclick="return confirm('Delete this image?')" class="w-7 h-7 bg-red-500 text-white rounded-lg flex items-center justify-center shadow-lg hover:bg-red-600">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php admin_page_end(); ?>
