<?php
/**
 * Books — Public Download Page
 * Browse and download books uploaded by admin
 */
$page_title = 'Books';
$page_description = 'Download free books, guides, and resources from Miemploya Consult.';
require_once __DIR__ . '/includes/header.php';

$booksFile = __DIR__ . '/uploads/books/books.json';
$books = file_exists($booksFile) ? json_decode(file_get_contents($booksFile), true) : [];
if (!$books) $books = [];

// Filter by category
$categories = array_unique(array_column($books, 'category'));
sort($categories);
$filterCat = $_GET['category'] ?? '';
$filteredBooks = $filterCat ? array_filter($books, fn($b) => $b['category'] === $filterCat) : $books;
$filteredBooks = array_reverse($filteredBooks);
?>

<!-- Hero Banner -->
<section class="py-16 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-500/10 rounded-full text-xs font-bold text-amber-600 dark:text-amber-400 mb-4">
                <i data-lucide="book-open" class="w-3.5 h-3.5"></i> Resource Library
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-4">
                Our <span class="gradient-text">Books & Guides</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
                Download professional books, guides, and resources covering HR, payroll, business development, and more.
            </p>
        </div>
    </div>
</section>

<!-- Category Filter -->
<?php if (!empty($categories)): ?>
<section class="py-6 bg-white dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 sticky top-16 lg:top-20 z-30 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-2 justify-center">
            <a href="<?= SITE_URL ?>/books.php" class="px-4 py-2 rounded-full text-sm font-semibold transition-all <?= !$filterCat ? 'bg-gradient-to-r from-brand-500 to-purple-600 text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' ?>">
                All
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= SITE_URL ?>/books.php?category=<?= urlencode($cat) ?>" class="px-4 py-2 rounded-full text-sm font-semibold transition-all <?= $filterCat === $cat ? 'bg-gradient-to-r from-brand-500 to-purple-600 text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' ?>">
                <?= sanitize($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Books Grid -->
<section class="py-16 bg-slate-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($filteredBooks)): ?>
        <div class="text-center py-20">
            <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="book-open" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-500 dark:text-slate-400 mb-2">No books yet</h3>
            <p class="text-sm text-slate-400 dark:text-slate-500">Check back soon for new resources and guides.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($filteredBooks as $book): ?>
            <div class="reveal group bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-500">
                <!-- Cover -->
                <div class="aspect-[3/4] bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-500/5 dark:to-orange-500/5 flex items-center justify-center overflow-hidden relative">
                    <?php if ($book['cover'] && file_exists(__DIR__ . '/uploads/books/covers/' . $book['cover'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/books/covers/<?= $book['cover'] ?>" alt="<?= sanitize($book['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="text-center p-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i data-lucide="book-open" class="w-8 h-8 text-white"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400 dark:text-slate-500"><?= $book['ext'] ?></p>
                    </div>
                    <?php endif; ?>
                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-bold bg-white/90 dark:bg-slate-800/90 text-amber-600 dark:text-amber-400 backdrop-blur shadow">
                        <?= sanitize($book['category']) ?>
                    </span>
                </div>
                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-snug mb-1 line-clamp-2"><?= sanitize($book['title']) ?></h3>
                    <?php if ($book['author']): ?>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">by <?= sanitize($book['author']) ?></p>
                    <?php endif; ?>
                    <?php if ($book['description']): ?>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-3"><?= sanitize($book['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500"><?= $book['ext'] ?> · <?= round($book['size'] / 1024) ?> KB</span>
                        <a href="<?= SITE_URL ?>/uploads/books/<?= $book['file'] ?>" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-xs font-bold rounded-xl shadow hover:scale-105 transition-all">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
