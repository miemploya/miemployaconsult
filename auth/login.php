<?php
/**
 * Miemploya Consult — Login Page
 */
require_once __DIR__ . '/../includes/auth_guard.php';

// If already logged in, redirect
if (isLoggedIn()) {
    $user = currentUser();
    if (isAdmin()) {
        redirect(SITE_URL . '/admin/index.php');
    }
    redirect(SITE_URL . '/index.php');
}

// Handle login POST
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (attemptLogin($email, $password)) {
        if (isAdmin()) {
            redirect(SITE_URL . '/admin/index.php');
        }
        redirect(SITE_URL . '/index.php');
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .mesh-bg {
            background: 
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(99,102,241,0.08), transparent),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(168,85,247,0.06), transparent),
                radial-gradient(ellipse 50% 60% at 50% 80%, rgba(59,130,246,0.05), transparent);
        }
        @keyframes float { 0%,100%{transform:translateY(0px)} 50%{transform:translateY(-20px)} }
        .float-animate { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="font-sans bg-slate-50 mesh-bg min-h-screen flex items-center justify-center px-4">

    <!-- Floating Decorations -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-brand-400/10 rounded-full blur-3xl float-animate"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-400/10 rounded-full blur-3xl float-animate" style="animation-delay:2s"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-blue-400/8 rounded-full blur-3xl float-animate" style="animation-delay:4s"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Logo & Brand -->
        <div class="text-center mb-8">
            <a href="<?= SITE_URL ?>" class="inline-flex items-center gap-3 group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-xl shadow-brand-500/30 group-hover:scale-110 transition-transform">
                    <span class="text-white font-black text-xl">MC</span>
                </div>
                <div class="text-left">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight"><?= SITE_NAME ?></h1>
                    <p class="text-xs text-slate-500 font-medium tracking-wide"><?= SITE_TAGLINE ?></p>
                </div>
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-slate-200/50 border border-white/50 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-brand-500/10 via-purple-500/5 to-transparent px-8 py-6 border-b border-slate-100/50">
                <h2 class="text-xl font-bold text-slate-900">Welcome Back</h2>
                <p class="text-sm text-slate-500 mt-1">Sign in to your account</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= $error ?>
                </div>
                <?php endif; ?>

                <?php if ($msg = flash('success')): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= $msg ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            Email Address
                        </label>
                        <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all"
                               placeholder="you@company.com">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            Password
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition-all"
                               placeholder="••••••••">
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-gradient-to-r from-brand-500 to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all text-sm tracking-wide">
                        Sign In
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-500">
                        Don't have an account? 
                        <a href="register.php" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Create Account</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; <?= date('Y') ?> <?= COMPANY_NAME ?>. All rights reserved.
        </p>
    </div>

</body>
</html>
