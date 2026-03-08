<?php
/**
 * Admin Page Boilerplate Helper
 */
function admin_page_start($title, $subtitle = '') {
    global $page_title, $page_subtitle;
    $page_title = $title;
    $page_subtitle = $subtitle ?: 'Miemploya Consult Admin';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> — <?= SITE_NAME ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                colors: { brand: {50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81'} }
            }}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <style>[x-cloak]{display:none !important;}</style>
</head>
<body class="font-sans bg-slate-50 text-slate-900 antialiased">
<div class="flex h-screen w-full">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <div class="flex-1 flex flex-col h-full overflow-hidden w-full relative">
        <?php include __DIR__ . '/admin_header.php'; ?>
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
    <?php
}

function admin_page_end() {
    ?>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/admin_scripts.php'; ?>
</body>
</html>
    <?php
}
