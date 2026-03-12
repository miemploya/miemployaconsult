<?php
/**
 * Admin — Product Infographic Slides Manager
 * Upload product screenshots + configure frame size & slide duration
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$slidesDir = __DIR__ . '/../assets/images/slides/';
if (!is_dir($slidesDir)) mkdir($slidesDir, 0777, true);

$configFile = __DIR__ . '/../assets/images/slides/config.json';

// Load config defaults
$config = [
    'duration' => 5,       // seconds per slide
    'aspect'   => '16:9',  // frame aspect ratio
];
if (file_exists($configFile)) {
    $saved = json_decode(file_get_contents($configFile), true);
    if ($saved) $config = array_merge($config, $saved);
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_slides' && !empty($_FILES['slides']['name'][0])) {
        $count = 0;
        foreach ($_FILES['slides']['tmp_name'] as $i => $tmp) {
            if ($_FILES['slides']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['slides']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    $fname = 'slide_' . time() . '_' . $i . '.' . $ext;
                    move_uploaded_file($tmp, $slidesDir . $fname);
                    $count++;
                }
            }
        }
        $msg = "$count slide(s) uploaded!";
    }

    if ($action === 'delete_slide') {
        $file = basename($_POST['file'] ?? '');
        if ($file && $file !== 'config.json' && file_exists($slidesDir . $file)) {
            unlink($slidesDir . $file);
            $msg = 'Slide deleted.';
        }
    }

    if ($action === 'save_settings') {
        $config['duration'] = max(1, min(30, intval($_POST['duration'] ?? 5)));
        $config['aspect']   = in_array($_POST['aspect'] ?? '', ['16:9', '4:3', '21:9', '3:2', '1:1']) ? $_POST['aspect'] : '16:9';
        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
        $msg = 'Slideshow settings saved!';
    }
}

// List slides (exclude config.json)
$slides = [];
foreach (glob($slidesDir . '*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) as $f) {
    $slides[] = basename($f);
}
sort($slides);

admin_page_start('Product Slides', 'Manage product infographic carousel & settings');
?>

<div class="max-w-6xl">
    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Settings Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="settings" class="w-5 h-5 text-amber-500"></i> Slideshow Settings
                </h3>
            </div>
            <div class="p-6">
                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="save_settings">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Slide Duration (seconds)</label>
                        <input type="number" name="duration" value="<?= $config['duration'] ?>" min="1" max="30" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all text-sm">
                        <p class="text-[11px] text-slate-400 mt-1">How many seconds each slide is displayed (1–30).</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Frame Aspect Ratio</label>
                        <select name="aspect" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all text-sm">
                            <?php
                            $ratios = ['16:9' => '16:9 (Widescreen)', '4:3' => '4:3 (Standard)', '21:9' => '21:9 (Ultrawide)', '3:2' => '3:2 (Photo)', '1:1' => '1:1 (Square)'];
                            foreach ($ratios as $val => $label):
                            ?>
                            <option value="<?= $val ?>" <?= $config['aspect'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Controls the shape of the slideshow frame.</p>
                    </div>
                    <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all">
                        Save Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Upload Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="upload" class="w-5 h-5 text-indigo-500"></i> Upload Product Slides
                </h3>
                <p class="text-xs text-slate-500 mt-1">Screenshots & infographics of your products. Displayed in the dark Product Showcase section on the homepage.</p>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>These slides are <strong>separate</strong> from the Hero Slides. Hero images appear in the top-right of the homepage. These appear in the full-width Product Showcase section further down.</span>
                </div>
                <form method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-4">
                    <input type="hidden" name="action" value="upload_slides">
                    <div class="flex-1 w-full">
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Select Images (multiple)</label>
                        <input type="file" name="slides[]" accept="image/*" multiple required class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all whitespace-nowrap">
                        Upload Slides
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Current Settings Preview -->
    <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-wrap gap-6 text-sm">
        <div class="flex items-center gap-2">
            <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-500">Duration:</span>
            <span class="font-bold text-slate-700"><?= $config['duration'] ?>s</span>
        </div>
        <div class="flex items-center gap-2">
            <i data-lucide="ratio" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-500">Frame:</span>
            <span class="font-bold text-slate-700"><?= $config['aspect'] ?></span>
        </div>
        <div class="flex items-center gap-2">
            <i data-lucide="images" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-500">Slides:</span>
            <span class="font-bold text-slate-700"><?= count($slides) ?> uploaded</span>
        </div>
    </div>

    <!-- Slides Gallery -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="images" class="w-5 h-5 text-purple-500"></i> Current Slides
                <span class="text-xs text-slate-400 font-normal">(<?= count($slides) ?> files — max 10 displayed on homepage)</span>
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($slides)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="image-off" class="w-8 h-8 text-slate-300"></i>
                </div>
                <p class="text-slate-400 text-sm">No slides uploaded yet.</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <?php foreach ($slides as $idx => $slide): ?>
                <div class="group relative bg-slate-50 rounded-xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all">
                    <div class="aspect-video overflow-hidden">
                        <img src="<?= SITE_URL ?>/assets/images/slides/<?= $slide ?>?v=<?= time() ?>" alt="Slide <?= $idx + 1 ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="px-3 py-2 border-t border-slate-100 bg-white flex justify-between items-center">
                        <p class="text-[11px] font-semibold text-slate-700">Slide <?= $idx + 1 ?></p>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="delete_slide">
                            <input type="hidden" name="file" value="<?= htmlspecialchars($slide) ?>">
                            <button type="submit" onclick="return confirm('Delete this slide?')" class="w-6 h-6 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
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
