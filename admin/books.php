<?php
/**
 * Admin — Books Manager
 * Upload and manage downloadable books/e-books
 */
require_once __DIR__ . '/../includes/auth_guard.php';
requireRole(['super_admin', 'staff_admin']);
require_once __DIR__ . '/../includes/admin_page.php';

$booksDir = __DIR__ . '/../uploads/books/';
if (!is_dir($booksDir)) mkdir($booksDir, 0777, true);

$coversDir = __DIR__ . '/../uploads/books/covers/';
if (!is_dir($coversDir)) mkdir($coversDir, 0777, true);

$metaFile = $booksDir . 'books.json';
$books = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
if (!$books) $books = [];

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_book') {
        $title   = trim($_POST['title'] ?? '');
        $author  = trim($_POST['author'] ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? 'General');

        if ($title && !empty($_FILES['bookfile']['name']) && $_FILES['bookfile']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['bookfile']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'doc', 'docx', 'epub', 'txt'])) {
                $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
                $fname = $slug . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['bookfile']['tmp_name'], $booksDir . $fname);

                // Handle cover image
                $coverName = '';
                if (!empty($_FILES['cover']['name']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                    $coverExt = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
                    if (in_array($coverExt, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                        $coverName = $slug . '_cover.' . $coverExt;
                        move_uploaded_file($_FILES['cover']['tmp_name'], $coversDir . $coverName);
                    }
                }

                $books[] = [
                    'id'       => uniqid(),
                    'title'    => $title,
                    'author'   => $author,
                    'description' => $desc,
                    'category' => $category,
                    'file'     => $fname,
                    'cover'    => $coverName,
                    'size'     => filesize($booksDir . $fname),
                    'ext'      => strtoupper($ext),
                    'uploaded' => date('Y-m-d H:i:s'),
                ];
                file_put_contents($metaFile, json_encode($books, JSON_PRETTY_PRINT));
                $msg = "\"$title\" uploaded successfully!";
            } else {
                $msg = 'Invalid file type. Allowed: PDF, DOC, DOCX, EPUB, TXT.';
            }
        }
    }

    if ($action === 'delete_book') {
        $id = $_POST['id'] ?? '';
        foreach ($books as $k => $b) {
            if ($b['id'] === $id) {
                if (file_exists($booksDir . $b['file'])) unlink($booksDir . $b['file']);
                if ($b['cover'] && file_exists($coversDir . $b['cover'])) unlink($coversDir . $b['cover']);
                unset($books[$k]);
                $books = array_values($books);
                file_put_contents($metaFile, json_encode($books, JSON_PRETTY_PRINT));
                $msg = 'Book deleted.';
                break;
            }
        }
    }
}

admin_page_start('Books', 'Upload and manage downloadable books & e-books');
?>

<div class="max-w-6xl">
    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= $msg ?>
    </div>
    <?php endif; ?>

    <!-- Upload Book -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="upload" class="w-5 h-5 text-amber-500"></i> Upload New Book
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="upload_book">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Book Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Payroll Management Guide" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Author</label>
                        <input type="text" name="author" placeholder="e.g. Empleo System" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief description of the book..." class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 text-sm resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Category</label>
                        <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 text-sm">
                            <option>General</option>
                            <option>HR & Payroll</option>
                            <option>Business Development</option>
                            <option>Audit & Compliance</option>
                            <option>Training</option>
                            <option>Technology</option>
                            <option>Leadership</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Book File (PDF, DOC, EPUB) *</label>
                        <input type="file" name="bookfile" accept=".pdf,.doc,.docx,.epub,.txt" required class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1.5 block">Cover Image (optional)</label>
                        <input type="file" name="cover" accept="image/*" class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100">
                    </div>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl text-sm shadow-lg hover:scale-105 transition-all">
                    Upload Book
                </button>
            </form>
        </div>
    </div>

    <!-- Books Library -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-orange-500"></i> Books Library
                <span class="text-xs text-slate-400 font-normal">(<?= count($books) ?> books)</span>
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($books)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="book-open" class="w-8 h-8 text-slate-300"></i>
                </div>
                <p class="text-slate-400 text-sm">No books uploaded yet.</p>
            </div>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach (array_reverse($books) as $book): ?>
                <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 hover:border-amber-200 hover:shadow-md transition-all group">
                    <!-- Cover -->
                    <div class="w-16 h-20 rounded-lg bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 flex items-center justify-center shrink-0 overflow-hidden">
                        <?php if ($book['cover'] && file_exists($coversDir . $book['cover'])): ?>
                        <img src="<?= SITE_URL ?>/uploads/books/covers/<?= $book['cover'] ?>?v=<?= time() ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                        <i data-lucide="book-open" class="w-6 h-6 text-amber-400"></i>
                        <?php endif; ?>
                    </div>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-900 text-sm"><?= sanitize($book['title']) ?></h4>
                        <?php if ($book['author']): ?>
                        <p class="text-xs text-slate-400 mt-0.5">by <?= sanitize($book['author']) ?></p>
                        <?php endif; ?>
                        <?php if ($book['description']): ?>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?= sanitize($book['description']) ?></p>
                        <?php endif; ?>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 font-semibold"><?= sanitize($book['category']) ?></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold"><?= $book['ext'] ?> · <?= round($book['size'] / 1024) ?> KB</span>
                            <span class="text-[10px] text-slate-400"><?= date('M d, Y', strtotime($book['uploaded'])) ?></span>
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="<?= SITE_URL ?>/uploads/books/<?= $book['file'] ?>" target="_blank" class="w-8 h-8 bg-brand-50 text-brand-500 rounded-lg flex items-center justify-center hover:bg-brand-500 hover:text-white transition-all" title="Download">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </a>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="delete_book">
                            <input type="hidden" name="id" value="<?= $book['id'] ?>">
                            <button type="submit" onclick="return confirm('Delete this book?')" class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
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
