<?php
/**
 * Miemploya Consult — Videos Gallery
 * Displays all uploaded videos (unlimited)
 */
require_once __DIR__ . '/includes/db.php';
$videos = db_query("SELECT * FROM videos ORDER BY publish_date DESC, created_at DESC");
$page_title = 'Videos';
$page_description = 'Watch our latest video content — training sessions, company updates, product walkthroughs, and more.';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="py-16 bg-gradient-to-br from-red-500/5 via-rose-500/5 to-transparent mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 rounded-full text-xs font-bold text-red-600 mb-6">
                <i data-lucide="video" class="w-3.5 h-3.5"></i> Media Gallery
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Video <span class="gradient-text">Gallery</span></h1>
            <p class="text-lg text-slate-600 leading-relaxed">Watch our latest content — training highlights, company updates, product walkthroughs, and industry insights.</p>
        </div>
    </div>
</section>

<!-- Videos Grid -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($videos)): ?>
        <div class="text-center py-20">
            <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="video-off" class="w-10 h-10 text-red-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">No Videos Yet</h3>
            <p class="text-slate-500">Check back soon — we're preparing great content for you.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($videos as $i => $video): 
                // Extract embed URL
                $embed_url = '';
                $vurl = $video['video_url'];
                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $vurl, $m)) {
                    $embed_url = 'https://www.youtube.com/embed/' . $m[1];
                } elseif (preg_match('/vimeo\.com\/(\d+)/', $vurl, $m)) {
                    $embed_url = 'https://player.vimeo.com/video/' . $m[1];
                }
            ?>
            <div class="reveal group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:border-red-200 transition-all duration-500" style="transition-delay: <?= min($i * 80, 400) ?>ms">
                <!-- Video Embed / Thumbnail -->
                <div class="relative aspect-video bg-slate-100 overflow-hidden">
                    <?php if ($embed_url): ?>
                    <iframe src="<?= $embed_url ?>" title="<?= sanitize($video['title']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                    <?php elseif ($video['thumbnail_path']): ?>
                    <a href="<?= sanitize($vurl) ?>" target="_blank" class="block w-full h-full relative">
                        <img src="<?= SITE_URL ?>/uploads/videos/<?= $video['thumbnail_path'] ?>" alt="<?= sanitize($video['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-slate-900/30 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                                <i data-lucide="play" class="w-7 h-7 text-red-600 ml-1"></i>
                            </div>
                        </div>
                    </a>
                    <?php else: ?>
                    <a href="<?= sanitize($vurl) ?>" target="_blank" class="w-full h-full bg-gradient-to-br from-red-50 to-rose-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-white/80 flex items-center justify-center shadow-lg mx-auto mb-3">
                                <i data-lucide="play" class="w-7 h-7 text-red-500 ml-1"></i>
                            </div>
                            <p class="text-xs text-red-400 font-medium">Watch Video</p>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-bold text-slate-900 line-clamp-2 mb-2"><?= sanitize($video['title']) ?></h3>
                    <?php if ($video['description']): ?>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-3"><?= sanitize($video['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">
                            <?= $video['publish_date'] ? format_date($video['publish_date']) : '' ?>
                        </span>
                        <?php if ($video['is_featured']): ?>
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full">⭐ Featured</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
