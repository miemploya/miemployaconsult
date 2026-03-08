<?php
/**
 * Miemploya Consult — News & Media
 */
require_once __DIR__ . '/includes/db.php';
$news = db_query("SELECT * FROM news_posts ORDER BY publish_date DESC, created_at DESC");
$posters = db_query("SELECT * FROM posters ORDER BY event_date DESC, created_at DESC LIMIT 6");
$videos = db_query("SELECT * FROM videos ORDER BY publish_date DESC, created_at DESC LIMIT 6");
$page_title = 'News & Media';
include __DIR__ . '/includes/header.php';
?>

<!-- News Section -->
<section class="py-20 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-sky-50 rounded-full text-xs font-bold text-sky-600 mb-4">
                <i data-lucide="newspaper" class="w-3.5 h-3.5"></i> Latest Updates
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">News & <span class="gradient-text">Media</span></h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Stay updated with the latest news, events, and media from Miemploya Consult.</p>
        </div>

        <?php if (empty($news) && empty($posters) && empty($videos)): ?>
        <div class="text-center py-16 reveal">
            <div class="w-20 h-20 rounded-3xl bg-sky-50 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="newspaper" class="w-10 h-10 text-sky-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">No Content Yet</h3>
            <p class="text-slate-500">News, posters, and videos will appear here as they are published.</p>
        </div>
        <?php endif; ?>

        <?php if (!empty($news)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            <?php foreach ($news as $i => $post): ?>
            <div class="reveal group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all" style="transition-delay: <?= $i * 80 ?>ms">
                <?php if ($post['image_path']): ?>
                <div class="h-48 bg-slate-100 overflow-hidden">
                    <img src="<?= SITE_URL ?>/uploads/news/<?= $post['image_path'] ?>" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <?php else: ?>
                <div class="h-48 bg-gradient-to-br from-brand-50 to-purple-50 flex items-center justify-center">
                    <i data-lucide="newspaper" class="w-12 h-12 text-brand-200"></i>
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <?php if ($post['category']): ?>
                        <span class="px-2 py-0.5 bg-brand-50 text-brand-600 text-[10px] font-bold uppercase rounded"><?= sanitize($post['category']) ?></span>
                        <?php endif; ?>
                        <span class="text-xs text-slate-400"><i data-lucide="clock" class="w-3 h-3 inline"></i> <?= $post['publish_date'] ? format_date($post['publish_date']) : format_date($post['created_at']) ?></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2"><?= sanitize($post['title']) ?></h3>
                    <p class="text-sm text-slate-500 line-clamp-3"><?= sanitize($post['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Posters & Flyers -->
        <?php if (!empty($posters)): ?>
        <div class="mb-16">
            <h2 class="text-2xl font-black text-slate-900 mb-8 reveal">Posters & <span class="gradient-text">Flyers</span></h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($posters as $i => $poster): ?>
                <div class="reveal bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all" style="transition-delay: <?= $i * 80 ?>ms">
                    <div class="h-64 bg-slate-100 overflow-hidden">
                        <img src="<?= SITE_URL ?>/uploads/posters/<?= $poster['image_path'] ?>" alt="" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-slate-900 mb-1"><?= sanitize($poster['title']) ?></h4>
                        <?php if ($poster['event_date']): ?>
                        <p class="text-xs text-slate-400 mb-2"><i data-lucide="calendar" class="w-3 h-3 inline"></i> <?= format_date($poster['event_date']) ?></p>
                        <?php endif; ?>
                        <?php if ($poster['registration_link']): ?>
                        <a href="<?= $poster['registration_link'] ?>" target="_blank" class="text-xs font-bold text-brand-600 hover:text-brand-700">Register →</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Videos -->
        <?php if (!empty($videos)): ?>
        <div>
            <h2 class="text-2xl font-black text-slate-900 mb-8 reveal">Video <span class="gradient-text">Gallery</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($videos as $i => $video): ?>
                <div class="reveal group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all" style="transition-delay: <?= $i * 80 ?>ms">
                    <div class="relative h-48 bg-slate-100">
                        <?php if ($video['thumbnail_path']): ?>
                        <img src="<?= SITE_URL ?>/uploads/videos/<?= $video['thumbnail_path'] ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-rose-50 flex items-center justify-center"><i data-lucide="play-circle" class="w-12 h-12 text-red-200"></i></div>
                        <?php endif; ?>
                        <a href="<?= $video['video_url'] ?>" target="_blank" class="absolute inset-0 bg-slate-900/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg"><i data-lucide="play" class="w-6 h-6 text-red-600 ml-1"></i></div>
                        </a>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-slate-900 mb-1 line-clamp-2"><?= sanitize($video['title']) ?></h4>
                        <p class="text-xs text-slate-400"><?= $video['publish_date'] ? format_date($video['publish_date']) : '' ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
